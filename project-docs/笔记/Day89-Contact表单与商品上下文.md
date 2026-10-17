---
项目: DentAll WooCommerce
日期: 2026-10-08
补充日期: 2026-10-17
工作日: D89
计划检查点: D89（不自动等于一个完整实际工作日）
周次: W15
计划工时: 6小时50分钟有效工作
实际有效工时: 未记录；不使用计划工时代填
验收层级: 隔离Local技术验证；正式投递与目标环境未验
状态: 已完成授权的隔离Local候选；未发布
---

# DentAll 每日复盘 D89：Contact表单与商品上下文

## 相关笔记

- 每日索引：[[README|DentAll每日笔记索引]]
- 固定页面候选：[[Day21-固定页面清单与URL责任边界]]
- 商品类型边界：[[Day10-商品类型与价格库存规则]]
- 当日学习：[[WordPress实战笔记/Day89-表单服务端上下文与通知边界]]
- 后续集成抽样：[[Day90-内容样本与集成抽样]]
- 目标窗口执行卡：[[Day89-D工作包目标发布与验收卡]]

## 结论与三个验收结果

- [x] 在独立Local副本安装并评估Fluent Forms Free 6.2.15，保留WordPress原生Contact Page；首次交付导入包为`draft`、通知关闭、收件人留空。2026-10-13按用户确认补入公司收件地址，仍不含TEST地址或环境ID。
- [x] 展示型商品链接只传ID，Core在预填、入库与邮件前重读WooCommerce商品；有效TEST商品保留规范名称/URL，标准商品或伪造字段被拒绝。四宽Contact无横向溢出，前台正常/无效参数/必填错误/成功、Honeypot、Token及邮件失败均在隔离环境验证。
- [x] 收件人、条目访问角色、留存期、隐私文案、目标缓存与公开Canonical列为发布门槛；未把隔离验证当成正式表单、真实邮件或M7内容验收。

## 授权、范围与职责

用户先要求提交既有待交内容，再明确“按照你的建议开始”。本轮先提交D87源分支待交工作，随后在独立Local副本实施先前建议的D89最小候选。使用者为匿名访客；业务接收与处理角色尚未确认。预期联系量、正式收件邮箱、保存期限与隐私告知仍无真实业务输入，因此只使用TEST身份、`invalid.test`邮箱和Mailpit。内容数量不决定表单字段结构；未知业务事实不阻塞隔离骨架验证，却阻止正式发布。

第一版仅收姓名、邮箱、留言和可选的展示型商品ID；不做附件、客户登录、CRM、工单、自动回复、报价/订单创建、文件上传或会员权限扩展。FAQ继续使用原生Page；`/contact-us/`为既有候选路径，在隔离副本建立TEST Page，不把该Page数据库对象作为源码交付。`/contact-us/?product_id=...`只预填来源，不建立新商品URL。

D89原总计划已有Contact、FAQ、404、反垃圾、邮件与定制商品上下文，本轮在该检查点内收敛为候选实现，未改变D120里程碑或另加已确认工时；实际用时未记录。若后续增加附件、自动回复、工单、角色范围或正式数据迁移，须新记范围、工时和排期，不能默认为D89吸收。

技术选型先核原生Page和现有`dentall-core`：Page能承载内容，却不提供条目、通知、字段校验及垃圾拦截；现有能力没有通用联系表单。采用单个成熟表单插件负责表单存储与通知，主题仅负责Page展示，Core只负责DentAll展示型商品的可信上下文。插件只在隔离副本安装，版本包来自WordPress.org；Git不包含第三方源码。相较于自研提交、数据库、后台列表、邮件和防垃圾机制，本候选的长期维护成本更低，但增加插件升级、权限与数据留存责任，须在目标环境单独验收。

## 七个专注周期与收尾

| 周期 | 计划目标 | 本轮实际结果 | 用时 |
|---|---|---|---:|
| C1 | 核实授权、源码与隔离边界 | D87待交内容先提交；D81/D82＋D87/D88以Git整合，独立Local运行 | 未记录 |
| C2 | 评估原生/插件候选 | 检查Fluent Forms 6.2.15 Hook、导入结构、权限和持久数据 | 未记录 |
| C3 | 建立TEST表单/Page/商品 | 表单ID3、Contact/FAQ及展示型商品209只存在隔离库 | 未记录 |
| C4 | 最小实现 | Core商品验证与通知Hook、主题Page上下文和单页CSS；导入JSON默认草稿 | 未记录 |
| C5 | 四端与交互 | 390/768/1024/1440px浏览器检查、有效/无效/伪造输入与垃圾拦截 | 未记录 |
| C6 | 邮件与失败 | Mailpit仅收`invalid.test` TEST邮件；失败日志与访客中性保存提示复核 | 未记录 |
| C7 | 独立审查 | Code Review与安全审查发现的Token配置、通知Hook和伪造字段风险均修复；代码P0/P1/P2=0 | 未记录 |

收尾按真实结果更新项目文档；计划周期不是实际工时记录。

## 实现与减法审查

| 边界 | 实际实现与保留理由 |
|---|---|
| 原生数据 | Contact与FAQ为WordPress Page；商品仍为WooCommerce Product。未增CPT、ACF字段或数据库表定义 |
| Core | `includes/contact.php`集中商品ID/销售模式校验、表单入库清洗、邮件内容和Contact Canonical，随站点长期启用且跨主题 |
| 子主题 | `inc/contact.php`只在Contact Page添加经校验的提示和条件CSS；`assets/css/contact.css`只含页面局部布局与焦点规则 |
| 表单导入 | `project-docs/forms/dentall-contact-fluentform.json`是插件原生导出格式；2026-10-17修正为插件原生未发布`unpublished`、通知关闭，收件沿用10月13日确认的公司地址。导入后的环境ID由`dentall_contact_form_id`配置，不提交TEST地址、账号凭据或环境ID |
| 删除的预实现 | 去掉客户端隐藏名称/URL/来源字段；去掉按表单强开Token的错误Hook；改用邮件专属`fluentform/email_body`，不污染访客确认提示 |

商品ID只接受正整数。`wc_get_product()`得到的对象必须为已发布、可见且`dentall_sales_mode=display_only`，名称和URL由服务端重新获得；入库前丢弃客户端同名字段并写入可信值。访客可在留言中自行描述商品，但不能让浏览器填入的名称/URL成为系统标记。通知邮件再次验证商品；普通联系邮件不附商品。公开输出按上下文转义，表单插件负责其必填与邮箱校验。匿名公开表单不检查登录Capability，垃圾拦截用Fluent Forms全局Token和Contact专用Honeypot，不能把Nonce等同身份授权。

## 隔离Local实际证据

| 场景 | 结果 | 证据限制 |
|---|---|---|
| 环境 | WordPress 7.1、WooCommerce 11.0.0、Storefront 4.6.2、Yoast 28.2、PHP 8.2.29；独立`18989/18990`和Mailpit`18992`，noindex/外呼限制/支付关闭 | 不是共享Local或目标环境 |
| 四视口 | Contact在390/768/1024/1440px均200、唯一`main`/H1、输入可见标签、无横向溢出；390/1440截图已人工查看 | 无实体设备、读屏器或真实网络 |
| 来源上下文 | ID209的展示型TEST商品显示并预填；标准商品44显示无效提示且ID为空；普通Contact无商品提示。FAQ为200，未知路径是真实404 | TEST商品不是正式内容 |
| 成功提交 | Token生成AJAX 200、提交200；条目10与11仅保留规范209/name/url/source，伪造名称/URL/来源未入库；条目12的标准ID不保留来源 | TEST收件人，真实投递未验 |
| 拦截与错误 | Honeypot和无效Token分别422，不产生新条目；必填/邮箱错误有前台提示；模拟邮件失败时条目仍保存且插件记录失败，前台只称“已保存、待审核” | 未核对长期日志告警与人员处理SOP |
| 邮件 | Mailpit仅捕获`d89@invalid.test`，有效商品邮件附规范名称与URL，访客确认不含商品HTML；伪造文本未入邮件 | 未向外部发信；真实SMTP、From/Reply-To与收件责任未验 |
| 导入回放 | 最终JSON经插件原生`TransferService::importForms()`另导入为Form 5，仍为`draft`、四字段、通知关闭且收件人为空 | 导入副本只在隔离库，不证明目标环境权限或邮件配置 |
| 条目隐私 | Contact专用Hook使IP、国家为`null`，但Fluent Forms仍保存浏览器、设备和包含查询参数的`source_url` | 不可宣称只保存姓名、邮箱、留言 |

忽略目录`.codex-tmp/d89-local/evidence/`保存截图，隔离库保存TEST条目；它们不是版本化交付。D90版本烟测后通过经路径/PID校验的脚本停止隔离PHP/MySQL/Mailpit，18989～18992四个端口监听均为0；TEST副本、表单、邮件与证据文件留在本工作树忽略目录供复核，不迁往公开环境。

D90抽样期间发现插件对导入表单的TextArea `class`与SubmitButton `container_class`缺失发出Warning。已在JSON补两个空默认键，同步隔离Form 3后Contact GET为200，新增日志中Warning/Fatal/Parse计数0；没有修改Fluent Forms源码或新增CSS行为。

## 2026-10-13业务确认与原生维护入口

用户明确Contact由`materials@chinaadsdentallab.com`收件，网站管理人员主办、每天查收一次并全权处理；批准的英文隐私提示和政策链接由他们后续自行补充，开发先留入口。用户进一步要求这些运营协作不阻塞开发，优先完成已授权功能闭环。

- 隐私入口复用`页面 → Contact Us → 编辑`的原生正文：在表单短代码旁增加普通段落，填获批准的英文提示并通过编辑器链接工具选择政策Page。现有Website Manager具备Page编辑能力；正文是可直接维护的入口，无需新增数据字段、固定文案、特殊占位组件或PHP。政策正文也由Website Manager维护；如需绑定WordPress系统隐私政策页，在部署配置时由Administrator完成。
- 条目入口复用`Fluent Forms → Settings → Managers`：Administrator为实际Website Manager独立账号仅勾选View Forms、View Entries、Manage Entries，并启用指定表单、明确选中目标Contact Form。导出由View Entries覆盖；处理、标记和删除由Manage Entries覆盖。指定列表不可为空，不在Roles页授权整个角色，不授予表单编辑、全局设置、支付或Full Access。初配属于开发/部署交接，日常处理归网站管理人员。
- 具体留存天数、删除/导出安排、正式隐私文案和政策链接属于网站管理人员的后续运营任务；开发不代定期限，不做自动清理、工单、通知重试或新审计模块。未知留言量不改变当前四字段骨架，不重复询问。
- 本次延续D89范围，原生入口与配置优先，没有新增运行文件、函数、规则块或依赖；公司地址只写入禁用通知配置，不触发发送。开发交付与运营内容、正式环境验收分别按证据判断，不将后续协作列为隔离开发阻塞。

本次在既有隔离Local中调用插件原生`ManagerService::addManager()`创建临时Website Manager授权，真实WordPress＋插件ACL定向检查20/20通过：Contact三项能力允许，Form 5与表单/设置/支付/Full Access拒绝，Contact Page #207编辑能力允许，Content Editor条目访问拒绝，WordPress系统权限拒绝。测试用户均删除，角色能力表与测试前一致；隔离服务四端口均无监听。该证据验证原生配置和权限判断，未冒充目标环境实际登录、导出/删除动作或SMTP投递验收；脚本和结果只保留在本分支忽略目录`.codex-tmp/contact-owner-check.php`、`contact-owner-results.json`。ACL、ManagerService、FormManagerService三个插件源文件与原6.2.15版本包逐文件SHA-256一致，未修改第三方源码。

## 2026-10-17D工作包补测与最小修复

沿用本对话已有实施授权及总协调A/B/D工作包下发，在本工作树另建独立`.codex-tmp/d-workpack-20261017`（HTTP18887/MySQL18888/Mailpit18889～18890），从已停止的旧隔离副本只读克隆并同步当前候选文件。未写旧b8bc、主目录、A/B工作树或共享站点。新副本的文章固定链接按D19使用`/blog/%postname%/`；初始化时新option与旧rewrite_rules不同步，后以新请求重新初始化并刷新规则，文章HTTP200/预览恢复。这是夹具配置修正，没有修改正式URL路线。

1. **受保护商品边界P2。** 新TEST商品设置密码后，旧helper仍把其名称写入页面提示、隐藏ID、normalize及邮件。修复前4项拒绝断言均失败，匿名HTTP可见名称；在同一资格判断新增`get_post_password('edit')`非空拒绝后4/4通过，HTTP只显示无效商品提示。拒绝不随密码Cookie变化，未增函数、字段或查询。
2. **默认关闭P2。** 原生导入`draft`后，普通短代码虽不展示，匿名preview入口仍能渲染，合法Token的直接POST实际200并新增1条TEST记录。改为原生`unpublished`后，同类POST为422/Invalid Form、条目0→0，匿名preview也被阻断。原10月13日7/7只是值检查，不能作为提交关闭证据；今后以真实拒绝为准。通知仍关闭，不增加自定义停用Hook。
3. **发布准备。** 已形成[[Day89-D工作包目标发布与验收卡]]，区分统一集成、原生配置、临时受控TEST提交、目标冷暖缓存/Canonical、实际WM与SMTP及回滚；正式隐私/留存仍由WM公开前补齐，不阻塞开发。原IP不入库策略下未证明插件IP计数限流有效，Token/Honeypot只称反垃圾，不擅自增加限流模块。

独立代码/安全审查均确认上述最小修复属于已授权边界。Fluent Forms 6.2.15证据与后续补丁版验证分别保留；[官方6.2.16记录](https://wordpress.org/plugins/fluentform/#developers)于10月16日发布，本轮在隔离站核同插件补丁兼容，不在共享Staging安装或升级。原生Managers的三项ACL实现未变，20/20证据按文件哈希复用；正式账号实际登录、真实SMTP及Breeze/Varnish仍归已列明的目标窗口。

最终Fluent Forms 6.2.16独立回归97/97：两匿名会话交叉A/B/无效/数组/普通/受密码保护商品ID、四宽、服务端派生字段、篡改拒绝、Token/Honeypot；坏Token和Honeypot均422且不新增条目。3封Mailpit成功；一条唯一TEST消息的收件地址在最后`wp_mail`过滤改为语法无效值，真实WordPress异常触发插件`Email sending failed`，条目仍保存。未手动模拟失败事件，未向公司邮箱发信；真实SMTP故障留目标窗口。新JSON原生导入后普通/preview均不渲染，合法Token POST422且条目0→0；6.2.15也保留对应前后证据。

隔离Canonical6/6均指基础Contact URL，临时`blog_public`精确恢复且mu无索引头始终保留；本地无Breeze/Varnish，缓存验收不计通过。补丁安全13/13确认MCP/Abilities及插件支付/OrderClaim均关闭，`ff_entry`匿名/未授权WM拒绝、指定Contact WM允许；临时两账号删除、角色表未改。测试Form、Page、产品、条目、邮件和失败mu均清理，Form3/meta/Token/Form ID/索引原值恢复7/7；受保护TEST商品217已删。最终JSON值7/7，第一方43文件哈希一致，18887～18890监听与专用进程均为0。

本轮证据在忽略目录`evidence/contact/regression/`的`results-6.2.16.json`、`failure-log-6.2.16.json`、`import-after-6.2.16.json`、`cleanup-6.2.16.json`，以及`evidence/security/patch-results.json`和`evidence/runtime-stopped.json`。早期夹具的Token等待、模拟SMTP被禁函数阻断500等失败保留，不将其抹成通过或归因当前源码。三个新增P2（另一个是D90 Page表格）均在Local关闭；开放事项仅为已登记的正式内容和目标环境验收。

减法审查：相对`97a9065`，第一方执行文件只改Core入口版本、既有Contact helper和既有主题style.css，合计5行新增/2行删除、净增3行（含中文边界说明及版本替换）；实际行为只增加一个资格条件和一个CSS选择器，新增运行文件/函数/规则块均0。JSON仅替换状态值，readme补版本/结果；唯一新增正式文件是独立目标验收卡，用于跨包合成和串行窗口交接，不创建新后台或发布自动化。其余是4个Day及既有学习笔记、索引和状态的必要证据归档。没有新增查询、远程调用或自定义Cron实现；第三方补丁未做性能前后测量，不宣称零影响。

## 发布门槛与风险

1. 收件地址、Website Manager处理责任和每日一次查收已确认。部署时按上方原生Managers路径给实际账号配置Contact条目最小权限，并验证允许与拒绝路径；当前导入包为原生`unpublished`、通知关闭。
2. 正式开放前由Website Manager补齐具体留存安排、删除/导出操作和访客隐私告知。插件会保存姓名、邮箱、留言、browser、device、source_url；停用插件不会自动删除条目或日志。政策页与表单文案须与实际保存数据一致；这一运营待办不阻塞当前开发。
3. 部署前备份目标数据库；安装经版本/兼容复核的插件，导入原生未发布配置、核字段和禁用通知、设置目标Form ID；配置确认的收件人与From/Reply-To，检查权限、全局Token生成端点、Honeypot、缓存查询参数隔离及公开Canonical后才发布。先在明确授权窗口用TEST提交检查收件、失败日志和人员处理，再验四宽。回滚先关闭表单/Page，再停用新装插件并按留存决定处理条目；回退主题/Core不会删除插件数据。

本次没有改生产或Staging数据、支付、物流、订单、缓存设置、DNS或索引状态。新增功能影响Contact页面的表单CSS/JS与AJAX、插件表单/条目/日志表及邮件处理；隔离Cron列出`fluentform_do_scheduled_tasks`每5分钟与`fluentform_do_email_report_scheduled_tasks`每天。实测没有性能前后基线，不能声称零影响。公开环境的Canonical、查询参数缓存和邮件服务仍未验；正式Contact文案、隐私政策及M7内容验收不在本轮结果内。

## 可复用核心思想

- 跨平台不变量：访客来源参数只是线索，任何影响业务处理的名称、链接与商品资格都应在服务端从权威数据重建；“提交成功”和“通知送达”是两件事，数据留存与处理责任必须明确。
- WordPress/WooCommerce当前实现：原生Page承载内容、WooCommerce CRUD读取商品、`dentall-core`维持跨主题规则，Fluent Forms保存条目和发通知；Form ID与Token全局设置是部署契约，不能从Local复制固定ID或靠单一Hook推断生效。
- Shopify或其他平台：对应表单、商品读取、邮件和权限机制须按目标平台实测；上述信任边界可迁移，插件Hook与WordPress数据表不能照搬。
