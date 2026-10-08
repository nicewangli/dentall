---
类型: 项目Day笔记
项目: DentAll WooCommerce
日期: 2026-10-08
工作日: Day92
主题: URL与Canonical受控验证
状态: 进行中；隔离副本正常与有效分页通过，目标环境验收待完成
验收层级: 隔离副本技术抽样
tags:
  - DentAll
  - Day92
  - SEO
---

# Day92 URL与Canonical受控验证

## 结论

沿用D91建立的四日共用URL清单，在仅监听`127.0.0.1`的隔离副本观察WordPress可索引分支的路由、robots与Canonical；首轮发现测试路由未加保护头，修正仅隔离路由后重跑代表请求，外层HTTP均禁止索引。首轮证据支持：正常首页、Shop、商品分类和商品详情返回200并指向自身Canonical；合法变体参数指向父商品；排序参数指向Shop；价格筛选为`noindex, follow`并指向Shop；商品搜索为`noindex, follow`且无Canonical；未知URL为真实404。首轮只有两件商品，Page 2为404。第二轮仅在独立数据库用WooCommerce CRUD临时补11件TEST商品，使13件商品跨两页；真实Page 2为200、自身Canonical且上一页指向Shop，越界Page 3为404。夹具已删除并按九组快照恢复原值。

这只是当前分支代码加一份**独立旧Local数据库**的技术回放。Staging的`blog_public`保持0，WooCommerce Coming Soon未改变；Production未触碰。隔离PHP路由补充HTTP `X-Robots-Tag: noindex, nofollow`后，11类GET均读到该保护头；它只覆盖测试路由，不能将D92整体写为通过。旧Local的商品、标题、URL和内容不能外推当前Staging或Production。

## 相关笔记

- 前置项目笔记：[[Day91-SEO元数据模板与Staging验证]]
- 当日学习笔记：[[WordPress实战笔记/Day92-索引环境与Canonical分层验证]]
- URL合同：[[../URL_SEO_MAP|URL与SEO映射]]
- 后续项目笔记：[[Day93-Sitemap与抓取边界审计]]

## 目标与范围

- D92计划目标：审计正常URL、排序、筛选、搜索和分页的状态码、索引与Canonical，识别重复页面风险。
- 本轮执行：对隔离副本做只读HTTP抽样；为观察Yoast可索引分支，仅在隔离数据库临时设置`blog_public=1`，并关闭隔离副本的WooCommerce Coming Soon。
- 边界：没有修改Staging/Production的站点可见性、Coming Soon、robots、Canonical、URL、数据库内容、缓存或部署。隔离副本的HTTP保护头已重测，原Coming Soon值已恢复，服务已停机。

## 最多3项验收结果

1. [x] 首轮隔离副本覆盖基础归档、商品、排序、价格筛选、搜索和404，并分别记录状态码、robots及Canonical。
2. [x] 隔离PHP路由补护栏后重跑11类GET，均有`X-Robots-Tag: noindex, nofollow`；页面Meta仍用于观察Yoast分支，外网不可达性与目标环境另验。
3. [ ] 有效第2页已在隔离副本通过；仍需在Staging/后续目标环境核对分页、缓存键和内容一致性，再收口D92。

## 环境与证据边界

| 项目 | 当前事实 | 限制 |
|---|---|---|
| 运行代码 | 当前分支DentAll子主题与Core；WordPress 7.1及其他依赖来自旧Local副本 | 本轮未修改运行文件，不能代表Staging插件版本或配置 |
| 数据 | 与Staging不同的独立旧Local数据库 | TEST商品和旧标题只作机制样本 |
| 服务 | `http://127.0.0.1:16575`，仅loopback | 不能作为公网或Production结果 |
| 索引分支 | 隔离数据库临时`blog_public=1`；Coming Soon在隔离库关闭 | Staging仍为`blog_public=0`且匿名商品正文是Coming Soon |
| HTTP保护 | 首轮发现路由保护头缺口；仅测试路由补护栏后11类GET均有`noindex,nofollow` | 不等于Staging设置或外网不可达性验收 |
| 原始记录 | 忽略目录`.codex-tmp/day92-94-runtime/evidence/summary.json` | 含旧Local页面事实，不提交为正式内容 |

## 首轮URL矩阵

下表的Canonical均指向该loopback隔离站点的相应路径；“index”描述本轮页面Meta robots分支，不表示网页已允许公网抓取。

| 请求 | HTTP | 页面Meta robots | Canonical | 当前判断 |
|---|---:|---|---|---|
| `/`、`/shop/`、`/product-category/test-d12-products/` | 200 | `index, follow` | 各自无参数URL | 基础对象路由通过隔离抽样 |
| Simple与Variable商品详情 | 200 | `index, follow` | 各自商品URL | 仅旧Local TEST商品 |
| 合法Variable属性参数 | 200 | `index, follow` | 无参数父商品URL | 变体不生成独立Canonical |
| `/shop/?orderby=price-desc` | 200 | `index, follow` | `/shop/` | 排序不成为独立索引目标 |
| `/shop/?min_price=1` | 200 | `noindex, follow` | `/shop/` | 参数页索引与Canonical分开控制 |
| 商品搜索 | 200 | `noindex, follow` | 无 | 不强行Canonical到Shop |
| `/shop/page/2/` | 404 | `noindex, follow` | 无 | 只有两件商品，未覆盖有效分页 |
| 未知URL | 404 | `noindex, follow` | 无 | 真实404抽样通过 |

另查`/shop/page/1/`：隔离PHP路由给200、Canonical回Shop；运行中的Local Nginx及Staging均301回Shop。三者在路由层存在差异，应以目标环境的一跳301为验收条件，不能用Canonical相同掩盖状态码不同。

### 第二轮有效分页与精确恢复

原副本公开商品2件，Shop每页12件；仅在独立数据库用WooCommerce CRUD新增11件唯一标记的TEST Simple商品。`/shop/`主查询显示`found_posts=13`、`max_num_pages=2`、12张商品卡，Head `next`和可见分页链接均指向`/shop/page/2/`。真实`/shop/page/2/`为200，主查询`paged=2`、仅1张可见TEST商品卡，Title为`Products - Page 2 of 2 - Dentall`，页面Meta `index, follow`，Canonical指向自身，Head `prev`与可见上一页均指向`/shop/`，没有`next`。`/shop/page/3/`为真实404、Meta `noindex, follow`、无Canonical。三类响应仍带隔离路由HTTP `noindex,nofollow`；证据在忽略目录`evidence/day92-page2/`。

11件TEST商品已通过WooCommerce CRUD删除，商品posts/meta、term及关系、两个Woo lookup和关键设置共九组快照的行数与SHA-256均与创建前一致，相关Yoast Indexable夹具残留0。Coming Soon恢复`yes`，PHP/MySQL端口均停机；源Local关键设置及`wp-config`哈希不变。该恢复证明列出的范围，不宣称隔离数据库所有表逐字节相同。

## 实现来源与当前缺口

- [SEO兼容模块](../../app/public/wp-content/plugins/dentall-core/includes/seo-compatibility.php)在`wp_robots`后段对Shop/商品分类的价格、`filter_*`、`query_type_*`参数设`noindex, follow`，不改Yoast的Canonical表示层。本轮价格参数样本与合同一致。
- 普通页面和排序的Canonical由Yoast生成；商品搜索沿既有`noindex`且无Canonical合同。隔离回放没有新增Canonical代码。
- D91的Staging匿名响应全站`noindex,nofollow`且无Canonical，商品正文仍是Coming Soon；这证明保护策略当前生效，不证明在Staging临时开放后会得到同样的正常商品输出。
- 有效第2页的主查询、Canonical、`prev/next`与可见卡片已在隔离副本验证；目标环境缓存键、正式商品目录与真实服务器路由仍待核对。Local历史证据见[[../URL_SEO_MAP|URL与SEO映射]]。
- 测试路由保护头在11类GET中已复测为`noindex,nofollow`；服务监听地址实测仅为`127.0.0.1`。收尾记录确认隔离Coming Soon恢复为`yes`、PHP/MySQL端口停止、源Local关键选项及`wp-config`哈希不变；证据留在忽略目录的`evidence/closure.json`。

## 测试、风险与下一步

- 已读取：`.codex-tmp/day92-94-runtime/evidence/summary.json`的11类请求结果，核对状态码、页面Meta robots和Canonical；未运行真实浏览器四端或在线抓取验证器。
- 待验：Page 1跨路由差异、筛选组合与异常参数、Staging有效分页及缓存区分、Production自身Canonical及页面级索引。D92状态保持进行中。
- 业务边界：旧Local的TEST商品与旧标题不作为正式SEO内容；Website Manager仍负责正式名称、描述和商品事实。
- D93衔接：另行核对Sitemap与robots.txt的对象范围、页面索引策略和环境保护，不从本轮页面Meta robots直接推断Sitemap正确。
- 影响：本轮未改Staging/Production数据、URL、SEO配置、缓存、支付、物流或部署；隔离副本测试设置和11件TEST商品已恢复/清理，临时服务已停。隔离文件与数据库副本仍在Git忽略目录中，不作为正式内容发布。

## 可复用核心思想

### 跨平台不变量

Canonical描述相似URL的首选页面，robots决定当前响应是否允许索引，两者必须分别观测。测试“允许索引时会输出什么”应在不可被公众抓取的隔离环境进行，并单独验证访问护栏。无数据的第2页404不能替代有效分页；最少TEST夹具可验证分页后精确清理，并用数据快照证明恢复范围。

### WordPress/WooCommerce当前实现

WordPress查询先确定首页、归档、商品、搜索或404；Yoast生成相应Canonical，`dentall-core`再对目录筛选参数补`noindex, follow`。WooCommerce Coming Soon会改变匿名正文分支，`blog_public=0`又会改变SEO输出，必须将访问身份、正文和Head同时记录。

### Shopify或其他平台的对应机制

可迁移的是“正常URL、参数URL、分页与错误页分别建立索引合同，再用受控环境验收”的方法。Shopify或其他平台的Canonical与抓取设置入口未在本轮验证，不能照搬WordPress Hook或Yoast字段。
