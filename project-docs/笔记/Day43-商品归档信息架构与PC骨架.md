---
项目: DentAll WooCommerce
工作日: D43
计划检查点: D43（不自动等于一个完整实际工作日）
日期: 2026-09-01
实际有效工时: 未记录；不使用计划工时代替
验收层级: Local商品归档共用骨架；不等于正式内容、搜索、筛选或非Local部署验收
状态: 已完成用户授权范围
tags:
  - DentAll
  - Day43
  - WooCommerce
  - ProductArchive
  - Responsive
---

# DentAll 每日复盘 D43：商品归档信息架构与PC骨架

## 相关笔记

- 每日笔记索引：[[README|DentAll每日笔记索引]]
- 前置项目笔记：[[Day42-首页全链路校准与M4技术验收]]
- 当日学习笔记：[[WordPress实战笔记/Day43-WooCommerce归档主查询与条件资源]]
- 前置学习笔记：[[WordPress实战笔记/Day42-首页整链路验收与证据分层]]
- 后续项目笔记：[[Day44-商品网格响应式]]
- 后续学习笔记：[[WordPress实战笔记/Day44-CSS-Grid与WooCommerce每页数量解耦]]

> [!success] 当前结论
> Day43已在Local按确认范围完成。原生Shop Page公开标题由“商店”改为`Products`，slug、访问URL和Canonical继续保持`/shop/`；Shop与商品taxonomy复用WooCommerce原生Archive Header、主查询、商品循环和D29 ProductCard。子主题0.20.0只新增条件加载的`catalog.css`，管理归档标题区和列表上下节奏，不提前实现D44网格、D45排序、D46分页、D47搜索、D48正式分类内容或D49以后筛选。四端、分类正常态、空态、排序回归和搜索隔离均通过，三路独立复核最终未发现P0～P3范围内缺陷。

## 授权与范围

用户于2026-09-01明确授权：

> 确认按推荐范围实施Day43；Shop公开标题使用Products，URL保持/shop/

本次实施边界：

- 只在Local调整原生Shop Page标题；不改变slug、固定链接结构或商品分类URL。
- 复用WooCommerce 11.0.0与Storefront 4.6.2现有归档模板、主查询、排序、结果计数、空状态和商品卡，不创建第二套查询或模板。
- Shop与`product_cat`等商品taxonomy共用Archive Header骨架；商品搜索明确留D47，因此搜索请求不加载Day43样式。
- 新增一个职责单一的CSS文件，并从既有`inc/setup.php`条件加载；不新增JavaScript、插件、依赖、字段、Option、Cron、远程请求或构建链。
- 不创建或修改商品、分类、价格、库存、订单、支付、物流、税费、正式素材和正式业务文案；既有TEST分类只用于代表状态验证。
- 不部署Staging或Production，不关闭Coming Soon，不改robots、Sitemap、缓存配置或支付边界。

## 最多3项验收结果

- [x] 冻结商品归档共用信息架构：原生Shop Page或taxonomy提供标题/描述，WooCommerce主查询提供结果，原生循环输出D29 ProductCard；未引入模板覆盖或次级查询。
- [x] Shop公开标题为`Products`且`/shop/`不变；390、768、1024、1440的归档标题区渐进为160/192/192/224px，四端页面级横向溢出为0。
- [x] Shop正常态、商品分类正常态、空分类、原生价格排序与商品搜索隔离完成回归；Code、Design、Test/Scope独立复核没有阻塞问题，后续功能未被提前吸收。

## 7个专注周期实际分工

| 周期 | 目标 | 实际结果 |
|---|---|---|
| C1 | 对照总计划、状态、代码规则和Day42交接，盘点版本与归档现状 | 确认Local版本、2件现有商品、3列/12项每页合同、无模板覆盖和现有排序结构 |
| C2 | 拆解Shop、taxonomy、搜索、主查询、模板与URL/SEO职责 | 冻结“原生主查询＋原生模板＋条件展示样式”最小方案，并提交用户确认 |
| C3 | 更新Shop标题与条件资源入口 | Page ID 7标题改为`Products`，slug仍为`shop`；新增`dentall_enqueue_catalog_assets()` |
| C4 | 实现Mobile First归档标题区与上下节奏 | 新增`catalog.css`，同一DOM按48rem和75rem渐进增强，不改网格、排序内部或分页 |
| C5 | 四端与代表状态浏览器验证 | Shop四端、分类正常/空态、排序、Canonical、Console、资源HTTP与搜索隔离通过 |
| C6 | 独立Code、Design、Test/Scope复核与最小修复 | 修正CSS特异性/侧栏依赖和搜索条件泄漏；删除一条父主题已覆盖的冗余规则 |
| C7 | 状态、两套笔记、索引、影响与回滚收口 | 记录Local写入、部署差异、诊断日志副作用、减法统计与D44交接 |

实际有效工时未记录；上表记录职责与结果，不把7个计划周期换算为实际工时。

## 信息架构与职责合同

| 请求类型 | 标题与描述来源 | 查询与商品输出 | URL/SEO边界 | Day43处理 |
|---|---|---|---|---|
| Shop | 原生Shop Page标题/内容；当前标题`Products` | WooCommerce主查询＋原生循环＋D29 ProductCard | slug、URL、Canonical均为`/shop/` | 加载归档骨架样式 |
| 商品taxonomy | 当前term名称/描述 | WooCommerce主查询＋原生循环＋D29 ProductCard | `/product-category/{slug}/`等原生机制 | 复用同一骨架样式 |
| 商品搜索 | 搜索词与搜索结果标题 | WooCommerce搜索主查询 | 当前Yoast输出`noindex, follow` | D47处理；Day43显式排除 |
| 空归档 | 仍由Page或term提供Header | WooCommerce原生0结果状态 | 不制造伪商品或自定义URL | 保留原生`role="status"`提示 |

Day43没有新增Hero媒体位。标题或描述有数据就自然输出；没有图片时也不存在破图、空图片容器或布局占位。

## 实际改动

### 运行代码与样式

- `app/public/wp-content/themes/dentall/inc/setup.php`：新增`dentall_enqueue_catalog_assets()`，在`wp_enqueue_scripts`优先级45按请求条件加载归档CSS；防御Woo条件函数缺失，并显式排除搜索。
- `app/public/wp-content/themes/dentall/assets/css/catalog.css`：新增55行/1499字节，含8个叶子规则块和2个媒体查询；只管理Archive Header与排序/商品列表的上下节奏。
- `app/public/wp-content/themes/dentall/style.css`：主题版本由0.19.0升至0.20.0，作为静态资源缓存键。

### Local数据

- 使用WordPress CLI更新原生Shop Page ID 7的`post_title`：`商店` → `Products`。
- 未传入`post_name`；复核页面仍为`publish`、`page`、slug `shop`，访问URL为`http://dentall.local/shop/`。
- 没有修改商品、分类、订单、价格、库存、销量、用户、菜单或其他Option。

### 明确未改

- WooCommerce、Storefront、WordPress和第三方插件核心文件。
- `woocommerce/`模板覆盖、自定义主查询、`pre_get_posts`、`woocommerce_product_query`或每页数量。
- ProductCard内部、网格列数、排序控件内部、分页、搜索样式、筛选侧栏、列表切换、正式Hero/分类内容。
- JavaScript、插件、字段、CPT、ACF、REST、AJAX、Cron、远程请求、支付、物流、税费和缓存配置。

## 验证证据

### 浏览器四端与状态矩阵

| 页面/状态 | 结果 |
|---|---|
| `/shop/` 390 | 内容宽375/滚动宽375；Header 160px、24px padding、H1 `Products`、2件商品 |
| `/shop/` 768 | 753/753；Header 192px、32px padding、2件商品 |
| `/shop/` 1024 | 1009/1009；Header 192px、32px padding、2件商品 |
| `/shop/` 1440 | 1425/1425；Header 224px、40px纵向/48px横向padding、2件商品 |
| 商品分类正常态 | `/product-category/test-d12-products/`显示原生分类H1与2件商品，复用同一骨架 |
| 商品分类空态 | `/product-category/test/`为0件、无排序/结果计数，保留原生英语`role="status"`提示 |
| 排序回归 | 两次选择`price-desc`后顺序稳定为可变商品→简单商品，无溢出，Console为0 |
| 商品搜索隔离 | `/?s=TEST&post_type=product`有2件商品，Day43 `catalog.css`不加载，Yoast为`noindex, follow` |

四端均为单一H1、无重复ID、页面级横向溢出0；Shop Title为`Products - Dentall`，Canonical为`/shop/`，`catalog.css?ver=0.20.0`返回HTTP 200与`text/css`。全页截图中的Admin Bar重复黑条是截图拼接工具伪影，不存在于页面DOM。

### 静态与服务端

- DentAll主题5个PHP文件通过PHP CLI语法检查。
- `catalog.css`花括号10/10，`!important` 0处，旧`.storefront-full-width-content`依赖0处，尾随空格0处。
- 新代码中行内`style`/`script`/事件属性0处，自定义查询0处；子主题`woocommerce/`覆盖目录不存在。
- Woo设置仍为3列、4行，即当前12项/页合同未因Day43改变。
- `git diff --check`通过；仅出现工作区既有LF→CRLF提示，不是语法错误。

### 独立复核

- Code Review：最终P0/P1/P2/P3均为0。初审发现标题区选择器依赖`.storefront-full-width-content`的P2，已改为稳定的`body.woocommerce`作用域并复核关闭。
- Design Review：最终P0/P1/P2/P3均为0。确认四端高度/留白、分类复用、空态退化、语义H1和无溢出；正式Hero、筛选、网格、分页与搜索属于后续范围。
- Test/Scope Review：最终P0/P1/P2/P3均为0，未发现范围越界；独立确认Shop标题/slug/Canonical、原生3列循环、12项/页配置、四端0溢出、无新查询/模板/JS，且既有D25未跟踪文件未触碰。该Agent按收口指令停止新增取证，因此空分类最终DOM、搜索资源、`price-desc`选中顺序与Console沿用主验收和Design Review证据，不冒充第三次动态复演。

## 排错与减法审查

### 两个真实问题

1. 初版标题规则特异性低于Storefront，`text-align`和padding没有按预期生效。修复后使用`body.woocommerce .woocommerce-products-header`，既提高最小必要特异性，也不依赖侧栏是否启用。
2. Woo商品搜索请求中`is_shop()`也可能为真，导致Day43样式误加载。条件中显式加入`is_search()`排除，把搜索视觉责任留给D47。

### 减法结果

- 删除父主题已提供的`ul.products`底部margin清零规则，避免重复声明。
- 运行文件净变化：修改2个既有文件、新增1个CSS文件；净增84行（`setup.php` 29行＋`catalog.css` 55行，版本行1换1）。
- 新增1个函数、0个模板、0个JavaScript、0个查询函数、0个插件/依赖；保留理由仅为“条件加载”和“归档展示职责”两个稳定边界。
- 未为了Day编号创建PHP模块；enqueue仍属于现有setup资源职责，归档CSS因页面生命周期和后续微调范围独立成文件。

### 诊断日志副作用

主Agent与独立Code Reviewer各有一次Windows引号处理错误的只读`wp eval`命令，PHP在执行前即报parse/fatal，并向Git忽略的`app/public/wp-content/debug.log`追加诊断记录；未修改数据库或运行源码。为保留环境审计事实，本轮未擅自清理日志，后续排错时应按时间和命令来源区分这些工具侧记录与页面运行错误。

## 影响与回滚

| 检查面 | 实际影响 | 回滚/后续 |
|---|---|---|
| 数据 | Local一条Page标题写入；无商品/订单写入 | `wp post update 7 --post_title='商店'`可回退标题 |
| URL | `/shop/`、slug与固定链接结构不变 | 部署前再次核对环境中的Shop Page绑定 |
| SEO | H1、Title与面包屑公开文字变为`Products`；Canonical不变 | Staging同步后复测Title、Canonical、robots、Sitemap与内部链接 |
| 性能 | Shop/taxonomy多1个1499字节CSS请求；无新查询、JS、远程请求或Cron | 0.20.0提供缓存失效键；生产缓存和CWV尚未测量 |
| 缓存 | 未改缓存配置；主题版本改变资源URL | 部署后按真实缓存层清理/预热并核对旧CSS不残留 |
| 支付/物流/订单 | 0变更 | 保持现有关闭/未确认边界 |
| 权限/安全 | 无后台入口或用户输入；无nonce/capability新增场景 | Shop标题在目标环境仍由Administrator受控维护 |
| 部署 | 仅Local完成，Staging/Production未同步 | 需同时部署主题文件并单独同步Shop标题，然后重跑代表状态 |

代码回滚需撤销`inc/setup.php`新增enqueue、删除`catalog.css`并把主题版本退回0.19.0；数据回滚与代码回滚彼此独立，不能只回退其中一层后宣称环境完全恢复。

## Day44交接

- 复用D43已经验证的Archive Header、WooCommerce主查询、原生商品循环和D29 ProductCard。
- D44首先解耦“视觉列数”与WooCommerce“每页12项”合同；不能因为修改`loop_shop_columns`而把当前3列×4行的12项/页误改成16项/页。
- D44仍须单独梳理最多3项结果并等待实施确认；本笔记不构成网格、分页、筛选或正式分类内容的授权。

## 可复用核心思想

### 跨平台不变量

归档页应先冻结“标题/描述来源、查询所有权、卡片输出、URL合同和空状态”，再调整视觉。查询负责决定有什么，模板负责结构，CSS负责呈现；把三者混成一次定制会让排序、分页、SEO和后续筛选互相牵连。

### WordPress/WooCommerce当前实现

WooCommerce 11.0.0在主请求中建立商品归档查询，Storefront 4.6.2输出Archive Header、排序、循环和空状态；DentAll 0.20.0只通过`wp_enqueue_scripts`条件加载叶子CSS。Shop Page标题是可编辑内容数据，slug和Canonical是另一份URL合同，修改标题不应顺带重写URL。

### Shopify或其他平台的对应机制

Shopify通常也有集合标题/描述、商品集合查询、主题卡片和资源管线这些职责，但具体Liquid/JSON Template、Collection路由、筛选和SEO输出尚未在本项目验证，均标记为“待验证”。可迁移的是先分离数据、查询、模板、样式和URL责任，不是照搬WordPress Hook或条件函数。
