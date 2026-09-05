---
项目: DentAll WooCommerce
工作日: D46
计划检查点: D46（不自动等于一个完整实际工作日）
日期: 2026-09-02
实际有效工时: 未记录；不使用计划工时代替
验收层级: Local商品归档分页、URL与SEO边界；不等于搜索样式、筛选、正式内容或非Local部署验收
状态: 已完成用户授权范围
tags:
  - DentAll
  - Day46
  - WooCommerce
  - Pagination
  - SEO
---

# DentAll 每日复盘 D46：商品归档分页与URL归一化

## 相关笔记

- 每日笔记索引：[[README|DentAll每日笔记索引]]
- 前置项目笔记：[[Day45-商品排序与结果信息]]
- 当日学习笔记：[[WordPress实战笔记/Day46-WooCommerce分页链接与Canonical边界]]
- 前置学习笔记：[[WordPress实战笔记/Day45-WooCommerce排序Hook与参数URL]]
- URL与SEO事实：[[../URL_SEO_MAP|URL与SEO映射]]
- 后续项目笔记：[[Day47-商品搜索结果与边界状态]]
- 后续学习笔记：[[WordPress实战笔记/Day47-WooCommerce商品搜索请求与模板复用]]

> [!success] 当前结论
> Day46已在Local按用户授权范围完成。DentAll 0.23.0继续使用WooCommerce原生主查询、每页12项和`paginate_links()`，Shop与商品taxonomy只保留一组底部分页；分页项至少44×44px、可换行、当前态和键盘Focus清晰。11个临时TEST商品形成真实12/1两页后，Shop、taxonomy、排序参数、第一页归一化、越界404、空态、搜索隔离与五宽均通过。独立终审P0/P1/P2为0；商品#120～#130已在逐项核对后移入回收站，发布商品和TEST分类均恢复2项。未提交Git，未部署或修改非Local环境。

## 授权与实施边界

用户于2026-09-02明确回复：

> 确认按推荐范围实施Day46，并授权在Local临时创建11个TEST商品，验收后移入回收站；不提交Git、不部署非Local。

本次确认范围为：

- 复用D43～D45的原生Archive Header、主查询、12项/页、商品Grid和顶部结果/排序工具栏。
- Shop与商品taxonomy仅保留一组底部原生分页，收敛页码密度并补充可翻译的Previous/Next名称。
- 使用既有Design Token完成Mobile First分页布局、44px触控区、当前态、hover、Focus与换行能力。
- 验证第一页、第二页、排序参数、taxonomy、空taxonomy、越界页、Canonical、robots、`rel=prev/next`和搜索隔离。
- 仅在Local用WooCommerce CRUD创建11个明确标记的Simple TEST商品，验收完成后移入回收站。

明确不做：

- 不改WooCommerce 3列×4行，即每页12项的查询合同。
- 不改D44商品Grid、ProductCard内部、D45排序查询、D47搜索样式或D49以后筛选。
- 不新增模板覆盖、JavaScript、插件、字段、自定义查询、路由或外部依赖。
- 不把TEST名称、SKU、价格、缺图状态或分类关系当作正式商品事实。
- 不永久删除TEST商品，不清空回收站，不提交Git，不部署Staging/Production。

## 当日最多3个验收结果

1. Shop与商品taxonomy使用一组底部原生分页，320～1440px无横向溢出，触控与键盘状态可用。
2. Page 1、Page 2、排序参数、Canonical、`rel=prev/next`与越界404形成一致URL合同，不制造可避免的内部301跳转。
3. 11个Local TEST商品完成真实12/1分页验证后安全移入回收站；空态、搜索、数据和非Local边界不回归。

三项均在授权范围内通过。

## 7个专注周期执行记录

| 周期 | 目标 | 实际结果 |
|---|---|---|
| C1 | 读取规则、D43～D45实现、Woo/Storefront源码与Local基线 | 确认Storefront上下各输出分页、Woo每页12项、分页模板参数与搜索隔离边界 |
| C2 | 冻结最小实现与测试矩阵 | 决定只改既有Hook、分页参数Filter、归档CSS和缓存版本，不覆盖模板或改主查询 |
| C3 | 实施原生分页与视觉状态 | 移除顶部重复分页；新增一个分页参数函数和7个CSS规则块；主题升至0.23.0 |
| C4 | 建立真实多页夹具 | 用WooCommerce CRUD创建#120～#130，共11个有独立SKU与fixture meta的Simple TEST商品 |
| C5 | 动态、URL、SEO和五宽验收 | Shop/taxonomy 12/1、多参数、404、空态、搜索、320/390/768/1024/1440及Focus通过 |
| C6 | 独立Code/Design/Test复核与修复 | 发现Page 1链接先经`/page/1/`的P2；交回WordPress默认base/format后关闭，最终P0/P1/P2=0 |
| C7 | 回收夹具、减法审查与文档收尾 | 11项全部核对后移入回收站；发布商品/分类恢复2项；运行文件与状态文档同步 |

实际有效工时由用户按需记录，本笔记不把计划6小时50分钟写成实际工时。

## 实施结果

### Hook与原生分页职责

`app/public/wp-content/themes/dentall/inc/storefront-hooks.php`继续在`wp`阶段调整父主题已注册的循环Hook：

- Shop与商品taxonomy移除Storefront在循环前优先级30注册的顶部分页。
- 循环后仍由WooCommerce/Storefront原生位置输出唯一分页。
- 商品搜索显式排除，继续保持D28/D45既有上下两组原生工具栏和分页，等待D47单独处理。

新增`dentall_catalog_pagination_args()`只在非搜索Shop/taxonomy生效：

- `end_size=1`、`mid_size=2`，避免商品很多时页码无限增长。
- Previous/Next使用`esc_html__()`和`dentall` text domain。
- 不改`current`、`total`、主查询、每页数量或排序参数。
- `unset( $args['base'], $args['format'] )`让WordPress核心重新使用自己的默认base/format，Page 1直接指向归档根URL，同时兼容当前Pretty Permalink、taxonomy和GET参数。

### CSS职责

`app/public/wp-content/themes/dentall/assets/css/catalog.css`只锚定“商品循环之后的Storefront底部容器”：

- 移除父主题分页浮动与边框残留，使用Flex居中并允许换行。
- 链接和当前页最小宽高均为`2.75rem`，当前16px根字号下为44px。
- 当前页使用动作蓝背景、白字和边框，不只依赖颜色差异；分页数字、链接与省略号结构仍由WordPress/WooCommerce输出。
- hover使用既有边框、浅蓝Surface和动作色Token；Focus继续复用全站3px深蓝`focus-visible`规则。
- 省略号缩窄但不作为交互目标；没有`!important`、硬编码Hex或新的媒体查询。

### 为什么没有模板覆盖或JavaScript

分页的HTML、当前页、链接、ARIA、总页数和参数合并已经由WooCommerce模板与WordPress核心负责。子主题只需要调整Hook组合、参数窗口和CSS；复制`woocommerce/loop/pagination.php`会增加升级同步债，自写JavaScript或第二查询则会重复实现已经存在的平台能力。

## 真实TEST商品生命周期

| 阶段 | 事实 |
|---|---|
| 创建前 | Local有2个发布商品；`test-d12-products`分类有2项 |
| 创建 | WooCommerce CRUD创建11个Simple商品，ID #120～#130，SKU `TEST-D46-PAGE-01`～`11` |
| 标记 | `_dentall_test_fixture=day46-pagination`，另有同批次标记；名称和内容均明确为TEST |
| 用途 | 与既有2项合计13项，形成第一页12项、第二页1项；缺图和较长TEST标题同时覆盖卡片边界 |
| 清理闸门 | 写入前一次性核对ID、post type、publish状态、Simple类型、逐项SKU和fixture meta；任一不符则整体停止 |
| 清理结果 | 11/11通过核对并用`wp_trash_post()`移入回收站；没有永久删除 |
| 清理后 | 发布商品2项；分类count=2；Shop/taxonomy均0个分页且无Day46 fixture文本 |

回收站中的11项当前可恢复；若未来手工清空或由WordPress回收站保留策略清理，将变为不可恢复，不能把“已进回收站”写成“已永久删除”。

## 浏览器、URL与SEO证据

### Shop与taxonomy矩阵

| 场景 | 实际结果 |
|---|---|
| `/shop/` | 12项、`Showing 1–12 of 13 results`、1个底部分页、Page 1当前态、Page 2与Next |
| `/shop/page/2/` | 1项、`Showing 13–13 of 13 results`、Page 2当前态、Previous与Page 1均直达`/shop/` |
| `/product-category/test-d12-products/` | 12项、1个底部分页、Canonical自身、`rel=next`指向第二页 |
| taxonomy第二页 | 1项、Previous/Page 1直达taxonomy根URL，Canonical自身、`rel=prev`指向根URL |
| 空taxonomy `/product-category/test/` | 0项、原生No products状态、0个排序/结果/分页，Canonical自身 |
| 清理后Shop/taxonomy | 各2项、0个分页；底部空wrapper为`display:none` |

### 参数与状态码

| 请求 | 结果 |
|---|---|
| `/shop/page/2/?orderby=price` | 200；排序值保留；Previous/Page 1直达`/shop/?orderby=price`；Canonical为无参数第二页 |
| 在第二页切换为`price-desc` | 原生表单提交到`/shop/?orderby=price-desc`，回到第一页，12项，Next继续保留参数 |
| `/shop/page/1/` | 301归一到`/shop/`；最终分页内部链接已不再主动生成该跳转URL |
| `/shop/page/2/` | 200 |
| `/shop/page/3/` | 404；`error404`、`noindex, follow`、无Canonical、无主商品循环分页 |
| 商品搜索 `/?s=TEST&post_type=product` | 仍为两组原生排序/结果/分页、`noindex, follow`且不加载`catalog.css` |

Yoast输出的有效第二页Canonical为分页页自身，排序参数被Canonical剥离；`rel=prev/next`使用无排序参数的主分页关系。该结论只代表当前Local的WordPress 7.0.4、WooCommerce 11.0.0、Yoast 28.2和当前配置，不能外推到非Local缓存或未来插件版本。

### 响应式与可访问性

| 视口 | 商品列数 | 分页结果 |
|---:|---:|---|
| 320px | 2 | 3个分页项均44×44px；3px深蓝Focus＋3px偏移；无横向溢出 |
| 390px | 2 | 唯一底部分页；缺图和长标题卡片正常换行；无横向溢出 |
| 768px | 2 | 44px目标、居中、无横向溢出 |
| 1024px | 3 | 44px目标、居中、无横向溢出 |
| 1440px | 4 | 分页居中；顶部结果数按D45恢复可见；无横向溢出 |

真实数据只有2页，因此没有伪造11个真实分页页面。运行时合成只读证据确认总页数11时：

- 当前第1页：`1 2 3 … 11 Next`
- 当前第6页：`Previous 1 … 4 5 6 7 8 … 11 Next`
- 当前第11页：`Previous 1 … 9 10 11`

多行能力由真实`flex-wrap`、最小尺寸和几何边界证明，但没有把合成HTML冒充真实11页DOM截图。hover只完成源码状态审查，未取得实际hover截图；这两项均不构成P0～P2。

## 独立复核与修复记录

- Code Review：初始发现Page 2的Previous/Page 1先指向`/page/1/`再301的P2；建议让WordPress恢复默认base/format。修复后Pretty Shop、taxonomy、GET参数和Plain Permalink算法证据通过，最终无P0/P1/P2。
- Design Review：320/390/768/1024/1440实页复核通过；44px、Focus、唯一底部分页、缺图/长标题、2/2/3/4列和无横溢出通过，P0/P1/P2=0。
- Test/SEO Review：12/1、Canonical、robots、`rel`、排序参数、404、空分类、搜索隔离和清理边界通过，P0/P1/P2=0。

## 静态检查与减法审查

最终运行文件证据：

| 文件 | D45基线 | D46最终 | 净变化 |
|---|---:|---:|---:|
| `inc/storefront-hooks.php` | 9959 bytes / 303行 | 11034 bytes / 335行 | +1075 bytes / +32行 |
| `assets/css/catalog.css` | 3633 bytes / 142行 | 5423 bytes / 205行 | +1790 bytes / +63行 |
| `style.css` | 0.22.0 | 0.23.0 | 只改主题版本 |

保留结果：修改3个既有运行文件，新增0个运行文件、1个函数、7个CSS规则块、0个媒体查询、0个模板、0个JavaScript、0个查询、0个插件或依赖。分页样式继续留在职责已经明确的归档CSS中；分页参数继续留在Storefront Hook模块中，不因Day编号拆出微型文件。

实际检查：

- PHP 8.2.29对`storefront-hooks.php`和`setup.php`执行`php -l`，无语法错误。
- `catalog.css`为30/30对花括号、0个`!important`、0个硬编码Hex。
- `git diff --check`通过，仅有Windows工作区既有CRLF提示。
- 主题运行时版本回读为0.23.0；临时审计/清理PHP文件数量为0。
- CLI继续出现既有`php_imagick.dll`启动警告；站点当前图片处理使用既有可用路径，本轮没有声称修复Imagick。

减法审查没有删除7个分页规则块：wrapper、列表、列表项、交互目标、hover、current和dots各自处理不同父主题残留或状态，进一步合并会降低可读性或扩大选择器作用域。没有为测试结果保留fixture脚本、认证Cookie文件或合成审计文件。

## 数据、URL、SEO、缓存与交易影响

| 检查面 | 结论 |
|---|---|
| 数据 | 11个临时商品已在精确核对后移入Local回收站；发布基线恢复2项；没有订单、客户、销量、库存交易或正式内容写入 |
| URL | 保留`/shop/`与taxonomy结构；有效第二页使用原生`/page/2/`；内部Page 1链接直达归档根URL |
| SEO | Page 2自身Canonical；排序参数不成为Canonical主版本；越界Page 3为真实404/noindex；搜索继续noindex |
| 缓存 | 仅主题静态版本升至0.23.0；未清理或配置非Local页面缓存/CDN，生产参数缓存仍待部署前验证 |
| 支付/物流/订单 | 无影响；未进入购物车、结账、支付、税费、运费、订单或退款流程 |
| 部署 | 仅Local；未提交Git、未切分支、未部署Staging/Production |

## 未验证项与后续边界

- 未创建足以形成真实11页DOM的132个商品；多行分页以算法输出和CSS几何证明，不冒充真实大数据量浏览器证据。
- 未取得实际hover截图；hover规则已静态与设计复核，键盘Focus已真实验证。
- 未验证匿名商城内容，因为WooCommerce Coming Soon仍是独立保护层；不能用登录态页面推断匿名预发布结果。
- 未验证Staging/Production缓存、CDN、Core Web Vitals、抓取日志或未来Storefront/WooCommerce升级。父主题若改变`storefront_woocommerce_pagination`回调或优先级，必须重跑Hook矩阵。
- D47商品搜索样式、D48正式分类内容和D49以后筛选没有提前实施。

## D47衔接

D47应先只读梳理真实商品搜索结果页：搜索词、结果/无结果、分页、排序参数、Canonical/robots、长词与特殊字符、Header搜索提交和四端布局。它可以复用D43～D46的商品循环、ProductCard、Grid和分页思想，但搜索当前明确不加载`catalog.css`且保留两组原生工具栏；是否接入、如何收敛和SEO策略必须作为D47独立确认单，不能把Day46的归档条件直接扩到`is_search()`。

## 可复用核心思想

### 跨平台不变量

分页不是一排数字，而是“查询总量、页码URL、参数状态、内部链接、Canonical、错误页和交互反馈”共同组成的导航合同。第一页、有效后页和越界页必须分别验证；只看页面能翻动，仍可能隐藏重复入口、软404或参数丢失。

### WordPress/WooCommerce当前实现

DentAll让WooCommerce主查询决定12项/页和总页数，让Woo模板保留分页输出位置，让WordPress `paginate_links()`负责base/format、Pretty/Plain Permalink和查询参数合并；子主题只调整Storefront Hook组合、页码窗口和品牌状态。临时商品用Woo CRUD创建，用明确meta/SKU防误删，再通过WordPress回收站API可逆清理。

### Shopify或其他平台的对应机制

其他平台同样需要集合查询、分页游标或页码、参数保持、Canonical、404、触控与键盘状态；但Shopify的Collection分页、Liquid `paginate`、URL参数、Canonical和主题发布机制在DentAll尚未实测，均为待验证。可迁移的是分层责任、第一页归一化、边界状态和可逆夹具方法，不是WooCommerce Hook名或DOM类名。
