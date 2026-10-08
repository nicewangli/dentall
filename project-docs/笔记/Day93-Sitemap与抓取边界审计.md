---
类型: 项目Day笔记
项目: DentAll WooCommerce
日期: "2026-10-08"
工作日: Day93
主题: Sitemap与抓取边界审计
状态: 只读阶段审计；D93未完成
tags:
  - DentAll
  - Day93
  - SEO
---

# Day93 Sitemap与抓取边界审计

## 结论

本轮只读盘点确认：Cloudways Staging应用`6604195`仍为`blog_public=0`、WooCommerce Coming Soon开启，但匿名请求可得到200响应。新鲜请求的Yoast Sitemap Index列出7个子地图，其中品牌地图有3条URL、商品标签地图有248条URL，Page地图仍列有TEST页面。品牌地图的实际输出与第一版品牌归档`noindex, follow`、不进入Sitemap的既定合同不符。Yoast 28.2本机源码核对表明，全站`blog_public=0`不会单独从taxonomy Sitemap排除品牌；Staging的具体taxonomy/term设置、过滤器及XML缓存状态尚未读回，不能断言某个设置值错误。商品标签的正式索引策略正等待用户决定。D93尚未完成，也不代表Production抓取配置通过。

## 相关笔记

- 当日学习笔记：[[WordPress实战笔记/Day93-抓取信号与站点地图分层]]
- 前置元数据与四日共用样本：[[Day91-SEO元数据模板与Staging验证]]
- 前置URL与Canonical抽样：[[Day92-URL与Canonical受控验证]]
- 品牌第一版Local基线：[[Day52-品牌数据与筛选基线]]
- 品牌机制学习笔记：[[WordPress实战笔记/Day52-WooCommerce原生品牌taxonomy与筛选URL]]
- URL与SEO合同：[[../URL_SEO_MAP|URL与SEO映射]]
- 决策记录：[[../DECISIONS|项目决策]]中的ADR-033
- 项目状态：[[../PROJECT_STATE|项目当前状态]]
- 每日笔记索引：[[README|DentAll每日笔记索引]]

## 范围与最多3项阶段验收

1. [x] 只读确认当前Staging的站点索引、Coming Soon与匿名访问边界；未解除保护。
2. [x] 以新鲜HTTP请求核对Sitemap/robots响应，并用只读WordPress REST词项统计量化商品标签现状。
3. [ ] 定位两条`X-Robots-Tag`的来源，核实品牌Sitemap为何偏离合同，取得商品标签正式策略后完成受控抓取验收。

本阶段不修改Yoast选项、词项、文章、商品、`blog_public`、WooCommerce Site Visibility、`robots.txt`、Sitemap、缓存策略或Production。当前计划D93目标仍是XML地图、robots、环境抓取规则与抓取模拟；上述第三项及后续回归未完成，故只记阶段事实。

## 只读证据

| 检查对象 | 本轮观察 | 证据边界 |
|---|---|---|
| Staging环境 | `blog_public=0`；WooCommerce Coming Soon为`yes`；匿名页面请求可返回200 | `noindex`与Coming Soon均不是全站HTTP访问认证；本轮未重新读取Cloudways Password Protection设置，匿名响应未遇认证挑战 |
| Sitemap Index | 新鲜请求为200，列出7个子地图 | 地图可访问不等于其中URL允许搜索引擎索引 |
| 品牌子地图 | 列出3条品牌归档URL；REST中ADS、Aidite、Toboom三个品牌的Yoast `robots.index=noindex` | 与ADR-033及URL映射的第一版不入图合同不符；全站`blog_public=0`会影响robots，却不直接排除taxonomy Sitemap。REST值不能区分全站与品牌专属设置，具体选项、过滤器和缓存待只读核实 |
| 商品标签子地图 | 列出248条商品标签归档URL | 仅证明当前输出范围；正式保留、排除或逐项治理尚未决定 |
| Page子地图 | 仍列有TEST页面 | 技术夹具不得因出现在地图就视为正式发布内容；Production迁移前须核对 |
| WordPress REST商品标签 | 共294个词项；46个为空且均无描述；199个仅关联1个商品；49个至少关联2个商品 | 统计描述的是当前Staging词项分布，不判定每个标签的业务价值；空词项数与地图条数相加等于总数，不据此替代逐URL一致性检查 |
| `robots.txt`与Sitemap响应头 | 同一响应中出现`X-Robots-Tag: noindex, follow`和`X-Robots-Tag: noindex, nofollow` | 两条头的来源未定位；不可直接归因于Cloudways或某个插件 |
| 静态CSS响应头 | `/wp-includes/css/dist/block-library/style.min.css`返回200，也带`X-Robots-Tag: noindex, nofollow` | 支持“存在疑似独立于Yoast页面动态输出的全局响应头规则”；尚不能证明所有路径均由同一层添加 |

核对使用匿名HTTPS只读请求；对Sitemap和robots使用新查询参数取得未命中旧页面缓存的响应，再记录状态、响应头与XML内容。商品标签分布来自只读REST分页结果。没有执行保存、清缓存、爬虫提交或批量改词项。既有Local数据库中Yoast `noindex-tax-product_brand=true`且品牌词项为0；这是Local事实，不能直接推定Staging的选项值，亦不能单凭Staging品牌地图推定其开关为`false`。本机Yoast 28.2源码`inc/sitemaps/class-taxonomy-sitemap-provider.php:64-73,240-259,312-336`显示，公开taxonomy是否入图取决于taxonomy级`noindex-tax-*`选项、排除Filter以及term级`wpseo_noindex`/Canonical；该provider直接读取选项，不使用`yoast_indexable`作为入图条件。`src/integrations/front-end/indexing-controls.php:50-54`的`blog_public`影响前台robots，未在上述Sitemap provider中作为排除条件。源码只能缩小候选原因，不能代替Staging设置读回。

## 差异、风险与待办

- **品牌归档：** ADR-033与[[../URL_SEO_MAP|URL与SEO映射]]确定第一版`noindex, follow`且不入Sitemap；Staging当前地图列3条。先只读核对Staging品牌taxonomy选项、term级覆盖与排除Filter，再比对新鲜和标准地图响应及缓存；确认原因后再按最小范围提出修正与恢复步骤。
- **商品标签：** 248条地图URL中，大量词项只有一个关联商品；数量本身不证明页面应统一`noindex`，但提示薄弱或重复归档风险。先由业务方确认标签的用途、正式词表和目标索引策略，再逐类决定是否保留、合并或排除；不擅自删除词项或批量改URL。
- **TEST页面：** Page地图仍有样本内容。正式上线前由内容负责人明确保留、撤稿或替换，并核对Sitemap、内链、状态码及缓存；当前不把TEST页面视为正式内容验收。
- **双响应头：** 静态CSS也有固定`noindex,nofollow`，说明仅查看WordPress/Yoast选项不足以解释HTTP终态。须区分WordPress/插件、Web服务器和缓存层的贡献；在来源确认前，不把该头当作可依赖的临时开放安全闸门。
- **环境边界：** 本轮不临时开放公开Staging索引。当前匿名200、地图含TEST与待审归档，短暂切换`blog_public=1`可能暴露测试URL，也可能让缓存保留旧robots/Canonical响应。Production式索引/Canonical验收优先在环回绑定且有访问控制的隔离副本中进行；现有Staging继续`blog_public=0`。

本轮仅增加记录，站点配置、数据、URL、索引、SEO输出、缓存、支付、物流和部署均未改变，因此无需站点回滚。未来若经确认修改品牌/标签Sitemap或robots，应先保存目标设置与term覆盖，只改批准的字段，分别核对原点与缓存响应，并按目标URL或子地图验证恢复；不得用全站清缓存或整项选项覆盖代替差异核对。D93完成仍需明确商品标签策略、关闭品牌输出差异、解释双响应头并在受控环境跑代表抓取矩阵。D91～D94各自定向验收后，再按共用URL样本做联合回归。

## 可复用核心思想

### 跨平台不变量

站点地图是发现清单，页面是否可索引还取决于HTTP状态、robots信号、访问控制及实际内容；地图列出URL不等于搜索引擎会收录。环境级保护与内容级索引策略要分开设计，缓存可能让两层呈现不同时间的状态。上线前应同时核对生成源、公开响应和正式内容清单。

### WordPress/WooCommerce当前实现

本项目由WordPress的`blog_public`、WooCommerce Coming Soon、Yoast索引与Sitemap、Web服务器响应头及缓存共同决定访客看到的抓取信号。`product_brand`和`product_tag`是不同taxonomy，Local品牌选项不能替Staging品牌地图作证。只读REST词项统计可提示治理规模，但是否索引仍须由业务用途、归档内容和URL合同决定。

### Shopify或其他平台的对应机制

其他平台同样需要把集合/标签页面的发布状态、站点地图、robots、访问控制和CDN响应分开核验。Shopify具体Sitemap与集合标签配置未在本轮验证，不能将Yoast选项或Woo taxonomy规则直接套用。
