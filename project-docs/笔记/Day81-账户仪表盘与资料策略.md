---
类型: 项目Day笔记
项目: DentAll WooCommerce
日期: 2026-10-07
工作日: Day81
主题: 账户仪表盘与资料策略
状态: 已完成隔离Local技术验收，目标环境待复验
---

# Day81：账户仪表盘与资料策略

## 结论与用户授权

用户在D81/D82功能确认单后回复“按上述 D81/D82 范围实施，并决定第一版登录邮箱策略”。第一版采用确认单推荐项：客户登录邮箱不开放My Account自助修改。D81复用WooCommerce 11.0.0的Dashboard、导航和资料表单；子主题只补登录态响应式样式及邮箱只读提示，`dentall-core`负责服务端身份边界。隔离Local以WordPress 7.1实测，并将My Account原生Page模板设为Storefront Full width，避免默认博客侧栏压窄账户表单；目标环境仍须逐站配置与验收，未部署Staging或Production。

范围与排期：仍对应总计划D81账户仪表盘/资料检查点，未增加新字段或独立流程，D83/D84不前移或并入；新增排期为0个计划工作日。实际工时未由用户记录，不能据此宣称节省工时。目标环境配置与缓存验收单列发布工作。

## 最多3项验收结果

1. Customer A/B/Guest登录态隔离，Dashboard和原生导航在390、768、1024、1440px可用；D83订单列表与详情不在本日验收。
2. 客户可保存姓名、显示名；更换登录邮箱被表单与REST拒绝；改密须当前密码且新密码至少12字符。
3. 页面仅在登录态账户路由加载所需资源；无模板覆盖、跨账户读写、公开URL或交易事实变更。

## 实现与职责边界

| 层级 | 本日职责 |
|---|---|
| WooCommerce | 输出Dashboard、菜单、资料表单；验证Nonce、当前用户、必填与当前密码，执行原生保存 |
| `dentall-core/includes/customer-account.php` | 在`woocommerce_save_account_details_errors`拒绝Customer改登录邮箱和短密码；在`rest_request_before_callbacks`拒绝Customer通过核心用户REST改邮箱或绕过当前密码改密 |
| DentAll子主题 | 登录态账户页条件加载`customer-account.css`；Customer资料端点条件加载`account-email.js`把原生邮箱输入设为`readonly`并关联说明 |

邮箱字段保留原生`name="account_email"`，使WooCommerce的必填提交合同不变。无JavaScript时字段仍可提交，但服务端拒绝变更。Billing email是地址/交易联系方式，不是账户登录邮箱；其变更见D82。管理员人工纠错必须先核验新邮箱控制权、历史Guest归属及已签发报价状态，本日没有新增自动迁移流程。

响应式采用一套Woo语义DOM：小于1024px导航在正文上方，1024px起两列；表单字段保持单列，长文本可换行。Chrome DevTools中选中`.entry-content > .woocommerce`查看Grid列、选中`.woocommerce-MyAccount-content`看父主题Float是否被覆盖；微调应先判断Token、账户公共规则或局部地址规则，再回源码和四宽复验。初次把Grid施加到页面上所有`.woocommerce`时误伤Header Mini Cart，已缩到正文直子节点并增加390px显隐断言。

## 验证证据

| 项目 | 当前结果 |
|---|---|
| 静态检查 | PHP 8.2.9语法、Node语法、`git diff --check`通过 |
| 纯PHP合同 | 26/26通过；仅证明Hook分支，不代替WordPress/Woo真实保存 |
| 隔离Local真实请求 | Customer A的原生表单9项、核心REST旁路10项（原6项＋body/query ID覆盖4项）已通过；订单/报价不变量另见D82 |
| 隔离Local四端浏览器 | Guest及Customer B，5个账户页面×390/768/1024/1440px共20组、213/213断言通过；页面错误0、Console错误0，截图留在私有运行目录 |
| 独立安全/代码复核 | 已修复REST前导零ID、请求参数覆盖URL ID，以及Storefront地址标题清除浮动伪元素；开放P0/P1/P2=0 |

## 七个专注周期对应工作

| 周期 | 本日结果 |
|---|---|
| C1 | 核对D79/D80、主线版本、项目规则及原生账户源码 |
| C2 | 确定第一版登录邮箱策略、资料权限和D83边界 |
| C3 | 实现资料保存及REST服务端守卫 |
| C4 | 实现条件资源和Mobile First账户布局 |
| C5 | 纯PHP合同与独立安全审查，关闭已发现的REST旁路 |
| C6 | 隔离Local真实请求、20组四宽浏览器及D75已签发报价快照通过 |
| C7 | 独立代码/视觉与安全终审开放P0/P1/P2=0；隔离服务已停，目录删除被自动审批拒绝，待允许路径清理 |

## 风险、下一步及系统影响

- WordPress 7.1核心用户REST会允许Customer更新自身资料；路由ID还能被高优先级请求参数覆盖。因此守卫覆盖Customer对核心用户写路由提交的邮箱和密码字段，不只比较URL中的用户ID。工作人员后台编辑仍由原生权限管理。
- WooCommerce原生订单导航和Dashboard订单链接保留；订单列表、详情、再次购买及完整账户链路转D83/D84。目标环境My Account Page须采用Storefront Full width模板，Selling/Shipping国家设置另按D82复核。账户Page标题是否已为英语，须在发布前按第一版英语合同核对；不能把本地CSS当作文案验收。
- 无Schema、字段、插件、远程请求、Cron或公共URL增加；资料保存可能同步默认Billing姓名，但不会重写既有订单。账户页仍须绕过共享整页缓存，目标环境缓存尚未复验。
- 支付、价格、库存、物流报价和部署配置未改。Core/主题版本用于发布后资源失效；Staging与Production未部署，不能宣称目标缓存或真实邮件已通过。

## 相关笔记

- 前置项目笔记：[[Day79-登录注册与订单归属]]、[[Day80-密码重置流程]]
- 对应学习笔记：[[WordPress实战笔记/Day81-WooCommerce账户资料与登录邮箱边界]]
- 后续地址检查点：[[Day82-默认账单与配送地址]]
- 决策：[[../DECISIONS#ADR-045：第一版客户登录邮箱不开放自助修改]]

## 可复用核心思想

- 跨平台不变量：可编辑的联系邮箱与证明账户所有权的登录邮箱必须分开建模；历史订单归属或报价依赖身份时，改邮箱需要新的控制权证据和迁移审计。
- WordPress/WooCommerce当前实现：前台只读是界面提示，真正约束放在Woo资料保存和WordPress核心REST写入口；Nonce、当前密码和角色权限各自解决不同问题。
- Shopify或其他平台：账户邮箱、客户地址、历史订单关联的具体API与验证流程需查目标平台官方合同，不能把Woo Hook或Guest归户行为直接套用，待验证。
