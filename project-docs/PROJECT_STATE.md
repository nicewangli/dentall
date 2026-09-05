# 项目当前状态

> 本文件是新对话和每日工作的当前事实入口。每天收工更新；历史细节进入每日Obsidian笔记和版本记录。

## 状态快照

- 更新日期：2026-09-05。
- 当前阶段：W9/D54已按用户确认范围仅在Local完成商品发现全链路回归。恢复态2商品/0品牌下，Shop、商品分类、商品搜索、排序、非持久化1项/页分页、Price/Size/Shade、已选条件、零结果、7类非法参数302、URL/SEO、Sitemap、缓存及390/768/1024/1440与1199/1200交互均通过；30品牌规模、lookup回退和清理护栏复用D53证据，未重建夹具。D54原回归运行代码净改动0，独立静态与Test/UX复核P0=P1=P2=0；提交前另获授权补入一项WooCommerce缺失短路守卫并通过动态冒烟。D33～D54已分批推送至开发者个人私有`origin/main`，D43～D54实现与主文档基线为`c0a1ba9`且远端恢复验证通过。D53三项P3继续监控；正式内容、真实设备/辅助技术、Production缓存、非Local部署及公司控制Git治理仍未完成。
- 当前计划：单休20周编辑先行版，120个工作日，自然周期约4.6个月，对外按4.5～5个月管理。
- 当前里程碑：M1技术预验收已在D6通过，Website Manager培训者预演已在D13通过；D18 M2商品模型候选冻结通过。D25商品、文章和Page当前技术/人员路径已通过，但正式内容/素材与公司Git治理未完成，因此M3继续按既有边界管理。D42 M4已按用户指定的Local技术v1口径通过：首页、页头和页脚四端骨架完成，不等于正式品牌内容、非Local部署或Production上线。WM-A可在受保护Staging按批准SOP开始“Simple模板v1、小批次、只新增Draft”的商品录入；Variable/Variation CSV仍待独立验收。
- 当前状态：Cloudways Flexible已从试用升级为Full Access；受保护Staging、HTTPS、禁止索引、支付关闭边界、恢复入口及凭据轮换均已验证。
- 当前版本：Local为WordPress 7.0.4、WooCommerce 11.0.0、Yoast 28.2、Storefront 4.6.2、DentAll 0.29.0和DentAll Core 0.2.7；活动主题为DentAll，父主题为Storefront。D50～D53的目录筛选继续集中在`inc/catalog-filters.php`与`assets/css/catalog.css`，D54原回归未改运行代码，提交前另获授权补入一项WooCommerce缺失短路守卫。Woo目录仍为每页12项、2/2/3/4列；没有Woo模板覆盖、第二商品结果查询、AJAX、插件、自定义缓存或构建链。最终属性lookup为7行/父商品#44/#46，`enabled=yes`、`direct_updates=yes`、`optimized_updates=no`，缺货隐藏为`no`，Coming Soon=`yes`，`posts_per_page=10`；发布商品仅#44/#46，#120～#130共11项仍在Trash，品牌term/关系与三类计数transient均为0。Local品牌归档继续`noindex`且不进Sitemap；商品分类Yoast模板及这些数据库配置均未同步非Local。Staging仍为DentAll Core 0.2.6部署提交`501e5e5`；DentAll子主题及D43～D54目录代码已进入个人私有Git基线，但尚未部署，两个自定义商品导入草稿模块继续未加载、未提交、未部署。Homepage、TEST菜单/Page/分类/素材和公司Git治理边界保持既有记录。
- 2026-08-22用户已在Staging确认并保存WooCommerce全局币种为`USD`（左侧货币符号、千位`,`、小数`.`、两位小数）；商品CSV价格继续只录纯数值，不承担币种转换。Staging密码重置邮件当前未送达，与既有“SMTP未配置”事实一致；不阻塞已通过的Draft商品录入，Website Manager临时由管理员受控重置密码，正式自助找回须在企业事务邮件服务选型后独立验收。

## 已完成

- 首页PC、平板横屏、平板竖屏和手机效果图。
- M4首页与全局框架已按Local技术v1完成登录态四端整链路验收；正式内容、非Local与Production不在该完成口径内。
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
- 目标协作结构下，两名网站操作人员均使用`DentAll Website Manager`并各自持有独立账号；当前仅WM-A完成实测，第二人按CR-007条件性补验。低权限`DentAll Content Editor`保留为未来可选角色，但不再作为D12当前人员验收项。
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
- D16 C2实施前完成DentAll Core 0.2.2纯结构重构：主入口由430行收敛为23行，17个函数与12个Hook保持不变；角色、媒体、商品治理和后台访问分别进入独立模块。Local Content Editor审计47/47、Website Manager只读回归9/9及四个页面HTTP基线通过，独立Code Review与安全审查均为P0～P3=0。测试Agent提出的P3为D5历史笔记记录48项、当前脚本输出47项的证据口径差异；历史记录保留，D16本次结果按47项单独记录。
- D16 C2在DentAll Core 0.2.3新增独立SEO兼容模块，只在Yoast启用时于`wp_head`优先级0移除WordPress核心重复的`_block_template_render_title_tag`。首页、商店、Simple #44、Variable #46和真实404在Yoast启用、停用及恢复后均保持单一Title；Yoast恢复后正常页面各1个Canonical、404无Canonical。未修改Title内容、URL、Slug、索引、robots、sitemap、重定向或缓存配置。
- D16 C2代码已通过`deploy/staging`提交`0467e1a`部署；Cloudways日志显示2026-08-17 07:37:37 UTC成功，Staging后台确认DentAll Core 0.2.3已激活。首页与商店返回200、真实404返回404，均为单一Title且无致命错误；因Staging未安装Yoast且商店无代表商品，Yoast启用分支与完整五页矩阵尚不能验收。
- D16 C3明确商品名称/H1、SEO Title、Meta Description、Slug、Canonical和robots的字段职责。按用户决定，Website Manager继续保留Yoast高级元数据权限，不增加代码限制；日常Title/Description可独立维护，已发布Slug、Canonical、索引和高级robots变更通过记录、复核和回归控制。
- D16 C4区分了首次发布前Slug修正、已发布URL永久迁移、重复页Canonical和无等价替代撤销场景。永久迁移使用一跳301且新页自身Canonical；Canonical不代替301，不把不相关URL批量跳首页/商店/分类。WordPress核心旧Slug 301只作为回退机制，真实变更仍须登记和验证；当前未安装重定向插件、未新增实现、未改任何环境URL。
- D16 C5冻结商品生命周期分流：临时缺货保持200、原URL、可索引、自身Canonical和`OutOfStock`，不删除/301/noindex；永久停售由业务确认，严格一对一替代才301，有持续价值则保留200停售页，无替代且无价值则真实404、必要时评估410。WooCommerce 11.0.0无原生`discontinued`状态，当前无真实停售样本，不提前新增字段或Schema代码；骨架可继续，正式停售文案、替代关系和`Discontinued`输出尚不能验收。
- D16 C6只读核对Staging：WordPress 7.0.4、WooCommerce 11.0.0、DentAll Core 0.2.3、Breeze与Object Cache Pro处于启用状态；WooCommerce 11.0.1与Breeze 2.5.13仅显示可更新，未升级。Yoast未安装，因此Website Manager虽已由DentAll Core授予`wpseo_edit_advanced_metadata`，Staging仍不能验收Yoast字段界面、保存和前台输出。
- D16 C6只读盘点当时在Staging看到2个Website Manager角色账号和1个Content Editor账号；该数量只证明存在账号记录，不能证明第二名真实工作人员账号已完成归属和登录。D24 C2用户进一步更正确认当前WM-B尚未创建，因此历史测试/角色账号不再视为跨账号流程前置；第二身份与两人各30分钟无指导验收未执行，并已按CR-007移出D24-D25当前门槛，触发特殊情况时再补验。Staging现有4个商品均为TEST数据，其中2个发布、2个草稿且全部为Simple；尚无Variable、当前缺货、可下载和真实永久停售代表样本，不能替代D17真实业务内容验收。
- D16 C6发现两个已发布Staging商品实际URL均为`/shop/{slug}/`，与项目候选基线`/product/{slug}/`不一致。C6未修改固定链接、Slug或重定向；该差异必须在D17真实URL验收前决定和复核，现有受保护且`noindex`的TEST URL不进入Production 301登记。
- D16 C6后续确认并由用户在Staging将商品固定链接从自定义`/shop/`切换为WooCommerce默认`/product/`；`/shop/`继续作为商品归档。该变更未修改PHP代码、Slug或商品数据。两个新TEST商品URL正常显示，旧`/shop/{slug}/`均自动跳至对应新URL；浏览器已确认发生跳转，但本轮未取得原始响应状态码，不能把它写成已验证301。
- D17由Website Manager在受保护Staging完成5个代表商品样本矩阵：D17新建Simple草稿#45、Variable草稿#47和多图Simple草稿#52，并补全既有已发布Simple #32、#35的Yoast Title与Meta Description；没有把5个样本误记为5个D17新建商品。
- D17 Variable #47使用全局Size与Shade，2×2理论组合只建立A/A、A/B、B/A三个合法Variations；A/A为39.99且库存5，A/B为39.99且库存0并禁止加购，B/A为49.99且库存3并覆盖父级物流为2.5 lb、9×9×4 in，B/B未建立且选择器不提供。父商品不管理数量库存，每个Variation使用唯一TEST SKU。
- D17五个样本均完成名称/H1、Slug、SKU、价格/库存、分类和Yoast字段抽查；#45验证Simple单图，#52验证1张主图加3张图库，#47验证Variable价格区间、缺货、非法组合和物流继承/覆盖。Staging继续输出`noindex, nofollow`且不输出Canonical，这是受保护环境边界，不是Production Canonical验收。
- D17 C6主回归与独立测试Agent均未发现缺陷，结论P0=0、P1=0、P2=0、P3=0。已发布#32/#35匿名访问返回200；草稿#45/#47/#52在匿名请求返回404、登录态预览正常，符合草稿隐私边界。用户手动验证#47三个合法组合的价格、库存和按钮状态，以及B/B非法组合不可选；未点击加购，购物车未改变。
- D17样本只证明原生商品与SEO生产线骨架可承载。正式名称、公开SKU来源、价格库存、合法组合、物流数值、图片/文案授权、可下载资料和真实永久停售事实仍未取得业务确认，因此真实内容尚不能验收；不新增ACF、Schema、重定向或自动停售代码。
- D17盘点登记两个不纳入样本矩阵的历史草稿：#31为空壳草稿，#39带历史`__trashed` Slug痕迹且资料不完整。D17未删除或修复，D18形成归档/清理候选，D25前完成TEST对象去留复核。
- D18 C1～C4已完成Local候选结构矩阵、Variable父子职责审计和Website Manager权限/保存修复：DentAll Core 0.2.4、角色版本6、商品原始自定义字段面板隐藏、Yoast高级字段可见及真实后台保存通过；代码、安全与测试复核无未关闭缺陷。
- D18 C5已升级Local DentAll Core至0.2.5，Website Manager可使用WooCommerce原生商品CSV导出但仍不能访问WordPress全站内容导出。Local CSV包含#44、#46、#51～#53共5行，核心字段与商品审计一致；中文Upsells/Cross-sells重复“交叉销售”表头登记为P2，D25前完成回导处置。
- D18已登记TEST对象去留候选：Local #44、#46/#51～#53继续作为回归夹具；Staging代表样本保留到D25；#31为空壳删除候选，#39先核对公开/引用历史再决定。C5未删除、改名或改变任何TEST对象状态。
- D18 C6已将DentAll Core 0.2.5同步到受保护Staging。XuDan Website Manager确认Yoast Advanced及Canonical/robots字段可见，WordPress原始Custom Fields不再出现在显示选项或商品页面；商品列表导出入口、导出页和CSV下载正常，`/wp-admin/export.php`仍拒绝访问，用户、插件、主题、工具和系统设置入口仍未开放。
- D18 C6 Staging CSV为49列、10行、6312字节，覆盖#31、#32、#35、#39、#45、#47～#50和#52；39/39项按列断言通过，确认父子SKU、价格39.99/39.99/49.99、库存5/0/3、缺货、三个合法组合、B/B非法组合缺失、物流继承/覆盖及Staging图片URL。SHA-256为`537CCCB2B9E2C4ADCB2FD06EF83806A390552AD5BB9FD6E9AB01C3C59A0F8D90`；未勾选自定义元数据，中文“交叉销售”重复表头P2继续保留至D25。
- D18 C7已完成数据字典、编辑流程、测试计划、风险、运行手册、版本记录、项目状态及W3周总结收口。M2候选冻结通过：冻结WooCommerce原生商品模型及职责约束，不冻结TEST值或逐商品正式事实，不提前开放批量录入；D19按文章信息架构与治理边界开始。
- 2026-08-19开发者本人在受保护Staging完成D18引导式个人复演：重新解释并验证Website Manager权限、Variable #47父子职责、三个合法组合、缺货/异价/非法组合、购物车、Yoast字段与Canonical默认留空；另以Website Manager创建Simple草稿#58，完整走通主图#59、图库#60、分类、品牌、SKU、价格、库存、物流、全局Size属性、描述、SEO和前台预览。Local与Staging均确认完整WooCommerce运营权限属于ADR-015的有意设计，WordPress插件、主题、用户、代码和系统管理仍关闭。商品CSV导出页可访问，CSV与图片、订单客户、全站设置、代码和恢复测试的边界已补讲并由用户确认理解。#58/#59/#60为未发布TEST对象，D25前决定保留或清理；本次不代表两名实际工作人员无指导验收。
- 用户随后在Local执行相同设置。WooCommerce 11.0.0保存“默认”时会把空选项规范化为实际`product`基础；刷新后界面因此回显为第四项“自定义基础链接`product/`”。Local与Staging该回显均属核心代码预期行为，最终商品URL一致为`/product/{slug}/`，无需重复修改。
- 用户已授权在Staging安装Yoast 28.2；插件文件成功安装后，自动激活导航多次超时，最终由用户在后台手动激活成功。后续五页矩阵确认首页、商店、两个已发布Simple商品和真实404均只有1个Title并输出`noindex, nofollow`；受全站禁止索引影响，五页均无Canonical，Production自身Canonical仍须在对应环境规则下另行验收。
- D19 C1只读盘点确认Staging现有4篇文章（2篇发布、2篇草稿）、2个文章分类、1个TEST标签及多个默认/TEST作者身份；文章固定链接当前为`/%postname%/`，首页显示最新文章，尚无独立博客页。Yoast当前让文章、分类、标签和作者归档可索引，日期归档启用但`noindex`；本次盘点没有修改数据库、Slug、固定链接或SEO配置。
- D19 C2已接受并修订ADR-017：文章范围限于DentAll工厂、公司、产品与服务相关信息，正式文章前台统一署名`DentAll Editorial Team`；若由两名Website Manager实际参与，仍应使用各自独立账号直接编写和发布，后台`post_author`保留内容作者，受支持字段的修订版本记录对应修改账号和时间，但二者不能证明发布、撤回或回收站动作人。当前由WM-A实测；依据CR-007，WM-B创建与跨账号验收不属于D24-D25当前门槛，只有第二人上岗或出现权限、交接、并发、强制互审及审计差异时才补验。团队规划仍可保留1名开发者＋2名网站人员的目标，第一版明确不新增品牌作者账号或新角色，也不把两名后台显示名改成同名。作者归档开关持久化、301回首页和作者Sitemap缺席均已验证；当前Yoast `Article` Schema仍输出实际后台账号为`Person`作者，公开署名与Schema统一转D87文章详情实现和验收。
- D19已接受ADR-018并完成Staging复核：日期归档关闭，`/blog/2026/08/`已验证301到首页；文章仍保留真实发布日期和修改日期，不改变文章详情URL。Production robots、Canonical、状态码与缓存另验。
- D19已接受ADR-019并完成Staging复核：WordPress标签能力与现有`test-d12-manager` TEST标签继续保留，标签归档为`noindex, follow`且已从Yoast XML Sitemap排除；D25再决定TEST标签去留，Production结果另验。
- D19已接受ADR-020并完成Staging信息架构复核：分类URL、SEO标题模板与`category-sitemap.xml`均符合规则。只有正式名称/Slug、多篇相关文章和独立说明的分类才满足Production索引门槛；现有`TEST D12 Content`与`Uncategorized`仍是D24/D25内容治理对象，Production索引和Canonical另验。
- D19已接受ADR-021并完成Staging复核：文章详情标准URL冻结为`/blog/{slug}/`，已发布文章、草稿预览、文章Sitemap及分类/标签实际路径均已抽查。页面和`/product/{slug}/`商品不变；现有TEST旧地址没有Production历史资产，不补无意义301，Production SEO与缓存另验。
- D19文章URL路由人工验证通过：用户确认已发布TEST文章的新`/blog/{slug}/`可正常访问，移除`/blog/`后的旧根路径显示404，草稿#36使用WordPress查询参数预览URL`/?p=36&preview=true`正常预览；预览地址没有`/blog/`属于预期。现有TEST旧地址没有Production历史资产，不补无意义301；原始HTTP状态码、Canonical、Sitemap和Production输出仍待复核。
- D19 Yoast Sitemap Index截图复核通过类型矩阵：共6项，包含`post`、`page`、`product`、`category`、`product_brand`和`product_cat`；没有`author-sitemap.xml`与`post_tag-sitemap.xml`，证明作者与标签Sitemap已排除，`category-sitemap.xml`保留符合分类主归档规则。日期归档没有独立Yoast Sitemap，其缺席不能单独验证开关；`post-sitemap.xml`内文章URL、页面级robots/Canonical、作者/日期归档URL和Production输出仍待复核。
- D19 `post-sitemap.xml`截图复核通过：共3个URL，包含首页`/`和两篇已发布TEST文章；两篇文章均为`/blog/{slug}/`，两篇草稿未进入Sitemap。首页仍因阅读设置“您的最新文章”而兼任文章归档，因此出现在文章Sitemap；独立`/blog/`归档与静态商城首页尚未建立，页面级Canonical、原始状态码和Production输出仍待复核。
- D19用户已发布空白原生结构页`Home`与`Blog`，页面列表截图确认两页均为已发布；未新增角色/账号、正式内容、区块、特色图或SEO高级值。Slug与“静态首页/文章页”映射尚未保存验证，因此当前首页仍按原阅读设置处理。
- D19已接受并实施ADR-022：用户在受保护Staging把阅读设置改为“一个静态页面”，主页指定`Home`、文章页指定`Blog`，并保持全站禁止索引。用户人工确认`/`显示静态Home占位、`/blog/`显示已发布文章、`/blog/{slug}/`详情继续正常，三层路由通过；两页仍是空白结构页，不代表首页内容、博客UI、分页或Production SEO完成。
- D19无痕窗口复核`post-sitemap.xml`确认3个URL均正确：`/blog/`文章归档和两篇`/blog/{slug}/`已发布文章；旧首页`/`已不在Post Sitemap。Yoast 28.2会把`page_for_posts`作为Post归档加入Post Sitemap，并从Page Sitemap排除该Page，因此`/blog/`保留在Post Sitemap是预期行为，不是缓存。Codex此前预期Post Sitemap只剩2篇文章且`/blog/`进入Page Sitemap的判断已纠正；下一步Page Sitemap应包含静态首页`/`但不重复列`/blog/`。
- D19 `page-sitemap.xml`截图确认首页`/`存在且`/blog/`未重复，Home/Blog Sitemap归类通过；同时发现P1 SEO配置缺口：`/cart/`、`/checkout/`、`/my-account/`仍进入Page Sitemap，应保留WooCommerce页面但逐页设置`noindex`并验证从Sitemap移除。`/shop/`应继续索引；`/sample-page/`和D12 TEST页面转D25清理/去留，不在本轮直接删除。
- D19用户已将Cart、Checkout和My Account逐页设为Yoast `noindex`；刷新后的Page Sitemap从7项降为4项，只保留首页、Sample Page、Shop和D12 TEST页面，P1 SEO配置缺口关闭。三张WooCommerce必需页面未删除；Sample Page与D12 TEST页面仍转D25清理/去留。
- D19只读HTTP验证：首页、`/blog/`、Cart和My Account为200；空购物车下Checkout 302到Cart后200。响应均有HTTP `X-Robots-Tag: noindex, nofollow`与Meta robots `noindex, nofollow`，且无Canonical，符合Staging全站禁止索引边界；Production自身Canonical仍未验。
- D19确认`/blog/%postname%/`的WordPress前段会作用于文章相关归档：正确URL为`/blog/category/{slug}/`、`/blog/tag/{slug}/`、`/blog/author/{slug}/`及`/blog/{year}/{month}/`，不是此前假设的根路径`/category/`、`/tag/`。TEST分类/标签正确URL均200；标签为Yoast `noindex, follow`且无Sitemap；两个已有发布作者归档均301到首页；日期归档`/blog/2026/08/`亦301到首页。分类标题已移除“归档”变量；分类索引与Canonical受Staging全站`noindex`影响，Production另验。
- D19两篇已发布TEST文章REST抽查确认后台作者分别保留实际账号，Yoast输出`Article` Schema；当前Schema作者仍是实际账号对应的`Person`，与ADR-017统一公开署名目标尚有实现差距。该差距不改变后台内容作者账号，已转D87文章详情模板与SEO输出验收，不在D19增加品牌账号、角色或修改Storefront/Yoast核心文件。
- D19“博客信息架构v1”验收通过，P0=0、P1=0：冻结并验证原生Post/Category/Tag内容模型、静态首页与独立博客归档、文章/分类/标签URL、分类主归档、标签`noindex`、作者/日期归档关闭、Website Manager文章职责和Yoast Sitemap归类。D24真实内容、D25 TEST对象清理、D87公开署名/作者Schema及Production SEO均为已登记后续项，不阻塞D19完成。
- D20 C1已完成Staging只读文章盘点与字段职责冻结：现有2篇发布、2篇草稿保持原状；两篇草稿均覆盖标题、摘要、特色图、TEST分类和2次修订，但正文极短、没有H2/H3或正文内链，不能替代D20长文验证。文章模板采用原生Post＋操作清单，草稿可不完整，送审前再检查标题、Slug、摘要、正文、特色图/alt、正式分类、后台实际作者、相关内链和SEO字段；特色图基线为16:9、推荐1600×900。C1未保存、发布、删除或修改任何Staging数据、URL和配置。
- D20 C2已冻结正文骨架：文章标题承担唯一H1，正文常规使用引导段＋2～4个H2，确有下级内容才使用H3且不跳级；Paragraph、H2/H3、List、Image和Separator为常规集合，复杂布局、Table、File、Buttons及Embed等使用前复核，H1、Spacer、Custom HTML、未知Shortcode、直接iframe/script/style和手工空白排版禁止普通编辑使用。D20长文TEST夹具约1200～1500个英文单词、4个H2和至少1个H3，只作覆盖而非SEO字数标准；目录实现转D87按真实导航需求评估。C2未改Staging数据或代码。
- D20 C3～C7已完成TEST机制验收：#68保持草稿，分类为`TEST D12 Content`；6处中文混入及连写问题已关闭；配置#59特色图与正文图、1条相关内链及Yoast Title/Meta。登录态预览读回1个文章H1、4个业务H2、2个业务H3、两张图和目标200的内链。临时标记保存后恢复修订#73，修订数5→7且标记消失；匿名`/?p=68`为404，Post Sitemap不包含#68或其Slug。#59源图1280×1372只验证媒体机制，不满足正式特色图16:9与授权验收；未发布、未改Production、全站SEO、权限或缓存。
- W1与W2周总结已分别整理到`project-docs/笔记/`，覆盖计划/实际对照、技术与人员验收差异、编辑反馈、缺陷、工时缺口、遗留风险及下一周交接；各日实际工时尚未完整记录，未用计划工时替代实际值。

## 进行中

- W1：Cloudways Full Access已完成，继续复核账单状态和服务器持续可用性。
- W1：从老板持有的闲置域名中选择具体域名，D4由老板提供DNS访问并完成映射。
- D13历史人员验收缺口：此前按口头信息记录两个独立Website Manager账号均能登录；D24 C2用户更正确认目前只有WM-A完成实际准备。CR-007已把第二身份、两人各30分钟无指导试录和跨账号流程转为条件性补验，不再列为D24-D25当前进行中；历史角色/测试账号不能替代未来真实WM-B。
- D10：定制商品默认展示、询价条件启用的最新方案已记录；当前无询价实施工时，真实询价商品出现后再确认角色、收件邮箱、隐私文案和SMTP。
- D10：定制展示商品的参考价格范围和商品上下文Contact入口已确认；正式价格范围、按钮文案和联系收件人在实现前由业务方提供，不阻塞标准商品价格与库存骨架。

## 当前阻塞/待确认

- W2～W3不存在资料层面的整体阻塞；以下事项按最晚节点确认，未确认前使用明确标记的TEST数据验证骨架。
- 正式商品分类名称、层级和商品归属由Website Manager根据业务内容维护，不再阻塞W2～W3骨架；设计稿入口与Solutions边界在相应前端/内容阶段按实际数据处理。
- Website Manager技术能力矩阵已在Local与Staging验收：业务内容、媒体、评论/商品评价、商品、订单、优惠券、客户和报表可操作，用户、插件、主题、代码和WordPress系统管理保持关闭。D17已验证Yoast字段实际录入、保存和前台输出；Site Kit共享面板仍待后续。D24 A/C6已按“可查批准SOP、独立判断与留证”的口径通过，A/C7完成当前对象、字段、预览、公开隔离、修订恢复和登记抽样；第二人无指导试录按CR-007触发时补验，不得直接使用Administrator或原生Shop Manager替代。
- SKU来源、格式、唯一性和日常责任规则已在D9形成候选，D17仅以TEST编号验证父子唯一性；具体制造商货号公开许可及ADS/DentAll自有编号细节需由业务方确认，未确认时不得发布正式商品。
- 合法变体组合、套装与单支关系、正式USD价格、库存和缺货策略不阻塞通用骨架；D17 TEST矩阵只证明显式合法组合、逐Variation库存和前台状态机制可用，不证明锆块的正式Size/Shade值或组合。
- 图片/PDF公开授权、英文内容、技术参数和医疗/合规事实需由业务方确认；未确认内容不得正式发布。
- Cloudways正式购买状态，以及数据库和Web服务器的最终版本记录。
- 支付服务商、物流国家/地区、运费和税费规则。
- 品牌技术方案已由D52冻结为WooCommerce原生`product_brand`，无品牌留空且归档第一版noindex；D53已确认首版预计30个有效品牌并完成该规模的Local控件/负载验证。仍待业务方提供正式英文品牌清单、别名、逐商品归属和素材授权；超过30项需重新评估。Solutions的Page优先方向已确认，待确认的是正式内容范围、英文标题/Slug、媒体授权、相关商品和导航位置。
- LocalWP命令行PHP的Imagick扩展声明仍不可用；D11已确认Web/CLI实际使用GD完成PNG读取和WebP输出，不阻塞D12。若最终主题或性能阶段出现GD/Imagick输出差异，以Staging结果为部署依据。
- Git远程当前仍为开发者个人私有GitHub仓库；D3只复核跟踪边界和密钥排除，公司控制的所有权、备份、访问与交接路径仍待D25前确认。
- 正式Staging域名与DNS访问尚未确定；当前Cloudways临时域名可用于D6试录。
- `deploy/staging`代码专用分支已创建并推送；Cloudways Via Git使用只读Deploy Key连接GitHub，分支和`public_html/`路径已验证并完成首次部署。
- 插件方向已冻结：ACF Pro负责必要结构化展示字段，Local已安装6.8.7但在D16冲突隔离后保持停用；Yoast SEO Free负责SEO元数据与技术SEO辅助，Local与Staging均已安装并激活28.2，完成Title唯一性和Staging禁止索引边界验证；Site Kit by Google负责未来Production的Search Console、GA4和PageSpeed数据接入，仍待后续按计划安装与验证。
- 多语言未来方案已冻结为WPML Multilingual CMS＋ACF Multilingual＋WPML SEO＋WooCommerce Multilingual & Multicurrency；第一版仍为英语、美元，暂不安装或实施多语言、多币种。
- 支付方向暂定WooCommerce Stripe Gateway＋WooCommerce PayPal Payments＋WooCommerce原生BACS；公司主体、销售国家、账户审核、正式费率和收款负责人仍待确认，当前不连接真实支付。
- 第一版测量架构已冻结为Site Kit＋GA4＋GTM＋Search Console：Site Kit部署唯一GA4标签并放置GTM容器，GTM不重复部署GA4；WooCommerce标准事件优先使用Site Kit转化跟踪，联系表单、资料下载及实际启用的条件功能通过`dataLayer`＋GTM补充。分析与关键转化事件已从Should提升为Must。
- D5 Local浏览器走查发现的两个P2已完成代码修正，并通过Local与Staging回归：商品标签输入已移除且服务端拒绝创建；上传界面与服务端均显示并执行5MB上限。
- D5 Staging首轮菜单走查发现评论和工具入口仍由WordPress默认显示；DentAll Core 0.1.2增加菜单隐藏与直接URL 403拦截，Pull后回归通过。
- D5 Staging已Pull DentAll Core 0.1.2；评论和工具菜单已消失，用户、插件、工具、评论、设置与WooCommerce订单直接URL均被拒绝。
- 内容试录员采用可演进的能力白名单；后续可按明确业务动作逐项扩权，高风险商城和系统权限不得打包开放。

## D21固定页面候选结论

- [x] 完成About、Contact、Privacy、Terms、Shipping、Returns/Refunds和FAQ的候选范围盘点；Solutions保留至D22判断。
- [x] 固定页面默认使用原生WordPress `Page`，当前候选全部为顶级页面，不提前建立政策父子层级。
- [x] 完成页面责任边界候选：Website Manager负责录入并按来源核对，老板或指定业务/合规负责人确认正式事实和政策；当前没有独立SEO岗位，Website Manager维护内容级SEO，开发者负责高影响URL、菜单、表单安全和技术输出。
- [x] 形成主导航、页脚、WooCommerce功能页和Contact `product_id`上下文依赖候选；未修改或实测Staging菜单。
- [x] 定义空页、草稿、缺负责人、待业务确认、缺媒体授权和未完成SEO等状态的验收边界。
- [x] 2026-08-21 D24 C1截图复核：Staging Page列表共10项（8项已发布、2项草稿）；Reading设置仍以`Home`为首页、`Blog`为文章页，且环境继续勾选建议搜索引擎不索引。本轮未创建页面、未修改绑定或索引设置。
- [ ] 正式页面标题、Slug、联系方式、政策文案、FAQ范围和菜单配置仍未冻结；D25前完成。

## D22 Solutions结构决定与待验证项

- [x] ADR-023已于2026-08-21获用户确认：第一版Solutions以原生WordPress `Page`优先承载，不注册Solutions CPT。
- [x] 形成候选URL`/solutions/`；存在真实且独立条目时才评估`/solutions/{slug}/`，正式标题、Slug、菜单和条目范围仍待业务确认。
- [x] 形成原生最小内容模型候选：标题、摘要或首屏简介、正文、适用对象/场景、相关商品或Contact入口、授权媒体/alt、SEO字段与发布状态；不增加ACF、关系字段、自动聚合或筛选。
- [x] 更正CPT升级门槛：数量只是信号，必须结合可重复字段、独立归档/筛选、频繁增删及可验证维护成本综合判断，不设机械的“约6个”硬阈值。
- [x] 完成文档与自定义代码范围的只读盘点：未发现Solutions CPT注册、已登记的正式Solutions条目、归档或筛选实现；用户提供的Staging Page列表截图及`Solutions`标题搜索未发现对应Page。本证据不覆盖Local、Production、回收站、其他`post_type`、Slug冲突或公开URL，不能扩写为全环境数据库审计。
- [x] 用户接受Page优先、不建CPT；该结构决定不等于已创建Solutions Page或冻结正式URL。
- [x] WM-A已在明确TEST的Page #76完成原生Page标题、Slug、正文区块、作者、草稿状态、默认模板、无父页面和评论关闭的保存回读；登录态预览正常。该项描述的是C2初始草稿证据；Page #76随后完成A/C3发布路径与A/C4更新/恢复路径，现已安全收口为Draft，不是正式Solutions。
- [x] CR-007已将WM-B从D24-D25当前验收范围移除；C2按WM-A角色级路径完成。未来触发第二人上岗、权限差异、强制互审、交接、多人并发或审计需求时，再用公司控制的唯一邮箱创建独立账号并专项验证跨账号修订。
- [ ] 正式Solutions范围、英文标题/Slug、媒体授权、相关商品与导航位置仍待业务确认；不阻塞D23治理骨架。

## D23内容审核、发布与媒体治理已确认方向与待验证项

- [x] ADR-024治理方向已获用户确认：第一版以原生状态、受支持内容字段修订、素材登记和发布/变更登记完成基础治理；不预先新增审计日志插件。
- [x] 明确目标协作结构下两名Website Manager应以独立账号承担日常运营、按需互审和发布；当前仅WM-A实测，同一角色具备发布能力，`Pending Review`只是流程信号，不是系统强制审批。
- [x] 形成业务/合规升级、误发布撤回、授权撤回与已公开URL迁移边界候选。
- [x] 明确`Pending Review`只适用于未发布内容；已发布对象点击“更新”会直接改变公开版本，高风险更新必须先在受保护Staging或独立审阅草稿审核后同步。
- [x] 明确`post_author`是内容作者、原生修订只追踪受支持内容字段的对应修改账号；发布、撤回和回收站等状态动作不具备天然操作者审计，必须另行登记或显式接受第一版缺口。
- [x] 明确原生修订与素材登记不能逐次审计全部WooCommerce、媒体和SEO元数据操作；第二版按需评估审计日志能力。
- [x] 用户最终决定以`project-docs/CONTENT_ASSET_REGISTER.md`作为唯一活动业务登记载体，统一记录内容发布/变更与素材授权；WM-A维护业务记录，开发者维护结构、Git版本与交付证据。原`.xlsx`的16条A/C3-A/C4记录已迁移，生成文件已删除且不再作为事实来源。
- [ ] 公司控制Git远程的所有权、备份、访问与交接路径，以及首条真实内容/素材记录仍待验证；选择Markdown不等于公司治理、审计或人员验收闭环，多人读写只在CR-007触发后补验。
- [ ] D23治理仍未整体完成：A/C3的新Page状态路径、A/C4的已发布更新/修订/两类恢复、A/C5高风险独立审阅Draft、A/C6 SOP辅助人员验收及A/C7抽样均已通过，当前技术/人员边界已关闭；首条真实素材/正式内容登记尚未形成，公司控制Git交接仍为D25门槛，因此ADR-024尚未冻结。第二身份与跨账号编辑为当前不适用的条件性补验，不能写成已通过。

## D24 A/C1结构确认、A/C2 Page走查、A/C3-A/C4发布恢复、A/C5培训与A/C6-C7验收

- [x] 用户接受第一版非强制互审流程，并最终选择Git中的`CONTENT_ASSET_REGISTER.md`作为唯一活动登记载体；D24 C2进一步更正确认WM-A可用、WM-B尚未创建，不能再声称两个真实账号均已就绪。
- [x] 依据用户提供截图完成Staging Page列表、`Solutions`标题搜索、Home/Blog绑定及noindex的只读复核；未创建、编辑、发布、删除Page，也未修改菜单、URL、SEO或环境配置。
- [x] 历史阶段曾生成并验证包含两个工作表的普通`.xlsx`；用户随后决定停止Excel，16条A/C3-A/C4记录迁移后该文件已删除。后续记录只写入`CONTENT_ASSET_REGISTER.md`，不再转换为Google表格或双写Excel。
- [x] WM-A在Staging创建并保存Page #76 `TEST D24 Page Field Walkthrough`；Slug为`test-d24-page-field-walkthrough`，C2初始状态为Draft，作者为WM-A，默认模板、无父页面、评论关闭，正文为Paragraph、H2和List原生区块。
- [x] Page #76登录态预览正常；无痕访问`?page_id=76`返回Error 404；`page-sitemap.xml`搜索该Slug为`0/0`，未进入Sitemap。
- [x] WM-A后台菜单不显示用户、插件、主题、工具和设置；直接访问`users.php`、`plugins.php`、`themes.php`、`options-general.php`和`tools.php`均被拒绝。
- [x] A/C3由WM-A将Page #76从Draft改为Pending Review；匿名精确URL继续404，Page Sitemap仍不含该Slug。同一WM-A仍看到并能执行发布，证明`Pending Review`只提供协作信号，不构成强制互审。
- [x] WM-A首次发布后，精确URL返回200且Page Sitemap包含该Slug；同时发现Storefront在Primary与Handheld均未分配显式菜单时使用页面fallback，导致所有已发布Page（包括#76）自动进入导航。该结果不是“自动添加新的顶级页面”复选框造成。
- [x] 为控制公开面，先将#76撤回Draft；Sitemap随即移除Slug，但精确URL仍命中旧的公开缓存并返回`X-Cache: HIT`、HTTP 200。清除Breeze/Varnish缓存后，精确URL返回404、`X-Cache: MISS`，Sitemap和首页均不再出现#76，撤回P0关闭。
- [x] Administrator创建原生菜单#29 `TEST Staging Controlled Navigation`，顺序为Home、Blog、Cart、Checkout、My account、Shop；分配给Primary与Handheld，关闭自动添加顶级页面，Secondary不分配。菜单中不包含Page #76、D12 TEST Page或Sample Page。
- [x] 菜单控制后由WM-A最终重新发布#76；发布瞬时精确URL为200、`X-Cache: MISS`、`Age: 0`，Page Sitemap包含Slug，Primary与Handheld均保持六项且不含#76。后续只读回归中该Published页面正常转为`X-Cache: HIT`（Age约614）；长期HIT是已发布页面的预期缓存，不应误写成必须持续MISS。
- [x] 历史`.xlsx`曾含A/C3的6条与A/C4的10条动作，共16条；这些记录已迁移到`CONTENT_ASSET_REGISTER.md`，生成文件已删除。本轮未形成正式素材授权记录。
- [x] CR-007已将WM-B创建、跨账号编辑、双人读写和第二人复跑从D24-D25当前范围移除；C2按WM-A角色级技术路径通过收口。该项状态为“当前不适用/条件性补验”，不是WM-B已通过；未来第二人上岗、权限差异、强制互审、交接、并发登记或账号级插件差异出现时再执行专项矩阵。
- [ ] `solutions` Slug占用、对象ID、回收站、匿名URL与Sitemap未完成全量审计；由于C1不创建对象，该项不构成当前数据或URL变更风险，最晚在创建首个正式Solutions Page前完成。
- [x] A/C3单账号技术路径通过；Page #76曾保留Published作为C4 TEST夹具。
- [x] A/C4的低风险Published更新、V1/V2修订、普通恢复与立即撤销均通过；证实普通恢复为Draft，而从Published移入回收站后的立即撤销会恢复Published并直接重新公开。
- [x] A/C4最终收口通过：Page #76为Draft，V1与5个修订保留，V2不存在；匿名精确URL为404且无正文泄漏、REST为401、Sitemap和两份导航均无#76。安全缓存的404可以为HIT，旧Published正文返回200才是失败。
- [x] C2已按WM-A代表Website Manager角色级技术路径收口；中文手册保留未来WM-B条件性补验说明，不再等待第二账号。
- [x] A/C5有指导手册与WM-A培训完成：Page #11只读检查后因样例政策及未经批准的30天条款被排除，仍为Draft；Post #24保持Published源基线且未修改。
- [x] A/C5高风险路径使用Post #90独立审阅Draft隔离；已检查正文结构、摘要、分类/标签、内链、评论与Yoast，并验证登录预览、匿名404、Sitemap排除及源Post #24不变。
- [x] Post #68与Page #76完成有指导字段操作并保持Draft；#68已移除不合规特色图但正文TEST图仍保留，#76仍为TEST Page。#68/#90的授权16:9特色图尚未完成。
- [x] A/C6按CR-009/ADR-026的SOP辅助口径通过：允许查阅对象ID、URL、字段与步骤清单，不要求闭卷背诵；操作者仍须自行判断、验证、留证并在不确定时停止升级。2026-08-22只读回归确认#24匿名ID路径为200，#90/#68/#76匿名ID路径为404，Draft Slug不在相应Sitemap且首页无#76。
- [x] A/C7可查SOP抽样通过：四对象状态/用途一致；#90字段、内链、无特色图、Yoast与登录预览正常；#76保存临时标记后修订5→6，恢复后为7且C7消失、C4保留、状态/非修订字段不变；恢复前后匿名404、REST 401、Sitemap/首页排除，Markdown已登记，开放P0/P1为0。
- [ ] 3篇正式文章＋1个正式固定Page及授权16:9素材仍待完成；D24整体不得标记完成。公司控制Git远程所有权、备份和交接属于D25/M3门槛，不再误列为A/C7通过条件。

## D25 C3～C6 Staging综合验收

- [x] Website Manager CSV权限小改动已提交、部署并由用户完成Cloudways Pull；WM-A可见WooCommerce原生Import/Export并成功进入映射页。自定义导入草稿未加载、未提交、未部署。
- [x] 首次2行Simple源CSV导入结果为Imported 2、Updated 0、Skipped 0、Failed 0；新建#109/#110均为Draft。导入前11条与导入后13条CSV记录逐列对比，仅新增这两个对象，既有11条0处变化。
- [x] 同源重复SKU负向复跑结果为Imported 0、Updated 0、Skipped 2、Failed 0；顶级`product`仍为10，全量CSV另含3个Variation，合计13条记录。此前把13条CSV记录口径写成13个顶级商品的说法已纠正。
- [x] #110普通Trash → Restore后仍为Draft，ID/SKU、价格和库存状态正常；WP-CLI确认#109/#110均为`post_author=4`，对应WM-A及`dentall_website_manager`角色。该证据只证明创建账号，不证明恢复或后续修改人。
- [x] C4文章回归使用Post #111并复用#68/#90长文与审阅Draft证据；C5复用Page #76的创建、发布、修订、恢复、菜单、缓存和撤回证据。技术/人员路径通过，不替代正式业务内容与素材授权。
- [x] 独立代码/权限审查P0=0、P1=0。P2保留全局`import`粗粒度边界及未跟踪导入草稿的误纳入风险；未经新授权不处理草稿，每次提交/部署前检查暂存清单。

## D26 Storefront子主题骨架

- [x] 用户明确确认D26实施范围：复用现有`dentall`目录转换为Storefront子主题，处理阻断继承的旧Starter模板，保留D25 TEST对象，只建立骨架与资源加载边界。
- [x] `style.css`已声明`Template: storefront`和DentAll 0.2.0；`functions.php`只加载主题初始化与Storefront Hook模块。旧`header.php`、`footer.php`、`front-page.php`和`index.php`已删除，当前主题回到Storefront模板继承链。
- [x] 未重复加载子主题样式：Storefront自动在父主题与WooCommerce样式之后加载DentAll `style.css`，三者顺序与单次加载已通过HTTP验证；D26没有额外CSS/JS文件和请求。
- [x] 为关闭D24已发现的公开导航风险，只在Primary与Handheld未分配菜单时禁用Page fallback；Local实际页面回退项由12降为0，Secondary回退参数保持原值。
- [x] Local已激活DentAll子主题；首页、Shop、Cart、My Account均返回200并使用Storefront页头、内容和页脚，旧Starter标记为0。PHP语法、主题头、禁止项、样式顺序和实现验证窗口错误日志增量均通过。
- [x] 独立Code Review结果为P0=0、P1=0、P2=0、P3=0，可以标记“D26 Local通过”。Storefront 4.6.2未声明测试到WordPress 7.0.4，Staging版本复核、部署与运行矩阵留在单独授权后的部署步骤，不能用Local结论代替。

## D27设计证据与Design Token

- [x] 用户于2026-08-25明确确认冻结C1证据优先级和冲突处理口径，并授权写入Day27笔记与项目状态后进入C2；该授权不等于C3编码授权。
- [x] 用户随后确认四端覆盖、Mobile First及简洁易学代码边界，并于2026-08-25明确回复“可以的，开始C3吧”，授权C3最小实现；该授权不自动扩大到C4～C7。
- [x] C1证据层级冻结：A为首页三端原始终稿及合图，B为1440/768/390高清增强稿，B-为1024推导稿，商城B只约束内容区，C为总览，P为开发占位。原始PC、竖屏、手机和横屏图与本地参考库对应文件SHA-256一致；两个响应式ZIP各41个文件，36个相同、5份说明文档不同，采用v2及当前`design-assets/guides/`。
- [x] 首页宏观顺序冻结为Announcement Bar、Header、Hero、Selected Categories、Solutions、Best Sellers、Trust Metrics、Newsletter、Footer；9/4/5只作布局覆盖目标，不硬编码分类、卡片或指标业务数据。
- [x] 冲突口径冻结：A层及已确认需求/WooCommerce真实状态优先；1024采用紧凑导航方向、完整导航从`>=1200px`开始；手机Footer内容不得因截图省略而删除；截图独有的多语言/多币种、Wishlist、Buy Now、Wallet、Notifications、社交登录、固定手机底栏和视频不进入第一版。
- [x] C2形成候选：带`--dentall-*`前缀的语义Token；Source Sans Pro现有400/600/700字重；1320px容器外框；20/32px gutter；4px间距阶梯；6/10/14px圆角；768/1024/1200断点锚点；390/768/1024/1440为主验收宽度。当前0.3.2已应用C3 Token、C4基础容器及C5从768px起的32px宽屏gutter；1024/1200没有容器变化，因此未生成空媒体查询。
- [x] C2独立只读复核P0=0，并识别4个条件性P1后写入候选约束：原`#667085`在`#eaf3ff`上仅4.44:1，通用次文本改候选`#5f6b7a`；`#f6a700`不作唯一信息信号；无构建链时禁用`@custom-media`；Storefront从768px启用桌面导航的行为留D31显式调整到1200px门槛。C2当时的P2为1320px浏览器量测、现有Google字体依赖/品牌许可证和浅边框用途边界；其中1320px外框语义已在C5经用户确认与浏览器量测关闭，其余项留对应实现日处理。
- [x] C3只修改子主题`style.css`：0.2.0提升为0.3.0，落地63个`--dentall-*`Token及最小`body`颜色/字体/字号/字重/行高映射；没有标题、链接、控件、容器、媒体查询、Header/Home组件、新请求或依赖。
- [x] C3静态审计、HTTP加载、颜色复算和独立Code Review通过，最终执行范围P0/P1为0；Home、Shop、Cart、My Account在390/768/1024/1440共16组均为200、无横向溢出、无控制台/页面错误，子主题CSS只加载一次且版本为0.3.0。
- [x] 用户在既有C4范围说明后明确回复“继续C4吧”；C4只在同一`style.css`新增一个低权重`.col-full`规则并提升至0.3.1，以1320px border-box外框上限、20px内侧gutter和自动居中完整覆盖Storefront原有混合盒模型，没有媒体查询、组件、依赖或新请求。
- [x] C4静态/HTTP/日志检查和独立Review通过，P0/P1为0。真实父子CSS夹具在390/768/1024下内容左右20px，1440下外框x=60/宽1320、内容x=80/宽1280；320/375/430辅助小屏也无溢出。公开四路由×四宽度共16组失败0且0.3.1单次加载。
- [x] 用户明确回复“开始C5吧，确定1320px是‘容器外框宽’”；C5提升至0.3.2，仅新增`@media (min-width:48rem)`中的一条32px gutter覆盖。767/768边界及12个宽度夹具失败0；390/768/1024内容宽为350/704/960px，1440外框x=60/宽1320、内容x=92/宽1256。1024/1200只验证连续性，不建立空断点。
- [x] C5静态/HTTP/日志和公开四路由×四宽度回归通过，0.3.2单次加载、无横向溢出或控制台/页面错误；独立Review P0/P1为0。
- [x] C6真实Shop四端验证完成：CSS视口390/768/1024/1440对应Windows经典滚动条下375/753/1009/1425px布局宽；前三端主容器分别为20/32/32px gutter，1440主外框x=53、宽1320、左右32px、内容1256px。四端`scrollWidth === clientWidth`，商品单列/多列自然换行，嵌套`.col-full`为0；商品图alt有值、破图0、标题/Landmark/重复ID及排序控件可访问名称无P0/P1，控制台warn/error为0；独立Review允许关闭C6。
- [x] C7完成Home、Shop、Cart、My Account在390/768/1024/1440共16组登录态真实DOM与视口截图：每组子主题0.3.2正常加载，正文只有一层`.col-full`、嵌套为0，1320px外框及20/32px gutter正确，`scrollWidth === clientWidth`，重复ID、破图和页面控制台warn/error均为0。Cart只覆盖当前空态，Account只覆盖当前登录态，不外推全流程；匿名Coming Soon仍在且未被用作真实DOM证据。双重独立复核P0/P1为0，D27已关闭。
- [ ] D27延期P2已登记但不阻塞收口：Home/Shop/Account的390固定手持底栏最终组件已由D31～D34收口；四页768/1024 Header已由D31～D34收口；Shop排序控件可见标签与基础Focus已于D28关闭。`lang="en-US"`下Home技术页已于D37改为明确TEST英文Hero，Shop公开标题已于D43改为`Products`且`/shop/`不变；正式英语业务文案仍待内容验收，Cart与Account的中文/中英文混排继续在D68/D81对应页面关闭。Cart专用空态CTA及最终视觉转D68。任何配置或内容修改仍需另行授权。

## D28基础控件与可访问状态

- [x] 用户于2026-08-25明确确认按已提交功能确认单实施Day28，范围仅限Local、不部署Staging；随后明确开始C1。授权不覆盖Header/Footer/卡片/页面组件提前实现、表单业务逻辑、插件/依赖、模板覆盖、数据库/配置或Staging/Production变更。
- [x] C1冻结元素与状态：正文区H1～H6、正文文本链接、内容区操作按钮、常用文本控件、checkbox/radio、Focus和已有字段错误状态；商品卡最终标题、系统通知、导航、真实登录/注册/结账逻辑均留对应计划日。
- [x] C1冻结选择器边界：视觉规则以`.site-main`为主，Focus以`.site`为范围并仅保留当前深色Header的白色Focus例外；一般列表使用低权重`:where()`，仅在必须覆盖Storefront现有规则时使用受控`:is()`或真实组件作用域，不使用ID、`!important`、行内代码或无实际变化的断点。Shop排序保持自适应宽度，数量与Variation控件列为受影响回归面。
- [x] C1冻结Shop路线：WooCommerce 11.0.0原生支持`woocommerce_catalog_ordering( array( 'useLabel' => true ) )`；C2在父主题Hook注册后替换Storefront上下两处排序回调，输出原生可翻译`Sort by`、唯一`for/id`并保留查询字段，不覆盖`loop/orderby.php`、不使用JavaScript。
- [x] C1变更前Local真实Shop确认：0.3.2单次加载；H1约41.89px/300字重，商品H2为16px/400；两个商品按钮为浅灰背景、无圆角、无最小高度声明；上下两个排序`select`均为14px、无圆角/内边距/最小高度，只有`aria-label="Shop order"`而无可见标签或`id`，重复ID为0。Home与Shop在`lang="en-US"`下仍有中文，已建立按D37/D43/D68/D81关闭的英语前台清单；Home已于D37改为明确TEST英文Hero，正式英语业务文案仍待内容验收。
- [x] C1没有修改运行代码。变更前`style.css`仍为0.3.2、4413字节、SHA-256 `3FBFBBFA50704F8B060AAF381F9560BC0615F5A366E11C31B5BE51D3AFE82EF7`；`storefront-hooks.php` SHA-256 `2344863E561B9014EA0BB898576A7C8B6B2E9E58DAC9FC26CB29D3C22EABF78A`。C1时Chrome商品页补采连接超时且未产生站点写入，当时将其记录为C2编码前前置项；随后已在C2编码前补齐，见下方证据。
- [x] C1期间工作区外部新增`a0e2520`、`407cbb0`、`23b11b5`三个提交；结束复查时上述两个运行文件均与当前HEAD一致，内容和哈希未变。当前主Agent没有创建、暂存、回退或重写这些提交，后续在当前HEAD上继续并保留其余用户差异。
- [x] 用户随后明确回复“开始c2吧”。C2编码前已补录Simple商品数量标签/可购买按钮，以及Variable商品Size、Shade标签、3个Variation与未选规格按钮状态；没有改动价格、库存、合法组合或加购逻辑。
- [x] C2只修改现有`inc/storefront-hooks.php`：在`after_setup_theme`优先级30等待父主题回调注册完成，再将Shop上下两处排序回调替换为调用`woocommerce_catalog_ordering( array( 'useLabel' => true ) )`的包装函数；WooCommerce函数不存在时安全返回。没有模板覆盖、JavaScript、CSS、插件、依赖或持久化行为。
- [x] C2定向Local验收通过：默认Shop上下2个GET表单均显示可见`Sort by`，`for/id`分别唯一关联且重复ID为0；`orderby`、`paged=1`及6个排序值保持。`/shop/?orderby=price-desc`两处值与商品高价到低价顺序正确；1920px页面日志0条warn/error。PHP 8.2.9语法与`git diff --check`通过；独立只读Code Review对加载时序、两处Hook、`useLabel`、函数缺失保护和唯一ID审查为P0/P1/P2/P3均0。完整四端/键盘、实际停用WooCommerce、归档变体、PHP日志和缓存命中页验收仍留C6。
- [x] 用户随后明确回复“开始C3吧”。C3仅修改子主题`style.css`并提升至0.4.0：在`.site-main`内以低权重`:where()`建立H1～H6标题颜色、行高、字重、字号和长文本换行；正文普通文本链接限定于内容/描述区域的无类名、无ID链接，保留下划线与正常、Hover、Active颜色。没有进入按钮、表单、Focus、Header/Footer、商品卡最终视觉、模板或JavaScript。
- [x] C3真实Home、Shop与Simple商品页1440px定向回归通过：Shop H1为48px/700字重，Classic商品卡仍为16px/400字重；商品页区段H2保留WooCommerce组件字号，Related商品卡不变；`.product_meta`普通链接应用操作蓝和下划线，Header、排序与购买按钮未被改写。无横向溢出，最终页面warn/error为0。
- [x] C3独立测试初次发现正文标题直接子链接仍受Storefront `h1 a`～`h6 a`的300字重/紫色影响，记为P2；已增加仅匹配标题直接子链接的继承规则。复测中Home实际1440px标题容器/链接同为32px/700/`#031a3a`，Shop实际1920px的商品卡、按钮及Header/Footer排除项保持；独立Code Review确认P2关闭，最终P0/P1/P2/P3均为0。CSS花括号16/16、`!important`和行内`style=`均为0，`git diff --check`通过；Shop修复后1440px再确认、390/768/1024真实视口、Variable、长标题、H3～H6样本、Hover/Active、Woo Blocks和C5 Focus集成继续留C6。
- [x] 用户授权C4～C7连续完成。C4在现有`style.css`中建立内容区Classic/Blocks按钮Normal、Hover、Active、Disabled与`aria-disabled`基线，并复用WooCommerce/Storefront原生Loading反馈；最小高度44px、10px圆角及1px实线边框。Cart空态Block推荐按钮的Storefront高权重灰底/无边框规则已由真实作用域兼容分支稳定覆盖。
- [x] C5建立常用文本控件、`select`、`textarea`、checkbox/radio、Disabled、Readonly、Placeholder和已有Error展示；只在`.form-row`内全宽，Shop排序保持约147×44px。新增`#b42318`错误Token；Focus在内容/普通Footer使用深蓝，在Header和深色手机固定底栏使用白色，均为3px outline/3px offset。
- [x] C6代表页面回归：Home、Shop、Simple、Variable、My Account均完成390/768/1024/1440；Cart空态完成390/1024/1440，768单页因浏览器自动化连接超时保留为孤立证据缺口。其余已完成23组均无横向溢出、重复ID或站点warn/error；不通过写数据制造非空Cart、Account表单、Readonly、Error或Loading业务状态。
- [x] 390px真实键盘夹具加载Storefront与DentAll 0.5.0：Header链接/搜索和手机固定底栏为白色3px outline，正文链接/输入与普通Footer为深蓝3px outline，全部offset 3px。按钮与错误色对比度通过；PHP语法、CSS花括号39/39、`!important`/行内样式为0、`git diff --check`和公开0.5.0资源加载通过。
- [x] C7独立Review/Test发现并关闭Disabled-Hover P2、Error-Hover P2、Cart Block覆盖P2、Block边框P3及手机固定底栏Focus P1；首次收口P0/P1/P2/P3均为0。0.5.0的`style.css`为14604字节、547行，SHA-256 `1839259F241C9FD46F97846EE9C87CA8B225E97223AB9D9F02C985B874853721`。没有数据库、配置、URL/SEO、缓存策略、支付、物流或Staging/Production变更。
- [x] 用户随后明确回复“确认精简Day28 CSS”，授权同功能维护性重构。最终0.5.2继续只使用一个`style.css`，没有拆分运行文件、PostCSS、自定义选择器或新依赖；按钮状态共用声明，Block高权重分支独立，Focus目标清单通过继承变量从四份合为一份，字段Hover/Loading沿用平台反馈，Readonly保留最小可见区别。
- [x] 精简后`style.css`为13168字节、443行、33个花括号块（33/33配对），较0.5.0净少1436字节和104行；SHA-256为`C32B7EEA5D6B20FC5A2BA02547470DCCF8CB594EB7B57D313BC4C9B569F30A7D`。gzip模拟3753→3943字节，说明重复文本原本易压缩，本次收益是维护性而非可宣称的网络提速；仍只有一个CSS请求。
- [x] 最终HTTP复测Home、Shop、Cart、My Account、Simple、Variable均为200且各加载一次`style.css?ver=0.5.2`，运行时CSS与磁盘哈希一致；真实Simple数量框和`.button.alt`、390px Cart Block按钮计算样式通过。0.5.2的Readonly/Error/Loading没有自然业务状态，最新键盘Tab与Shop四端补测受浏览器连接超时影响未新增完整动态证据；0.5.0既有四端/键盘证据、0.5.2静态级联与独立Review共同保留，未把缺口写成通过，最终P0/P1/P2/P3均为0。

## D29三类卡片组件v1

- [x] 用户于2026-08-26明确确认D29按三类卡片v1实施：ProductCard使用真实WooCommerce TEST输出，CategoryCard与SolutionCard使用非持久化TEST夹具，真实数据接入留D39/D40，范围仅Local；随后明确开始C1、C2，并最终授权C3～C7一起执行、纯视觉问题接Day30共同微调。该授权不覆盖持久化分类/Page、Staging/Production、模板覆盖、插件/依赖或交易逻辑。
- [x] C1冻结职责：D29只完成卡片内部展示契约；D39负责真实`product_cat`查询/顺序/空态，D40负责真实Page字段映射/顺序/空态，D44负责Shop商品网格列数和1～多商品布局。
- [x] ProductCard继续复用WooCommerce 11.0.0原生`content-product.php`及Hook顺序，不复制模板。价格、促销、评分、占位图和购买动作均以WooCommerce输出为权威；不加入无数据源的副标题、品牌、虚拟评分/评论数或图标型购买动作。
- [x] CategoryCard夹具复刻原生`content-product-cat.php`的`li.product-category > a > img + h2`结构；SolutionCard冻结`.dentall-solution-card`单链接结构，CTA为同一链接内视觉文本，不嵌套第二链接。两者TEST内容只进入独立子目录`project-docs/tests/fixtures/day29-cards/`候选，避开既存Day25 CSV夹具，不注册WordPress路由或写数据库。
- [x] C1登录态真实Shop确认现有2个商品完整覆盖Simple特价`del/ins + Add to cart`和Variable价格区间`Select options`；两者均有324×324缩略图、无评分。匿名Shop受Coming Soon保护，不能作为商品卡DOM证据。四端批量读取发生浏览器连接超时且未写站，临时视口已恢复；实现后390/768/1024/1440动态回归仍留C6/C7。
- [x] C1没有修改运行代码。子主题仍为0.5.2；`style.css` SHA-256为`C32B7EEA5D6B20FC5A2BA02547470DCCF8CB594EB7B57D313BC4C9B569F30A7D`，`storefront-hooks.php`为`9D4F3CF115AAC42709C90071C5153438BC9FB24C81564F66956148CC2B0F4FD5`。目标主题目录相对Git干净，其他用户既存差异未被处理。
- [x] C2只修改现有`themes/dentall/style.css`并将缓存版本提升至0.6.0；ProductCard规则作用于原生商品循环且明确排除`.product-category`。没有PHP、模板覆盖、JavaScript、构建链、额外前台请求或持久化行为。
- [x] 用户提出维护性反馈后完成减法审查与独立Review：首轮草稿的123行/13选择器/80声明最终收敛为相对C1净增71行（含注释和空行）、8选择器/43声明/0个`!important`。`AGENTS.md`已将“简洁、易读、可维护、方便微调”升级为编码前复用检查、实现后减法审查和量化报告的项目验收项。
- [x] 新增`project-docs/tests/fixtures/day29-cards/`静态骨架，只包含ProductCard的Simple特价、Variable长标题、缺图/不可购买和Loading展示状态；0个行内样式、脚本和事件，8个ID无重复，9个本地资源无缺失，不被WordPress正常页面加载。
- [x] C2阶段Local HTTP确认Shop与0.6.0 CSS均为200且Shop引用新缓存键；登录态真实Shop确认2张商品卡、Simple `Add to cart`、Variable `Select options`、关键计算样式和0横向溢出。该阶段的四端缺口已在后续C6补齐；静态夹具视觉与键盘Focus按最终C7结论接Day30，不写成D29已通过。
- [x] 独立Code Review初检的1个P2（夹具缺Storefront图标字体）与1个P3（标题重复3条全局基线声明）均已修复并复测；最终P0/P1/P2/P3均为0。剩余风险为Local uploads可移植性、AJAX成功后`.added_to_cart`并存布局和未来Woo Blocks契约，分别留夹具资产治理、C5～C7与采用Blocks时处理。
- [x] C3完成CategoryCard内部规则与4种TEST状态；C4完成SolutionCard合法单链接结构与4种TEST状态；C5把Product补为5种状态并完成减法审查。最终Category为5规则块/15声明，Solution为12规则块/32声明，合计17规则块/47声明；生产CSS没有新增断点，测试网格只存在于夹具。
- [x] C6静态审计确认19个ID无重复、ARIA/Hash目标完整、19个锚点平衡、12张图片alt/尺寸有效、17个本地资源存在、0行内代码；主题58/58、夹具14/14花括号配对且均0个`!important`。真实Shop在390/768/1024/1440px均加载0.7.0、横向溢出0、动作44px高。
- [x] C7独立Review发现并关闭Solution正文非法`span > h3`语义P2；修复为`div`、统一DOM/视觉顺序并删除`order`，再复用父主题基线和统一长词换行。最终已发现P0/P1/P2为0；Category/Solution浏览器视觉、可见Focus、精确留白/比例及Shop页面级视觉密度转入D30周验收。
- [x] D29学习收尾已生成`Day29-原生循环与卡片展示契约`，与项目Day笔记、前置/后续学习笔记和学习索引建立双向链接；最终19-ID夹具已合入当前工作树。

## D30 Design System v1与系统状态

- [x] 用户明确授权完整执行总计划D30，范围仅C1～C2栅格/间距/系统状态、C3～C5 Loading/空数据/通知组件、C6～C7四端周验收和Design System v1交付；不覆盖数据模型、路由、插件、依赖、持久化、SEO配置、Staging/Production或D29卡片内部重写。
- [x] `.dentall-section`复用D27既有Token，390/768/1024/1440上下间距为32/40/40/64px；显式`.dentall-grid`使用`auto-fill/minmax()`形成1/2/3/4列和16/24/24/24px gap，不接管`ul.products`，D44继续拥有Shop商品网格职责。
- [x] `.dentall-system-state`覆盖可见Loading与Empty壳；Spinner具备32×32px块盒、正常旋转、`aria-hidden`和Reduced Motion静止降级，状态输出者仍负责真实`role/aria-live/aria-busy`及生命周期。
- [x] WooCommerce原生无商品状态按真实`.woocommerce-no-products-found > .woocommerce-info`两层DOM处理；Classic Info/Success/Error与Blocks Info/Success/Warning/Error、可关闭按钮均使用真实结构和核心CSS验证，D30只统一视觉，不复制模板或改变业务角色。
- [x] WooCommerce 11 React `NoticeBanner`视觉容器按本地源码保持无`role`，真实关闭按钮类包含`wp-element-button contained`；Success/Warning/Info由`wp.a11y.speak`以`polite`播报、Error为`assertive`。静态夹具只证明视觉DOM与级联，动态播报生命周期留真实Blocks流程回归。
- [x] 非持久化夹具位于`project-docs/tests/fixtures/day30-design-system/`，只请求测试样式资产，不调用业务/API端点、不写数据；行内Style/Script/事件均为0、重复ID组0，并显式保留Grid列表语义。
- [x] 浏览器四端跟踪12个状态/通知元素，溢出失败均为0、页面横向溢出0；Classic图标预留56px、Blocks核心padding 16px、关闭按钮Focus、长文本、44px动作、控制台和状态色对比均通过。
- [x] 独立Review发现并关闭3个P1：Classic图标预留被选择器权重压住、Spinner缺块盒、Classic Hover颜色/透明度不满足AA；同时关闭真实空态结构、Blocks四态/关闭按钮、列表语义、Reduced Motion及夹具说明等P2/P3，最终D30隔离范围P0/P1/P2/P3均为0。
- [x] D30隔离阶段相对当时D29 C2记录基线为CSS净增243行、6587字节；该数字只作为历史候选快照。最终0.8.6为102/102对花括号、97个叶声明块、0个`!important`，没有新增运行文件、PHP/JavaScript函数、模板覆盖或依赖。
- [x] D29/D30已受控合并：保留三类卡片与D30独立状态区段，统一语义Token和0.8.6缓存键；Worktree与Local换行归一化后SHA-256均为`420F3762472A3D1A582D3273AC5586E27A0AAF5DD5B6DDCE3DA2DD593906096B`，Local/HTTP CSS为25943字节且逐字节一致。
- [x] 真实页面DevTools排错关闭Shop排序/结果数、Cart数量/删除重叠、Header/Checkout紫色Focus、Checkout Country/State错位及商品Tabs/默认链接色；前四类有开发者四端复验反馈，商品Tabs/默认链接有最终真实页面关闭反馈。最终W5前端设计系统周验收通过，Design System v1完成，测试范围P0/P1/P2/P3为0。
- [x] 验收边界保持：D39/D40真实数据接入、D44最终Shop网格、星级/筛选/支付及Checkout特殊/交易状态继续由对应工作日负责；本次没有部署Staging/Production，也不宣称性能提升或零影响。

## D31 PC公告栏与主页头结构

- [x] 实施授权可追溯：用户确认“按D31最小范围实施，公告栏仅Local使用`[TEST]`占位，正式Logo/公告后补”；确认`style.css`保留基础层、`site-shell.css`承载D31～D36；随后明确要求牙齿外围深蓝、Logo文字更大、全站蓝色纠偏、PC左三公告/右币种语言Help，并只占据WPML/WCML未来位置而不安装或实现插件。
- [x] `inc/setup.php`新增`dentall_enqueue_site_shell_styles()`，以`dentall-site-shell`依赖`storefront-child-style`；`style.css`仍负责Token/基础层，`site-shell.css`只负责Header/Navigation/Footer同生命周期壳层。拆分依据是职责、依赖、复用和变更频率，不按行数、设备或Day机械拆分。
- [x] `inc/storefront-hooks.php`新增Local公告、Local占位品牌、WooCommerce Account入口和Header编排共4个函数；连同enqueue共新增5函数。公告挂`storefront_before_header`；品牌替换原20回调；Account使用`wc_get_page_permalink( 'myaccount', '' )`；完整Storefront Header Cart由60重排到40并保留WooCommerce原生fragment Filter。
- [x] Local公告左侧三条均带`[TEST]`；右侧三个`li`只占位：币种读取WooCommerce当前`USD $`，`English`与`Help Center`为静态文本。三者均无链接、按钮或切换逻辑；WPML/WCML未安装、未启用，Help目标未建立。后续插件只复用信息架构与CSS位置，仍须单独实现和验收URL、SEO、缓存、金额与键盘状态。
- [x] Logo使用内置图像生成模式按“牙齿外围深蓝、文字更大”的要求生成候选，经纯色背景移除、透明处理和裁切后得到1024×240、144221字节、32位ARGB PNG，四角Alpha均为0。只在Local且没有Custom Logo时输出；非Local或已有Custom Logo立即回到`storefront_site_branding()`。该图不是正式商标或授权资产。
- [x] 全局动作蓝按真实Header与设计稿对照从历史`#0b63d8/#0756c9`纠偏为`#003a9f/#002f82`，主题版本升至0.9.0。新Normal/Hover对白字为9.97:1/12.15:1，在三种项目浅色表面的最低值为8.91:1/10.85:1；20格代表页面的动作按钮计算色均为`#003a9f`。D27/D28/D30旧值和哈希保留为历史快照。
- [x] 公告四端实际行为：390/768只显示第一条；1024显示三条＋币种；1440显示三条＋币种/语言/Help，Home四端均无横向溢出。Header使用一套DOM：`<768`只显示品牌；`>=768`品牌/Account/Cart首行、搜索次行；`>=1200`品牌/搜索/Account/Cart单行。
- [x] 登录态Home`/`、Shop`/shop/`、Simple`/product/test-d12-simple-fixed-pack/`、Cart`/cart/`、Account`/my-account/`×390/768/1024/1440共20格无意外横向溢出或重复ID；390下Header三动作隐藏，Storefront Handheld Footer仅在父主题实际输出的适用页面保留唯一入口，`>=768`动作可见且Handheld隐藏。
- [x] 0.9.0真实键盘与Console已补证：1440 Tab为Search input→Search button→Account→Cart，关键动作均为`#002f82 solid 3px`、offset 3px且未裁切；390按`Built with WooCommerce`→`My Account`→Search入口，Enter展开后再Tab入input→submit→Cart，其中My Account/Search触发器/Cart有3px可见焦点，展开后input未裁切。不先Enter直接Tab会进入未展开input且下缘越界；submit虽在DOM中可达、有可访问名称，却只有1×1px、可见Focus不足。两项转D33 P2。站点Console error/warning为空；工具侧Statsig超时不属于页面错误。
- [x] Cart Blocks 390例外已确认根因：Storefront `storefront_handheld_footer_bar()`检测到`woocommerce_blocks_enqueue_cart_block_scripts_after`或Checkout Block相关Action已发生时直接`return`，不是D31 CSS隐藏；该页当前无移动动作入口，转D33优先关闭，不能写成所有页面均有Handheld Footer。
- [x] 静态/HTTP资源证据：PHP语法通过；`site-shell.css`经`.NET ReadAllLines()`与LF分隔符统计均为303个物理行（PowerShell `Get-Content`不返回末尾空记录，显示301条）、7334字节、43/43对花括号、0个`!important`、gzip模拟2069字节、SHA-256=`656330EEC3979A8D07A2BB6D9BAB0CF8AEEA25DF8518D415754B6BAE8768E2FF`；PNG/SVG为144221/253/320字节；两个CSS与三张图片经Local HTTP均为200且长度与磁盘一致；`git diff --check`退出0。
- [x] 性能/数据边界：PC最多新增1个CSS＋3个图片请求；没有新JS、字体、模板覆盖、插件、自定义SQL、远程请求、Cron、数据库写入或新增autoload Option。代码会读取主题版本、Custom Logo、币种和WooCommerce页面绑定等既有API/Option，未做Query Monitor/前后性能基线，因此不把查询影响写成0，也不宣称性能“零影响”或提速。
- [x] D31三项P2已由D33关闭：新Header不再依赖折叠Handheld Search，48px商品搜索输入/按钮直接可达，Cart Block页也有Header Account/Cart入口；经典fragment、动态数量和旧缓存迁移已补证。`48rem`/固定768px边界转D34；搜索实际提交/结果、Cart Blocks同页数量桥接、非空Mini Cart/长金额、非Local分支与正式Custom Logo分别留D47、D69及素材/部署节点。当前占位Logo仍是Local资产，正式替换时重新校准并接D98/上线前资源优化。

## D32 PC主导航与一级下拉

- [x] 用户明确授权“按上述最小范围实施，指导我在Local创建和绑定`TEST D32 PC Navigation`”；用户通过`外观 → 菜单`完成保存，Primary绑定term ID 25，Secondary与Handheld未绑定。13项形成9个顶级项、`Shop by Categories`下3个原生产品分类及`About Us`下1个TEST Page。
- [x] D32只修改既有`site-shell.css`与`style.css`版本：PC导航在`>=75rem`（当前1200px）显示，390/768/1024/1199隐藏；没有新增PHP、JavaScript、模板覆盖、插件、依赖、构建链或运行文件。
- [x] 用户按设计稿纠正“分类按钮上下有空隙、导航没有分隔线”；最终Header和导航上下边线计算值均为0，导航行64px、分类按钮48px，上下各8px白色留白。分类按钮为`#003a9f`、白字、10px圆角，并保留三横图标和真实子项箭头。
- [x] Storefront原生`wp_nav_menu()`、导航DOM与`navigation.js`的`.focus`机制继续作为真相源；D32 CSS同时响应`:hover`、`.focus`、`:focus-within`。分类下拉向右展开，其余下拉向左展开；没有以`nth-last-child`绑定后台可编辑顺序。
- [x] 1440真实键盘链通过：分类父项→3个分类子项→Products离开关闭，Shift+Tab可重开；About父项→TEST子项→Contact离开关闭，子项有3px可见Focus。分类/About下拉均240px并位于容器内。
- [x] 390/768/1024/1199/1200/1440边界无横向溢出；Home、Shop、Simple、Cart、My Account五个登录态1440页面保留9顶级项/3＋1子项、D31 Header及无重复ID，站点Console error/warning为空。
- [x] 独立Code Review初审发现小屏Skip Link、空菜单空带、长文本和脆弱下拉方向共3个P2/1个P3，均修复；复审无新P0/P1/P2。独立设计复核确认无分隔线、8px留白、容器对齐与下拉边界，P0～P3为0。独立测试在数据库、静态、HTTP及首页390/768/1024/1200/1440范围内未发现P0/P1/P2；其1199、真实键盘链和其余四页登录态批量检查因工具超时没有独立证据，不作为单独签字，缺口由主执行链对应浏览器证据覆盖。
- [x] 静态/HTTP证据：`site-shell.css`为464行、12261字节、65/65对花括号、0个`!important`，SHA-256=`9B23EEF3DA081058511E4BA7545947D1F1C16D54CB006EF1A0BADC6B9B124576`；HTTP `?ver=0.10.0`返回200且与磁盘一致；`git diff --check`通过。
- [x] 减法边界：相对D31运行文件净增0，CSS净增161行/4927字节/22个规则块，版本号1处替换；已删除分隔线、位置序号判断和重复TEST URL造成的伪当前态，不预写手机抽屉、Mega Menu、正式路由或Escape专用JS。
- [x] D33已在同一Primary DOM上启用手机导航并恢复小屏Skip Link；真实触屏和完整键盘开合链按P2证据计划转D34。当前多个TEST自定义链接重复指向`/shop/`或`/`，只验证骨架，不冻结正式URL、当前项或SEO内部链接；若未来允许分类项离开首位，仍需重新验证其左右边界。Staging/Production未部署。

## D33 手机与平板竖屏Header

- [x] 用户明确授权“单一Primary DOM、非模态展开面板、一级子项常显、不新增JS、仅Local”，并按设计稿追加手机/平板竖屏Logo居中、Account/Cart靠右、Cart显示商品数量、放大镜恢复尺寸及所有端只保留`Search products…`视觉提示。
- [x] `storefront_header`中的Primary包装器/导航/关闭包装器调整为10/11/12；同一DOM在手机/768作为左侧Menu和非模态面板，在`>=1200px`恢复D32横向PC导航。运行时注销Handheld位置并移除Handheld Footer DOM与脚本，页面`#site-navigation=1`、Handheld导航/Footer=0。
- [x] 手机与768采用等宽两侧轨道；Logo链接与实际图片均显式居中。独立设计复核确认390图片中心195px、768图片中心384px，与各自视口中心偏差0；Account/Cart各44×44px、间距0，390/768右侧gutter分别20/32px，无碰撞或横溢出。
- [x] Search删除D31可见浮动Label规则，所有宽度只显示原生`Search products…`；语义`Search for:`仍1×1裁剪供辅助技术使用。390按钮48×48px，20px圆环＋10px斜柄完整；768以上恢复文字按钮。D31折叠Search越界、1×1 submit可见Focus及Cart Blocks页无移动动作入口由新Header结构关闭。
- [x] `dentall_cart_link()`从`WC()->cart->get_cart_contents_count()`输出动态徽标及国际化单复数`aria-label`；经典`woocommerce_add_to_cart_fragments`替换同一`a.cart-contents`。空车0、内存态1/12及fragment键通过且不写数据库；D31旧浏览器fragment会覆盖新DOM的问题通过固定`woocommerce_cart_fragment_name`后缀`_dentall_header_v1`关闭。
- [x] 非模态面板关闭态为`visibility:hidden/opacity:0/pointer-events:none`，展开后一级子项静态常显；没有遮罩、滚动锁、Dialog、焦点陷阱、多级手风琴或新JS。`prefers-reduced-motion`下取消过渡而不改变开闭状态。
- [x] 静态/HTTP证据：PHP lint与`git diff --check`通过；`site-shell.css`为770行、19620字节、102/102对花括号、0个`!important`，SHA-256=`8F2A2C6A80B5C0B26A7AA8CEF47413674D995ED570C6998CD6A057BEB6CA0585`；HTTP `?ver=0.11.10`字节与哈希一致，Handheld Footer脚本请求为0。
- [x] 独立设计复核关闭768实际图片偏左P1；Code Review关闭768～1199 Mini Cart 44px宽P1及Handheld误绑/死脚本P2，Reduced Motion和fragment缓存迁移另行复核；独立测试当前范围无P0/P1。Cart Blocks同页Store API改量不保证即时刷新经典Header，按用户“不新增JS”边界记录P3兼容债并转D69。
- [ ] P2证据移交：Chrome控制在“Enter展开→再次关闭→焦点留在Menu”时超时；Shop四宽已通过，但Home、Simple、Cart、Account未各自完成四宽批量浏览器回归。责任人为开发者/Codex，D34首先在390/768补完整键盘链，再用390/768/1024/1440批量补四类页面；真实物理触屏同时在D34验证。Staging/Production未部署。

## D34 平板横屏Header与断点收敛

- [x] 用户明确授权“1024～1199保留紧凑菜单，1200以上使用完整PC导航；仅Local、优先CSS、不新增JS/插件/模板”，并要求按四端导航、键盘/触控和五类页面回归三项验收。
- [x] `48rem～74.999rem`统一使用紧凑三列Header；`64rem`只增加公告密度；`75rem`才左对齐Logo、显示Account文字、隐藏Menu按钮并静态展开完整Primary导航。删除1024起提前隐藏导航及提前切换Logo/Account的冲突职责，没有新增DOM或脚本。
- [x] 390/768/1024/1199/1200/1366/1440均只有一个`#site-navigation`且无横向溢出；390～1199为44×44px紧凑Menu，1200～1440完整9项导航可见。390/768/1024/1199的Logo几何中心与视口中心一致，1024展开后的13个菜单链接最小高度44px。
- [x] 独立Code Review首轮发现2项P2并完成最小修复：子菜单默认`visibility:hidden/pointer-events:none`，仅在`<1200px`且`.toggled`时恢复；Reduced Motion同时覆盖外层面板、`ul.nav-menu`和Menu三条伪元素。复审P0/P1/P2/P3均为0。
- [x] 390/768/1024键盘Enter开→关得到`aria-expanded false→true→false`，焦点留在Menu；390关闭后Tab进入Logo、Shift+Tab返回Menu而不进入隐藏子菜单。390px Skip Link已实际聚焦并激活到`#site-navigation`；Chrome原生跳转后`activeElement`为`BODY`，同构夹具确认下一次Tab进入Menu。最新关闭态的两个子菜单均不可见、不可点击。
- [x] Reduced Motion浏览器夹具加载Storefront与DentAll真实CSS：普通偏好下外层面板、内层`ul.nav-menu`和三条Menu伪元素均有过渡；强制`reduce`后五个目标全部为`transition-property:none`和`duration:0s`。
- [x] 最新0.12.0跨页回归20/20通过：Home、Shop、Simple Product、Cart、My Account在390/768/1024/1440均为唯一导航、9个顶级项、正确紧凑/PC状态、Header/Search/Account/Cart存在且无横向溢出。
- [x] 静态/HTTP证据：`site-shell.css`为779行、20045字节、103/103对花括号、0个`!important`，SHA-256=`939573937FAB8F131EB7F414658D28C98536D456B789A528769121E24F5D90D1`；HTTP `?ver=0.12.0`与磁盘逐字节一致。子主题3个PHP文件通过`php -l`，`git diff --check`通过。
- [x] 减法审查：运行文件、PHP/JS函数、模板、插件、依赖和请求净增0；断点修复先删除重复职责，因关闭2项可访问性P2，最终CSS相对D33净增9行、425字节和1个规则块。没有性能前后测量，不宣称零影响。
- [x] 用户于2026-08-28明确确认D34实体设备验收通过，关闭真实手机/平板触摸、方向切换与系统级Reduced Motion观察缺口。未提供设备型号与浏览器版本，只记录人工验收结论，不补写不存在的设备细节；Staging/Production未部署。

## D35 PC页脚与Newsletter测试壳层

- [x] 用户明确授权“仅Local、一个两级Footer菜单、Newsletter只做明确不可提交的测试壳层、未确认社交和支付不渲染”；真实Newsletter、业务菜单内容、社交和支付仍不在本次实施范围。
- [x] 新增`inc/site-footer.php`，在`after_setup_theme`优先级30注册`footer`位置、移除Storefront默认四列Widget、挂载`storefront_before_footer`与`storefront_footer`回调，并关闭`Built with WooCommerce`推广链接；父主题`footer.php`保持未修改。
- [x] Newsletter只在Local输出，HTML为0个`form`、2个原生disabled控件且无`name`、submit、端点、AJAX/REST、远程请求或持久化。`[TEST]`与未连接状态文案可见；非Local分支直接返回。
- [x] Footer菜单使用单一`wp_nav_menu()`、`depth=2`和`fallback_cb=false`；当前位置未绑定，Local只显示诚实TEST空状态，非Local不显示测试提示。站点名、首页URL和属性按上下文转义；社交与支付0输出。
- [x] 1440首轮截图发现Storefront `.col-full` clearfix伪元素参与Newsletter Grid，以及Customiser Footer链接颜色压过品牌两项级联问题；分别在组件边界取消伪元素并提高受控选择器权重，0.13.2复验文案在左、控件在右、品牌白色且无常驻下划线。
- [x] 390、768、1024、1199、1200、1366、1440七个宽度均0横向溢出；1199保持单列，1200切换Newsletter/Footer双区。390下Home、Shop、Simple Product、Cart、My Account共5/5为Newsletter=1、Footer=1、表单=0、禁用控件=2、溢出=0。
- [x] PHP lint、0行内样式/脚本、0提交/远程/持久化扫描、133/133 CSS花括号、0个`!important`、`git diff --check`通过；HTTP与磁盘`site-shell.css`均24548字节且SHA-256一致。独立Code Review与视觉复核最终P0/P1/P2/P3均为0，视觉专项未独立覆盖全部宽度逐档截图或未绑定菜单的填充态；独立测试为P0=0、P1=0、P2=1、P3=0，唯一P2是匿名Coming Soon独立模板仍有默认社交链接，不是Day35 Footer输出。匿名HTTP只作为资源smoke，不替代登录态DOM证据。
- [ ] 环境P2：匿名Local的WooCommerce Coming Soon模板仍输出LinkedIn、Instagram、Facebook默认链接。责任人为开发者/Codex；需在单独获得页面配置授权后通过WooCommerce原生编辑/配置修正，最晚在匿名Staging或Production预发布页开放前复测。Day35 Footer本身保持社交/支付0输出。
- [x] 减法审查：运行文件净增1个、PHP函数净增3、CSS相对D34净增197行/4503字节/30个规则块；无JS、模板覆盖、插件、依赖、数据库字段、Option、查询、Cron或新资源请求。保留增量仅承担两个已授权组件及必要状态/断点，没有预实现D36折叠、社交、支付或真实Newsletter。

## D36 手机与平板页脚和后台菜单绑定

- [x] 用户明确授权“仅Local，由Administrator创建并绑定TEST Footer菜单，采用全部链接可见的静态重排，不做Accordion或新增JS”；正式栏目、真实Newsletter、社交、支付、Website Manager权限扩大和非Local部署不在范围。
- [x] Administrator通过`外观 → 菜单`创建term ID 26 `TEST D36 Footer Navigation`：5个顶级项、4个二级项、共9项；关闭自动添加页面，只绑定Footer。数据库只读核对为`primary => 25`、`footer => 26`，Primary未覆盖。
- [x] 子主题0.14.1在现有`site-shell.css`完成同一DOM静态重排：390单列，768四列且第5组换行，1024起五列，1200起菜单与240px品牌列并排；1199保持品牌在菜单下方。Newsletter从768起两列，仍为0表单/2禁用控件。
- [x] 当前项与祖先使用2px下划线；普通菜单链接无常驻下划线，hover恢复下划线。长标签继续通过`min-width:0`与`overflow-wrap:anywhere`换行；未新增隐藏链接、Accordion、Walker或JavaScript。
- [x] Home在390、768、1024、1199、1200、1440实测均为5个顶级项、4个子项、9个可见菜单链接和0横向溢出；390/768/1024/1440取得登录态截图。Shop、Simple Product、Cart、My Account的填充态、当前项/祖先和代表宽度回归已取证；独立矩阵结论在当日笔记登记，不把未执行格子冒充证据。
- [x] 独立测试实际完成13/20格：Home、Shop、Simple Product四宽及Cart 390全部通过，P0/P1/P2为0；Cart其余三宽与My Account四宽共7格未在独立会话执行。其0.14.0快照仅作为布局矩阵证据，最终0.14.1普通链接/当前项装饰由主验证链重新加载确认；完整口径见当日笔记。
- [x] 独立Code Review/设计复核P0/P1/P2为0；唯一P3是1200px断点的重复`align-items:center`，已删除并关闭。Reviewer未独立重做0.14.1最新截图、hover/focus、320px/200%缩放、未绑定菜单空状态或非Local验证，不扩大证据口径。
- [x] PHP lint、Newsletter静态边界、139/139 CSS花括号、0个`!important`、0.14.1资源版本和`git diff --check`通过。`site-shell.css`为1006行、25243字节、SHA-256 `ba7bb768cbddf1a1fc28256457c64eb8961feee1b37f6323687e0d678ef626e3`。
- [x] 减法审查：运行文件净增0、PHP函数净增0、JavaScript净增0；CSS相对D35净增30行/695字节/6个规则块，且4个Newsletter规则是从1200断点上移到768断点，没有复制第二套组件。无新模板、插件、依赖、查询、远程请求、Cron或资源文件。

## D37 PC首页Hero与原生首页路由

- [x] 用户明确授权“仅Local，原生Home/Blog＋Homepage模板，Hero使用核心区块＋特色图，允许现有3:2占位图制作Local优化副本”；不生成新页面/文案/图片，不新增ACF/CPT/插件/JavaScript，不修改冻结设计稿、Staging或Production。
- [x] 子主题0.15.3新增`inc/homepage.php`和条件加载的`assets/css/homepage.css`：在Storefront `homepage` Action上移除未验收的默认内容/商品区，推进主循环，输出核心区块与响应式特色图，并卸载已无用途的父主题Homepage脚本。
- [x] Administrator通过原生后台创建并配置Home（ID 102）、Blog、TEST核心区块与TEST特色图，并在`设置 → 阅读`保存Homepage=`Home`、Posts page=`Blog`；根URL实际输出Home Hero，`/blog/`实际输出文章归档，其他Reading选项未因本轮改变。
- [x] 现有冻结3:2源图保持不变；Local优化副本为1536×1024、WebP q84、34,244字节，源工作副本位于被Git忽略的`design-assets/runtime-local/day37/`，Administrator将其作为TEST特色图上传后由WordPress生成响应式派生图。正式透明前景素材仍待业务提供。
- [x] 1440/1024/768/390px真实浏览器回归通过：单H1、三项列表、按钮与图片结构正确，四端无横向溢出；1440px普通1×浏览器选择768×512、390px选择416×277是`srcset`正常资源选择，上传原件未裁剪或替换。
- [x] 无图时不输出空`figure`，无内容时退化为仅媒体，两者都空时不输出空Hero；独立Code Review、最终视觉复核与素材合同复核均无P0/P1。1024/768/390当前Hero偏高为D38 P2；含`<br>`/`&nbsp;`的伪空段落和实色图无法无缝融入CSS底色为已知边界。
- [x] PHP lint、`homepage.css` 28/28花括号、0个`!important`、0个`object-fit: cover`和`git diff --check`通过。D37运行代码净增2个职责文件、3个PHP函数、115行PHP与172行CSS；保留理由分别是Homepage生命周期/语义输出与页面专用布局，未引入模板覆盖、插件、依赖、查询、远程请求、Cron或交易逻辑。

## D38 手机与平板首页Hero精调

- [x] 用户明确授权“按Day38推荐范围实施，并采用390/768隐藏三项卖点，1024/1440显示”；该显示规则是DentAll本轮产品决定，不表述为行业规范。未扩展至D39～D41、图片生成、正式文案/素材、字段、数据库、Staging或Production。
- [x] 子主题0.16.0继续使用同一Homepage DOM：基础层以单一Grid区域叠放正文与媒体，`48rem`、`64rem`和`75rem`渐进调整内容/媒体比例，并在1024起恢复三列卖点。媒体保持`object-fit: contain`；缺图正文恢复100%宽，无正文媒体在各端居中，两者都空时不输出Hero。
- [x] `wp_get_attachment_image()`的`sizes`已同步56%移动/平板媒体宽与63%/768px桌面合同；1283～1319px窄区间可能轻微多取1024px候选，登记为Code Review P3，不影响布局、清晰度、SEO或当前验收。
- [x] 登录态Local浏览器实测390/768/1024/1440：卖点分别为隐藏/隐藏/三列/三列，Hero高度约320/320/333/352px，四端均`scrollWidth === clientWidth`；1440媒体越过Hero覆盖Newsletter以及768/1024缺图正文被半宽覆盖的问题均已修复并复验关闭。独立测试另补320/375无横向溢出。
- [x] Home实际加载`homepage.css?ver=0.16.0`且控制台0个warning/error；Shop与Blog均无Hero、无Homepage CSS。最终CSS HTTP 200、4826字节、38/38花括号、0个`!important`；Local PHP 8.2语法检查与`git diff --check`通过。
- [x] 独立设计复核P0/P1/P2/P3=0；独立测试P0/P1/P2/P3=0；Code Review P0/P1/P2=0、P3=1。有效附件记录但物理文件404、冷缓存`currentSrc`精确候选、正式透明素材和实体设备正式视觉未制造或未验，不外推为完成。
- [x] 减法审查：运行文件净增0，仅修改既有`homepage.css`、`homepage.php`和`style.css`；D37记录的172行为PowerShell 5.1默认ANSI口径，换算UTF-8物理行基线174行后，本轮CSS净增49行/1104字节/10个规则块，只承担叠层、断点、异常状态和桌面溢出修复。PHP函数、JavaScript、模板、插件、依赖、字段、查询、远程请求、Cron与新资源请求均净增0。

## D39 首页精选分类入口与自适应换行

- [x] 用户明确授权“按Day39推荐范围实施”，并在代码注册位置后把`TEST D39 Homepage Categories`绑定至`Homepage categories`；当前menu ID 27只有`TEST D12 Products`（term ID 18、parent 0、count 2、无分类图）。未新增正式分类、插件、字段、模板覆盖、JavaScript或非Local配置。
- [x] 子主题0.17.0用专用菜单选择和排序；代码只接受`taxonomy/product_cat`，去重ID，以一次批量`get_terms()`限制顶级、WordPress非空分类，再按WooCommerce前台可见count二次剔除0项，预检term link并恢复菜单顺序。自定义链接、子分类、重复项、访客空分类和失效链接不输出。
- [x] 分类区挂在Storefront `homepage`优先级20、紧跟Hero；复用`content-product_cat.php`、Woo原生缺图占位和链接`aria-label`，count只在Homepage局部隐藏。0项时整个section保持0输出。
- [x] 同一Flex DOM按实际总数自动换行：390/768/1024/1440容量为3/5/9/9，9项分别3＋3＋3、5＋4、9、9；10项为3＋3＋3＋1、5＋5、9＋1、9＋1，末行中心偏差最大0.008px，页面/列表溢出0。1/4/8/9/10项24/24、320/767/768/1023/1024边界、长标题、缺图、count隐藏和3px Focus通过。
- [x] Local实际输出1个section/1个分类li/占位图/count标记及正确`/product-category/test-d12-products/`链接；默认1905px登录态Chrome确认区块顺序、Flex、0横向溢出和`homepage.css?ver=0.17.0`。Chrome批量切换真实页四端时调试连接脱离，未冒充真实四端集成通过；四端多数量结论由加载同一Storefront/DentAll CSS与DOM的夹具证明，浏览器默认视口已恢复并清理验收标签页。
- [x] 独立Code Review发现的Woo最终可见count P1已修复并以正向1项/负向0项0字节关闭；Woo循环全局污染、10项`columns/first/last`和两处注释/夹具P3均已修复。最终Code Review与测试/设计复核P0/P1/P2/P3=0；PHP lint、46/46 CSS花括号、0个`!important`、唯一ID和`git diff --check`通过，仅保留既有Imagick启动警告。
- [x] 减法审查：运行文件净增0，PHP净增2函数/1菜单位置/1回调/152行，Homepage CSS净增49行/1393字节/8个规则块；新增1个92行非运行时夹具。无新模板、JS、插件、依赖、字段、Option、Cron、远程请求或持久化自动化；正式分类内容、图片授权和非Local部署仍待。

## D40 首页方案Page映射与四端区域

- [x] 用户明确授权“按推荐范围实施Day40；允许使用Local-only noindex TEST Page，未确认`/solutions/`前不显示`View all`”。范围限Page原生字段、专用菜单、既有D29 SolutionCard、Local TEST数据与验证；未扩展到CPT、ACF、插件、模板覆盖、JavaScript、D41或非Local配置。
- [x] 子主题0.18.0为Page启用核心Excerpt并注册`Homepage solutions`菜单位置；Storefront `homepage`优先级30在Hero 10、Categories 20之后输出Solutions。菜单只接受`post_type/page`并按对象ID去重，一次`get_posts()`批量限制已发布、无密码Page，再排除空标题/失效链接，过滤后最多取前4项。
- [x] 卡片始终重新读取Page标题、固定链接、原始手工Excerpt和特色图；菜单自定义标签/URL不能覆盖事实。空Excerpt不回退正文，缺图使用text-only，首个有效项自动featured，CTA统一为可翻译`Learn more`并带`aria-hidden`装饰箭头。0项在section前返回，未输出`View all`或`/solutions/`。
- [x] Local创建并绑定menu ID 28 `TEST D40 Homepage Solutions`，按106、108、110、112排序。四个已发布Page均明确标记Local TEST；三页有图、一页缺图，三页有Excerpt、一页空Excerpt，覆盖正常、长摘要、正文不回退、长标题和缺图状态。Website Manager可编辑/发布Page和上传媒体，但无`edit_theme_options`，菜单继续由Administrator维护。
- [x] 4个TEST Page均通过Yoast API设置noindex，Yoast presentation为`noindex`；`page-sitemap.xml` HTTP 200且四个Slug及`/solutions/`命中0。前台DOM为1 section/4 cards/4 links/4 h3/3 summaries/3 images/1 featured/1 text-only/4 CTA箭头；菜单别名和自定义URL负向测试均未进入输出。
- [x] 0项0字节、1项1张featured、4项真实顺序、混合草稿/临时密码/重复/自定义链接/5+项过滤后取前4均通过；临时状态和菜单过滤均精确恢复。PHP lint、55/55 CSS花括号、0个`!important`、静态夹具资源与`git diff --check`通过，仅保留既有Imagick启动警告。
- [x] 同一DOM按390/768/1024/1440采用1/1/2/4列合同，390隐藏摘要、768起显示；独立Code Review P0/P1/P2/P3=0，Design Review P0/P1/P2=0，Test Review未发现P0～P3功能缺陷。Chrome能列标签但页面快照/求值持续超时，真实首页四宽截图、Focus、卡高、16px gap、D39/D40纵向间距和正式素材裁切未形成实时证据，作为明确P3转D42，不冒充通过。
- [x] 减法审查：运行文件净增0；按D39记录基线，`homepage.php`净增162行/4629字节（Solutions函数区块160行＋菜单/回调注册各1行），保留2个同职责函数；`homepage.css`净增39行/1110字节/9个叶子规则块；新增1个75行/4263字节非运行时noindex夹具。JavaScript、模板、插件、依赖、CPT、ACF、自定义字段、Option、Cron、远程请求和交易逻辑净增0。

## D41 首页累计热卖与信任指标条

- [x] 用户明确授权按设计稿直接还原Trust数据与图标，后续有业务改动再调整。实施边界限Local：首页真实累计销量商品、既有ProductCard、设计稿五项Trust预览和四端CSS；没有测试订单、销量/商品/Option写入、插件、JavaScript、模板覆盖、CPT、ACF、Newsletter提交或非Local配置。
- [x] 子主题0.19.0在Storefront `homepage`优先级40/50依次增加Best Sellers与Trust。Best固定调用WooCommerce `popularity`排序，限定`product`、`publish`、无密码，排除目录隐藏并按Woo设置排除缺货，最终只保留`total_sales >= 1`且`is_visible()`的最多5个`WC_Product`；URL `orderby=price`和`rating_filter=5`不能注入。
- [x] 有结果时复用WooCommerce原生循环与D29 ProductCard，Shop总览链接只在已发布Shop Page且固定链接有效时输出；0项在section前返回。`$post`、`$product`、`woocommerce_loop`均在`finally`精确恢复，popularity `posts_clauses`回调也只在本函数新增时移除，预存popularity/price/rating Filter不被破坏。
- [x] Trust精确还原设计稿五项：`10,000+`、`100+`、`5,000+`、`99.5%`与`Secure Payments`及其英文说明；新增5个唯一symbol的1334字节SVG sprite。所有陈述均未被业务证明，`wp_get_environment_type()`非Local时数组和HTML保持0，不得迁移为正式营销或支付事实。
- [x] 纯CSS horizontal scroll/snap不新增JS：390/768/1024/1440的代表轨道分别完整容纳1/3/4/5张卡，页面级横向溢出0；390商品露出下一卡，768 Trust四项可见并保留第五项横向可达，1024/1440为五列信息带。初审2项视觉P2已关闭：Product→Trust局部间距收敛至16px；Trust列表有`tabindex=0`、可访问名称和全局3px Focus，ArrowRight后`scrollLeft=170`。
- [x] Local只读审计最终15/15 PASS：真实基线Best 0、读取期模拟2、Trust 5，覆盖正销量/上限、空输出、URL隔离、原生卡、已发布Shop真实href、三类全局恢复、预存排序Filter、SVG符号、Trust键盘入口与Hook顺序。未创建订单或更新销量；长标题、缺图和不可购买状态由静态Storefront/DentAll夹具覆盖，不能冒充真实查询集成。
- [x] 独立Woo Code Review二次复核P0/P1/P2/P3=0；独立Design Review确认两项P2关闭，最终P0/P1/P2=0并保留整页/真实卡语义P3；独立Test/Scope Review P0/P1/P2=0。真实订单驱动正销量、真实商品卡完整Tab/辅助文本、Trust→Newsletter整页邻接、非Local运行证据、正式Trust事实和无横滑宽度上的额外Tab停靠点均如实登记，不阻塞D41技术收尾。
- [x] 减法审查：相对D40，`homepage.php`净增289行/8918字节、4个同职责函数和2个Hook；`homepage.css`净增214行/5379字节/31个规则块；`style.css`只升版本；新增23行/1334字节运行时SVG、104行/8598字节非运行时夹具及211行/7666字节Local只读审计。JavaScript、模板、插件、依赖、字段、Option、Cron、远程请求、订单/商品写入和构建链净增0。

## D42 首页全链路校准与M4 Local技术v1验收

- [x] 用户明确授权“按推荐范围实施Day42；不创建TEST订单；M4按Local技术v1验收”，并要求工作树分批提交。范围限登录态Local整页校准、只读测试、阻塞缺陷最小修复、文档与Git治理；没有订单/销量/商品/Option写入、Coming Soon关闭、非Local配置或正式内容发布。
- [x] 登录态真实Homepage按同一DOM完成390/768/1024/1440复核：390/768/1024为紧凑Header，1440为完整PC Header；Hero辅助卖点按小屏隐藏/1024起显示；当前1个分类、4个Solutions分别按1/1/2/4列呈现；真实0销量Best Sellers整区隐藏；Trust按纵向/4项加横滑/5列/5列渐进；Newsletter保持0表单/2禁用控件，Footer按1/4/5/5列合同呈现。
- [x] 四端几何分别为`clientWidth/scrollWidth=375/375、753/753、1009/1009、1440/1440`，页面级横向溢出0。390、768、1440保存登录态整页截图；1024保存顶部与覆盖Solutions/Trust/Newsletter/Footer的下半页分段证据。Admin Bar在全页拼接中的黑色接缝属于截图工具伪影，不是页面DOM。
- [x] 登录态Homepage Console warning/error为0；`style.css`、`site-shell.css`、`homepage.css`、Trust SVG、Logo PNG、User/Cart SVG共7项资源均HTTP 200且Content-Type正确。Local版本复核为WordPress 7.0.4、WooCommerce 11.0.0、Storefront 4.6.2、DentAll 0.19.0；主题5个PHP文件通过PHP 8.2.9 CLI lint。
- [x] D41 Local只读审计重跑15/15 PASS，真实Best基线0、读取期模拟2、Trust 5；未创建订单或更新销量。D34 Header键盘链与D41 Trust `ArrowRight`滚动证据复用同一运行文件基线；D42登录态DOM快照确认语义顺序未被破坏。精确生产查询时延、页面缓存与Core Web Vitals未在D42冒充通过，仍按D97/上线前测量。
- [x] 独立设计复核与测试复核最终未发现P0/P1或阻塞M4的P2；1024下半页证据补齐后关闭唯一M4证据P2。本轮未重跑四宽完整Tab链、768以上Solutions沿用公共24px Grid gap、无横滑宽度下Trust额外Tab停靠点与D38窄宽带图片轻微过取均为P3；正式素材、真实正销量/退款/同销量/缓存、正式Trust事实、匿名Coming Soon与非Local运行继续属于后续业务/部署门槛。
- [x] M4按Local技术v1通过：运行代码、运行资源、插件、模板、JS、字段、依赖、数据库、URL、SEO、缓存、支付、物流和部署净改动0，DentAll保持0.19.0。Day42只新增项目/学习笔记并更新状态、总档案、变更日志与索引；截图留在非仓库证据目录，不提交Git。

## D43 商品归档信息架构与PC骨架

- [x] 用户明确授权“按推荐范围实施Day43；Shop公开标题使用Products，URL保持/shop/”。范围限Local原生Shop/taxonomy归档骨架、标题写入、条件CSS、代表状态与四端验证；D44网格、D45排序细化、D46分页、D47搜索、D48正式分类内容、D49以后筛选和非Local部署未被提前吸收。
- [x] 原生Shop Page ID 7公开标题已从`商店`改为`Products`，未传`post_name`；复核仍为已发布Page、slug `shop`、URL和Canonical `http://dentall.local/shop/`。H1、浏览器Title与面包屑公开文字相应变为英语，商品、分类、订单、价格、库存、销量、菜单和其他Option均未修改。
- [x] DentAll子主题升至0.20.0。`inc/setup.php`新增一个条件enqueue函数：WooCommerce条件函数缺失时安全退出，商品搜索由`is_search()`显式排除，只在Shop或商品taxonomy加载依赖`dentall-site-shell`的`catalog.css`；没有自定义主查询、`pre_get_posts`、模板覆盖、JavaScript、插件、字段、依赖、Cron或远程请求。
- [x] 新增55行/1499字节`catalog.css`，只管理原生Archive Header和排序/列表上下节奏；同一DOM按Mobile First在390/768/1024/1440形成160/192/192/224px标题区。最终选择器使用稳定的`body.woocommerce`作用域，不依赖Storefront侧栏布局类；减法审查删除一条父主题已有的商品列表margin规则，最终0个`!important`。
- [x] `/shop/`四端均为H1 `Products`、2件商品、原生3列循环、上下两组排序/结果信息且页面级横向溢出0；`price-desc`排序回归、分类正常态、0商品分类原生`role="status"`空态、CSS HTTP 200、Console warning/error 0均通过。商品搜索仍返回2件商品并保持Yoast `noindex, follow`，但不加载D43样式；Woo配置仍为3列×4行，即12项/页合同未改变。
- [x] 独立Code Review初审的1项P2（选择器依赖全宽布局类）与主Agent发现的搜索条件泄漏均已最小修复；Code、Design与Test/Scope最终均为P0/P1/P2/P3=0，未发现范围越界，未授权D25未跟踪文件保持未触碰。Test/Scope按收口指令停止新增取证，空分类最终DOM、搜索资源、`price-desc`与Console由主验收/Design证据承担；正式素材/内容、侧栏Widget运行态、12项满页、缓存/CDN、CWV、匿名Coming Soon和非Local仍待验。
- [x] 运行文件净变化为修改2个既有文件、新增1个CSS，净增84行、1个函数、8个叶子规则块和2个媒体查询；Page标题是独立Local数据写入。两次Windows引号错误的只读`wp eval`在被Git忽略的`debug.log`追加parse/fatal诊断记录，未改数据库或源码，本轮为保留审计事实未擅自清理。Staging/Production部署时须同时同步主题文件与Shop标题，并重新验证URL/SEO、缓存和代表状态。

## D44 商品网格响应式

- [x] 用户明确授权“确认按推荐范围实施Day44：Local CSS-only，四端2/2/3/4列，间距16/24/24/24px，每页保持12项，1024按3列推导，不生成补充图”。范围限Local既有归档CSS与缓存版本；未授权D45～D49、补图、数据写入或非Local部署。
- [x] DentAll子主题升至0.21.0。`catalog.css`在D43的55行/1499字节基线上净增36行/924字节，最终91行/2423字节；用一个Mobile First Grid、最小伪元素/浮动/宽度/margin复位和48/64/75rem断点形成2/2/3/4列与16/24/24/24px gap。新增6个叶子规则块、1个媒体查询，0个新运行文件、函数、查询、模板、JS、插件或数据写入。
- [x] 登录态真实Shop动态实测：390为159.5px×2/16px，768为332.5px×2/24px，1024为299px×3/24px，1440为296px×4/24px；真实2项始终占前两格、同排左对齐，不拉伸空列。四端及320附加边界均满足`scrollWidth===clientWidth`，页面实际加载`catalog.css?ver=0.21.0`且Computed生效。
- [x] 正常`/product-category/test-d12-products/`复用Grid；空`/product-category/test/`无商品列表、排序或结果计数并保留原生`role="status"`；商品搜索未加载`catalog.css`，仍为block列表。Shop/taxonomy URL、Title、Canonical、robots、Schema、Sitemap与数据均未改。
- [x] 独立只读WP-CLI确认`woocommerce_catalog_columns=3`、`woocommerce_catalog_rows=4`，WooCommerce 11.0.0仍按3×4得到每页12项；主题/插件未发现`loop_shop_columns`、`loop_shop_per_page`或`pre_get_posts`归档覆盖。视觉4列没有把查询改为16项。
- [x] Code、Design与Test最终P0/P1/P2/P3均为0。设计证据支持手机2列、平板竖屏2列和PC 4列；1024三列只按用户授权推导。真实1/5/12项、D29长标题/缺图/售罄/不可购买/loading/added与Grid动态整合、最终Console与CSS独立HTTP读取、生产缓存/CWV及非Local仍未验证，不计为通过；浏览器拒绝内存夹具后未绕过安全策略或创建持久TEST数据。

## D45 商品排序与结果信息

- [x] 用户于2026-09-02明确授权“确认按推荐范围实施Day45”。推荐范围限Local Shop与商品taxonomy的一组顶部原生排序工具栏、响应式结果信息、URL/SEO与代表状态验证；不改每页12项、D44 Grid、主查询、分页、搜索样式、筛选、商品/订单数据或非Local环境。
- [x] DentAll子主题升至0.22.0。既有`dentall_enable_catalog_ordering_labels()`从`after_setup_theme`移到主查询完成后的`wp`：继续移除Storefront原生无标签排序并重挂WooCommerce `useLabel`输出；仅对非搜索Shop/taxonomy移除底部排序与结果数，底部分页Hook完整保留。动态验证发现早期条件会误收敛商品搜索后，增加`! is_search()`边界，搜索恢复D28既有上下两组输出。
- [x] `catalog.css`在D44的91行/2423字节基线上净增51行/1210字节，最终142行/3633字节；新增6个规则块、0个媒体查询，复用75rem断点。顶部排序使用Flex、唯一可见标签和原生select；320/390/768/1024把结果数视觉裁剪为1×1但保留DOM文本与`role="status"`，1440恢复结果数左、排序右；无分页时底部空wrapper用`:empty`隐藏。
- [x] 独立Chrome五宽验证：320/390/768/1024/1440均无横向溢出，select高度44px，Grid仍为2/2/3/4列；1440结果数尺寸约119.56×21px，左边界84.5px，排序左边界1139.8px。默认Shop为1组排序/1组结果、6个原生选项、唯一`label[for]`/`id`、隐藏`paged=1`、`catalog.css?ver=0.22.0`，底部空wrapper为`display:none`。
- [x] 只读动态合同覆盖默认、`price`、`price-desc`、非法`orderby`回退、`min_price/max_price`参数保留、正常与空taxonomy以及商品搜索隔离。当前Simple 24.99、Variable 39.99支持验证升价/降价顺序；排序参数页Canonical保持基础Shop/taxonomy URL，Sitemap没有`orderby=`，商品搜索仍为2组排序/结果且不加载`catalog.css`。没有修改Title、robots、Schema、商品、价格、库存、销量、订单或Option。
- [x] Code、Design、Test三路独立复核最终P0/P1/P2/P3均为0。PHP lint、CSS 23/23对花括号、0个`!important`、`git diff --check`通过；D45运行文件仅修改3个既有文件，0个新运行文件、函数、查询、模板、JavaScript、插件、依赖或数据写入。原生select实际Focus交互截图与Console最终读取因Chrome控制超时未取得，不冒充已验；既有44px/3px `focus-visible`源码合同、登录态DOM与键盘原生控件边界已由Code/Design复核，未形成P0/P1。

## D46 商品归档分页与URL归一化

- [x] 用户于2026-09-02明确授权“确认按推荐范围实施Day46，并授权在Local临时创建11个TEST商品，验收后移入回收站；不提交Git、不部署非Local”。范围限Shop/taxonomy原生分页、URL/SEO、四端与可逆夹具；不改每页12项、D44 Grid、D45排序查询、D47搜索样式、筛选、正式内容或非Local。
- [x] DentAll子主题升至0.23.0。D45既有`wp`回调仅多移除Storefront循环前优先级30的顶部分页，循环后保留唯一原生分页；新增`dentall_catalog_pagination_args()`只在非搜索Shop/taxonomy把`end_size/mid_size`收敛为1/2、补充可翻译Previous/Next，并把Woo传入的base/format交回WordPress默认算法。没有模板覆盖、第二查询、JavaScript、插件或路由。
- [x] `catalog.css`在D45的3633字节/142行基线上最终为5423字节/205行，净增1790字节/63行和7个规则块；分页使用Flex换行、至少44×44px目标、Token化current/hover/dots状态并复用全局3px Focus，0个新媒体查询、`!important`或硬编码Hex。PHP Hook文件由9959字节/303行增至11034字节/335行；运行文件净变化为修改3个既有文件、新增0个运行文件、1个函数。
- [x] WooCommerce CRUD在Local创建11个Simple夹具#120～#130，SKU `TEST-D46-PAGE-01`～`11`并标记`_dentall_test_fixture=day46-pagination`；与既有2项合计13项，真实形成Shop与`test-d12-products`分类的12/1两页。第一页结果1～12、第二页13～13、唯一底部分页、Page 2当前态、44px与五宽2/2/3/4列均通过；缺图和长标题没有造成横向溢出。
- [x] URL/SEO矩阵通过：Shop/taxonomy Page 2为200和自身Canonical，`rel=prev/next`正确；`orderby=price`随分页链接保留，切换排序回Page 1；手工`/page/1/`为301、有效Page 2为200、越界Page 3为真实404/`noindex`且无Canonical。初始Previous/Page 1生成`/page/1/`多一次301的P2，按独立Review建议`unset(base,format)`后实页直达归档根URL；商品搜索继续两组原生工具栏/分页、不加载`catalog.css`，空taxonomy无分页。
- [x] 320/390/768/1024/1440均无横向溢出；320三个分页项均44×44px，真实键盘Focus为3px深蓝外框＋3px偏移。合成总页数11的第1/6/11页窗口分别为`1 2 3 … 11`、中段与`… 9 10 11`，但没有把算法/几何证据冒充真实11页DOM或hover截图。Code、Design、Test/SEO终审均为P0/P1/P2=0。
- [x] 清理前一次性核对11项的ID、post type、publish状态、Simple类型、逐项SKU和fixture meta，11/11通过后调用WordPress回收站API；#120～#130当前均为Trash，发布商品恢复2项，term 18分类count恢复2，Shop/taxonomy均0分页且无fixture文本。未永久删除、未清空回收站、未提交Git或部署非Local；回收站未来被手工/自动清空后将不可恢复。

## D47 商品搜索结果与边界状态

- [x] 用户于2026-09-03明确授权“确认按推荐范围实施 Day47，仅限 Local，并授权临时恢复 #120～#130 测试商品，验收后重新移入回收站。”范围限明确商品搜索的原生结果结构、工具栏/分页、无效输入、空结果恢复、URL/SEO、四端和可逆夹具；不改搜索算法、正式内容、筛选、模板、JavaScript、插件、交易或非Local。
- [x] DentAll子主题升至0.24.0。`setup.php`只对非搜索Shop/taxonomy或严格的`is_search()`＋Product Archive＋`post_type=product`加载既有`catalog.css`；`storefront-hooks.php`让目标搜索复用D45顶部结果/排序和D46底部分页，并在Woo原生空通知外输出Shop/Home两个恢复链接。没有模板覆盖、第二查询、JavaScript、插件或新运行文件。
- [x] `template_redirect`优先级1只处理商品搜索的空值、Unicode纯空白、非标量和超过WordPress有效1600字节边界的关键词，以`wp_safe_redirect()` 302到Woo动态Shop URL；有效单结果仍由WordPress/WooCommerce原生302。独立Review发现801个撇号经WordPress加斜杠后变1602字节而主查询清空的P2，以及NBSP/U+3000和`sanitize_text_field()`误把非空特殊词判空的边界；最终同时检查加斜杠前后长度、直接判断原值Unicode空白，800/801撇号、ASCII 1600/1601、`%aa`与纯HTML词均按合同复验。
- [x] 恢复#120～#130后，`TEST`真实形成13项/12+1页；Page 1/2各一个H1、一个面包屑、一个排序表单和一个结果数，只有一组底部分页。`s`、`post_type=product`与`orderby=price`保持，Page 2返回Page 1直达根搜索URL；越界页为404。特殊字符无脚本注入或重复ID。
- [x] SEO与隔离通过：多/零结果、排序、有效后页和1600字节搜索均为`noindex, follow`，无Canonical/`rel`且不进Sitemap；普通`?s=TEST`不加载`catalog.css`、无Woo目录循环/工具栏/CTA。最终2项基线下，Shop和term 18分类仍加载目录CSS、各一个结果数/排序、0实际分页并保持自身Canonical。
- [x] 数据收尾通过：恢复前、重新回收前均逐项核对ID、`product`类型、状态、SKU和`_dentall_test_fixture=day46-pagination`；#120～#130当前11/11为Trash，发布商品2项、Trash商品11项、term 18 count=2。未永久删除、未清空回收站、未改订单、客户、价格、库存或销量。
- [x] 登录态真实有结果页390/768/1024/1440已完成四宽浏览器验收：Grid分别为2/2/3/4列，gap为16/24/24/24px，均无横向溢出；排序select为44px，H1可断长词，只有一组排序/结果信息且当前2项无实际分页。
- [x] 真实空结果页四端P2已在D48实施前关闭：390的两个335×44px CTA纵向堆叠，768/1024/1440的两个266×44px CTA并排；四端页面client/scroll相等、长词正常、DOM唯一，双链接真实键盘Focus为3px深蓝外框＋3px偏移，Console `[]`并保存截图。Day47现按Local授权范围完成。

## D48 商品分类内容与W8列表回归

- [x] 用户于2026-09-03明确授权“确认按推荐范围实施 Day48，仅限 Local；授权临时修改并恢复分类 #18 的测试标题、描述和 Yoast 元数据，并移除商品分类 Title/Social Title 模板中的 Archives；不恢复 #120～#130，不创建正式分类。”未扩展至正式分类内容、D49筛选、插件、字段、模板、JavaScript、查询、交易或非Local。
- [x] 写前保存#18的名称、slug、parent、count、空描述、两项Woo term meta、无Yoast term条目、菜单/Sitemap/Head和`wpseo_titles`基线。临时写入长TEST标题、两段描述、安全Shop链接、长token及Yoast内容级Title/Meta后，实际Head覆盖、Canonical自身、index/follow、Sitemap和菜单动态链接均通过。
- [x] 首次390px实页发现连续长token利用Grid item默认`min-width:auto`撑大内部轨道并裁切内容。DentAll升至0.25.0；`catalog.css`只给标题增加`min-width:0`，给term/page描述增加`min-width:0`与`overflow-wrap:anywhere`。修复后390/768/1024/1440均0横溢出，2/2/3/4列与16/24/24/24px gap不变，描述链接3px Focus通过。
- [x] 空分类#24四端保持有效200原生空态：1个H1、1个描述、1个`role=status`，0结果数/排序/Grid/分页/搜索CTA。最终2商品下Shop、正常分类、商品搜索、`price`/`price-desc`、Page 1归一化与当前Page 2 404回归通过；没有恢复#120～#130制造新多页证据，D46/D47真实12/1证据继续有效。
- [x] Yoast Local商品分类全局`title-tax-product_cat`改为`%%term_title%% %%page%% %%sep%% %%sitename%%`，`social-title-tax-product_cat`改为`%%term_title%%`；排除两键后的其他配置hash前后相同。#18恢复后Title/OG Title为`TEST D12 Products - Dentall`，无内容级Meta Description，Canonical/robots/URL不变。
- [x] #18最终名称、Slug、parent、count、空描述、两项term meta和无Yoast内容级条目逐项回到基线；Primary/Homepage菜单对象显示名随term恢复，URL不变。#120～#130本轮未恢复且11/11仍为Trash；发布商品2项、Trash 11项，没有新建正式分类或永久删除数据。
- [x] PHP lint、CSS 34/34花括号、0个`!important`、`git diff --check`通过。运行层只修改2个既有文件，净增3条必要CSS声明、0新规则块/文件/函数/查询/模板/JS/插件/依赖；配置审计与Code Review均无P0/P1/P2，Test/SEO为P0=0、P1=0、P2=1、P3=0。P2只指最终0.25.0恢复态Shop、有结果搜索与短内容#18尚未完整重跑四宽，不代表发现功能缺陷；负责人开发者/Codex，最晚D49实施前关闭。正式内容、非Local数据库配置重放、缓存/CDN和真实抓取仍是独立门槛。

## D49 商品筛选合同与属性查询表

- [x] 用户于2026-09-03明确授权：“确认按推荐范围实施 Day49，仅限 Local：先关闭 Day48 P2；冻结分类、价格、Size、Shade 的商品级筛选合同；允许重建并启用 WooCommerce 属性查询表，Direct Updates 开、Optimized Updates 关；属性归档继续关闭；不恢复 #120～#130，不修改商品和缺货设置，不做筛选 UI、品牌、评分、插件或非 Local 变更。”本轮严格按该边界执行。
- [x] 实施前用当前2个发布商品在DentAll 0.25.0下补跑Shop、有结果商品搜索和短内容分类#18的390/768/1024/1440登录态截图，共12张；H1、两卡左对齐、2/2/3/4列轨道、唯一工具栏和无可见横向裁切通过，页面Title分别为`Products - Dentall`、`You searched for TEST - Dentall`和`TEST D12 Products - Dentall`。分类页与筛选空态Console均为`[]`，Day48唯一证据P2关闭。
- [x] 冻结合同：分类用`/product-category/{slug}/`，价格用`min_price`/`max_price`，Size/Shade分别用`filter_size`/`filter_shade`和显式`query_type_*=or`；同属性多值OR，不同属性与价格AND，条件变化回第一页。商品搜索不在D49增加筛选入口；Package Quantity、品牌、评分及其他候选均不进入当前v1。
- [x] 接受WooCommerce原生父商品级属性语义：父商品拥有term即命中，不保证多个条件来自同一可售Variation。#46不存在`Large 105 mm + Medium`Variation但组合查询仍命中#46；严格同Variation匹配明确属于扩项。
- [x] `wp wc palt regenerate --force --from-scratch --disable-db-optimization --batch-size=10`完整重建派生表，仍为7行、2个父商品、最高父商品ID 46；随后启用表。最终`woocommerce_attribute_lookup_enabled=yes`、`direct_updates=yes`、`optimized_updates=no`，`hide_out_of_stock_items=no`。
- [x] 主查询11场景通过：Shop基础返回#44/#46；Size、Shade、同属性OR、跨属性、价格25～40、属性＋价格和分类Size均返回#46；无效Size term与反向价格区间返回0项。属性SQL实际引用`wc_product_attributes_lookup`，价格SQL引用`wc_product_meta_lookup`。
- [x] Woo CRUD与只读表审计确认#44/#46及Variations #51～#53的状态、价格、库存、属性与分类未变；#120～#130为11/11 Trash。Package Quantity、Size、Shade属性归档均为false，没有创建、修改或删除商品、term、品牌、评分、插件或前端资源。
- [x] 参数页SEO目标冻结为`noindex, follow`、Canonical回当前无参数基础归档且不进Sitemap；Local代表Size请求实测Canonical已为`/shop/`，robots仍为`index, follow`。D49按确认范围不实施UI/SEO代码；D50首次建立可点击筛选链接前必须补齐robots并验证参数缓存键。回滚为先`wp wc palt disable`，再把Direct Updates恢复`no`；查询表是可重建派生数据，Optimized继续`no`。
- [x] 运行代码净变化0文件、0函数、0CSS规则块、0查询封装、0模板、0JavaScript、0插件/依赖，DentAll保持0.25.0。数据库只修改3项Woo查询表相关状态中的2项实际值并重建派生表；没有改URL、Sitemap成员、支付、物流、订单、缓存配置或非Local环境。
- [x] 两路独立复核完成：Woo查询/SEO复核为P0=0、P1=0、P2=0、P3=1，唯一P3是参数页robots按范围转D50；配置/数据边界复核为P0～P3=0，并以SQL快照＋连续binlog确认D49窗口没有商品、价格、库存、term、属性定义或运行文件写入。Local服务随后停止，最终取证未为回读而重新启动服务；此前在线WP-CLI、浏览器和Woo CRUD证据仍有效。

## D50 PC商品筛选与参数页索引收口

- [x] 用户于2026-09-03明确授权：“确认按推荐范围实施 Day50，仅限 Local：先关闭筛选参数页 robots P2；允许新增 inc/catalog-filters.php 并最小修改现有加载、样式和 SEO 模块；仅在 >=1200px 为 Shop/商品分类启用 Categories、Price、Size、Shade 常驻侧栏；采用无 JS 链接式属性筛选和 Min/Max＋Apply，复用 Woo 主查询及 D49 合同；390/768/1024 只做无回归；不做品牌、评分、计数、Chips、专门 Reset、移动抽屉、插件、商品数据或非 Local 变更。”本轮严格按该边界执行。
- [x] 子主题新增职责独立的`inc/catalog-filters.php`并由`functions.php`加载；只在非搜索的Shop/商品分类打开侧栏＋结果布局。分类使用`wp_list_categories()`，Size/Shade使用`WC_Widget_Layered_Nav`，价格为GET Min/Max＋Apply；没有模板覆盖、JavaScript、第二商品查询、插件或依赖。
- [x] 集中白名单只传播有效非负十进制价格、存在的Size/Shade term、对应`query_type=or`和Woo六种排序。分类、属性、价格、排序及最终分页URL不复制`foo`、非法orderby、数组输入、孤立query type或旧分页；单选取消回基础归档，多选取消保留剩余有效状态。
- [x] 空值、非标量、科学计数法、负数、逗号或超长价格302到移除无效键后的白名单第一页，并返回`X-Redirect-By: DentAll`与no-store；合法Min大于Max不交换，主查询返回0项，表单输出`role=alert`及两项`aria-invalid=true`。
- [x] `dentall-core` SEO兼容模块新增晚优先级`wp_robots`规则：Shop/商品分类出现任意`min_price`、`max_price`、`filter_*`或`query_type_*`键即`noindex, follow`，包含非法term、Package Quantity直达及未来未知属性；Yoast基础归档Canonical保留。仅`foo`/合法排序仍index，商品搜索维持D47 noindex且无Canonical，D49 robots P2关闭。
- [x] 查询矩阵通过：基础#44/#46；Size、Shade、同属性OR、跨属性、价格与属性＋价格组合均按D49合同返回#46；无效term与反向区间0项；价格/属性SQL分别使用价格/属性lookup。排序升降价、分类筛选、空分类与搜索隔离通过。
- [x] 登录态Local在390/768/1024/1440分别保持2/2/3/4列；筛选侧栏在390/768/1024和1199隐藏且无可聚焦控件，在1200/1440显示为240px列，均0横溢出。44px目标、3px键盘Focus、可见label、错误ARIA、选中勾选/字重/`aria-current`与移除说明通过；页面Console无站点warning/error。
- [x] Woo CRUD/Option/lookup/Trash最终回读：发布商品仍[44,46]，#51～#53价格库存组合不变，#120～#130为11/11 Trash，三项属性归档false，PALT/direct/optimized=yes/yes/no，lookup 7行/父商品[44,46]，hide out of stock=no；本轮无商品、Variation、term或配置写入。
- [x] 运行层新增1个416行职责模块、1行加载、SEO 42行/1函数及约166行目录CSS；版本为DentAll 0.26.0、DentAll Core 0.2.7。最终减法已删除不可达非法价格表单分支、重复term校验、`!important`、重复勾选及无收益抽象；保留集中URL/状态恢复、Woo适配与可访问性边界。
- [x] 三路独立Code/Test/UX-SEO复核均为P0=0、P1=0、P2=0、P3=0。空分类不输出必然为0的Size/Shade、人工越界404先于价格302为已接受边界；正式12+商品筛选分页、Production缓存/CDN、正式长term、非Local抓取和移动抽屉未验。
- [x] 收尾补做WP-CLI只读回读时，文件系统可确认WordPress 7.0.4，但Local数据库端点已拒绝连接；未为重复取证擅自重启服务。D50完成结论使用停机前的Woo CRUD/Option/SQL、HTTP、浏览器与三路独立复核证据；该在线前置已在D51用登录态Local浏览器关闭。

## D51 手机与平板筛选抽屉

- [x] 用户于2026-09-03明确回复“确认按上述推荐范围，仅限 Local 实施 Day51。”本轮只增加小于1200px的Filter入口、原生dialog、关闭/焦点/滚动/生命周期和三端适配；D50唯一筛选DOM、Woo主查询、参数白名单及SEO合同继续复用。
- [x] `inc/catalog-filters.php`新增一次性Filter按钮，并在原有唯一aside中加入隐藏抽屉标题/Close及相邻空dialog壳。正常结果与Woo空结果虽使用两个Hook，静态渲染闸门保证一个请求最多只有一个入口；没有复制Categories、Price、Size、Shade。
- [x] `inc/setup.php`只在非搜索Shop/商品分类条件加载新`assets/js/catalog-filters.js`。脚本能力检测通过后才解除入口`hidden`；不支持原生dialog时不暴露失效按钮，核心页面与1200px起的CSS侧栏仍可使用。商品搜索实测Filter/dialog/aside/脚本均为0。
- [x] 小于1200px时把同一aside移入`dialog.showModal()`；1200px起只在aside确实位于dialog时移回其前方布局并恢复240px常驻栏。没有第二商品查询、AJAX、History改写、Storage、Cookie、远程请求、Cron、模板覆盖、插件或依赖。
- [x] 交互覆盖标题/错误字段初始焦点、Close、原生`cancel`与Escape专用`keydown`兼容兜底、遮罩按下/抬起安全关闭、焦点返回和`html/body`滚动锁；断点、方向、`pageshow/pagehide`均同步或清理状态。合法反向价格在窄屏自动打开并聚焦首个无效字段。
- [x] 登录态Local验证390/768/1024/1440与1199/1200：窄屏面板最大384px并可内部滚动，桌面恢复240px＋4列；所有宽度0横向溢出。打开后768→1440正确关闭/解锁/移回aside；844×390方向变化仍可操作且无动画。
- [x] Escape、Close和遮罩三条关闭路径均恢复`aria-expanded=false`、移除两处滚动锁并把焦点送回Filter；Tab/Shift+Tab没有逃出原生模态树。BFCache离页返回后dialog关闭、锁清除、入口和aside父节点恢复。
- [x] 反向价格、Size选中、空分类和商品搜索隔离通过：错误页自动打开并保留两项`aria-invalid`/`role=alert`；空分类只显示Categories/Price和Woo原生空态；D50白名单URL、参数页`noindex, follow`与基础`/shop/` Canonical保持。
- [x] PHP lint 2/2、Node语法、CSS 71/71花括号、0`!important`与`git diff --check`通过；0.27.0 CSS/JS分别HTTP 200，JS为4077字节。页面Console错误为0；PHPCS、真实移动设备、旧浏览器、正式12+目录、Production缓存/CDN/CWV和非Local抓取未验。
- [x] 按D51写前快照与UTF-8物理行口径复核，运行源码净增313行：筛选PHP +35、`setup.php`净+13、目录CSS +109并新增1个156行JS文件；新增1个PHP命名函数、5个JS命名闭包及约11个CSS规则块，主题0.26.0→0.27.0。减法审查移除了异步`close`回调、共享返回焦点状态、冗余方向监听和重复状态清理，未保留动画、状态持久化、polyfill、图标库、通用抽屉框架或D52/D53预实现；数据、Slug、支付、物流、订单和非Local均未改变。
- [x] 独立Code终审已关闭按钮级联、异步close竞态和桌面同宽偷焦点三个P2，最终P0～P3=0；最终静态复核确认Escape `keydown`＋原生`cancel`双路径幂等。独立Test/UX当日因登录态失效登记的跨断点、History/BFCache、全四端与异常/隔离证据P2，已在D52任何品牌实现前用可用Local登录态补跑1199→1200→1199、同宽History返回、四端、反向价格和搜索隔离后关闭。工具未暴露`pageshow.persisted`值，只记录实际返回行为。

## D52 品牌数据与筛选基线

- [x] 用户于2026-09-04明确授权按推荐范围仅在Local实施D52，并确认无品牌商品留空、品牌归档第一版`noindex`。回复同时列出`≤30 / 31～100 / >100`三个互斥档位，实际规模未冻结；它不阻塞通用骨架，超过30项的控件和负载复评转D53。
- [x] 接受ADR-033：复用WooCommerce 11.0.0原生`product_brand`，term保持扁平、每商品最多一个主要品牌；不创建`pa_brand`、重复taxonomy、品牌CPT、ACF字段或独立品牌插件。Website Manager可管理/分配，Content Editor只能分配既有品牌。
- [x] 原生商品CSV `Brands`列映射与导出关系通过。具有term管理权限的导入者可能因未知名称创建新品牌，第一版以“后台先建并审核、CSV完全匹配、导入后抽查新增term/层级/单品牌”SOP治理，不增加自定义导入器或保存拦截。
- [x] `inc/catalog-filters.php`在D50/D51同一aside中把原生文字品牌组放在Categories与Price之间；只在Shop/商品分类输出并继续使用Woo主查询。有效品牌可与分类、价格、Size、Shade和排序组合，搜索不显示筛选UI。
- [x] Woo原生Hook读取数组GET会Fatal，因此新增优先级1护栏：只允许最长512字节、纯正整数、去重排序且当前关联公开商品的品牌ID；数组、混合、未知或空品牌均变为空键，不形成tax query或传播。有效值也只允许影响非搜索Shop/商品分类，搜索与其他商品taxonomy隔离。
- [x] 品牌链接移除Woo内部`filtering=1`并复用集中白名单；选中项具有nofollow、可见勾选、`aria-current`和移除说明。临时term32制造分类＋品牌＋仅另一商品拥有的Size零结果时，品牌移除链接仍可见可键盘操作，恢复路径成立。
- [x] 品牌归档沿用Woo默认`/brand/{slug}/`；Local Yoast设为`noindex, follow`，实测无Canonical、不进Sitemap。筛选参数页继续noindex且Canonical回Shop/分类基址；商品搜索继续noindex且无Canonical。
- [x] 清理前两个已关联TEST品牌下审计19/19通过，Shop品牌29/30分别命中#44/#46，分类/组合/四端/详情/Schema/归档成立；数组Fatal、搜索污染、空品牌和ARIA问题均已关闭。
- [x] 清理后term 29～32、#44/#46品牌关系及`wc_layered_nav_counts_product_brand` transient均为0；发布商品2、#120～#130为11项Trash、lookup 7行及价格库存不变。Shop/分类0个Brand标题，#44详情与Schema无品牌，旧品牌URL和品牌Sitemap均404。恢复态再次列19项PASS，但有效正向分支条件跳过，不替代清理前证据。
- [x] 主题版本0.27.0→0.28.0；相对D52写前既有筛选模块净增146行/4个命名函数，0个新运行文件、0条CSS规则、0个JS函数、0模板/插件/依赖。双路独立终审P0=P1=P2=0；P3为Woo内部Widget/markup兼容、真实品牌规模、Production缓存/CDN和匿名Coming Soon边界。

## D53 已选条件、动态计数与重置

- [x] 用户于2026-09-04确认首版预计30个有效品牌，并明确仅按推荐范围在Local实施D53。第一版保留30项完整品牌文字列表，不增加搜索、折叠、Logo、虚拟列表、AJAX或新插件；超过30项须重新确认范围。
- [x] 在D50～D52同一筛选aside和Woo主查询上增加统一已选区：价格上下限合并为一个Chip，Size、Shade与Brand逐term显示；普通结果和零结果都只输出一次。逐项移除保留其他合法条件，`Clear filters`保留当前分类路由和合法`orderby`，二者均移除旧分页并回第一页。
- [x] Size、Shade和Brand显示父商品语义的动态计数：当前计数维度不自我收窄，但继承价格及其他已选维度；属性lookup开启时以`product_or_parent_id`约束父商品，缺货隐藏为`no`时继续计入目录可见缺货父商品。Category与Price本日不增加计数。
- [x] 计数只在三个Widget渲染期临时挂接Woo过滤器，并沿用Woo查询哈希transient；没有自定义缓存或第二商品结果查询。30品牌代表矩阵实测冷最多3、暖0条计数SQL，品牌递归子项查询0；五组TTFB冷/暖中位数为140.1/127.6ms。属性lookup临时关闭时代表场景仍通过，恢复后配置为`enabled=yes/direct=yes/optimized=no`。
- [x] 公开GET在主查询`pre_get_posts`优先级1统一清洗，Size/Shade固定为第一版`OR`语义。缺失`query_type`、显式`AND`、非法或未知`filter_*`/`query_type_*`及畸形品牌值会在`template_redirect`以302跳转至当前分类或Shop的白名单第一页；合法OR保持200。商品搜索和其他上下文移除价格与全部筛选键并重置Woo已选属性缓存，隐藏参数不再缩小结果。
- [x] 30品牌/30发布商品Local夹具下，`day53-catalog-filter-audit.php`的16个Shop、分类、组合、顺序、零结果、反向价格和搜索场景全部通过；独立oracle同时核对结果、计数、已选项、移除/Clear URL和查询上限。带价格、Large和Brand04的商品搜索仍显示30件结果且筛选UI为0。
- [x] `day53-responsive-audit.mjs`在390/768/1024/1440验证2/2/3/4列、0横向溢出、44px目标、30项完整文本、长名称与HTML特殊字符、唯一aside/dialog、内部滚动到Brand30、Close/Escape焦点返回、History、Focus、零结果及反向价格错误态；8张截图与页面Console错误0。
- [x] 清理护栏会在删除品牌前核对全部关联对象；临时把Brand30关联既有Trash #120时，cleanup按预期拒绝并且没有部分删除。解除关系后正式cleanup成功。最终`brand_terms=0`、D53 TEST term/SKU/post=0、`product_brand`关系=0，#44/#46/#120品牌为空；发布商品仅#44/#46，Trash为#120～#130共11项，lookup 7行且父商品仍#44/#46，Coming Soon和Woo配置均恢复，三类计数transient为0。更新后的D52恢复审计19/19通过。
- [x] 独立Code Review已关闭清理外部关系、lookup关闭回退、缺失/AND查询类型、非法直达零结果和搜索隐藏筛选五类问题；独立安全审查确认GET/302、输出转义、SQL标识符/预处理、Woo缓存和Local清理门禁通过，最终P0=P1=P2=0。保留3项非阻塞P3：属性筛选512字节纵深上限、嵌套Widget时恢复计数taxonomy旧全局值、夹具检查`delete(true)`返回/极端部分失败恢复，由开发者在D54或相关代码再改时评估。静态语法、差异与截图复核通过。`debug.log`保留实施早期已修复的lookup表警告、探索性引号/错误端口连接警告，以及旧D52测试绕过新边界导致的一次脚本Fatal；D52脚本已修正并19/19通过，历史日志未清空，不能表述为全局日志干净。
- [x] 主题版本0.28.0→0.29.0。按UTF-8物理行与字节口径，`catalog-filters.php`597→897行、22696→33360字节，净+300行/+10664字节及3个命名函数；`catalog.css`509→592行、13832→15913字节，净+83行/+2081字节及约10个规则块；合计运行源码净+383行/+12745字节。`style.css`仅替换版本号，D51 JavaScript保持4077字节/156行；0个新运行文件。

## D54商品发现全链路回归与W9收口

- [x] 用户明确授权仅在Local运行现有审计、浏览器/HTTP回归及必要临时状态；不重建30品牌夹具、不恢复#120～#130，发现运行代码缺陷先提交最小修复确认。D54原授权回归未发现需修复的运行时缺陷，运行代码净改动0；其后Git提交前专项审计和另获授权的最小修复单独记录如下。
- [x] 冻结WordPress 7.0.4、WooCommerce 11.0.0、Storefront 4.6.2、Yoast 28.2、DentAll 0.29.0与Core 0.2.7基线；`catalog-filters.php`、`catalog.css`、`catalog-filters.js`和SEO入口哈希在回归前后相同。恢复态为2个发布商品、11个Trash、0品牌、lookup 7行/父商品#44/#46。
- [x] D52恢复态审计19/19通过；D47正常搜索、零结果和`price-desc`三场景分别返回2/0/2件。Shop、#18有商品分类、#24空分类、未知分类、排序、Price/Size/Shade有结果/零结果和反向价格HTTP矩阵通过。
- [x] 7类无效请求均302至预期白名单第一页，并含`X-Redirect-By: DentAll`与`no-store`：缺失query type、显式AND、非法term、未知`filter_color`、畸形品牌、科学计数价格及600字节属性值。600字节样本无414/Fatal，但只作为安全收口证据，不关闭显式长度上限P3。
- [x] Shop与商品分类通过请求进程内`loop_shop_per_page=1`分别形成1+1两页：Page 1/2商品、排序参数链接、基础/自身Canonical及越界Page 3的404/noindex均正确；没有恢复回收站商品或修改持久化每页配置。
- [x] 390/768/1024/1440下基础、有结果筛选和零结果三态均为2/2/3/4条CSS Grid轨道，0 Brand空壳、0横向溢出；1199↔1200 DOM迁移、Close/Escape焦点返回、History、反向价格自动开抽屉和搜索隔离通过。独立Test/UX生成并目视复核14张`.codex-tmp/day54-*.png`，有效页Console/Page Error为0。
- [x] Shop/排序保持`index, follow`且Canonical回基础归档；筛选为`noindex, follow`且Canonical回当前基础归档；商品搜索为`noindex, follow`且无Canonical。6个Yoast子Sitemap均不含价格、筛选、query type或排序参数URL；Shop/分类CSS+JS为1/1，搜索为1/0，404为0/0。
- [x] 同一筛选冷/暖HTTP均200，Size/Shade缓存内容稳定；D53的冷最多3/暖0条计数SQL证据在相关版本与文件尺寸一致前提下复用。最终Coming Soon=`yes`、`posts_per_page=10`、三类facet transient为0，D52恢复态审计再次19/19通过。
- [x] 独立静态Review与Test/UX最终P0=P1=P2=0。D53三项P3均未触发：属性参数长度、嵌套Widget全局值恢复、夹具极端失败恢复继续按触发条件监控。
- [x] Git提交前专项审计发现WooCommerce停用时`WC_Query`类可能不存在的兼容性P2；用户确认后只在`dentall_catalog_filter_prepare_query_args()`首个短路条件增加`class_exists( 'WC_Query' )`，没有新增文件、函数、Hook、查询或版本号。`--skip-plugins=woocommerce`动态冒烟、D52恢复审计19/19、D47正常商品搜索与独立复核均通过，最终P0=P1=P2=0。DentAll Core `readme.txt`的`Stable tag`与Changelog同时对齐既有0.2.7，不改变运行逻辑。
- [x] D43～D54已按职责形成7个中文提交：`dc5d7de`（Core 0.2.7）、`9a4c42c`（主题发现链路）、`e13495b`（D47测试）、`3338133`（D52～D53测试）、`114e69b`（D43～D48笔记）、`ea5ebac`（D49～D54笔记）及`c0a1ba9`（主文档收口）。连同本地既有4个D33～D42提交，11个提交已从`2c254f8`推送至个人私有`origin/main`的`c0a1ba9`。
- [x] 从远端`main`浅克隆恢复后，HEAD精确为`c0a1ba9acb2e8e0dfe9e82768a01775d4ea78c7c`；7个关键运行/说明文件对象与本地一致，工作树干净，`git fsck --no-dangling`通过，9个冻结D25草稿与1个未知0字节文件均未进入远端。临时克隆已清理。个人私有Git技术基线完成，但不等于`deploy/staging`部署或公司控制的远程所有权、备份与交接完成。
- [x] `debug.log`从243599字节起只新增测试过程证据：3条普通`wp.bat`连接错误端口的数据库警告，以及内联缓存审计第一次引号解析失败产生的2条WP-CLI测试工具Fatal；修正为端口10011和原生transient命令后通过，后续有效HTTP/浏览器请求没有新增PHP日志。历史日志未清空，不能表述为全局日志干净。

## 现有设计素材冻结v1

- [x] 按用户最终范围只冻结已选用的现有素材：`design-assets/final-versions/v1/`含64个视觉副本，总计69,106,251字节，64个SHA-256均唯一且与来源一致；GPT新页面输出为0，全部未批准公开。
- [x] 已撤销缺页、About、Solutions、FAQ和文章内容的生成计划与项目内候选副本；后续仅在对应开发日出现明确视觉证据缺口时另行说明并申请生成授权。
- [x] 冻结目录只用于本机开发选稿和版本追踪，当前被Git忽略，不等于团队备份；没有修改WordPress运行代码、数据库、URL、SEO、缓存、支付、物流或部署配置。

## 下一步三个验收结果

1. D55先只读冻结商品详情字段职责、WooCommerce原生模板/Hook来源、PC详情骨架和正常/缺图/长文/售罄或不可购买等适用状态；提交功能确认单后才实施，不提前进入D56图库或D58购买区。
2. 以#44 Simple和#46 Variable作为结构样本，不要求业务方先补齐逐商品正式名称、品牌、价格或素材；若详情方案改变URL、Schema、Variation选择或购买流程，再提出最小必要业务问题。
3. 个人私有Git技术基线已经完成；继续处理公司远程所有权、正式品牌/商品/分类/素材与Trust事实，以及Variable CSV、Coming Soon、Newsletter/SMTP、支付、物流、税费、缓存和非Local部署门槛。不得把个人远端恢复通过写成公司治理、Staging或Production部署完成。

## 本周风险

- 如果支付、物流、税费和SMTP长期不确认，会影响W13以后关键路径。
- 商品、文章和Page技术路径已完成D25抽查，WM-A可在受保护Staging按批准SOP用Simple模板v1小批次录入Draft；Variable/Variation CSV、正式内容审核、Production同步和发布仍是独立闸门。
- 不要将当前大型PNG和ZIP直接提交到源码Git仓库。
- 正式Logo、品牌字体及许可证、Hero/分类/Solutions/商品图、支付和社交官方素材仍缺；D31 AI透明PNG只在Local占位，不阻塞通用骨架，但阻塞正式品牌视觉与业务内容验收。
- D37当前3:2实色Hero WebP只在Local作为TEST特色图，背景烘焙在像素内，不能靠CSS可靠变成无缝透明前景；D38通用响应式骨架已完成，但正式视觉验收仍需要业务提供符合Hero素材合同的授权透明主图。
- D40转入D42的真实首页四端与相邻区块证据已在登录态补齐，未发现需要改全局Section Token的缺陷；正式Solution素材裁切仍待业务供图后重新验收，当前TEST图不能证明正式视觉。
- D41真实Local商品累计销量基线为0，Best Sellers正确隐藏；读取期模拟只能证明查询与模板合同，不能证明真实订单状态、退款、同销量并列或整页缓存刷新。若要制造正销量证据必须另行授权受控TEST订单与清理，禁止直接修改销量meta/lookup table。
- D41 Trust五项和`Secure Payments`均是未经业务证明的Local设计预览，非Local代码路径为空但尚无Staging/Production运行证据；D42已完成真实整页Trust→Newsletter复核，但不得把登录态Local外观、匿名Coming Soon页面或设计稿文字写成正式指标/支付已实现。无横滑宽度的Trust列表仍保留一个额外非阻断Tab停靠点P3。
- M4已按Local技术v1通过，但当前Logo、Hero、分类、Solutions、Footer菜单和营销文案仍含TEST或占位内容；这不阻塞通用骨架，却阻塞正式品牌视觉、业务内容、匿名预发布和非Local验收。
- D46只用13项形成真实2页；11页场景的链接窗口与`flex-wrap`只有算法和几何证据，没有132项真实DOM、Production缓存或抓取证据。Storefront/WooCommerce升级若改变分页回调名、优先级、模板参数或DOM，必须重跑Shop/taxonomy/search与Page 1归一化矩阵。#120～#130当前在Local回收站且可恢复，未来手工或自动清空后将永久删除；不得把它们恢复并误作正式商品。
- D47真实空结果页四端、长词、CTA、44px、Focus、Console与截图已在D48实施前补齐，原P2关闭。后续若搜索DOM、CSS、主题/Woo版本或空态文案发生变化，需重新跑正常/空结果四端，不能永久沿用旧截图。
- D47无效请求302发生在主查询之后，不能节省已经执行的查询；非Local Web服务器/CDN可能先对超长URL返回414，缓存若忽略查询参数也可能混淆搜索响应。Staging/Production部署前必须验证URL长度、查询参数缓存键、`X-Redirect-By`、noindex与Sitemap；当前Local结果不外推。
- D48的商品分类Title/Social Title模板只写入Local数据库，Git不会自动携带；部署到Staging/Production时必须按两个精确键重放并验证缓存后的Title、OG、Canonical、robots、Sitemap和分页。现有#18/#24均为TEST内容，不得因Local index/follow或技术页面可访问而进入Production正式导航与索引验收。
- D48最终0.25.0恢复态Shop、有结果搜索和短内容#18已在D49配置前补跑390/768/1024/1440并保存12张截图；原证据P2关闭，W8 Local视觉矩阵可以按当前2商品状态闭环。若主题/Woo版本、归档DOM、商品卡或断点改变，仍须重新验证，不能永久沿用旧截图。
- D50已关闭D49参数页robots差距，并验证分类、属性、价格、排序和分页新链接不复制未知参数；但Local没有Production页面缓存/CDN。非Local部署前必须确认参数缓存键或绕过策略，重跑基础/组合/分页/Canonical/robots/302，不能把Local无缓存结果外推为生产隔离已完成。
- D51采用原生dialog且只在现代Local浏览器完成自动化；真实iOS/Android触屏、辅助技术和不支持`showModal()`的旧浏览器未验。当前降级是不暴露窄屏失效入口而保留商品页与PC侧栏，不等于旧浏览器也有移动筛选；若业务支持矩阵要求更广，必须单独比较polyfill、非模态降级或最低浏览器版本的维护成本。
- 用户已确认首版预计30个有效品牌，D53以30品牌/30商品及长名称完成完整文字列表、DOM、0递归品牌子项SQL和冷/暖计数缓存验证；这不等于31～100或更大目录已通过。实际数量超过30、品牌名明显更长或运营反馈难用时，必须重新评估搜索/折叠、DOM和查询负载。
- D52复用Woo内部`WC_Widget_Brand_Nav`，并对其当前`li/a` markup补无障碍属性。`class_exists`失败会诚实空输出，但Woo升级仍可能改变Hook、查询或DOM；升级后必须回归品牌入口、选中/移除、空态、数组护栏和搜索隔离，再决定公开API或最小自有渲染替代。
- 品牌归档noindex是Local Yoast数据库配置，Git不会自动同步；Staging/Production部署时需明确重放并验证robots、Canonical、Sitemap、缓存和旧/新Slug。原生CSV具有管理权限时可能创建未知品牌，正式导入必须执行批准term与导入后抽查SOP。
- Production页面缓存/CDN必须把`filter_product_brand`与分类、价格、Size、Shade、排序和分页共同纳入缓存键或绕过；三类分面计数另使用Woo原生查询哈希transient。D53只验证Local冷最多3/暖0、TTFB样本与清理，未测量非Local页面缓存、真实目录、缓存失效时延或Core Web Vitals。
- D49的7行查询表仍只覆盖恢复态2个TEST父商品与3个Variation；D53的30商品夹具证明代表组合下的查询与回退，不证明正式目录的重建时间、保存延迟、数据库负载或Production缓存性能。Direct Updates适合当前小批量基线；未经目标环境测量不得宣称性能提升。
- D49接受父商品级属性语义。严格同Variation组合、缺货term隐藏和评分仍未实现；品牌已由D52以独立taxonomy接入，但不改变属性的父商品级语义。尤其`Large 105 mm + Medium`没有对应Variation但仍命中#46，这是已记录合同而非查询缺陷。若业务要求改变，必须先重新确认数据、查询、URL、计数和性能范围。
- WooCommerce 11.0.0当前只在商品taxonomy第一页输出描述；Day48遵守“不恢复#120～#130”，没有形成新的真实分类Page 2描述证据。当前源码与D46/D47分页证据支持技术判断，但Woo/主题/Yoast升级或正式大分类上线前仍须复测根页、Page 2和越界页。
- 1024首页图属于推导证据，AI稿中的业务数字和功能不得固化；用户已冻结1320px为容器外框宽，现有Google字体依赖及浅边框用途仍须在后续浏览器和可访问性证据中复核。
- WooCommerce Coming Soon仍遮蔽匿名真实商城内容，且其独立模板保留LinkedIn、Instagram、Facebook默认链接（D35环境P2）；未获得单独页面配置授权前不修改，最晚在匿名Staging或Production预发布页开放前处理并复测。C7已通过登录态Chrome补齐Home、Shop、Cart空态和Account登录态的四端公共基线，但非空Cart、错误/Notice、售罄及账户登录注册等状态仍须在对应工作日安全验证，不得为制造证据擅自关闭保护、索引或修改数据库。
- D31当前语言/币种/Help只是无交互槽位；WPML/WCML虽已在早期评估中冻结候选方向，但未安装或验证。未来接入会新增URL、SEO、缓存、购物车金额与插件生命周期风险，必须重新走功能确认和代表状态验收。
- D33已关闭Cart Blocks页无Header移动入口、折叠Search越界、1×1 submit可见Focus、经典fragment动态数量及旧浏览器fragment覆盖；搜索提交/结果的服务端合同、有结果和空结果真实四端几何已由D47及Day48实施前补验完成。Cart Blocks同页Store API改量后的Header即时同步、非空Mini Cart、非Local/正式Custom Logo仍分别按D69/素材与部署节点验收，不能用经典fragment通过推断Blocks事件已互通。
- D32多个Primary自定义项仍重复指向`/shop/`或`/`；D36 Footer term 26的9项也只允许作为Local TEST骨架。正式页面/分类、栏目与Slug到位后必须复核当前项、404/301、Canonical和全站内部链接；不能把已通过的技术填充态推断为正式业务内容已验收或把TEST菜单迁移到非Local。
- 单休节奏下第7天必须完整休息，不能靠连续取消休息日吸收偏差。

## 进度真实性口径

- D1～D18保留原编号并解释为结果驱动的计划检查点；检查点通过以授权结果、配置/代码落地和验证证据为准，不要求等同18个足量工作日。
- D1～D18实际有效工时未记录，历史总工时保持“未统计”；这只影响生产率和工时偏差分析，不影响已有技术证据或检查点推进。
- Git `main`从2026-08-06至2026-08-18共有8个不同提交日期；它只能证明版本活动日期，不能作为工时证据。
- 当前不把D日序号直接换算成整体完成百分比。后续分别报告计划位置、能力状态、人员/业务验收和可演示结果；实际工时仅在用户选择统计或出现延期时报告。
- 同一自然日期可以完成多个D日检查点，不人为放慢已通过验收的工作；用户发现速度慢于预期时主动说明，再调整排期或低优先级范围。`Obsidian每日复盘模板.md`继续记录验收层级和可复演结果，工时改为可选。

## 工时记录（可选）

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
| W3 / D13 | 6小时50分钟 | 待用户记录 | 已完成（培训者预演与简单商品流程） | Website Manager引导预演和不到5分钟无提示复跑通过；促销、库存、Backorders、缺图及恢复通过，P0/P1为0；第二身份人员验收未执行，CR-007后为条件性补验而非当前待补 |
| W3 / D14 | 6小时50分钟 | 待用户记录 | 已完成（Local可变商品流程） | Website Manager完成Variable默认值、价格、库存、非法组合、物流继承/覆盖和购物车验证；父子重复库存P1修复并完成持久化读回，已执行范围P0/P1为0 |
| W3 / D15 | 6小时50分钟 | 待用户记录 | 已完成（Local库存与物流字段） | 三层物流数据、Simple/Variable继承覆盖、数量/状态库存、临时缺货与恢复通过；45/45及独立23/23、17/17审计通过，正式物流输入仍待业务与承运商方向 |
| W3 / D16 | 6小时50分钟 | 待用户记录 | 已完成（商品SEO规则与双环境边界） | 重复Title修复与Staging部署、字段职责、Slug/301/Canonical、缺货/停售生命周期、Yoast五页矩阵及`/product/`基线完成；Production未变更 |
| W3 / D17 | 6小时50分钟 | 待用户记录 | 已完成（代表商品与SEO骨架验收） | 累计5个Staging样本覆盖Simple、Variable、缺货Variation、多图及Yoast保存/输出；独立回归P0～P3为0。真实商品内容、可下载和永久停售样本未验收；Production未变更 |
| W3 / D18 | 6小时50分钟 | 待用户记录 | 已完成（M2商品模型候选冻结） | C1～C7完成；Local与Staging商品职责、权限、真实保存和原生CSV导出通过，M2候选冻结与W3周验收收口；中文CSV重复表头P2转D25 |
| W4 / D19 | 6小时50分钟 | 待用户记录 | 已完成（博客信息架构v1） | 原生Post/Category/Tag、`/blog/`路由、归档治理、内容作者与受支持字段修订、Yoast输出通过Staging验收；公开统一署名与作者Schema转D87 |
| W4 / D20 | 6小时50分钟 | 待用户记录 | 已完成（TEST机制） | 字段职责、正文骨架与Staging长文草稿#68通过；媒体、内链、Yoast、预览、修订恢复、匿名404及Sitemap排除已验，正式16:9素材、授权与真实内容转D24 |
| W4 / D21 | 6小时50分钟 | 待用户记录 | 已完成（候选清单） | About、Contact、政策页与FAQ候选清单及职责边界已形成；未创建页面、菜单或正式Slug，2026-08-21已纠正工时和职责口径 |
| W4 / D22 | 6小时50分钟 | 待用户记录 | 结构决定已接受，WM-A走查已完成 | ADR-023已确认Page优先、不建CPT；D24已完成WM-A Page字段、预览、匿名隔离与权限边界，CR-007把WM-B跨账号改为条件性补验，正式内容/Slug/菜单仍待后续 |
| W4 / D23 | 6小时50分钟 | 待用户记录 | 治理方向已确认，实际验收进行中 | 非强制互审与Markdown单一活动登记载体已确认；A/C3-A/C7技术与人员抽样已关闭当前边界。首条正式内容/素材登记仍待，Git企业交接留D25 |
| W4 / D24 | 6小时50分钟 | 待用户记录 | A/C2-A/C7当前技术与人员抽样通过 | #76完成字段/权限、发布、缓存、菜单、修订及两类恢复后保持Draft；A/C5完成#11排除、#24基线不改、#90审阅Draft隔离、#68/#76引导操作；A/C6按可查SOP口径通过，A/C7完成状态/字段/预览/隔离/修订/登记抽样且P0/P1为0。CR-007排除WM-B当前验收；正式3篇文章＋1个Page及授权16:9素材仍待，D24整体进行中；Git企业交接留D25/M3 |
| W5 / D25 | 6小时50分钟 | 待用户记录 | C1～C7技术/人员验收完成；M3整体待治理/业务门槛 | Staging部署、WM-A Simple模板v1的2行Draft导入、既有数据对比、重复SKU跳过、#110普通恢复、创建者追溯及文章/Page复用回归通过，P0/P1为0；手册、两次CSV批次与恢复记录已收口。正式业务内容/素材与公司Git交接仍待，不误报M3整体完成 |
| W5 / D26 | 6小时50分钟 | 待用户记录 | 已完成（Local技术验证） | Storefront父主题＋DentAll子主题骨架、继承链、样式顺序、导航fallback保护及Home/Shop/Cart/My Account回归通过；未进入视觉实现或Staging部署 |
| W5 / D27 | 6小时50分钟 | 待用户记录 | 已完成 | 子主题0.3.2已落地63个Design Token、最小`body`映射、1320px外框及20/32px响应式gutter；C6真实Shop与C7四页×四端登录态真实DOM、截图、当前状态、日志和双重独立复核通过，P0/P1为0。Cart仅空态、Account仅登录态；代码、配置、数据库、索引保护与Staging均未因C7改变 |
| W5 / D28 | 6小时50分钟 | 待用户记录 | 已完成（Local技术验收） | C1～C7完成原生`Sort by`、标题/文本链接、按钮、表单、Error与分区Focus基线；用户确认后将子主题精简至0.5.2，仍为单CSS且净少104行/1436字节。既有23组四端、键盘与对比度证据，最终六页HTTP、Simple/Cart计算样式、静态检查和独立Review通过，P0/P1/P2/P3为0；动态证据缺口及未自然出现状态已如实登记，Staging/Production未变 |
| W5 / D29 | 6小时50分钟 | 待用户记录 | 已完成（技术收口） | Product/Category/Solution三类卡片内部契约、13种状态、19-ID夹具、真实Shop四端、静态审计、独立Review和学习笔记完成；真实分类/Page接入留D39/D40，最终Shop网格留D44 |
| W5 / D30 | 6小时50分钟 | 待用户记录 | 已完成（W5前端周验收） | D29/D30受控合并为0.8.6；Grid/Section、Loading/Empty、Classic/Blocks通知完成四端/Focus/ARIA/Reduced Motion回归，四类真实页面修复完成四端复验，商品Tabs/默认链接由开发者最终确认关闭；测试范围P0/P1/P2/P3为0，Design System v1完成；M3业务/素材/Git治理仍独立待办 |
| W6 / D31 | 6小时50分钟 | 待用户记录 | 已完成（Local最小Header技术验收） | 子主题0.9.0完成Local三公告/三个右侧非交互槽位（币种/语言未来插件位置＋Help页面位置）、透明占位Logo、搜索/Account/原生Cart主行、动作蓝纠偏与领域CSS拆分；五页×四宽结构、公告四端、1440完整Focus、390三个底栏动作/展开后input、Console、静态/HTTP/对比度完成取证，P0/P1=0。390折叠态直接Tab、1×1 submit可见Focus、Cart Blocks移动入口、真实搜索提交、fragment/非空Mini Cart、非Local/正式Logo和WPML/WCML尚未关闭；未部署Staging/Production |
| W6 / D32 | 6小时50分钟 | 待用户记录 | 已完成（Local最小PC导航技术验收） | 用户创建并绑定`TEST D32 PC Navigation`；子主题0.10.0完成`>=1200px`无分隔线PC导航、分类按钮上下8px留白、两个一级下拉、键盘/边界与五页1440回归。独立Code Review与设计复核无遗留P0/P1/P2；手机/平板、真实触屏、正式URL/当前态及Staging/Production按后续节点接续 |
| W6 / D33 | 6小时50分钟 | 待用户记录 | 已完成（Local推荐最小范围；2项P2证据转D34） | 子主题0.11.10完成单一Primary DOM、手机/768实际Logo居中、非模态面板、一级子项常显、Search视觉精简、动态Cart数量、经典fragment缓存迁移及Handheld清理；无新增JS，当前范围P0/P1为0。完整键盘链、四类页面四宽批量回归和真实触屏由D34补证；Cart Blocks同页同步转D69，非Local未部署 |
| W6 / D34 | 6小时50分钟 | 待用户记录 | 已完成（Local自动化＋用户确认实体设备验收） | 子主题0.12.0把1024～1199收敛为紧凑菜单，1200起恢复完整PC导航；关闭态子菜单与Reduced Motion P2已修复并经独立Review关闭，390～1440边界、390/768/1024 Enter链、Skip Link、强制Reduced Motion夹具和五类页面20/20回归通过。用户于2026-08-28确认实体设备触摸、方向切换与系统级Reduced Motion验收通过；无新JS/插件/模板，非Local未部署 |
| W6 / D35 | 6小时50分钟 | 待用户记录 | 已完成（Local推荐最小技术范围；1项环境P2另行处理） | 子主题0.13.2新增独立Footer模块、深度2且无Page回退的Footer菜单位置、Local不可提交Newsletter、动态品牌/版权与空状态；Day35 Footer未输出社交/支付。七宽度0溢出、390五类页面5/5、静态/HTTP与独立Review通过；匿名Coming Soon独立模板的默认社交链接登记为环境P2，Footer正式菜单/URL、填充态视觉和手机/平板收口转D36，非Local未部署 |
| W6 / D36 | 6小时50分钟 | 待用户记录 | 已完成（Local推荐最小技术范围；正式内容与环境P2另行处理） | Administrator创建并绑定9项TEST Footer菜单term 26，Primary term 25不变；子主题0.14.1以同一DOM完成390单列、768四列、1024五列与1200起菜单＋品牌静态重排，补充长文本、当前项和普通链接装饰状态。无新PHP/JS/Walker/模板/插件，Newsletter继续0表单/2禁用控件；正式栏目、Newsletter、社交/支付和非Local部署未完成 |
| W7 / D37 | 6小时50分钟 | 待用户记录 | 已完成（Local推荐最小技术范围；正式素材与D38精调另行处理） | 子主题0.15.3新增Homepage模块与条件CSS，使用原生Home/Blog Reading路由、核心区块和特色图完成PC Hero；根URL、`/blog/`、390/768/1024/1440px与独立Review通过，P0/P1为0。当前3:2实色WebP与TEST英语内容只限Local；正式透明素材、D38移动视觉及非Local部署未完成 |
| W7 / D38 | 6小时50分钟 | 待用户记录 | 已完成（Local用户确认范围；正式素材与非Local部署另行处理） | 子主题0.16.0复用同一Homepage DOM，以Mobile First Grid叠层完成四端Hero精调；390/768隐藏三项辅助卖点、1024/1440恢复三列，四端高度约320/320/333/352px且无横向溢出。设计与测试专项均0缺陷；Code Review仅保留响应式图片窄宽带轻微过取P3。正式透明素材、正式内容和非Local部署未完成 |
| W7 / D39 | 6小时50分钟 | 待用户记录 | 已完成（Local用户确认范围；正式分类与非Local部署另行处理） | 子主题0.17.0用专用原生菜单选择/排序真实顶级前台非空`product_cat`，复用Woo CategoryCard并精确恢复循环；3/5/9/9容量、0/1/4/8/9/10项、可见count、缺图、长标题、Focus及真实menu ID 27通过。最终独立Review P0/P1/P2/P3=0；当前TEST分类与图片、Staging/Production未验 |
| W7 / D40 | 6小时50分钟 | 待用户记录 | 已完成（Local技术范围；整页视觉证据由D42补齐） | 子主题0.18.0用专用菜单选择/排序真实已发布Page，过滤后最多输出4张D29 SolutionCard；menu ID 28的Page 106/108/110/112均为Local TEST、逐页noindex且不进Sitemap，0/1/4/4+、草稿/密码/重复/非Page、空摘要、缺图、菜单别名和无`View all`/`/solutions/`通过。Code/Design/Test独立复核无P0/P1/P2；真实页四端与相邻节奏已由D42补证，正式素材裁切仍待供图 |
| W7 / D41 | 6小时50分钟 | 待用户记录 | 已完成（Local技术范围；整页集成由D42补齐） | 子主题0.19.0按Woo累计销量与可见性输出最多5张原生ProductCard，真实0销量整区隐藏；设计稿五项Trust/图标只限Local。只读审计15/15、四端夹具、768键盘横滑及三路独立复核完成，最终P0/P1/P2=0；D42已补整页邻接，无订单/销量写入、非Local部署或正式指标确认 |
| W7 / D42 | 6小时50分钟 | 待用户记录 | 已完成（M4 Local技术v1） | 登录态Homepage完成390/768/1024/1440整链路与0页面溢出验证，Console 0、7项资源200、主题PHP和D41审计15/15通过；补齐1024下半页后独立复核P0/P1/阻塞P2=0。运行代码0改动、DentAll保持0.19.0且未创建TEST订单；正式内容、非Local部署和生产性能另行验收 |
| W8 / D43 | 6小时50分钟 | 待用户记录 | 已完成（Local确认范围） | Shop公开标题改为`Products`且`/shop/`不变；DentAll 0.20.0复用Woo原生Archive Header、主查询、循环与ProductCard并条件加载归档CSS。四端、taxonomy正常/空态、排序、搜索隔离与三路独立复核通过，最终P0～P3=0；D44～D49、正式内容和非Local部署未提前实施 |
| W8 / D44 | 6小时50分钟 | 待用户记录 | 已完成（Local确认范围；代表数量/状态动态证据另行补验） | DentAll 0.21.0用既有`catalog.css`完成2/2/3/4列和16/24/24/24px gap，Woo保持3×4=12项/页；Shop四端、320、taxonomy正常/空态、搜索隔离及三路独立复核通过，最终P0～P3=0。真实1/5/12项、D29特殊状态整合、Console/独立HTTP和非Local未验 |
| W8 / D45 | 6小时50分钟 | 待用户记录 | 已完成（Local确认范围；Focus截图/Console与非Local另行验） | DentAll 0.22.0复用Woo原生GET排序和主查询，把Shop/taxonomy收敛为一组顶部工具栏；320～1024视觉隐藏但保留结果状态，1440结果左/排序右，底部只留D46分页位置。五宽、升降价/非法值/参数保留、正常/空taxonomy、搜索隔离、Canonical及三路复核通过，P0～P3=0；未改12项、Grid、查询、分页、搜索或数据 |
| W8 / D46 | 6小时50分钟 | 待用户记录 | 已完成（Local确认范围；真实11页DOM与非Local另行验） | DentAll 0.23.0复用Woo每页12项、原生分页模板和WordPress链接算法，只保留Shop/taxonomy底部一组分页；11个临时商品形成12/1，五宽、44px、Focus、参数、Page 1直链、Canonical/404、空态与搜索隔离通过。初始Page 1跳转P2已修复，三路终审P0/P1/P2=0；#120～#130已移入回收站，未提交Git或部署非Local |
| W8 / D47 | 6小时50分钟 | 待用户记录 | 已完成（Local确认范围） | DentAll 0.24.0让明确商品搜索复用原生标题、主查询、ProductCard、Grid、顶部工具栏和底部分页；非法输入/单结果302、零结果双CTA、真实12/1、参数、转义、noindex/无Canonical、普通搜索隔离与数据清理通过。slash/Unicode/特殊词边界P2已修复；#120～#130均回收、发布2项。有结果四宽与Day48实施前补齐的空结果CTA/Focus/Console/截图均通过；未提交Git或部署非Local |
| W8 / D48 | 6小时50分钟 | 待用户记录 | 已完成（Local功能与最终视觉证据） | DentAll 0.25.0以#18可逆验证商品分类长标题、多段描述、链接、Yoast内容级覆盖与四端；3条局部CSS声明修复390px Grid长词裁切，商品分类Title/Social Title移除`Archives`。#18已精确恢复、#120～#130未恢复、无正式分类；原恢复态Shop/有结果搜索/短内容分类四端证据P2已在D49配置前用12张截图关闭。正式内容与非Local配置/缓存/抓取另行验收 |
| W9 / D49 | 6小时50分钟 | 待用户记录 | 已完成（Local确认范围） | 先关闭D48最终矩阵P2；冻结分类、价格、Size、Shade商品级筛选合同与父商品Variation语义。完整重建并启用Woo属性查询表，7行/2父商品，Direct=yes、Optimized=no，属性归档和缺货设置不变；11个主查询与数据审计通过。运行代码0改动、主题保持0.25.0；参数页noindex目标、PC/移动UI、正式数据、品牌/评分、缓存与非Local另行验收 |
| W9 / D50 | 6小时50分钟 | 待用户记录 | 已完成（Local确认范围） | DentAll 0.26.0在1200px起为Shop/商品分类显示Categories、Price、Size、Shade常驻侧栏，复用Woo主查询/Layered Nav且无JS；Core 0.2.7将筛选参数页设为noindex/follow并保留基础Canonical。四宽、组合、非法价格302、排序/分页白名单、键盘与数据不变量通过，三路终审P0～P3=0；移动抽屉、品牌/计数、缓存和非Local另行验收 |
| W9 / D51 | 6小时50分钟 | 待用户记录 | 已完成（仅Local确认范围；原证据P2已关闭） | DentAll 0.27.0在小于1200px以原生dialog承载D50同一筛选aside，完成Filter、Close/Escape/遮罩、焦点、滚动锁、方向/断点/BFCache和错误/空态；1200px起恢复240px侧栏，搜索脚本隔离。运行源码净增313行/1个JS文件，无新查询、参数、数据、插件或非Local变更；当日最终矩阵P2已在D52实施前补跑关闭，真实移动设备、旧浏览器与Production缓存未验 |
| W9 / D52 | 6小时50分钟 | 待用户记录 | 已完成（仅Local确认范围） | DentAll 0.28.0复用Woo原生`product_brand`，冻结扁平/最多一个/无品牌留空、角色、CSV、默认`/brand/`和第一版noindex合同，并在Shop/分类同一aside加入品牌。输入Fatal、搜索隔离、空品牌和ARIA问题关闭；清理前非空19/19与四端/组合/SEO通过，清理后0品牌/0关系、旧URL404、Schema/缓存恢复。净增146行/4函数，无新运行文件/规则/JS/插件；双路终审P0=P1=P2=0，真实规模与非Local缓存未验 |
| W9 / D53 | 6小时50分钟 | 待用户记录 | 已完成（仅Local确认范围） | 用户确认首版预计30个有效品牌；DentAll 0.29.0完成价格/Size/Shade/Brand已选项、逐项移除/Clear、三类父商品动态计数、公开GET规范化302与搜索隔离。30品牌/30商品下16场景、四端、冷最多3/暖0条计数SQL、0品牌子项查询、cleanup护栏和恢复态通过；终审P0=P1=P2=0。运行源码净+383行/+12745字节、0新运行文件，>30、真实设备、正式内容和非Local未验 |
| W9 / D54 | 6小时50分钟 | 待用户记录 | 已完成（W9 Local技术回归） | 恢复态2商品/0品牌下完成Shop/分类/搜索、排序、请求内1项/页分页、Price/Size/Shade、Chips/Clear、零结果、7类302、robots/Canonical/Sitemap、缓存与四端回归；14张截图及独立静态/Test/UX复核P0=P1=P2=0。D54原回归未重建30品牌夹具且运行代码0改动，配置/数据/transient精确恢复；提交前另获授权补入一项WooCommerce缺失短路守卫并通过动态冒烟。D43～D54个人私有Git基线`c0a1ba9`及远端恢复验证完成；真实设备、正式内容、公司Git治理和非Local仍待 |

## 更新规则

- 每天记录完成、验证证据、阻塞和明日第一件事；实际工时由用户按需记录，出现延期时再用于偏差分析。
- 每天记录风险等级、启动的Agent、Review结果、未验证项和剩余风险。
- 每周第6天记录计划完成率、编辑反馈、返工原因和下周风险。
- 落后超过2个工作日时调整范围或里程碑，禁止默认占用每周唯一休息日。
