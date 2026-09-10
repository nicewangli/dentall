---
项目: DentAll WooCommerce
日期: 2026-09-09
工作日: D72
计划检查点: D72（人工运费报价变更收口）
周次: W12
实际有效工时: 用户未记录
验收层级: 独立Local候选实现与交易边界验证
状态: 已完成批准范围与D67～D72 Local合成树终验，并由`6b5c96e`合入远端main；正式邮箱与非Local部署待后续验收
---

# Day72 人工运费邮件报价与购物车收口

## 相关笔记

- 每日笔记索引：[[README|DentAll每日笔记索引]]。
- 前置金额验证：[[Day71-运费税费与金额摘要候选验证]]。
- 当日学习笔记：[[WordPress实战笔记/Day72-人工运费报价与结账安全边界]]。
- 变更与决定：[[../CHANGE_REQUESTS#CR-012：标准价格商品采用人工运费邮件报价|CR-012]]、[[../DECISIONS#ADR-038：标准商品第一版先邮件确认运费再开放订单付款|ADR-038]]。

## 先给结论

已按用户批准范围完成第一版人工运费邮件报价候选：正常定价的实体商品先在Cart通过邮箱申请运费，业务人员线下确认Shipping、Tax和Fee，再用WooCommerce原生`Pending payment`订单发送付款链接。客户WhatsApp只是邮件模板中的可选联系方式，公司WhatsApp展示留给D89 Contact Us页面，不阻塞当前实现。

第一版没有站内询价表单、询价CPT、ACF金额字段、自动承运商报价、WhatsApp API或0元运费占位。正式收件邮箱使用WooCommerce设置保存且默认空值；空值不会回退到`admin_email`，因此代码与Local验证可继续，但Staging业务验收和Production部署前必须由业务方提供公司控制的邮箱。

## 授权、范围与三个验收结果

用户于2026-09-09明确回复“同意按上述范围实施人工运费报价流程”，随后指定“第一版先用邮箱”，并允许客户在邮件/订单备注中提供可选WhatsApp。

- [x] 含实体商品的Cart Block显示`Request Shipping Quote by Email`，预填商品名称、SKU、规格、数量、当前商品小计、coupon代码和客户/收货信息空位；数量变化后链接实时更新。
- [x] 普通Checkout页面、经典结账提交及Store API带版本/无版本/Batch/大小写旁路均有服务端阻断；人工订单`order-pay`页面保持可访问，已报价订单的配送地址和适用税基地域在付款前锁定，实体订单缺Shipping明细时REST与经典付款提交均失败。
- [x] 缺少正式邮箱时安全失败；隔离Local中的TEST待付款订单使用原生Shipping重算，付款页可打开，TEST订单、设置和checkout draft最终均为0。

## 实现边界与职责

| 层级 | 本次职责 | 明确不负责 |
|---|---|---|
| DentAll子主题 | Cart页条件加载一个JS；通过Cart Block官方Filter改按钮文字和链接；根据当前Store API数据生成`mailto:` | 不计算最终运费、税费、订单总额，不保存个人信息 |
| DentAll Core | Woo Shipping设置中的报价邮箱；按商品自身属性判断实体购物车；扩展Cart Store API；阻断未报价的普通结账 | 不自建订单表、报价状态机、支付或邮件队列 |
| WooCommerce | Product、Cart、Pending order、Shipping/Tax/Fee明细、重算和`order-pay` | 不在第一版自动决定业务报价 |
| 业务人员 | 根据数量和目的地询价；把最终Shipping/Tax/Fee写入订单；复核总额并发付款链接 | 不直接改模糊“总金额”，不把TEST政策当正式政策 |
| 业务方 | 提供公司控制的报价邮箱；确定邮箱访问、留存和报价SOP | 公司WhatsApp页面展示可在D89确认 |

## 关键技术取舍

WooCommerce 11.0.0的`WC_Cart::needs_shipping()`在全站没有任何配送方法时直接返回`false`。人工报价模式恰好可能没有配送方法，因此D72没有把它当成业务真相，而是遍历购物车商品并读取`WC_Product::needs_shipping()`，再通过Store API扩展命名空间`dentall/shipping-quote`把`required`布尔值交给Cart Block。这一修正在动态集成测试中由首轮失败暴露，并已增加回归测试。

邮件由访客本机邮件客户端发送，WordPress第一版不接收表单POST，也不持久化姓名、地址或WhatsApp。代价是依赖本机邮件客户端，且超大购物车可能触及`mailto:`长度兼容边界；当前只按常规少量行项目验证，大批量报价需另开变更。

## 运行代码与减法审查

- 新增`dentall-core/includes/shipping-quote.php`：设置、实体商品判断、Store API扩展、结账守卫和已报价订单地址锁属于同一“报价前后交易边界”职责。
- 新增主题`assets/js/shipping-quote.js`：只在Cart加载，负责当前购物车到邮件草稿的展示映射。
- 修改Core入口、主题Cart资源加载和两个版本号；没有模板覆盖、CSS新规则、数据库表、CPT、ACF、AJAX、远程请求、Cron、第三方插件或依赖。
- DentAll由0.37.0升至0.38.0，DentAll Core由0.2.8升至0.2.9，用于刷新Cart脚本缓存并标记跨主题交易规则变化。

保留两个新运行文件而不塞入既有大文件：Core模块与主题JS分属服务端交易边界和浏览器展示生命周期，可独立测试与回滚。其代码量主要来自设置验证、经典/Block双结账防绕过、Store API动态事实和可访问英文邮件模板；未预实现表单、报价记录或自动订单。

## 实际验证

| 证据 | 结果 |
|---|---|
| PHP lint与Node语法 | Core入口、报价模块、主题setup和JS通过 |
| 纯PHP合同 | 36/36：严格邮箱、设置、实体/虚拟/空车、经典结账、带版本/无版本/Batch/大小写/Agentic路由、Shipping与Billing税基地域锁、无Shipping订单付款判定 |
| 纯JS合同 | 23/23：Filter、两种Cart对象形态、邮件内容、动态数量、虚拟车、缺邮箱、收件人URI编码及邮件点击捕获 |
| 隔离Local浏览器/交易 | 18/18：实体商品、按钮、邮件上下文、数量1→3、Checkout回退、Store API 409、0 draft、原生Shipping、`order-pay`、清理、390/1440无横溢出和无意外Console |
| 独立补证 | 虚拟/混合Cart 16项、Variation 8项、Batch 8项、地址锁6项、无Shipping付款7项；邮件显式`USD`，按钮点击后不持续loading |
| 数据清理 | D72 TEST订单0、checkout draft 0、TEST税率0、库存8、报价邮箱TEST option不存在；17171/17172已停止，源Local未写入 |
| 日志 | 有效实现请求未匹配新增PHP Fatal/Warning/Parse；刻意Store API 409在浏览器Console单独视为预期负向证据 |

隔离环境沿用D71副本：WordPress 7.0.4、WooCommerce 11.0.0、Storefront 4.6.2、PHP 8.2.29、HTTP 17171、MySQL 17172。测试使用`quotes@dentall.test`和TEST订单，未发送邮件、未调用外部服务、未启用支付。

## 2026-09-10 W12合成树收口

D71/D72源提交`ff92cdc`已纳入D67→D70线性候选，在`codex/day72-w12-integration@7176a3f`形成DentAll 0.40.0/Core 0.2.9合成树；终验文档`56f3a2a`随后由`6b5c96e`一并合入并推送`origin/main`。Header Cart同步和人工运费报价两套Cart资源同时保留。重新同步28个自定义运行文件后，PHP/JS纯合同36/36与23/23、D71金额177/177、D72报价18/18、Variable/长属性/Coupon/空态67/67全部通过；Header正常/失败恢复/Variable/Mini Cart/四宽/BFCache及五组竞态状态机也全部通过。

Variable/Coupon首轮唯一P1来自复用测试脚本期待`TESTD72Size/TESTD72Shade`，而当前夹具按`TESTD71Size/TESTD71Shade`命名。仅修正忽略目录中的测试期待并完整重跑67/67，运行代码没有因此修改。最终标记订单、checkout draft和报价TEST option均为0，商品与Variation锁/快照不存在，17171/17172监听和PID文件为0。

这份证据允许W12候选进入主线，不代表Staging已部署。D66的RSK-035/037/038、正式报价邮箱、真实邮件客户端、SMTP、支付沙盒、Express钱包、真实税费/物流和缓存仍按发布门槛分别验收。

主线与远端SHA核验完成后，`fb49`已从Git工作树登记移除，源目录内容为0文件/0子目录。68份脱敏结果、测试脚本和TEST截图共4,847,866字节保存到主项目Git忽略归档，清单SHA-256为`70be729e537f8d4ce22e31f80d0c7e5899d4b4c4a203c03e016b41972f455820`；复制哈希差异、高置信密钥命中和禁入文件均为0。数据库、SQL、凭据、客户端配置、日志、密钥、WordPress副本和浏览器配置未归档并已清理。Windows因当前Codex任务仍占用原路径，只保留一个0文件/0子目录空壳，其他工作树未删除。

## 数据、URL、SEO、缓存与部署影响

- 数据：新增一个可为空的Woo option；当前候选库最终已删除TEST值。正式值部署后是配置数据。订单继续使用Woo CRUD/HPOS兼容对象与原生明细。
- URL/SEO：无新公共URL、Canonical、Schema、robots或Sitemap；普通Checkout会回Cart，`order-pay`/`order-received`放行。
- 支付与库存：报价前不付款、不创建正式订单、不扣/预留库存；业务确认后才建立待付款订单。实体/无法解析商品订单缺Shipping明细时付款守卫安全失败；真实支付、扣减、退款和邮件仍归后续日验证。
- 物流与税：不配置固定费率、实时承运商或税率；业务人员在订单中分别维护Shipping、Tax和Fee。
- 缓存：新增Cart页一个条件JS请求，版本0.38.0刷新缓存键；Cart、Checkout和Order Pay仍不得页面缓存。没有新transient、远程请求或Cron。
- 部署：未改共享Local、Staging、Production、DNS或真实支付。正式邮箱是Staging业务验收前置，不是当前编码阻塞。

## 风险与后续

- `mailto:`依赖访客设备的默认邮件客户端；上线前需在主要浏览器和真实设备验证。
- 邮箱会出现在Cart页面源数据中，可能被抓取；业务方应使用专用公司邮箱并建立垃圾邮件处理规则。
- 大量商品可能导致邮件URL过长；若真实业务出现大批量、多地址或高频报价，评估站内表单/成熟报价插件，不在本版继续堆代码。
- 员工必须按SOP分别新增Shipping、Tax、Fee并Recalculate；付款链接发出前复核币种、收货地、商品、优惠和总额。
- 若业务人员修改已报价订单的Shipping公司/完整地址，或Woo按Billing计税时修改账单国家、州省、邮编或城市，必须重新报价并重新核对总额；不能让客户沿用旧付款链接自行改目的地。
- 真实支付启用前必须验证并关闭实体Cart/Product中的Express Checkout；Apple Pay、Google Pay或PayPal Express不得绕过人工报价，只在已报价`order-pay`路径开放经验证的网关。
- 当前不新增报价订单meta，而用商品配送属性与Shipping line推断边界；商品以后在physical/virtual间切换、删除商品或处理历史待付单时须在D76/D78回归。Cart邮件点击是Woo 11持续loading的最小DOM兼容层，升级或新增点击分析时须回归。
- Contact Us公司WhatsApp、正式SMTP/邮件投递、支付沙盒、订单邮件、库存扣减/回补、税务与非Local缓存仍待对应开发日验收。

## 可复用核心思想

### 跨平台不变量

人工报价不是“把运费显示成0”，而是一个明确的交易闸门：报价前只有购物意向，报价后才形成可支付的最终订单。最终金额必须保留商品、运费、税和其他费用的明细来源，前端按钮不能成为唯一安全边界。

### WordPress/WooCommerce当前实现

WooCommerce的原生Order、Shipping、Tax、Fee、Recalculate和`order-pay`足以承载第一版最终交易；DentAll只补“何时允许进入付款”的业务规则和Cart邮件入口。无配送方法时不能依赖`WC_Cart::needs_shipping()`，需要从商品自身配送属性建立独立、可测试的Store API事实。

### Shopify或其他平台的对应机制

其他平台仍需区分报价请求、最终订单、分项费用和付款授权，但具体Draft Order、Shipping line、税务与付款链接机制必须按该平台官方能力重新验证。本项目不因知识对照而增加Shopify实施范围。
