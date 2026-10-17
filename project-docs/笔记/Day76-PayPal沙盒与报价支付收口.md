---
类型: 项目Day笔记
项目: DentAll WooCommerce
日期: 2026-10-17
工作日: Day76
状态: 隔离修复与连接准备；真实Sandbox及Staging待窗口
验收层级: 本地代码候选
---

# Day76：PayPal沙盒与报价支付收口

## 相关笔记

- 前置：[[Day73-报价字段与客户身份合同]]、[[Day74-订单摘要与四端结账]]、[[Day75-人工物流与报价72小时生命周期]]。
- 学习：[[WordPress实战笔记/Day76-支付中间态与扣款前守卫]]。
- 后续执行基线：[[Day107-系统回归准备与执行包]]；D78联合交易验收在本篇矩阵通过后收口。

## 授权、基线与三个验收结果

2026-10-17总协调任务「D73～D120｜业务资料与进度总盘点」中，用户回复“可以，下发吧”，授权A/B/D并行收尾；本任务负责B交易包。原有继续配置PayPal授权沿用。协调任务ID为`01a14766-acd0-7471-93da-06be0e902a00`，总盘点位于主目录同名2026-10-17笔记。没有分配共享Staging写入或交易窗口，现场动作须串行安排。

1. 复现并最小修复报价中间态P1，完成独立代码、安全和原生交易复核。
2. 核清Sandbox连接和回调前置材料；活动配置以现场证据为准。
3. 准备D74/D76/D78共用矩阵，窗口内用新TEST报价执行并保留对账、邮件、库存证据。

当前工作树为本任务自己的`dd59/dentall`，初始干净、detached于`3eb52db`；已建立`codex/day76-payment-state-fix`，基于已推送的整合候选`e078fc5`。本轮远端只读核对`main=e7caa3a`，主目录旧`main=e9b5d2b`及用户文档未动。Core补丁候选为0.9.2；最终统一发布版本由协调方决定。

`e078fc5`并不包含已部署D79视觉`1b02567`及全部后续修复；只交付本任务增量，不能将本工作树整目录覆盖现场。共享Local、Staging、Production均未部署本轮候选。

## 修复结果与职责

- 生命周期将`pending/failed/on-hold`视为尚未结算的报价，进入`on-hold`保留截止时间和Action；客户发起新付款仍仅限`pending/failed`。
- 完成回调允许有效`on-hold`，继续复核首次签发、正Shipping、完整地址、签名、期限和永久关闭标记。过期、已关闭或被实质修改的报价不能复活。
- 商品行签名只排除Woo扣库存时新增的`_reduced_stock`，数量、规格、其他自定义meta、金额、税、优惠、地址和归属仍受保护。
- 停用预检整批在途报价；有候选`on-hold`就拒绝停用，保留守卫与任务。取消前重读订单，避免预检旧对象覆盖已付款状态。此措施不保证Core停用后仍能防所有网关回调；维护前仍需停止新付款、排空并对账在途交易。
- 独立`shipping-quote-paypal.php`承担网关依赖边界：PayPal创建前校验原生key＋付款权限及报价；网关处理前复核；真正Capture前按原生Purchase Unit的`custom_id`重读订单复核。使用网关公开Hook，未修改第三方核心。
- 已付款报价的重复处理返回“不再处理”，保留网关原生成功响应；创建及再次Capture仍拒绝旧单，避免原生失败处理把已付款订单降为`failed`并返库存。

生命周期与PayPal适配分文件，是因为外部插件的入口、异常类型及升级节奏与报价合同不同；复用同一报价判定，没有第二套金额算法、新字段、迁移、插件或通用HTTP拦截。

## 实际验证与证据边界

修复前既有纯合同101/101通过；补上D76用例后出现7项失败，证明确有覆盖缺口。独立真实Woo＋HPOS复现：订单进入`on-hold`写`closed_reason=on-hold`并撤到期Action，`payment_complete()`返回true，但fresh订单无付款日期/交易号、完成事件为0；商品库存50→48，新增库存行meta使签名不匹配。第一版只改状态的候选仍未通过管理库存路径，已据此补精确签名排除。

本轮主Agent实际执行：

```text
php -n project-docs/tests/day72-shipping-quote-php-unit.php        65/65
php -n project-docs/tests/day75-shipping-quote-lifecycle-unit.php 123/123
php -n project-docs/tests/day76-paypal-quote-unit.php              44/44
node project-docs/tests/day72-shipping-quote-unit.mjs             46/46
dentall-core下PHP语法                                           13/13
git diff --check                                               通过
```

Day76脚本复用并先执行D75测试，不把其中123项重复计数。停用错误输出来自故障注入的预期分支。首次Day76只读快照测试把夹具getter追踪误算成持久化变化，已修正测试快照范围后通过，未据此修改产品。

独立Review与安全审查均已核对最终文件指纹，约定的普通钱包立即CAPTURE代码范围内未保留P0/P1。原生独立矩阵167/167通过，环境为新建空库的WordPress7.1、WooCommerce11.0.0、PHP8.2.29、MySQL8.4.0及`OrdersTableDataStore` HPOS；原始报告为`before.json`、`after-stage1.json`、`after-stage2-149pass.json`及最终`after.json`，保留失败到成功轨迹。最终运行阶段HTTP外呼为0，72次邮件尝试均在本机拦截；环境安装阶段另有被拦截的默认HTTP/邮件，不算本次交易请求。

原生167项矩阵覆盖管理/不管理库存、pending/failed/on-hold完成、到期、实质变更、取消/闭单、重复完成、回库/优惠券记账及停用预检；使用真实PayPal4.1.3实体和`OrderEndpoint::capture`，无效报价在Bearer调用前阻断，合法报价能到Bearer哨兵。真实`CheckoutOrderApproved::handle_request`对已processing/completed重复批准返回成功且fresh状态/付款日期/交易号/库存/优惠券不变；失效on-hold由网关异常分支捕获并返回不成功，没有进入订单处理。这些均未连接PayPal网络。

补充原生退款21/21通过，独立证据为`refund.json`：全额退款、先部分再全额、明确`restock_items=true`回库及退款后晚到完成不复活；使用`wc_create_refund(refund_payment=false)`，没有PayPal退款API调用。真实库存48→49→50（分次）或48→50（全额）；优惠券用量保持1，不能写成“退款自动恢复券”。退款阶段另有7次邮件在本机拦截。新PHP进程对11组已存订单复读通过；这些补证与167项分列，不重复包装为远端资金验证。

退款另以新PHP进程复读2/2。共用可比原生合同为80项：HEAD基线33项失败，第一版状态修复8项失败，最终消除；扩展到167项后的最终结果均通过。本地完整脚本实际执行顺序如下（路径均在本工作树私人测试目录）：

```text
prepare.ps1 → install.php → finish-install.php
php -c php.ini native-matrix.php before
php -c php.ini native-matrix.php after-stage1
php -c php.ini native-matrix.php after
php -c php.ini persistence-and-cleanup.php
php -c php.ini refund-matrix.php
php -c php.ini refund-matrix.php verify-cleanup
stop.ps1
```

终态证据`cleanup.json`、`refund-cleanup.json`、`cleanup-stop.json`：新TEST订单/退款、商品、券及专属报价待执行任务为0，独占测试库已删除，17676监听及专属MySQL父子进程为0。测试库本就可丢弃，不保留数据库备份；可追踪的脚本和脱敏结果继续保留，没有删除共享Local或其他工作树资料。

私人原始证据位于本工作树`.codex-tmp/day76-test-native/evidence/`，审查报告在`.codex-tmp/day76-review/`与`.codex-tmp/day76-security/`，不进入Git或公开网页。产品与测试副本指纹一致：

| 文件 | SHA-256 |
|---|---|
| Core入口 | `8a2b90280a6af9efd55a69e4377c2f70b11583e4f3a9cb1a4aad0ded7ce422da` |
| 生命周期 | `63f8a7408148dfc250293bcc9b896339503c645fac77da96425d63ba402e1729` |
| PayPal适配 | `d4987690a8a096b3239f31683dbdfe1d05176422ebf5567b4e09d3f1235e8333` |

这不是PayPal真实Sandbox交易证据：当前测试使用原生Woo和网关实体/入口，在HTTP边界以哨兵阻断外呼。没有真实资金、Sandbox远端交易、BossMail投递、目标四端或公网回调通过记录。

## 连接前的最小现场核对

以下均为待执行，不能根据插件目录或Apps=1填写“已连通”。

| 项目 | 检查与准入 | 操作者 |
|---|---|---|
| 插件与活动状态 | 读取目标WP/Woo/PayPal版本、活动网关；本地磁盘PayPal4.1.3仅为源码事实 | 开发 |
| 商户及买家 | 中国Business收款、美国Personal付款，中国Personal备用；验证登录、地区和虚拟USD资金 | 公司授权人员＋开发 |
| 商户连接 | Woo付款设置中确认Sandbox、商户标识及连接成功；普通向导优先 | 公司授权人员首次连接 |
| API凭据 | 只有手工连接/排错需要时由授权人员在后台填Sandbox凭据；不索取Developer主账号密码/MFA/Secret | 公司授权人员 |
| 本轮付款方式 | 先核普通PayPal钱包立即Capture；保存付款方式/Vault、卡、Authorize Only、APM及其他钱包未验，不将其混入通过口径 | 开发记录，配置变化按窗口范围执行 |
| 快捷入口 | Product/Cart快捷付款须关闭，不能绕过WM人工报价；验证伪造创建请求也拒绝 | 开发 |
| Webhook | 核验已订阅当前Sandbox应用、目标URL为公网HTTPS443、实际投递/验签通过；`PAYPAL_WEBHOOK_REQUEST_VERIFICATION`不得为false | 开发＋授权人员排错 |
| 访问与缓存 | Coming Soon不能挡受控Guest付款和回调；保留noindex/商店边界；order-pay、订单完成及回调不缓存，不全站开放预览 | 开发按精确路径验证 |
| 环境隔离 | Local/Staging独立Invoice Prefix、无Live凭据；TEST邮箱与订单不会触发真实发货或污染正式业务 | 开发＋WM |
| 邮件 | 沿用FluentSMTP＋BossMail单处理器；收件材料邮箱既定，不重开D80已验找回邮件 | 开发＋WM |

官方依据（2026-10-17核对）：[WooCommerce连接及Sandbox向导](https://woocommerce.com/document/woocommerce-paypal-payments/account-setup-and-onboarding/)、[PayPal Webhook接收与重试要求](https://developer.paypal.com/api/rest/webhooks/rest/)。监听端点必须接收HTTPS并成功返回2xx；模拟通知不能替代真实Sandbox交易通知和验签。

## D74→D76→D78窗口执行矩阵

由协调方先核收增量、保留D79视觉及其他必要修复，再分配共享Staging窗口。创建前记录版本、HPOS、关键配置、订单/商品/队列基线和同点备份；使用新订单，禁止复用已取消的#1579/#1774。只对TEST对象做清理，不用数据库回滚覆盖同期编辑。

| 执行组 | 新TEST操作 | 验收事实 |
|---|---|---|
| D74有效报价 | WM建完整地址、USD金额和正Shipping；首次成功付款邮件 | UTC截止＋站点时区准确，重发不续期，金额逐项与Woo一致 |
| D74身份/四端 | Guest正确/错误key及邮箱、订单本人/他人，390/768/1024/1440 | 无越权/共享缓存，正确身份可达；无布局及脚本阻塞 |
| D73地区 | Shipping US/CA/AU正向及GB负向；Billing GB等非白名单 | 配送白名单只约束Shipping；Billing按原生国家规则 |
| D76成功 | 美国Personal付中国Business；观察网关及Woo | 只Capture一次、transaction ID/金额/币种/付款日期可对账；状态、库存和优惠券一次记账 |
| D76取消/失败 | 关闭PayPal或主动取消，使用支持的Sandbox负例 | 不误扣、不误记已付；允许的有效重试不延长截止 |
| D76中间态/重复 | 经可触发的真实通知/原生重送验证on-hold、重复approved/completed | 不提前关闭；同一交易不重复扣库/用券，已付不降失败 |
| D75到期 | 新TEST报价经受控方式覆盖到期/实时拒付、Action及客户停留后再确认 | 旧报价不复活；Capture调用前再次复核，真实72h时钟另留证据 |
| D75变更/归属 | 地址/金额/数量/Customer ID变更 | 旧单关闭且不自动改签；WM建立替换报价 |
| D78退款 | Sandbox全额/部分退款及明确选择回库存 | 网关退款额、Woo退款记录与库存一致；不用“全额退款必定恢复券”等推测替代实测 |
| D78晚到与对账 | 已过期/关闭后重送通知 | 不重开付款；若远端先前已收款而通知晚到，记录差异并由WM/财务对账处理，不声称本地取消等于远端退款 |
| D77支付邮件 | 付款、取消、退款的适用邮件 | BossMail真实受控收件，订单/金额/状态正确；不复用D80证据替代 |
| 终态 | 核对TEST清单、库存/券/队列、邮件与日志 | 保留必要脱敏证据、按对象清理；恢复限定配置，不影响内容编辑 |

目前未分配窗口，因此以上目标尚未执行。普通Guest链不等待全部账户/Contact/SEO；注册、验证归户、替换报价由账户线在联合窗口衔接。

## 影响、减法审查与交付层次

- 运行变化只涉及Core入口、生命周期和PayPal适配；没有主题/前端、公共URL、SEO、缓存策略、正式运费/税费变更，未增加远程调用或Cron种类。多出的校验在相关支付入口、订单保存/状态变化及低频停用运行。
- 金额和库存继续由Woo处理；本轮改变报价签名和支付准入。已关闭的历史报价不自动清除关闭标记、重签或复活；现场测试用新单。
- 减法审查：3个运行文件合计新增172行/删除18行，净增154行；其中新建1个114行适配模块，新增4个函数/3个公开Hook/1个状态常量。既有单测净增118行，新入口合同138行。没有新表、字段、后台入口、JS、CSS、依赖或通用拦截层。保留代码分别用于原生库存兼容、中间态期限、外部扣款前守卫和停用预检，没有一般化框架。
- 本地修复与独立复核已完成；Git目前为本任务分支的可审查未提交差异，未提交/推送、未合并main、未写部署分支、未部署或验收Staging。D74、D76、D78/M6均不因本地通过而整体标Done。
- 最小回退是撤销本任务的三个运行文件增量，恢复上一Core版本；支付窗口后若已产生在途交易，须先对账再安排回退，不能直接停用Core或只恢复数据库。

## 可复用核心思想

- 跨平台：创建支付、外部扣款、异步通知和站内记账是不同事件；必须在不可逆副作用之前校验，在重复通知时保持幂等。
- WooCommerce：`payment_complete()`返回true不保证订单已付款；fresh CRUD状态、付款日期、交易号及库存/券事实才是证据。签名保护业务条件，精确排除已确认的运行元数据。
- 其他平台：原则可迁移，但状态名称、授权捕获和回调验证须按目标平台重查；本轮未验证Shopify支付机制。
