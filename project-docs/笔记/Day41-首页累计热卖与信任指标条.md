---
项目: DentAll WooCommerce
工作日: D41
计划检查点: D41（不自动等于一个完整实际工作日）
日期: 2026-08-31
实际有效工时: 未记录；不使用计划工时代替
验收层级: Local代码、只读数据合同、四端夹具与独立Review完成；真实正销量和整页相邻区块留D42
状态: 已完成用户授权的Local最小范围
tags:
  - DentAll
  - Day41
  - Homepage
  - WooCommerce
  - Responsive
---

# DentAll 每日复盘 D41：首页累计热卖与信任指标条

## 相关笔记

- 每日笔记索引：[[README|DentAll每日笔记索引]]
- 前置项目笔记：[[Day40-首页方案Page映射与四端区域]]
- 商品卡展示合同：[[Day29-三类卡片组件契约]]
- 当日学习笔记：[[WordPress实战笔记/Day41-累计销量查询与首页展示边界]]
- 前置学习笔记：[[WordPress实战笔记/Day40-菜单驱动的Page映射与原生摘要]]
- 后续项目笔记：[[Day42-首页全链路校准与M4技术验收]]

> [!success] 当前结论
> D41授权范围已在Local落地。首页在Solutions后按WooCommerce累计`total_sales`降序读取最多5个真实可见商品，复用原生ProductCard；真实累计销量为0时整个Best Sellers区保持0输出，不伪造商品或销量。设计稿五项Trust数据与五枚同风格SVG图标已按用户要求还原，但因事实尚未由业务证明，只在`WP_ENVIRONMENT_TYPE=local`输出，非Local为0。商品横向浏览使用纯CSS scroll snap，不增加JavaScript；390/768/1024/1440分别完整容纳1/3/4/5张卡，页面级横向溢出均为0。

## 授权与范围

用户于2026-08-31明确授权：

> 确认，trust我希望你直接按照设计稿，包括图标的提取和数据，先按照设计稿的样子写好，后续我们需要改动再改

该授权落实为以下边界：

- Best Sellers使用WooCommerce真实累计销量，最多5项；不创建测试订单、不直接修改`total_sales`、不使用设计稿假商品补空位。
- Trust按设计稿还原五项数字、文案和图标，但统一视为Local-only TEST视觉证据；业务确认前不能进入Staging或Production。
- 继续复用D29 WooCommerce ProductCard和既有Design Token；不新增轮播库、JavaScript、模板覆盖、插件、CPT、ACF或后台入口。
- Newsletter继续使用D35/D36既有Local不可提交测试壳层，本轮不改表单、数据提交、社交或支付内容。
- 不修改数据库、订单、价格、库存、支付、物流、正式URL、SEO设置或非Local配置。

## 最多3项验收结果

- [x] Best Sellers只读取已发布、无密码、目录可见且累计销量大于0的真实商品，固定按累计销量排序、最多5项；0项时整区0输出，URL中的排序与评分参数不能改写结果。
- [x] 复用WooCommerce原生商品循环及D29 ProductCard，以同一DOM完成390/768/1024/1440的1/3/4/5张容量、横向滚动、长标题、缺图和不可购买代表状态，无页面级横向溢出。
- [x] Trust五项设计稿数据与五枚SVG图标仅在Local输出；768横向区可键盘聚焦并滚动，其余四端布局、局部间距、转义、Hook顺序、SVG资源及独立Review已验证。

## 7个专注周期实际分工

| 周期 | 目标 | 实际结果 |
|---|---|---|
| C1 | 核对总计划、设计稿、D29卡片合同与Woo原生排序机制 | 冻结真实累计销量、最多5项、0项诚实隐藏、Local Trust边界 |
| C2 | 设计查询、可见性、缓存与全局循环恢复合同 | 选用`WC_Query::get_catalog_ordering_args( 'popularity' )`与一次`WP_Query`，不接收当前URL排序 |
| C3 | 实现Best Sellers读取与原生循环输出 | 挂载Homepage优先级40，恢复`$post`、`$product`和`woocommerce_loop` |
| C4 | 按设计稿实现Trust数据与图标 | 挂载优先级50；Local五项、非Local空输出；新增五符号SVG sprite |
| C5 | 完成Mobile First布局与代表夹具 | CSS横向scroll snap；390/768/1024/1440按1/3/4/5卡渐进增强 |
| C6 | 只读审计与三路独立Review | 修复排序Filter精确恢复、768键盘滚动入口及区块局部间距 |
| C7 | 四端重跑、减法审查与文档收尾 | 四宽0页面溢出，审计全PASS；登记真实正销量与整页相邻区块剩余证据 |

## 实际实现

### Homepage输出顺序

```text
Hero 10 → Categories 20 → Solutions 30 → Best Sellers 40 → Trust 50
```

`Newsletter`仍由既有Footer模块负责，不被D41函数接管。D41只在既有`inc/homepage.php`职责内增加两个相邻首页区域，没有按Day号拆出新的PHP模块。

### Best Sellers数据链

1. `dentall_get_homepage_best_sellers()`先确认WooCommerce查询与商品API可用。
2. 查询限定`product`、`publish`、无密码，并排除`exclude-from-catalog`；若Woo全局设置隐藏缺货商品，同时排除`outofstock`可见性项。
3. 排序固定调用WooCommerce的`popularity`合同，实际SQL使用`wc_product_meta_lookup.total_sales DESC, product_id DESC`；当前URL的`orderby`和`rating_filter`不进入该首页查询。
4. 只返回`WC_Product`、`get_total_sales() >= 1`且最终`is_visible()`为真的对象，最多5个。
5. `dentall_homepage_best_sellers()`在结果为空时直接返回；有结果时复用WooCommerce原生循环模板，并在`finally`中精确恢复循环与全局对象。
6. 排序回调只删除本函数实际新增的priority 10 popularity Filter；若调用前已存在则保留，避免破坏同请求中的嵌套或第三方查询。

本轮关闭查询结果缓存，原因是累计销量会随交易事实变化，而普通商品文章缓存并不能证明与销量更新同步。该选择不等于性能无影响：真实首页每次未命中整页缓存时会多一次商品排名查询，D42及上线前仍需用代表数据量测。

### Trust Local测试合同

| 图标符号 | 设计稿值 | 标签 | 补充文案 | 发布边界 |
|---|---|---|---|---|
| `professionals` | `10,000+` | Dental Professionals | Trust DentAll | Local TEST |
| `globe` | `100+` | Countries Served | Worldwide | Local TEST |
| `box` | `5,000+` | Quality Products | In Stock | Local TEST |
| `smile` | `99.5%` | Customer Satisfaction | Rate | Local TEST |
| `lock` | Secure Payments | — | Multiple safe payment options | Local TEST |

这些数字、国家数、产品数、满意率和支付陈述均未被本轮证明。`dentall_get_homepage_trust_metrics()`在非Local直接返回空数组，因此设计稿还原不会被误当成Production事实。五枚图标集中在单一`trust-icons.svg`中，以外部`<use>`复用；图标仅装饰信息，统一`aria-hidden="true"`。

### 响应式与状态合同

| 宽度 | 商品轨道 | Trust | 代表验证 |
|---:|---|---|---|
| 390 | 单卡宽301px，露出下一卡 | 五项纵向堆叠 | 页面390/390，无页面溢出 |
| 768 | 三卡各224px | 四项可见＋第五项横向可达 | Trust可聚焦，方向键滚到170px，3px可见Focus |
| 1024 | 四卡各228px | 五列完整信息带 | 页面1024/1024，无页面溢出 |
| 1440 | 五卡各238px | 五列完整信息带 | 页面1440/1440，无页面溢出 |

- 正常：原生标题、图片、价格、促销和购买动作由WooCommerce ProductCard输出。
- 加载：没有异步商品请求或JS Skeleton；服务器端渲染随文档一起返回，因此不伪造组件级加载状态。
- 空：真实累计销量0时Best Sellers整区0输出；非Local Trust整区0输出。
- 错误/依赖缺失：WooCommerce查询或模板API不可用时Best Sellers安全空输出，不用错误文案伪装可购买内容。
- 缺图、长文本、售罄/不可购买：继续使用D29卡片合同；夹具覆盖Woo缺图占位、长标题、`Select options`和`Read more`。
- 键盘：商品链接/按钮沿用Woo原生焦点；768 Trust溢出列表自身可聚焦，并复用项目全局3px Focus规则与浏览器方向键滚动。

## 实际验证证据

### 只读运行审计

使用Local WP-CLI运行`project-docs/tests/day41-home-products-trust-audit.php`，未创建订单或写入销量：

```text
INFO baseline_best_sellers=0 simulated_best_sellers=2 trust_metrics=5
PASS baseline_limit_and_sales_boundary
PASS baseline_empty_output_consistent
PASS url_ordering_cannot_override_popularity
PASS url_rating_filter_not_injected
PASS simulated_products_are_positive_and_limited
PASS simulated_section_and_cards_render_once
PASS shop_link_uses_published_shop_page
PASS woocommerce_loop_restored
PASS post_global_restored
PASS product_global_restored
PASS preexisting_popularity_filter_preserved
PASS local_trust_has_five_metrics
PASS trust_uses_expected_svg_symbols
PASS trust_scroll_region_has_keyboard_entry
PASS homepage_hook_order_is_stable
```

正向场景通过只读Getter Filter模拟两个已发布Local商品的累计销量，仅证明查询、限制、模板和恢复合同；它不能证明真实订单状态变化、同销量并列、页面缓存刷新或正式商品内容。

### PHP、CSS、SVG与浏览器夹具

- 子主题全部PHP及D41审计脚本通过PHP 8.2.9 CLI语法检查。
- `homepage.css`花括号数量相等、`!important`为0；夹具无行内`style`、`<style>`、`<script>`或行内事件。
- `trust-icons.svg`含5个唯一`symbol`，Local HTTP为200且`Content-Type: image/svg+xml`。
- Playwright使用真实Chrome按390/768/1024/1440加载同一Storefront、DentAll及D41 DOM/CSS；四宽`documentElement.scrollWidth === clientWidth`均为真。
- 更新后的截图位于`C:\Users\Administrator\.codex\visualizations\2026\08\31\01a055bb-ccc6-78f3-9d1e-01aa7de07985\day41\`，另有`day41-focus-768.png`记录方向键滚动后的3px焦点。
- 登录态Local真实首页确认区块顺序为Hero→Categories→Solutions→Trust→Newsletter→Footer，Trust为5项；Best Sellers因两个现有TEST商品真实`total_sales=0`而正确缺席。
- 匿名自动浏览器请求被WooCommerce Coming Soon模板接管，不能拿来证明登录态Homepage整页。该环境边界未被D41修改。

## 独立Review与问题关闭

| 专项 | 初次结论 | 处理 | 当前状态 |
|---|---|---|---|
| WooCommerce/Code Review | 无P0/P1/P2；初审发现排序Filter清理会误删预存回调的P3 | 改为保存实例并只清理本次新增回调；增加预存回调审计 | 二次复核P0/P1/P2/P3=0 |
| 设计/可访问性Review | 无P0/P1；初审发现768 Trust键盘入口和区域间距2项P2 | 列表增加焦点入口/名称并验证方向键；只局部收敛Best/Trust padding | 二次复核确认2项P2关闭；保留整页/真实卡语义P3 |
| 需求/测试Review | 范围、无写入、URL隔离、空状态与证据口径复核 | 按要求区分只读模拟、静态夹具、匿名Coming Soon与真实集成 | P0/P1/P2=0；证据边界登记为P3 |

最终未关闭P0/P1/P2为0。保留的P3不阻塞D41技术收尾：真实订单驱动正销量、真实商品卡完整Tab/辅助文本、Trust→Newsletter整页邻接、非Local运行证据及正式Trust事实均留D42或对应业务/部署节点。`tabindex="0"`在390/1024/1440无横向溢出时会增加一个非阻断Tab停靠点；当前接受该最小静态取舍，不为响应式移除焦点而增加JavaScript。

## 减法审查与文件职责

相对D40记录基线：

- `inc/homepage.php`从D40的431行/12182字节增至720行/21100字节，净增289行/8918字节；保留4个同领域函数（Best getter/render、Trust getter/render）和2个Homepage回调，不另造按Day编号的PHP文件。
- `assets/css/homepage.css`从D40的311行/7329字节增至525行/12708字节，净增214行/5379字节、31个规则块；只承担商品轨道、Trust布局、断点与局部间距，没有复制移动/平板/PC四套DOM。
- `style.css`只把主题版本从0.18.0提升为0.19.0，用于既有条件CSS缓存刷新，没有承载D41叶子规则。
- 新增一个23行/1334字节的运行时`assets/images/trust-icons.svg`，集中5个小型符号；相对D40只增加1个Local Trust资源请求。
- 新增一个104行/8598字节非运行时HTML夹具和一个211行/7666字节Local-only只读WP-CLI审计脚本；两者均不进入WordPress前台资源队列。
- JavaScript、模板覆盖、插件、依赖、字段、Option、Cron、远程请求、订单/商品写入及构建链净增0。

## 开发与业务责任边界

| 事项 | 开发者负责 | 业务方负责 |
|---|---|---|
| Best Sellers | 真实累计销量查询、可见性、空状态、卡片复用、性能与缓存验证 | 决定正式商品与交易政策；不手工篡改销量 |
| Trust | Local视觉壳层、响应式、图标和非Local闸门 | 证明并批准数字、国家范围、产品数量、满意率与支付陈述 |
| 商品内容 | 系统动态承载价格、库存、图片和购买状态 | 正式商品名称、价格、库存、图片、授权与可售决定 |
| Newsletter | 保持既有不可提交测试壳层，不扩大范围 | 后续确认服务商、隐私、双重确认、邮件与合规要求 |

## 影响、风险与回滚

| 检查面 | D41实际影响 |
|---|---|
| 数据 | 首页新增只读商品排名查询；没有订单、销量、商品或Option写入 |
| URL/SEO | 不新增路由、Slug、Canonical、Schema、robots或Sitemap项；有真实热卖商品时只链接既有Shop与商品URL |
| 性能/缓存 | Homepage未命中整页缓存时增加一次基于Woo lookup table的排名查询；关闭该查询对象缓存，主题版本0.19.0刷新条件CSS；真实数据量与整页缓存仍待D42/上线前量测 |
| 支付/物流/订单 | 无配置和数据变更；Trust中的Secure Payments只是Local TEST陈述，不代表支付已启用或验收 |
| 部署 | 仅Local；Staging与Production未部署、未激活该子主题版本 |
| 回滚 | 移除Homepage优先级40/50回调与对应局部CSS即可停止输出；删除SVG前必须先移除Trust引用，避免404 |

## DevTools安全微调路径

1. 在登录态Local首页Elements中确认区块顺序和当前商品/Trust DOM，不先从截图推断数据存在。
2. 临时调整`.dentall-home-best-sellers`、商品轨道`grid-auto-columns`或`.dentall-home-trust__list`的局部规则，观察390/768/1024/1440和键盘Focus。
3. 判断数值属于全局Design Token、公共ProductCard还是D41局部布局；本轮间距只改Homepage叶子规则，不改全局`.dentall-section`。
4. 回到子主题源码修改，重新跑WP-CLI审计、四端水平溢出、方向键滚动，并回归Trust→Newsletter相邻区域。
5. DevTools临时规则不算交付证据；不得修改WooCommerce、Storefront或WordPress核心文件。

## D42衔接与明确不做

> [!success] D42回填
> [[Day42-首页全链路校准与M4技术验收]]已按“不创建TEST订单、M4按Local技术v1”完成登录态四端整链路复核；D41查询与Trust实现未发现阻塞缺陷，主题保持0.19.0。

- D42在真实登录态完整首页补齐D40/D41与Newsletter的相邻节奏、完整Tab顺序、真实Woo ProductCard语义和整页截图；当前夹具不能替代这项集成证据。
- 若要验证真实正销量排序、同销量顺序、订单状态与页面缓存刷新，必须先另行确认受控TEST订单和精确清理方案；不能直接写`total_sales`制造通过。
- 业务未证明Trust事实前保持Local闸门；不迁移到Staging/Production，不把设计稿数字写入正式营销或Schema。
- D42还需汇总D37～D41首页全链路、可访问性和性能证据，满足后才能判断M4：首页v1是否完成。

## 可复用核心思想

### 跨平台不变量

排行榜必须由可追溯业务事实驱动，空数据要诚实暴露；营销信任数字必须与视觉实现分开治理。查询、展示、空状态、缓存和事实审批共同组成数据合同，不能用好看的假数据替代。

### WordPress/WooCommerce当前实现

DentAll复用WooCommerce popularity排序、product visibility、`WC_Product`与原生循环模板，在Storefront `homepage` Action中输出；主题负责展示和全局状态恢复，WooCommerce维护商品/销量事实。设计稿Trust通过`wp_get_environment_type()`限制在Local，避免未证实陈述越过环境边界。

### Shopify或其他平台的对应机制

其他平台也应优先复用官方商品集合、销量/推荐排序和主题Section，而不是复制价格库存或手工制造排行榜。具体的销量口径、排序字段、缓存刷新、主题扩展点和营销事实发布机制必须按目标平台官方能力重新验证；本笔记不授权DentAll接入Shopify。
