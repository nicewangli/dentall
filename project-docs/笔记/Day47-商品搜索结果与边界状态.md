---
项目: DentAll WooCommerce
工作日: D47
计划检查点: D47（不自动等于一个完整实际工作日）
日期: 2026-09-03
实际有效工时: 未记录；不使用计划工时代替
验收层级: Local商品搜索请求、原生结果结构、URL与SEO；登录态正常与空结果四端终验通过
状态: 已完成（Day48实施前已补齐空结果实页证据并关闭P2）
tags:
  - DentAll
  - Day47
  - WooCommerce
  - Search
  - SEO
---

# DentAll 每日复盘 D47：商品搜索结果与边界状态

## 相关笔记

- 每日笔记索引：[[README|DentAll每日笔记索引]]
- 前置项目笔记：[[Day46-商品归档分页与URL归一化]]
- Header搜索来源：[[Day33-手机与平板竖屏Header]]
- 当日学习笔记：[[WordPress实战笔记/Day47-WooCommerce商品搜索请求与模板复用]]
- 前置学习笔记：[[WordPress实战笔记/Day46-WooCommerce分页链接与Canonical边界]]
- Header机制学习：[[WordPress实战笔记/Day33-单一导航DOM与购物车Fragment]]
- URL与SEO事实：[[../URL_SEO_MAP|URL与SEO映射]]
- 后续项目笔记：[[Day48-商品分类内容与W8列表回归]]
- 后续学习笔记：[[WordPress实战笔记/Day48-WooCommerce分类描述与SEO模板边界]]

> [!check] 当前结论
> Day47已在Local完成代码实施、真实主查询/模板审计、HTTP边界、12/1搜索分页、SEO和可逆数据清理。DentAll 0.24.0让明确的商品搜索复用D43～D46的Archive Header、ProductCard、2/2/3/4列Grid、顶部结果/排序和底部分页；无结果保留WooCommerce原生`role="status"`并追加两个恢复链接。空值、Unicode纯空白、数组和超过1600字节的商品搜索302到Shop；有效单结果保留Woo原生302。#120～#130已重新移入回收站，发布基线恢复2项。登录态真实有结果页已在390/768/1024/1440通过2/2/3/4列、0横向溢出、44px排序控件和唯一工具栏；Day48实施前又补齐真实空结果页四端CTA几何、长词、44px、键盘Focus、Console和截图，开放P2已关闭，Day47现按Local授权范围完成。

## 授权与实施边界

用户于2026-09-03明确回复：

> 确认按推荐范围实施 Day47，仅限 Local，并授权临时恢复 #120～#130 测试商品，验收后重新移入回收站。

本轮规范化实施合同：

- 仅处理`is_search()`、商品归档和`post_type=product`同时成立的商品搜索。
- 复用WooCommerce原生主查询、标题、面包屑、循环、ProductCard、排序和分页，不建立第二查询。
- 有结果时只保留一组顶部结果/排序和一组底部分页；排序与分页保留搜索上下文。
- 无结果时保留原生语义通知，并增加`Browse All Products`与`Back to Home`两个真实链接。
- 空值、纯空格、非标量关键词和超过1600字节的商品搜索临时302到Woo Shop；1600字节仍按有效请求处理。
- 保留WooCommerce有效单结果302到商品详情的原生行为。
- 搜索页维持Yoast当前`noindex, follow`，不自造Canonical或`rel`。
- 允许临时恢复已知夹具#120～#130，验收后逐项核对并重新移入回收站；不永久删除。

明确不做：

- 不覆盖WooCommerce模板，不新增JavaScript、插件、依赖、字段、持久化设置、自定义查询或搜索服务。
- 不把SKU、分类或全局属性加入第一版搜索算法；当前经典搜索仍按WordPress/WooCommerce既有可搜索内容工作。
- 不生成空态插画，不实现AJAX联想、拼写建议、筛选或D48正式分类内容。
- 不改变Shop、商品taxonomy、商品固定链接、支付、物流、订单或非Local环境。
- 不提交Git，不部署Staging/Production，不关闭Coming Soon或修改索引保护。

## 当日最多3项验收结果

1. [x] 商品搜索只命中目标请求类型，Header GET字段、正常/单结果/空结果、非法输入和普通搜索隔离形成明确合同。
2. [x] 真实13项阶段完成12/1分页、排序参数、Page 1直链、特殊字符、SEO和清理；最终#120～#130全部回收，发布商品与term 18恢复2项。
3. [x] 登录态真实有结果页与空结果页均在390/768/1024/1440完成布局、横向溢出、44px、唯一结构、键盘Focus、Console和截图终验。

## 7个专注周期执行记录

| 周期 | 目标 | 实际结果 |
|---|---|---|
| C1 | 读取规范、D33/D43～D46代码、Woo/Storefront输出和Local状态 | 冻结精确商品搜索谓词、原生模板复用和SEO/数据边界 |
| C2 | 完成功能确认后的最小实现 | 只修改4个既有运行文件；新增非法请求302与空结果恢复导航 |
| C3 | 恢复真实多页夹具 | 恢复前逐项核对#120～#130的类型、Trash状态、SKU和fixture meta；恢复后发布商品13项 |
| C4 | 主查询、DOM、分页与排序验收 | TEST搜索形成第一页12项/第二页1项；顶部一组结果/排序、底部一组分页，参数保持通过 |
| C5 | URL、SEO、安全和隔离验收 | 302边界、单结果原生跳转、转义、noindex、无Canonical/rel、Sitemap和普通搜索隔离通过 |
| C6 | 恢复数据并做最终状态回归 | 11项全部重新Trash；发布商品2项、term 18 count=2；Shop/taxonomy和两类搜索回归通过 |
| C7 | 独立复核、减法审查与文档 | Code/Test/SEO无P0/P1/P2；Design确认四端结果页通过，空结果浏览器链路超时形成1项证据P2 |

## 实现结果

### 请求识别与资源加载

`inc/setup.php`继续在`wp_enqueue_scripts`阶段加载既有`catalog.css`，但目标从“非搜索的Shop/taxonomy”扩展为两类明确请求：

```php
$is_catalog_archive = ! is_search() && ( is_shop() || is_product_taxonomy() );
$is_product_search  = is_search()
	&& is_post_type_archive( 'product' )
	&& 'product' === get_query_var( 'post_type' );
```

第三个严格比较防止普通`?s=...`或异常`post_type`进入商品目录样式。搜索继续使用同一WooCommerce归档模板和语义DOM，因此不复制Grid、ProductCard或工具栏CSS。

### 非法搜索的302边界

`dentall_redirect_invalid_product_search()`挂在`template_redirect`优先级1，早于Yoast的搜索URL处理和WooCommerce优先级10的单结果跳转。它只读取公共GET关键词：

- 非字符串直接判无效；`wp_unslash()`另存可读关键词。
- 使用Unicode空白表达式同时覆盖ASCII空白、NBSP和全角空格；不把字面`%aa`或仅含HTML标签的非空搜索词误判为空。
- 同时检查WordPress加斜杠后的原始请求值与`wp_unslash()`后的值；任一超过1600字节即判无效。这与`WP_Query`按加斜杠查询变量执行1600字节保护的实际行为一致。
- 使用`wc_get_page_permalink( 'shop' )`取得真实目标，`nocache_headers()`后通过`wp_safe_redirect(..., 302, 'DentAll')`响应。

这是临时导航，不是永久URL迁移，所以没有使用301。该Hook发生在主查询之后，能阻止错误页面和误入单结果跳转，但不能减少已经发生的一次查询；查询前限流属于未来独立范围。

### 工具栏、分页与空结果

- `dentall_enable_catalog_ordering_labels()`在`wp`阶段识别商品搜索，沿用D45/D46组合：循环前移除重复分页，循环后移除重复结果数，不再增加底部排序。
- 顶部继续由WooCommerce输出一个`role="status"`结果数和一个带可见`Sort by`标签的GET表单。
- 底部继续由WooCommerce原生分页模板输出；`dentall_catalog_pagination_args()`把D46的页码窗口、Previous/Next和Page 1归一化复用于商品搜索。
- 无结果时WooCommerce原生`woocommerce-info[role="status"]`保持不变；优先级20的DentAll Action另行输出语义`nav`，两个URL分别来自Woo Shop API和`home_url( '/' )`。
- CTA不塞进状态通知，避免辅助技术重复朗读时夹带操作控件；普通搜索和空taxonomy不输出该导航。

### CSS与响应式职责

- Archive H1和商品搜索面包屑增加`overflow-wrap:anywhere`，处理长英文、无断点字符串和编码后的特殊字符。
- 空结果CTA容器使用Flex与`flex-wrap`；每个按钮`flex:1 1 14rem`，窄屏自然纵向、空间足够时自然并排，不新增断点或第二套DOM。
- 按钮继续复用全局至少`2.75rem`（当前16px根字号为44px）和3px `focus-visible`合同；Home链接只在局部改为透明次按钮。
- 商品Grid、工具栏和分页继续使用D44～D46已验的Mobile First规则，未复制任何响应式布局。

## 真实请求、DOM与SEO证据

### HTTP响应矩阵

| 请求 | 最终状态 | 跳转责任 |
|---|---|---|
| `?s=&post_type=product` | 302到`/shop/` | `X-Redirect-By: DentAll` |
| 3个空格 | 302到`/shop/` | DentAll |
| `s[]=x` | 302到`/shop/` | DentAll |
| 1600个ASCII字节 | 200 | 不触发DentAll边界 |
| 1601个ASCII字节 | 302到`/shop/` | DentAll |
| 800个撇号（加斜杠后1600字节） | 200 | 不触发DentAll边界 |
| 801个撇号（加斜杠后1602字节） | 302到`/shop/` | DentAll |
| 仅NBSP或全角空格 | 302到`/shop/` | DentAll |
| 字面`%aa`或仅`<b></b>` | 200 | 合法非空词，不误判为空 |
| `?s=TEST&post_type=product` | 200，多结果 | Woo主查询 |
| `?s=Fixed&post_type=product` | 302到`/product/test-d12-simple-fixed-pack/` | WordPress/WooCommerce原生单结果行为 |
| `?s=NO-DAY47-MATCH&post_type=product` | 200，空结果 | Woo主查询 |
| 普通`?s=TEST` | 200 | 普通WordPress搜索，不进入目录规则 |

### 真实13项多页阶段

- `TEST`共13项，Page 1输出12张ProductCard，Page 2输出1张，`max_num_pages=2`。
- 两页均只有一个H1、一个面包屑、一个排序表单和一个结果数；只有一组底部分页。
- Page 1到第二页链接为`/page/2/?s=TEST&post_type=product`；Page 2返回Page 1时直达`/?s=TEST&post_type=product`，不制造`/page/1/`中转。
- `orderby=price`随分页保留；排序仍使用Woo主查询，未创建第二查询。
- 越界Page 3被WordPress标记为404；不加载商品目录主内容。

### 最终2项基线

- `TEST`命中2项、200；一个顶部排序表单、一个结果数、0分页，加载`catalog.css`。
- 空结果为200；一个原生`role="status"`、一个恢复导航、两个真实链接，0排序/结果数/Grid/分页。
- Header商品搜索表单唯一、`method=get`、action为站点首页，包含`name=s`搜索输入与隐藏`post_type=product`。
- Shop与term 18分类均为2项、一个结果数、一个排序、0实际分页导航、加载`catalog.css`，不含D46夹具文本。
- 普通搜索不加载`catalog.css`，不输出Woo商品循环、目录工具栏或空结果CTA。

### 安全与SEO

- 特殊查询`牙科 & <script>alert(1)</script> "`在H1和面包屑中只表现为文本，审计得到嵌套`script`节点0、重复ID 0。
- 多结果、无结果、排序、Page 1/Page 2均由Yoast输出`noindex, follow`；Canonical、`rel=next`和`rel=prev`均为空。
- Sitemap index与Product Sitemap均无`s=`、`post_type=product`或查询参数URL。
- 没有自制搜索Canonical到Shop，因为搜索结果和Shop不是等价内容；302只处理无法形成有效搜索意图的输入。

## TEST商品生命周期

| 阶段 | 事实 |
|---|---|
| 开始前 | Local发布商品2项；#120～#130均为Trash |
| 恢复闸门 | 11项逐一核对`product`类型、Trash状态、`TEST-D46-PAGE-01`～`11` SKU、`_dentall_test_fixture=day46-pagination`和原发布状态 |
| 恢复 | 使用WordPress公开恢复API回到原`publish`状态；发布商品增至13项，仅用于多页测试 |
| 搜索验证 | `TEST`形成12/1两页；没有改价格、库存、销量、分类、图片或正式内容 |
| 清理闸门 | 回收前再次一次性核对ID、类型、publish状态、SKU和fixture meta；任一不匹配则整体停止 |
| 最终状态 | 11/11调用`wp_trash_post()`成功；#120～#130均为Trash，发布商品2项、Trash商品11项、term 18 count=2 |

回收站不是永久存档。未来若人工清空或由保留策略清理，这11项会不可恢复；不得把它们重新发布并当成正式商品。

## 浏览器证据与P2关闭

### 已有证据

- 当前真实Woo模板的主查询、H1、面包屑、工具栏、分页、空通知、CTA、Header表单、SEO Head和重复ID均由CLI渲染后的DOM解析验证。
- 登录态真实有结果页已完成四宽几何：390为159.5px×2列/16px gap，768为332.5px×2列/24px gap，1024为299px×3列/24px gap，1440为296px×4列/24px gap；四档`documentElement`与`body`均无横向溢出，排序select均44px，H1 computed `overflow-wrap:anywhere`，且均为一组排序/结果信息、0实际分页。
- D43～D46已经在同一子主题、同一Woo归档DOM和同一`catalog.css`上完成390/768/1024/1440真实Shop/taxonomy Grid、工具栏与分页验证。
- D47新增CSS只有长词换行和四个空结果CTA规则块；静态审查确认34/34花括号、0个`!important`、0个新增媒体查询。
- 已保留`project-docs/tests/fixtures/day47-product-search/index.html`，用于浏览器恢复后复演长词与CTA几何。

### Day48实施前补验

真实URL `/?s=NO-DAY47-MATCH&post_type=product`在登录态Chrome完成：

- 390px的文档client/scroll为375/375；CTA容器335×100px，两个按钮各335×44px并纵向堆叠。
- 768px为753/753，1024px为1009/1009，1440px为1425/1425；三档CTA容器均544×44px，两个按钮各266×44px并排。
- 四端均只有1个原生状态、1个恢复导航，0 Grid、排序、结果数和分页；H1与面包屑长词正常换行。
- 真实键盘Tab依次聚焦两个链接，均为3px深蓝实线外框＋3px偏移；Console读取为`[]`。
- 截图：`outputs/day48/d47-empty-search-{390,768,1024,1440}.png`和`d47-empty-search-focus-1440.png`。

原P2只代表证据缺口，不是页面缺陷；现已按原关闭条件补齐并回写项目笔记、状态、测试计划与索引。

## 独立复核

- Code/Security Review：终审确认修复后的空/ASCII空白/NBSP/全角空格、非标量、ASCII 1600/1601和撇号加斜杠1600/1602矩阵与Woo原生单结果分支均正确，未发现P0/P1/P2。商品搜索谓词目前跨两个文件重复，列为P3维护观察项；达到新的真实复用点时再评估公共辅助函数，当前不为消除短重复增加加载耦合。
- Design/Responsive Review：原P2只指空结果实页证据缺口；Day48实施前补齐四端、Focus、Console和截图后关闭。最终P0/P1/P2=0，未发现布局或隔离代码缺陷。
- Test/SEO/Data Review：确认#120～#130全部Trash、发布商品2项；重定向责任、1600/1601边界、单/多/零结果、普通搜索隔离、Header表单、noindex、无Canonical/rel、Sitemap和Shop/taxonomy回归通过，未发现P0/P1/P2。最终2项状态无法重新生成真实12/1页，沿用本轮夹具恢复阶段证据并作为P3证据边界。

## 静态检查与减法审查

| 运行文件 | D46基线 | D47当前 | 净变化 |
|---|---:|---:|---:|
| `inc/setup.php` | 68行 | 75行 | +7行 |
| `inc/storefront-hooks.php` | 335行 | 432行 | +97行 |
| `assets/css/catalog.css` | 205行 | 230行 | +25行 |
| `style.css` | 928行 / 0.23.0 | 928行 / 0.24.0 | 0行；只改版本 |

运行层合计净增129行，修改4个既有文件；新增0个运行文件、2个函数、4个CSS规则块、0个媒体查询、0个模板、0个JavaScript、0个查询、0个插件或依赖。另新增一个161行的只读CLI审计和一个33行的非运行时HTML响应式夹具；两者只为可重复测试，不由WordPress加载。审计脚本明确不覆盖`template_redirect`，缺少DOM扩展时会失败；重定向始终另用真实HTTP验证。

保留理由：非法请求跳转与无结果恢复导航属于不同生命周期/输出职责，不能合成一个函数；CTA的容器、通用按钮和次按钮分别解决布局、宽度与视觉层级，长词规则必须限定到Breadcrumb。没有抽出重复的商品搜索辅助函数，因为当前仅三处短谓词，额外抽象不会减少生命周期判断成本；后续若第四处真实复用再评估。

实际检查：

- PHP 8.2.9 CLI对`setup.php`、`storefront-hooks.php`和审计脚本执行`php -l`，均无语法错误。
- `catalog.css`为34/34对花括号、0个`!important`；未新增硬编码颜色。
- `git diff --check`通过，仅显示Windows工作区既有LF/CRLF提示。
- 主题版本由WordPress回读为0.24.0；临时公开夹具已删除，没有遗留测试服务器或认证文件。

## 数据、URL、SEO、缓存与交易影响

| 检查面 | 结论 |
|---|---|
| 数据 | 仅临时恢复并重新Trash 11个既有Local TEST商品；最终发布2项、Trash 11项；无订单、客户、销量、价格、库存交易或正式内容写入 |
| URL | 新增非法商品搜索302到动态Shop URL；有效单结果仍原生302；未改变Shop、taxonomy、商品Slug或固定链接结构 |
| SEO | 商品搜索保持`noindex, follow`、无Canonical/rel且不进Sitemap；未改Title模板、Meta、Schema、robots配置或正式索引设置 |
| 缓存 | 搜索页比D46旧基线多加载既有`catalog.css`请求；主题0.24.0改变资源版本参数。未验证非Local CDN、查询参数缓存或Web服务器对1601字节请求是否先返回414 |
| 性能 | 无新查询、JS、远程调用、Cron或autoload Option；没有实际测量前不宣称性能零影响或提升 |
| 支付/物流/订单 | 无影响；未进入购物车、结账、支付、税费、运费、订单或退款流程 |
| 部署 | 仅Local；无Git提交、分支、Staging/Production部署、DNS或Coming Soon变更 |

## D48衔接

Day48已先关闭本笔记的空结果实页P2，再按独立授权完成分类标题/描述机制、Yoast模板与W8回归，见[[Day48-商品分类内容与W8列表回归]]。D47没有实现筛选、正式分类内容、SKU搜索或AJAX搜索；这些仍不能因搜索骨架可用而自动进入后续范围。

## 可复用核心思想

### 跨平台不变量

站内搜索不是“一个输入框加结果列表”，而是请求类型、输入边界、结果状态、排序分页、恢复路径和索引策略组成的合同。空输入、非标量、超长词、单结果、多结果、零结果和越界页必须分别验证；重定向、Canonical和noindex解决的问题也不能互相替代。

### WordPress/WooCommerce当前实现

DentAll让Header GET表单声明`post_type=product`，让WordPress/WooCommerce主查询、归档模板、循环和分页负责数据与语义，让子主题在`wp_enqueue_scripts`、`wp`、`template_redirect`和`woocommerce_no_products_found`的正确生命周期做最小收敛。URL来自Woo/WordPress API，公共GET先做类型、Unicode空白和加斜杠前后字节长度验证，输出按属性、URL和文本上下文转义。

### Shopify或其他平台的对应机制

其他平台同样要区分商品搜索与全站内容搜索、无效请求与有效零结果、列表UI与索引策略，并验证排序/分页是否保留查询上下文。Shopify的Search模板、Liquid分页、搜索建议、URL参数和索引控制在DentAll尚未实际验证，均标记为待验证；可以迁移测试矩阵和职责边界，不能直接迁移Woo Hook名或URL格式。
