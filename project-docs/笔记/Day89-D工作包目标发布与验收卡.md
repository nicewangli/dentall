# D87～D90内容与Contact目标发布验收卡

日期：2026-10-17。范围：D工作包的既有文章、原生Page、Contact及内容联调；本卡是待分配Staging窗口的执行材料，不表示已经部署。

## 相关笔记

- [[README|每日笔记索引]]
- [[Day87-文章详情与公开署名]]
- [[Day88-Solutions原生Page前台验证]]
- [[Day89-Contact表单与商品上下文]]
- [[Day90-内容样本与集成抽样]]

## 版本与职责边界

- 本包沿用`e078fc5 → 97a9065`，D87/D88等效增量与D89/D90已在前者，不再次摘取旧分支。D候选为Theme 0.49.1、Core 0.9.2；A/B并行代码及最终版本由统一集成方合成，不以本包整目录覆盖。
- 已部署的D79视觉`1b02567`及D84补丁`394634c`必须由统一集成保留；作者Feed/oEmbed由A唯一负责，D只验可见HTML/Yoast团队署名及后台真实作者。D不操作报价、支付、订单、SMTP连接或全局缓存策略。
- 原生Page/Full width继续承载Solutions，正式文案、父子URL和导航由Website Manager发布时决定。正式3篇Post＋1个Page、授权素材和隐私内容均为运营验收项，不阻塞当前技术骨架。

## 文件白名单

### 2026-10-17离线发布预检

- 源码核对点：统一候选`e078fc5`、D增量`97a9065 → d3b00ff`、A增量`18e0096`、B增量`a64491b`；远端`main=e7caa3a`、`deploy/staging=c1de7f1`。本次仅核对Git对象，未核现场文件，也未实际合成或部署。
- 按下表路径映射核对9项：部署树缺少`inc/article.php`、`inc/contact.php`、`assets/css/contact.css`及Core `includes/contact.php`，其他5项与D候选不同。因此离线包中相对`e078fc5`的补丁不能直接套到部署树，须先接纳D87/D89前置模块和加载行。
- A/B/D的Core入口均标为`0.9.2`，版本相同不表示内容相同。统一集成须保留B新增`shipping-quote-paypal.php`加载、A的SEO Filter及D的Contact加载；合并A/D同名版本的readme条目后确定最终版本。D本轮不改A的SEO实现或B的支付代码。
- 主题`functions.php`须同时保留已部署D79的`inc/account-auth.php`与D的article/contact加载。D84的`inc/setup.php`和`assets/css/customer-account.css`补丁不在D增量内，必须由统一集成保留，不能用D目录覆盖。
- 核对时，用户已在「W13 Day76｜PayPal沙盒支付流程拆解」分配B独占Staging窗口，B正在现场预检；D不进入共享浏览器或目标站操作。下一窗口由总协调确认B结束、A的SSH操作安排和最终发布提交后分配，不因本任务“开始下一步”推定窗口移交。
- 原离线包`D工作包-d3b00ff.zip`的SHA-256仍为`608d871daa21e61d489cab3f18109e4638edbf38a7e125c1b60ca74db7f2e3da`，其中验收卡是`d3b00ff`快照；本节是后续预检记录，以当前Git文档为准。正式隐私文案及内容继续由Website Manager后补，不作为技术集成前置。

源码`app/public/wp-content/`映射部署分支根级`wp-content/`。以下是D涉及的全部运行文件，不是可绕过统一集成的整文件覆盖清单。

| 源码中`app/public/wp-content/`之后的路径 | 本包职责 | 合成方式 |
|---|---|---|
| `themes/dentall/inc/article.php` | 原生单篇日期、公开团队署名、评论入口 | D87既有独立模块 |
| `themes/dentall/inc/contact.php` | Contact原生Page商品提示及单页资源 | D89既有独立模块 |
| `themes/dentall/assets/css/contact.css` | 联系表单局部布局、输入与焦点 | D89既有单页CSS |
| `themes/dentall/functions.php` | 加载article/contact模块 | 只合并两条加载语句，保留其他包 |
| `themes/dentall/style.css` | D87阅读宽度、D90长文和表格规则 | 按既有选择器合并，保留D79视觉与其他样式 |
| `plugins/dentall-core/includes/contact.php` | 可信商品上下文、反垃圾/隐私Hook、Canonical | 本轮只补带密码商品拒绝 |
| `plugins/dentall-core/includes/seo-compatibility.php` | 可见文章相关SEO统一团队署名 | 保留D87既有增量，与A合成复验 |
| `plugins/dentall-core/dentall-core.php` | 模块加载与插件版本 | 合并Contact加载行，保留A/B入口与最终版本 |
| `plugins/dentall-core/readme.txt` | 版本及变更说明 | 合并各包条目，不覆盖其他变更 |

`project-docs/forms/dentall-contact-fluentform.json`只供插件后台原生导入，不放公开Web目录。原隔离证据使用Fluent Forms Free 6.2.15；本轮已按[官方发布记录](https://wordpress.org/plugins/fluentform/#developers)对6.2.16（10月16日发布，含权限/导入加固）完成97/97及13/13安全兼容，原生未发布导入后合法POST422且不入库。目标候选包SHA-256为`9ec4fce3857a0c069dcb392f6e0e9bd1a5a1231d94a49a76d9768a40e973a7fd`。窗口前再次复核选定包、版本与兼容性，不能把历史包当作最新安全版本。

## 已有隔离证据与目标缺口

本轮新测内容2对象编辑器全链路、四页四宽16/16及双作者HTML/Yoast2/2；Contact6.2.16回归97/97、原生关闭GET/preview/POST、Canonical6/6、安全兼容13/13；配置/清理原值7/7及第一方43文件同步通过。原ACL20/20按相同源码哈希复用，不伪称本轮重跑。临时对象与秘密清理，四端口/专用进程均0。工作树忽略证据在`.codex-tmp/d-workpack-20261017/evidence/`，分版本保留旧夹具失败与最终结果。

这些结果不包含真实Breeze/Varnish缓存、目标WM操作、BossMail投递/SMTP故障、公开索引或正式内容。后续只在本卡明确窗口补对应缺口，不重复D85/D86已经通过的全部验收。

## 窗口前须具备的材料

1. 统一集成方给出最终源码提交、部署分支提交、逐文件哈希及差异。现场重读应用身份、home/siteurl、活动主题/Core/插件版本、既有Contact Page/Form和目标WM账号；历史应用ID/本地ID不能代替现场核对。
2. 按实际变化明确暂停范围。本包不要求全站停录；需局部暂停Contact配置、表单条目操作和受影响文章/Page编辑，避免覆盖并发变更。窗口开始前保存文件＋数据库同点备份及本卡涉及的原值。
3. 用户明确分配独立Staging窗口。窗口内的插件安装、草稿配置、临时TEST可提交状态、真实受控邮件及定向缓存验证必须逐项列入授权；没有窗口就只做本地验证和材料准备。

## 原生配置与关闭默认

| 配置 | 目标值/核对方式 |
|---|---|
| Form状态 | 插件原生“未发布”`unpublished`；通知`enabled=false`。以匿名不渲染、有效POST拒绝且条目不增加证明关闭，不能只看状态字符串 |
| 收件 | `materials@chinaadsdentallab.com`；Reply-To=`{inputs.email}`，From复用既有FluentSMTP＋BossMail。密码/连接不入源码或文档 |
| 字段 | 姓名、邮箱、留言、一个未信任的商品ID；不增加附件、自动回复、CRM、报价或订单创建 |
| 环境ID | 导入后记录真实Form ID，`dentall_contact_form_id`与Contact Page短代码保持同一ID，禁止照搬Local的3/5 |
| Token | `_fluentform_global_form_settings.misc.tokenBasedProtectionStatus=yes`；核匿名`fluentform_generate_protection_token`路由。不能只用按表单Filter启用 |
| WM | 原生Managers给实际独立账号View Forms、View Entries、Manage Entries；启用指定表单并明确勾选Contact，列表不得为空；不授Forms/Settings/Payments/Full Access，不用Roles整角色授权 |
| 隐私入口 | `页面 → Contact Us → 编辑`，表单短代码旁的原生段落与政策Page链接；WM可直接补正文。若需WordPress系统隐私页绑定，由Administrator配置 |

保留IP/国家不入Contact条目的现有策略；browser、device、source_url及可选商品上下文仍实际保存。具体留存、导出/删除与正式英文文案由WM决定并在公开前补齐。Token/Honeypot是反垃圾措施，现有证据不证明基于IP的限流有效；有滥用时先核主机/WAF能力，不在本包新增限流模块。

## 三项目标验收

1. **文件及最小权限。** 核最终哈希/版本，按原生流程安装、导入未发布配置并绑定ID。用实际WM登录核Contact条目列表/详情、一次TEST导出/删除，其他表单、全局设置与插件管理拒绝；核Page隐私正文可编辑且回滚原文。错误Token/Honeypot拒绝且无新条目，关闭状态的直接POST也必须拒绝。
2. **受控邮件闭环。** 仅在窗口明确授权的临时可提交状态下，用标记TEST身份提交一次有效展示型商品联系，核服务端重建的商品名称/URL、条目与实际邮箱收件一致，To/From/Reply-To及FluentSMTP日志正确。按获准方法验证失败时条目保留、日志可查；“开始发送”不作为已送达证据。故障注入不得更改或中断共享SMTP连接，不擅自启用fallback或自动重试。窗口后恢复未发布/通知关闭；若正式运营内容尚缺，不留下可公开提交的TEST表单。
3. **匿名参数与内容。** 两个独立匿名会话交叉访问普通Contact、有效A、有效B、无效ID、数组参数、标准商品及带密码商品；每类先冷后暖并交换预热顺序。逐响应保存商品提示、隐藏ID、Canonical和`X-Cache/Age/Cache-Control/Vary`，不得出现A商品提示/B隐藏ID或其他访客上下文。带参数Canonical应为基础`/contact-us/`。同时抽长/空Post、Full width Page、FAQ、真实404与Contact四宽，核内外溢出、表格、缺图、键盘和页面错误。

本地PHP直出不等于Breeze/Varnish缓存通过；没有`X-Cache/Age`也不能单独证明所有缓存绕过。Staging保持`noindex`，Yoast可能因此不输出Canonical；不为验Canonical临时开放共享站索引，先用本地受控可索引分支证明代码，目标公开索引前再核输出。若目标缓存矩阵失败，保留失败证据，由统一集成方提出精确排除或键策略并确认，不擅自清全站或改策略。

## 回滚与结果登记

- 先撤下本轮Contact短代码/关闭表单及通知，确认匿名GET/POST已拒绝；再按备份恢复目标Form配置、Page原文、`dentall_contact_form_id`及本轮Token原值、具名Managers授权。存在其他表单时，不回退它们仍需的全局设置。
- 仅当本轮新装且无其他使用方时停用Fluent Forms；条目/日志/自有表不会随停用或PHP回退消失，由WM按留存安排处理。共享FluentSMTP不随Contact回滚停用。
- 代码回滚交统一发布方，保留A/B和D79/D84已上线能力；不恢复整份Local数据库，不推旧主题目录。按原受影响URL精确检查旧缓存，不执行未经授权的全站清理。
- 结果分别记录：隔离Local证据、Git提交/推送、main合并、Staging配置/验收、正式内容验收。正式内容未到位时写“技术可继续，内容验收待输入”，M7不自动完成。

## 可复用核心思想

- 跨平台：默认关闭需要实际拒绝渲染和提交的证据；状态字段、通知关闭和访问控制是三件不同的事。来源上下文必须由服务端重建，并与整页缓存的查询参数维度一致。
- WordPress/WooCommerce：Post/Page与修订负责编辑；表单插件的原生未发布状态、Token和按人/按Form的ACL负责联系入口；WooCommerce CRUD提供商品资格。插件配置、条目与邮件不会自动随Git提交迁移或回滚。
- 其他平台：编辑器预览、表单停用、权限与缓存隔离也应按真实目标机制验收；本次未验证其他平台对应API，不作一一映射。
