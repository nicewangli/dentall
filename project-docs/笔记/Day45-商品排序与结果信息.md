---
项目: DentAll WooCommerce
工作日: D45
计划检查点: D45（不自动等于一个完整实际工作日）
日期: 2026-09-02
实际有效工时: 未记录；不使用计划工时代替
验收层级: Local商品归档排序与结果信息；不等于分页、搜索样式、正式内容或非Local部署验收
状态: 已完成用户授权范围
tags:
  - DentAll
  - Day45
  - WooCommerce
  - Sorting
  - Accessibility
---

# DentAll 每日复盘 D45：商品排序与结果信息

## 相关笔记

- 每日笔记索引：[[README|DentAll每日笔记索引]]
- 前置项目笔记：[[Day44-商品网格响应式]]
- 当日学习笔记：[[WordPress实战笔记/Day45-WooCommerce排序Hook与参数URL]]
- 前置学习笔记：[[WordPress实战笔记/Day44-CSS-Grid与WooCommerce每页数量解耦]]
- 后续项目笔记：[[Day46-商品归档分页与URL归一化]]
- 后续学习笔记：[[WordPress实战笔记/Day46-WooCommerce分页链接与Canonical边界]]

> [!success] 当前结论
> Day45已在Local按用户确认的推荐范围完成。DentAll 0.22.0继续使用WooCommerce原生GET排序、主查询、结果状态和自动提交，只把Shop与商品taxonomy收敛为一组顶部工具栏：320/390/768/1024视觉隐藏结果数但保留DOM与`role="status"`，1440显示“结果数左、排序右”，底部只保留D46分页位置。五宽、默认/升价/降价/非法值、参数保留、正常/空taxonomy、搜索隔离与Canonical通过；三路独立复核P0～P3均为0。每页12项、D44 Grid、主查询、分页、搜索样式、筛选和数据没有改变。

## 授权与实施边界

用户于2026-09-02明确回复：

> 确认按推荐范围实施Day45

本次把此前推荐范围解释为以下已确认合同：

- Local Shop与商品taxonomy只显示一组顶部原生排序工具栏。
- 小于1200px不让结果数占据紧凑工具栏视觉空间，但仍保留给辅助技术；1200px起显示结果数左、排序右。
- 保留WooCommerce原生6个排序选项、GET参数、自动提交、清洗校验、主查询和Canonical策略。
- 删除循环底部重复排序与结果数，但不移除D46要使用的原生分页Hook。
- 不修改Woo当前3列×4行、每页12项，不修改D44 Grid、ProductCard、搜索样式或筛选。
- 不新增模板、JavaScript、查询、插件、字段、自动化、持久数据或外部依赖。
- 不创建商品、订单、销量或评分证据，不部署Staging/Production，不生成补充设计图。

## 当日最多3个验收结果

1. Shop与商品taxonomy只保留一组顶部排序/结果工具栏，四端节奏与辅助技术状态符合设计证据。
2. 默认、升价、降价、非法值和既有筛选参数均沿用WooCommerce原生GET合同，排序顺序与URL/Canonical正确。
3. 搜索、空taxonomy、每页12项、D44 Grid和底部分页位置不回归，独立Code/Design/Test没有P0/P1。

三项均在Local授权边界内通过；Popularity与Rating因真实销量、评分均为0，只证明选项和安全执行，未冒充有区分度的排序顺序证据。

## 7个专注周期执行记录

| 周期 | 目标 | 结果 |
|---|---|---|
| C1 | 读取规则、计划、D43/D44实现与参考图，冻结职责 | 确认原生排序、单顶部工具栏、1200px视觉切换和D46分页边界 |
| C2 | 盘点Storefront/WooCommerce Hook与GET合同 | 确认排序/结果/分页优先级、6选项、`useLabel`、自动提交和参数保留 |
| C3 | 最小修改既有Hook | 既有函数改挂`wp`，仅Shop/taxonomy移除底部排序与结果数 |
| C4 | 最小补充响应式CSS与缓存版本 | 顶部Flex、结果状态视觉隐藏/恢复、底部空wrapper和0.22.0完成 |
| C5 | 动态URL、排序、taxonomy与搜索验证 | 发现并修复商品搜索误收敛；默认/升降价/非法值/参数与Canonical通过 |
| C6 | 五宽浏览器与独立Code/Design/Test复核 | 320/390/768/1024/1440无横溢出，三路P0～P3均为0 |
| C7 | 减法审查、文档与学习收尾 | 无多余查询/模板/JS/文件；状态、索引、变更日志和学习笔记同步 |

实际有效工时由用户按需记录，本笔记不把计划6小时50分钟写成实际工时。

## 实施结果

### Hook职责

`inc/storefront-hooks.php`中的既有`dentall_enable_catalog_ordering_labels()`从`after_setup_theme`改挂到`wp`。原因不是延迟样式，而是必须等WordPress主查询、404状态和条件标签建立后，才能可靠区分Shop、商品taxonomy与商品搜索。

实际行为：

- 所有既有目录循环继续把Storefront原生无标签排序替换为DentAll调用的WooCommerce `useLabel`输出。
- 只有`! is_search() && ( is_shop() || is_product_taxonomy() )`时移除底部结果数并不再重挂底部排序。
- 底部`woocommerce_pagination`优先级30未被移除，D46仍可复用原生位置。
- 商品搜索继续保持D28上下两组带标签排序与结果信息，且不加载`catalog.css`。

### CSS职责

`assets/css/catalog.css`继续只由D43的Shop/taxonomy条件资源入口加载：

- 排序表单用Flex右对齐，标签不换行，select允许收缩并限制最大宽度14rem。
- 320～1024把结果数裁剪为1×1，保留文本、`role="status"`和WooCommerce live-region合同。
- 75rem起恢复结果数的尺寸、位置、溢出与裁剪属性，并通过自动外边距形成左右分布。
- 没有分页时，Storefront保留的底部空wrapper通过`:empty`隐藏；有分页时wrapper仍可显示。
- D44商品Grid规则、列数和gap没有修改。

### 发现并修复的问题

首次Hook收敛条件只判断Shop/taxonomy，未显式保护搜索；动态商品搜索验证发现底部输出会被误改。最终把请求判断放到`wp`阶段并增加`! is_search()`，恢复D28搜索合同。

这项修复说明：WooCommerce条件标签可能重叠，不能把“本页面看起来像商品列表”当作唯一作用域证据；必须用真实URL与Hook输出交叉验证。

## 验收证据

### 运行合同

| 场景 | 结果 |
|---|---|
| `/shop/` | 1个排序、1个结果状态、6个原生选项、唯一label/id、GET、`paged=1` |
| `?orderby=price` | 选中Price low to high；商品ID顺序`[44,46]`，价格24.99→39.99 |
| `?orderby=price-desc` | 选中Price high to low；商品ID顺序`[46,44]`，价格39.99→24.99 |
| 非法`orderby` | 安全回退默认`menu_order`，无错误 |
| `min_price=10&max_price=500` | 排序表单保留`paged`、`min_price`、`max_price`隐藏字段 |
| 正常taxonomy | 1个排序、1个结果状态，Canonical回分类基础URL |
| 空taxonomy | 0商品、0排序、0结果数，保留WooCommerce原生空状态 |
| 商品搜索 | 2个排序、2个结果数，`catalog.css`不加载，D28合同不变 |

### 五宽浏览器

| 视口 | 结果信息 | 排序/布局 | 横向溢出 |
|---|---|---|---|
| 320 | 1×1视觉裁剪，DOM/文本/`role=status`保留 | select高44px；Grid 2列 | 0 |
| 390 | 同320 | select高44px；Grid 2列 | 0 |
| 768 | 同320 | select高44px；Grid 2列 | 0 |
| 1024 | 同320 | select高44px；Grid 3列 | 0 |
| 1440 | 约119.56×21px可见，位于左侧 | 排序位于右侧；Grid 4列 | 0 |

1440实测结果数左边界约84.5px、排序左边界约1139.8px。底部无分页wrapper计算为`display:none`。实际页面加载`catalog.css?ver=0.22.0`。

### URL、SEO与数据

- Shop以及`price`、`price-desc`、非法排序参数的Canonical均为基础`/shop/`。
- 商品taxonomy排序参数Canonical回对应taxonomy基础URL。
- Product与product_cat Sitemap均没有`orderby=`参数URL。
- Title、H1、robots、既有Schema和Shop slug没有改变。
- Woo目录配置仍为3列×4行，每页12项；没有查询Filter或第二个`WP_Query`。
- 当前商品价格24.99/39.99只读；销量和评分均为0；没有创建订单或修改商品、价格、库存、销量、评分、分类或Option。

### 静态与独立复核

- `storefront-hooks.php`与`inc/setup.php`通过PHP 8.2 lint。
- `catalog.css`最终142行、3633字节、23/23对花括号、0个`!important`。
- `git diff --check`通过；Windows换行提示不属于语法或空白错误。
- Code、Design、Test三路独立复核最终P0/P1/P2/P3均为0。
- 独立Chrome在实际Focus交互和Console读取时超时，未保存新的`:focus-visible`截图或Console日志；源码确认原生select继续命中既有44px控件和3px `focus-visible`合同，本次没有新增JS。该证据缺口不冒充通过，也未被三路复核判定为P0/P1。

## 代码减法审查

以D44运行基线计，Day45净变化：

- 修改3个既有运行文件：`inc/storefront-hooks.php`、`assets/css/catalog.css`、`style.css`。
- 新增运行文件0；新增函数0；新增媒体查询0。
- PHP只修改1个既有函数的生命周期、请求边界与注释；未新建模块，因为职责仍属于既有Storefront Hook适配层。
- CSS净增51行/1210字节、6个规则块；复用既有75rem断点和Design Token。
- `style.css`只把缓存版本0.21.0提升到0.22.0。
- 查询、模板覆盖、JavaScript、插件、依赖、字段、Cron、REST/AJAX、远程请求和数据写入均为0。

保留这些变化的理由：PHP负责“输出几组工具栏以及何时生效”，CSS负责“同一语义DOM在不同宽度怎样呈现”，两者生命周期和测试价值不同，放回既有职责文件比新建Day文件或复制模板更清晰。

## WordPress/WooCommerce输出与安全微调路径

- HTML来源：WooCommerce循环Hook输出原生结果数和排序表单；DentAll只用现有回调请求`useLabel`，没有复制模板。
- 布局方法：同一语义DOM＋Mobile First Flex；1200px只恢复结果状态可见性和左右分布。
- 正常/异常状态：正常列表有一组工具栏；空taxonomy由WooCommerce不触发循环前Hook；非法排序回退；无分页时底部空wrapper隐藏。
- 安全微调：先在Chrome DevTools查看`.storefront-sorting`、`.woocommerce-result-count`与`.woocommerce-ordering`的Computed和Matched Rules；判断是改现有Token、公共控件还是`catalog.css`局部规则，再回源码复测五宽。
- 不要在DevTools、父主题、WooCommerce核心文件、页面编辑器或后台“自定义CSS”中长期保存修复。

## 影响、回滚与未验证项

| 影响面 | 结论 |
|---|---|
| 数据 | 0写入；商品、价格、库存、销量、评分、订单和Option不变 |
| URL/SEO | 排序参数继续Canonical回基础归档；未新增可索引URL |
| 缓存 | 子主题版本升至0.22.0；非Local缓存/CDN未验证 |
| 支付/物流/税费 | 0变更 |
| 性能 | 没有新请求、查询或JS；未测生产CWV，不宣称性能提升或零影响 |
| 部署 | 仅Local；Staging/Production未同步 |

局部回滚顺序：恢复`storefront-hooks.php`的D44 Hook注册与底部输出、删除`catalog.css`的D45工具栏规则、将缓存版本退回0.21.0；D43标题骨架和D44 Grid必须保留。非Local部署时应把D43～D45主题基线与Shop标题作为一个受控批次，并复测Canonical、缓存、分页和代表状态。

仍未验证：真实正销量/正评分导致的Popularity/Rating差异、满12项以上的真实分页、实际Focus截图、最终Console读取、非Local缓存/CDN、生产Core Web Vitals和正式商品内容。前两项分别需要真实业务数据或D46授权，不能通过擅自写销量/评分或创建订单补证。

## D46交接

- D46只读梳理原生分页、当前12项/页合同、0/1/多页状态、排序/筛选参数保持、Canonical和四端节奏。
- D45已保留底部`woocommerce_pagination`位置；不要重新加入底部排序/结果数来“填满”空wrapper。
- 当前仅2件商品，无法形成真实多页证据；若要创建持久TEST商品或改每页数量，必须提交新的功能确认单并得到明确授权。
- D47搜索样式、D48正式分类内容、D49以后筛选仍是独立范围。

## 可复用核心思想

### 跨平台不变量

排序至少有四份合同：可选择的业务顺序、URL参数、后端查询和前端状态反馈。视觉上只留一组控件时，不能删除辅助技术状态、参数保留或Canonical规则；任何“去重”都必须分别验证输出数量、查询顺序、URL和空态。

### WordPress/WooCommerce当前实现

WooCommerce原生GET表单负责`orderby`、隐藏参数和自动提交，主查询负责真正排序，结果数通过`role="status"`反馈。DentAll在`wp`阶段用可靠条件标签收敛Storefront Hook，并在条件加载的`catalog.css`中做视觉响应式；没有复制模板、重写查询或修改核心。

### Shopify或其他平台的对应机制

其他平台同样要区分集合排序选项、URL状态、后端/平台查询和可访问反馈。Shopify Collection的排序参数、Canonical、主题Section与过滤状态具体机制尚未在DentAll验证，均标记“待验证”；可直接迁移的是职责分层、参数白名单、辅助技术不丢失和基础URL去重思想。
