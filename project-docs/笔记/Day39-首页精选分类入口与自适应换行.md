---
项目: DentAll WooCommerce
工作日: D39
计划检查点: D39（不自动等于一个完整实际工作日）
日期: 2026-08-29
实际有效工时: 未记录；不使用计划工时代填
验收层级: Local技术验收（通过）
状态: 原生菜单数据源、真实CategoryCard、0/1/9/9+自适应换行与边界验证已完成；正式分类内容、图片授权和非Local部署待业务验收
tags:
  - DentAll
  - Day39
  - Homepage
  - Product-Category
  - Responsive
---

# DentAll 每日复盘 D39：首页精选分类入口与自适应换行

## 相关笔记

- 每日笔记索引：[[README|DentAll每日笔记索引]]
- 前置项目笔记：[[Day38-手机与平板首页Hero精调]]
- 卡片展示合同：[[Day29-三类卡片组件契约]]
- 当日学习笔记：[[WordPress实战笔记/Day39-菜单驱动的分类查询与Flex换行]]
- 后续项目笔记：[[Day40-首页方案Page映射与四端区域]]

> [!success] 当前结论
> D39已在Local完成：首页新增原生`Homepage categories`菜单位置，菜单只负责选择和排序，卡片名称、链接、图片、缺图占位及非空状态仍来自真实`product_cat`。总分类数量没有硬编码上限；同一Flex列表在390最多3项/行、768最多5项/行、1024和1440最多9项/行，超过后自动换行，末行整体居中。你绑定的menu ID 27当前输出唯一有效分类`TEST D12 Products`，无需为了技术验收再新增分类。

## 用户授权与后台绑定

实施授权：

> 同意按Day39推荐范围实施，然后如果需要我新增分类来检验页面效果就叫我。

随后用户在`外观 → 菜单`完成：

- 菜单：`TEST D39 Homepage Categories`。
- 菜单项：`TEST D12 Products`商品分类。
- 显示位置：`Homepage categories`。
- 用户反馈：`已绑定`，并提供后台截图。

授权和责任边界：

- 代码负责注册菜单位置、过滤有效分类、复用WooCommerce原生模板、空态和四端自适应布局。
- Administrator负责原生菜单的选择与排序；Website Manager当前没有`edit_theme_options`，不扩大权限。
- 业务方以后维护正式分类名称、图片、商品归属和显示顺序；当前TEST分类不是正式内容。
- 不新增正式分类、ACF、CPT、插件、模板覆盖、JavaScript、轮播、横向滑动、D40 Solutions或D41商品/信任区。
- 不修改Staging、Production、支付、物流、税费、缓存策略或真实业务数据。

## 今日三个验收结果

- [x] 首页分类入口接入真实`product_cat`：专用菜单决定选择和顺序，只输出顶级、非空、链接有效且不重复的分类；0项时整个区域不输出。
- [x] 同一语义DOM按实际数量自动换行：390/768/1024/1440每行上限分别为3/5/9/9，1项和不满一行的末行居中，10项以上继续换行而不截断。
- [x] 正常、缺图、长名称、隐藏count、可见键盘Focus、无横向溢出、真实菜单绑定与WooCommerce循环状态恢复均取得Local证据；子主题版本升至0.17.0。

## 进度真实性检查

- 自然日期：2026-08-29。
- 实际有效工时：未记录；没有用计划工时或D39编号代填。
- 本日最高验收层级：Local技术验收通过。
- 真实业务内容验收：未通过也未冒充；当前只有一个TEST分类，缺图使用WooCommerce占位图。
- 非Local状态：Staging/Production未部署，匿名请求仍受WooCommerce Coming Soon保护。

## 7个专注周期的实际落点

| 周期 | 计划 | 实际结果 | 证据 | 实际用时 |
|---|---|---|---|---|
| C1 | 复核D29合同、分类数据与设计证据 | 冻结“菜单选项＋真实term事实＋Flex换行”的最小方案 | D29/D38笔记、Local只读数据 | 未记录 |
| C2 | 注册数据入口 | 新增`Homepage categories`菜单位置和Homepage优先级20回调 | `inc/homepage.php` | 未记录 |
| C3 | 查询与过滤 | 批量读取菜单、去重并过滤自定义项、子分类、空分类和失效链接 | PHP集成测试 | 未记录 |
| C4 | 原生CategoryCard输出 | 复用`content-product-cat.php`和Woo循环，不覆盖模板 | 登录态真实首页 | 未记录 |
| C5 | 自适应布局 | Flex在48/64rem渐进到3/5/9项每行，末行居中 | `homepage.css`与10项夹具 | 未记录 |
| C6 | 边界与浏览器验证 | 0/1/4/8/9/10项、四端、缺图、长标题、count和Focus复核 | PHP、静态夹具、截图 | 未记录 |
| C7 | 独立Review、减法和文档 | 修复Woo全局循环恢复边界，更新状态、索引和学习笔记 | 专项Review与本笔记 | 未记录 |

## 实际实现

### WordPress与WooCommerce数据链

1. `after_setup_theme`注册`homepage_categories`菜单位置。
2. Administrator在原生菜单后台把商品分类加入专用菜单并保存顺序。
3. `get_nav_menu_locations()`找到绑定菜单，`wp_get_nav_menu_items()`按`menu_order`读取项目。
4. 只接受`taxonomy/product_cat`，先去重ID，再用一次`get_terms()`批量取得`parent=0`、`hide_empty=true`、`pad_counts=true`的真实term。
5. 查询结果重新按菜单ID顺序映射；链接错误、空字符串或失效项被跳过。
6. Storefront Homepage在Hero之后触发`dentall_homepage_categories()`，复用WooCommerce `content-product_cat.php`输出分类卡。

菜单项目的自定义标题、URL和层级不替代term事实。分类改名、换图、商品计数或链接变化时，首页继续读取真实分类；菜单只控制“选哪些、按什么顺序”。

### HTML和模板职责

- 区域是带屏幕阅读器标题的`section`，内部继续使用WooCommerce原生`ul.products > li.product-category > a`结构。
- 图片、缺图占位、标题和count标记由WooCommerce模板/函数生成；子主题不复制或覆盖第三方模板。
- count只在`.dentall-home-categories`内隐藏，不改变Shop分类区或其他WooCommerce页面。
- 0个有效分类时在输出`section`之前返回，不制造空标题、空白占位卡或假的Loading。

### 行内规范与总数量自适应

| 验收宽度 | 生效区间 | 每行最大项数 | 9项示例 | 10项示例 |
|---:|---|---:|---|---|
| 390px | `<48rem` | 3 | 3＋3＋3 | 3＋3＋3＋1 |
| 768px | `>=48rem`且`<64rem` | 5 | 5＋4 | 5＋5 |
| 1024px | `>=64rem` | 9 | 9 | 9＋1 |
| 1440px | `>=64rem` | 9 | 9 | 9＋1 |

规范不是把总数量写死为9：

- 菜单选中多少个有效分类，PHP就输出多少个，没有`array_slice()`或查询`number=9`。
- CSS只定义当前宽度的卡片基准宽度和每行上限；`flex-wrap`负责继续换行。
- `justify-content:center`让整行及最后不足一行的项目居中；卡片保持当前断点的统一宽度，不把最后1～2项拉伸成大卡。
- 390/768/1024/1440是验收锚点；真正的实现只有基础、48rem和64rem三个布局层。

### WooCommerce循环状态边界

WooCommerce原生分类模板会递增全局循环索引并读取`columns`。D39只在当前区域临时把桌面列数设为`min(9, 分类总数)`，渲染前记录`$GLOBALS['woocommerce_loop']`是否存在及原值，`finally`中精确恢复；这避免D40/D41或未来首页商品循环继承错误列数或索引。

## 正常与异常状态

| 状态 | 当前行为 | 证据/边界 |
|---|---|---|
| 0个有效分类 | 整个区域不输出 | 临时过滤菜单位置后HTML为0字节 |
| 1个有效分类 | 当前断点统一卡宽并居中 | 真实首页menu ID 27与四端夹具 |
| 9个分类 | 390为3＋3＋3、768为5＋4、1024/1440为单行9项 | 四端9项截图 |
| 10个及更多 | 不截断，自动形成新行且末行居中 | 10项夹具与几何检查 |
| 自定义链接/非分类项 | 跳过 | PHP类型白名单 |
| 子分类 | 跳过 | `parent => 0` |
| 空分类 | 跳过；子级商品可通过`pad_counts`计入父级 | `hide_empty`＋`pad_counts` |
| 重复菜单项 | 只保留第一次出现位置 | `$seen_ids`去重 |
| 失效链接 | 跳过，不把`WP_Error`交给模板 | 链接空值过滤测试 |
| 缺图 | WooCommerce原生占位图，保持1:1稳定区域 | 真实`TEST D12 Products`与夹具 |
| 长标题 | 允许换行，不撑破卡片或页面 | 10项长名称夹具 |
| count | DOM仍由WooCommerce生成，首页局部`display:none` | 真实count `(2)`计算样式为`none` |
| WooCommerce停用/函数不可用 | Homepage回调静默返回 | 函数守卫；未在当前活动站点停用Woo制造证据 |

## 验证证据

### PHP与Local数据

- Local版本：WordPress 7.0.4、WooCommerce 11.0.0、Storefront 4.6.2、PHP 8.2.29、DentAll 0.17.0。
- 实际绑定：`homepage_categories => menu ID 27`，菜单项目1个。
- 有效输出：term ID 18，`TEST D12 Products`，当前count 2，URL为`/product-category/test-d12-products/`。
- 真实渲染：1个`section`、1个分类`li`、`columns-1`、1个缺图占位、1个count标记和正确分类链接。
- 空菜单位置输出0字节；强制空链接后有效分类为0。
- 已存在和原先不存在两种Woo循环全局均精确恢复。
- 缓存已热的CLI单次样本为3个查询增量、约2.20ms，只用于本机相对观察，不外推生产性能。
- PHP lint通过。Local CLI仍打印既有`php_imagick.dll`加载警告；当前占位图由可用图像路径正常输出，本轮未修改PHP扩展。

### CSS与非持久化夹具

- `homepage.css`为272行、6219字节、46/46个花括号、0个`!important`。
- 10项夹具共有15个唯一ID、0个行内`style`、`script`或事件属性。
- 9项四端结果：390为3＋3＋3，768为5＋4，1024/1440为9；均无横向溢出。
- 1/4/8/9/10项在四端均按3/5/9上限换行，末行居中；1项在四端中心偏差为0，并具有可见3px实线Focus。
- count在首页局部隐藏；缺图和长标题未破版。
- 截图证据：`outputs/day39-categories-9-390.png`、`-768.png`、`-1024.png`、`-1440.png`。

### 登录态真实首页

- 因匿名页面由Coming Soon接管，使用用户现有登录态Chrome打开Local首页，只做只读检查。
- 默认1905px视口实际存在1个分类区和1张分类卡；区域紧跟Hero，列表为Flex，页面横向溢出0。
- 卡片链接、标题、Woo占位图Alt、隐藏count和`homepage.css?ver=0.17.0`均与PHP证据一致。
- Chrome扩展在批量切换四端时调试连接超时并脱离；已恢复浏览器默认视口并清理两个验收标签页。四端多数量结论来自加载同一Storefront/DentAll CSS与相同DOM的静态夹具，不把失败的登录态批量检查写成通过。

## 独立Review与修复

- 数据/模板专项确认：菜单顺序映射、类型白名单、顶级非空过滤、链接预检、Woo停用守卫、原生权限/Nonce和模板复用方向正确。
- 初版发现Woo循环结束后直接`wc_reset_loop()`会抹掉调用前状态；已改为快照＋`finally`精确还原，并补充两种状态测试。
- 独立Code Review发现P1：WordPress完成`hide_empty`后，WooCommerce仍可能按目录可见性把分类count重算为0；已在查询后按Woo核心语义再次剔除count 0，并以“正向1项、模拟不可见后0项/0字节”关闭。
- 初版10项仍声明`columns-10`；已改为最多`columns-9`，让Woo原生`first/last`类更接近1024/1440实际行结构，数据仍完整输出。
- 测试夹具一度误删Woo 11真实输出的10个分类链接`aria-label`；已恢复并由独立测试复核关闭P3。
- Code Review最后一个P3是CSS顶部注释仍写“只承载Hero”；已改成“多个全宽区块由各区块内层复用公共容器”。
- 最终独立Code Review与测试/设计复核均为P0/P1/P2/P3=0。

## 减法审查

- 运行文件净增0：继续复用D37建立的`inc/homepage.php`和`assets/css/homepage.css`，只修改这两个文件及`style.css`版本。
- PHP净增2个函数、1个菜单位置、1个Homepage回调和152个物理行；保留理由是选择/排序数据合同、批量term过滤、Woo前台可见count二次过滤、原生模板输出、空态和循环隔离，未创建通用查询框架。
- Homepage CSS相对D38的223行净增49行、1393字节、8个规则块；只承担分类区域、Storefront clearfix/float复位、3/5/9宽度和局部count隐藏。
- 新增1个92行非运行时HTML夹具，用于10项、缺图、长标题、count、Focus和四端换行；不写数据库、不新增路由或前台请求。
- JavaScript、模板覆盖、插件、依赖、字段、Option、Cron、远程请求和持久化自动化均净增0。
- 没有为390/768/1024/1440复制四套DOM，也没有新增分类来制造测试数据。

## 影响与回滚

| 检查面 | 结论 |
|---|---|
| 数据/权限/安全 | 代码只读菜单与term；用户通过原生Administrator菜单后台完成绑定，核心处理Capability与Nonce。未给Website Manager新增权限 |
| URL/SEO | 不创建或改写分类URL、Canonical、Schema；首页新增指向现有TEST分类的内部链接。正式上线前必须替换/审核TEST分类、名称、图片和目标URL |
| 缓存 | 主题版本升至0.17.0以刷新现有Homepage CSS查询版本；未改页面缓存或对象缓存配置 |
| 性能 | 没有新CSS/JS请求、远程请求或Cron；每个可见分类会产生图片请求，菜单/term/图片元数据读取受WordPress缓存影响。没有生产或CWV前后测量，不宣称零影响 |
| 支付/物流/订单/邮件 | 不适用且未改变 |
| 部署 | 仅Local；Staging/Production未部署。源码可回滚三个运行文件，后台可取消菜单位置绑定而不删除分类 |

## 可安全微调与DevTools路径

1. 在后台`外观 → 菜单 → 管理位置`确认`Homepage categories`绑定；拖动菜单项只改变顺序，不要用自定义链接伪造分类。
2. 在Elements定位`.dentall-home-categories ul.products`，查看Computed `display:flex`、`flex-wrap`、`justify-content`和卡片`flex-basis`。
3. 临时增删夹具中的`li`观察换行；真实业务分类由业务方在后台维护，不为视觉测试随意新建正式term。
4. 若调整每行数量，先判断是公共CategoryCard内部规则还是Homepage局部布局，再回源码修改，并复查0/1/9/10项、390/768/1024/1440和Shop分类展示。
5. DevTools临时修改刷新即失效；不得修改WooCommerce、Storefront或WordPress核心文件。

## 未完成与后续衔接

1. 当前只有一个TEST分类且没有正式分类图片；真实业务名称、图片授权、商品归属和排序仍由业务方后续验收。
2. 无需现在新增分类：10项夹具已覆盖总量和四端布局。若以后需要验证后台真实排序或正式图片组合，再由用户提供/创建代表分类。
3. D40先按项目规则只读梳理Solutions Page数据源、字段映射、顺序、空态和四端证据；D39授权不自动覆盖D40实施。
4. Staging/Production主题部署、缓存和公开SEO输出仍走后续发布闸门。

## 可复用核心思想

### 跨平台不变量

- “选择与排序”和“内容事实”应分离：运营清单决定展示哪些对象，名称、链接、图片和有效状态仍来自唯一业务数据源。
- 响应式布局应由实际条目数量和容器宽度驱动；每行上限不是总数据上限，末行也不应被强行拉伸。
- 测试数据不足时可用不持久化夹具验证骨架，但必须再用至少一个真实对象确认数据链，不能把夹具当业务验收。

### WordPress/WooCommerce当前实现

- WordPress原生Menu Location提供选择和顺序；WooCommerce `product_cat`和`content-product_cat.php`提供分类事实与原生卡片输出。
- DentAll子主题只在Homepage回调中组合两者，并用局部CSS完成Flex换行；不改核心、不覆盖模板、不把展示逻辑放进`dentall-core`。
- WooCommerce循环使用请求级全局状态，局部复用模板时必须精确恢复调用前状态，不能假设本区块永远是页面最后一个循环。

### Shopify或其他平台

- 可迁移的是“可配置精选清单＋真实Collection/Category对象＋响应式组件”的分层；Shopify导航、Collection选择器、Section schema和发布机制本日未验证，标记为待验证，不进入DentAll第一版范围。
