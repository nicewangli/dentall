---
类型: 项目Day笔记
项目: DentAll WooCommerce
日期: 2026-10-19
复核日期: 2026-10-20
工作日: Day95
主题: 转化事件隔离试验与付款真值边界
状态: CR-016候选已否决；CR-017获批隔离Local实施，D95整体未完成
验收层级: 隔离Local实现与分层验证
tags:
  - DentAll
  - Day95
  - GA4
  - GTM
  - WooCommerce
---

# Day95 转化事件隔离试验与付款真值边界

## 结论

CR-016批准的GTM4WP 2.0.6隔离试验已完成。Shop、Simple商品与Cart的部分`dataLayer`事件可观察，但该插件即使仅允许Processing/Completed订单触发`purchase`，仍会对**没有PayPal捕获或交易号、仅经WooCommerce CRUD手工转成这些状态**的TEST订单输出购买事件。刷新页没有再输出第二次，不能抵消首次误记。GTM4WP单独承担正式购买事件的路线未通过用户“先保证不误记、不重复”的要求。D95未连接公司Google资产，未通过Checkout、真实PayPal、GA4或Consent端到端验收，**不得标为Done**。

## 相关笔记

- 当日学习笔记：[[WordPress实战笔记/Day95-购买事件状态与支付证据边界]]
- 变更与授权：[[../CHANGE_REQUESTS|CR-016完整电商事件来源的隔离兼容试验]]
- 测量架构及待复核边界：[[../DECISIONS|ADR-011]]
- 测量验收项：[[../TEST_PLAN|GA4、GTM与转化测量]]
- 每日笔记索引：[[README|DentAll每日笔记索引]]

本主题没有既有直接同主题的前置学习笔记；后续D95正式实施笔记产生后再建立双向链接。D94的商品Schema属于搜索输出责任，不能拿它代替交易事件的付款证据。

## 最多3项阶段验收

1. [x] 在仅监听`127.0.0.1`的WordPress副本观察已存在页面的候选电商事件，并识别未覆盖路径。
2. [x] 用无实际捕获的TEST订单验证购买误报与刷新重复边界；购买事件准确性**未通过**，因此候选路线淘汰。
3. [ ] 形成并获批新的事件来源、唯一基础标签与同意接入方案；之后在目标环境以Tag Assistant、GA4 DebugView、成功/失败/晚到订单和缓存路径验收。

CR-016的最高验收层级是**隔离候选路线验证**；后续获批CR-017的实现与测试另记于下节。这两项均不等于D95的“分析与转化测量基础完成”。实际有效工时未由用户记录，本笔记不以计划周期代填。

## 范围、环境与真实证据

- CR-016阶段用户仅批准第一阶段：在隔离WordPress副本试验GTM4WP候选，不连接公司Google资产，不安装到共享Local、Staging或Production；首版允许买家付款后完全不返站造成少量漏计，但不得假阳性或重复。当时公司GA4、GTM、Search Console资产尚未建立；CR-017期间用户并行准备，尚未回读ID和权限。
- 隔离版本：WordPress 7.1、WooCommerce 11.0.0、PHP 8.2.29、GTM4WP 2.0.6；服务只绑定回环地址。GTM容器注入关闭，仅观察浏览器本地`dataLayer`；客户与订单个人信息输出关闭。浏览器阻断非回环请求；没有证明PHP服务端所有出站都受监控。
- 已观察：Shop `view_item_list`、Simple `view_item`与`add_to_cart`、Cart `view_cart`。`select_item`未观察；Variable父商品页默认未输出`view_item`。当前人工运费报价闸门使Checkout返回Cart，因此`begin_checkout`、`add_shipping_info`、`add_payment_info`未通过。
- 购买反例：Pending、On hold、Failed、Cancelled在本轮配置下未输出；两个无捕获/交易号却被手工设为Processing或Completed的TEST订单各输出一次`purchase`，原Pending手工改Processing也输出一次。刷新同一感谢页未重复。这里的“每次一次”只说明插件有去重行为，**不说明订单已付款或GA4已接收**。
- [GTM4WP 2.0.6源码](https://github.com/duracelltomi/gtm4wp/blob/2.0.6/src/Modules/WooCommerce/ProductData.php#L824-L899)按配置的订单状态判断购买事件资格；[设置源码](https://github.com/duracelltomi/gtm4wp/blob/2.0.6/src/Modules/WooCommerce/AdminSchema.php#L343-L350)也明确该触发时刻不等于实收。空状态列表并非安全的“关闭购买”开关；保留其他电商事件而独立关闭插件购买事件没有本轮认可的原生配置。
- 本机脱敏试验摘要：`.codex-tmp/day92-94-runtime/evidence/d95-gtm4wp/trial-summary.md`；原始隔离数据库、TEST订单密钥和浏览器材料留在Git忽略区，不纳入仓库或本笔记。

## 恢复与未验证项

执行者已恢复隔离数据库快照及Coming Soon，移出运行中的候选插件、删除临时mu文件并关闭16575/16576端口。独立复核见试验前后核心选项导出SHA256一致、两个端口无监听、Git没有试验运行文件；TEST订单、插件选项和跟踪meta归零由执行者现场查询报告，**没有保留可供第二人复算的恢复后完整数据库导出**。

未做真实PayPal收款、晚到Webhook、正式Checkout、GTM Preview、GA4 DebugView、Consent Mode、目标页面缓存或定量性能测试。用户当前先主动面向美国、加拿大、澳大利亚；Google标签的同意处理仍需根据访客所在地评估。Staging仍维持全站禁止索引；本轮未改共享站点索引、URL、Canonical、Sitemap、Schema、缓存、订单、物流、支付或部署。

## 风险、取舍与下一步

- **P1验收门槛开放：** 不能把Woo订单状态、`is_paid`表象或感谢页刷新去重等同于PayPal实际捕获。候选方案需提出可复核的收款证据与单次上报规则，并在未付、失败、取消、晚到通知及重复访问下做独立交易测试。
- **范围变化：** 既定D95目标未增加，但原ADR-011“Site Kit原生事件足够”的实施假设已被源码复核推翻；GTM4WP整套事件路线又因购买假阳性未通过。用户已于2026-10-19明确批准[[../CHANGE_REQUESTS|CR-017]]仅隔离Local实现；Google标签归属、CMP和目标环境连接仍需后续确认。
- **资产归属：** 可先用个人Google登录创建公司命名的GA4/GTM新账号，随后在账号级加入至少两名公司控制的管理员并实测权限，再移交或降低个人访问；不能把“换邮箱”理解为直接改掉同一Google登录的地址。具体权限见[GA4用户管理](https://support.google.com/analytics/answer/9305788)与[GTM用户管理](https://support.google.com/tagmanager/answer/6107011?hl=en-GB)。Search Console需以最终正式域名建立与验证。此项尚未执行。
- **D95完成路径：** 确定并批准最小事件源和单一Google tag所有者；建立公司资产、明确同意方案；在隔离环境与独立测试流完成各事件及真实支付负路径，再安排Staging/Production受控接入与D96联合审计。D91～D94也保持各自未Done，不因本试验而自动闭合。

## CR-017授权后的隔离实现

用户确认“批准只在Local执行”，同时由用户自行创建GA4/GTM资产；两条工作线互不阻塞。代码仅进入当前独立Git工作树和回环WordPress副本，不安装到共享Local、Staging或Production。

| 文件 | 职责与边界 |
|---|---|
| `plugins/dentall-measurement/dentall-measurement.php` | 插件声明、Woo依赖加载、HPOS兼容声明；没有后台入口或配置迁移 |
| `includes/events.php` | 公开商品字段、经典成功加购片段/下一页短队列、成功Store API响应增量和页面条件加载；不修改原生端点权限 |
| `includes/purchase.php` | 完成捕获Hook三字段证明、订单授权后的只读快照与感谢页购买门禁；不发起或改变付款 |
| `assets/events.js` | 白名单事件、真实成功动作、列表/商品、Blocks及授权order-pay；不注入Google标签或请求外部服务 |
| `project-docs/tests/day95-measurement-contract.php` | 仅隔离Local/WP-CLI可跑的真实Woo CRUD与HPOS捕获合同；TEST对象在finally精确清理 |

- 商品/变体以Woo真实ID为`item_id`，不依赖SKU是否填写；`purchase.value`采用折后不含税商品行金额合计，`tax`/`shipping`独立，捕获证明则核全额订单总额。两种金额有不同职责。
- 只有PayPal `Capture`实体为COMPLETED、最终捕获、custom_id属于订单、网关/金额/币种匹配时保存`_dentall_measurement_paypal_capture`，仅含capture_id/amount/currency。感谢页另核订单key、买家、交易号与证明；不发送capture号、客户资料或key。
- 报价order-pay在Woo `before_woocommerce_pay_form`之后才提供事件数据；Woo要求Guest邮箱验证时，未通过验证不得提前输出，错key和他人账号同样排除。Woo原生新建订单验证宽限期仍由平台处理，测量插件不另设权限规则。该页没有新的配送信息提交，不补造`add_shipping_info`。
- Blocks加购按公开REST生命周期核实际成功201和Cart数量增量，覆盖已有商品再次加购；客户端结合官方成功事件消费，避免Woo缓存的旧载荷重播。配送地址仅在浏览器内存比较，事件与浏览器存储不含地址。
- 静态JS通过enqueue；仅白名单运行时JSON通过`wp_add_inline_script`传入。Blocks不重复输出不用的服务端Cart快照，order-pay/感谢页不因本插件额外加载Blocks Store依赖。没有样式或可见DOM改版。
- 本阶段`sessionStorage`只验证同标签页刷新抑制；跨标签/跨会话仍需GA4网页流transaction_id联验。目标接入前必须将分析存储服从CMP，并清理Google自动page_location/referrer可能携带的订单key/自由搜索输入；当前无Google外传不代表该项已通过。

独立Review已定位并修复StoreAPI已有行漏Hook、缓存事件重播、配送单费率更新漏记，以及REST响应extensions实际为stdClass的类型问题。最终浏览器负例又确认：Blocks的processing状态先于字段错误汇总，仅用其上升沿会在空表单产生`add_payment_info`。现改为公开validation、checkout、payment和cart状态共同通过、需要付款且每个包裹均有选中运费后，在同次processing内锁存一次；没有增加请求、订阅或计时器。该事件表示客户端已准备提交付款信息，仍不证明服务端已接受订单或收款成功。模拟捕获与真实Woo页面结果分别记录，真实PayPal SDK、Webhook、GA4和同意联验仍待后续。

减法审查：运行代码净增4文件、613行，含19个PHP命名函数与6个JS局部命名函数，无CSS/模板覆盖。较确认单300～500行警戒估计多约113行，增加主要用于既有报价order-pay的授权数据、StoreAPI已有行/缓存成功事件、配送自动更新及付款校验边界；均属已批准流程的必要修正。已删除通用CartSchema扩展注册、Blocks重复Cart快照，并依据Woo真实缓存生命周期舍弃额外动作窗口/持久化去重框架；没有后台设置、构建链或通用事件框架。四文件分别对应插件加载、页面/Cart事件、授权订单测量、浏览器状态，保持独立启停而不耦合到Core交易规则。

## CR-017本机验证（2026-10-20）

| 层级 | 实际验证与结果 | 边界 |
|---|---|---|
| 捕获与金额合同 | `wp eval-file project-docs/tests/day95-measurement-contract.php`：真实Woo CRUD/HPOS下22项通过 | Capture实体/Hook为模拟；没有PayPal真实收款 |
| 购买与访问 | 58项浏览器断言通过：捕获证明、状态、金额币种、错key/他人账号、匿名与管理员、刷新抑制 | 同标签页去重；不宣称GA4跨会话已去重 |
| 经典与Store API | 列表、真实链接、Simple/Variable、成功加购、无价/售罄和搜索白名单；Store原生action专项9项通过，覆盖重复加同商品、失败、数量编辑、旧缓存及页面跳转 | HTTP原生端点测试与浏览器真实action分别保留 |
| 报价与结账 | 报价Guest空/错邮箱没有订单快照或begin；验证后付款表单与真实COD提交事件通过。最终Blocks付款负例/正常COD8项通过：空六必填时无事件/无Checkout请求；有效提交HTTP200且只1次`add_payment_info`，感谢页`purchase=0` | COD仅在隔离环境作负例；普通虚拟商品合法经过Blocks。实体商品仍走既有人工报价，不绕过Core |
| 可视与语法 | 商品/Checkout两代表页×390/768/1024/1440共8项HTTP200、无横溢出及pageerror；人工查看390 Checkout和1440商品。PHP四文件lint、JS `node --check`、本机JS合同与`git diff --check`通过 | 不等于全站四端、真实设备或完整视觉验收 |

最终JS合同还覆盖：校验/支付未ready、checkout/payment错误、计算中、缺少某包运费、无需付款时不发付款信息；同一次processing中的错误状态变化不重复。实际项目实体报价页没有新增shipping提交，本轮`add_shipping_info`正向仅为JS合同，不把它记为已通过真实Checkout配送验收。

22项PHP合同与58项购买访问沿用本阶段既有通过结果；最终测试Agent没有重跑这两组，相关购买PHP与JS购买分支未改。最终4运行文件SHA256与副本一致由测试Agent记录，主Agent再次对工作树文件复算一致。PHP合同的复验方式是在保留邮件/HTTP/Cron阻断、仅回环且启用测量插件的隔离副本内，用WP-CLI `eval-file`指向本工作树`project-docs/tests/day95-measurement-contract.php`；脚本自带环境闸门和finally精确清理，不能在共享站点运行。

失败证据原样保留：旧`final-browser`停在报价夹具；后续fresh订单处于Woo邮箱验证宽限期，修改TEST创建时间后验证原生邮箱门禁；`finish-invalid`真实复现了processing误报，最终`finish-payment-fixed`记录修复结果。没有覆盖旧失败材料。证据目录为本机Git忽略区`.codex-tmp/day95-measurement-test/`；数据库快照、测试口令、订单key与原始浏览器材料不进仓库。

### 资源与SEO输出配对

| 页面 | 停用→启用HTML字节 | script标签 | 单次查询样本 |
|---|---|---|---|
| Shop | 72,842→74,410（+1,568） | 24→26 | 117→103 |
| 商品 | 80,311→81,022（+711） | 35→37 | 64→50 |
| 空Cart | 297,283→297,894（+611） | 94→96 | 64→50 |

每页新增1个插件外部脚本与1个运行时配置标签；JS源文件11,366字节，未把它伪称为压缩传输体积。三个页面的Title、Canonical、robots和Schema配对一致。查询是单次顺序样本，缓存与预热未作为受控变量，不能解释为查询优化或性能提升；没有真实CDN、页面缓存、Core Web Vitals或支付SDK耗时结论。

### 配对恢复

独立测试Agent恢复完整原始隔离数据库、配置和mu文件，并保留恢复后的配对SQL供复算；测量运行目录和临时metrics mu已移出，两个端口关闭。主Agent再独立计算原快照与恢复导出的SHA256，均为`0DE1F8164B77CC32713E47F25B0B0DE0AE93F97E67339CE0F12815DF01D8985C`，并复查无监听、运行目录不存在及私有SQL被Git忽略。测试恢复摘要显示D95商品/用户/订单、捕获meta和测量启用行均0。与CR-016不同，本轮保留了可重复核对的完整恢复后导出；它只证明隔离副本恢复，不是共享站点备份或部署验收。

安全摘要：`results/finish-source-hashes.json`、`finish-payment-fixed.json`、`final-store-actions.json`、`finish-visual.json`、`finish-metrics-comparison.json`、`finish-restore-summary.json`及`finish-ports.json`。正式目标接入仍须完成Google资产权限、唯一标签、CMP/地区同意、URL与referrer清理、真实Sandbox/Webhook和GA4端到端联验。

## Google资产准备（2026-10-20）

用户要求在Chrome打开GA4/GTM并代办创建，只在缺真实资料时接手；用户提供公司员工为101～500人（包含工人），将其他报表设置委托处理，且在GA4条款页明确授权接受服务与数据处理条款。公司地区沿用已确认的中国；中国报表时区、USD、健康行业以及销售/潜在客户目标已填。可选额外数据共享关闭。个人Google登录只是当前访问入口，公司命名的独立账号不与既有货运业务混用；公司管理员移交仍未完成。

| 资产 | 页面回读结果 | 当前状态 |
|---|---|---|
| GA4账号 | DentAll，`412639445` | 已创建 |
| 正式媒体资源 | DentAll Production，`559747628` | 已创建，尚无Web数据流 |
| 测试媒体资源 | DentAll Test，`559786936` | 已创建，尚无Web数据流 |
| GTM网站容器 | DentAll账号 / DentAll Web，`GTM-WM2DF8CN` | 已创建，代码列表为空，未配置/发布标签 |

用户说明拟用域名当前有其他网站使用，尚未提供域名；因此两项Web数据流跳过，尚无`G-`衡量ID。GA4账号/媒体资源和GTM容器创建本身不修改DNS、既有网站代码或数据；后续可先提供最终域名作为数据流信息，实际采集仍由网站安装与标签发布决定。本轮没有测试任何已有域名或修改既有货运资产。

GA4在授权后首次尝试操作条款checkbox时发现页面已进入“数据收集”，故没有重复接受或重复创建；随后首页及通用选择器明确显示新账号、Production与Test及各自ID。GTM创建按钮后没有出现新协议弹窗，直接显示安装提示与容器ID；后续代码页明确显示“您还没有任何代码”。不把发起按钮操作当作完成证据。

本机截图保存在Git忽略目录`.codex-tmp/day95-google-assets/`：`ga4-assets-created.png`显示同一DentAll账号下两个媒体资源，`gtm-empty-container.png`显示容器ID及空代码列表；创建前GA4协议截图另保留。Chrome两页已作为本轮交付保留。后续需要最终域名/数据流、公司管理员权限交接、单一Google tag、同意管理及真实PayPal/GA4联验；资产准备不改变D95整体未Done的状态。

## 可复用核心思想

### 跨平台不变量

“事件只发一次”与“事件代表真交易”是两个独立验收。转化测量必须从可审计的收款事实推导，再处理去重；只按流程状态或页面到达判断容易把未收款订单计成销售。浏览器事件还要明确不可观察路径，不能用猜测数据补齐。

### WordPress/WooCommerce当前实现

本轮GTM4WP 2.0.6按WooCommerce订单状态列表决定`purchase`资格；隔离TEST证明手工状态变化也可满足该门槛。WooCommerce CRUD是正确读取订单事实的接口，但“状态值”本身不足以证明PayPal捕获。DentAll现有PayPal报价格挡只在创建和捕获**之前**复核资格，不是捕获成功通知或测量事件源。

### Shopify或其他平台的对应机制

同样需要分清订单创建、付款授权、实收与事件上报，并分别验证失败、取消、异步通知和重复访问。Shopify具体的付款证明、事件扩展点、同意接口与去重能力尚未在本项目验证，不能照搬WooCommerce Hook或插件设置。
