# 版本变更记录

采用语义化版本思路：重大不兼容变化为主版本，新增兼容功能为次版本，缺陷修复为修订版本。每个正式发布绑定Git标签、数据库备份和发布记录。

## Unreleased

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
- 现有DentAll Starter主题已转换为Storefront子主题；资源加载复用Storefront原生顺序，不重复注册子主题样式。D26仅完成Local骨架与运行验证，未进入视觉还原或Staging部署。

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
