---
类型: 项目Day笔记
项目: DentAll WooCommerce
日期: 2026-10-19
工作日: Day95
主题: 转化事件隔离试验与付款真值边界
状态: CR-016第一阶段隔离试验已完成；D95整体未完成
验收层级: 隔离Local候选路线验证
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

最高验收层级是**隔离候选路线验证**。这不是D95的“分析与转化测量基础完成”。实际有效工时未由用户记录，本笔记不以计划周期代填。

## 范围、环境与真实证据

- 用户仅批准CR-016第一阶段：在隔离WordPress副本试验GTM4WP候选，不连接公司Google资产，不安装到共享Local、Staging或Production；首版允许买家付款后完全不返站造成少量漏计，但不得假阳性或重复。公司GA4、GTM、Search Console资产尚未建立。
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
- **范围变化：** 既定D95目标未增加，但原ADR-011“Site Kit原生事件足够”的实施假设已被源码复核推翻；GTM4WP整套事件路线又因购买假阳性未通过。隔离自定义事件源的确认单已列入[[../CHANGE_REQUESTS|CR-017]]，仍待用户明确批准；Google标签归属、CMP和目标环境连接仍需后续确认，不能以“继续D95”代替。
- **资产归属：** 可先用个人Google登录创建公司命名的GA4/GTM新账号，随后在账号级加入至少两名公司控制的管理员并实测权限，再移交或降低个人访问；不能把“换邮箱”理解为直接改掉同一Google登录的地址。具体权限见[GA4用户管理](https://support.google.com/analytics/answer/9305788)与[GTM用户管理](https://support.google.com/tagmanager/answer/6107011?hl=en-GB)。Search Console需以最终正式域名建立与验证。此项尚未执行。
- **D95完成路径：** 确定并批准最小事件源和单一Google tag所有者；建立公司资产、明确同意方案；在隔离环境与独立测试流完成各事件及真实支付负路径，再安排Staging/Production受控接入与D96联合审计。D91～D94也保持各自未Done，不因本试验而自动闭合。

## 可复用核心思想

### 跨平台不变量

“事件只发一次”与“事件代表真交易”是两个独立验收。转化测量必须从可审计的收款事实推导，再处理去重；只按流程状态或页面到达判断容易把未收款订单计成销售。浏览器事件还要明确不可观察路径，不能用猜测数据补齐。

### WordPress/WooCommerce当前实现

本轮GTM4WP 2.0.6按WooCommerce订单状态列表决定`purchase`资格；隔离TEST证明手工状态变化也可满足该门槛。WooCommerce CRUD是正确读取订单事实的接口，但“状态值”本身不足以证明PayPal捕获。DentAll现有PayPal报价格挡只在创建和捕获**之前**复核资格，不是捕获成功通知或测量事件源。

### Shopify或其他平台的对应机制

同样需要分清订单创建、付款授权、实收与事件上报，并分别验证失败、取消、异步通知和重复访问。Shopify具体的付款证明、事件扩展点、同意接口与去重能力尚未在本项目验证，不能照搬WooCommerce Hook或插件设置。
