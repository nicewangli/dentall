---
项目: DentAll WooCommerce
日期: 2026-08-26
工作日: D29
计划检查点: D29（不自动等于一个完整实际工作日）
周次: W5
计划工时: 6小时50分钟有效工作
实际有效工时: 未记录；不使用计划工时代填
验收层级: C3～C7技术收口；三类卡片视觉P2接Day30周验收
状态: 技术收口，视觉验收接续
---

# DentAll 每日复盘 D29：三类卡片组件契约

## 相关笔记

- 前置笔记：[[Day28-基础控件与可访问状态]]
- 后续笔记：[[Day30-设计系统v1与系统状态]]（记录D30周验收后的最终状态）
- 真实Solution Page接入：[[Day40-首页方案Page映射与四端区域]]
- 分类真实接入：[[Day39-首页精选分类入口与自适应换行]]
- 当日WordPress实战学习笔记：[[WordPress实战笔记/Day29-原生循环与卡片展示契约]]
- 前置学习笔记：[[WordPress实战笔记/Day28-基础控件状态与CSS级联]]

> [!note] 后续收口
> 本笔记保留Day29结束时把视觉P2交给Day30的历史状态。D29/D30现已受控合并，第5周前端周验收最终结论记录在[[Day30-设计系统v1与系统状态]]；下文未勾选项不应误读为当前仍阻塞。
- 当前事实入口：`project-docs/PROJECT_STATE.md`
- 代码规则入口：`project-docs/CODEX_WP_WC_RULES.md`
- 设计参考入口：`design-assets/README.md`

## 今日三个验收结果

- [x] ProductCard v1直接承接WooCommerce原生商品循环，保留真实图片、标题、评分、促销、格式化价格和购买动作，并通过现有Simple特价商品与Variable价格区间商品验证。
- [x] CategoryCard v1与SolutionCard v1使用明确标记、非持久化的TEST夹具完成合法语义结构与状态覆盖；D29没有创建正式分类、Page、CPT、字段或URL。
- [ ] 三类卡片完整视觉与键盘验收接Day30：真实Shop ProductCard已完成390、768、1024、1440px无溢出回归；Category/Solution因夹具不属于WordPress路由，本轮只完成静态语义、资源、状态与对比度审计。当前已发现P0/P1为0，不能据此写成三类视觉全部通过。

## 授权与范围

- 用户于2026-08-26明确回复：“确认Day29按三类卡片v1实施，分类卡和Solution卡使用非持久化TEST夹具，真实数据接入留D39/D40，范围仅限Local；开始C1。”
- 用户随后明确回复“开始c2”，授权在上述已确认范围内进入C2。
- 用户在查看页面后进一步明确：“直接c3-c7一起执行”，并决定把页面纯视觉问题放到Day30周验收，由Codex带领使用DevTools共同微调。本次因此获准在既定三类卡片、非持久化TEST夹具和Local边界内完成C3～C7；该授权仍不覆盖真实分类/Page接入、持久化数据、插件/依赖或Staging/Production。
- 用户在检查C2草稿后要求代码必须简洁、易读、可维护、方便微调，并要求把该要求加入项目总规范；`AGENTS.md`现已将其升级为带“编码前复用检查、实现后减法审查和量化交付报告”的验收项。
- 本次实施覆盖：在现有子主题`style.css`完成ProductCard、CategoryCard和SolutionCard内部展示，并建立不被WordPress加载、不写数据库的静态TEST夹具。
- ProductCard的主验证数据继续使用Local现有WooCommerce TEST商品；CategoryCard与SolutionCard的展示内容只存在于版本库测试夹具，不写入WordPress数据库。
- D39负责分类卡真实`product_cat`查询、选择、顺序和空数据；D40负责Solution Page查询、内容字段映射、顺序和空数据。D29只冻结可复用展示契约，不把TEST文案当正式业务内容。
- 本次授权不覆盖Staging/Production、正式分类或Page录入、Solution CPT、商品数据批量修改、Header/Footer、列表筛选、购物车/结账、插件/依赖、WooCommerce模板覆盖、固定链接或SEO配置。
- 若后续视觉验收需要新增PHP Hook、WordPress测试路由、模板覆盖或持久化数据，仍必须暂停并重新说明复杂度与影响；本轮没有采用这些路线。

## 进度真实性检查

- 自然日期：2026-08-26。
- 实际有效工时证据：未记录；只按授权、文件差异和可复核证据判断进度。
- 今天完成或推进的计划检查点：D29 C1～C7。
- 本日最高验收层级：三类卡片内部展示、边界状态、静态审计和独立Review已完成；ProductCard已在真实Shop完成四端动态回归。Category/Solution完整视觉与键盘验收按用户决定接Day30。
- 可由用户直接查看、运行或复演的结果：Local登录态Shop、子主题0.7.0、非持久化TEST夹具、本笔记和D29实战学习笔记。
- 尚未完成的验收：三类卡片参考图精调、Category/Solution浏览器四端与可见Focus，以及Shop页面级列数/视觉密度；均记录为Day30视觉P2，不影响D29技术收口。

## 专注周期记录

| 周期 | 计划 | 当前结果 | 证据/文件 | 用时 |
|---|---|---|---|---:|
| C1 | 冻结三类卡片的数据、DOM、状态、选择器和变更前基线 | 已完成；见本笔记C1章节 | 本笔记、Local真实Shop、WooCommerce/Storefront源码、当前子主题 | 未记录 |
| C2 | 落地ProductCard v1并建立非持久化卡片夹具 | 已完成；运行时只修改现有`style.css`，夹具只含ProductCard状态骨架 | `themes/dentall/style.css`、`tests/fixtures/day29-cards/`、Local真实Shop | 未记录 |
| C3 | 落地CategoryCard v1 | 已完成；复用Woo原生分类DOM，4种TEST状态 | `style.css`、`tests/fixtures/day29-cards/` | 未记录 |
| C4 | 落地SolutionCard v1 | 已完成；合法单链接结构，不接Page查询 | 同上 | 未记录 |
| C5 | 补齐边界状态并做维护性收敛 | 已完成；新增加购成功态并完成减法审查 | 5 Product、4 Category、4 Solution；0个`!important` | 未记录 |
| C6 | 四端、键盘、无障碍和静态回归 | 已执行；Product真实Shop四端通过，夹具静态审计通过；Category/Solution视觉与键盘接D30 | 四端DOM/Computed、静态审计 | 未记录 |
| C7 | 独立Review、修复、文档与学习笔记收尾 | 已完成技术收口；视觉P2明确接D30 | Review、`PROJECT_STATE.md`、学习笔记与索引 | 未记录 |

## C2实际实施与减法审查

- 运行时只修改`app/public/wp-content/themes/dentall/style.css`并将主题缓存版本从0.5.2提升到0.6.0；没有新增PHP、模板覆盖、JavaScript、构建链或前台资源请求。
- ProductCard继续使用WooCommerce原生DOM。新增规则明确排除`.product-category`，只处理单卡表面、主链接纵向布局、1:1媒体框、标题、价格、促销标记和原生购买动作；列表列数与间距仍由Storefront负责并留D44重构。
- 新增`project-docs/tests/fixtures/day29-cards/index.html`和`fixture.css`。它们只提供Simple特价、Variable长标题、缺图/不可购买和动作Loading四种展示状态；不注册WordPress路由、不执行JavaScript、不发送请求、不写数据库，也不被正常前台加载。
- 首轮草稿曾净增123行、13个选择器块和80条声明。收到维护性反馈后执行减法审查，先删除预实现Hover、额外Focus包装、评分/删除价覆盖和重复动作规则；独立Review再删除3条已由全局标题基线提供的重复声明。最终相对C1基线净增71行（含注释和空行）、8个选择器块、43条声明、0个`!important`。
- 保留单一`style.css`是为了沿用现有加载链并避免新增运行请求；没有用拆文件、压缩写法或抽象层隐藏复杂度。后续微调优先改现有Design Token，只有ProductCard独有差异才改本段局部规则。

## C3～C5实际实施与减法审查

- 子主题版本由0.6.0提升到0.7.0。CategoryCard只增加原生分类列表项、整卡链接、1:1图片、标题与可选count的内部规则；真实分类数量、区域网格、顺序和空态仍留D39。
- SolutionCard使用独立BEM类名、CSS Grid和一个整卡链接。正文为可合法包含`h3`的`div`，DOM与视觉顺序均为正文在前、可选媒体在后；摘要、图片缺省时直接省略元素，CTA仍是同一链接内文本。真实Page查询、字段映射、顺序和空态仍留D40。
- Product夹具由4种补为5种，新增WooCommerce AJAX成功后原按钮与`.added_to_cart`并存的展示；ProductCard列表项使用既有8px Token形成两项之间的稳定间距，不改变真实加购流程。
- Category夹具覆盖普通、长标题、可选count和Woo占位图；Solution夹具覆盖深色首卡、长文案、无摘要和无图片，共形成5 Product、4 Category、4 Solution状态。
- 独立维护性Review发现并关闭1个P2：初版使用`span`包裹`h3`不符合HTML内容模型。修复为`div`并同步调整DOM顺序后，删除不再需要的`order: -1`；随后再删除Storefront已提供的count透明背景和无收益的`align-items`，并把长词换行责任收敛到Solution正文壳，统一覆盖标题、摘要与CTA。
- 最终相对C2新增的生产规则为Category 5个规则块/15条声明、Solution 12个规则块/32条声明；合计17个规则块/47条声明、0个`!important`、0个生产媒体查询。测试网格只存在于`fixture.css`，没有进入WordPress前台。
- 整个D29生产区段合计91条声明：ProductCard 44条、CategoryCard 15条、SolutionCard 32条。选择器均按职责连续排列，没有新增CSS文件、运行请求、框架、构建链或深层抽象。

## C1已冻结：职责与交付边界

Day29解决的是“卡片本身如何安全承载未知内容”，不负责各页面最终查询、区块顺序或多列网格。

- ProductCard：D29直接作用于WooCommerce原生`ul.products > li.product`商品循环；D44仍负责Shop商品网格列数、间距和1～多商品的页面级布局。
- CategoryCard：D29按WooCommerce原生`content-product-cat.php`结构完成卡片内部视觉；D39再接真实`product_cat`数据并完成9分类区域、顺序、链接和空数据。
- SolutionCard：D29冻结独立的`.dentall-solution-card`展示结构；D40再将真实Page标题、摘要来源、特色图和固定链接映射进去并完成4卡区域。
- 三类卡片只维护一套语义化DOM和Mobile First CSS；不得按390、768、1024、1440复制四套HTML。
- C2候选最小路线为：继续复用子主题现有`style.css`；在独立子目录`project-docs/tests/fixtures/day29-cards/`保存不进入数据库的HTML/CSS TEST夹具，避开既存Day25 CSV夹具。测试夹具不属于前台页面、不会注册WordPress路由，也不增加正常页面资源请求。
- 当前没有证据要求新增PHP组件、模板覆盖、JavaScript、构建链或插件。D39/D40的数据映射在各自工作日落到最靠近使用处的最小模板/Hook，不在D29预造查询层。

## C1已冻结：ProductCard数据与DOM契约

### 数据来源

| 展示内容 | 权威来源 | D29规则 |
|---|---|---|
| 链接 | WooCommerce商品固定链接 | 保留原生商品链接，不拼接Slug或猜测URL |
| 图片 | WooCommerce缩略图/占位图输出 | 保留`alt`、`width`、`height`和原生占位行为；视觉框固定1:1，不输出破图 |
| 标题 | 原生商品标题 | 保留完整可见标题和`h2.woocommerce-loop-product__title`；允许换行，不用硬截断隐藏关键商品名 |
| 评分 | 原生评分HTML，可缺省 | 只在真实评分存在时展示；不伪造星级或评论数 |
| 促销 | 原生`.onsale` | 只按WooCommerce促销判断展示，不从截图硬编码`Sale!` |
| 价格 | `$product->get_price_html()`对应的原生循环输出 | 保留简单价、`del/ins`促销价、Variable价格区间及屏幕阅读器文本；不得硬编码`$`或金额格式 |
| 购买动作 | 原生loop add-to-cart输出 | 保留Add to cart、Select options及WooCommerce对售罄/不可购买商品决定的文案、链接、ARIA和AJAX类 |

### 真实DOM与Hook顺序

当前WooCommerce 11.0.0的`content-product.php`继续输出原生`li.product`，内部顺序由Hook维护：

1. `woocommerce_before_shop_loop_item`打开商品链接。
2. `woocommerce_before_shop_loop_item_title`输出缩略图；Storefront将促销标记从图片前移动到标题后。
3. `woocommerce_shop_loop_item_title`输出`h2.woocommerce-loop-product__title`。
4. `woocommerce_after_shop_loop_item_title`按评分5、促销6、价格10输出可选内容。
5. `woocommerce_after_shop_loop_item`先关闭商品链接，再输出原生购买动作。

D29不改变Hook顺序、不复制`content-product.php`，也不把价格或按钮包进新的嵌套链接。视觉上允许将`li.product`和商品主链接作为纵向Flex容器，使长标题时价格与动作保持稳定，但DOM与业务行为不变。

### D29明确不加入的字段

- 不加入设计稿中的商品副标题、品牌行或短描述：当前没有冻结的权威数据源。
- 不加入虚拟评论数或评分：当前两个真实TEST商品没有评分输出。
- 不加入仅图标购物车按钮：参考图在不同页面不一致，且原生可见动作文字更稳妥。
- 不实现Wishlist、Quick View、Buy Now、比较、视频或新的AJAX加载行为。

## C1已冻结：CategoryCard夹具契约

CategoryCard的非持久化TEST夹具应复刻WooCommerce原生分类循环，而不是另造第二套无法在D39复用的DOM：

```html
<li class="product product-category">
  <a href="...">
    <img ...>
    <h2 class="woocommerce-loop-category__title">...</h2>
  </a>
</li>
```

| 夹具字段 | D29用途 | D39真实映射 |
|---|---|---|
| `name` | 分类标题与长文本测试 | `WP_Term->name`，输出时转义 |
| `url` | 整卡单链接与Focus测试 | `get_term_link()`并处理`WP_Error` |
| `image_src` | 正常与缺图占位测试 | 分类缩略图；缺图走确定性占位 |
| `image_alt` | 图片替代文本测试 | 根据分类图片用途生成；装饰图允许空alt |
| `image_width` / `image_height` | 稳定比例、避免布局偏移 | 使用WordPress/WooCommerce图片函数输出尺寸 |
| `count`（可选） | 验证有/无数量时不破版 | WooCommerce原生数量；D39决定首页区域是否显示，不在D29全局隐藏 |

- 整张卡只有一个链接，不在标题或CTA中再嵌套链接。
- 图片框采用1:1稳定比例；真实素材允许`object-fit: contain`，避免器械/包装图被强裁切。
- 缺图时显示明确的中性占位，不输出空`src`或破图图标。
- D29不会创建、重命名、排序或发布任何`product_cat`，也不冻结正式分类名称。

## C1已冻结：SolutionCard夹具契约

SolutionCard没有WooCommerce原生循环可直接复用。D29只冻结展示结构，D40再决定Page查询与字段来源：

```html
<li class="dentall-solution-card">
  <a class="dentall-solution-card__link" href="...">
    <div class="dentall-solution-card__body">
      <h3 class="dentall-solution-card__title">...</h3>
      <span class="dentall-solution-card__summary">...</span>
      <span class="dentall-solution-card__cta">...</span>
    </div>
    <span class="dentall-solution-card__media">...</span>
  </a>
</li>
```

| 夹具字段 | D29用途 | D40真实映射 |
|---|---|---|
| `title` | 标题与长英文换行 | Page标题，输出时转义 |
| `summary`（可选） | 正常、缺省与长摘要测试 | 来源在D40冻结；D29不新增Page字段或ACF |
| `url` | 整卡单链接 | Page固定链接；D40前不冻结Slug |
| `cta_label` | 可见动作提示 | 由模板使用可翻译字符串，不作为每个Page的持久化字段 |
| `image_src`（可选） | 正常与缺图占位 | 候选为Page特色图，D40确认 |
| `image_alt` | 图片语义测试 | 装饰图使用空alt；承载独立信息时使用经审核替代文本 |
| `image_width` / `image_height` | 稳定媒体区域 | 使用WordPress图片函数输出尺寸 |

- `h3`与Solutions区段的未来`h2`形成清晰标题层级；TEST夹具会提供区段标题上下文。
- CTA是同一链接内的视觉文本`span`，不创建嵌套第二链接。
- 摘要或图片缺省时不输出空段落或破图；卡片仍保留清晰标题、链接与Focus。
- 不在D29确定Page摘要来自手写Excerpt、首段正文还是独立字段；该决定会影响后台编辑职责，留D40结合真实样本确认。

## C1已冻结：状态与验证矩阵

| 卡片 | 真实Local集成证据 | 非持久化视觉夹具 | 集成日边界 |
|---|---|---|---|
| ProductCard | #44 Simple特价：原价/现价与Add to cart；#46 Variable：价格区间与Select options | 长标题、缺图占位、无评分、动作`.loading`、售罄/不可购买及`.added_to_cart`成功后的DOM等价状态 | 价格、库存、购买文案和ARIA仍以WooCommerce真实输出为准；夹具不证明交易流程 |
| CategoryCard | D29不读取或创建正式分类 | 正常标题、长标题、正常图、缺图、可选count、单链接Focus | D39验证真实term、顺序、链接错误、0/1/9+条与区域空态 |
| SolutionCard | D29不读取或创建正式Page | 正常、长标题/摘要、无摘要、缺图、长CTA、单链接Focus | D40验证真实Page字段来源、0/1/4+条、Draft排除、顺序与区域空态 |

共同验证规则：

- 390、768、1024、1440px均不得出现卡片内部横向溢出、破图、文字覆盖或无法识别的Focus。
- 标题与摘要使用正常换行和`overflow-wrap`；不依赖仅颜色区别状态，不通过`line-clamp`隐藏关键名称。
- Product/Category媒体优先1:1稳定框；Solution媒体采用横向稳定比例，具体比例在C2参考图量测后仅以组件Token或局部规则实现。
- 卡片内部可使用Flex/Grid；页面级列数、轮播、横向滑动和整体区块间距留D39、D40、D44。
- 加载、空和错误必须区分层级：D29只验证卡片已有动作加载与缺省内容；查询级Skeleton、空区域和错误提示由真实数据接入日负责，不为静态夹具发明假的网络状态。

## C1变更前基线证据

### 当前版本与运行文件

| 文件 | C1事实 |
|---|---|
| `themes/dentall/style.css` | 0.5.2；13168字节；SHA-256 `C32B7EEA5D6B20FC5A2BA02547470DCCF8CB594EB7B57D313BC4C9B569F30A7D`；已有63个Design Token与基础控件规则，没有三类卡片规则 |
| `themes/dentall/inc/storefront-hooks.php` | 1876字节；SHA-256 `9D4F3CF115AAC42709C90071C5153438BC9FB24C81564F66956148CC2B0F4FD5`；只含既有Storefront展示Hook，没有卡片逻辑 |
| WooCommerce `templates/content-product.php` | 原生商品循环Hook结构；子主题无模板覆盖，只读 |
| WooCommerce `templates/content-product-cat.php` | 原生分类循环Hook结构；子主题无模板覆盖，只读 |
| Storefront WooCommerce样式 | 当前负责`ul.products`列表、卡片居中、标题/价格/图片和768px起的多列布局；父主题文件禁止修改 |

- C1开始时目标子主题目录相对Git为干净状态。工作区另有用户既存的`AGENTS.md`、模板与D25商品导入草稿差异，D29全部避开，不暂存、不回退、不清理。
- 现有Design Token包含表面色、边界、评分色、14px大圆角、卡片阴影、字体、间距和Focus基线；C2优先复用，不为每张卡散落硬编码数值。

### Local真实Shop

- 匿名`http://dentall.local/shop/`返回200但命中WooCommerce Coming Soon，仅能证明保护页和`style.css?ver=0.5.2`加载，不能作为商品卡DOM证据。
- 登录态真实Shop返回原生商品归档，当前只有2个商品：`TEST D12 Simple Fixed Pack`和`TEST D12 Variable Size Shade`。
- 770×920 CSS视口下，Shop列表为`ul.products.columns-3`；两个卡片各约210.30px宽，仍由Storefront列宽/浮动规则控制。D29不把这个列宽固化为组件尺寸。
- 两张商品卡均使用324×324原生缩略图属性；当前显示宽约210.30px。标题为16px/400字重，价格为14px/400字重；卡片本身无边框、圆角、阴影或表面色。
- Simple商品输出原生`Sale!`、`del/ins`及屏幕阅读器价格说明，按钮为可AJAX加购的`Add to cart`并带商品专属`aria-label`/`aria-describedby`。
- Variable商品输出`$39.99–$49.99`及屏幕阅读器价格区间，动作是跳转商品页的`Select options`，另有隐藏说明其需在详情页选择规格。
- 两个真实商品均有图且无评分；长标题、缺图、售罄/不可购买和加载态必须在非持久化夹具或后续真实样本中分层验证，不得把未出现状态写成真实业务通过。
- 尝试批量切换390/768/1024/1440视口时浏览器连接超时；没有发生页面或数据库写入，临时视口已恢复默认。该超时不是网站失败，四端动态回归仍按计划在C6/C7实施后执行。

### 设计证据边界

- 继续沿用D27冻结的证据优先级：原始终稿与真实WooCommerce状态优先，高清增强稿辅助视觉，1024推导稿只作方向参考。
- 商品列表与首页参考共同支持白色表面、轻边界/圆角、稳定媒体框、清晰标题/价格/动作的方向；不同参考中的购物车图标、评分数量和商品副标题不一致，因此不进入必需契约。
- 分类与Solutions素材当前允许使用固定比例占位图；任何TEST名称、数字、价格、分类数量或Solution文案都不得发布为正式内容。

## 测试与验证

- 执行的命令：`git diff --check`；CSS版本、SHA-256、规则/声明、花括号和`!important`统计；夹具ID、ARIA/Hash目标、锚点平衡、图片alt/尺寸、本地资源、行内代码检查；Shop与0.7.0 CSS的Local HTTP检查；登录态Shop四端DOM与计算尺寸读取。
- 浏览器/页面：Local登录态`/shop/`确认2张真实商品卡，Simple为`Add to cart`，Variable为`Select options`。390、768、1024、1440px均加载`style.css?ver=0.7.0`，横向溢出均为0，购买动作均为44px高；卡宽分别约335、210、288、382px，内部图/动作宽分别约301、176、254、348px。
- 静态证据：0.7.0为17927字节，SHA-256 `14F5BAD07AF0CF6E0B934EF0016962A46CE5D2A98F95C18EE2924E726835A96D`，全文件58/58个花括号配对、0个`!important`。夹具含19个ID且无重复，6个ARIA引用与19个Hash目标均存在，19个锚点开闭平衡，12张图片均有alt和正整数尺寸，17个本地资源无缺失，0个行内样式、脚本或事件；夹具CSS为14/14个花括号配对。
- 语义与可访问证据：4张Solution卡均只有一个链接和一个`h3`，正文壳为合法`div`，没有嵌套链接或`span > h3`；4张分类卡均复用一个链接和原生`h2`。关键对比度审计为正文/浅蓝15.19:1、CTA/浅蓝4.95:1、白字/深蓝15.07:1、Focus/夹具背景6.23:1。
- HTTP证据：Local Shop与子主题CSS均返回200；Shop引用0.7.0缓存键，HTTP返回CSS包含Category/Solution目标规则，并已移除修复后的`order: -1`。
- 未验证项：浏览器安全策略拒绝直接导航本地`file://`夹具，本轮没有建立临时站点路由绕过边界；Category/Solution的四端真实渲染、可见Focus和参考图精调因此由用户明确接到Day30。AJAX真实交易成功、D39/D40查询级空态和D44页面网格也不由静态夹具证明。

## Codex Agent 调度与审查

- 今日风险等级：C1低风险；C2～C5为中风险WooCommerce公共展示层与新组件契约改动。
- 风险判断理由：不改数据或交易行为，但现有经典商品/分类循环会继承组件规则，Solution结构还需防止非法HTML与未来映射债。
- 启动的Agent及职责：主Agent完成实施与Local验证；设计审计Agent核对三类卡片最小视觉/响应式边界；独立Code Review Agent检查语义、选择器、重复声明和维护成本；独立测试Agent检查夹具、可访问性、资源、对比度与四端证据。
- 未启动安全/交易测试Agent的原因：没有PHP入口、权限、输入、持久化、价格计算、库存写入、购物车、订单或支付改动。
- Review结果：C2的图标字体P2与标题重复声明P3均已关闭；C3～C5另发现并关闭1个语义P2（非法`span > h3`）。修复后再完成3项减法：DOM/视觉顺序一致并删除`order`，复用Storefront透明mark基线，删除无收益对齐声明并统一正文长词换行。最终已发现P0/P1/P2为0。
- 剩余风险或未验证项：Category/Solution完整视觉与键盘Focus接Day30；夹具正常商品图依赖Local uploads，清理TEST媒体或换环境后需重建；AJAX成功只验证DOM等价布局，不代表真实交易成功；Woo Blocks不在C1经典循环契约内；D39/D40真实数据源与查询级空态不能由D29夹具替代。

## 决策与范围变化

- 今日决定：恢复总计划D29的三类卡片v1范围；将ProductCard连接真实WooCommerce TEST输出，CategoryCard与SolutionCard使用非持久化TEST夹具。
- 新需求：相对上一版“D29只做商品卡”的临时收窄，本次把原总计划中的分类卡和内容卡提前恢复；“内容卡”按当前业务语义命名为SolutionCard。
- 预计增加工时：D29相对只做商品卡预计增加约2～3个专注周期；D39/D40分别减少重复视觉与状态工作，当前不增加总计划工作日。该估算不是无条件承诺，真实数据字段或布局若改变契约仍需记录返工。
- 是否已确认：是，用户已明确确认三类卡片v1、非持久化夹具、D39/D40真实接入和Local边界。

## 数据、URL与系统影响

- 实际数据影响：无。没有创建、编辑、发布、删除商品、分类、Page、媒体、用户、订单或配置；夹具只存在于版本库文件。
- 实际URL/SEO影响：无。没有新增WordPress路由、Slug、链接目标、Title、Meta、Canonical、Schema、robots、Sitemap或状态码变化；夹具自身声明`noindex, nofollow`且不是网站路由，Coming Soon保持启用。
- 实际性能/缓存影响：继续只加载现有一个子主题CSS请求，没有新增查询、远程请求、Cron、自动加载选项或JS；缓存键由0.5.2变为0.7.0，确保Local读取新样式，但未执行缓存清理。没有重构前后性能量测，因此不宣称性能零影响。
- 实际支付/物流影响：无。没有修改价格、库存、Variation、购物车、结账、支付、税费或运费。
- 实际部署影响：无。范围仅Local；Staging/Production、DNS和正式支付保持不变。

## 问题与风险

- 阻塞：无。真实业务内容不足不阻塞通用卡片骨架；只阻塞D39/D40真实内容验收。
- 技术债：Storefront继续拥有商品网格列数与浮动布局，D29只处理卡片内部；D44必须显式重构页面级网格并回归1～多商品。
- 需要他人提供：D29无需正式资料。正式分类名称/图片与Solution标题/摘要/特色图最晚在D39/D40业务验收前提供并确认授权。

## 今日复盘

- 完成：C1契约；C2～C5三类卡片内部展示、13个状态夹具、维护性减法；C6真实Shop四端与完整静态审计；C7独立Review、修复、状态文档和实战学习笔记技术收口。项目总规范已加入可量化的简洁可维护验收规则。
- 未完成及原因：Category/Solution浏览器视觉、可见Focus及三类卡片参考图精调按用户决定接Day30周验收；静态夹具不是WordPress路由，本轮不为截图新增测试路由或持久化数据。
- 实际工时与计划偏差：未记录。
- 今天学到的内容：WooCommerce商品和分类卡已有稳定原生循环契约，先复用真实DOM再做视觉，可把未来数据接入工作缩小为“查询与字段映射”，而不是重写组件。

## WordPress实战学习笔记收尾

- [x] 已按模板生成[[WordPress实战笔记/Day29-原生循环与卡片展示契约]]，并在索引、前置学习笔记与本笔记建立双向链接。

## 明日启动点

- 下一检查点：Day30按总计划完成栅格、间距、Loading/空数据/通知与四端周验收，并由用户和Codex共同使用DevTools执行“临时试验→归因到Token/公共组件/局部规则→回写源码→四端回归”。
- 需要提前准备：保留现有2个WooCommerce TEST商品和D29非持久化夹具；不修改其价格、库存、图片或发布状态。D30若需要真实分类/Page数据或测试路由，必须重新确认，不默认扩大范围。

## 可复用核心思想

- 跨平台不变量：组件契约应把“内容字段、语义DOM、视觉状态、查询/空态”分层。真实数据未到位时可以先完成前三层，但必须把查询级空态和业务正确性留给数据接入验收，不能用静态夹具替代。
- WooCommerce/WordPress实现：商品与分类已有核心维护的循环模板、格式化价格、占位图和可访问购买动作。优先在子主题CSS中适配原生输出，通常比复制模板或手写价格/按钮更少升级债。
- 跨平台不变量：卡片内部布局和页面级网格是两个责任。先让单卡在任意可用宽度下不破版，再由区块/列表决定列数，可避免一个断点改动同时破坏所有卡片内容。
- Shopify或其他平台对照：平台提供的数据对象和模板语法不同，但“价格由平台格式化、购买状态由交易系统决定、展示夹具不得冒充真实业务数据”的边界仍成立；具体Liquid输出和可访问性行为需在对应平台重新验证。
