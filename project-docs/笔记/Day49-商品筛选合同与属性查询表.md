---
项目: DentAll WooCommerce
工作日: D49
计划检查点: D49（不自动等于一个完整实际工作日）
日期: 2026-09-03
实际有效工时: 未记录；不使用计划工时代替
验收层级: Local商品级筛选数据合同与WooCommerce属性查询表
状态: 已完成（Local确认范围；筛选UI与参数页robots转D50）
tags:
  - DentAll
  - Day49
  - WooCommerce
  - ProductFiltering
  - AttributeLookup
---

# DentAll 每日复盘 D49：商品筛选合同与属性查询表

## 相关笔记

- 每日笔记索引：[[README|DentAll每日笔记索引]]
- 前置项目笔记：[[Day48-商品分类内容与W8列表回归]]
- 当日学习笔记：[[WordPress实战笔记/Day49-WooCommerce属性查询表与商品级筛选]]
- 前置学习笔记：[[WordPress实战笔记/Day48-WooCommerce分类描述与SEO模板边界]]
- 数据字典：[[../DATA_DICTIONARY|数据字典]]
- URL与SEO合同：[[../URL_SEO_MAP|URL与SEO映射]]
- 决策：[[../DECISIONS#ADR-030：第一版商品筛选采用WooCommerce原生商品级合同与属性查询表|ADR-030]]
- 后续项目笔记：[[Day50-PC商品筛选与参数页索引收口]]
- 后续学习笔记：[[WordPress实战笔记/Day50-WooCommerce链接式筛选与参数治理]]

> [!check] 当前结论
> Day49已按用户确认范围在Local完成。先用DentAll 0.25.0补齐Shop、有结果商品搜索和短内容分类#18三页×四宽的最终证据，关闭Day48唯一P2；再冻结分类、价格、Size、Shade的商品级筛选合同，完整重建并启用WooCommerce属性查询表。最终查询表7行/2父商品，Direct Updates为`yes`、Optimized Updates为`no`，所有属性归档和缺货设置保持原值。11个主查询与数据完整性检查通过，没有修改商品、Variation或#120～#130。筛选参数页目标robots已经冻结，但当前Local仍是`index, follow`；因为本日明确不做筛选UI/SEO代码，该项作为D50首次建立站内筛选链接前的P3前置条件，不阻塞D49。

> [!success] 后续关闭
> D50已在首次输出筛选链接的同一Local实施中将任意价格、`filter_*`和`query_type_*`参数页设为`noindex, follow`，并保留基础归档Canonical；该P3已关闭，详见[[Day50-PC商品筛选与参数页索引收口]]。

## 授权与实施边界

用户于2026-09-03明确回复：

> 确认按推荐范围实施 Day49，仅限 Local：先关闭 Day48 P2；冻结分类、价格、Size、Shade 的商品级筛选合同；允许重建并启用 WooCommerce 属性查询表，Direct Updates 开、Optimized Updates 关；属性归档继续关闭；不恢复 #120～#130，不修改商品和缺货设置，不做筛选 UI、品牌、评分、插件或非 Local 变更。

本轮实施合同：

- 只操作Local；配置写入只限WooCommerce属性查询表重建、启用及Direct/Optimized开关。
- 冻结Shop与商品分类归档上的分类、价格、Size、Shade参数、组合关系、分页重置、SEO目标和父商品级Variation语义。
- 使用当前2个发布TEST商品和3个Variation做代表验证，不增加或修改商品数据。
- 属性归档继续关闭；`woocommerce_hide_out_of_stock_items`继续为`no`。
- 使用WooCommerce原生命令、主查询和公开数据对象，不直接改商品表或手工写lookup行。

明确不做：

- 不恢复或永久删除#120～#130，不制造新的多页商品集合。
- 不做PC/移动筛选UI、移动抽屉、品牌、评分或严格同Variation组合筛选。
- 不新增或安装插件，不改主题、WooCommerce核心、模板、JavaScript、CSS或查询封装。
- 不修改商品、Variation、term、库存、缺货策略、价格、分类归属或正式内容。
- 不部署Staging/Production，不改缓存、支付、物流、税费、订单、DNS或Coming Soon。

## 当日最多3项验收结果

1. [x] Day48最终0.25.0恢复态Shop、有结果商品搜索、短内容分类#18完成390/768/1024/1440补证，原P2关闭且#120～#130未恢复。
2. [x] 分类、价格、Size、Shade字段、参数、组合、父商品级Variation语义和SEO/缓存目标形成可执行合同，明确Package Quantity、品牌、评分与严格同Variation不在v1。
3. [x] Local属性查询表完整重建并启用，Direct=yes、Optimized=no；主查询、数据完整性、正常/空结果与回滚边界通过，独立复核无P0/P1/P2。

## 7个专注周期执行记录

| 周期 | 目标 | 实际结果 |
|---|---|---|
| C1 | 关闭Day48证据P2 | 0.25.0下Shop、商品搜索和#18各保存四宽截图；Title与Head复核通过，原P2关闭 |
| C2 | 建立D49写前基线 | 记录3个全局属性、5个商品/Variation对象、11个Trash对象、4项Woo设置和lookup 7行基线 |
| C3 | 冻结字段与组合语义 | 确认分类、价格、Size、Shade；同属性OR、跨属性/价格AND、条件变化回第一页，接受父商品term语义 |
| C4 | 冻结URL、SEO与缓存边界 | 参数名、Canonical、目标robots、Sitemap与缓存键写入正式文档；识别当前robots实施差距 |
| C5 | 重建并启用查询表 | 从头重建派生表，启用使用，Direct改为yes，Optimized保持no |
| C6 | 主查询与数据回归 | 11个主查询、实际SQL数据源、登录态单结果/空态、商品/Variation/Trash和属性归档通过 |
| C7 | 独立Review与收尾 | 查询/SEO复核P0/P1/P2=0、P3=1；配置/数据边界复核P0～P3=0；更新决策、数据、测试、状态、每日与学习笔记 |

## 实施前先关闭Day48 P2

最终运行版本仍为DentAll 0.25.0，商品保持#44/#46两项，分类#18保持短标题和空描述，#120～#130全程未恢复。

| 页面 | 390 | 768 | 1024 | 1440 | Head结果 |
|---|---:|---:|---:|---:|---|
| Shop `/shop/` | 通过 | 通过 | 通过 | 通过 | Title `Products - Dentall`；Canonical自身；index/follow |
| 商品搜索 `/?s=TEST&post_type=product` | 通过 | 通过 | 通过 | 通过 | Title `You searched for TEST - Dentall`；无Canonical；noindex/follow |
| 分类#18 | 通过 | 通过 | 通过 | 通过 | Title `TEST D12 Products - Dentall`；Canonical自身；index/follow |

- 共12张登录态截图保存在`outputs/day49/d48-p2-{shop,search,category}-{390,768,1024,1440}.png`。
- 390/768显示两列；1024/1440继续使用D44冻结的三/四列轨道，两张卡保持同排左对齐，没有可见横向裁切或重复工具栏。
- 分类页Console为`[]`。Chrome控制工具自身对外遥测超时不属于DentAll页面Console，未把它误记为站点错误。
- Head通过同一Local主题/插件请求链读取；D49配置前没有运行代码变化。

## 商品级筛选合同v1

| 维度 | 数据来源 | URL合同 | 组合规则 | 当前边界 |
|---|---|---|---|---|
| 分类 | `product_cat` | `/product-category/{slug}/` | 先确定当前集合 | 不新增平行分类GET参数 |
| 价格 | Woo商品价格lookup | `min_price`、`max_price` | 与属性AND | 不硬编码货币符号；反向区间返回空 |
| Size | `pa_size` | `filter_size`＋`query_type_size=or` | 同属性多值OR | 第一版字段 |
| Shade | `pa_shade` | `filter_shade`＋`query_type_shade=or` | 同属性多值OR | 第一版字段 |

其他规则：

- Size与Shade之间按AND；属性与价格之间按AND。
- 条件变化后回到基础归档第一页，后续UI不得把旧`/page/{n}/`带到新结果。
- 商品搜索继续使用D47合同，D49不增加搜索筛选入口。
- Package Quantity仍是#44的全局展示属性，但不进入第一版筛选。
- 品牌留D52；评分和其他候选不进入当前v1。
- 所有现有属性归档继续关闭，参数页不等于独立属性term归档。

## 为什么明确写成“商品级”

#46父商品声明：

- Size：`Small 98 mm`、`Large 105 mm`
- Shade：`Light`、`Medium`

实际只存在3个Variation：

| Variation | Size | Shade | 价格 | 库存 |
|---:|---|---|---:|---:|
| #51 | Small 98 mm | Light | 39.99 | 5 |
| #52 | Small 98 mm | Medium | 39.99 | 0 |
| #53 | Large 105 mm | Light | 49.99 | 3 |

没有`Large 105 mm + Medium`。WooCommerce原生属性查询分别判断同一`product_or_parent_id`是否拥有Large和Medium term，所以该跨属性请求仍返回父商品#46。它回答的是“这个商品系列包含这些值”，不是“存在一个同时满足全部条件且可购买的Variation”。D49接受前者；若以后必须回答后者，需要新确认查询语义、计数、价格、缺货、缓存与性能范围。

当前缺货隐藏为`no`，因此只出现在缺货#52上的Medium仍参与命中。D49没有修改该全局策略。

## 属性查询表实施结果

写前状态：

| 项目 | 写前 |
|---|---|
| 表使用 | disabled |
| 表内容 | 7行 / 2个父商品 / 最高父商品ID 46 |
| Direct Updates | `no` |
| Optimized Updates | `no` |

执行命令：

```powershell
php -d mysqli.default_port=10011 C:\wp-cli\wp-cli.phar wc palt regenerate --force --from-scratch --disable-db-optimization --batch-size=10 --path=app/public
php -d mysqli.default_port=10011 C:\wp-cli\wp-cli.phar wc palt enable --path=app/public
php -d mysqli.default_port=10011 C:\wp-cli\wp-cli.phar option update woocommerce_attribute_lookup_direct_updates yes --path=app/public
php -d mysqli.default_port=10011 C:\wp-cli\wp-cli.phar option update woocommerce_attribute_lookup_optimized_updates no --path=app/public
```

最终状态：

| 项目 | 最终 | 说明 |
|---|---|---|
| 表使用 | enabled | 属性筛选主查询改用lookup |
| 表内容 | 7行 / 2个父商品 | 完整重建前后数量一致 |
| Direct Updates | `yes` | 当前小批量场景优先保存后立即同步 |
| Optimized Updates | `no` | 未用2个TEST商品推断批量性能或扩展兼容 |
| 属性归档 | 3/3 false | 未改变 |
| Hide out of stock | `no` | 未改变 |

查询表是从商品、Variation与term关系派生的数据，不是另一个业务真相源。D49没有手工插入或修改任何lookup行。

## 主查询验证矩阵

| 场景 | 结果 | 数据源证据 |
|---|---|---|
| Shop基础 | #44、#46 | 不需要属性/价格lookup条件 |
| Size Small | #46 | SQL使用`wc_product_attributes_lookup` |
| Shade Medium | #46 | 缺货行仍命中，符合hide out of stock=no |
| Size Small或Large | #46一次 | 同属性OR |
| Size Large＋Shade Medium | #46 | 父商品级语义；不是同Variation |
| Price 25～40 | #46 | SQL使用`wc_product_meta_lookup` |
| Small＋Light＋Price 35～45 | #46 | 属性表与价格表共同参与 |
| 无效Size term | 0项 | 不回退全量 |
| Price 50～10 | 0项 | 不自动交换区间 |
| 分类#18基础 | #44、#46 | 当前分类集合 |
| 分类#18＋Size Small | #46 | 分类约束与属性约束共同生效 |

登录态1440额外截图：

- `outputs/day49/day49-filter-size-small-1440.png`：单结果#46。
- `outputs/day49/day49-filter-invalid-1440.png`：Woo原生无结果状态。
- 无效term空态Console为`[]`。

## 数据完整性与防误改

| 对象 | 最终结果 |
|---|---|
| #44 | Published Simple；24.99促销价、库存8、分类#18、Package Quantity展示值=`20 pcs`（term slug=`20-pcs`）不变 |
| #46 | Published Variable；Size/Shade、子项#51～#53、分类#18不变 |
| #51/#52/#53 | 价格39.99/39.99/49.99，库存5/0/3及属性组合不变 |
| 属性 | Package Quantity、Size、Shade继续存在且归档关闭，没有新term |
| #120～#130 | 11/11继续Trash，未恢复、未永久删除 |
| 商品与缺货设置 | 没有商品写入；hide out of stock继续`no` |

Direct Updates没有通过“临时改商品再改回”测试，因为用户明确禁止修改商品。当前验收证据是设置回读、WooCommerce当前源码路径和重建后派生行一致；未来首次正式小批量保存或导入时仍须验证实际同步时延。

## URL、SEO与缓存边界

目标合同：

- 价格/Size/Shade参数页统一`noindex, follow`。
- Canonical指向去筛选参数后的当前Shop或商品分类基础归档。
- 筛选参数URL不进入XML Sitemap。
- 缓存必须区分分类路径、有效筛选、排序和分页，不得把一个组合缓存成其他组合。

当前Local代表请求：

`/shop/?filter_size=small-98-mm&query_type_size=or`

- Canonical：`http://dentall.local/shop/`，符合目标。
- robots：`index, follow`，尚未符合目标。
- D49没有输出筛选链接，也按授权不新增SEO实现代码；独立Test/SEO将该差距列为P3。
- D50首次建立可点击筛选入口前必须实现并回归`noindex, follow`；届时若仍未关闭，升级为阻止D50完成的P2。
- 后续结果：D50已完成上述robots、Canonical、URL白名单及分页回归，参数页差距关闭。
- Local没有代表Production的页面缓存/CDN，未验证参数缓存键或真实抓取。

## 独立复核与风险分级

- 查询/SEO Agent独立重跑基础、Size、Shade、同属性OR、跨属性、价格、属性＋价格、无效term、反向价格和分类场景，确认属性SQL走查询表、价格SQL走价格lookup。
- 独立确认`Large + Medium`和缺货Medium结果符合已冻结父商品级语义及当前缺货设置。
- 配置/数据Agent通过SQL快照与连续binlog独立重建D49配置窗口：只发现属性查询表、相关options及正常后台会话/调度写入；商品、价格、库存、term、属性定义和运行文件均无写入。#44/#46/#51～#53基线、7行派生数据、三个属性归档关闭及#120～#130全部Trash再次通过。
- 结果：P0=0、P1=0、P2=0、P3=1。P3仅为参数页robots实现尚未进入D49范围，已有D50最晚节点。
- 后续结果：该P3已由D50在首次建立筛选链接时关闭；不改变D49当时“合同冻结、实现未进入范围”的历史结论。
- 配置/数据边界独立复核为P0=0、P1=0、P2=0、P3=0；其最终回读采用快照＋连续binlog，因为Local服务随后处于停止状态，这不改变此前在线WP-CLI、浏览器与Woo CRUD证据。
- 未验证：真实12+商品筛选分页、Production缓存/CDN、保存/CSV后的Direct同步时延、hide out of stock=yes、非Local环境和正式业务数据。

## 减法审查

运行代码净变化：

- 新增运行文件：0。
- 修改运行文件：0。
- 新增PHP函数、CSS规则块、查询封装、模板、JavaScript：0。
- 新增插件、依赖、Cron、远程请求、前端资源：0。
- 主题版本：继续0.25.0。

保留理由：WooCommerce 11.0.0已经提供Global Attribute、价格lookup、属性查询表和主查询参数；D49需要的是冻结合同并正确启用派生索引，不需要提前制造UI或自定义查询。

## 回滚

如Local属性查询表出现兼容或一致性问题：

```powershell
php -d mysqli.default_port=10011 C:\wp-cli\wp-cli.phar wc palt disable --path=app/public
php -d mysqli.default_port=10011 C:\wp-cli\wp-cli.phar option update woocommerce_attribute_lookup_direct_updates no --path=app/public
```

- `woocommerce_attribute_lookup_optimized_updates`继续保持`no`。
- lookup表是派生数据，可保留以便检查，也可在修正源数据后重新生成；回滚不需要改商品或term。
- 回滚后重复执行基础、属性、价格、组合和空结果矩阵，确认Woo回退查询正确。
- 本轮没有可回滚的主题代码、插件、URL、支付、物流或订单变更。

## 数据、URL、性能与部署影响

| 检查面 | 结论 |
|---|---|
| 数据 | 只重建派生lookup并改变启用/Direct设置；商品、Variation、term和回收站状态未变 |
| URL | 没有改Slug、固定链接或Sitemap成员；只冻结未来参数合同 |
| SEO | Canonical目标与当前输出一致；目标robots尚未实现，D50前置P3 |
| 缓存 | 没有修改Local或非Local缓存；只冻结参数隔离要求 |
| 性能 | 查询已使用原生lookup，但数据仅2父商品；没有前后基准，不宣称更快或零影响 |
| 支付/物流/订单 | 无影响；未触碰购物车、结账、支付、税费、运费、订单或退款 |
| 部署 | 仅Local数据库状态；Git不会自动把设置带到Staging/Production，非Local实施需单独确认、重建与验证 |

## D50衔接

D50先只读梳理并提交功能确认单。推荐边界：

- PC端只为分类、价格、Size、Shade建立最小可访问UI，复用现有主查询和D49合同。
- 同步实现参数页`noindex, follow`，并回归Canonical、分页重置、排序保留、无效/空结果与缓存键。
- 不加入品牌、评分、Package Quantity、严格同Variation、自定义查询或筛选插件。
- 390/768/1024/1440与键盘Focus仍是验收项；移动抽屉属于D51，不在D50顺手实现。

## 可复用核心思想

### 跨平台不变量

筛选先是数据与URL合同，后是界面。必须先回答“筛选哪个集合、字段来自哪里、组合是AND还是OR、库存和变体如何解释、参数页能否索引、缓存如何隔离”，否则一个看似简单的复选框会把数据、SEO和性能问题同时带进生产。派生索引可以重建，业务源数据不能被当作索引的附属品随意修正。

### WordPress/WooCommerce当前实现

DentAll在WooCommerce 11.0.0上使用`product_cat`、`min_price`/`max_price`、`filter_{attribute}`与`query_type_{attribute}`；属性条件由`wc_product_attributes_lookup`缩小父商品集合，价格由`wc_product_meta_lookup`处理。Direct Updates决定商品保存后的索引同步路径，Optimized Updates是另一条需兼容和规模证据的更新方式。当前结论只覆盖Local、小数据量和商品级匹配。

### Shopify或其他平台的对应机制

其他平台也需要集合、商品选项/变体、价格、库存、URL参数、Canonical/robots和缓存之间的一致合同；具体索引表、参数名、同变体匹配和应用扩展机制不可照搬WooCommerce。Shopify的Collection过滤、Search & Discovery及变体可用性语义在DentAll未实际验证，均标记为待验证，不进入本项目范围。
