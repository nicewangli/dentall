---
项目: DentAll WooCommerce
日期: 2026-09-04
工作日: D52
计划检查点: D52（不自动等于一个完整实际工作日）
周次: W9
计划工时: 6小时50分钟有效工作
实际有效工时: 待用户选择是否记录
验收层级: Local技术验证
状态: 已完成（仅Local确认范围）
---

# DentAll 每日复盘 D52：品牌数据与筛选基线

## 相关笔记

- 前置笔记：[[Day51-手机与平板筛选抽屉]]
- 后续笔记：[[Day53-已选条件计数与重置]]
- 当日WordPress实战学习笔记：[[WordPress实战笔记/Day52-WooCommerce原生品牌taxonomy与筛选URL]]
- 同主题笔记：[[Day9-SKU品牌与属性规则]]、[[Day49-商品筛选合同与属性查询表]]、[[Day50-PC商品筛选与参数页索引收口]]

## 功能确认与授权

用户于2026-09-04明确回复：

> 确认按推荐范围，仅限Local实施Day52；预计品牌数量为≤30 / 31～100 / >100；无品牌商品同意留空；品牌归档第一版保持noindex。

授权覆盖WooCommerce 11.0.0原生`product_brand`数据、权限、CSV、归档、商品输出和Schema验证，以及在Shop/商品分类的D50/D51单一筛选DOM中接入品牌；允许使用并回收Local TEST品牌。品牌归档第一版使用`noindex`，非Local不配置、不部署。

原回复同时列出三个互斥规模档位，不能据此选择具体档位。规模不阻塞taxonomy、权限、空值、URL与安全骨架；超过30个品牌时的控件形态和负载复评转D53，当前小样本不得外推。

## 今日三个验收结果

- [x] 原生品牌数据、角色、CSV、URL与SEO合同有可追溯证据；无品牌留空，品牌归档第一版`noindex`。
- [x] Shop/商品分类品牌筛选复用Woo主查询、D50白名单与D51单一DOM，并可与分类、价格、Size、Shade及排序组合。
- [x] 正常、空、异常、搜索隔离、可访问性、Sitemap、缓存与数据恢复通过浏览器、WP-CLI和双路独立复核。

## 进度真实性检查

- 自然日期：2026-09-04。
- 实际有效工时证据（可选）：用户未选择记录；不以计划工时代填。
- 今天完成或推进的计划检查点：D52 Local推荐范围完整落地并收口。
- 本日最高验收层级：Local技术验证；不等于正式品牌内容、人员录入、Staging、Production或真实规模验收。
- 可由用户直接复演的结果：品牌后台与商品分配、原生CSV列、Shop/分类筛选、品牌归档SEO、异常参数护栏，以及清理后无品牌的诚实空状态。
- 尚未完成：正式品牌清单与归属、实际规模档位、超过30项的控件/性能、真实辅助技术、Production缓存/CDN、非Local配置与抓取。

## 业务与技术边界

- 使用角色：Website Manager可创建、编辑、删除和分配品牌；Content Editor只能分配既有品牌。
- 使用频率：品牌term预计低频维护，商品关联会随批次录入发生；正式频率和人员反馈尚无业务样本。
- 数据来源：业务方批准的正式英文品牌清单与逐商品归属；TEST名称只验证机制，不成为正式事实。
- 数据量：用户尚未在`≤30`、`31～100`、`>100`中选定一个真实档位。
- 数据规则：扁平term、每商品最多一个主要品牌；已确认无品牌时不分配term，不创建`Unknown`或`Unbranded`。

## 方案与关键取舍

- 选择WooCommerce原生`product_brand`，不复制为Global Attribute、普通Product Tag、品牌CPT或ACF字段，也不安装品牌插件。原生能力已经覆盖后台关系、REST、CSV、归档、详情、Schema与查询，重复模型会增加同步和迁移成本。
- 前台继续复用Woo原生主查询和`WC_Widget_Brand_Nav`，只在Shop/商品分类接入D50/D51现有aside；不增加第二查询、AJAX、第二DOM或新JavaScript。
- Woo Brands会在优先级10直接读取公开GET，数组值曾能触发Fatal。子主题在同一查询Hook优先级1先验证：仅标量、最长512字节、纯正整数ID列表、当前关联公开商品的品牌才能进入Woo；其余变为空键，不形成品牌tax query。
- 有效品牌参数也只允许影响非搜索Shop/商品分类；商品搜索与其他商品taxonomy主动清空该值，避免手工参数绕过UI改变集合。
- 原生品牌链接被收敛到项目白名单并移除`filtering=1`；选中项补充`rel=nofollow`、`aria-current=true`和“selected; activate to remove”名称。没有新CSS规则，只扩展D50通用选中样式选择器。
- 品牌归档沿用Woo默认`/brand/{slug}/`。Yoast Local配置设为`noindex, follow`，实测无Canonical且品牌Sitemap为404；未来开放索引是新SEO决策。

## 专注周期记录

| 周期 | 计划 | 实际结果 | 证据/文件 | 用时 |
|---|---|---|---|---:|
| C1 | 关闭D51证据前置并核对原生能力 | D51四宽、断点、History、错误与搜索隔离补证完成；核对Woo原生taxonomy、CSV、详情、Schema和Widget | `PROJECT_STATE.md`、WooCommerce 11.0.0源码 | 未记录 |
| C2 | 冻结数据/权限/URL/SEO合同 | 接受扁平、最多一个、无品牌留空、默认`/brand/`和第一版noindex；登记规模未决 | `DECISIONS.md` ADR-033、`DATA_DICTIONARY.md` | 未记录 |
| C3 | 实施最小品牌筛选 | 在既有筛选模块增加品牌输入护栏、链接清洗、Widget输出和可读选中态；主题升至0.28.0 | `inc/catalog-filters.php`、`catalog.css`、`style.css` | 未记录 |
| C4 | 正常与组合验证 | 两个已关联TEST品牌分别命中#44/#46；Shop、分类、属性/价格/排序组合及四端同一DOM成立 | 登录态Local浏览器 | 未记录 |
| C5 | 异常、安全与SEO验证 | 修复数组Fatal、搜索有效值污染、空品牌陷阱和选中态语义；品牌归档、Canonical、robots、Sitemap通过 | `day52-brand-audit.php`、浏览器、WP-CLI | 未记录 |
| C6 | 独立Review与极端空组合 | Code与Test/SEO Agent复核；分类＋品牌＋仅另一商品拥有的Size为0结果时，品牌移除链接仍可见可键盘操作 | 双路Agent结论、登录态Local浏览器 | 未记录 |
| C7 | 清理、恢复态与文档 | 删除term 29～32、#44/#46关联及品牌计数transient；复核Shop、分类、商品、旧URL、搜索和Sitemap并同步文档 | WP-CLI、浏览器、项目文档 | 未记录 |

## 测试与验证

### 清理前正向证据

- 两个扁平TEST品牌分别关联发布商品#44和#46，审计在真实非空集合下19/19通过；原生CSV `Brands`列、角色能力、排序去重、主查询、最多一个品牌和扁平约束均成立。
- Shop按品牌30只返回Variable商品；商品分类按品牌29只返回Simple商品；品牌和Size/Shade/价格/排序组合仍使用Woo主查询。
- 390/768/1024/1440保持D51同一aside与2/2/3/4列；选中品牌有可见勾选、`aria-current`和移除说明，页面无横向溢出。
- 商品#44分配品牌时，详情品牌链接与Product Schema Brand由Woo原生输出；品牌归档`noindex, follow`、无Canonical。
- 数组、混合、未知与空品牌ID均不触发Fatal或生成品牌tax query；Shop异常参数页仍`noindex, follow`且Canonical回`/shop/`，异常值不传播。
- 带有效品牌29的商品搜索仍返回两件商品、0筛选UI、`noindex, follow`、无Canonical且不跳到商品详情。
- 临时空品牌term 31不进入筛选：Shop仍为两件商品、0选中项、0异常值传播。临时term 32制造分类零结果组合时，Brand选中移除链接仍可见并保留Size条件。

### 清理与恢复态证据

- 已解除#44/#46品牌关系，删除TEST term 29、30以及边界夹具31、32，并删除`wc_layered_nav_counts_product_brand` transient。
- 最终回读为`brands=0`、`assigned_products=0`；发布商品仍2件，#120～#130仍为11项Trash，属性查询表仍7行，#44/#46/#51～#53价格、库存和状态不变。
- 恢复态审计再次列出19项PASS；其中有效品牌正向分支在0品牌时条件跳过，因此它只证明清理后不变量，不能替代清理前非空集合证据。
- Shop与商品分类均返回两件基线商品、唯一筛选DOM、0个Brand标题；基础页面恢复`index, follow`与自身Canonical。
- 商品#44没有品牌链接，Product Schema没有`brand`；`/brand/test-ads/`返回404、`noindex, follow`且无Canonical。
- Sitemap Index为200且不含品牌项，`/product_brand-sitemap.xml`为404；清理后带旧品牌29的商品搜索仍返回两件商品、0筛选UI、无Canonical。

### 命令与静态质量

- `php -l`通过主题筛选模块与Day52审计脚本；`node --check`通过既有目录脚本；`git diff --check`通过。
- 最终PHP运行文件SHA-256：`8AD3AA42E371A601575C7327B1C6C5E3F3668FD70811E996ACACFD786FC7515A`；审计脚本：`045A3EEBD10A451A00F9ED3CC5B0A5B2BA8C69EFED52E53E53C7FAD2D9972CFC`。
- 最终浏览器页面Console错误为0。浏览器控制组件自身的Statsig网络超时不属于DentAll页面错误。
- 一次WP-CLI内联PHP因PowerShell引号解析失败并向忽略的本地`debug.log`追加诊断Fatal；随后改用无内联引号的只读命令完成复核，没有源码、数据库业务状态或前台运行故障。

## Codex Agent 调度与审查

- 今日风险等级：中。涉及公共商品查询、公开GET、URL/SEO、Schema、缓存与数据库TEST关系，因此达到Code Review和独立Test/SEO Agent门槛。
- 启动Agent及职责：品牌技术/Code Review Agent审查原生边界、安全、可访问性、性能和减法；项目状态/Test/SEO Agent独立复核数据、URL、robots、Sitemap、缓存和恢复态；项目/学习Agent完成当日实战学习笔记与双向索引。
- Review结果：运行实现与Local恢复态最终P0=0、P1=0、P2=0；P3为Woo内部Widget/markup兼容、真实规模、非Local缓存与匿名Coming Soon边界。
- 已关闭问题：数组输入Fatal、有效品牌污染搜索、空品牌造成不可清除0结果、品牌选中项缺机器可读状态、审计未建立Shop上下文、ADR回滚误删D50通用样式，以及零结果组合恢复路径疑点。
- 延期问题及计划：无功能性P2延期；实际品牌规模与`>30`控件/性能由D53处理。

## 代码规模与减法审查

- 相对D52写前，既有`inc/catalog-filters.php`由451行增至597行，净增146行和4个命名函数；没有新运行文件、CSS规则、JS函数、模板覆盖、插件或依赖。
- `catalog.css`只把既有选中样式扩到原生品牌列表，净增规则块为0；`style.css`只把主题版本0.27.0提升至0.28.0。
- 保留的四项职责分别是品牌早期输入护栏、品牌URL白名单、原生markup无障碍适配和Widget输出；生命周期与可独立回归点不同，继续塞入匿名流程会降低可读性。
- 没有保留Logo、计数、Chips、Reset、搜索框、折叠、大规模虚拟列表、自定义缓存、后台保存拦截或D53预实现。

## 决策与范围变化

- 今日决定：接受ADR-033，复用原生`product_brand`；无品牌留空；品牌归档第一版noindex；前台仅Shop/商品分类显示；搜索和其他taxonomy隔离。
- 新需求：无。数组/搜索/空品牌/ARIA修复均为已授权筛选能力达到安全、空态和可访问验收所必需的缺陷关闭。
- 工时与排期变化：未记录实际工时；规模复评转D53，不吸收到D52。
- 是否已确认：D52范围已由用户明确确认；三个品牌规模档位仍未形成单一选择。

## 影响与回滚

| 领域 | 结论 |
|---|---|
| 数据 | 最终没有正式或TEST品牌term/关系残留；保留Woo原生taxonomy。无品牌商品继续留空 |
| 权限 | 沿用既有Woo term能力；未新增角色/capability。CSV未知名称可能被有管理权限的导入者创建，需SOP抽查 |
| URL/SEO | 新确立`/brand/{slug}/`与`filter_product_brand`合同；品牌归档第一版noindex、无Canonical、不进Sitemap；筛选参数页noindex并回基础Canonical |
| 性能/缓存 | 单请求复用合法品牌ID集合；计数沿用Woo一小时transient。未验证31～100、>100或Production页面缓存/CDN |
| 支付/物流/订单 | 无影响；未进入购物车、结账、库存扣减、支付、退款或履约逻辑 |
| 部署 | 仅Local代码与Local Yoast配置；Staging/Production未部署、未重放、未清缓存 |

回滚前台时只移除品牌Widget调用、品牌链接清洗、品牌markup适配及对应Hook，保留Size/Shade依赖的D50通用选中样式；主题版本可恢复0.27.0。输入Fatal护栏除非Woo上游已修复，不建议随UI移除。Yoast noindex是数据库配置，需单独回滚并复核Sitemap；主题回滚不会删除原生taxonomy或任何未来正式品牌数据。

## 问题与风险

- 阻塞：无。具体品牌规模未选不阻塞当前骨架，但阻塞大目录UI/性能结论。
- 技术债：`WC_Widget_Brand_Nav`属于Woo内部类，markup适配依赖其当前`li/a`结构；Woo升级时必须回归，类不存在时当前降级为不输出品牌组。
- 需要他人提供：正式品牌清单、规范英文名称与别名、逐商品品牌归属、实际品牌总量及素材授权。

## 今日复盘

- 完成：Day52 Local数据、权限、CSV、查询、URL、SEO、可访问性、缓存和恢复态闭环；TEST品牌全部回收。
- 未完成及原因：正式内容、真实规模、非Local缓存/抓取和人员录入没有获得数据或部署授权，不属于本日完成口径。
- 实际工时与计划偏差：未记录。
- 今天学到的内容：复用平台原生模型不能只看后台字段是否存在，还要检查公开GET读取时机、Widget空态、SEO输出、CSV副作用及停用/升级边界。

## WordPress实战学习笔记收尾

- [x] 已使用[[WordPress实战笔记/WordPress实战学习笔记模板|WordPress实战学习笔记模板]]核心骨架，生成[[WordPress实战笔记/Day52-WooCommerce原生品牌taxonomy与筛选URL]]。
- [x] 已讲清taxonomy、查询Hook顺序、URL清洗、原生Widget、SEO、缓存、排错和恢复态。
- [x] 已在[[WordPress实战笔记/WordPress实战笔记索引|WordPress实战笔记索引]]登记，并与Day51学习笔记及本项目日报建立双向链接。
- [x] 已检查未写入密码、Cookie、私钥、支付密钥、真实客户数据或个人目录。
- 延期原因与补写节点：无。

## 明日启动点

- 明日第一件事：按D53已确认的30个有效品牌规模，实现已选条件、计数、逐项移除与统一重置，并以代表夹具验证查询和缓存负载；完整文字列表不增加搜索或折叠。
- 需要提前准备：正式或脱敏品牌数量、常见品牌名长度、同一商品是否存在多品牌例外、运营筛选优先级；没有内容时仍可用明确TEST数据验证骨架。

## 可复用核心思想

- 跨平台不变量：品牌首先是业务实体与商品关系；先冻结语义、空值、唯一性、维护权限、公开URL和索引策略，再选择字段或应用。
- WooCommerce/WordPress实现：同一`product_brand`关系可贯穿后台、CSV、REST、查询、归档、详情和Schema；复用原生能力时仍必须在上游读取公开输入之前完成类型与范围验证。
- 空态原则：一个筛选条件即使使结果归零，也必须保留可见、可键盘操作的撤销路径；“Widget不报错”不等于用户可恢复。
- SEO与缓存原则：参数被清洗掉不等于请求URL不存在；异常参数页仍需明确robots、Canonical和缓存键，防止低质量索引或响应串用。
- Shopify或其他平台：Vendor、Metafield、Collection或第三方筛选应用可能承担相似职责，但路由、唯一性、搜索隔离和索引机制必须按平台重新验证；不能照搬Woo的taxonomy与GET参数。
