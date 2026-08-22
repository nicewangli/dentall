# URL与SEO映射

## 原则

- URL发布后保持稳定；修改必须记录旧URL、301目标和上线日期。
- Staging始终禁止索引，Production上线时再显式检查索引设置。
- 筛选、排序、搜索和分页URL必须定义Canonical和索引策略。
- Website Manager可编辑内容级标题和描述，但不能无记录修改已发布Slug、分类层级、Canonical或固定链接结构；高影响URL和技术SEO由开发者复核。
- 每个HTML文档只能输出一个`<title>`。Local由Yoast负责SEO Title；DentAll Core仅在Yoast启用时移除WordPress核心重复的Block Template Title回调，Yoast停用后由WordPress核心回退输出，不把Title内容硬编码进主题或兼容模块。

## 第一版页面映射

具体英文Slug在关键词、分类结构和内容清单确认后冻结。项目为从零开发的新站，不包含旧站URL迁移。

### D8商品分类Slug候选

以下为设计与代表样本验证后的v1候选，尚未写入WooCommerce；正式冻结前需确认第一版销售范围。

| 入口/分类 | 类型 | Slug或URL候选 | 索引原则 |
|---|---|---|---|
| Dental Materials | Product Category | `dental-materials` | 有正式商品和独立描述后索引 |
| Dental Packaging | Product Category | `dental-packaging` | 标准/定制包装商品归档 |
| Instruments & Rotary | Product Category | `instruments-rotary` | 正式英文名确认后索引 |
| Orthodontics & Thermoforming | Product Category | `orthodontics-thermoforming` | EVA/护齿销售对象确认后索引 |
| Equipment | Product Category | `dental-equipment` | 正式销售范围确认后索引 |
| Infection Control & Disposables | Product Category | `infection-control-disposables` | 正式销售范围确认后索引 |
| Dental Lab Supplies | Product Category | `dental-lab-supplies` | 与Dental Materials分界确认后索引 |
| Implant Dentistry | Product Category | `implant-dentistry` | 有正式商品后创建并索引，避免空归档 |
| Custom Dental | Solutions/销售路径候选 | URL随D22 Solutions方案冻结 | 避免与Custom Restorations或商品分类争夺相同意图 |
| On Sale / Deals | 动态集合，不是Product Category | `/deals/`候选 | D16/D43确认Canonical、分页和空状态 |

- 商品分类归档继续使用`/product-category/{slug}/`基线。
- 商品固定链接使用`/product/{slug}/`，不把分类路径嵌入商品URL，避免改主分类导致商品URL变化。
- D16 C6只读审计发现Staging两个已发布TEST商品实际为`/shop/{slug}/`，后台确认这是自定义产品基础；用户随后在Staging与Local选择WooCommerce默认`/product/`。WooCommerce 11.0.0保存默认项后会将其规范化为实际`product`基础，刷新页面回显第四项`product/`属于预期行为。当前Local与Staging均以`/product/{slug}/`承载商品详情、以`/shop/`承载商品归档；Staging两个代表TEST商品和商店归档已回归通过，Production未修改。
- 分类最多两级；D25后修改分类Slug或层级必须登记旧URL、301重定向、Canonical、站点地图和内链影响。
- 设计稿中的分类数量为占位值，不进入SEO文案或结构化数据。

| 页面类型 | URL草案 | 索引 | Canonical | 备注 |
|---|---|---:|---|---|
| 首页 | `/` | 是 | 自身 | ADR-022：静态Page `Home`；当前为空白结构页，正式内容与组织/网站Schema后续完成 |
| 商店 | `/shop/` | 是 | 自身 | 商品归档 |
| 商品分类 | `/product-category/{slug}/` | 是 | 自身 | 分类描述避免重复 |
| 商品详情 | `/product/{slug}/` | 是 | 自身 | Product Schema |
| 品牌 | 待确认 | 视内容 | 自身 | 方案冻结后补充 |
| 商品搜索 | `/?s={term}&post_type=product` | 通常否 | 规则待确认 | 避免低质量搜索页索引 |
| 排序参数 | 商店URL加参数 | 否 | 基础归档 | 参数不生成独立索引页 |
| 筛选参数 | 归档URL加参数 | 通常否 | 基础归档/策略待定 | 高价值组合需单独评估 |
| 购物车 | `/cart/` | 否 | 自身 | noindex |
| 结账 | `/checkout/` | 否 | 自身 | noindex |
| 我的账户 | `/my-account/` | 否 | 自身 | noindex |
| 博客 | `/blog/` | 是 | 自身 | ADR-022：Page `Blog`被指定为文章页；WordPress自动输出文章归档，正式列表UI与分页策略后续完成 |
| 文章 | `/blog/{slug}/` | 是 | 自身 | ADR-021已冻结；不嵌入分类，D25后变更必须登记301、Canonical、Sitemap和内链影响 |
| 文章分类归档 | `/blog/category/{slug}/` | 满足内容门槛后是 | 自身 | 主归档维度；正式名称/Slug、多篇文章和独立说明后索引，TEST/空/薄弱分类不得进入Production |
| 文章标签归档 | `/blog/tag/{slug}/` | 否 | 无/按Yoast输出复核 | 保留标签能力但统一`noindex`并从Yoast Sitemap排除；不得批量制造近义标签 |
| 作者归档 | `/blog/author/{slug}/`形态但关闭 | 否 | 不适用 | 已验证301到首页；后台`post_author`保留内容作者，受支持字段修订保留对应修改账号，前台统一署名`DentAll Editorial Team`；发布状态操作留痕按D23另行治理 |
| 日期归档 | `/blog/{year}/{month}/`形态但关闭 | 否 | 不适用 | 已验证301到首页；文章仍保留真实发布日期和修改日期 |
| Solutions | `/solutions/`候选；真实独立条目才使用`/solutions/{slug}/`候选 | 满足正式内容门槛后是 | 自身 | ADR-023已接受：第一版使用原生Page优先，不建Solutions CPT。2026-08-21 Staging Page列表截图未发现Solutions对象；候选URL不表示Page已经创建或发布，正式Slug、菜单与条目范围仍待业务内容确认。若未来迁移CPT，优先保持URL，否则登记301、Canonical、Sitemap与内链变更 |
| About | `/about-us/` | 是 | 自身 | Slug待确认 |
| Contact | `/contact-us/` | 是 | 自身 | 联系方式和组织信息一致；`?product_id={ID}`只预填定制商品上下文，Canonical仍为无参数URL，不生成重复索引页 |
| FAQ | `/faq/` | 是 | 自身 | FAQ Schema需符合页面内容 |
| 政策页 | 按页面定义 | 是/视页面 | 自身 | 隐私、退款、配送等 |
| 404 | 无固定URL | 否 | 无 | 返回真实404状态码 |

## 重定向登记

| ID | 对象/类型 | 旧URL | 新URL | 状态码 | 原因 | 实现位置 | 负责人 | 上线版本/日期 | 验证 |
|---|---|---|---|---:|---|---|---|---|---|
当前不存在旧站，因此没有历史URL重定向清单。后续如发生已发布URL变更，再按变更记录补充301映射。

### D16已发布Slug、301与Canonical流程

#### 先判断URL是否已经形成公开资产

- Draft、Private或从未对外使用的Local/Staging TEST URL，可在首次正式发布前直接修正Slug；不进入Production 301登记，但仍需避免与正式Slug冲突。
- 已在Production发布、进入Sitemap、被内链/广告/邮件使用或可能已有外链的URL，均按公开资产处理；拼写修正、名称优化也不能绕过变更流程。
- Staging保持受保护和`noindex`。Staging验证通过不代表Production重定向已启用，也不能把Staging URL写入正式Canonical。

| 场景 | 正确处理 | Canonical | 明确禁止 |
|---|---|---|---|
| 首次发布前修正Slug | 直接使用最终Slug，无需301 | 正常页面使用自身URL | 为未公开TEST URL制造无意义重定向 |
| 已发布页面永久迁移到新URL | 旧URL一跳301到最相关的新URL | 新页面输出自身Canonical | 只改Slug、不验旧URL；用Canonical代替301 |
| 两个URL必须同时可访问且内容重复/高度相似 | 保留访问需求，经复核将重复页Canonical指向主页面 | 主页面自身Canonical；重复页指向主页面 | 把不相关商品Canonical到热门商品或分类页 |
| 临时活动或短期实验 | 另行评估302/307及索引影响，不纳入普通Slug修改 | 保持与临时策略一致 | 把临时跳转误设为永久301 |
| 页面撤销且没有等价替代 | 交由D16 C5决定保留停售页或404/410 | 不伪造不相关Canonical | 批量跳首页、商店或上级分类制造soft 404 |

#### 单个已发布Slug变更步骤

1. 保存旧URL、拟定新URL、变更原因、页面状态、替代关系、负责人和回滚方案；未登记前不保存新Slug。
2. 确认新URL不会冲突，目标页面内容与旧URL意图一致；只有永久迁移才使用301。
3. 先在Local/Staging复演，再于批准窗口实施Production变更；同时更新站内链接、导航、结构化数据引用和Sitemap来源。
4. 清理受影响页面/URL缓存后验证：旧URL一跳301、新URL200、无循环或链式跳转、新页面仅一个自身Canonical且未被意外`noindex`。
5. 复查Sitemap不再列旧URL、主要内链使用新URL，并记录上线证据；永久重定向原则上至少保留一年，仍有外链或业务引用时继续保留。

#### 当前技术实现边界

- WordPress 7.0.4会为已发布、非层级内容保存`_wp_old_slug`，旧Slug命中404时尝试301到当前固定链接；Product属于非层级内容，可利用此核心回退机制。
- 核心旧Slug回退不是DentAll的重定向登记系统，不能覆盖分类/层级变化、已删除对象、任意路径映射、冲突、缓存和批量迁移；每次仍须验证旧URL的真实状态码与目标。
- 当前没有重定向插件或项目自定义301引擎，也不因C4提前安装或编写。首次真实映射出现时，根据数量、性能、审计、环境同步和回滚要求，在Cloudways Web Rules、WordPress核心能力或版本化项目实现之间选择，并单独评审。

### D16临时缺货与永久停售URL生命周期

#### 状态判断

- “暂时无法购买但业务确认仍会补货/继续销售”是临时缺货；没有明确结论时先按临时缺货处理，并登记下次业务复核日期，不因不确定性自动升级为永久SEO变更。
- “业务确认不再销售当前商品/型号”才是永久停售。库存数量0或`outofstock`只能表达当前不可购买，不能单独证明永久停售。
- 安全召回、法律下架、侵权或医疗合规问题不走普通停售流程：必须先立即阻止购买并升级给业务、合规和开发负责人，再单独决定页面告知、状态码和保留证据。

| 生命周期场景 | 页面/购买状态 | 索引与Canonical | URL处理 | 目录与内链 |
|---|---|---|---|---|
| 临时缺货，计划补货 | 保持`publish`和原内容，库存为`outofstock`、禁止Backorders、无购买控件，清楚显示暂不可购买 | 保持可索引、自身Canonical；结构化数据使用`OutOfStock` | 保留原Slug和URL，不301、不404/410 | 第一版默认保持目录可见；可提供真实相关替代，但不冒充同一商品 |
| 永久停售，有严格一对一后继商品 | 旧商品停止购买；替代关系由业务确认且满足相同用途/搜索意图 | 新商品200、可索引且自身Canonical | 旧URL一跳301到后继商品，并登记和验收 | 所有销售入口、主要内链和Sitemap改向新商品 |
| 永久停售，无严格替代，但旧页仍有流量、外链、订单支持、说明书或独立信息价值 | 原URL保留200，明确显示`Discontinued`和不可购买，可展示多个相关替代 | 内容仍有独立价值时保持可索引和自身Canonical；不Canonical到非等价商品 | 不改Slug、不301 | 从主要可售商品网格/促销入口移除，但保留必要支持或上下文内链，避免孤立页面 |
| 永久停售，无严格替代且无持续用户价值 | 移除商品内容和购买入口 | 从Sitemap和主要内链移除，不输出可索引商品页 | 返回真实404；只有明确需要表达永久删除时才评估410 | 404页提供普通导航，但不得自动跳首页、商店或分类 |
| 单个Variation停售、父商品仍有其他合法可售组合 | 仅该Variation不可购买，不删除父商品 | 父商品保持自身Canonical和正常索引 | 父商品URL不变，不为Variation制造独立301 | 选择器只展示准确状态；不得让停售组合恢复为可购买 |
| 所有Variation均永久停售 | 按整个父商品的“严格替代/保留200/404”决策处理 | 随父商品方案 | 随父商品方案 | 随父商品方案 |

#### 永久停售决策步骤

1. 业务方书面确认：永久停售事实、最后可售日期、是否继续提供说明/售后，以及候选替代商品。
2. Website Manager停止购买并保留SKU、订单和历史引用；不得删除商品、复用SKU或擅自改Slug。
3. 业务负责人确认候选替代在产品用途、售后和客户承诺上是否成立；Website Manager记录搜索意图、内链和内容价值依据，开发者结合流量、外链、排名、状态码和索引影响复核技术处置。“同分类”“更热门”或“利润更高”都不足以构成301理由。
4. 在“严格替代301、保留200停售页、真实404/必要时410”三条路径中选择，并记录目标、理由、负责人、复核日期与回滚方案。
5. 开发者验证状态码、购买控件、Title/Canonical/robots、Product结构化数据、Sitemap、内链、缓存和重定向链；Production实施仍需单独批准。

#### 数据与实现边界

- WooCommerce 11.0.0原生库存状态只有`instock`、`outofstock`和`onbackorder`，没有独立的永久停售生命周期字段；DentAll不得把`outofstock`永久解释为`discontinued`。
- 当前WooCommerce结构化数据会把原生缺货输出为`OutOfStock`。Google支持`Discontinued`，但DentAll尚未实现该独立映射；只有真实永久停售商品选择保留200页时，才评估最小版本化字段和Schema过滤器，并完成前台、Feed和验证器回归。
- 当前没有真实永久停售样本，不新增ACF字段、插件或代码。通用决策骨架可继续；永久停售页面文案、严格替代关系和`Discontinued` Schema真实内容尚不能验收。

## 页面SEO字段

- SEO标题：每个可索引页面唯一。
- Meta描述：面向点击，不堆砌关键词。
- H1：每页一个主要H1，与页面目标一致。
- 图片alt：描述图片实际内容，装饰图可为空。
- Open Graph：核心页面提供分享标题、描述和图片。
- Schema：只输出页面真实可见且符合类型的数据。

### D16商品SEO字段责任矩阵

| 字段/输出 | 事实来源与默认责任 | Website Manager操作边界 | 内容与技术复核边界 |
|---|---|---|---|
| 商品名称 / H1 | WooCommerce商品名称；业务方确认正式名称与产品事实 | 可在草稿和日常内容维护中编辑；发布后若改变产品识别或搜索意图，先复核再保存 | Website Manager检查搜索意图与可读性；开发者只保证模板输出，不代写商品事实 |
| SEO Title | Yoast内容级SEO字段；留空时由Yoast模板生成 | 可独立填写和优化，不要求与H1逐字相同；必须保持唯一、准确且不堆砌关键词 | Website Manager维护逐内容SEO Title、抽样自检并可提出模板候选；Yoast全局模板由开发者配置和回归，不硬编码逐商品标题 |
| Meta Description | Yoast内容级SEO字段；不是WooCommerce简短描述 | 可独立填写；必须基于页面真实可见内容，不写未经确认的价格、认证、疗效或配送承诺 | Website Manager负责点击意图和长度自检；开发者只验证输出与缺省回退，不保证搜索引擎一定采用 |
| Slug | WordPress商品固定链接字段，最终形成`/product/{slug}/` | 首次发布前可按已确认英文名称设置；发布后仍保留技术权限，但不得无记录批量修改 | 已发布Slug变化必须登记旧URL、301、内链、Sitemap、Canonical和缓存影响；开发者负责技术验证 |
| Canonical | 默认留空，由Yoast为正常商品输出自身URL | 按用户决定保留Yoast高级字段权限；只有已确认重复/合并场景才手工覆盖，普通商品不得随意填写其他URL | Website Manager记录业务目标与理由；开发者验证目标状态、索引一致性及源码唯一性。Canonical不能代替301 |
| robots / 是否索引 | 由站点环境、页面类型和Yoast默认规则共同决定 | 保留高级字段权限；单页`noindex`、nofollow或高级robots变更必须记录原因并复核 | Staging始终禁止索引；Production页面级变更由Website Manager与开发者共同回归Sitemap、Canonical和前台源码 |

- Website Manager拥有内容级与Yoast高级元数据的实际操作权限；DentAll不增加额外代码限制，依靠培训、变更记录和上线复核控制高影响操作。
- 商品名称、SEO Title、Meta Description、Slug、SKU和Canonical职责不同，不得用一个字段代替另一个字段。
- D16 C3只冻结字段职责和操作边界，不填写真实商品SEO内容，也不修改现有URL、Canonical、robots、索引或重定向。
- D16 C4只冻结Slug、301与Canonical决策流程；当前重定向登记仍为空，未改Local、Staging或Production URL与配置。
- D16 C5冻结临时缺货与永久停售URL生命周期；未改变现有TEST商品库存、发布、目录、索引或URL状态。
- D16 C6最初只读审计未改URL；用户随后明确执行Staging产品基础切换。现有Staging TEST商品受密码保护且站点`noindex`，旧`/shop/{slug}/`不登记为Production 301资产。旧TEST URL会自动到达新`/product/{slug}/`，但本轮只验证跳转结果、未取得原始301/302状态码；Production真实迁移仍必须单独验证状态码。Staging全站`noindex`时Yoast不输出Canonical，Production自身Canonical另验。

### D17代表商品SEO输出验收

- 5个Staging代表样本均使用`/product/{slug}/`；D17没有修改既有已发布商品Slug、固定链接结构、Canonical、robots、Sitemap或重定向。
- Website Manager完成5个样本的SEO Title与Meta Description保存；前台/预览抽查确认单一Title、单一H1和Meta Description输出。已发布#32、#35的公开TEST URL保持不变并返回200。
- 草稿#45、#47、#52在登录态可预览，匿名请求返回404；草稿预览证据不能表述为已发布URL或Production SEO资产。
- Staging继续全站输出`noindex, nofollow`，Yoast在该边界下不输出Canonical。D17只验证字段持久化和受保护环境输出，不能证明Production可索引、自身Canonical、Sitemap或缓存已正确。
- 当前Production重定向登记仍为空。旧Staging `/shop/{slug}/`只确认可到达新URL，仍未取得原始301/302状态码，不登记为Production 301资产。
- #47的缺货Variation不产生独立URL、Canonical或重定向；父商品URL保持不变。真实永久停售仍需按D16的301/200/404/必要410流程取得业务事实后另行验收。

## 上线前SEO检查

- 已确认项目不存在旧站，无历史URL导出和迁移任务。
- 301映射经过抽样和自动检查。
- Sitemap只包含计划索引的正式URL。
- robots和站点可见性已从Staging规则切换到Production规则。
- Canonical、分页、筛选和搜索规则无冲突；第一版不启用多语言URL。
- 商品结构化数据无严重错误。
- 分析工具和站长平台归属由企业账户持有。
