# 版本变更记录

采用语义化版本思路：重大不兼容变化为主版本，新增兼容功能为次版本，缺陷修复为修订版本。每个正式发布绑定Git标签、数据库备份和发布记录。

## Unreleased

### D68手机与平板响应式Cart Block候选（2026-09-08，未合并）

- 在D67重放提交`21f2941`之上把购物车样式改为Mobile First基础层＋既有75rem PC增强层：补`min-width:0`、长文本/通用错误安全断行、44px增减/Remove、128px数量选择器，以及空态/错误卡片；继续复用原生Cart Block DOM、Flex、容器查询和Store API。DentAll候选版本由0.36.0升至0.37.0，Core保持0.2.8。
- 相对D67只修改3个既有运行文件，65行新增/35行删除、净增30行；新增运行文件、函数、Hook、模板、JavaScript、查询、字段、插件和依赖均为0。`setup.php`只有既有Cart条件加载说明注释变化，加载生命周期仍为`is_cart()`。
- 全新独立Local副本最终198/198断言通过；覆盖Simple/Variation、六宽、1199/1200、长文本/缺图、loading/error/售罄/不可购买/空态、触控/键盘、Cross-sell图片及Page ID 8批准英文候选。英文验证后已恢复中文原哈希，全部购物车、商品/Variation及整库恢复；源Local只读快照不变，端口已停止。
- 独立功能发现保留1项D70/P2：原生`Add coupons`高度20px，功能可展开但未达到44px触控目标。D66的RSK-035/037/038未关闭，故D68/D67/M5不标Done；未合并、推送、部署，未改正式数据、URL、SEO输出、支付、物流或缓存配置。主题版本查询串会在未来部署时刷新相关资源缓存。详见[[笔记/Day68-手机与平板响应式购物车候选验证]]。

### D69 Header Cart与Mini Cart状态联动（2026-09-08，独立Local候选）

- `codex/day69-header-cart-sync`将DentAll候选版本升至0.36.0；仅在实际Block Cart页加载1个脚本，以公开`wc/store/cart`的商品key/quantity变化触发经典Header/Mini Cart fragment。继续使用Woo Session与服务端HTML，不新增模板、插件、接口、字段、第二Store、轮询或交易逻辑。
- fragment改为只替换`span.dentall-cart-content`，保留Storefront监听所在的`a.cart-contents`，结构缓存键从`_dentall_header_v1`升至`_dentall_header_v2`；既有Mini Cart显隐规则只增加`:focus-within`。Simple/Variable、匿名/Customer、非空/空态、鼠标/键盘/模拟触屏、四宽及按页资源作用域通过。
- 初审发现的BFCache误退订和本页旧fragment晚到覆盖均已修复。终态用localized精确端点、公开jQuery AJAX生命周期与Cart revision等待整个fragment批次落地；Store/fragment失败、HTTP 200无目标fragment、204、abort、快速连续变化及下一次真实变化恢复均有界验证，无自动无限重试。
- 终态JS为176行、4452字节，源码与隔离运行副本SHA-256均为`269AD1E247B6C4BD3A05F001D9757BC90442DBFB043588C86E989A42002C166B`。Code Review、安全、独立测试均为P0/P1=0；隔离数据库已恢复测试前59表基线、D69临时用户为0，10669监听为0。
- RSK-039/040继续作为期限性P2：Web Storage完全禁用时Woo 11.0不消费刷新事件；极端双标签错序且来源标签立即关闭时，剩余标签可能暂显旧Header。两者不改服务端Cart，导航/刷新或后续变化恢复；D72/W12合成及最晚非Local浏览器矩阵复审，不依赖Woo私有存储键或擅自增加fallback。
- 当前仅独立分支和隔离Local技术候选，未合并`main`、未推送或部署。D66的RSK-035/037/038、D67/D68/D70、W12、M5、正式内容、真实辅助技术、Production缓存/CWV仍独立待验。详见[[笔记/Day69-Header Cart与Mini Cart状态联动]]。

### D66集成、远端同步与商品闭环回归（2026-09-08，待缺陷处置）

- `278d20d`保留D60/D61/D63祖先并合入、推送main；完成工作树登记已清理，160份独有忽略证据私密归档，3个Windows占用空目录保留。D67候选未纳入，未部署非Local。
- 本轮在同一集成运行树执行商品发现、Simple/Variable、四端、键盘、SEO及恢复检查；测试方法纠偏、原始失败与真实缺陷分别记录。RSK-035仍开放，新增RSK-037记录原生相邻商品导航在768px遮挡Tabs，RSK-038记录连续长参数表格四端裁切；内容80/80为自动断言，长参数视觉QA未通过。
- 本轮运行文件、函数、CSS规则块、字段和行数净增均为0，主题/Core不升版本；原生配置关闭导航及最小Variable错误适配均为待确认候选。验收命令、报告、风险与影响见[[笔记/Day66-商品浏览闭环集成回归]]，不宣称D66/M5完成。

### D61原生变体选择与购买验证（2026-09-08）

- `codex/day61-variable-purchase`将DentAll升至0.35.0，Core保持0.2.8；4个既有运行文件修改并新增1个22行/720字节JavaScript，120行新增/12行删除、净+108行，净增2个PHP函数、1个Filter和5个CSS规则块。继续复用Woo/Storefront原生Variable表单、选择生命周期、动态价格/库存/媒体与服务端交易校验，只补动态图片`sizes`、数量标签、局部CSS和按钮`aria-disabled`的原生class映射；没有模板覆盖、自定义AJAX、新字段、插件或交易重写。
- 9份根报告合计581/581次断言、50张保留截图；独立测试另通过匿名12/12、Customer 2/2、无价Variation POST 2/2、行内Filter合同6/6、D12 17/17和D18 8/8。8组状态快照在新PHP进程中完全相等，最终审计17/17、原1条session、0订单/退款、14条pending，D61临时Customer及其凭据和AJAX标记为0；16062/16063监听与对应PHP/MySQL进程均为0。
- 自定义差分Code Review P0～P3=0；全任务另保留Woo原生AJAX pending或失败后暂留旧按钮状态及可见价格/库存的P2 `RSK-035`，但空`variation_id`的键盘POST已由服务端拒绝且cart为空。负责人为开发者，D66复审、最晚非Local部署前处理；当前不扩展为自建loading/error UX。重复合法POST累加未专项验证，不宣称幂等；未来Variation Gallery多图、Quick View或第三方调用须重验。
- 分支基线为`c99126d`，D61增量以独立提交保存（具体以Git日志为准），尚未合并`main`；`main`仍为`7220fe5`、`origin/main`仍为`ad6f26e`，未移动`main`、未推送或部署。下一步为D63只读梳理；正式内容、真实辅助技术、CWV、Variation Gallery多图和非Local仍待。详见[[笔记/Day61-原生变体选择与购买验证]]。

### D60商品详情代表内容回归与W10收口（2026-09-07）

- 按用户授权，仅在一次性隔离Local使用业务来源但未批准公开的代表材料；Simple/Variable初始、长英文、五图、缺图、售罄、无价格、一次Simple数量2加购及D64/D65边界共30页、30张截图。原始401/419经WooCommerce 11.0合同复核后为419/419，18项均为测试Oracle假阴性；17条响应式WEBP `ERR_ABORTED`对应图片全部完成解码。
- #44/#46/#51～#53完整Woo CRUD数据和modified、订单/退款0、pending actions 14及原session哈希均回到基线；10个附件/原图、69个派生缩略图、10个夹具副本、临时用户和凭据全部清理，16060/16061停止。预检硬编码临时口令P1、隔离配置P2及派生图清理P2均在隔离范围关闭；测试、安全与Code Review终审P0～P3=0。
- D60没有修改运行代码、版本、字段、URL/SEO逻辑、缓存、支付、物流或部署；DentAll保持0.34.0、DentAll Core保持0.2.8。业务材料、截图、数据库转储、SQL、Cookie和凭据未进入Git，`CONTENT_ASSET_REGISTER.md`未改。
- W10按Local商品详情技术v1收口；正式内容/批准公开素材、D61 Variation动态图片/价格/库存/组合/加购、实体设备/辅助技术、公开Canonical/富结果、Production缓存/CWV和非Local部署仍待。详见[[笔记/Day60-商品详情代表内容回归与W10收口]]。

### D59商品详情四端购买区与Sticky收口（2026-09-07）

- DentAll 0.34.0保留WooCommerce经典单品的一套DOM，在768～1199px只把Gallery与Summary改为内容区满宽上下文档流，1200px起恢复D55的约57%/39%双列；Tabs、Simple购买区和Variable初始表单继续使用原生输出。
- Gallery平板`sizes`同步为内容区全宽公式；五图缩略图在390px可收缩、从768px起每列封顶100px。3个既有运行文件共12行新增/8行删除、净增4行，0新运行文件/函数/Hook/模板/JavaScript/插件/字段/查询。
- 共享Local通过Storefront原生Theme Mod显式关闭Sticky Add-To-Cart，未写死到非Local代码。隔离Local的正常态247/247、多图144/144、缺图80/80、长文本88/88及数据恢复通过；Sticky DOM/脚本为0，1440推荐图片命中未受遮挡，独立Code Review无P0～P3。
- 首轮隔离`ABSPATH`重复定义warning属于夹具缺陷；修正后使用`*-final`证据重跑，旧attempt不作为终态。权威汇总后一次只读WP-CLI内联命令因Windows引号转换失败留下工具侧Fatal、无数据写入；无内联命令随后成功读回0.34.0与Sticky=false，隔离服务已关闭。未实施D61动态Variation、正式内容、实体设备/辅助技术、CWV、Staging/Production配置或部署。详见[[笔记/Day59-商品详情四端购买区与Sticky收口]]。

### D63原生扩展信息与公开资料空状态收口（2026-09-07）

- 用户批准第一版仅面向匿名公开补充资料；当前没有合格PDF，因此不输出下载入口、不开放Website Manager PDF权限。参数继续使用可见商品级属性，少量经批准认证/说明使用Description，购买后受控与内部文件不纳入本范围。
- 只读复核WooCommerce 11.0.0 Tab/属性输出条件、当前#44/#46保存HTML、D65同运行代码的四端与键盘证据、角色MIME/5MB限制及公开uploads直链；独立复核P0/P1/P2=0，保留异常taxonomy或第三方Filter可能制造空面板的理论P3。
- 运行层、字段、商品、媒体、角色、URL/SEO、缓存配置、交易与部署0变更，数据库业务/配置显式写入0；新增D63项目/学习笔记并同步状态、决策、风险、测试与索引。本轮未做当前数据库全表前后哈希；正式认证、PDF正向路径、失效撤回、实体设备和非Local仍待。

### D57基线与D58/D62/D64/D65主分支集成（2026-09-07）

- 先将已验收D57基线提交到`main`，再按`D58 → D62 → D64 → D65`顺序选取四个专项增量；人工合成共享的详情CSS、Storefront Hook、版本和状态文档，没有重复带入D57快照。
- 合成树为DentAll 0.33.0与DentAll Core 0.2.8。隔离Local重跑12页Schema/DOM、390/768/1024/1440推荐区与Simple数量2加购/notice/购物车清空；P0/P1/P2=0。当时确认的1440px原生Sticky局部遮图P3已由本页D59条目关闭Local配置并回归；公开验证与非Local部署仍待。
- `origin/main`、`deploy/staging`、Staging/Production和共享Local数据库均未修改；四个临时分支及其工作树在增量可达和证据保全后清理。

### D62文档收口（2026-09-07）

- 按用户批准的零新增字段、零运行代码范围完成原生字段缺口复核；纠正当前Local为免费ACF 6.8.7且停用的实物记录，保留ADR-010和CR-005边界；新增D62项目/学习笔记及双向索引。运行版本、数据库、插件状态和非Local部署未变，本次提交仅含D62文档增量。详见[[笔记/Day62-原生字段复核与零扩展收口]]。

### D64并行：原生关联商品与推荐空状态（已合入main）

- 用户明确批准隔离Local与可逆TEST；D64在D57快照`6ece0ce`之上形成单独增量，集成时仅选取该增量并纳入`main`。
- 分支DentAll 0.33.0通过一个数量Filter让详情Upsells最多3项，保留Related原生最多3项、全部Upsell排除、随机排序及空态；复用D29卡片与条件详情CSS形成1/2/3/3列，不扩展Cross-sells。
- 3个既有运行文件净增48行（PHP15、CSS33），0新运行文件/JS/模板/字段/插件/查询/缓存。独立动态首轮发现推荐区间距为0的P2，补局部margin后四宽均48px、overflow 0，P2关闭；上限/空态/标签/角色与11行浏览器矩阵通过，原生回调冷/暖查询与基线同为32/0。
- 隔离TEST验证、5对象快照业务字段恢复、6商品/2分类/1标签清理、最终独立复核及服务关闭完成；无未关闭P0/P1/P2。modified/缓存不作逐字节恢复，5页恢复态200、无横溢出，详情CSS仅两商品页加载。主分支集成后，D65已将商品两份BreadcrumbList收敛为Woo 1份；当时复现的原生Sticky局部遮图P3已由D59关闭Local配置并回归。正式内容与非Local部署仍独立验收，详见[[笔记/Day64-原生关联商品与推荐空状态]]。

### D65并行修复（主分支Local集成范围已完成）

- DentAll Core 0.2.8：经典商品实际渲染时保留Woo原生BreadcrumbList，移除Yoast重复节点及WebPage引用；Coming Soon和非商品输出责任不变。复用SEO模块净增40行/2函数，无新运行文件、前端资源、数据字段或交易变更；分享基础仅核对现有OG/Twitter。
- 隔离Local完成12页前后、4页退出分支169项断言、10项独立分支检查和两商品四端/键盘走查；源配置与25个含数据表保持不变。纳入`main`并与D58/D62/D64合成后，12页Schema/DOM、四端推荐区及Simple购物车链路复验通过；公开环境、在线验证器、真实辅助技术与Production缓存/CWV仍待。详见[[笔记/Day65-商品详情结构化数据与SEO边界]]。

### 新增

- 项目专属Codex Skill和`AGENTS.md`。
- 双休100日项目计划和Obsidian复盘模板。
- 四端首页效果图及响应式素材包。
- 项目背景、需求、架构、数据、SEO、变更、测试和发布文档。
- 单休编辑先行版96日计划及D6/D12/D18三阶段编辑验收门槛。
- 单休20周、120日项目计划及D1～D25编辑第一阶段验收体系。
- `DentAll Website Manager`角色版本5，覆盖文章、页面、媒体、评论、商品、术语、订单、优惠券、客户创建和WooCommerce报表等业务能力。
- D12 Local简单/可变商品原型、自动审计脚本及Staging双环境权限验收记录。
- D17 Staging五个代表商品样本矩阵，覆盖Simple、Variable、缺货Variation、多图及Yoast字段保存/输出；作为D18商品模型候选冻结输入。
- D18 M2商品模型候选冻结结论与W3周验收：冻结Simple/Variable、父子SKU、合法组合、库存真相源、物流继承/覆盖、图片、SEO及Website Manager职责边界，不把TEST值升级为正式业务事实。
- DentAll 0.2.0 Storefront子主题骨架：按职责拆分主题初始化与Storefront Hook，并在Primary/Handheld未分配菜单时关闭全部Page回退，避免未批准页面自动进入公共导航。
- DentAll 0.3.0 Design Token基础：在现有子主题单个`style.css`中增加63个`--dentall-*`变量和最小`body`排版/颜色映射；没有新增请求、依赖、媒体查询或组件样式，Local Coming Soon保护页/全局加载基线四端冒烟与独立Review通过。
- DentAll 0.3.1 Mobile First基础容器：以单个低权重`.col-full`规则应用1320px border-box外框上限、20px内侧gutter和自动居中；无媒体查询、新请求或依赖，独立CSS夹具、辅助小屏、Coming Soon加载回归与独立Review通过。
- DentAll 0.3.2 宽屏容器渐进增强：从`48rem`（默认16px浏览器初始字号时为768px）起将`.col-full`内侧gutter切换为32px；1320px继续作为border-box外框上限，1024/1200无布局变化因此不建立空断点。断点边界夹具、四端加载回归、登录态真实Shop四端验证，以及Home、Shop、Cart、My Account四页×四端真实DOM/截图/当前状态/日志和双重独立复核均通过，D27归因P0/P1为0；Cart仅覆盖空态、Account仅覆盖登录态，未外推交易或账户全流程。
- DentAll 0.4.0 正文排版基线：在`.site-main`内增加H1～H6层级、长文本换行和普通文本链接状态；标题直接子链接继承修复经独立测试/Review关闭，商品卡、Header/Footer和组件链接保持原边界。
- DentAll 0.5.0 基础控件与可访问状态：复用WooCommerce 11原生`useLabel`为Shop上下排序控件输出可见`Sort by`与唯一`for/id`；增加Classic/Blocks按钮、常用表单、Error、Disabled/Readonly/Loading展示，以及内容/普通Footer深蓝、Header/手机固定底栏白色的3px Focus。代表页面四端、真实键盘夹具、对比度、静态检查与独立Review通过，最终P0/P1/P2/P3为0；没有模板、JavaScript、数据或Staging/Production变更。
- DentAll 0.5.2 基础控件维护性收口：不改变0.5.0功能范围，将按钮状态、Focus目标与字段规则按真实权重重新归并，继续只保留一个子主题CSS请求；精简后为13168字节、443行、33/33个花括号块，未引入预处理器、依赖或新运行资源。
- DentAll 0.7.0 三类卡片组件v1：ProductCard复用WooCommerce经典商品循环，CategoryCard复用原生分类DOM，SolutionCard冻结合法单链接结构；5/4/4非持久化TEST状态、真实Shop四端、静态语义/资源/对比度与独立Review完成，已发现P0/P1为0。真实分类/Page数据仍留D39/D40，Shop页面网格留D44；没有PHP、模板覆盖、JavaScript、路由、数据库或Staging/Production变更。
- DentAll 0.8.6 Design System v1：增加`.dentall-section`、显式响应式Grid、Loading/Empty状态及Classic/Blocks通知视觉，并通过DevTools关闭Shop排序、Cart数量与删除按钮、Header/Checkout Focus、Checkout复合Select和商品Tabs/默认链接问题。390/768/1024/1440px周验收通过，测试范围P0/P1/P2/P3为0；仍为单一子主题CSS，没有新增运行文件、依赖、持久化或Staging/Production变更。
- DentAll 0.9.0 PC Header最小技术版：在既有Storefront壳层中完成Local三条TEST公告、透明占位Logo、搜索、Account与原生Cart主行，并把Header/Footer领域样式拆入`site-shell.css`；五类页面四端结构与1440键盘Focus完成取证。币种/语言/Help仍为非交互位置槽，正式Logo、多语言/多币种、真实搜索与非空Mini Cart未实现。
- DentAll 0.10.0 PC主导航与一级下拉：复用Storefront唯一Primary菜单DOM和原生`navigation.js`，绑定Local `TEST D32 PC Navigation`，在`>=1200px`输出分类入口、一级导航和两组原生下拉；键盘、边界和五类页面回归通过。未新增PHP、JavaScript、模板或插件，手机/平板与正式URL按D33/D34及内容节点接续。
- DentAll 0.11.10 手机与平板竖屏Header：继续复用同一Primary DOM，完成手机/768 Logo居中、左侧Menu、右侧Account/动态Cart、非模态常流面板、一级子项常显和搜索视觉收敛；经典fragment数量与缓存迁移完成，无新增JS。完整键盘链与四类页面批量回归由D34补证，Cart Blocks同页同步转D69，非Local未部署。
- DentAll 0.12.0 四端Header断点收口：继续复用Storefront唯一Primary DOM和原生`navigation.js`，1024～1199保留紧凑菜单，1200起显示完整PC导航；同时关闭隐藏子菜单重新进入交互层与Reduced Motion覆盖不完整问题。390～1440关键边界、390/768/1024 Enter链、Skip Link实际激活、强制Reduced Motion浏览器夹具、五类页面20/20、静态/HTTP及独立Review已通过；无新增JavaScript、插件、模板、请求或数据写入，用户于2026-08-28确认真实物理触屏、方向切换与系统级Reduced Motion实体设备验收通过，仅Local实施。
- DentAll 0.13.2 PC Footer与Newsletter测试壳层：新增独立`site-footer.php`模块，使用Storefront公开Action注册一个深度2且无Page回退的Footer菜单位置，输出动态品牌/版权和未绑定Local TEST空状态；Newsletter仅在Local输出0表单、2个禁用控件的明确不可提交预览。Day35 Footer未输出社交或支付内容，390～1440七个宽度0溢出、390五类页面5/5、静态/HTTP与独立代码复核通过；独立测试发现匿名WooCommerce Coming Soon模板仍有默认LinkedIn、Instagram、Facebook链接，作为范围外环境P2登记并须在匿名预发布页开放前单独处理。正式菜单绑定、填充态视觉及手机/平板收口转D36，Staging/Production未改变。
- DentAll 0.14.1 Footer原生菜单绑定与四端静态重排：Administrator在Local创建并把9项`TEST D36 Footer Navigation`绑定至Footer，Primary继续使用term 25；现有`site-shell.css`让同一菜单DOM在390为单列、768为四列、1024起为五列、1200起与品牌并排，并补充当前项2px下划线与普通链接装饰基线。未新增运行文件、PHP函数、JavaScript、Walker、模板覆盖、插件或依赖；Newsletter继续为0表单/2禁用控件，正式内容、社交、支付和非Local部署未改变。
- DentAll 0.15.3 PC Homepage Hero与原生首页路由：新增职责独立的`inc/homepage.php`和条件加载的`assets/css/homepage.css`，通过Storefront `homepage` Action输出Home核心区块与响应式特色图，并移除未验收的父主题示例商品区块及已失去用途的Homepage脚本。Administrator在Local把Home设为静态首页、Blog设为文章页；根URL、`/blog/`及390/768/1024/1440px安全回归通过，P0/P1为0。当前1536×1024实色WebP只作Local占位，正式透明前景素材、D38移动视觉精调与非Local部署未完成。
- DentAll 0.16.0 手机与平板Homepage Hero精调：继续复用同一语义DOM和WordPress响应式特色图，以Mobile First Grid叠层完成390/768/1024/1440布局；按用户决定在390/768隐藏三项辅助卖点、1024/1440恢复三列，并同步`sizes`、缺图/无正文状态和1200起媒体高度。四端无横向溢出，Shop/Blog资源隔离、静态检查及独立设计/代码/测试复核通过；仅保留1283～1319px图片候选可能轻微过取的性能P3。未新增运行文件、JavaScript、字段、插件、数据或非Local变更。
- DentAll 0.17.0 Homepage精选分类入口：新增原生`Homepage categories`菜单位置，以菜单选择/排序真实顶级非空`product_cat`，并复用WooCommerce原生CategoryCard、缺图和链接输出。总分类数不限，390/768/1024/1440按3/5/9/9行容量自动换行并居中末行；0/1/9/10项、Woo前台可见count、长标题、Focus、循环状态恢复和真实menu ID 27均通过Local及独立复核，最终P0/P1/P2/P3=0。无模板覆盖、JavaScript、插件、字段、交易数据或非Local变更。
- DentAll 0.18.0 Homepage方案Page映射：为Page启用核心Excerpt并新增原生`Homepage solutions`菜单位置；菜单只选择/排序，代码批量读取真实已发布且无密码Page，过滤重复、非Page、空标题和失效链接后最多输出4张既有SolutionCard。Local menu ID 28绑定4个逐页noindex且不进Sitemap的TEST Page；空Excerpt不回退正文，缺图使用text-only，首个有效项自动featured，未输出`View all`或`/solutions/`。代码、SEO、0/1/4/4+边界及独立Review完成；真实首页四宽截图、Focus与间距量测因Chrome控制超时登记为D42 P3。无模板覆盖、JavaScript、插件、CPT、ACF、交易数据或非Local变更。
- DentAll 0.19.0 Homepage累计热卖与Trust预览：在Storefront `homepage`优先级40/50按WooCommerce累计`total_sales`和前台可见性读取最多5个真实商品，并复用原生ProductCard；真实0销量时整区隐藏，排序URL与评分参数隔离，临时排序Filter及Woo循环全局精确恢复。五项设计稿Trust数字/文案与五枚SVG图标仅在Local输出，非Local为空；纯CSS横滑完成四端1/3/4/5卡容量和768键盘访问。只读审计15/15及三路独立Review最终P0/P1/P2=0；没有订单/销量写入、JavaScript、模板覆盖、插件、字段或非Local部署，真实订单、正式指标与D42整页集成仍待。
- M4 Homepage与全局框架Local技术v1验收：不提升DentAll 0.19.0，在登录态真实Homepage完成390/768/1024/1440的Header、Hero、Categories、Solutions、0销量Best Sellers空状态、Local-only Trust、禁用Newsletter与Footer整链路校准；四端页面级横向溢出0，Console为0，7项主题资源HTTP 200，主题PHP与D41只读审计15/15通过。独立复核未发现P0/P1或阻塞M4的P2，运行代码净改动0且未创建TEST订单；正式内容、Trust事实、匿名预发布、非Local部署与生产性能不在该通过口径内。
- DentAll 0.20.0 商品归档骨架：只在Shop与商品taxonomy条件加载`catalog.css`，复用WooCommerce原生Archive Header、主查询、商品循环与D29 ProductCard，建立标题区和列表上下节奏；产品搜索页因`is_search()`明确排除并留D47接入。Local原生Shop Page公开标题由“商店”改为`Products`，slug、URL和Canonical保持`/shop/`。390/768/1024/1440正常态、TEST分类与空分类、`price-desc`、资源/Console及独立复核通过，最终P0/P1/P2/P3为0；没有模板覆盖、自定义查询、JavaScript、插件、字段、商品/订单写入或非Local部署，D44～D49职责未提前实现。
- DentAll 0.21.0 商品归档响应式网格：在D43既有`catalog.css`内用Mobile First CSS Grid把Shop与商品taxonomy的原生商品列表渐进为390/768/1024/1440的2/2/3/4列，间距为16/24/24/24px；最小解除Storefront浮动宽度、margin与clearfix残留，不改模板或ProductCard内部。Woo目录列/行仍为3×4，即每页12项；正常/空taxonomy、搜索资源隔离、320边界、真实2项部分行及三路独立复核通过，最终P0/P1/P2/P3为0。真实1/5/12项、D29特殊状态整合、最终Console/独立CSS HTTP、生产缓存/CWV与非Local部署未验；没有PHP函数、查询、JavaScript、插件、字段或数据库写入。
- DentAll 0.22.0 商品排序与结果信息：复用WooCommerce原生GET排序、主查询、结果状态与自动提交，在`wp`主查询完成后仅把Shop和商品taxonomy收敛为一组顶部工具栏；320～1024视觉隐藏结果数但保留辅助技术状态，1440为结果数左、排序右，底部只保留D46分页位置。五宽、升降价/非法值/参数保留、正常/空taxonomy、商品搜索隔离、Canonical及三路独立复核通过，最终P0/P1/P2/P3为0；未改12项/页、Grid、查询、分页、搜索样式、筛选、模板、JavaScript或数据，Staging/Production未部署。
- DentAll 0.23.0 商品归档分页与URL归一化：继续使用WooCommerce每页12项的原生主查询、分页模板和WordPress `paginate_links()`，Shop与商品taxonomy只保留一组底部导航；页码窗口为首尾1页、当前左右2页，交互目标至少44×44px并可换行。Local用11个临时Simple商品形成真实12/1两页，完成320/390/768/1024/1440、Focus、排序参数、Page 1直链、Canonical/rel、越界404、空taxonomy与搜索隔离验证；Page 1内部链接多一次301的P2已修复，三路终审P0/P1/P2=0。#120～#130已精确核对后移入回收站，发布商品与分类恢复2项；未改主查询、Grid、搜索、筛选、模板、JavaScript、插件或非Local环境，未提交Git。
- DentAll 0.24.0 商品搜索请求与边界状态：明确的`post_type=product`搜索复用WooCommerce原生标题、面包屑、主查询、ProductCard、D44 Grid、顶部结果/排序和D46底部分页；无结果保留原生状态并追加Shop/Home恢复链接。空值、Unicode纯空白、非标量及WordPress加斜杠前后任一超过1600字节的关键词302到动态Shop，唯一命中保留Woo原生302；搜索保持`noindex, follow`、无Canonical/rel且不进Sitemap。Local恢复#120～#130形成真实12/1搜索分页后已全部重新Trash，发布商品/分类恢复2项；无模板、JS、插件、第二查询或非Local部署。HTTP、DOM、SEO、转义、数据和Shop/taxonomy回归通过，登录态有结果页390/768/1024/1440按2/2/3/4列、0横溢出、44px和唯一工具栏通过；真实空结果CTA、Focus、Console与截图已在Day48实施前补齐，原P2关闭。
- DentAll 0.25.0 商品分类内容与W8列表回归：先用真实登录态商品搜索空结果页补齐390/768/1024/1440、双CTA、44px、Focus、Console与截图，关闭D47最终P2；再用既有Local TEST分类#18临时验证长标题、两段描述、安全链接、长token及Yoast内容级Title/Meta覆盖。390px实测发现Grid item自动最小宽度导致内部裁切，`catalog.css`仅增加两个`min-width:0`和一个`overflow-wrap:anywhere`后四端复验通过。Yoast商品分类全局Title/Social Title模板已删除`Archives`，其他标题设置不变；#18及其Yoast term数据已精确恢复，#120～#130未恢复，未创建正式分类。功能、数据、SEO、空分类与静态/独立复核无P0/P1；最终0.25.0恢复态Shop、有结果搜索和短内容#18尚未完整重跑四宽，作为证据P2转D49实施前关闭，不恢复#120～#130。运行层净增0文件、0函数、0规则块、0查询/JS/插件，正式内容与非Local配置重放仍待。
- Day49商品筛选合同与查询表Local基线：先用12张0.25.0登录态四端截图关闭Day48最终证据P2；冻结Shop/商品分类的分类、价格、Size、Shade商品级参数与父商品Variation语义。WooCommerce属性查询表完整重建后启用，7行/2父商品，Direct Updates开启、Optimized Updates关闭；11个主查询场景与商品/Variation/Trash审计通过。参数页目标为`noindex, follow`且Canonical回基础归档；当前Local Canonical已符合、robots仍为`index, follow`，D50首次输出筛选链接前补齐。未新增运行代码、UI、品牌、评分、插件或非Local变更，DentAll保持0.25.0。
- DentAll 0.26.0 PC商品筛选：仅在Local的Shop/商品分类输出单一Categories、Price、Size、Shade筛选DOM，并只在`>=1200px`显示240px常驻侧栏；属性复用WooCommerce Layered Nav，价格使用无JavaScript的Min/Max＋Apply，商品结果继续来自D49主查询与lookup。集中白名单覆盖分类、属性、价格、排序和最终分页链接；空值/非法价格302归一化，合法反向区间保留可访问错误。390/768/1024/1440、1199/1200、组合/空态、键盘、URL和数据不变量通过，无品牌、评分、计数、Chips、Reset、移动抽屉、插件、商品数据或非Local变更。
- DentAll 0.27.0 手机与平板商品筛选抽屉：仅在Local为小于1200px的Shop/商品分类提供唯一`Filter`入口，以原生`dialog`承载并移动D50同一Categories、Price、Size、Shade筛选aside；1200px起恢复240px常驻侧栏。关闭按钮、遮罩、Escape、焦点进入/返回、页面滚动锁、方向/断点及BFCache恢复通过，反向价格错误自动打开并定位字段；商品搜索不输出筛选DOM且不加载脚本。新增1个4077字节条件JS请求，不新增查询、参数、模板、插件、依赖、数据写入或非Local变更。
- DentAll 0.28.0 原生品牌数据与筛选基线：仅在Local复用WooCommerce 11.0.0原生`product_brand`，冻结扁平term、每商品最多一个主要品牌、无品牌留空、角色、原生CSV、默认`/brand/`及第一版品牌归档`noindex`合同；在Shop/商品分类的D50/D51同一aside中加入原生文字品牌筛选。优先级1输入护栏关闭Woo数组Fatal，并隔离商品搜索/其他taxonomy；链接仅传播白名单，选中项具备nofollow、可见勾选、`aria-current`和移除说明。清理前2个关联TEST品牌下19/19审计与四端/组合/SEO通过，清理后term/关系/transient为0、旧URL404、商品Schema无品牌；净增146行/4函数、0新运行文件/规则/JS/插件，真实规模与非Local缓存另验。
- DentAll 0.29.0 已选条件、动态计数与重置：用户确认首版预计30个有效品牌，仅在Local保留完整文字列表而不增加搜索/折叠。Shop/商品分类统一显示价格、Size、Shade与Brand已选条件，价格上下限合并为一个Chip；逐项移除和`Clear filters`保留当前分类与合法排序并回第一页。Size、Shade和Brand计数补齐价格及其他筛选维度的父商品语义，缺货隐藏为`no`时继续计入目录可见缺货父商品；公开筛选GET在`pre_get_posts`优先级1归一化，非法/非规范参数302到干净归档，商品搜索隔离。30品牌/30商品夹具下16场景、四端2/2/3/4列、44px、Dialog/焦点/History/错误态、冷最多3/暖0条计数查询及品牌递归子项查询0均通过；代码/安全终审P0=P1=P2=0。运行源码净+383行/+12745字节、0个新运行文件，JavaScript不变，无AJAX、插件、自定义缓存或第二商品结果查询，非Local未部署。
- Day54商品发现全链路与W9 Local技术收口：不提升主题/插件版本且不修改运行代码，在恢复态2商品/0品牌上重跑Shop、商品分类、商品搜索、排序、请求内1项/页分页、Price/Size/Shade、已选/清除、正常/零结果/错误、7类非规范GET、390/768/1024/1440及1199/1200交互、robots/Canonical/Sitemap与分面缓存。14张截图及独立静态/Test/UX复核P0=P1=P2=0；D53的30品牌、计数SQL、lookup回退和清理护栏按版本/指纹一致复用，未冒充D54新鲜证据。最终配置、商品、Trash、lookup和transient精确恢复；3项P3、真实设备/辅助技术、正式内容、非Local缓存/部署及稳定Git基线继续待处理。
- DentAll 0.30.0 商品详情字段与PC骨架：冻结WooCommerce经典单品模板、原生字段与三组核心Action责任；只在`is_product()`成立时条件加载407字节`product-detail.css`，并在1200px起以两条`width`把Storefront全宽商品页调整为约56.5% Gallery、4.35% gutter、39.1% Summary。#44 Simple、#46 Variable、390/768/1024/1440、1199/1200、Shop资源隔离、静态/HTTP/Console及三路独立复核通过；减法审查删除2条重复物理方向margin。没有字段、模板覆盖、插件、JavaScript、Buy Now、Wishlist、Hook重排、数据或非Local变更；768堆叠转D59，Sale flash对齐转D56/D57。
- DentAll 0.31.0 商品图库与响应式图片：复用WooCommerce/Storefront原生Gallery、FlexSlider、Zoom与PhotoSwipe，在既有详情CSS中建立单图、多图和缺图共用的方形画布、`contain`图片、响应式五列缩略图及44px灯箱入口；四参数Filter修正初始Gallery的`sizes`，全新1440请求由过小416px候选改取768px候选。#44通过Woo CRUD完成5图、缺图及新进程精确恢复，#46、六宽、缩略图、Zoom、键盘灯箱、Shop资源隔离、SEO/日志/静态检查和独立复核通过，最终P0～P3=0。新增0运行文件、1函数、1 Filter、13个CSS规则块；未新增模板、JS、插件、字段或查询，未实现网络失败替换、移动精确圆点和Variation动态图优化，未部署非Local。
- DentAll 0.32.0 商品基础信息与原生品牌输出：继续复用WooCommerce经典单品标题、评分、Regular/Sale/Variation价格、短描述、库存、SKU、分类与Meta，在既有详情CSS中建立信息层级并把原生Sale标签移入Gallery，关闭摘要顶线P3。子主题只移除Storefront优先级4的重复品牌缩略图，保留`product_meta`文字品牌与Product Schema。#44/#46、390/768/1024/1199/1200/1440、资源隔离、数据/URL/SEO不变量和三路专项复核通过；Woo 11促销HTML的`screen-reader-text`使相邻兄弟规则失效，已改用局部`del ~ ins`。最终4个既有运行文件净+120行、1函数、1 Action、16个CSS规则块；无字段、模板、JS、插件、查询、数据或购买逻辑变更，正向评分/品牌实页和非Local仍待。

- DentAll 0.33.0 简单商品购买区与隔离购物车验证：复用WooCommerce经典Simple POST、数量、库存、购物车与notice，在既有详情CSS增加4个局部规则，并以1个展示Filter让当前Simple主商品使用可翻译的`Quantity`标签、保留`Product quantity`可访问名称。独立文件/数据库副本完成16项交易矩阵、15个匿名cart清理、五商品精确恢复、六宽、键盘、异常状态、Variable/Shop回归及三路终审，最终P0～P3=0。运行层0新文件、1函数、1 Filter、净+53行；没有模板、JavaScript、插件、查询、数据字段或订单逻辑变更，共享Local及非Local未同步。

### 修改

- 项目排期从单休基线调整为20周双休基线。
- 当前有效排期由20周双休调整为16周单休，并将商品、文章和页面编辑流程前置到W1-W3。
- 当前有效排期由16周单休调整为20周单休，对外周期为4.5～5个月；编辑第一阶段延长到D25。
- 当前两名网站人员统一使用独立Website Manager账号；低权限Content Editor保留为未来可选角色，不纳入D12当前人员验收。
- D12 TEST对象保留为D13及下周回归夹具，D25前再次复核归档或清理。
- DentAll Core 0.2.2按角色、媒体、商品治理和后台访问拆分内部模块，保持既有函数、Hook、权限和运行行为不变；Local验证完成，尚未部署Staging。
- DentAll Core 0.2.3新增独立SEO兼容模块，修复Yoast启用时WordPress Block Template重复输出Title；Yoast停用时保留WordPress核心Title回退。已完成Local验证并部署Staging，五页矩阵与D17代表商品SEO输出通过受保护环境边界检查。
- DentAll Core 0.2.4将角色定义升级为版本6，重新同步Website Manager既有高级SEO元数据能力，并在商品编辑页隐藏WordPress原始自定义字段面板；Local与Staging均已复测通过。
- DentAll Core 0.2.5允许Website Manager使用WooCommerce原生商品CSV导出；`export`只在商品列表、商品导出页面、对应AJAX与下载请求中临时生效，不写入角色数据库，也不开放WordPress全站内容导出。Local 5行与Staging 10行商品CSV均已验证；Staging通过`e9e21c4`部署并完成D18 C6关键路径复测。
- DentAll Core 0.2.6按ADR-029/CR-010为Website Manager持久增加WordPress全局`import`，角色版本提升为7，以使用WooCommerce原生商品CSV导入器；商品`export`仍保持请求级授权，自定义商品导入草稿继续不由主入口加载。Local权限审计及Staging部署已通过；Staging另在既有媒体白名单中最小增加`csv => text/csv`，Simple模板v1的首次2行Draft导入、重复SKU跳过、普通恢复和创建者追溯均已验证。
- DentAll Core 0.2.7在既有SEO兼容模块增加Shop/商品分类筛选参数页robots规则：任意价格、`filter_*`或`query_type_*`键均为`noindex, follow`，同时保留Yoast基础归档Canonical。仅Local实施；Sitemap、普通排序、未知非筛选参数和商品搜索既有合同不变，非Local缓存/抓取未验。
- 现有DentAll Starter主题已转换为Storefront子主题；资源加载复用Storefront原生顺序，不重复注册子主题样式。D26仅完成Local骨架与运行验证，未进入视觉还原或Staging部署。
- Local WooCommerce商品属性查询表从“已存在但禁用”改为“完整重建后启用”；`woocommerce_attribute_lookup_direct_updates`由`no`改为`yes`，`woocommerce_attribute_lookup_optimized_updates`继续为`no`。该数据库配置未同步Staging/Production；回滚时禁用查询表并把Direct Updates恢复`no`。

### 删除

- 删除旧Starter的`header.php`、`footer.php`、`front-page.php`和`index.php`，解除其对Storefront模板继承与WooCommerce展示基线的阻断。

### 安全

- 规定生产密钥不进入Git；Staging必须禁止索引并使用支付沙盒。
- Website Manager继续禁止WordPress用户、插件、主题、代码和系统设置；Site Kit未来使用只读Dashboard Sharing，GTM在Google平台单独授权。
- Website Manager的`wpseo_edit_advanced_metadata`属于Yoast整组高级元数据能力，除Canonical和robots外还可能包含advanced robots、Breadcrumbs Title等字段；高影响修改继续执行旧值、新值、原因、受影响URL、复核人与页面回归记录。
- 商品CSV包含价格、库存、描述与素材URL，按业务数据文件管理；0.2.5仍拒绝Website Manager访问WordPress全站导出。中文WooCommerce CSV存在Upsells/Cross-sells均显示为“交叉销售”的重复表头，D25无损回导前必须规范化或在隔离环境验证。
- 角色能力会持久化到WordPress数据库；若需撤销Website Manager高级SEO能力，必须从角色白名单移除并提升新的单调递增角色版本。普通代码降级不能替代撤权，紧急角色对象撤权也必须在同一发布窗口补上版本化修复。
- WordPress全局`import`可被所有已注册导入器检查，WooCommerce原生商品导入也允许操作者勾选更新已有商品。当前只开放已实写验证的Simple模板v1，通过独立账号、新SKU、`Published=-1`、更新框未勾选、小批量、导入前商品导出/应用备份、完成页`Updated=0`和批次登记管理风险；Variable/Variation CSV、更新已有商品和Production导入未开放，也不把SOP描述为系统硬锁或完整活动审计。

### 修复

- DentAll Core 0.2.1隐藏并拦截Website Manager和Content Editor无业务内容的Tools入口。
- DentAll Core 0.2.4修复Local数据库角色版本未同步`wpseo_edit_advanced_metadata`的问题，并对Website Manager隐藏商品原始自定义字段面板，降低误改`total_sales`等技术元数据的风险；该界面防护不替代服务端capability和WooCommerce CRUD边界。
- 商品筛选主查询回调在调用`WC_Query`静态方法前检查类是否存在，避免WooCommerce停用或未加载时触发Fatal；DentAll Core `readme.txt`的`Stable tag`和Changelog同步对齐既有0.2.7。跳过WooCommerce的动态冒烟、正常商品搜索、恢复态品牌审计与独立复核均通过。

## 发布模板

## [版本号] - YYYY-MM-DD

### 新增

### 修改

### 修复

### 删除

### 安全

### 数据库/迁移

### 已知问题

### 发布证据

- Git标签：
- 数据库备份：
- uploads快照：
- 测试报告：
- 回滚说明：
