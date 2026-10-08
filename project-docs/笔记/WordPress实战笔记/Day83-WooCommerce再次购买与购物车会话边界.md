---
类型: WordPress实战学习笔记
项目: DentAll WooCommerce
日期: 2026-10-08
工作日: Day83
主题: WooCommerce再次购买与购物车会话边界
状态: 隔离Local HTTP与Cart会话复演完成；重购恢复P2及目标环境待验
掌握度: 初识
验证环境: WooCommerce 11.0.0、DentAll Core 0.7.1、DentAll子主题0.47.1；隔离Woo CRUD/WP-CLI与Chrome HTTP四宽
tags:
  - DentAll
  - WordPress实战
  - 购物车会话
---

# Day83 WordPress实战：再次购买与购物车会话边界

## 相关笔记

- 学习索引：[[WordPress实战笔记索引]]
- 对应项目笔记：[[../Day83-订单中心与再次购买暂缓]]
- 前置学习笔记：[[Day82-WooCommerce默认地址与订单快照]]、[[Day79-WooCommerce账户身份与订单归属]]
- 后续学习笔记：[[Day84-WooCommerce账户链与重置密钥]]
- 关联流程：[[Day75-WooCommerce报价过期与订单生命周期]]

## 今日学习成果

项目已取得以下可复演证据；它不表示用户本人已独立操作或通过费曼自测，掌握度仍为“初识”。

- 从Woo 11源码区分重购按钮Filter与Cart会话重装的执行阶段。
- 用隔离HTTP、同一Cookie及两件商品的非空Cart证明B方案在四类直链下保留商品键、数量、优惠券与金额。
- 结合原生订单HTML、A/B/C/Guest归属与四宽截图，定位手机订单号隐藏和长商品名窄列的责任边界。

## 真实项目场景与整体模型

DentAll的客户可能已把商品放进Cart并取得人工运费报价。Woo 11原生“Order again”在满足条件后可把旧订单商品装入Cart，默认行为还会清空原Cart。用户批准第一版暂缓这项能力，因此必须同时处理原生按钮和仍可访问的带Nonce直链。D83订单列表、详情及Cart和D84非付款链均已在隔离Local完成技术验收；目标环境另验。

**一句话模型：** 订单页不展示入口只能阻止正常点击；要保护已装商品，还须在Woo处理重购查询并改写Cart会话之前拦截请求，最后用真实会话快照验证。

记忆宫殿：商店橱窗的“按上次清单装篮”招牌对应订单详情的按钮；收银台后台收到旧清单对应`order_again`请求；顾客手里的篮子对应Woo Cart会话。拆掉招牌不会让旧清单失效，后台仍可能按清单重装篮子。真实机制分别是`woocommerce_order_again_button()`、查询参数、`WC_Cart_Session::get_cart_from_session()`；比喻不说明实际权限或执行顺序，必须回到源码与HTTP证据。

```mermaid
mindmap
  root((重购与Cart))
    入口
      订单详情按钮
      带Nonce直链
    Woo原生
      状态Filter
      wp_loaded会话读取
      旧Cart替换
    DentAll B方案
      空状态集合
      优先级1早期拦截
    验收
      非空Cart前后快照
      A B Guest
      四端与错误路径
```

主干是“显示判断”和“会话写入”属于不同阶段；两层代码要由真实Cart状态串起来验证。本次在隔离Local完成这一比较，不能直接外推到不同缓存和订单存储配置的环境。

## 请求与生命周期调用链

```mermaid
flowchart TD
  A["已登录Customer打开订单详情"] --> B["Woo订单详情Hook"]
  B --> C["woocommerce_order_again_button"]
  C --> D["过滤有效重购状态"]
  D --> E["DentAll返回空数组：按钮不输出"]
  F["带order_again与_wpnonce的GET"] --> G["WordPress wp_loaded"]
  G --> H["DentAll优先级1：重定向Cart并退出"]
  G --> I["Woo Cart Session默认优先级10"]
  I --> J["若未提前退出：校验Nonce并可能重装Cart"]
```

Woo 11源码中，`wc-template-functions.php`的`woocommerce_order_again_button()`先过滤可重购状态，再决定是否输出按钮。`class-wc-cart-session.php`的`get_cart_from_session()`从Session取原Cart；若同时有`order_again`、`_wpnonce`、已登录且Nonce有效，就调用`populate_cart_from_order()`，并标记会话需要更新。该函数默认通过`woocommerce_empty_cart_when_order_again`清空原Cart。即使状态Filter返回空数组，处理器也已进入重购分支；源码中的提前返回不能替代“原Cart在后续会话保存后仍不变”的实测证据。

## 核心概念卡与真实代码

| 概念 | 准确定义 | DentAll中的边界 |
|---|---|---|
| 状态Filter | Woo决定订单是否可输出重购入口及是否可从订单装Cart时调用的过滤器 | 返回`[]`隐藏入口；单靠Filter不保证已有Cart不变 |
| `wp_loaded`优先级 | WordPress同一Action内数字较小的回调先运行 | DentAll优先级1先于Woo Cart Session默认10；隔离HTTP中实际提前跳转并保留Cart |
| Cart会话 | Woo按当前客户端会话保存商品、数量等Cart事实 | 同一Cookie下比较商品键、数量、券和金额，才支持“本环境未被覆盖” |
| Nonce与归属 | Woo原生重购分支先检查Nonce，订单加载还要检查`order_again`能力 | Nonce不代替权限；B方案不是新授权模型 |

真实代码节选，源自`app/public/wp-content/plugins/dentall-core/includes/customer-account.php`：

```php
function dentall_core_disable_order_again() {
	return array();
}
add_filter( 'woocommerce_valid_order_statuses_for_order_again', 'dentall_core_disable_order_again' );

function dentall_core_redirect_order_again_request() {
	if (
		! function_exists( 'wc_get_cart_url' )
		|| ! isset( $_GET['order_again'], $_GET['_wpnonce'] )
		|| ! is_user_logged_in()
	) {
		return;
	}

	wp_safe_redirect( wc_get_cart_url() );
	exit;
}
add_action( 'wp_loaded', 'dentall_core_redirect_order_again_request', 1 );
```

第一段复用Woo公开Filter，不覆写Woo订单模板；第二段只命中Woo原生重购请求形状并提前结束当前请求。`wp_safe_redirect()`本身不能证明Cart安全；本次另对own-valid、own-invalid、foreign-valid、unknown-valid四类直链进行同一会话前后快照，才得到隔离Local结论。`dentall-core/dentall-core.php`同步把版本更新为0.7.1。

浏览器首轮截图揭示一个独立的展示问题：隔离副本的My Account Page带Storefront侧栏，账户导航与侧栏把订单表挤成三窄列；Storefront在768px以下隐藏响应式表格的`tbody th`，而Woo原生订单号恰位于`th`；订单详情的长备注又在自动表格布局中挤压商品列。先将隔离My Account与Cart Page改为Full width，再仅在子主题`assets/css/customer-account.css`定向恢复手机订单号、不让订单号断行、固定详情两列表格布局，主题升至0.47.1。订单数据与HTML仍由Woo输出，没有复制移动端页面或改核心模板。

## 运行证据与职责边界

| 层级 | 已有证据 | 尚不能证明 |
|---|---|---|
| 代码与Woo源码 | PHP语法、`git diff --check`通过；Woo 11源码可追到按钮Filter、Cart Session与默认清空分支 | 其他Woo版本或目标站点Hook组合 |
| 既有合同 | D79 16/16、D80 28/28、D81/D82 26/26通过 | 仅靠纯合同不能证明浏览器会话或页面 |
| 隔离WP-CLI | Core/Woo active，Filter为`[]`，DentAll拦截Hook优先级1 | CLI本身无真实Cookie与浏览器重定向 |
| 隔离Woo CRUD | 先前独立CLI夹具以A13/B2/Guest1共16单核对查询、10+3分页、状态/金额及A/B归属，已清理 | 目标HPOS与真实交易 |
| 隔离HTTP/Chrome | 130/130；A13/B2/Guest1订单及A/B/C/Guest归属、空态/分页、完成订单按钮隐藏；四类直链前后两件商品的键、数量、优惠券和金额一致。订单/详情/Cart四宽12图，最终390/768订单表定向4图通过；pageerror为0，唯一404经服务日志定位为`/favicon.ico` | D84非付款链另见对应笔记；真实支付、目标站点缓存/HPOS与实体设备/读屏仍待 |
| 独立审查 | 代码无开放P0～P2，安全无开放P0～P3；四宽截图再经独立目视发现并关闭订单表窄列/手机订单号问题 | 目标环境发布验收 |

隔离副本`.codex-tmp/d83-b-isolated`及专属数据库曾供D84复用；测试邮件由隔离`pre_wp_mail`短路，外发HTTP、Cron、Action Scheduler异步运行与支付网关仅在该副本受控关闭。D83 HTTP夹具的16单、2商品、1券及Customer B/C已清理，当时暂留的Customer A和私有凭据已由D84清除；专用PHP/MySQL进程已停，目录与空测试库保留。Woo底层`view_order`对未登录ID 0的Guest订单可返回true，但真实My Account匿名请求只显示登录入口，无订单详情；入口与底层能力不可混为一谈。

| 层级 | 本主题职责 |
|---|---|
| WordPress Core | 加载Hook、当前登录态、Nonce与安全重定向API；不改核心 |
| WooCommerce | 原生订单详情/能力检查、重购按钮、Cart Session和订单CRUD；现阶段不修改插件文件 |
| Storefront与子主题 | 原生响应式表格与账户展示；子主题只修复账户表格可读性，不承载跨主题的Cart安全规则 |
| `dentall-core` | 第一版站点级重购暂缓规则；不创建第二套商品或订单模型 |
| 浏览器与会话 | 用同一客户端Cookie复演请求及Cart前后；CLI不能替代 |

## 安全、数据、SEO与部署边界

- **输入与权限：** 候选拦截只检查两个查询键、Woo函数及已登录态，不读取或写入传入订单ID；Woo原生路径自身验证Nonce及`order_again`能力。B方案不新增订单读取权限。
- **数据与交易：** 运行代码无持久化字段或订单写入；隔离夹具按上文清理。四类真实直链的Cart前后不变量已在Local成立。商品价、运费报价、税、库存、支付及`order-pay`均未改。未签发Pending TEST订单仍显示原生`Pay`动作，本轮未点开付款页，不对付款安全下结论。
- **URL、SEO与缓存：** 不新增公共页面、Slug或Schema；隔离HTTP中命中请求转Cart。目标环境状态码、私有缓存与SEO输出仍待发布验收。
- **性能与回滚：** 仅两个Hook，无新增前端资源、Cron或远程请求；没有负载测量，不能称性能零影响。回滚候选Hook后必须再测试原生重购与Cart行为，不能把代码撤销等同于用户会话恢复。

## 动手练习与排错顺序

1. **只读观察已完成：** 查Woo 11的`woocommerce_order_again_button()`、`WC_Cart_Session::get_cart_from_session()`和`populate_cart_from_order()`，记录Filter、Nonce、会话写入与默认清空的先后。
2. **隔离Local动态练习已由项目执行：** TEST Customer在非空Cart中记录两商品的键、数量、优惠券和金额，访问本人有效/无效、他人有效及未知订单有效直链，并比较同一Cookie下前后Cart；用户本人可在安全的隔离环境照此复演，不在共享Local或真实交易环境操作。
3. **故障推演：** 若按钮没出现但Cart变化，先抓请求URL与登录Cookie，再核对`wp_loaded`回调优先级和实际重定向，最后读Woo会话保存路径；只看HTML是否隐藏会误判。

| 症状 | 首先检查 | 最小验证 |
|---|---|---|
| 订单详情还显示重购 | Filter是否加载、其他插件是否后置修改状态 | 隔离WP-CLI读取最终Filter，再看目标订单HTML |
| 点击旧链接后Cart被替换 | 请求是否同时含两个参数、拦截是否先于Session | 同一Cookie的Cart前后快照与响应链 |
| 订单A/B可互看 | 原生订单归属与`view-order`能力 | 两个TEST客户及Guest分别访问 |
| CLI通过但浏览器失败 | CLI无真实HTTP Session或环境代码不同步 | 先核对版本，再抓HTTP请求和服务器日志 |
| Guest底层能力显示可看Guest订单 | Woo按用户ID 0匹配订单归属，但My Account入口先检查登录态 | 用真实未登录HTTP访问Guest订单详情，确认只见登录页、无订单信息；并检查其他可达入口 |

## 掌握标准与费曼测试

当前掌握度仍为初识；项目的HTTP动态练习已经完成，用户本人尚未复演或费曼自测。合上笔记后应能回答：

1. 用购物篮比喻解释为什么移除“Order again”按钮不能保护既有Cart，并对应回两个真实Hook。
2. 在Woo 11源码中指出原Cart何时读出、Nonce何时检查、默认清空发生在哪里。
3. 为什么状态Filter返回`[]`后，仍不能凭CLI断言原Cart保持？
4. `wp_loaded`优先级1与10是什么关系？`wp_safe_redirect()`能证明什么，不能证明什么？
5. 怎样用Customer A/B、Guest和同一Cookie证明订单归属与Cart不变量？
6. 若未来恢复安全重购，应先确认哪些业务行为和回滚边界？

## 间隔复习与下一步

| 节点 | 计划日期 | 状态 | 复查主题 |
|---|---|---|---|
| D+1 | 2026-10-09 | 待复习 | 按钮Filter与会话Hook |
| D+3 | 2026-10-11 | 待复习 | Nonce、能力和早期拦截 |
| D+7 | 2026-10-15 | 待复习 | 非空Cart前后快照 |
| D+14 | 2026-10-22 | 待复习 | 安全重购业务规则 |

下次遇到相似问题，向AI提供Woo版本、真实Hook代码、完整请求形状、Cookie隔离方式、Cart前后快照及目标行为，请其区分源码事实、CLI与HTTP证据。订单或Cart行为仍以目标版本Local复演与Woo公开API为准。D83-P2由开发者继续持有；恢复“再次购买”能力需另定业务规则、方案和工时。

## 可复用核心思想

- 跨平台不变量：展示入口和服务端状态修改要分别控制；会改变用户已有状态的功能，须用同一会话的前后事实与错误路径验收。
- WordPress/WooCommerce当前实现：Woo 11通过状态Filter输出重购按钮，又在`wp_loaded`的Cart Session中处理直链；DentAll早期Hook阻断已通过隔离HTTP的同一会话Cart前后比较，但目标缓存、HPOS与支付另验。
- Shopify或其他平台：需重新核实重购API是否替换或合并现有Cart、如何鉴权及会话保存；具体对应机制待验证。
