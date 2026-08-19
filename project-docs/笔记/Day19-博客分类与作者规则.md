---
项目: DentAll WooCommerce
日期: 2026-08-19
工作日: D19
周次: W4
计划工时: 6小时50分钟有效工作
实际工时: 待用户记录
状态: 已完成；博客信息架构v1验收通过，P0=0、P1=0
---

# DentAll 每日复盘 D19

## 相关笔记

- 前置笔记：[[Day18-商品模型候选冻结]]
- 商品SEO边界：[[Day16-商品SEO规则]]
- 内容试录前置：[[Day6-商品与文章安全试录]]
- 后续笔记：待D20建立后补充

## 今日三个验收结果

- [x] 只读盘点Staging现有文章、分类、标签、作者、固定链接和Yoast归档设置，形成可见事实矩阵。
- [x] 冻结第一版博客固定链接、分类、标签、作者、日期归档和索引治理规则。
- [x] 按用户确认范围实施Staging配置并验证保存结果、前台状态、robots、Canonical、Schema现状和Website Manager操作边界；统一公开署名与作者Schema属于D87文章详情实现项，不在D19临时改Storefront模板。

## C1：受保护Staging只读事实矩阵

| 对象 | 当前事实 | 边界 |
|---|---|---|
| 文章 | 共4篇：2篇发布、2篇草稿 | 均为现有或TEST内容，不代表正式内容样本已验收 |
| 文章分类 | `TEST D12 Content`、`Uncategorized` | TEST/默认分类不能直接当作Production正式分类 |
| 文章标签 | `test-d12-manager` | 单个稀薄TEST标签不构成第一版标签体系 |
| 作者 | 可见`Dan`、`D12 Manager, DentAll`、`Xu, Dan`等身份 | 登录/测试身份不等于正式公开作者 |
| 首页 | 当前显示最新文章 | 尚未建立独立博客页，信息架构仍待确认 |
| 文章固定链接 | 当前为`/%postname%/` | 尚未改为候选`/blog/%postname%/`，本轮未修改URL |
| 分类/标签基础 | 后台留空，实际使用WordPress默认`/category/{slug}/`与`/tag/{slug}/` | 正式Slug与索引策略仍待确认 |
| Yoast文章 | 可在搜索结果显示；Title为标题、页码、分隔线和站点标题；Meta模板留空；Schema为WebPage＋Article；内容级SEO控制启用 | Staging全站仍禁止搜索引擎索引，不能外推Production结果 |
| Yoast分类 | 当前允许索引，Title包含术语标题、归档、页码、分隔线和站点标题；Meta模板留空 | 正式分类内容与索引门槛仍待确认 |
| Yoast标签 | 当前允许索引，Title模板与分类类似；Meta模板留空 | 第一版是否保留标签归档仍待确认 |
| Yoast作者归档 | 当前启用且允许索引；无文章作者归档不索引 | 与已确认的统一品牌作者规则冲突，待实施关闭并验证 |
| Yoast日期归档 | 当前启用，但不在搜索结果显示 | 是否彻底关闭仍待确认 |

C1只读阶段没有修改数据库、文章状态、作者、Slug、固定链接、Yoast设置或Production。

## C2：已冻结的文章作者规则

- 内容范围：第一版文章仅介绍DentAll工厂、公司、产品与服务相关信息，不发布临床建议或需要专业资质背书的医疗判断。
- 公开署名：正式文章统一使用`DentAll Editorial Team`。
- 后台责任：两名Website Manager继续使用各自独立账号直接创建、编辑、审核和发布；`post_author`与修订记录保留实际业务操作者，不在发布前改派给额外品牌账号。
- 归档策略：第一版关闭作者归档，避免单一品牌作者页重复博客列表；文章正文保留可见署名，后续前端可链接到About或编辑说明页面。
- Schema边界：普通文章继续使用`Article`语义，不使用医疗、临床或新闻专用类型。前端模板完成后必须核对Yoast实际输出，未验证前不宣称已输出`Organization`作者。
- 业务审核：无需临床专家审核，但工厂数据、资质、材料、产能、交付和品牌表述仍由业务负责人核实。
- 升级条件：未来若出现临床建议、法规解释、安全操作、诊疗判断或需要资质证明的声明，必须重新决定具名作者、合格审核人、来源和合规流程。

该规则已写入ADR-017、编辑流程和URL/SEO映射。当前已明确不新增品牌作者账号或新角色；Yoast作者归档已由用户保存关闭，前台统一署名与Schema仍待实现和验证。

### C2实施记录

- 用户已在受保护Staging的`Yoast SEO → 设置 → 高级 → 作者归档`关闭作者归档并完成保存。
- 用户已在受保护Staging的`Yoast SEO → 设置 → 高级 → 日期归档`关闭日期归档并完成保存；文章发布日期和修改日期继续保留，不改变文章详情URL。
- 用户已在受保护Staging的`Yoast SEO → 设置 → 分类与标签 → 标签`关闭“在搜索结果中显示标签”并完成保存。标签功能和现有TEST标签仍保留，本次没有删除标签或修改文章标签关系。
- 用户已在受保护Staging的`Yoast SEO → 设置 → 分类与标签 → 分类目录`保持“在搜索结果中显示分类目录”开启，将SEO标题模板保存为“术语标题＋页面＋分隔线＋网站标题”，并保持全局Meta Description模板空白。
- 用户已在受保护Staging的`设置 → 固定链接`将文章固定链接从`/%postname%/`改为并保存`/blog/%postname%/`；页面、商品、分类前缀和标签前缀没有随本决策改变。
- 用户截图确认Yoast Sitemap Index可访问且共列出6个子Sitemap：`post`、`page`、`product`、`category`、`product_brand`和`product_cat`。
- Sitemap Index未出现`author-sitemap.xml`，作者Sitemap排除结果通过；实际作者归档URL行为和前台署名/Schema仍待复核或实现。
- Sitemap Index未出现`post_tag-sitemap.xml`，标签Sitemap排除结果通过；标签归档实际`noindex`和Canonical仍待页面级复核。
- `category-sitemap.xml`仍存在，符合文章分类作为可索引主归档的全局设置；实际Title、robots、Canonical和其中TEST分类去留仍待复核，现有`TEST D12 Content`与`Uncategorized`不因此获得Production索引资格。
- 日期归档本来不对应独立Yoast Sitemap，其未出现在索引中不能单独证明开关状态；刷新持久化和实际日期归档URL行为仍待复核。
- URL人工抽查已确认：移除`/blog/`后的旧根路径显示真实404页面；草稿#36可通过`/?p=36&preview=true`正常进入预览。查询参数预览URL是WordPress草稿预览入口，不是正式文章Permalink，因此没有`/blog/`前缀不构成缺陷。
- 用户进一步确认已发布TEST文章的新`/blog/{slug}/`地址可正常访问。由此，文章URL路由人工验证通过：新地址正常、旧无`/blog/`根路径404、草稿查询参数预览正常。
- 用户截图确认`post-sitemap.xml`共含3个URL：首页`/`及两篇已发布TEST文章；两篇文章均使用新`/blog/{slug}/`，草稿#36及另一篇草稿均未进入Sitemap。文章固定链接与Sitemap URL验证通过。
- 首页`/`出现在`post-sitemap.xml`是因为阅读设置仍为“您的最新文章”，首页当前兼任文章归档；这不属于错误，但独立`/blog/`文章归档与静态商城首页尚未建立。
- 用户随后以现有账号发布两个空白原生结构页`Home`与`Blog`；没有新增角色、用户、正式文案、区块、特色图或SEO高级值。页面列表截图确认两页状态均为“已发布”；Slug和阅读设置映射仍待下一步验证。
- 用户在`设置 → 阅读`选择“一个静态页面”，主页指定`Home`、文章页指定`Blog`并保存；Staging继续保持“建议搜索引擎不索引本站点”。
- 用户人工确认保存后`/`显示静态Home占位、`/blog/`显示两篇已发布文章，现有`/blog/{slug}/`详情继续正常。首页/博客/文章三层路由验证通过；正式内容、前端视觉、分页和Production SEO不在本结论内。
- 无痕窗口再次检查`post-sitemap.xml`仍有3个URL，但条目已正确变为`/blog/`文章归档加两篇`/blog/{slug}/`文章，而不再是首页`/`加两篇文章。Yoast当前实现会把被指定为`page_for_posts`的Blog作为Post类型归档链接加入`post-sitemap.xml`，并从`page-sitemap.xml`排除该Page；这不是缓存或错误。
- Codex先前预期静态首页设置后`post-sitemap.xml`应只剩2篇文章，并预期`/blog/`进入`page-sitemap.xml`，该判断错误，已依据实际Sitemap与本地Yoast 28.2源码纠正。正确预期是：Post Sitemap含`/blog/`＋已发布文章；Page Sitemap含静态首页`/`但不重复列`/blog/`。
- 用户截图确认`page-sitemap.xml`共7个URL：静态首页`/`、`/sample-page/`、`/shop/`、`/checkout/`、`/my-account/`、`/cart/`和`/test-d12-manager-published-page/`；`/blog/`没有重复出现，首页与Blog归类正确。
- 发现D19 P1 SEO配置缺口：Cart、Checkout和My Account仍被Yoast视为可索引Page并进入Sitemap。三页是交易/账户功能页，应保留页面和WooCommerce分配，但需要逐页设置`noindex`以从Sitemap排除；Shop继续保留索引。
- `Sample Page`与D12 TEST页面属于D25清理/去留对象，当前受保护Staging全站`noindex`，本轮不因Sitemap盘点直接删除。
- 用户按指导将Cart、Checkout和My Account逐页设为Yoast `noindex`并更新；刷新后的`page-sitemap.xml`从7项降为4项，只保留首页`/`、`/sample-page/`、`/shop/`和D12 TEST页面。D19 P1 SEO配置缺口已关闭，三张WooCommerce必需页面保留且未删除。
- 只读HTTP复核：首页、`/blog/`、Cart和My Account返回200；空购物车访问Checkout先302到Cart再200，属于当前空购物车流程。上述响应同时输出`X-Robots-Tag: noindex, nofollow`和页面Meta robots `noindex, nofollow`，且不输出Canonical，符合受保护Staging全站禁止索引边界。
- 采用`/blog/%postname%/`且分类/标签前缀留空后，WordPress实际把文章分类、标签、作者与日期归档统一放在`/blog/`前段：`/blog/category/{slug}/`、`/blog/tag/{slug}/`、`/blog/author/{slug}/`、`/blog/{year}/{month}/`。先前文档中的根路径`/category/`与`/tag/`假设已纠正。
- 实际HTTP/REST复核：TEST分类与标签正确URL均返回200；标签Yoast结果为`noindex, follow`且不在Sitemap，标签规则通过；两名已有发布作者的归档URL均301到首页且作者Sitemap缺席，作者归档关闭通过；`/blog/2026/08/`日期归档301到首页，日期归档关闭通过。
- TEST分类标题输出为“术语标题＋分隔线＋网站标题”，已不含“归档”变量；受Staging全站禁索引影响仍为`noindex`且无Canonical，Production索引与自身Canonical留待对应环境验证。
- REST抽查两篇已发布TEST文章：路由均为`/blog/{slug}/`，Yoast输出`Article`结构化数据，后台作者分别保留实际账号，证明`post_author`审计链未被统一署名规则覆盖。当前Yoast仍把实际账号输出为`Person`作者，因此前台`DentAll Editorial Team`署名与对应Schema尚未实现；该差距登记为D87文章详情模板/SEO输出验收项，不在D19通过修改账号显示名、增加品牌账号或改第三方插件核心文件处理。
- Staging只读HTTP复核已确认首页、博客和主要功能页的当前状态码及全站`noindex, nofollow`边界；受Staging全站禁止索引影响，相关页面当前不输出Canonical。该结果只证明Staging保护边界和信息架构，不代表Production自身Canonical、索引、缓存或重定向已经验收。
- 本次没有新增品牌作者账号或角色，也没有把两名Website Manager的后台显示名改成相同名称；现有文章作者与修订审计继续保留实际账号。

## 博客信息架构v1验收结论

| 验收维度 | v1冻结结果 | Staging证据 | 结论 |
|---|---|---|---|
| 内容模型 | 使用WordPress原生Post、Category和Tag；不新增博客CPT、ACF字段或第三方内容模型 | 现有文章、分类和标签可由原生后台维护 | 通过 |
| 主路由 | 静态首页`/`、博客归档`/blog/`、文章详情`/blog/{slug}/` | 三层路由人工访问通过；已发布文章进入`post-sitemap.xml` | 通过 |
| 分类路由与职责 | `/blog/category/{slug}/`作为主要归档；只有正式名称/Slug、多篇相关文章和独立说明后才满足Production索引门槛 | TEST分类URL返回200；Title模板不再包含“归档”；`category-sitemap.xml`保留 | 通过；真实分类内容转D24/D25验收 |
| 标签路由与职责 | 保留`/blog/tag/{slug}/`能力，但第一版统一`noindex`且不进入Sitemap | TEST标签URL返回200，Yoast为`noindex, follow`，无`post_tag-sitemap.xml` | 通过 |
| 作者与日期归档 | `/blog/author/{slug}/`与`/blog/{year}/{month}/`形态保留但归档关闭 | 已有作者归档及日期归档均301到首页；无作者Sitemap | 通过 |
| 作者与审计 | 两名Website Manager使用各自账号直接写作和发布，后台保留真实`post_author`与修订记录；第一版不新增账号或角色 | 两篇已发布TEST文章保留不同实际作者；Website Manager文章发布边界已走通 | 通过；前台统一署名与作者Schema转D87实现 |
| Sitemap与功能页索引 | Post Sitemap包含`/blog/`和已发布文章；Page Sitemap包含首页但不重复`/blog/`；Cart、Checkout、My Account保留功能但排除索引 | `post-sitemap.xml`为博客归档加两篇文章；`page-sitemap.xml`由7项降至4项，三张功能页已移除 | 通过；D19 P1已关闭 |
| 权限和变更边界 | Website Manager拥有文章日常编写、发布、分类、标签和内容级SEO职责；固定链接、全局robots、Canonical、Sitemap和Schema仍由开发者变更管理 | 已发布TEST文章与既有权限复核证明日常流程可用；本轮未扩大系统管理权限 | 通过 |

**验收结论：博客信息架构v1通过，D19已完成，当前P0=0、P1=0。**该结论冻结内容对象、URL、归档职责、索引治理和权限边界，不表示正式文章内容、博客前端UI、Production SEO或统一公开作者输出已经完成。

后续非阻塞项：D24用代表文章验证真实内容；D25处理`Sample Page`、TEST页面、TEST分类和TEST标签去留；D87实现`DentAll Editorial Team`前台署名并统一作者Schema；Production上线前复核索引、Canonical、状态码、缓存和重定向。

## 当前实施边界

- 低频、可逆的Yoast开关优先由用户在原生后台执行，Codex提供逐项路径、风险和验证方法；作者、日期归档、标签索引、分类模板及功能页`noindex`已按此方式完成用户操作和Staging验证。
- 统一署名由后续前台模板与SEO输出实现，不新增用户登录面；实现时不得覆盖后台实际作者和修订审计。
- 修改文章固定链接会影响未来URL、Canonical、Sitemap、内链和重定向；必须独立确认后实施，不能因为作者规则已冻结而顺带修改。
- Staging继续受Cloudways访问保护并启用WordPress禁止索引；任何Staging截图都不代表Production已经完成SEO配置。

## 可复用核心思想

- 跨平台不变量：后台操作者、内容审核人和前台公开作者是三个不同职责。把它们强行合并会泄露内部身份、削弱审计，也会让公开署名失真。
- WordPress/WooCommerce当前实现：独立用户账号负责权限和修订追踪，`post_author`负责公开归属，Yoast控制作者归档与结构化数据。单一品牌署名站点通常不需要重复的作者归档，但仍应在文章页清楚显示真实内容责任方。
- Shopify或其他平台对照：平台通常也会区分员工账号与博客作者显示名，但具体权限、作者对象和结构化数据输出机制需按平台版本验证，不能把WordPress的用户/作者模型直接视为跨平台标准。
- 常见误区：内容不涉及医疗专业判断，不等于可以匿名、使用`Admin`署名或省略事实审核；专业资质门槛可以降低，但内容责任和真实性不能取消。
