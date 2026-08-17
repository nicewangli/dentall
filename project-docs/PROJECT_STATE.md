# 项目当前状态

> 本文件是新对话和每日工作的当前事实入口。每天收工更新；历史细节进入每日Obsidian笔记和版本记录。

## 状态快照

- 更新日期：2026-08-17。
- 当前阶段：W3 / D15已完成Local库存与物流字段验收；产品规格、单个销售包装和订单外箱职责、Simple/Variable物流、数量/状态库存、临时缺货及停售候选均通过。主审计45/45与独立Simple 23/23、Variable 17/17通过，无开放P0/P1。下一步进入D16商品SEO流程。
- 当前计划：单休20周编辑先行版，120个工作日，自然周期约4.6个月，对外按4.5～5个月管理。
- 当前里程碑：M1技术预验收已在D6通过，Website Manager培训者预演已在D13通过；两名实际网站工作人员仍需在到岗后分别补30分钟无指导试录，完成M1人员验收。该人员项不阻塞当前D16商品SEO骨架推进；D18商品模型候选冻结，D24内容样本完成，D25开放批量录入。
- 当前状态：Cloudways Flexible已从试用升级为Full Access；受保护Staging、HTTPS、禁止索引、支付关闭边界、恢复入口及凭据轮换均已验证。
- 当前版本：DentAll Core 0.2.1已通过Via Git部署到Staging；0.2.0落实角色版本5与统一Website Manager能力，0.2.1隐藏并拦截业务角色的空Tools入口。Local后台当前显示WordPress 7.0.4，项目旧记录7.0.3已产生版本事实偏差；WooCommerce仍按11.0.0基线管理。

## 已完成

- 首页PC、平板横屏、平板竖屏和手机效果图。
- 响应式素材包、设计Token、断点、组件和素材清单。
- 项目专属Codex Skill和根目录`AGENTS.md`。
- 当前单休编辑先行计划、历史单休/双休计划和Obsidian每日复盘模板。
- 项目背景、需求、架构、数据字典和URL/SEO初稿。
- 变更、决策、风险、测试、发布和运行文档模板。
- 已使用LocalWP创建WordPress站点，根目录为`D:\LocalWP\dentall`。
- 全部项目管理文档已迁移到LocalWP项目根目录。
- 已建立Codex多Agent动态调度规则，后续按每日进度和风险选择项目/需求、设计还原、Code Review、安全与测试Agent。
- 已建立D1第一版范围与访问依赖基线，并完成现有资料的已知/未知盘点。
- 已确认Git仓库已初始化，`origin`为开发者个人私有GitHub仓库；不作为D1业务依赖。
- 已确认服务器使用Cloudways Flexible / DigitalOcean Premium 4GB并已升级为Full Access；PayPal付款方式由老板配置。
- 已确认第一版为英语、美元；保留后续扩展边界，但第一版不实现多语言、多币种。
- 已确认项目从零开发，不存在旧站，不纳入旧站URL、内容或数据迁移。
- 已确认D6可安排30分钟实录；代表商品可由编辑人员在提出需求后约1小时提供。
- 已记录WordPress 7.0.3、WooCommerce 11.0.0、PHP 8.2.29、MySQL 8.4.0、Nginx 1.26.1和LocalWP 10.1.1+6939。
- WooCommerce已安装并激活；核心数据表及商店、购物车、结账和账户页面已生成。
- 已设置第一版前台英语、管理员中文、USD、暂不计算税费、`Asia/Shanghai`和`/%postname%/`。
- 已启用仅限Local的调试日志且不向页面显示错误；Mailpit本地邮件冒烟测试通过。
- 已明确老板不参与日常执行，只提供资金、域名资料、必要访问资料和实体产品内容支持。
- 已复核Git跟踪范围，未跟踪WordPress核心、第三方插件、uploads、数据库、日志、备份实体或`wp-config.php`。
- 已修复自定义代码放行规则可能重新纳入密钥的P1问题，并将mu-plugin范围收紧到DentAll明确命名文件。
- 已生成数据库、uploads、Git历史、工作区差异和未跟踪文件的本地D3备份；数据库已在隔离临时库成功恢复51张表，校验后临时库已删除。
- 已建立Cloudways临时域名Staging，启用Password Protection并验证未授权访问被拦截。
- 已确认Staging运行PHP 8.2.33（64位），安装并启用WooCommerce 11.0.0。
- 已验证WordPress与站点地址使用HTTPS，浏览器无证书警告；商店、购物车、结账和账户页面绑定及固定链接正确。
- 已启用“建议搜索引擎不索引本站”，在线支付方式均未安装或启用，未接入真实支付。
- 已配置每日异地备份、保留一周并生成可用恢复点；本地备份保持关闭。
- 已轮换并复测WordPress管理员、Password Protection、数据库和Redis凭据；新凭据未写入项目文档。
- 已新增D4 Cloudways及WordPress/WooCommerce两篇配置与常用操作手册。
- 已安装WinSCP 6.5.6，生成带口令的Ed25519密钥并完成Cloudways SSH/SFTP密钥登录验证。
- 已新增并在Local与Staging激活`dentall-core` 0.1.2，注册`DentAll Content Editor`最小权限角色。
- 已创建仅限Local的`dentall_d6_editor`测试账号；密码为随机高强度值且未写入项目文件。
- 已通过48项角色、对象级所有权、媒体边界、商品标签与后台入口审计；临时测试对象已清理。
- 已冻结Staging部署架构：Cloudways Via Git＋`deploy/staging`代码专用分支，SFTP仅作应急；D25前后再评估GitHub Actions＋SSH/rsync。
- Cloudways Via Git首次部署及后续Pull成功；Staging测试账号完成菜单、直接URL、文章/商品草稿、预览、媒体上传/拒绝和测试数据清理。
- 为验证商品预览，Staging的WooCommerce Site Visibility设为Live；Cloudways Password Protection、WordPress noindex和支付关闭保持不变，不代表正式上线。
- D7已完成商品资料盘点v1：现有B2B资料表、商品附件、目录/价格PDF及旧版仓库Excel足以支撑W2～W3商品骨架和流程验证，资料层面无整体阻塞。
- 已确认D18冻结商品模型和编辑流程候选，不冻结约970条仓库物料的全部正式商品内容；全量清洗与录入不属于W2～W3。
- 已冻结资料解释边界：仓库分类、内部编号和内部单价不能直接作为网站分类、公开SKU或正式USD售价；未确认值只能作为带`TEST`标识的测试数据。
- 第一版商品路径候选为标准简单商品、标准可变商品和少量定制展示商品；询价为条件需求。爱迪特锆块、运动护齿套/EVA护齿材料片、HP0103G、FG0312D和定制包装用于代表场景验证；逐商品正式名称与销售事实不影响通用骨架，留到实际录入或D17～D18正式样本验收时最小确认。
- D8曾形成“8个推荐商品顶级分类＋Solutions映射＋1个动态促销入口”的分析候选；用户最终确认不由开发者冻结正式分类树，8个分类仅保留为设计和测试参考，分类内容由业务方自行维护。
- Local只读查询未发现现有Product Categories，当前没有分类迁移或历史分类URL兼容负担；候选结构尚未写入Local或Staging数据库。
- 已接受ADR-012并在D12 Local落实角色版本5：第一版采用一种`DentAll Website Manager`业务角色统一管理文章、页面、媒体、分类、标签、评论/商品评价、内容级SEO、商品、订单、优惠券和WooCommerce日常运营；保留低权限Content Editor及开发者Administrator，不要求业务人员切换多个角色。每位工作人员仍使用独立账号，禁止共享登录凭据。
- D9已形成SKU规则v1候选：采用制造商货号与ADS/DentAll自有编号并存的混合来源；`DentAll Website Manager`负责日常创建、重复检查和维护商品及变体SKU，开发者不参与逐个编号，只处理规则、权限、系统异常、批量改号、导入和迁移。
- 已确认商城名称保持`DentAll`，自有商品品牌为`ADS`；第三方商品使用经确认的原厂品牌，供应商和实际工厂不自动成为前台品牌。
- D9已形成Global Attributes最小候选及命名边界：Material、Size、Shade、Color、Package Quantity、Compatible System/Device和Intended Use；属性展示、筛选与Variation是三个独立决定。
- 已确认爱迪特锆块购买时需要选择Size和Shade，两者进入Variation候选；D10已验证通用组合规则，真实合法组合、逐变体SKU、价格、库存和图片留到D14及D17～D18正式样本验证。
- 已收紧D9业务确认边界：不提前集中询问运动护齿/EVA材料片、HP0103G、FG0312D、锆块组合和定制包装等逐商品细节；它们不影响通用骨架，仅在实际录入或D17～D18正式样本验收时按当前商品最小化确认。
- 已将“骨架优先与业务确认”纳入项目级规则：后续先判断未知信息是否改变数据模型、权限、安全、URL、交易流程或不可逆配置；不影响骨架的逐商品/逐内容事实不提前询问、不等待，由业务方实际录入时填充。只有结构性影响问题才最小化提问并说明节点与风险。
- 已为D1～D9共12篇每日笔记和操作手册补充文件末尾的“可复用核心思想”，覆盖需求范围、环境基线、备份恢复、Cloudways、WordPress/WooCommerce配置、权限、试录、数据治理、分类建模、远程交付和SKU/属性模型；后续每日笔记沿用同一强制总结规则。
- ADR-013已被ADR-014替代：第一版以标准价格Simple和Variable商品为开发核心；少量定制商品默认使用展示模式，询价仅在出现真实需求时重新评估。
- D10 C1-C2已形成商品类型决策树及套装、单支、包装数量和定制商品边界；D12优先验证Simple与Variable TEST原型，Inquiry原型当前不创建，Display Only仅在CR-005字段需要立即验证时作为可选第3个原型。
- CR-004已降为Needs clarification：当前不实施询价记录、邮件报价或人工报价转订单，原2～4天实施工时释放回标准价格、库存、购物车、结账、支付、物流和回归。
- 已确认CR-005：少量定制商品使用`display_only`，显示经确认的USD参考价格范围，并通过携带Product ID的`Contact Us About This Product`复用D89联系表单；不进入购物车、结账或订单。预计新增0.5～1个有效工作日。
- D10 C3已形成价格规则v1候选：标准Simple和每个可售Variation必须具有经业务确认的正数Regular price，Variable父级价格从Variations派生，Sale price使用原生字段与日期，Display Only参考范围不进入成交价或Offer。
- D10 C4已形成库存规则v1候选：可信数量才启用数量跟踪，库存按可售包装/销售单位记录；Variable默认逐Variation管理，共享物理库存才使用父级库存；Backorders默认关闭，Display Only不参与库存，临时缺货保留稳定URL。
- D10 C5已形成重量与尺寸规则v1候选：产品规格、单个可售单位发货数据与最终订单外箱分开；Simple维护自身物流数据，Variation可继承父值或按实际覆盖；D15已复核Local当前为重量`lbs`、尺寸`in`，但承运商、物流插件和真实包装样本仍未确定，未修改WooCommerce单位配置。
- D10 C6已完成五个代表场景映射和组合检查：理论组合不等于合法组合，Variation必须逐项具备唯一SKU、有效价格、唯一库存真相源及明确物流继承/覆盖；现有骨架无新增商品类型或插件缺口。
- D12原型输入已收敛为一个固定包装TEST Simple和一个2 Size×2 Shade、仅3个合法Variations的TEST Variable；Display Only仅在需要立即验证CR-005字段时作为可选第3个原型。
- D10 C7已完成跨文档一致性核对，关闭项目状态旧节点和ADR-011无条件询价事件两处文档偏差；`git diff --check`通过。当前完成的是候选规则与交接，不是WooCommerce商品配置或交易测试。
- D11已完成媒体规范v1候选：商品源图采用1:1画布和`contain`安全显示，推荐1600×1600、现有素材接受1200～2000px，普通WebP/JPEG目标不超过350KB，内容角色仍仅允许JPEG/PNG/WebP和5MB。
- D11已完成文件治理与授权边界：文件名、Title/alt/Caption/Description职责、公开PDF验收、媒体公开直链、购买后受控下载和内部证据隔离规则已形成；`CONTENT_ASSET_REGISTER.md`新增授权状态与发布闸门。
- D11 Local内容角色完整上传链、MIME拒绝、5MB边界、公开直链、GD WebP输出及清理共11项通过；未发现P0/P1。Staging管理员页只读显示10MB，未用管理员结果替代内容角色5MB证据。
- 已生成并登记`AST-011` D12媒体测试包：5张1254×1254 WebP合规占位图和1张640×480 TEST INVALID负向样本，存放于被Git忽略的`dentall资料/TEST-D12商品原型/`；仅用于开发，上线前替换。
- D12 Local商品原型已落地：TEST Simple商品44通过23项持久化审计与回收站恢复；TEST Variable商品46包含3个合法Variations（51～53），最终17项审计通过，前台价格、库存、缺货和非法组合不可选均已验证。
- D12 Website Manager角色版本5已完成独立Code Review、安全与测试复核；业务内容、媒体元数据、评论/商品评价、商品及术语、订单、优惠券、客户创建、报表和WooCommerce日常运营能力开放，WordPress用户、插件、主题、代码和系统设置保持关闭。
- D12 Staging已部署DentAll Core 0.2.1并激活Storefront；独立Website Manager账号已验证文章发布/更新/回收站恢复、页面发布/媒体上传/Alt持久化/恢复、简单商品价格库存和物流字段/发布/恢复、全局属性与属性项增删改、优惠券、空订单页、客户、报表及评论管理。
- Staging Website Manager直接访问`users.php`、`plugins.php`、`themes.php`和`options-general.php`均被拒绝；0.2.1部署后空Tools菜单已消失。未授予WordPress用户、插件、主题、核心更新、数据库或部署权限。
- 两名网站操作人员当前都使用`DentAll Website Manager`，每人必须使用独立账号；低权限`DentAll Content Editor`保留为未来可选角色，但不再作为D12当前人员验收项。
- Site Kit后续由Administrator安装和首次连接；Website Manager仅通过Site Kit Dashboard Sharing查看已共享的Analytics、Search Console、PageSpeed Insights等数据，不开放WordPress插件管理。GTM日常标签权限在Google Tag Manager平台独立分配，WordPress端容器接入仍由开发者管理。
- D12 TEST对象经用户确认暂不清理，作为D13及下周回归夹具。Local保留两个商品原型；Staging保留TEST文章、页面、媒体、简单商品、分类和优惠券。它们不是正式业务内容，D25开放批量录入前再次决定归档或删除。
- D13使用独立Website Manager账号在Staging完成培训者预演：引导创建并发布`TEST D13 Manager Simple Product`，创建TEST文章草稿；随后在不到5分钟内无提示创建另一个TEST简单商品和TEST文章草稿，无停顿或误操作。
- D13简单商品流程通过：即时促销、未来计划促销、促销价高于原价的输入校验、库存0且禁止Backorders的缺货状态、库存0但允许通知客户的Backorder状态、缺图占位、图片恢复及草稿商品回收站恢复均符合预期。
- D13形成简单商品发布字段候选：WooCommerce技术上允许SKU留空，但标准可购买商品只允许在资料未齐时留空保存草稿；正式发布前必须具备来源明确且全站唯一的SKU。该规则到D18代表样本验收后才进入商品模型候选冻结。
- D13新增TEST对象当前保留在受保护Staging：培训商品保持已发布TEST基线，独立复跑商品与两篇文章保持草稿；均不得当作正式内容，D25前统一决定归档或删除。
- D14使用Local父商品46与Variations 51～53完成Website Manager人工验收：Size/Shade均为Variation属性，3个合法组合及1个非法组合边界正确；同价/异价、缺货、默认值、图片、加购和购物车属性/金额通过。
- D14发现父商品误启用数量库存1，同时三个Variations分别管理库存，形成两个库存真相源；已取消父级数量库存并保存，父商品继续保留系列SKU，数量由各Variation独立维护。
- D14临时将Small/Light设为合法默认并通过前台验证，结束时恢复Size/Shade均无默认；购物车已清空。#51/#52继承父物流，#53覆盖物流的机制通过，但TEST数值和`lbs/in`仍不代表Production物流标准。
- D15确认三层数据职责：产品规格使用属性/内容，WooCommerce Shipping保存一个销售单位连同直接包装的数据，最终订单外箱由后续装箱/物流/履约决定；三者不得混用。
- D15由Website Manager在Local复核#44 Simple自身物流、#46父物流、#51/#52空原始字段继承和#53明确覆盖；当前`lbs/in`、所有重量尺寸均只作为TEST机制证据，承运商、物流插件和真实包装样本未确定。
- D15在#44验证数量库存与状态库存切换、数量0临时缺货和恢复：缺货时原URL、SKU、Slug、发布及目录可见性保持，前台不可购买；最终恢复数量8、有货、禁止Backorders并清空购物车。
- D15 C6主审计45/45通过，独立测试Agent重跑Simple 23/23和Variable 17/17通过；已执行范围P0=0、开放P1=0。永久停售不执行删除或改Slug，替代商品、301、Canonical和索引策略衔接D16。
- W1与W2周总结已分别整理到`project-docs/笔记/`，覆盖计划/实际对照、技术与人员验收差异、编辑反馈、缺陷、工时缺口、遗留风险及下一周交接；各日实际工时尚未完整记录，未用计划工时替代实际值。

## 进行中

- W1：Cloudways Full Access已完成，继续复核账单状态和服务器持续可用性。
- W1：从老板持有的闲置域名中选择具体域名，D4由老板提供DNS访问并完成映射。
- D13人员验收补项：两名实际网站工作人员到岗后，分别使用独立Website Manager账号完成30分钟无指导试录；培训者预演不能替代该结论，但不阻塞当前D16商品SEO骨架。
- D16：定义商品SEO标题、描述、Slug、Canonical及缺货/停售URL处理候选，继续保持Production重定向和索引配置不变。
- D10：定制商品默认展示、询价条件启用的最新方案已记录；当前无询价实施工时，真实询价商品出现后再确认角色、收件邮箱、隐私文案和SMTP。
- D10：定制展示商品的参考价格范围和商品上下文Contact入口已确认；正式价格范围、按钮文案和联系收件人在实现前由业务方提供，不阻塞标准商品价格与库存骨架。

## 当前阻塞/待确认

- W2～W3不存在资料层面的整体阻塞；以下事项按最晚节点确认，未确认前使用明确标记的TEST数据验证骨架。
- 正式商品分类名称、层级和商品归属由Website Manager根据业务内容维护，不再阻塞W2～W3骨架；设计稿入口与Solutions边界在相应前端/内容阶段按实际数据处理。
- Website Manager技术能力矩阵已在Local与Staging验收：业务内容、媒体、评论/商品评价、商品、订单、优惠券、客户和报表可操作，用户、插件、主题、代码和WordPress系统管理保持关闭。内容级SEO插件兼容性与Site Kit共享面板留到插件按计划安装后验证；D25前不得直接使用Administrator或原生Shop Manager替代。
- SKU来源、格式、唯一性和日常责任规则已在D9形成候选；具体制造商货号公开许可及ADS/DentAll自有编号细节需在D17～D18正式样本验收前确认，未确认时仅在Local或受保护Staging使用TEST编号。
- 合法变体组合、套装与单支关系、正式USD价格、库存和缺货策略不阻塞通用骨架；在对应商品实际录入或D17～D18正式样本验收时由业务方填充。真实样本不足会影响业务验收，不推翻骨架。
- 图片/PDF公开授权、英文内容、技术参数和医疗/合规事实需由业务方确认；未确认内容不得正式发布。
- Cloudways正式购买状态，以及数据库和Web服务器的最终版本记录。
- 支付服务商、物流国家/地区、运费和税费规则。
- 品牌实现方案和Solutions内容结构。
- LocalWP命令行PHP的Imagick扩展声明仍不可用；D11已确认Web/CLI实际使用GD完成PNG读取和WebP输出，不阻塞D12。若最终主题或性能阶段出现GD/Imagick输出差异，以Staging结果为部署依据。
- Git远程为开发者个人私有GitHub仓库；D3只复核跟踪边界和密钥排除。
- 正式Staging域名与DNS访问尚未确定；当前Cloudways临时域名可用于D6试录。
- `deploy/staging`代码专用分支已创建并推送；Cloudways Via Git使用只读Deploy Key连接GitHub，分支和`public_html/`路径已验证并完成首次部署。
- 插件方向已冻结：ACF Pro负责必要结构化展示字段，Yoast SEO Free负责SEO元数据与技术SEO辅助，Site Kit by Google负责未来Production的Search Console、GA4和PageSpeed数据接入；均待按计划安装与验证。
- 多语言未来方案已冻结为WPML Multilingual CMS＋ACF Multilingual＋WPML SEO＋WooCommerce Multilingual & Multicurrency；第一版仍为英语、美元，暂不安装或实施多语言、多币种。
- 支付方向暂定WooCommerce Stripe Gateway＋WooCommerce PayPal Payments＋WooCommerce原生BACS；公司主体、销售国家、账户审核、正式费率和收款负责人仍待确认，当前不连接真实支付。
- 第一版测量架构已冻结为Site Kit＋GA4＋GTM＋Search Console：Site Kit部署唯一GA4标签并放置GTM容器，GTM不重复部署GA4；WooCommerce标准事件优先使用Site Kit转化跟踪，联系表单、资料下载及实际启用的条件功能通过`dataLayer`＋GTM补充。分析与关键转化事件已从Should提升为Must。
- D5 Local浏览器走查发现的两个P2已完成代码修正，并通过Local与Staging回归：商品标签输入已移除且服务端拒绝创建；上传界面与服务端均显示并执行5MB上限。
- D5 Staging首轮菜单走查发现评论和工具入口仍由WordPress默认显示；DentAll Core 0.1.2增加菜单隐藏与直接URL 403拦截，Pull后回归通过。
- D5 Staging已Pull DentAll Core 0.1.2；评论和工具菜单已消失，用户、插件、工具、评论、设置与WooCommerce订单直接URL均被拒绝。
- 内容试录员采用可演进的能力白名单；后续可按明确业务动作逐项扩权，高风险商城和系统权限不得打包开放。

## 下一步三个验收结果

1. D16明确商品SEO标题、Meta Description、Slug与Canonical的字段职责和Website Manager操作边界。
2. 区分临时缺货与永久停售的URL、索引、替代商品和301候选流程，不直接删除或批量改Slug。
3. 核对SEO账号权限、现有TEST商品URL和风险边界，继续保持Production重定向、索引、缓存及部署配置不变。

## 本周风险

- 如果支付、物流、税费和SMTP长期不确认，会影响W13以后关键路径。
- 设计素材可后补，但商品模型必须在D18形成候选冻结，文章分类、URL和发布流程必须在D24完成样本验证，D25综合验收后才开放批量录入。
- 不要将当前大型PNG和ZIP直接提交到源码Git仓库。
- 单休节奏下第7天必须完整休息，不能靠连续取消休息日吸收偏差。

## 实际工时

| 周/日 | 计划有效工时 | 实际有效工时 | 状态 | 偏差说明 |
|---|---:|---:|---|---|
| 开发前准备 | 未纳入D1-D120 | 待记录 | 进行中 | 项目治理、Git和编辑先行准备 |
| W1 / D1 | 6小时50分钟 | 待用户记录 | 已完成 | 范围、编辑优先目标和依赖基线已确认；Cloudways购买与域名选择转入W1跟进 |
| W1 / D2 | 6小时50分钟 | 待用户记录 | 已完成 | 版本、WooCommerce基础配置、页面、数据库、日志和Mailpit冒烟验证完成 |
| W1 / D3 | 6小时50分钟 | 待用户记录 | 已完成 | Git与密钥边界、数据库/uploads/代码备份及隔离恢复验证完成 |
| W1 / D4 | 6小时50分钟 | 待用户记录 | 已完成 | Staging访问保护、HTTPS、noindex、支付边界、恢复入口及凭据轮换复测通过 |
| W1 / D5 | 6小时50分钟 | 待用户记录 | 已完成 | 最小角色、Via Git部署、Staging权限与内容生产线端到端验收通过 |
| W1 / D6 | 6小时50分钟 | 与D5连续执行，待用户记录 | 技术预验收已完成 | 开发者使用编辑账号完成商品、文章、预览、媒体和清理；真实编辑验收转D13 |
| W2 / D7 | 6小时50分钟 | 待用户记录 | 已完成 | 商品资料盘点v1完成；资料足以支撑W2～W3骨架验证，全量物料清洗不纳入本阶段 |
| W2 / D8 | 6小时50分钟 | 待用户记录 | 已完成 | 已冻结动态分类骨架与统一Website Manager职责；8个分类降为测试参考，未写入WooCommerce |
| W2 / D9 | 6小时50分钟 | 待用户记录 | 已完成（文档规则） | SKU、ADS/原厂品牌、Global Attributes及五个场景映射候选完成；业务问题异步确认，未写入WooCommerce |
| W2 / D10 | 6小时50分钟 | 待用户记录 | 已完成（文档规则） | 商品类型、价格、库存、物流尺寸、合法组合和D12原型输入候选完成；只读核对Local单位，未写入WooCommerce |
| W2 / D11 | 6小时50分钟 | 待用户记录 | 已完成（规范、测试与交接） | 媒体显示、格式/压缩、元数据、PDF/授权规则完成；Local上传链11项通过，D12 TEST媒体包已准备，未上传正式媒体或写数据库 |
| W2 / D12 | 6小时50分钟 | 待用户记录 | 已完成（双环境原型与权限验收） | Local简单/可变商品原型及自动审计完成；DentAll Core 0.2.1部署到Staging，Website Manager完成内容、媒体、商品与商城运营冒烟；TEST对象按用户决定保留用于下周回归 |
| W3 / D13 | 6小时50分钟 | 待用户记录 | 已完成（培训者预演与简单商品流程） | Website Manager引导预演和不到5分钟无提示复跑通过；促销、库存、Backorders、缺图及恢复通过，P0/P1为0；两名实际工作人员人员验收待补 |
| W3 / D14 | 6小时50分钟 | 待用户记录 | 已完成（Local可变商品流程） | Website Manager完成Variable默认值、价格、库存、非法组合、物流继承/覆盖和购物车验证；父子重复库存P1修复并完成持久化读回，已执行范围P0/P1为0 |
| W3 / D15 | 6小时50分钟 | 待用户记录 | 已完成（Local库存与物流字段） | 三层物流数据、Simple/Variable继承覆盖、数量/状态库存、临时缺货与恢复通过；45/45及独立23/23、17/17审计通过，正式物流输入仍待业务与承运商方向 |

## 更新规则

- 每天记录完成、验证证据、阻塞、实际工时和明日第一件事。
- 每天记录风险等级、启动的Agent、Review结果、未验证项和剩余风险。
- 每周第6天记录计划完成率、编辑反馈、返工原因和下周风险。
- 落后超过2个工作日时调整范围或里程碑，禁止默认占用每周唯一休息日。
