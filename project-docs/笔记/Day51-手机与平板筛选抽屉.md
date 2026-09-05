---
项目: DentAll WooCommerce
工作日: D51
计划检查点: D51（不自动等于一个完整实际工作日）
日期: 2026-09-03
实际有效工时: 未记录；不使用计划工时代替
验收层级: Local手机与平板商品筛选抽屉
状态: 已完成（仅Local确认范围）
tags:
  - DentAll
  - Day51
  - WooCommerce
  - ProductFiltering
  - Accessibility
---

# DentAll 每日复盘 D51：手机与平板筛选抽屉

## 相关笔记

- 每日笔记索引：[[README|DentAll每日笔记索引]]
- 前置项目笔记：[[Day50-PC商品筛选与参数页索引收口]]
- 当日学习笔记：[[WordPress实战笔记/Day51-原生Dialog与单一筛选DOM]]
- 前置学习笔记：[[WordPress实战笔记/Day50-WooCommerce链接式筛选与参数治理]]
- URL与SEO合同：[[../URL_SEO_MAP|URL与SEO映射]]
- 决策：[[../DECISIONS#ADR-032：窄屏使用原生Dialog承载同一筛选DOM|ADR-032]]
- 后续项目笔记：[[Day52-品牌数据与筛选基线]]
- 后续学习笔记：[[WordPress实战笔记/Day52-WooCommerce原生品牌taxonomy与筛选URL]]

> [!check] 当前结论
> Day51已按用户确认范围仅在Local完成。DentAll子主题0.27.0在小于1200px的Shop与商品分类提供`Filter`入口，以原生`<dialog>`承载D50同一份Categories、Price、Size、Shade筛选aside；1200px起恢复原240px常驻侧栏。关闭按钮、遮罩、Escape、焦点进入与返回、页面滚动锁、跨断点与BFCache清理均已实测；商品搜索不加载脚本。没有复制筛选控件、查询或数据，也没有加入品牌、计数、Chips、Reset、AJAX、插件及非Local变更。

## 授权与实施边界

用户于2026-09-03明确回复：

> 确认按上述推荐范围，仅限 Local 实施 Day51。

该回复承接本轮功能确认单中的推荐范围：

- 仅为非搜索的Shop与`product_cat`增加窄屏筛选入口和抽屉交互。
- 继续使用D50唯一`.dentall-catalog-filters`、D49/D50参数白名单、WooCommerce主查询和Layered Nav；不生成第二份控件或第二商品查询。
- 小于1200px使用抽屉；1200px及以上保持D50常驻侧栏。
- 覆盖打开/关闭、焦点进入与返回、Escape、遮罩、滚动锁、方向和断点变化、BFCache返回、错误与空结果。
- 只修改Local子主题运行文件和项目文档，不写数据库，不部署Staging/Production。

明确不做：

- 不做品牌、评分、Package Quantity入口、已选Chips、计数、专门Reset、价格Slider、AJAX或严格同Variation匹配。
- 不改商品、Variation、价格、库存、分类、term、属性、查询表设置或缺货策略。
- 不新增插件、依赖、构建链、模板覆盖、REST/AJAX、远程请求、Cron或持久化状态。
- 不触碰真实支付、物流、税费、订单、DNS、Production缓存或匿名Coming Soon配置。

## 当日最多3项验收结果

1. [x] 390、768、1024px均有唯一`Filter`入口并打开D50同一筛选DOM；1440px及1200边界仍为240px常驻侧栏且隐藏移动入口。
2. [x] 关闭按钮、遮罩、Escape、Tab模态约束、焦点进入/返回、页面滚动锁、方向/断点变化及BFCache返回均正确恢复，不产生横向溢出。
3. [x] 正常、反向价格错误、空分类与商品搜索隔离通过；URL、主查询、robots/Canonical及D50筛选合同未改变，运行期无页面Console错误。

## 7个专注周期执行记录

| 周期 | 目标 | 实际结果 |
|---|---|---|
| C1 | 回读规则、D50结构与视觉证据 | 确认只移动同一aside；现有三端图没有抽屉打开态，遮罩、关闭控件和焦点行为按工程与可访问性规范补全，不冒充设计稿证据 |
| C2 | 服务端入口与资源边界 | 新增一次性`Filter`按钮、抽屉标题/关闭按钮和空`dialog`壳；脚本只在Shop/商品分类条件加载，商品搜索只保留目录CSS |
| C3 | 单一DOM与断点同步 | 窄屏将既有aside移入dialog，桌面移回原布局；不复制Categories、Price、Size、Shade或主查询 |
| C4 | 模态交互 | 完成打开、关闭、原生`cancel`与`keydown`兼容兜底承接Escape、遮罩按下/抬起安全判断、焦点进入/返回及`html/body`滚动锁 |
| C5 | 生命周期与异常恢复 | 以媒体查询事件处理1200px跨越，以CSS/动态视口高度承接同侧方向变化，并处理`pageshow/pagehide`和反向价格自动打开/定位错误字段 |
| C6 | Local四端与状态验证 | 完成390/768/1024/1440、1199/1200、方向变化、正常/错误/空结果/搜索、键盘、URL、Head、资源与Console验证 |
| C7 | 独立复核、减法审查与文档收口 | Code终审与最终静态复核均无缺陷；当日登记的跨断点/BFCache独立证据P2已在D52任何品牌实现前用可用Local登录态补跑关闭 |

## 实施结构与关键取舍

### 同一筛选DOM在两种容器间移动

服务端仍只输出一个`aside#dentall-catalog-filters`。JavaScript在小于75rem时把该aside移动进原生`dialog`，在桌面断点把它移回`.dentall-catalog-layout`中的原位置。分类、价格、Size与Shade没有第二份HTML，因此以下状态天然只有一个真相源：

- 当前分类和属性选中态；
- 反向价格错误与`aria-invalid`；
- 筛选链接、排序和分页的D50白名单；
- WooCommerce主查询、属性lookup及价格lookup结果。

这不是为四个设备维护四套页面。DOM语义只有一套，CSS按Mobile First渐进，JavaScript只处理展示容器与模态生命周期。

### 原生dialog负责模态层，项目代码负责生命周期

采用原生`<dialog>.showModal()`，让浏览器提供top layer和背景内容的模态隔离；项目代码补齐业务状态：

- `aria-expanded`与按钮可见性；
- 打开后聚焦标题，价格反向错误时聚焦第一个无效字段；
- 关闭后焦点返回`Filter`；
- 优先监听dialog原生`cancel`事件，并增加仅处理Escape的`keydown`兼容兜底；两条路径都阻止默认行为并统一调用同步清理函数，覆盖未派发`cancel`的浏览器自动化/嵌入环境；
- 只有指针按下和抬起都发生在遮罩上才关闭，避免拖动越过面板误关；
- 跨桌面断点和离页时清除dialog/滚动锁；同一侧断点内的方向变化由动态视口高度与CSS重排承接，不保留冗余`orientationchange`监听。

若浏览器不支持原生dialog，服务端`hidden`入口不会被暴露；页面、商品结果和1200px起的CSS侧栏仍可用。该降级不提供窄屏筛选，但不会给用户一个失效按钮。

### 资源只进入真实需要页面

`inc/setup.php`继续让Shop、商品taxonomy和商品搜索共享`catalog.css`；只有`dentall_is_catalog_filter_archive()`为真时才加载`catalog-filters.js`。真实商品搜索实测：Filter、dialog、aside和脚本均为0，D47搜索合同不被D51污染。

## Local浏览器与状态证据

| 场景 | 实际结果 |
|---|---|
| 390×844关闭态 | 唯一Filter可见；aside已在关闭dialog中；页面0横向溢出 |
| 390×844打开态 | dialog为390×844；面板342px宽，右侧保留48px遮罩；标题获得焦点，页面和面板滚动边界正确 |
| Escape / Close / 遮罩 | 三种路径均关闭；`aria-expanded=false`；`html/body`解锁；焦点返回Filter |
| Tab与Shift+Tab | 焦点没有逃出模态dialog；背景内容处于原生模态树之外 |
| 768×1024 | Filter可见；打开面板384px宽，无横向溢出 |
| 1024×768 | 3列商品网格不变；打开面板384px宽并可内部滚动 |
| 打开后768→1440 | 自动关闭、解锁、隐藏Filter并把同一aside恢复到240px桌面列；商品4列 |
| 1199 / 1200 | 1199为抽屉入口；1200为隐藏入口＋240px常驻侧栏；断点无间隙 |
| 844×390方向变化 | 抽屉保持打开，384px面板可滚动，页面锁定；动画名`none`、过渡时长`0s` |
| 反向价格50～10 | 抽屉自动打开并聚焦Min；两字段`aria-invalid=true`、错误`role=alert`、0商品、URL仍为参数页 |
| 选中Size | 抽屉内链接进入D50白名单组合URL；返回后自动打开，已选项仍有可见/ARIA移除状态 |
| 空分类 | Filter仍可打开；Categories与Price各1组，Size/Shade为0，保留Woo原生空态 |
| 商品搜索空结果 | Filter/dialog/aside/脚本均为0；目录CSS仍按D47条件加载 |
| BFCache返回 | 返回归档后dialog关闭、锁清除、入口状态恢复，aside仍只有1份 |

页面Console错误为`[]`。浏览器控制工具自身的遥测网络提示不属于DentAll页面错误。

## URL、SEO与数据边界

- D51不创建新的筛选参数；Filter只展示D50已存在的分类、`min_price`、`max_price`、`filter_*`和`query_type_*`入口。
- 代表参数页继续输出`noindex, follow`，Canonical回基础`/shop/`；脚本不改History、URL、表单值或链接。
- 点击Size Large后进入D50白名单URL，未知参数、旧分页和非法排序仍不传播。
- 商品搜索继续无D51筛选入口，避免把归档侧栏错误扩展到搜索请求。
- 本轮没有数据库写入、AJAX、localStorage/sessionStorage、Cookie、REST或远程调用；不会保存抽屉开关状态。

## 独立复核与问题关闭

- 设计证据复核确认三张商品归档图只有默认态，没有抽屉打开态；左侧进入、宽度、遮罩和关闭行为均是工程候选，不能写成设计稿冻结值。
- 独立Code Review先后发现并关闭三个P2：全局主按钮权重覆盖Filter/Close、异步`close`任务污染重开状态、桌面同宽`pageshow`偷走筛选内精确焦点；同时删除冗余方向监听和重复状态清理。当前代码终审P0/P1/P2/P3均为0。
- 独立Test/UX在D51当日对最终哈希`2ECEE3…5261E`确认390px按钮样式、打开态、内部滚动、焦点进入，以及Escape、Close、遮罩三条关闭路径；当时因登录态会话失效登记1项证据P2。D52任何品牌筛选实现前已补跑1199→1200→1199、同宽History返回、全四端、反向价格和搜索隔离：dialog关闭、滚动锁清除、aside保持唯一、2/2/3/4列及异常状态均成立。浏览器控制面未暴露`pageshow.persisted`，因此证据只记录实际History返回行为，不伪造该标志；原P2已关闭。

## 修改文件与减法审查

运行文件：

| 文件 | Day51净变化 | 保留理由 |
|---|---:|---|
| `themes/dentall/inc/catalog-filters.php` | +35行、1个PHP命名函数 | 在既有筛选职责内输出一次性入口与dialog壳，不另建渲染模块 |
| `themes/dentall/inc/setup.php` | +13行净变化 | 按真实页面身份条件加载独立交互资源，并收敛Shop/分类与商品搜索的共享样式判断 |
| `themes/dentall/assets/css/catalog.css` | +109行、约11个新规则块 | 抽屉壳、按钮、遮罩、内部滚动、滚动锁，并把D50筛选基础样式复用于窄屏 |
| `themes/dentall/assets/js/catalog-filters.js` | 新增156行、4077字节、5个命名闭包 | 独立的前端交互生命周期具有不同变更频率和测试价值，不堆入PHP或全站脚本 |
| `themes/dentall/style.css` | 0.26.0→0.27.0 | 更新主题资源缓存键 |

按UTF-8物理行口径，D51写前筛选模块、`setup.php`与目录CSS分别为416、72与399行，当前分别为451、85与508行；连同新增156行脚本，运行源码相对D51写前净增313行。新增1个运行文件、1个PHP命名函数和5个JS命名闭包（不把事件匿名回调计入函数数），没有新增模板覆盖、插件、依赖、查询、数据字段、AJAX、远程请求或持久化行为。

减法审查保留了独立JS文件，因为其职责是可单独测试的dialog/焦点/断点生命周期，和PHP的服务端筛选输出不同；没有继续拆为微型模块。最终代码没有动画、状态存储、图标库、polyfill、通用抽屉框架、第二DOM、第二查询或D52/D53预实现。`syncLayout()`只在aside确实位于dialog时恢复桌面位置，避免无意义DOM移动。

## 验证命令与资源

```powershell
php -l app/public/wp-content/themes/dentall/inc/catalog-filters.php
php -l app/public/wp-content/themes/dentall/inc/setup.php
node --check app/public/wp-content/themes/dentall/assets/js/catalog-filters.js
git diff --check
```

- 两个PHP文件语法通过，JavaScript语法通过，CSS花括号71/71，`!important`为0，媒体查询为3。
- 静态检查未发现`fetch`、XHR、Storage、HTML注入、`eval`、计时器、直接SQL、数据库写入、远程请求、AJAX或行内`style/script/event`。
- Local HTTP的`catalog-filters.js?ver=0.27.0`返回200/4077字节；级联修复后的`catalog.css?ver=0.27.0`返回200/13874字节。
- PHPCS在当前工作区不可用，因此没有把PHP lint等同于完整编码规范扫描。

## 未验证项

- 没有用正式目录规模、正式超长term或12+筛选结果重新跑真实分页；D46/D50原合同未改，但仍须在正式样本阶段复测。
- 没有在真实iOS/Android触屏设备和不支持原生dialog的旧浏览器上复测；当前证据来自Local桌面浏览器视口与指针/键盘自动化。
- D51当日登记的最终跨断点、History返回、全四端及异常/搜索隔离证据P2已在D52实施前关闭；真实`pageshow.persisted`值未由工具暴露，不把未知值写成已观察事实。
- 没有验证Staging/Production、真实页面缓存/CDN、Core Web Vitals、匿名预发布抓取或未来Woo/Storefront升级。
- 没有更改或验证支付、物流、税费、订单、退款、邮件与真实客户路径。

## 回滚

1. 从`inc/setup.php`移除`dentall-catalog-filters`脚本enqueue并删除`assets/js/catalog-filters.js`。
2. 从`catalog-filters.php`移除Filter按钮、dialog壳和抽屉标题/关闭按钮，恢复D50单一aside输出。
3. 从`catalog.css`移除D51按钮/dialog/遮罩/滚动锁规则，并把基础筛选样式恢复到D50桌面断点内。
4. 将子主题版本从0.27.0恢复为0.26.0，清理Local浏览器/页面缓存后复测Shop、分类、搜索和1200px侧栏。

回滚不需要改商品、Variation、term、lookup、URL、SEO规则、支付、物流或订单数据；D50 PC筛选和参数页`noindex`应继续保留。

## 数据、URL、SEO、缓存与部署影响

| 检查面 | 结论 |
|---|---|
| 数据 | 0数据库写入；商品、Variation、term与查询表未改 |
| URL | 0新参数/Slug/固定链接；继续使用D50白名单GET链接与表单 |
| SEO | robots、Canonical、Sitemap和Title逻辑未改；参数页合同实测保持 |
| 缓存 | 主题版本升至0.27.0使CSS/JS换缓存键；未改页面缓存/CDN策略 |
| 性能 | Shop/分类新增1个条件JS请求，当前4077字节；不新增查询或远程请求。未做生产级性能基线，不宣称零影响 |
| 支付/物流/订单 | 无影响；没有进入购物车、结账、库存扣减、支付或履约逻辑 |
| 部署 | 仅Local工作树；Staging/Production没有代码、配置、缓存或数据变化 |

## D52衔接

D52已按确认范围仅在Local完成，详见[[Day52-品牌数据与筛选基线]]：复用WooCommerce原生`product_brand`，在D51同一aside中加入品牌筛选，品牌归档第一版`noindex`，TEST品牌已回收。Day51本身没有预建品牌；它提供的单一DOM、dialog和生命周期边界经D52四端回归保持不变。

## 可复用核心思想

### 跨平台不变量

响应式筛选最重要的不是抽屉外观，而是保持一个查询状态、一个可访问交互生命周期和一个可恢复断点合同。复制两套控件会制造选中态、错误、URL和焦点的同步问题；方向变化、BFCache和跨断点才是最容易遗漏的真实边界。

### WordPress/WooCommerce当前实现

DentAll继续让WooCommerce主查询与Layered Nav决定商品集合，子主题服务端只输出一次筛选aside，条件enqueue的原生JavaScript把它移入或移出`dialog`。原生dialog提供top layer与模态语义，项目代码负责`aria-expanded`、焦点、滚动锁和生命周期清理；没有修改核心、父主题或Woo模板。

### Shopify或其他平台的对应机制

其他平台也应优先让同一筛选状态在窄屏面板和桌面侧栏间复用，并验证URL、焦点、缓存返回和断点恢复。Shopify的Section Rendering、Filter URL与主题drawer实现没有在DentAll实测，标记为待验证，不能把WordPress的Hook、PHP DOM输出或`dialog`脚本机械映射过去。
