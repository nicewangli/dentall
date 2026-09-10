---
项目: DentAll WooCommerce
日期: 2026-09-08
工作日: D69
周次: W12
验收层级: 独立Local技术候选
状态: 独立Local技术候选已完成；保留RSK-039/040期限性P2，待W12集成
实际有效工时: 未记录
---

# Day69 Header Cart与Mini Cart状态联动

## 相关笔记

- 每日笔记索引：[[README|DentAll每日笔记索引]]。
- 原始Header与fragment边界：[[Day33-手机与平板竖屏Header]]。
- 当前集成基线：[[Day66-商品浏览闭环集成回归]]。
- 当日学习笔记：[[WordPress实战笔记/Day69-Cart Store与经典Fragments桥接]]。
- 同主题购买验证：[[Day58-简单商品购买区与隔离购物车验证]]、[[Day61-原生变体选择与购买验证]]。

## 结论

D69在`codex/day69-header-cart-sync`分支完成最小技术候选：只在WooCommerce Cart Block页面订阅公开`wc/store/cart`，商品键或数量发生真实变化后，串行触发既有经典`wc_fragment_refresh`，让Header数量与Woo Mini Cart从服务端Session重新取得一致HTML。没有自建购物车Store、接口、重试器或交易逻辑。

实施同时修复了一个完成D69必须处理的既有交互断点：经典fragment原先替换整个`a.cart-contents`，会丢失Storefront运行时绑定的桌面焦点与首次触摸监听。现在只替换链接内部动态内容，并以`_dentall_header_v2`隔离旧浏览器fragment结构；链接节点保持稳定。Mini Cart增加`:focus-within`，键盘焦点进入面板后不再因父链接失焦而关闭。

本结论只代表当前分支、当前隔离Local版本和已列明场景。D66的RSK-035/037/038仍开放，D67/D68/D70候选未在本分支集成，W12、M5、非Local和Production均不因此完成。

## 用户授权与最小范围

用户于2026-09-08明确回复：

> 同意按上述 D69 最小范围实施

本轮据此前置确认单实施以下范围：

- 实际用户：匿名访客与已登录Customer；发生频率为用户在Cart Block页修改数量或移除商品时。
- 数据来源：WooCommerce原生Cart Store与Session；以Simple和Variable代表商品、1～3件及空购物车做隔离TEST。
- 第一版必须做：同页数量/移除同步、非空/空Mini Cart、快速连续变更串行、Store与fragment失败保底、跨标签页/历史返回、390/768/1024/1440及资源作用域。
- 明确不做：重写Cart/Mini Cart、客户端猜数量、自定义REST/AJAX、第二状态Store、轮询/无限重试、跨标签页广播、优惠券/金额展示改造、结账/订单/支付、插件安装、数据库字段、非Local部署。
- 选择方案：原生能力单独使用不能连接Block Store和经典Header；成熟第三方插件会扩大生命周期与维护面；因此采用子主题内的最小公开API桥接。
- 计划工时：按D69一个计划检查点管理，计划有效工作为6小时50分钟；实际工时由用户按需记录，不以计划值代填或承诺。

## 三个验收结果

- [x] Cart Block中Simple/Variable数量修改与移除后，Header Cart和Mini Cart在同页与后续导航保持一致；首次服务端Header、Store、fragment与刷新恢复边界分层验证。
- [x] 快速连续变化最大fragment并发为1；Store API失败不误刷，fragment失败保留上次服务端Header且下一次有效变化可恢复；优惠券单独变化不触发商品状态刷新。
- [x] 390/768/1024/1440、匿名/Customer、桌面/键盘/模拟触屏、跨标签页/历史返回、资源作用域及三路专项复核均已执行；终态P0/P1为0，两个期限性P2已登记。

## 七个专注周期

| 周期 | 工作 | 实际证据 |
|---|---|---|
| C1 | 读取规则、D33/D66边界及当前Woo/Storefront实现 | 确认Cart Block Store与经典Header fragment为两条客户端链路；未预设主题或版本 |
| C2 | 建立隔离Local与修改前基线 | 独立数据库、端口10669、禁外发/邮件/Cron/结账；改量后Store 2而Header 1，刷新后才为2 |
| C3 | 实现最小Store→fragment桥接 | Cart Block条件enqueue、公开Store descriptor、初始化resolver与商品签名 |
| C4 | 保留Header节点身份及Mini Cart交互 | fragment改为内部`span`、缓存键v2、`:focus-within` |
| C5 | 正常、错误、并发、跨页和角色验证 | Simple/Variable、匿名/Customer、Store/fragment故障、快速改量与跨标签页 |
| C6 | 四端、资源作用域、可访问性及专项复核 | 四宽几何、键盘/触控、非Cart对照页；Code Review/安全/独立测试 |
| C7 | 减法审查、恢复、停机、文档与提交 | 不扩大到D70金额语义；JS收敛至176行，数据库恢复测试前基线，10669监听为0，本分支独立提交收口 |

周期是工作组织方式，不代填实际有效工时。

## 实际实现

### Cart Store到经典fragment的桥接

`assets/js/cart-header-sync.js`只在依赖和Cart页面身份齐全时运行：

1. 用Woo公开的`window.wc.wcBlocksData.cartStore`作为Store descriptor；
2. 先等待`wp.data.resolveSelect(cartStore).getCartData()`，再记录初始签名，避免把Store从空壳水合成真实数据误判为用户操作；
3. 签名只包含排序后的商品`key`和`quantity`，价格、总额、优惠券、运费或Store对象引用变化不会触发Header商品状态重绘；
4. 有效变化只触发已有jQuery事件`wc_fragment_refresh`，不直接调用或复制Woo内部端点；
5. 在等待Store初始化前就监听公开jQuery `ajaxSend/ajaxComplete`，只匹配`wc_cart_fragments_params`生成的精确同源fragment端点；Woo因首屏缓存、跨标签页或BFCache自行发出的请求也进入同一在途集合；
6. 每次商品签名变化递增内存revision。已有请求全部落地后才判断该变化是否已经被有效Header fragment覆盖；每个revision最多主动请求一次。失败、HTTP 200但缺目标fragment或事件无人消费都释放状态，同一变化不自动重试；下一次真实变化形成新revision后可以再次请求。

初版曾在所有`pagehide`事件永久退订。独立Code Review与安全审查分别判定：BFCache入缓存时`event.persisted=true`，恢复的是同一个页面实例，若已经退订，返回后继续改量不会再刷新Header。当前只在`!event.persisted`时退订，修复后的合成BFCache路径已通过。

第二轮Code Review又复现首次旧fragment晚到并覆盖较新Header；原`.one()`只能知道“有事件完成”，不知道完成的是哪次请求。当前状态机按精确端点跟踪每个jqXHR及其Cart revision，等待整批settle后再决定，避免本页旧请求与桥接请求并发。HTTP 200但没有目标Header fragment也被视为失败，不会锁死后续真实变化。

### Header动态内芯与Mini Cart

- `dentall_cart_link_content()`集中输出可替换内芯：可见Cart标签和数量对辅助技术隐藏，完整动态名称由`.screen-reader-text`提供。
- `dentall_cart_link()`只负责稳定的`a.cart-contents`链接和Cart URL；不再把动态名称放在会随fragment替换的锚点属性上。
- `woocommerce_add_to_cart_fragments`现在返回`span.dentall-cart-content`，而不是整个`a.cart-contents`。浏览器实测刷新前后锚点对象引用相同，Storefront既有监听仍在。
- `woocommerce_cart_fragment_name`从D33的`_dentall_header_v1`升级为`_dentall_header_v2`，避免浏览器旧缓存再次用整锚点HTML覆盖新结构。这个键只随fragment HTML合同升级，不随每次CSS版本变化。
- 既有桌面Mini Cart规则增加`:focus-within`，使Tab从Cart链接进入面板内容后保持展开；没有新弹窗、焦点陷阱或自定义菜单脚本。

### 条件资源与职责归属

`dentall_enqueue_cart_header_sync_assets()`在`wp_enqueue_scripts`优先级55运行，仅当`is_cart()`且页面实际包含`woocommerce/cart`区块时登记脚本。依赖明确声明为`jquery`、`wp-data`、`wc-blocks-data-store`、`wc-cart-fragments`，由WordPress排序并在页脚defer加载。

该桥接依赖DentAll Header标记和Storefront Mini Cart交互，生命周期与子主题展示一致，因此放入子主题；没有理由放入`dentall-core`、mu-plugin或新插件。PHP加载逻辑留在既有`inc/setup.php`，Header fragment逻辑留在既有`inc/storefront-hooks.php`，浏览器生命周期单独放一个小JS文件，避免把PHP、HTML和异步状态机堆入同一文件。

## 修改前后证据

### 修改前基线

同一隔离Cart会话：

| 步骤 | Cart Store | Header |
|---|---:|---:|
| 首屏 | 1 | 1 |
| 同页数量加一 | 2 | 1 |
| 刷新页面 | 2 | 2 |
| 同页移除 | 0 | 2 |

这证明不是商品数量或服务端Session写错，而是Block Store变更后没有通知经典Header重绘。

### 实施后主路径

| 场景 | 实际结果 |
|---|---|
| Simple首屏1→同页2→刷新2→移除0 | Header与Store分别为1/1、2/2、2/2、0/0 |
| Variable #53，Large 105mm / Light，数量2 | Header为2，移除后为0 |
| 登录Customer数量更新/移除 | Header为2/0；临时账号已删除 |
| 非空Mini Cart | 商品、`1 × $24.99`、`Subtotal: $24.99`正确；移除后为`No products in the cart.`且Header 0 |
| 锚点身份 | fragment前后`a.cart-contents`为同一DOM对象 |
| 桌面/键盘/模拟触屏 | hover可展开；链接focus可展开；Tab进面板保持展开；首次触摸留在当前页并打开Mini Cart |

### 错误、并发与非目标变化

| 场景 | 实际结果 |
|---|---|
| 中止一次fragment请求 | Header保留1；不自动无限重试；下一次改量恢复为3 |
| 中止一次Store API改量 | Store事实未变、Header保持3、fragment请求0 |
| 快速连续改量 | 最终Header 3；fragment最大并发1，只按最终状态收敛 |
| 只改变优惠券Store字段 | Header商品数保持3；fragment请求0 |
| 第二标签页同Session | 活跃页更新为2；另一页由Woo既有机制恢复为2 |
| 历史返回 | 返回Cart时Header为2；BFCache `persisted=true`恢复后继续改量仍可正常同步 |
| 首次旧fragment延迟 | 状态机等待已在途请求settle后刷新；最终Store/Header为2/2，无无限请求 |
| HTTP 200 HTML、204或abort | 同一变化不自动重试且内部状态释放；下一次真实变化恢复为3/3 |

### 四端、作用域与诊断

| 宽度 | Cart链接水平范围 | Count尺寸 | 结果 |
|---:|---|---|---|
| 390 | x326～370 | 18×18 | 位于视口内，无Header横溢出 |
| 768 | x692～736 | 18×18 | 位于视口内 |
| 1024 | x948～992 | 18×18 | 位于视口内 |
| 1440 | x1304～1348 | 18×18 | 位于视口内 |

- 脚本在Block Cart每页恰好1份；Home、Shop、Simple Product和My Account均为0。
- 390与1440截图已人工查看，Header Cart标签/徽标、主体和视口边界无明显回归；这不代替实体设备或屏幕阅读器验收。
- 非空Cart出现1条WooCommerce关于`wc.wcBlocksData`访问方式的上游诊断。阻止D69脚本后仍出现，因此不是D69新增；HTML依赖注册明确含`wc-blocks-data-store`。保留为当前Woo 11.0.0基线观察项，不隐瞒，也不归因成项目错误。
- 页面没有D69引入的其他Console error/warning、PHP语法错误或意外资源请求。

## 独立复核

- Code Review：终态SHA-256为`269AD1E247B6C4BD3A05F001D9757BC90442DBFB043588C86E989A42002C166B`，P0=0、P1=0、P2=2、P3=0；两个P2均为已登记的RSK-039/040。初审发现的BFCache退订与本页旧fragment响应晚到均已修复，184行状态机经减法审查收敛至176行。
- 安全审查：同一终态哈希与10669运行副本一致，P0=0、P1=0、开放P2=2、产品源码P3=0；未发现第二购物车真相、交易写回、自建端点、XSS、权限或nonce缺陷。隔离夹具另有`WP_DEBUG_DISPLAY`重复定义日志噪声P3=1，Fatal为0，不归入产品缺陷。
- 独立测试：同一终态哈希、25项源码/运行副本清单全部一致；旧响应错序、200 HTML、204、abort、连续revision及Simple `1→2→0`均通过，每个revision最多一次桥接请求，P0=0、P1=0、新增P2/P3=0。证据只写Git忽略目录。

三路终审均确认P0/P1为0；RSK-039/040按负责人、延期理由和D72/W12及最晚非Local部署前的复审节点有条件接受。当前只标记独立Local技术候选完成，不外推为Production就绪。

## 减法审查与复杂度

按UTF-8物理行口径，运行源码为5个文件（4个既有文件修改、1个JS新增），237行新增、17行删除，净增220行；新增JS为176行、4452字节。当前设计保留：

- 1个新增运行文件：`cart-header-sync.js`，因为浏览器Store订阅、官方fragment批次与页面生命周期可独立测试，和PHP职责不同；
- 新增2个PHP具名函数和4个JS具名函数；PHP只负责条件enqueue/Cart动态内芯，JS只负责签名、精确请求识别、有效fragment判断与有界调度；既有Cart链接/fragment函数只做必要改造；
- CSS不新增规则块，只给既有Mini Cart显隐规则增加1个`:focus-within`选择器；
- 没有模板覆盖、插件、构建链、第三方依赖、自定义Store、接口、数据库字段、轮询、通用框架或预实现D70金额状态。

Code Review减法将JS从184行收至176行：利用`cartRevision`单调递增，删除每批重置与重复取最大值的机械状态，再把数字预算收敛为`lastRefreshRequestRevision`，直接表达“每个revision最多主动请求一次”。Map及请求/有效/最后落地三个revision维度分别负责等待全部在途请求、识别当前变化是否发出/有效及检测旧响应最后覆盖；继续删除会重开已复现竞态，故不以压缩写法掩盖。

没有把JS塞进行内`<script>`，也没有为单页桥接建立新PHP模块或插件。若未来Header改为原生Mini Cart Block并直接消费同一Store，应优先删除本桥接，而不是继续叠加兼容层。

## 数据、URL、SEO、缓存与交易影响

| 检查面 | 当前影响 | 边界/回滚 |
|---|---|---|
| 商品、客户、库存、订单数据 | 运行代码不新增写入；测试只改变隔离Woo Session | 恢复独立数据库基线并删除临时Customer；不触碰共享Local |
| URL | Cart URL及所有Slug、参数合同不变 | 不新增重定向、端点或路由 |
| SEO | Title、Meta、Canonical、Schema、robots、Sitemap与内部链接不变 | 仅Cart前端资源/fragment标记；非Local未验 |
| 缓存 | 浏览器fragment结构键v1→v2，会在用户端获取一次新结构 | 不删除全站storage，不改页面缓存/CDN；回退提交恢复旧代码，但旧键自然留待过期 |
| 性能 | Cart Block增加1个小JS请求；有效商品变化增加既有fragment请求，快速变化串行 | 无前后CWV或Production缓存测量，不宣称零影响或变快 |
| 支付、税费、物流、订单 | 无直接变更，也未进入Checkout或创建订单 | D70金额、支付与后续交易节点独立处理 |
| 部署 | 当前仅分支与隔离Local技术候选 | 未合并`main`、未推送、未部署Staging/Production |

## 未验证与不外推

- 真实iOS/Android触屏、Safari BFCache、真实屏幕阅读器、浏览器兼容矩阵和Production Core Web Vitals。
- 页面缓存/CDN、跨设备购物车、登录切换、长时间Session过期及多窗口并发编辑。
- RSK-039：完全禁用Web Storage时同页Header不即时刷新；当前正确释放状态并由导航恢复，fallback是否值得增加由D72/非Local浏览器矩阵决定。
- RSK-040：双标签快速改量、旧跨标签响应被人为延迟且来源标签立即关闭的组合下，剩余标签可能暂显旧Header；服务端事实不变，下一次导航/变化恢复。
- Coupon、折扣后行金额、税费、运费、跨币种、Checkout/Order/Payment；D70合同仅用于确认优惠券不是Header商品数触发字段。
- WooCommerce/Storefront未来版本、区块主题、Mini Cart Block或第三方Cart/Quick View插件。
- D66三项P2、D67/D68/D70候选集成、W12整周验收、M5及非Local发布。

## 收尾与下一步

1. 三路终态哈希终审已收齐，P0/P1为0；BFCache恢复后再改量已通过，RSK-039/040按期限性P2继续跟踪。
2. 隔离数据库已由1.94 MB测试前转储恢复，59张表可读、D69临时用户为0；10669专用PHP进程已精确停止并确认监听为0。测试证据留在Git忽略目录，不提交Cookie、SQL、截图或内部配置。
3. D69只在当前分支形成独立提交，不合并、不推送；后续由W12集成按实际基线合成D67/D68/D69/D70，并重新跑购物车全链路，不能直接把各分支单日通过相加。

## 可复用核心思想

### 跨平台不变量

- 一个交易状态可以有多个派生视图，但只能有一个业务事实源；同步层应发送最小失效通知，而不是复制事实。
- “最终数字正确”不足以验收异步状态：还必须验证初始化、并发、失败、恢复、跨页和DOM监听器存活。
- 局部渲染选择器是交互合同的一部分。替换看似相同的外层HTML，也可能丢失运行时监听器与焦点状态。

### WordPress/WooCommerce当前实现

- WooCommerce 11.0.0的Cart Block使用`wc/store/cart`与Store API；经典Storefront Header/Mini Cart依赖`wc-cart-fragments`。DentAll只用公开Store descriptor订阅前者，并触发后者已有刷新事件。
- `wp_enqueue_script()`声明依赖和页面范围；`woocommerce_add_to_cart_fragments`只替换动态内芯；`woocommerce_cart_fragment_name`在HTML合同改变时隔离旧浏览器缓存。
- BFCache保留页面实例，`pagehide.persisted=true`不是永久卸载；页面生命周期判断必须进入状态同步测试矩阵。

### Shopify或其他平台

- 其他商城同样需要识别Cart事实源、派生Header、局部渲染边界和失败恢复；具体API、事件和缓存实现不能直接照搬WooCommerce。
- Shopify Cart API、Section Rendering及主题事件的具体映射本轮未验证，标记为待验证；它们不构成DentAll第一版新增范围。
