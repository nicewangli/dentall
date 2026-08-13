# URL与SEO映射

## 原则

- URL发布后保持稳定；修改必须记录旧URL、301目标和上线日期。
- Staging始终禁止索引，Production上线时再显式检查索引设置。
- 筛选、排序、搜索和分页URL必须定义Canonical和索引策略。
- SEO人员可编辑标题和描述，但不能随意修改Slug、分类层级和固定链接结构。

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
- 分类最多两级；D25后修改分类Slug或层级必须登记旧URL、301重定向、Canonical、站点地图和内链影响。
- 设计稿中的分类数量为占位值，不进入SEO文案或结构化数据。

| 页面类型 | URL草案 | 索引 | Canonical | 备注 |
|---|---|---:|---|---|
| 首页 | `/` | 是 | 自身 | 组织/网站Schema |
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
| 博客 | `/blog/` | 是 | 自身 | 列表分页策略待确认 |
| 文章 | `/blog/{slug}/`或待定 | 是 | 自身 | 发布前冻结结构 |
| Solutions | 待确认 | 是 | 自身 | Page/CPT方案决定URL |
| About | `/about-us/` | 是 | 自身 | Slug待确认 |
| Contact | `/contact-us/` | 是 | 自身 | 联系方式和组织信息一致 |
| FAQ | `/faq/` | 是 | 自身 | FAQ Schema需符合页面内容 |
| 政策页 | 按页面定义 | 是/视页面 | 自身 | 隐私、退款、配送等 |
| 404 | 无固定URL | 否 | 无 | 返回真实404状态码 |

## 重定向登记

| ID | 旧URL | 新URL | 状态码 | 原因 | 上线版本 | 验证 |
|---|---|---|---:|---|---|---|
当前不存在旧站，因此没有历史URL重定向清单。后续如发生已发布URL变更，再按变更记录补充301映射。

## 页面SEO字段

- SEO标题：每个可索引页面唯一。
- Meta描述：面向点击，不堆砌关键词。
- H1：每页一个主要H1，与页面目标一致。
- 图片alt：描述图片实际内容，装饰图可为空。
- Open Graph：核心页面提供分享标题、描述和图片。
- Schema：只输出页面真实可见且符合类型的数据。

## 上线前SEO检查

- 已确认项目不存在旧站，无历史URL导出和迁移任务。
- 301映射经过抽样和自动检查。
- Sitemap只包含计划索引的正式URL。
- robots和站点可见性已从Staging规则切换到Production规则。
- Canonical、分页、筛选和搜索规则无冲突；第一版不启用多语言URL。
- 商品结构化数据无严重错误。
- 分析工具和站长平台归属由企业账户持有。
