---
项目: DentAll WooCommerce
日期: 2026-09-21
工作日: D79
计划检查点: D79（登录注册策略、错误与安全边界）
周次: W14
实际有效工时: 用户未记录
验收层级: 独立Local技术实现与身份归属验证
状态: 已完成批准的D79 Local范围；真实SMTP、密码重置、支付沙盒、非Local缓存与限频按后续检查点验收
---

# Day79 登录注册与订单归属

## 相关笔记

- 每日笔记索引：[[README|DentAll每日笔记索引]]。
- 当日学习笔记：[[WordPress实战笔记/Day79-WooCommerce账户身份与订单归属]]。
- 前置付款边界：[[Day72-人工运费邮件报价与购物车收口]]。
- 决策与需求：[[../DECISIONS#ADR-042：客户验证邮箱后按唯一账单邮箱归属历史Guest订单|ADR-042]]、[[../REQUIREMENTS#Must：第一版必须完成|R-008]]。
- 后续检查点：D80密码重置、D81账户首页、D82地址管理、D83订单中心、D84账户全链路回归。

## 结论

D79已按确认的A方案形成Local技术候选：客户可以继续以Guest购买，也可以在My Account自行注册。WooCommerce 11.0在客户通过新账户设密或确认邮件证明控制该邮箱后，把同一Billing email且仍为`customer_id=0`的历史Guest订单关联到该Customer；不同邮箱订单和已经归属其他Customer的订单不移动。

2026-09-22，业务方进一步确认：客户不存在多人共用账单邮箱、代理下单或代采购的情况，账户邮箱可以作为第一版唯一订单所有人边界。因此RSK-049的发布NO-GO已经解除；若未来业务模式改变，必须在开放新模式前重新评估历史Guest订单归属规则。

与D75合成后的操作合同是：已签发Guest报价若在邮箱验证后被WooCommerce写入Customer ID，报价签名会因订单归属变化而失效，旧订单自动取消。Website Manager必须复核客户、商品、地址、Shipping、Tax和Fee，并建立及发送一张新的替换报价；不得恢复旧订单、复制旧付款链接、手改生命周期meta或把归户视为自动续期。

DentAll只增加两项上游没有替项目完成的最小职责：

1. `dentall-core`把My Account登录中的“账号不存在／密码错误”统一成同一公开提示，并保留空字段、无效Nonce、限频或其他认证错误的原职责。
2. 子主题复用WooCommerce原生登录/注册DOM，在未登录My Account页条件加载一份Mobile First布局样式；没有复制账户模板或重写注册、会话、邮件和订单归属。

注册成功与重复邮箱失败仍可被比较，Woo原生注册会在重复邮箱错误中显示该邮箱。若改成“统一受理、邮件确认后再创建账号”，将是新的注册流程和D77邮件依赖，不在D79授权范围内。密码找回的统一响应、无效/过期链接和真实投递留D80/D77；公开环境登录限频留主机/WAF或经评估的成熟方案，未在Core自造IP锁。

## 授权、范围与三个验收结果

用户明确回复：“同意按已确认的D79范围实施。”

- [x] 账户设置合同通过：Guest Checkout开启；My Account自助注册开启；Checkout自动开户与WordPress通用注册关闭；用户名和密码由Woo生成，客户通过邮件设密。
- [x] 身份归属合同通过：新账户完成邮件设密后，3张同邮箱代表Guest订单归户；不同邮箱Guest订单保持Guest，已归属B但Billing email为A的订单仍归B；验证后新增Guest订单在重新登录后仍保持Guest，明确关联后才进入A账户。
- [x] 安全与展示通过：登录错误归一化、Nonce、站内重定向、退出、三套浏览器会话、跨客户订单访问、Customer/Website Manager能力、Guest `order-pay`边界、纯虚拟Checkout和390/768/1024/1440px均通过；测试库精确恢复，源Local未写入。

## 第一版业务合同

| 场景 | 第一版行为 | 责任与边界 |
|---|---|---|
| Guest购买 | 不要求先注册 | 订单以Billing email保存，付款链接按密钥管理 |
| My Account注册 | 客户自行提交邮箱；Woo创建`customer`并发送设密链接 | 不开放WordPress通用注册，不让Website Manager创建WordPress用户 |
| 邮箱验证 | 新账户完成设密，或既有未验证客户完成确认邮件 | 证明控制账户邮箱后才执行历史归户 |
| 历史归户 | 同Billing email且仍为Guest的订单改为当前Customer | 业务已确认第一版账户邮箱代表唯一订单所有人；模式变化时重新评估 |
| 已归属订单 | 不因Billing email相同而移动 | `customer_id`优先于邮箱相似性，避免覆盖明确所有者 |
| 验证后新Guest订单 | 普通登录不会再次自动归户 | Website Manager核实身份后在原生订单中明确选客户并保存 |
| 账户地址变化 | 只更新客户默认地址 | 历史订单中的报价地址、Shipping、金额与税地域快照保持不变 |
| 实体商品 | 延续CR-012人工运费报价与`order-pay` | 不恢复公共实体Checkout，不提交真实付款 |
| 全虚拟商品 | 使用相同账户身份规则，可继续Guest Checkout | 不因虚拟商品建立第二套账户或归户逻辑 |

## 七个专注周期

| 周期 | 实际工作 | 结果 |
|---|---|---|
| C1 | 复核D79确认范围、当前设置、Woo 11源码和D72付款边界 | 冻结Guest、自助注册、邮箱验证归户和后置Guest手工关联合同 |
| C2 | 独立安全与测试设计审查 | 明确注册枚举、共享邮箱、Guest付款链接和非Local缓存/限频边界 |
| C3 | 实施Core登录错误归一化与账户页条件样式 | 不覆盖模板、不新增字段/表/接口 |
| C4 | 建立ACL私有的独立文件、MySQL、邮件捕获和浏览器环境 | 源Local只读，外部HTTP、真实邮件、Cron、文件修改均关闭 |
| C5 | 走真实浏览器注册、设密、确认邮箱、归户、退出和订单访问 | 主浏览器审计108项通过 |
| C6 | 验证后置Guest、手工关联、地址快照、权限、四端和Guest付款 | 两轮后置浏览器审计各17项通过；纯PHP16项通过 |
| C7 | 精确清理、整库恢复、源基线复核、独立代码审查和文档收口 | 测试标记归零，即时基线转储精确一致，Review P0～P3为0，服务按精确PID/端口停止；敏感文本清空后私有保留运行目录 |

## 实现边界与文件职责

| 文件 | 职责 | 为什么保留 |
|---|---|---|
| `app/public/wp-content/plugins/dentall-core/includes/customer-account.php` | 只处理Woo My Account有效Nonce登录中的凭据错误归一化 | 安全规则跨主题存在，且需避开wp-login与其他认证入口 |
| `app/public/wp-content/plugins/dentall-core/dentall-core.php` | 源候选升至0.3.1；集成后为0.4.1并同时加载账户与报价生命周期模块 | 主入口继续只做模块加载 |
| `app/public/wp-content/themes/dentall/assets/css/account-auth.css` | 未登录账户页的卡片、间距和1→2列布局 | 有独立页面生命周期，避免把账户规则塞回全站样式 |
| `app/public/wp-content/themes/dentall/inc/setup.php` | 只在My Account且未登录时enqueue账户样式 | 已登录Dashboard不承担无效资源请求 |
| `app/public/wp-content/themes/dentall/style.css` | 源候选升至0.43.0；批次集成后统一为0.44.0 | 为新增静态资源提供缓存版本键 |

测试脚本位于`project-docs/tests/day79-*`，只在明确的`dentall_day79`环回隔离库运行；运行前检查环境、数据库名和私有清单路径。密码、Cookie、Nonce、确认Key、订单Key、邮件正文、SQL和数据库客户端配置均未进入Git。

## 减法审查

- 最终运行差分为新增2个文件、修改3个既有文件，净增209个物理行；新增5个PHP函数、3个Hook注册和12个CSS选择器规则块（其中2个在1个`48rem`媒体查询）。这些增量分别承载跨主题登录公开错误边界、未登录账户页条件enqueue和同一Woo原生DOM的响应式布局；没有可由现有模块安全合并而不混淆生命周期的重复职责。
- 测试层新增4个隔离脚本，共1,166个物理行，分别负责纯PHP登录合同、Woo CRUD夹具/恢复、主浏览器矩阵和后置Guest可见性；它们不进入前台请求或发布运行层。账户与订单归属属于高风险数据边界，因此保留独立负向、恢复和四端证据，不把Woo原生实现复制进产品代码。
- 没有WooCommerce模板覆盖、JavaScript、账户CPT、客户字段、REST/AJAX端点、定时任务、插件依赖或自定义归户算法。
- 没有把原生账户设置锁死在PHP中；设置继续由各环境WooCommerce后台明确重放和审计。
- 没有把注册枚举包装成“已解决”；改变注册创建时机需要重新确认范围。
- 没有自造IP限频、邮箱队列或付款授权；这些能力需要主机/邮件/支付环境证据。
- 展示与业务安全分开：账户CSS留在子主题，跨主题登录反馈规则留在Core。

## 配置重放清单

未来每个目标环境须由授权人员逐项保存并复核，Git不会同步这些数据库设置：

1. WooCommerce → Settings → Accounts & Privacy：允许Guest Checkout。
2. 允许客户在My Account页面创建账户。
3. 不允许客户在Checkout期间创建账户；不启用Checkout登录提醒作为强制门槛。
4. 自动生成用户名；通过邮件发送设置密码链接。
5. Settings → General：`Anyone can register`保持关闭。
6. My Account页面保持WooCommerce原生绑定；账户、Checkout和`order-pay`不得进入共享整页缓存。

本轮只在隔离Local设置这些值。共享Local、Staging和Production均未修改。

## 动态验证证据

### 环境

- 独立Local：WordPress 7.1、WooCommerce 11.0.0、Storefront 4.6.2、PHP 8.2.29、D79源候选DentAll 0.43.0、DentAll Core 0.3.1；批次集成为DentAll 0.44.0、Core 0.4.1。
- HTTP与MySQL只监听`127.0.0.1`；运行根ACL只有`SYSTEM`和本机`Administrator`。
- 使用随机`example.test`身份、独立浏览器Context和WooCommerce CRUD/HPOS兼容订单；未创建真实客户、Completed订单或付款。

### 结果

| 验证 | 结果 |
|---|---|
| 登录错误纯PHP合同 | 16/16；三类凭据错误同文案且原始审计错误码不变，无效Nonce、畸形字段与空字段保持原文案，附加限频/MFA类错误优先显示自身提示 |
| 主浏览器矩阵 | 108/108；注册、设密、确认邮件、错误账户、重放、60秒重发、登录/退出、站内redirect、Guest付款、纯虚拟/实体Checkout与跨客户访问通过 |
| 后置Guest | 17/17；已验证A重新登录后仍看不到后置Guest订单 |
| 明确关联 | 17/17；手工把后置订单关联A后，A可见、B与匿名不可见 |
| 四端 | 390为单列，768/1024/1440为双列；0横向溢出、0重复ID、标签关联完整、提交按钮44px、Console/Page error均为0 |
| 权限 | Customer只有原生客户能力；Website Manager仍有订单/Woo管理和`create_customers`，无`edit_users`、插件、主题能力 |
| 地址快照 | 修改Customer默认Billing/Shipping后，已报价订单地址、Shipping与金额快照逐字段不变 |
| 数据清理 | 精确删除7单、2商品、4用户后marker为0；最终恢复到本轮测试前即时基线，转储SHA-256精确一致；17979/17980监听与记录进程为0 |
| 源Local | 前后D79相关设置、用户/文章计数与`wp-config.php` SHA-256一致；隔离数据库账号读取源库被拒绝 |
| 私有证据 | 29份含凭据、邮件、Token、SQL或日志的文本已清空；停服运行目录ACL仅`SYSTEM`与本机管理员，另保留无邮箱/秘密的结果JSON和四端截图共6份 |

首轮视觉测试暴露了隔离PHP Router未规范化Windows路径，静态资源被错误当作WordPress请求，产生脚本解析错误和21px未加载样式按钮；只修复测试运行时Router后重跑，正式产品代码未因此改变。手工关联后的订单在同一行有订单号和View两个链接，测试由“恰好1个链接”修正为“至少1个可见入口”；订单行数仍要求精确4行，避免假阳性。

## 安全与残余风险

| 风险 | 当前处理 | 最晚节点 |
|---|---|---|
| 登录账号枚举 | My Account有效登录请求统一未知账号/错误密码；其他安全错误不覆盖 | 当前Local已通过，非Local复测 |
| 注册重复邮箱枚举 | 保留Woo原生行为并诚实登记 | 若业务不接受，另开注册流程变更 |
| 找回密码枚举/过期链接 | D79不改 | D80＋D77 |
| 登录暴力尝试 | 不在Core自造IP锁 | 公开Staging前验证主机/WAF或成熟方案 |
| 共享邮箱归错订单 | 业务已确认第一版不存在共享邮箱、代理下单或代采购；Staging仍用代表样本复核归属，未来模式变化时重新开启风险评估 | 2026-09-22发布NO-GO解除，转持续监控 |
| Guest付款链接泄漏 | 订单Key按密码管理，不进截图、工单、分析或公开日志 | D76～D78支付与D100缓存 |
| 先确认邮箱再付款 | 归户后匿名付款链接要求登录该账户 | 客户邮件/WM SOP在D77复核 |
| 大量历史订单查询 | Woo原生按邮箱无上限读取；当前小规模可接受 | 真实订单量上升时测量 |

## WordPress/WooCommerce输出与前端微调

WooCommerce的`form-login.php`输出同一份语义DOM、Label、Nonce、登录与注册按钮。DentAll不复制模板：`dentall_enqueue_account_auth_assets()`只在`is_account_page()`且退出登录时加载CSS。Mobile First默认一列，`48rem`起用CSS Grid变为两列；字段、按钮和Focus继续复用D28全局控件。

安全微调路径：

1. Chrome DevTools选中`#customer_login`，在Computed查看`grid-template-columns`、`gap`和卡片宽度。
2. 临时修改`--dentall-space-*`、卡片padding或`48rem`媒体规则，观察390/768/1024/1440及长隐私文案。
3. 判断是公共Token还是账户局部规则；本页结构只改`account-auth.css`，全站控件才回到`style.css`。
4. 回到子主题源码保存，再回归登录错误、注册、密码显示按钮、键盘Focus和已登录账户页资源隔离。DevTools临时值不能替代源码。

## 数据、URL、SEO、缓存、支付、物流与部署影响

| 检查面 | 结论 |
|---|---|
| 数据 | 无Schema或迁移；未来目标环境保存账户设置会写`wp_options`，邮箱确认会按Woo CRUD更新符合条件订单的Customer ID |
| URL/SEO | 不新增URL、Canonical、Schema或Sitemap规则；继续使用原生`/my-account/`与端点，账户页保持不索引策略 |
| 缓存 | 主题版本键会刷新资源；新增约一份账户页条件CSS请求。账户、Checkout和订单页必须绕过共享整页缓存，非Local尚未验证 |
| 支付 | 未提交真实付款；只验证`order-pay`身份入口。重复付款、网关回调和双扣仍属D76～D78 |
| 物流 | 不改变CR-012；实体Cart仍走人工报价，全虚拟Cart仍可原生Guest Checkout |
| 邮件 | 仅私有捕获新账户与确认邮件；未证明SMTP、垃圾箱、延迟、跨设备或公司发件身份 |
| 部署 | D79源候选已提交并纳入批次①集成分支；尚未合并main、推送或部署，未修改共享Local、Staging、Production、DNS或缓存配置 |

## 回滚

1. 移除Core入口对`customer-account.php`的加载并删除该模块，Core版本回退0.3.0。
2. 移除账户样式enqueue和`account-auth.css`，主题版本回退0.42.0。
3. 各环境账户设置属于数据库配置，若已经保存需按变更前快照单独恢复；代码回滚不会自动修改设置。
4. 历史订单一旦已归属Customer，代码回滚不会自动把订单改回Guest；必须基于订单审计和授权逐单处理，不能批量猜测。

## 未验证项与后续衔接

- D77：真实SMTP投递、公司发件身份、退信/垃圾箱、延迟、跨设备和客户邮件文案。
- D80：完整找回密码、统一公开响应、过期/已用/改邮箱链接和错误状态。
- D76～D78：沙盒付款成功、失败、取消、超时、重复提交与回调幂等。
- D81～D84：已登录Dashboard、地址表单、订单列表/详情/再次购买和账户全链路视觉。
- D100及非Local：Cloudways/Varnish/CDN缓存隔离、HTTPS Cookie、WAF限频和真实多会话。
- 真实屏幕阅读器、实体设备、正式英语页面内容和正式隐私文案仍需对应验收；当前隔离数据继承了共享Local的中英混合内容，本轮未改业务文案。

## 可复用核心思想

- 跨平台不变量：账户归属必须由可证明的身份信号触发；邮箱相同只是候选，已明确的所有者不能被模糊匹配覆盖。
- WordPress/WooCommerce当前实现：Woo Customer、订单`customer_id`、Billing email、邮箱验证事件和CRUD形成单一归属链；主题只负责表单展示，Core只补登录公开错误边界。
- Shopify或其他平台：仍需区分Guest订单、客户身份验证、历史订单认领和付款链接授权，但具体Customer/Order API与验证事件须查平台官方机制，当前未验证且不属于DentAll实施范围。
