---
项目: DentAll WooCommerce
日期: 2026-09-08
工作日: D71
计划检查点: D71（不自动等于一个完整实际工作日）
周次: W12
实际有效工时: 用户未记录
验收层级: D68运行候选之上的独立Local交易摘要技术验证
状态: 批准范围已验证；候选未合并、未推送、未部署
---

# Day71 运费、税费与金额摘要候选验证

## 相关笔记

- 每日笔记索引：[[README|DentAll每日笔记索引]]。
- Cart Block响应式前置：[[Day68-手机与平板响应式购物车候选验证]]。
- 当日学习笔记：[[WordPress实战笔记/Day71-WooCommerce运费税费与金额真相]]。
- 后续集成：[[Day72-人工运费邮件报价与购物车收口]]。

## 先给结论

D71批准范围已在独立Local副本完成候选技术验证。运行树继承D67→D68候选，DentAll保持`0.37.0`；D71没有新增或修改运行代码。WooCommerce 11.0.0原生`WC_Cart`、Shipping/Tax API、Store API与Cart Block已经能够在既有地址状态下统一计算并展示商品、优惠、TEST运费、TEST税费和总计，因此不为“看起来方便”复制一套客户端金额公式。

主测试报告为177/177断言通过，P0/P1失败为0；覆盖未定位、CA、NY、不可配送国家、已计算但无可用方式、无效国家、网络失败重试、Simple/Variation物流字段继承和覆盖、数量、缺货、匿名A/B会话、登录Customer持久购物车、含税/未税展示、逐行/小计舍入及六档视口。全部费率和税率均为隔离TEST口径，不代表DentAll正式配送报价或税务结论。

本结论有两个必须保留的边界：当前Cart Block页面没有向访客提供Cart内地址/地区编辑表单；地区切换是通过Woo原生`cart/update-customer`合同验证，不等于“Cart内运费计算器已交付”。此外，`total_shipping="0"`只有结合当前可选rate才能解释；本轮无rate的`0`明确不是免邮。

## 授权、业务问题与责任边界

用户已明确授权拆分新任务实际实现D71；本任务只处理D71，不代替D72集成收口。

- 业务问题：证明购物车中地区、物流字段、优惠、税费与库存变化能够触发Woo服务端重算，并让Store API与Cart Block表达同一金额真相。
- 使用角色：匿名访客和登录Customer；测试Customer只存在于独立副本，凭据不记录，测试后删除并由整库恢复兜底。
- 数据来源与规模：复用TEST Simple #44、Variable #46及Variation #51/#52/#53；仅用少量代表状态验证通用骨架，不冻结正式商品事实。
- 开发者负责：保持单一服务端事实源、建立隔离护栏、验证状态与恢复、记录实现边界。
- Website Manager/业务方负责：正式销售国家、配送区域、税务口径、价格、库存、重量/尺寸、合法Variation、免邮门槛和文案事实。
- 本轮明确不做：正式税率、正式费率、Shipping Class、体积重、包裹拆分、承运商/API、Cart内自定义地址表单、Checkout字段、订单、支付、退款、邮件、库存扣减/回补、Staging/Production部署。

## 三个验收结果

- [x] 未定位、已定位、不可配送、无可用方式与恢复路径均返回可区分的原生状态；地区、数量、Variation和库存变化后，旧费率不参与金额，缺货与超库存由服务端拒绝。
- [x] `WC_Cart`服务端getter、Store API最小货币单位字符串和Cart Block可见金额一致；未税/含税与逐行/小计舍入均由Woo计算，没有重复加税或客户端重算。
- [x] 390、768、1024、1199、1200、1440px无页面/购物车/摘要横向溢出或金额碰撞，键盘焦点可见；独立库、TEST Customer、商品库存和源Local均按恢复流程核对。

## 基线、继承与最小实现

### Git与运行基线

- 本任务从`main@c9ca48c8489bf351dcb7ce04bc84080528dc68f1`开始。
- 以非快进合并`1fd74cb`继承完整D67→D68候选，形成继承提交`0ca4ba4`；合并后的运行树与D68树一致。
- D70提交`1a304a9`只包含文档/测试证据，且不是D68后继树；D71只把它作为D72参考，不以整树覆盖D71运行基线。
- 运行版本：WordPress 7.0.4、WooCommerce 11.0.0、Storefront 4.6.2、PHP 8.2.29、DentAll 0.37.0、DentAll Core 0.2.8。

### D71运行层净变化

| 项目 | D71增量 | 保留理由 |
|---|---:|---|
| 新增/修改运行文件 | 0 | 原生金额、配送、税费与库存合同满足当前验证目标 |
| 新增函数、Hook、规则块 | 0 | 不复制WooCommerce计算链或提前建立自定义扩展点 |
| 新增JavaScript、模板覆盖、查询 | 0 | Cart Block继续消费Store API，不建立第二状态机 |
| 插件、依赖、字段 | 0 | 正式物流/税务业务尚未确认，不为TEST场景形成生产负担 |
| 主题版本变化 | 0 | 没有运行资源变化，无需制造无意义缓存失效 |

减法审查结论：D71只增加测试证据和中文文档。若为了Cart内主动选地区而新增React/JavaScript、REST代理或客户端金额公式，会同时扩大D73地址、D75物流与安全/缓存范围；当前证据不足以批准该架构。

## WooCommerce金额与配送合同

| 状态 | Store API关键字段 | 正确解释 |
|---|---|---|
| 未提供足够地区 | `has_calculated_shipping=false`，`total_shipping=null` | 运费尚未计算；Cart提示在Checkout计算，不是免费 |
| CA TEST方式 | 当前rate被选中，运费`555`、运费税`40` | 金额单位为美分；TEST固定运费和税率，不是正式报价 |
| NY TEST方式 | CA rate消失，NY rate成为唯一当前选项 | package/rate随地址重新生成；旧rate不能成为金额真相 |
| 加拿大不可配送 | 地址格式可接受，但当前rate数组为空、运费为`0` | 可保存/可计算不等于可配送；`0`不能标为Free |
| TX无可用方式 | `has_calculated_shipping=true`、rate为空、运费为`0` | 已尝试计算但没有方式；应与未计算`null`区分 |
| 无效国家代码 | HTTP 400，既有CA地址与金额不变 | schema拒绝无效输入，不污染当前会话 |

`WC_Cart::get_shipping_total()`在尚未计算时仍可返回数值`0`，而Store API有意输出`null`。这不是金额矛盾；服务端/API一致性断言先比较`has_calculated_shipping`，再按其语义比较运费字段。

## TEST配置与金额证据

所有配置只写入`dentall_day71_fb49_20260908`独立数据库，并在最终交付前整库恢复：

- 币种USD、2位小数、后台重量kg、尺寸cm；Store API的`items_weight`固定以克返回。
- CA TEST Flat Rate `$5.55`及TEST税率`7.25%`；NY TEST Flat Rate `$7.25`及TEST税率`8.875%`；TX区域故意不配置方式。
- 仅为计算链验证创建`test-d71-12-5`（12.5%）TEST coupon，`free_shipping=false`；不验证D70正式优惠规则。
- 分别切换`prices_include_tax`与`tax_round_at_subtotal`，不把任何TEST值带入共享Local或非Local。

| 场景 | 商品 | 优惠 | 运费/运费税 | 商品税/总税 | 总计 | 结果 |
|---|---:|---:|---:|---:|---:|---|
| 未税录价、未定位 | `$24.99` | `$0.00` | `null` | `$0.00/$0.00` | `$24.99` | 尚未计算运费 |
| 未税录价、CA | `$24.99` | `$0.00` | `$5.55/$0.40` | `$1.81/$2.21` | `$32.75` | API、getter、DOM一致 |
| 未税录价、CA＋TEST coupon | `$24.99` | `$3.12`，同步移除税`$0.23` | `$5.55/$0.40` | `$1.81/$1.99` | `$29.41` | 优惠与税额分配由Woo计算 |
| 含税录价、CA | 净额`$23.30`＋商品税`$1.69` | `$0.00` | 净额`$5.55`＋税`$0.40` | —/`$2.09` | `$30.94` | 未重复加税；DOM显示Including税文案 |
| 含税录价、CA＋TEST coupon | 净额`$23.30` | 净额`$2.91`，税`$0.21` | 净额`$5.55`＋税`$0.40` | —/`$1.88` | `$27.82` | API、getter、DOM一致 |
| 两行商品＋coupon，逐行舍入 | `$64.98` | `$8.12` | `$5.55/$0.40` | —/`$4.53` | `$66.94` | Woo逐行舍入结果 |
| 同一输入，小计舍入 | `$64.98` | `$8.12` | `$5.55/$0.40` | —/`$4.52` | `$66.93` | 仅配置变化产生1美分差异 |

上表证明的是WooCommerce 11.0.0在明确TEST输入下的计算合同，不是业务方应采用哪一种录价、舍入或税务政策。

## 商品物流字段与库存证据

- #44为1.2kg；加入购物车后Store API总重为1200g。
- Variation #51的原始重量/尺寸为空，继承父商品#46的2kg与8×8×3cm；与#44同车时总重3200g，数量改为2后总重5200g。
- Variation #53显式覆盖为2.5kg与9×9×4cm；单独加入时总重2500g。
- Variation #52缺货，加购返回HTTP 400及`woocommerce_rest_product_out_of_stock`，购物车仍为空。
- 把#51的隔离TEST库存临时改为1后，前端加号请求返回HTTP 400及`invalid_quantity`，API与输入框数量均保持1，原生alert区域出现；随后用Woo CRUD恢复库存5并由整库恢复复核。

Flat Rate不会自动根据kg/cm调整价格。本轮只能证明重量/尺寸继承、覆盖和总重输入正确；计费重、体积重、Shipping Class、装箱和承运商联动仍归D75。

## 安全、会话与缓存

- Store API写请求缺失Nonce返回401，错误Nonce返回403；没有启用`woocommerce_store_api_disable_nonce_check`。
- 响应中的Nonce、Cart-Token和Cookie只检查“是否存在”，不写入Git、报告或截图；Cart-Token按会话秘密处理。
- Cart GET/写响应均验证`Cache-Control: no-store`；匿名Cart HTML的缓存策略不被本轮改写。
- 旧CA rate和伪造rate均可收到HTTP 200，但Woo忽略无效选择，NY当前rate和金额不变；安全判断依赖最终服务端package/rate，而非仅看状态码。
- A/B匿名浏览器上下文分别保持商品、coupon、地区和rate，未串会话；登录Customer关闭浏览器上下文后，原生持久购物车和NY地址仍能读回。
- `cart/update-customer`会保存`WC_Customer`并重算，不是只读请求；测试前后订单/退款与checkout draft均为0。
- 隔离护栏阻断Cron、XML-RPC、Checkout、支付、邮件和服务端外部HTTP；浏览器发起的既有外部字体请求由测试路由阻断，外部成功响应为0。

## 四端与状态验证

| 视口 | Cart根溢出 | 摘要溢出 | 金额碰撞 | coupon键盘焦点 | 75rem增强层 |
|---:|---:|---:|---|---|---|
| 390 | 0px | 0px | 无 | 可见 | 否 |
| 768 | 0px | 0px | 无；总计标题与金额可正常分行 | 可见 | 否 |
| 1024 | 0px | 0px | 无 | 可见 | 否 |
| 1199 | 0px | 0px | 无 | 可见 | 否 |
| 1200 | 0px | 0px | 无 | 可见 | 是 |
| 1440 | 0px | 0px | 无 | 可见 | 是 |

加载骨架、网络失败后保持旧状态并重试、不可配送、无方式、库存错误、缺货与空车均有动态或继承证据。当前`Add coupons` disclosure在六宽仍为20px，虽然键盘Focus与展开功能正常；这是D68已登记并交D70处理的P2，不在D71越界改样式，D72合成后必须复验。

## Cart内地区入口的真实边界

Page ID 8当前使用`woocommerce/cart-order-summary-shipping-block`。开启Woo原生Shipping Calculator配置后，Cart Block在未定位时仍显示“Shipping will be calculated at checkout”；将候选块名改为`cart-order-summary-shipping-form-block`的隔离探针也没有生成地址表单，随后已恢复原Block内容。

因此本轮地区切换只证明：当会话通过Woo原生Store API或其他Woo流程已有地址时，配送、税费和总计会正确重算。它不能证明访客能直接在Cart内录入地址，也不能证明地址真实、可投递或承运商可服务。D72需要选择接受“到Checkout计算”的原生流程，还是另开功能确认单评估Cart内估算器；地址字段和错误定位继续归D73。

## 缺陷、风险与后续责任

| 级别 | 事项 | 当前结论 | 负责人/计划 |
|---|---|---|---|
| P2 | Cart Block没有Cart内地址/地区编辑入口 | 金额管线通过，但主动地区估算不是已交付UI | D72作产品/架构决定；若需要，另行确认范围并与D73地址合同对齐 |
| 继承P2 | `Add coupons` disclosure为20px | 功能与键盘可用，未达到44px触控目标 | D70处理；D72合成后六宽回归 |
| D72交叉风险 | D69刷新签名只含商品key与数量 | 地址/税区变化时Cart Block可能已更新，而经典Mini Cart小计暂旧 | D72必须做Header、Mini Cart、Cart三者金额联动测试 |
| 既有P2 | RSK-035/037/038 | D71未修改对应商品详情行为 | 保持原负责人和门槛，不以D71关闭 |

没有正式Shipping Zone、税率、免邮、承运商或地址政策，仍是W13前的业务确认风险。本轮不新占风险登记编号，避免D69/D70既有`RSK-039`冲突；D72合成时统一去重编号。

## 恢复、安全与影响

- 隔离身份：独立文件副本、独立数据库`dentall_day71_fb49_20260908`、HTTP 17171、MySQL 17172及独立浏览器上下文；源`D:\LocalWP\dentall`只读。
- 主测试后第一次整库恢复与预测试功能审计逐字段完全相等；基线/恢复审计SHA-256均为`67DBD6A8483B7F904C72D8B9E5FAB7B5B90F8806AF008AE74B64831CEF6C02A6`。
- 数据：正式商品、价格、库存、用户、订单、退款、checkout draft、税率、配送区域、coupon和session均未改变；TEST Customer删除，整库恢复后coupon 0、session 1、税率0、基线区域1。
- URL/SEO：没有改Page ID、slug、Canonical、Title、Meta、Schema、robots或sitemap；未进入Checkout。
- 缓存：未新增缓存、transient或前端资源；Store API响应按原生合同为`no-store`。运行代码不变，因此主题版本不升。
- 支付/物流/邮件：未启用支付、未创建订单、未发送邮件、未接承运商；只有独立库中的TEST固定运费与税率。
- 部署：未改共享Local、Staging、Production、DNS或缓存配置；未合并、未推送。

## 实际验证与未验证项

已执行：PHP/Node/PowerShell语法检查；WooCommerce 11.0.0相关源码只读核对；177/177主自动化断言；`WC_Cart` getter与Store API逐状态比对；六宽Cart Block DOM、Focus、截图和断点边界；Nonce、无效输入、旧/伪造rate、网络失败、会话、登录持久化、库存与缺货负向测试；订单/draft审计；数据库整库恢复与源Local哈希/快照核对；Git差异与敏感信息检查。

独立补证结果与最终恢复/停机证据已汇总到[[Day72-人工运费邮件报价与购物车收口]]；本日没有生成单独的`day71-results.json`，不把不存在的文件列作完成证据，也不把主脚本复述冒充独立测试。

未执行：真实税率/税务合规、真实配送国家与费率、真实免邮、Shipping Class、体积重、包裹拆分、承运商、地址真实性/可投递、实体设备、屏幕阅读器、真实弱网、CDN/页面缓存、Core Web Vitals、Staging/Production、Checkout、支付、订单、退款、库存扣减/回补和邮件。

## 状态与下一步

D71只能表述为“批准范围的独立Local候选技术验证通过，运行代码零新增”。D72应在其自己的集成树中吸收D69、D70、D71候选证据并重新回归，不得把本分支直接当成D72结果；同时解决风险编号冲突、Mini Cart金额同步和Cart内地区入口决定。D66三项P2处置前，仍不提升M5。

## 可复用核心思想

### 跨平台不变量

交易金额必须只有一个权威计算者；UI、缓存和旁路摘要只能消费结果，不能根据看见的单价自行拼总计。`0`、空数组、`null`和错误码是不同状态，必须结合“是否已计算”和当前可选方案解释。测试配置与正式业务政策必须分离，恢复证据与正向断言同等重要。

### WordPress/WooCommerce当前实现

WooCommerce 11.0.0通过`WC_Cart`及Shipping/Tax API计算，再由Store API以最小货币单位字符串输出；Cart Block只负责表达。`cart/update-customer`会清洗、验证、保存`WC_Customer`并重算，写请求由Nonce或Cart-Token保护；`items_weight`以克输出。DentAll子主题本日没有介入计算链。

### Shopify或其他平台的对应机制

其他平台同样要区分服务器报价、配送可用性、税额、折扣、购物车会话和主题显示，但具体Cart API、税务服务、配送配置、缓存与扩展机制并不与WooCommerce一一对应。Shopify对应关系本日未实测，须在目标平台官方文档与沙盒重新验证，不纳入DentAll第一版范围。
