---
类型: WordPress实战学习笔记
项目: DentAll WooCommerce
日期: 2026-09-08
工作日: Day61
主题: 变体生命周期与展示语义
状态: 已生成（隔离Local技术证据，保留原生P2留项）
掌握度: 初识，待费曼自测
验证环境: PHP 8.2.29、WordPress 7.0.4、WooCommerce 11.0.0、Storefront 4.6.2、DentAll 0.35.0；独立隔离Local TEST副本
tags:
  - DentAll
  - WordPress实战
  - WooCommerce变体
  - 可访问性
---

# Day61 WordPress实战：变体生命周期与展示语义

## 相关笔记

- 后续证据整合：[[Day66-集成基线与商品全链路回归]]；不自动提升本人的掌握度。
- 后续Cart承载学习：[[Day68-Cart-Block响应式布局与状态证据]]（不把Cart通过解释成RSK-035已关闭）。

- 学习索引：[[WordPress实战笔记索引]]
- 对应项目笔记：[[../Day61-原生变体选择与购买验证]]
- 前置学习笔记：[[Day60-代表内容压力测试与可逆验收]]
- 同主题知识：[[Day56-WooCommerce原生商品图库与响应式图片]]、[[Day58-WooCommerce原生加购与测试隔离]]
- 后续学习笔记：[[Day62-WooCommerce原生字段与ACF启用边界]]。

> [!important] 证据边界
> 本篇依据已实际运行的源码、浏览器、交易与新进程恢复证据；详细报告与终审以对应项目笔记为准。RSK-035原生AJAX错误体验P2留至D66复审，不能把正常交易通过扩写为所有异常体验或非Local上线通过。笔记生成不代表用户已经掌握。

## 今日学习成果

- [ ] 我能解释预载Variation数据与get_variation AJAX为什么最终经过同一个Filter。
- [ ] 我能按初始、选择、不可购买、清除和提交顺序说明Woo原生状态与DentAll展示适配的边界。
- [ ] 我能在DevTools判断应微调五块局部CSS、共享sizes合同还是ARIA映射，而不重写交易规则。

## 真实项目场景

### 今天解决了什么问题

D56已经让初始Gallery使用符合D59布局的sizes，但Woo选择Variation后会用Variation数据中的image.sizes替换主图属性，动态图片因此可能退回平台默认提示。D58只完成Simple购买区，D60也只验证Variable未选初始态。D61需要保留Woo原生属性匹配、价格、库存和加购，同时补齐动态图片传输提示、Variable数量标签与按钮禁用语义。

### 学习范围

- 本篇要掌握：Variation数据来源、预载/AJAX分支、Woo事件时序、ARIA状态映射、服务端交易边界和五块CSS继承。
- 本篇明确不展开：自定义Variation匹配、AJAX加购、防重复提交、正式价格库存、支付结账、Quick View或区块单品模板。
- 代码入口：DentAll的setup.php、storefront-hooks.php、product-detail.css、product-variation.js，以及Woo的class-wc-product-variable.php、class-wc-ajax.php、variable.php、variation.php、add-to-cart-variation.js。

## 先建立整体模型

### 一句话模型

WooCommerce先根据属性确定Variation并生成当次展示快照，提交时再复核交易事实；DentAll只补充图片尺寸提示、可见标签、局部排版和可访问语义。

### 记忆宫殿：仓库发货台

把Variable商品想成一张总目录，Variation是仓库里的具体货箱。商品少时，页面先拿到全部货箱清单；商品多时，页面把属性送到查询窗口，只取匹配货箱。仓库系统决定价格、库存和能否出库，DentAll只调整展示牌大小，并给“暂不可发货”按钮贴上读屏器能理解的标签。

### 比喻对应回真实机制

| 记忆对象 | 真实技术对象 | 不能混淆的边界 |
|---|---|---|
| 总目录 | WC_Product_Variable | 父商品不是可随意替代子Variation的库存事实 |
| 货箱清单 | data-product_variations中的预载数组 | 预载只是一种传输方式，不改变匹配规则 |
| 查询窗口 | wc-ajax=get_variation | AJAX返回同一种available variation数据 |
| 仓库放行 | is_purchasable、is_in_stock与服务端add_to_cart | ARIA或CSS不能替代服务端校验 |
| 展示牌 | sizes、局部CSS与aria-disabled | 展示适配不能反向制造价格或合法组合 |

## 思维导图

~~~mermaid
mindmap
  root((Variation))
    传输
      预载或AJAX
    Woo事实
      匹配、库存与交易
    DentAll展示
      sizes、CSS与ARIA
    验证
      状态、加购与恢复
~~~

最重要的主干是：不论Variation数据预载还是经AJAX返回，平台先形成同一种状态对象，主题只能消费和呈现它。

## 请求与生命周期调用链

~~~mermaid
flowchart TD
    A["打开Variable商品页"] --> B{"Variation数量是否超过阈值"}
    B -->|预载或AJAX| C["woocommerce_available_variation只补image.sizes"]
    C --> D["Woo更新ID、图片、价格、库存和数量"]
    D --> E["show/hide更新原生class"]
    E --> F["DentAll同步ARIA"]
    F --> G["Woo服务端复核并写cart"]
~~~

- setup.php按is_product且当前父商品is_type('variable')条件加载依赖wc-add-to-cart-variation的脚本；脚本就绪后才查找form.variations_form。
- 输入是父商品ID、属性和available variation数据；输出是原生价格、库存、数量、图片和按钮状态。选择不写商品，成功加购会写隔离Woo session，必须恢复。

## 核心概念卡

| 概念 | 准确定义 | DentAll真实例子 | 常见误区 | 如何验证 |
|---|---|---|---|---|
| Variation传输 | 少量预载数组；超过阈值后按属性请求单个快照 | 当前3项通常inline，代码也覆盖get_variation | 快照保证点击时库存不变 | 查看data-product_variations、请求与POST复核 |
| sizes合同 | 浏览器选择srcset候选时使用的槽位提示 | 初始图与Variation图共享D59公式 | sizes直接指定下载文件 | 对照sizes、srcset与currentSrc |
| 展示语义 | ARIA把原生视觉状态表达给辅助技术 | disabled class映射为aria-disabled | aria-disabled会自动阻止提交 | 键盘、属性与原生提示联合验证 |
| 交易事实 | Woo服务端根据父商品、Variation、属性和数量复核 | 经典POST进入cart | 前端按钮状态等于最终授权 | 检查cart项目与服务端拒绝分支 |

## 项目实战代码

### 共享sizes合同

~~~php
function dentall_product_gallery_sizes() {
	return '(min-width: 82.5rem) 44.37rem, (min-width: 75rem) calc(56.521739vw - 2.26087rem), (min-width: 48rem) calc(100vw - 4rem), calc(100vw - 2.5rem)';
}

$variation_data['image']['sizes'] = dentall_product_gallery_sizes();
~~~

初始Gallery属性Filter与woocommerce_available_variation都调用该函数。普通页面还核对当前父商品ID；AJAX分支要求WC_DOING_AJAX且wc-ajax等于get_variation。Filter只改image.sizes；预载/AJAX都是当次生成的状态快照，点击时库存仍可能变化，最终由POST服务端复核。

### 初始状态与事件映射

~~~javascript
$form.find('.single_add_to_cart_button').attr('aria-disabled', 'true');
$form.on('hide_variation.dentall show_variation.dentall', () => {
	syncButtonState($form);
});
~~~

Woo脚本会延迟初次匹配；在收到show或hide前，DentAll保守宣告不可购买。原生处理器先增删disabled class，DentAll再同步ARIA。实测已有可购选择再次请求时，Woo清空ID并显示遮罩，但旧class仍为enabled，因而ARIA暂为false；映射脚本不自建loading或交易状态，也不增加HTML disabled或点击拦截。503失败后原生没有专用网络错误提示，需Clear/重选或刷新，不能把“保留原生”写成完整错误UX已实现。

### 五块CSS为什么保留

Storefront已经处理单元格堆叠、value间距、价格display和按钮间距。DentAll五块新增规则只负责label间距、Select满宽、Clear外距、长词换行和动态价格Token；D58四个数量规则仅扩展到Variable，没有复制第二套布局。

### 运行证据

- 主Agent真实回放：六宽inline 363/363、参数URL/键盘加购与清空13/13、两宽AJAX 121/121、延迟/503/恢复/快速切换7/7；实际页面未捕获JS异常为0。外网字体与emoji被隔离主动拦截，不能算成线上网络成功。
- 独立匿名12/12：#51×2=$79.98 USD，超库存、售罄、属性不匹配均被服务端拒绝且cart空；Customer2/2：真实登录后#53×1=$49.99。没有Checkout或订单测试。
- 四宽真实后台默认值/变体长词/缺图回退24/24、双缺图与单售20/20、无价格与stock1状态16/16；无价POST独立2/2。单售由父Variable设置继承并隐藏整个Quantity；非单售Variation即使仅剩1件仍显示input且min=max=1，不能照搬Simple服务端模板的hidden推断。
- AJAX四类参数/键盘/cart再跑13/13；已有可购→503→键盘POST4/4证明旧展示问题真实存在、服务端拒绝且cart空。该原生UX问题登记RSK-035/P2，由开发者在D66复审，不把复现成功当成修复完成。
- 同一get_variation响应关闭/开启主题Filter后，仅image.sizes不同；价格、库存、ID和属性未改。正式数据恢复结果与默认/长词/缺图场景在项目笔记集中登记。
- 首轮测试曾错误要求Clear退回初次currentSrc、在noindex页要求Canonical存在、以及只等ID就立即断言ARIA。纠正前提与原生300ms通知时序后均真实重跑，旧失败报告保留。Zoom可预载原图，浏览器可以保留较大缓存候选。

## 职责边界

| 层级 | 本主题中负责什么 | 不应该负责什么 |
|---|---|---|
| WooCommerce | 经典Variable模板与表单DOM、匹配、价格库存、数量、提示及服务端加购 | 采用DentAll断点或品牌视觉 |
| Storefront | 默认布局与Hook编排 | 承担Woo交易事实或DentAll Design Token |
| DentAll子主题 | sizes、可见标签、局部CSS和ARIA映射 | 自建匹配器、库存源或AJAX加购 |
| dentall-core | 保持既有SEO兼容职责 | 承载本次纯展示脚本和CSS |
| 数据库 | 保存正式Product/Variation和Woo session | 接受未经授权的正式事实改写 |
| 浏览器 | 执行原生脚本、选择图片候选并呈现ARIA | 成为最终交易可信边界 |

## Hook、API或模板机制详解

| 机制 | 本次真实用途 | 返回或副作用 | 验证重点 |
|---|---|---|---|
| wp_enqueue_scripts | 条件加载详情CSS与Variable脚本 | 新增一个按页JS请求 | Simple、Shop和Cart不加载 |
| woocommerce_quantity_input_args | 将主Simple/Variable名称缩为Quantity | 只改label文案 | 不改min、max或库存 |
| woocommerce_gallery_image_html_attachment_image_params | 初始Gallery使用共享sizes | 返回图片属性数组 | 非Product退出 |
| woocommerce_available_variation | 预载和get_variation共用动态sizes | 只改image.sizes | 两条请求路径一致 |
| show_variation / hide_variation | 原生状态稳定后触发ARIA同步 | 只改aria-disabled | 原生处理器先执行 |
| WC_Cart::add_to_cart链 | 服务端验证并创建购物车行 | 写Woo session | 非法、缺货与数量边界拒绝 |

## 安全、数据与站点影响

| 检查面 | 当前结论 | 证据或边界 |
|---|---|---|
| 输入清洗与验证 | 主题不读取自定义业务输入；Woo处理原生属性与数量 | 超库存/售罄/属性不匹配/无价/失败后空ID均拒绝 |
| Capability与Nonce | 公共浏览/加购不使用后台Capability；本次不新增后台动作 | 不把Nonce虚构为前台交易授权 |
| 输出 | sizes为主题常量；Variation数据继续由Woo编码输出 | inline/AJAX Filter开关深比较仅image.sizes不同 |
| 数据库 | 代码不写商品；加购会写session | 新进程确认5商品/modified与原1条session恢复；非整库逐字节恢复 |
| URL与SEO | 不改Slug、Canonical、Schema或路由 | 参数URL/客户端选择及Filter开关输出不变量通过，公开SEO未验 |
| 性能与缓存 | Variable页新增720字节条件JS；不增查询接口 | 非Variable资源退出通过；未量测整页SQL/CWV |
| 支付、物流、订单 | 均不改；不进入Checkout | 订单/退款保持0，pending actions保持14 |
| 部署与回滚 | 变更仅在当前任务分支；不改main或非Local | 逆向D61主题增量并恢复版本，无业务数据迁移 |

## DevTools微调与排错顺序

1. Elements先确认仍是一套Woo原生form、两个Select、variation_id、single_variation与按钮。
2. Event Listener与属性面板观察show/hide前后的disabled class和aria-disabled，不从颜色猜状态。
3. Network区分inline与get_variation；检查响应中的image.sizes，不直接复制第二套公式。
4. Image属性同时看src、srcset、sizes和currentSrc；src改变不代表浏览器已显示正确候选。
5. Computed先看Storefront已有规则，再改五块DentAll局部规则；不要恢复已删除的Grid或th/td重设。
6. 提交后以购物车属性、数量和金额确认服务端事实，并按快照恢复隔离环境。

## 常见误区与排错顺序

| 现象或误区 | 可能原因 | 推荐检查顺序 | 最小验证 |
|---|---|---|---|
| 选图后sizes退回416px | 只改了初始Gallery HTML | Filter路径→Variation JSON→图片DOM | 比较初始与选择后sizes |
| 按钮看似灰但读屏无状态 | Woo只切换disabled class | class→show/hide→aria-disabled | 初始、可购、缺货、Clear |
| AJAX分支不生效 | 只用is_product判断 | WC_DOING_AJAX→wc-ajax→Filter | 强制阈值或隔离调用 |
| Clear后currentSrc不同 | 可能是Zoom预载后复用父图大候选，也可能真未Reset | 先核对父图src/srcset/sizes，再看候选归属 | 不能只逐字比较首次currentSrc |
| 已有选择AJAX时ARIA仍false | 原生旧disabled class暂留，ID已清空且表单遮罩中 | ID→blockOverlay→class→show/hide终态 | 延迟、快速重选与服务端拒绝分开测 |
| 测试声称live polite失败 | 混淆两个live region | single_variation role→reset alert | 对照Woo 11源码 |

## 动手练习

1. 画出预载和AJAX在woocommerce_available_variation汇合的两条路径。
2. 在DevTools只读记录三个Variation的ID、price、stock、quantity max与currentSrc。
3. 关闭DentAll语义脚本，说明视觉disabled为何仍存在、读屏信息为何退化、服务端为何仍应拒绝非法提交。

## 掌握标准

- [ ] 能在2分钟内讲清“Woo决定事实，主题表达结果”。
- [ ] 能指出共享sizes helper、两个Filter、条件enqueue与ARIA脚本。
- [ ] 能区分single_variation的role=alert和reset alert的aria-live=polite。
- [ ] 能解释为何不加入HTML disabled或自定义点击拦截。

## 费曼自测题

1. 为什么Variation少和多时传输方式不同，却不需要两套主题业务规则？
2. sizes、srcset与currentSrc分别回答什么问题？
3. 为什么ready阶段先写aria-disabled=true，而不是立刻照抄按钮class？
4. 缺货Variation从数据库事实到按钮与提示，依次经过哪些对象和事件？
5. 哪些CSS可安全微调，为什么前端可购买状态仍不能替代WC_Cart校验？

### 我的费曼答案与纠正

尚未自测，不评分；完成后每题2分、满分10分。先口述，再把含糊处链接回对应章节，不能因参考代码存在就提高掌握度。

## 间隔复习记录

| 复习节点 | 计划日期 | 完成 | 复习重点 |
|---|---|---|---|
| D+1 | 2026-09-09 | [ ] | 预载/AJAX与共享Filter |
| D+3 | 2026-09-11 | [ ] | 事件时序与ARIA |
| D+7 | 2026-09-15 | [ ] | 交易拒绝与恢复 |
| D+14 | 2026-09-22 | [ ] | 迁移到其他主题/平台 |

## 收尾总结

- 今天形成的模型：Variation事实由WooCommerce建立，DentAll只做可替换的展示适配。
- 最容易混淆的点：disabled class、ARIA语义与服务端不可购买是三层，不互相替代。
- 下次先检查：数据传输模式、原生事件、服务端事实，再判断是否需要主题代码。

## 后续如何向AI高效提问

提问公式：环境版本＋商品/Variation事实＋inline或AJAX＋最短选择步骤＋DOM/事件/JSON证据＋隔离恢复边界；要求AI先查available variation、show/hide、currentSrc和购物车，再给不改核心、不复制模板、不自建交易判断的最小候选。

## 变种应用到其他项目

换用其他经典主题、区块单品模板或Quick View时，仍坚持“平台事实与主题展示分离”，但必须重新验证DOM、事件、图片槽位和扩展点。Shopify等平台的Variant事件、媒体候选与Cart API均待官方资料和测试店验证。

## 可复用核心思想

### 跨平台不变量

变体系统必须把“合法组合与交易事实”和“页面怎样展示”分开。传输方式可以变化，但价格、库存、可购买与购物车必须有唯一可信来源，展示层只能消费结果。

### WordPress/WooCommerce当前实现

WooCommerce 11.0用WC_Product_Variable、available variation数据、原生事件和服务端Cart链建立事实；DentAll 0.35.0通过公开Filter、条件enqueue、五块局部CSS和一个ARIA映射脚本完成最小展示适配。恢复时先完成子Variation，再最后恢复父级并用新进程验证，避免对象缓存隐藏父子同步副作用。

### Shopify或其他平台的对应机制

可迁移的是“平台决定变体事实、主题表达状态、服务端再次校验”的分层。Shopify具体Variant选择事件、响应式媒体字段与Cart API拒绝机制尚未在DentAll验证，必须查官方资料和测试店实测，不能把Woo Hook名称直接类比。
