# 项目当前状态

> 本文件是新对话和每日工作的当前事实入口。每天收工更新；历史细节进入每日Obsidian笔记和版本记录。

## 状态快照

- 更新日期：2026-08-31。
- 当前阶段：W7/D42与M4 Local技术v1已完成。登录态真实Homepage在390/768/1024/1440完成Header、Hero、Categories、Solutions、真实0销量Best Sellers诚实空输出、Local-only Trust、禁用Newsletter和Footer整链路校准；四端`scrollWidth === clientWidth`，Console warning/error为0，7项主题资源HTTP 200，主题PHP lint与D41只读审计15/15通过。独立设计/测试复核未发现P0/P1或阻塞M4的P2，因此Day42运行代码净改动0、DentAll保持0.19.0，且按用户边界未创建TEST订单或修改销量。M4通过只代表Local技术骨架，不等于正式内容/素材、Trust事实、匿名Coming Soon、Staging/Production、生产缓存/性能或交易流程完成。下一检查点为D43商品归档信息架构只读梳理；Cart Blocks同步、正式Logo/菜单URL、真实搜索、非空Mini Cart、WPML/WCML和M3治理门槛均保持原边界。
- 当前计划：单休20周编辑先行版，120个工作日，自然周期约4.6个月，对外按4.5～5个月管理。
- 当前里程碑：M1技术预验收已在D6通过，Website Manager培训者预演已在D13通过；D18 M2商品模型候选冻结通过。D25商品、文章和Page当前技术/人员路径已通过，但正式内容/素材与公司Git治理未完成，因此M3继续按既有边界管理。D42 M4已按用户指定的Local技术v1口径通过：首页、页头和页脚四端骨架完成，不等于正式品牌内容、非Local部署或Production上线。WM-A可在受保护Staging按批准SOP开始“Simple模板v1、小批次、只新增Draft”的商品录入；Variable/Variation CSV仍待独立验收。
- 当前状态：Cloudways Flexible已从试用升级为Full Access；受保护Staging、HTTPS、禁止索引、支付关闭边界、恢复入口及凭据轮换均已验证。
- 当前版本：DentAll Core Local/Staging均已包含角色版本7及Website Manager全局`import`，并保留WooCommerce商品请求级`export`；Staging部署提交为`501e5e5`。Local为WordPress 7.0.4、WooCommerce 11.0.0、Yoast 28.2、Storefront 4.6.2和DentAll子主题0.19.0；Local活动主题为DentAll，父主题为Storefront。子主题继续复用`style.css`基础层、`site-shell.css`壳层、`inc/site-footer.php`以及职责独立的`inc/homepage.php`/条件`homepage.css`，仍无模板覆盖、自定义JavaScript或构建链。Homepage顺序为D38 Hero、D39 Categories、D40 Solutions、D41条件Best Sellers与Local-only Trust；D42只做整链路验收，没有新增运行代码或提升版本。Best使用Woo popularity lookup排序、可见性和原生循环，Trust使用一个1334字节五符号SVG sprite。Home/Blog Reading路由已核验。Primary绑定term ID 25，Footer绑定9项TEST菜单term ID 26，Homepage Categories绑定menu ID 27并选中term ID 18，Homepage Solutions绑定menu ID 28并选中Page 106/108/110/112；Website Manager可维护Page但没有`edit_theme_options`，菜单继续由Administrator维护。动态Cart与经典fragment边界不变，Cart Blocks同页事件桥接未实现。匿名HTTP仍受Coming Soon保护，不能替代登录态DOM；登录态真实首页当前显示Trust 5项但Best因0销量不输出。2026-08-22 Staging后台显示WordPress 7.1与Storefront，DentAll子主题尚未部署或激活，Staging其余插件版本未重新逐项核验。两个自定义商品导入草稿模块继续不由主入口加载，也未进入部署树。
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
- 品牌实现方案；Solutions的Page优先方向已确认，待确认的是正式内容范围、英文标题/Slug、媒体授权、相关商品和导航位置。
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
- [ ] D27延期P2已登记但不阻塞收口：Home/Shop/Account的390固定手持底栏最终组件已由D31～D34收口；四页768/1024 Header已由D31～D34收口；Shop排序控件可见标签与基础Focus已于D28关闭。`lang="en-US"`下Home技术页已于D37改为明确TEST英文Hero，正式英语业务文案仍待内容验收；Shop、Cart、Account的中文/中英文混排继续在D43/D68/D81对应页面关闭。Cart专用空态CTA及最终视觉转D68。任何配置或内容修改仍需另行授权。

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

## 现有设计素材冻结v1

- [x] 按用户最终范围只冻结已选用的现有素材：`design-assets/final-versions/v1/`含64个视觉副本，总计69,106,251字节，64个SHA-256均唯一且与来源一致；GPT新页面输出为0，全部未批准公开。
- [x] 已撤销缺页、About、Solutions、FAQ和文章内容的生成计划与项目内候选副本；后续仅在对应开发日出现明确视觉证据缺口时另行说明并申请生成授权。
- [x] 冻结目录只用于本机开发选稿和版本追踪，当前被Git忽略，不等于团队备份；没有修改WordPress运行代码、数据库、URL、SEO、缓存、支付、物流或部署配置。

## 下一步三个验收结果

1. 进入D43时先只读梳理商品归档信息架构、WooCommerce模板层级、查询职责、URL/SEO边界和D44网格依赖；按每日规则提交最多3项验收结果，等待用户确认后再实施。
2. 继续等待业务证明并批准Trust五项数字和支付陈述，同时提供正式Solutions/Page、分类、Hero、Logo、商品内容和素材授权；确认前保持Local TEST/noindex，不迁移到Staging/Production或正式Schema/营销内容。
3. 公司控制Git远程、Variable/Variation CSV、匿名Coming Soon默认社交链接、正式Newsletter/SMTP、支付、物流、税费及非Local部署继续按各自功能确认和里程碑处理，不从M4 Local技术v1自动推断完成。

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
- 1024首页图属于推导证据，AI稿中的业务数字和功能不得固化；用户已冻结1320px为容器外框宽，现有Google字体依赖及浅边框用途仍须在后续浏览器和可访问性证据中复核。
- WooCommerce Coming Soon仍遮蔽匿名真实商城内容，且其独立模板保留LinkedIn、Instagram、Facebook默认链接（D35环境P2）；未获得单独页面配置授权前不修改，最晚在匿名Staging或Production预发布页开放前处理并复测。C7已通过登录态Chrome补齐Home、Shop、Cart空态和Account登录态的四端公共基线，但非空Cart、错误/Notice、售罄及账户登录注册等状态仍须在对应工作日安全验证，不得为制造证据擅自关闭保护、索引或修改数据库。
- D31当前语言/币种/Help只是无交互槽位；WPML/WCML虽已在早期评估中冻结候选方向，但未安装或验证。未来接入会新增URL、SEO、缓存、购物车金额与插件生命周期风险，必须重新走功能确认和代表状态验收。
- D33已关闭Cart Blocks页无Header移动入口、折叠Search越界、1×1 submit可见Focus、经典fragment动态数量及旧浏览器fragment覆盖；搜索实际提交/结果、Cart Blocks同页Store API改量后的Header即时同步、非空Mini Cart、非Local/正式Custom Logo仍分别按D47/D69/素材与部署节点验收。不能用经典fragment通过推断Blocks事件已互通。
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

## 更新规则

- 每天记录完成、验证证据、阻塞和明日第一件事；实际工时由用户按需记录，出现延期时再用于偏差分析。
- 每天记录风险等级、启动的Agent、Review结果、未验证项和剩余风险。
- 每周第6天记录计划完成率、编辑反馈、返工原因和下周风险。
- 落后超过2个工作日时调整范围或里程碑，禁止默认占用每周唯一休息日。
