---
项目: DentAll WooCommerce
日期: 2026-09-21
工作日: D73
计划检查点: D73（人工报价字段与客户身份合同）
周次: W13
实际有效工时: 用户未记录
验收层级: 已完成授权范围的隔离Local技术验收；真实支付留D76/D78
状态: 已完成（Local）；未部署Staging/Production
---

# Day73 报价字段与客户身份合同

## 相关笔记

- 每日笔记索引：[[README|DentAll每日笔记索引]]。
- 前置交易边界：[[Day72-人工运费邮件报价与购物车收口]]。
- 后续报价生命周期：[[Day75-人工物流与报价72小时生命周期]]。
- 当日学习笔记：[[WordPress实战笔记/Day73-WooCommerce报价字段与客户身份边界]]。
- 变更与决定：[[../CHANGE_REQUESTS#CR-014：补齐人工报价字段并加入72小时自动失效|CR-014]]、[[../DECISIONS#ADR-040：人工报价订单采用完整地址合同和首次付款邮件起算的72小时生命周期|ADR-040]]。

## 先给结论

> 批次①集成补充（2026-09-22）：Shipping国家仅限美国、加拿大、澳大利亚。Billing国家不限制，州省、邮编等字段是否必填由WooCommerce对应国家的地址合同决定；Billing email仍必须有效。首封付款邮件发送前会校验正数Shipping及完整Billing/Shipping，失败时不发送、不签发、不调度。

D73不再建立实体商品的普通公共Checkout表单。CR-012已经把实体商品流程改为“Cart邮件申请报价 → 业务人员建立WooCommerce待付款订单 → 客户使用`order-pay`付款”，因此本日冻结的是报价邮件、后台订单和付款守卫之间的字段合同。

商品名称、SKU、Variation规格、数量、商品小计和当前coupon由Cart自动带入邮件，客户不需要重新抄写。客户必须提供准确的Billing email、收件人姓名和完整配送地址；账单地址可以与配送地址不同。Company、Address line 2、Phone/WhatsApp和Preferred delivery speed保持可选。

新客户允许免注册支付。邮箱自助注册、登录安全和历史订单归属留D79；业务人员建立报价订单时，只有在核对身份后才显式选择已有Customer，不能仅凭相同邮箱自动猜测并关联账号。

## 授权与业务合同

用户已确认实施D73＋D75，并明确加入72小时自动失效；授权原文登记在CR-014。D73采用以下字段合同：

| 数据 | 来源 | 第一版要求 | 说明 |
|---|---|---|---|
| 商品、SKU、Variation、数量、小计、coupon | 当前WooCommerce Cart | 自动带入 | 客户不重复填写，邮件内容仍需业务人员复核 |
| Billing email | 客户 | 必填且必须是有效邮箱 | 用于订单联系和Guest付款身份核对，不等于邮箱所有权验证 |
| Shipping first/last name | 客户 | 必填 | 与订单Shipping字段对应 |
| Shipping country/state/city/postcode/address 1 | 客户 | 必填 | Shipping国家仅限美国、加拿大、澳大利亚，并按WooCommerce对应国家地址规则验证完整结构化地址 |
| Shipping company/address 2 | 客户 | 可选 | 空值不能阻止报价 |
| Billing address | 客户 | 可与Shipping相同；不同时填写完整结构化地址 | 报价确认后作为锁定内容，变化时必须重新报价 |
| Phone/WhatsApp、Preferred delivery speed | 客户 | 可选 | 不作为付款必填或账号关联依据 |

`mailto:`只生成可编辑邮件草稿，不向WordPress提交字段，因此无法在客户发送邮件前执行站内必填校验。第一版通过明确的Required文案、业务复核，以及订单进入付款前的服务端字段守卫共同约束；若未来要求客户在站内逐字段即时校验，必须另行评估表单、隐私、反垃圾、持久化和邮件队列。

## 三个验收结果

- [x] 邮件模板自动带入商品事实，并清楚区分必填配送/账单字段和可选字段；进口税费口径与D75一致，JavaScript合同46项通过。
- [x] 付款守卫要求正数Shipping、有效Billing email及完整Billing/Shipping资料，并锁定已确认的完整地址和联系资料，纯PHP合同59项通过。
- [x] Guest `order-pay`和已核对Customer的Store API归属路径已在隔离Local覆盖正常、错误订单key、缺字段、地址/项目变化和未签发草稿拒付；四端Cart与付款页浏览器回归通过，测试数据库与源快照隔离。真实网关扣款仍按D76/D78验收。

## 客户身份边界

| 场景 | 第一版处理 | 不做的推断 |
|---|---|---|
| 新客户 | 订单可保持Guest并通过付款链接支付 | 不强迫先注册，不因Guest身份拒付 |
| 客户主动注册 | D79开放邮箱自助注册并验证登录/枚举边界 | D73不提前配置或验收注册表单 |
| 已有客户 | 业务人员核对后，在后台创建订单时显式选择原Customer | 不根据邮箱自动猜测，不把相同邮箱当作身份验证 |
| 无法确认身份 | 保持Guest，付款和订单归属按Guest规则处理 | 不把订单关联给可能错误的账号 |

WooCommerce后续是否把经验证邮箱的历史Guest订单归入新账号，属于D79需要按当前版本实际测试的账户行为；D73不把它写成已交付保证。

## 实现边界与职责

| 层级 | 本日职责 | 明确不负责 |
|---|---|---|
| DentAll子主题 | 在Cart的`mailto:`草稿中表达完整字段合同和费用说明 | 不保存个人信息，不验证邮件是否真实发出 |
| DentAll Core | 在`order-pay`前检查订单资料，并锁定已确认的完整Billing/Shipping内容 | 不自动判断客户身份，不创建用户账号 |
| WooCommerce | 提供Cart、Customer、Order、Address及Guest付款原生对象 | 不根据业务语义自动确认地址真实性或客户身份 |
| 业务人员 | 核对邮件、客户身份和地址，把确认资料写入订单 | 不用相同邮箱代替身份核对，不把缺字段订单发给客户付款 |
| 业务方 | 维护邮箱访问、留存与个人资料处理规则 | 不把开发测试结论当作隐私或税务意见 |

## 当前实际证据

- `php -n -l`已验证报价模块和测试入口语法；`php -n project-docs/tests/day72-shipping-quote-php-unit.php`返回`{"status":"pass","assertions":59}`，覆盖正数、0元、净0及负数Shipping，完整/缺失资料，完整Shipping/Billing锁，姓名、电话、街道和email变化，三种`tax_based_on`、虚拟订单，以及所有交易判断只读WooCommerce `edit`上下文原始值。
- `node --check`已验证主题`shipping-quote.js`和测试入口；`node project-docs/tests/day72-shipping-quote-unit.mjs`返回`{"status":"pass","assertions":46}`，覆盖结构化Shipping、不同Billing地址、准确Billing email、可选联系方式、Cart商品事实和进口费用边界。
- 独立Local使用真实WooCommerce CRUD和Customer Invoice邮件完成40/40建单/邮件/签名及展示Filter负向断言、15/15权限与REST审计；首封实际邮件包含原生付款URL，展示Filter不能改变Shipping、地址、订单状态、订单key或生命周期判断，客户归属变化会使已签发旧单失效。Guest错误key在Woo权限回调阶段拒绝，已登录订单Owner携带有效Store API nonce且无订单key时可通过DentAll生命周期守卫。
- Playwright在390、768、1024、1440px验证Cart邮件入口，四端均无横向溢出、按钮高度至少44px、收件人为隔离测试邮箱且邮件字段完整；四类付款页检查通过，Console Error为0。独立Review最终为P0=0、P1=0、P2=0。
- 本轮只写隔离数据库和Git忽略运行副本，没有修改共享Local、Staging或Production。真实支付成功、库存/coupon最终状态与网关回调仍属D76/D78，SMTP实际投递属D77，账号注册与历史Guest归属属D79。

## 7个专注周期与当前状态

| 周期 | 工作 | 状态 |
|---|---|---|
| C1 | 对照CR-012、现有邮件字段和Woo地址对象 | 已完成只读核对 |
| C2 | 冻结必填/可选字段、Guest及已有Customer边界 | 已由用户确认方向 |
| C3 | 收敛Cart邮件模板 | 已完成；46项JavaScript合同通过 |
| C4 | 收敛订单完整资料付款守卫 | 已完成；59项纯PHP合同通过 |
| C5 | 补齐完整Billing/Shipping锁定与错误文案 | 已完成；真实邮件和四端输出通过 |
| C6 | 运行纯合同、隔离Local与安全负向测试 | 已完成；40/40、15/15及四类付款页通过 |
| C7 | 独立Review、恢复检查和文档证据回填 | 已完成；P0/P1/P2均为0 |

## 数据、URL、SEO、缓存与部署影响

- 数据：D73不新增客户表、询价CPT或表单记录；订单继续使用WooCommerce CRUD和原生Billing/Shipping字段，保持HPOS兼容。
- URL/SEO：不新增公开URL、Canonical、Schema、robots或Sitemap输出；普通Checkout和`order-pay`既有边界保持。
- 隐私：邮件草稿由客户本机邮件客户端发送；正式邮箱的访问、留存和删除由业务方制定SOP。
- 缓存：Cart、Checkout和Order Pay仍是动态交易页面，不得页面缓存。
- 部署：Local授权范围已通过；Staging/Production未部署，目标环境邮件、缓存和支付仍须按发布计划复验。

## 风险与后续

- 邮件字段是提示合同，不是站内受控表单；客户可能漏填或改写，业务人员必须在建单前复核。
- 有效邮箱格式不等于邮箱所有权或客户身份已经验证；账号关联错误会造成订单信息越权。
- 订单付款页面若允许改变已报价地址，会使Shipping、Tax或其他费用失去依据，因此完整Billing/Shipping必须锁定。
- 卖方应否代收Sales Tax、VAT或GST、采用含税还是未税展示，以及计税地址仍待财税负责人确认；D73只冻结字段能力，不作税务判断。
- D74继续验证`order-pay`摘要和四端布局；D79负责注册、登录及历史Guest订单归属行为。

## 可复用核心思想

### 跨平台不变量

交易字段应按“事实来源、必填时点、谁能修改、修改后是否需要重新报价”设计。邮件中的Required标签只能帮助沟通；真正允许付款前，服务端仍要检查订单事实。

### WordPress/WooCommerce当前实现

Cart提供商品事实，WooCommerce订单原生Billing/Shipping字段承载确认后的个人和地址资料，DentAll Core只补付款前完整性与不可变边界。Guest、Customer和User是不同概念，不能只凭邮箱字符串自动合并。

### Shopify或其他平台的对应机制

其他平台也需要区分Guest订单、客户账号、账单地址和配送地址。平台可能提供Draft Order或不同的客户合并规则，具体自动关联和付款身份机制必须按官方能力重新验证。
