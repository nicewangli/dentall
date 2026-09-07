---
项目: DentAll WooCommerce
日期: 2026-09-07
工作日: D57
计划检查点: D57（不自动等于一个完整实际工作日）
周次: W10
计划工时: 6小时50分钟有效工作
实际有效工时: 待用户选择是否记录
验收层级: Local最小技术实现
状态: 已完成（Day57确认范围）
---

# DentAll 每日复盘 D57：商品基础信息与原生品牌输出

## 相关笔记

- 前置笔记：[[Day56-商品图库与响应式图片]]
- 后续笔记：[[Day58-简单商品购买区与隔离购物车验证]]
- 当日WordPress实战学习笔记：[[WordPress实战笔记/Day57-WooCommerce商品摘要Hook与状态驱动样式]]
- 同主题笔记：[[Day52-品牌数据与筛选基线]]
- 相关决定：[[../DECISIONS#ADR-035：商品详情保留原生文字品牌并移除Storefront重复缩略图|ADR-035]]

## 结论

D57已按用户确认的推荐最小范围仅在Local完成。DentAll子主题升至0.32.0，继续复用WooCommerce 11.0.0和Storefront 4.6.2的经典单品模板、原生字段、价格/库存格式化及Variation表单。现有详情CSS只为标题、评分、Regular/Sale/Variation价格、短描述、库存和Meta建立统一视觉层级，并把Woo原生Sale标签绝对定位到Gallery左上角，关闭D55登记的摘要起点约低43px问题。

Storefront在商品摘要优先级4追加的品牌缩略图已从Hook中移除；WooCommerce在`product_meta`末尾输出的文字品牌链接和独立Product Schema品牌Filter均保留。没有修改商品、评价或品牌数据，没有新增字段、模板、JavaScript、插件、查询或购买逻辑。

## 功能确认与授权

用户于2026-09-07明确确认：

> 确认按推荐最小范围仅在 Local 实施 Day57：复用 WooCommerce 原生商品信息，只修改现有详情样式，移除 Storefront 重复品牌缩略图并保留 product_meta 文字品牌链接，更新主题版本；不修改商品或评价数据，不新增字段、模板、JS、插件或购买逻辑；按上述状态矩阵、专项复核和收尾文档完成，超出范围先停下确认。

授权覆盖既有详情CSS、一个Storefront展示Hook移除、主题资源版本和必要的职责注释；不覆盖数据夹具、评价/品牌录入、模板覆盖、购买区、D59平板顶层堆叠、D61 Variation媒体/价格优化或非Local部署。

## 今日三个验收结果

- [x] #44 Simple Sale与#46 Variable初始状态继续使用Woo原生语义和数据；标题、价格、摘要、库存及Meta在390/768/1024/1199/1200/1440px无横向溢出，Sale标签进入图库且不遮挡Summary。
- [x] 精确移除Storefront品牌缩略图回调，同时保留`WC_Brands::show_brand@10`的Meta文字品牌链和Product Schema Filter；Shop不加载详情CSS。
- [x] 完成静态、数据不变量、URL/SEO、Console/PHP日志增量、减法审查及代码/测试/视觉专项复核；最终阻塞级缺陷为0，证据缺口不外推。

## 今日事实与前置输入

- Local版本：WordPress 7.0.4、WooCommerce 11.0.0、Storefront 4.6.2；DentAll由0.31.0升至0.32.0。
- #44：Simple，Regular 29.99、Sale 24.99、SKU `TEST-D12-SIMPLE-001`、管理库存8、1个TEST分类、0评分、0品牌。
- #46：Variable，初始区间39.99～49.99、SKU `TEST-D12-VARIABLE-001`、2个全局属性Select、3个合法Variation、0评分、0品牌。
- Variation 51/52/53现有库存分别为5 / 0 / 3，对应`in stock` / `out of stock` / `in stock`；本日只读，没有保存。
- D55/D56已冻结经典单品DOM、1200px列宽和原生Gallery；768～1199px顶层双列仍由D59负责，不在D57改结构。

## 状态矩阵与证据

| 状态 | 原生事实/输出 | D57期望 | 实际证据 | 结论 |
|---|---|---|---|---|
| #44 Simple Sale | Woo输出`onsale`、`del`、`ins`和屏幕阅读器原/现价 | 保留语义，建立主次价格和图库角标 | 390～1440六宽可见；原/现价辅助文字仍在 | 通过 |
| #46 Variable初始 | Woo输出价格区间、2个Select和Variation form | 不把区间硬编码为单价，不影响表单 | 390～1440六宽，表单1个、Select 2个 | 通过 |
| Variation库存 | Woo按匹配结果插入`availability_html` | `.stock`只统一间距/字重，不改匹配和按钮 | CRUD只读：#51 `5 in stock`、#52 `Out of stock`、#53 `3 in stock`；D56已有#51选择链证据 | 通过（数据/合同）；D57未新增动态逻辑 |
| 0评分 | `rating.php`在`rating_count=0`时不输出容器 | 不用CSS造星级或“0 review” | #44/#46 DOM评分容器均为0 | 通过 |
| 有评分合同 | 原生`.star-rating`＋评价链接 | Flex可换行且不受Storefront clearfix伪元素干扰 | 源码/CSS专项复核；当前无正向数据，未做实页视觉 | 合同通过；真实样本待后续内容验收 |
| 短描述存在/为空 | `short-description.php`为空时提前返回 | 存在时长文本可换行；为空时不留假内容 | #44/#46现有短描述六宽无溢出；空值由当前模板源码确认 | 通过（空值为源码合同） |
| SKU/分类/标签 | `meta.php`按原生条件输出 | 保留链接、换行和信息层级 | #44/#46 SKU及分类唯一，长内容可断行 | 通过 |
| 0品牌 | 没有`product_brand`关系 | 不输出缩略图或文字行 | 两商品品牌关系0、品牌缩略图DOM 0 | 通过 |
| 有品牌合同 | Storefront缩略图＋Woo Meta文字曾为双链 | 只删缩略图，保留文字链接和Schema | 运行Hook：缩略图回调`false`；Meta仍有`WC_Brands::show_brand@10`；Schema Filter源码独立 | 合同通过；真实品牌视觉待正式样本 |
| Sale角标/摘要顶线 | D56前Sale在普通流推低Summary约43px | 角标进入Gallery，Summary与Gallery同顶线 | 768～1440双列的Gallery/Summary顶线相同；390无遮挡 | 通过，关闭D55 P3 |
| Product/Shop隔离 | 详情CSS只应在`is_product()`加载 | Shop不受详情规则污染 | Product加载1份`?ver=0.32.0`；Shop为0，商品卡2个 | 通过 |

真实评分和品牌属于业务内容“血肉”。本日禁止修改商品或评价数据，因此没有为了截图创建假关系；这不阻塞通用输出骨架，但正向内容视觉不能写成已实页验收。

## 实施内容

### 1. 商品信息视觉

在既有`assets/css/product-detail.css`中增加16个职责明确的规则块：

- Sale：使用商品根容器现有定位上下文，在左上12px放置原生角标。
- 标题与主价格：32px Token层级；促销原价降为18px弱化，现价保留主层级。
- 评分：原生星级与评价链接用可换行Flex排列，并定向关闭父主题clearfix伪元素。
- 短描述、库存与Meta：统一24/16px节奏、长文本断行、Meta边界和原生行间距。
- 所有文案、金额、币种、库存与状态仍由WooCommerce生成；CSS没有使用`content`伪造业务事实。

### 2. 品牌输出链

`dentall_remove_storefront_product_brand_thumbnail()`在`after_setup_theme`优先级40执行，精确移除：

```text
woocommerce_single_product_summary
└─ storefront_woocommerce_brands_single @ 4
```

以下WooCommerce链保持不变：

```text
product_meta.php
└─ woocommerce_product_meta_end
   └─ WC_Brands::show_brand @ 10
```

Product Schema由`woocommerce_structured_data_product`上的独立Brands Filter维护，也未被移除。

### 3. 版本与缓存键

子主题Header版本由0.31.0更新为0.32.0。既有详情CSS继续通过`is_product()`条件加载，没有增加资源请求；版本变化会让浏览器把现有子主题静态资源视为新缓存键。

## 修改文件

| 文件 | 变更 | 保留理由 |
|---|---|---|
| `app/public/wp-content/themes/dentall/assets/css/product-detail.css` | D57信息层级与Sale位置 | 复用D55/D56同一Product专用资源，不新增请求 |
| `app/public/wp-content/themes/dentall/inc/storefront-hooks.php` | 精确移除Storefront品牌缩略图 | 父主题Hook适配属于既有模块职责 |
| `app/public/wp-content/themes/dentall/inc/setup.php` | 更新详情资源职责注释 | 避免仍写“摘要字段由后续Day负责”的失真说明；无运行变化 |
| `app/public/wp-content/themes/dentall/style.css` | 版本0.32.0 | 刷新既有资源缓存键 |

## 高内聚与最小方案判断

- 不新增CSS文件：D57与D55/D56同属商品详情视觉、加载生命周期完全一致，拆文件只会增加请求和重复依赖。
- 不覆盖模板：原生模板已经提供所需语义和条件状态，Action与CSS足够完成需求。
- 不新增JavaScript：标题、价格、库存、Meta和Sale都不需要新交互；Variation继续由Woo原生脚本处理。
- 不新增字段/插件：所有事实已有Woo原生字段和`product_brand` taxonomy。
- Hook函数放在`storefront-hooks.php`：它只处理父主题展示回调，不属于`dentall-core`跨主题业务规则。

## 减法审查

首版实现后共删除6条无收益声明：标题重复继承的`font-weight`/`line-height`、短描述重复继承的`color`，以及纯文本库存节点无第二Flex item可用的`display`/`align-items`/`gap`。同时：

- 将泛化函数名改成`dentall_remove_storefront_product_brand_thumbnail()`，直接表达职责。
- 把“空字段均由模板决定是否输出”改成更准确的“字段内容和输出规则由上游负责”，因为空价格仍可能保留空`.price`容器。
- 增加评分clearfix伪元素的局部`content:none`，这是修复真实父主题级联问题，不是预实现。
- 将促销价格间距从无法跨过Woo 11屏幕阅读器节点的`del + ins`改为局部`del ~ ins`，保留可访问文字且只命中Summary顶层价格。
- 更新`setup.php`已有注释，未改变加载条件或新增代码路径。

最终运行差异为4个既有文件、122行新增/2行删除、净增120物理行；新增0个运行文件、1个函数、1个Action和16个CSS规则块，没有模板、JS、插件、字段、查询或依赖。

## 七个专注周期回放

| 周期 | 工作 | 结果 |
|---|---|---|
| C1 | 读取规则、D55/D56证据、Woo/Storefront源码与Hook | 冻结事实源、输出责任和排除项 |
| C2 | 建立标题、评分、价格、摘要、库存、Meta与Sale最小CSS | 只复用原生DOM和Token |
| C3 | 移除重复品牌缩略图、保留Meta链、更新版本 | Hook运行审计通过 |
| C4 | #44/#46手机与平板竖屏检查 | 390/768无溢出、语义与表单保持 |
| C5 | 1024/1199/1200/1440断点与长文本检查 | 顶线、列宽和断点连续性通过 |
| C6 | 数据、SEO、资源、Console、PHP与静态检查 | 不变量和证据边界记录 |
| C7 | 三路专项复核、减法审查、文档与学习收尾 | 阻塞缺陷关闭，状态同步 |

计划工时只是节奏模板，本日未记录实际有效工时，也没有为了凑满周期延迟验收。

## 验证证据

### 浏览器六宽矩阵

| 宽度 | #44 Simple | #46 Variable | 关键结论 |
|---:|---|---|---|
| 390 | Gallery约335px；Summary约335px并在下方 | Gallery约335px；2个Select保留 | 页面无横溢出，长标题两行，Sale不遮挡 |
| 768 | Gallery约270px；Summary约389px | 同列宽合同 | 仍为D55双列，字段无溢出 |
| 1024 | Gallery约370px；Summary约534px | 同列宽合同 | 字段与表单无溢出 |
| 1199 | Gallery约438px；Summary约633px | 同列宽合同 | 断点前稳定 |
| 1200 | Gallery约634px；Summary约439px | 同列宽合同 | D55 PC列宽在边界准时切换 |
| 1440 | Gallery约710px；Summary约492px | 同列宽合同 | 标题、区间价格、Meta稳定 |

六个宽度的页面`scrollWidth`均等于`clientWidth`。#44 Sale标签始终位于Gallery内且不与Summary相交；768～1440双列时Gallery/Summary顶线相同。D59仍负责是否让768～1199顶层堆叠，D57未偷改结构。

证据口径：六宽结论来自主流程与独立测试的DOM/几何循环，不等于每个宽度都有一张独立截图。最终`del ~ ins`版本补拍了#44 390px并确认约8px间距；1440截图早于该单条选择器修正，其他宽度以同一无断点规则的静态合同和此前几何证据复核。若后续要求完整视觉档案，应在D59/D60整页回归时补齐终态截图，不能把本日记录写成“六档独立截图已完成”。

### 数据、Hook与语义

- #44只读结果：Simple、SKU、Regular/Sale、当前价、库存8、分类、0评分、0品牌全部与基线一致。
- #46只读结果：Variable、SKU、当前最低价39.99、3个子Variation、分类、0评分、0品牌全部与基线一致。
- 51/52/53只读结果保留原属性、价格和5/0/3库存；临时审计文件已删除。
- Hook运行结果：`storefront_woocommerce_brands_single@4`为`false`；Meta回调仍为`WC_Brands::show_brand@10`。
- #44保留Woo促销的`del`、`ins`及“Original price / Current price”屏幕阅读器文本；H1唯一。
- Product Canonical、Title、robots和JSON-LD仍由原链输出；D57没有SEO Filter或模板变更。

### 静态、资源与日志

- 相关PHP文件lint通过；`git diff --check`通过，仅有工作区既有LF→CRLF提示。
- `product-detail.css`最终221行、7170字节、32/32对花括号、0个`!important`。
- Product只加载一份`product-detail.css?ver=0.32.0`；Shop详情CSS为0。
- 已验证页面Console error/warning为0；浏览器控制层曾出现Statsig网络超时，不是站点页面Console。
- Day57若干探索性只读WP-CLI命令因Windows引号、受保护方法或错误数据库端口向历史`debug.log`追加工具Fatal/连接警告；它们未改数据。改用文件式只读审计和正确Local配置后成功，最终有效检查未新增站点运行错误。历史日志未清空，因此不能写“全局日志干净”。

## 专项复核

- Code/范围终审：发现并关闭评分clearfix伪元素的潜在Flex间隙、库存无收益Flex预实现、泛化函数名和两处失真/过宽注释；最终P0/P1/P2/P3均为0。
- 独立测试：基于最终0.32.0检查Simple/Variable、六宽、资源隔离、数据不变量、静态与日志边界；不把无数据正向状态冒充实页证据。
- 设计/视觉复核：最终版390px及修正前1440px确认信息层级、Sale顶线、长文本和D59边界，结合六宽DOM几何与无断点CSS合同终审P0～P3=0；没有把该证据表述为六档独立截图。B级参考中的评分/品牌正向内容继续受真实样本限制。
- 安全专项未触发：本次没有输入、权限、nonce、持久化、订单、价格计算、库存写入、支付或外部集成代码；范围/Code Review已核对数据和交易不变量。

## 使用Chrome DevTools安全微调

1. 在Elements定位`.summary .product_title`、`.summary > .price`、`.stock`或`.product_meta`，查看Styles和Computed来源。
2. 临时改Token或局部声明，判断应回到全局Design Token、商品详情共用规则还是单一状态规则。
3. 切换390、768、1024、1440，并额外检查1199/1200；观察标题换行、价格语义节点、Sale与Gallery/Summary矩形。
4. 检查`::before/::after`，避免父主题clearfix在Flex/Grid中成为不可见布局项目。
5. 回到子主题源码修改并刷新；DevTools临时值不能代替源码，禁止改WooCommerce或Storefront核心CSS。

## 数据、URL与系统影响

| 领域 | D57结论 |
|---|---|
| 数据 | 只读；未修改商品、Variation、价格、库存、评价、品牌、分类或媒体关系 |
| URL | Product/Shop/品牌Slug、固定链接、重定向和查询参数不变 |
| SEO | Title、Meta、Canonical、robots、Schema、Sitemap和Breadcrumb不变；品牌Schema Filter保留 |
| 性能 | 无新请求、查询、远程调用、Cron、JS或插件；CSS由3916增至7170字节，需在后续整页基线中继续观察 |
| 缓存 | 主题Version升至0.32.0会刷新现有子主题静态资源缓存键；未清页面缓存/CDN |
| 支付/物流/订单 | 无影响；未改Variation匹配、加购、扣库存、结账、支付、税费、物流或邮件 |
| 部署 | 仅Local工作树；未提交、未推送、未部署Staging/Production |

## 明确未做

- 未创建、编辑或删除商品、Variation、评价、品牌term或关系。
- 未新增字段、模板覆盖、JavaScript、插件、依赖、查询或缓存层。
- 未实现Buy Now、Wishlist、购买区重构或交易逻辑。
- 未改变768～1199顶层双列；该结构仍由D59统一处理。
- 未处理D61的Variation动态图`sizes`或价格/媒体联动优化。
- 未生成图片，未部署或清理非Local缓存。

## 风险与后续衔接

- 当前两件恢复态商品均无评分和品牌，正向星级、评价链接、Meta品牌文字及超长品牌名仍需在业务提供真实样本或另行授权可逆夹具后完成实页内容验收；骨架和Hook合同可继续。
- 768～1199保持Storefront双列，与B级平板竖屏参考可能不同；D59需结合购买区、Tabs与遮挡统一判断，不应在D57局部抢改。
- 升级WooCommerce、Storefront或改用区块单品模板后，必须重新核对Summary回调、Brands集成、模板条件、伪元素和选择器。
- 0.32.0尚未部署非Local；Production缓存、真实辅助技术、RTL、Core Web Vitals与正式内容均不在本次通过口径。

## 今日复盘

- 完成：商品基础信息视觉、Sale顶线、品牌双链收敛、六宽/状态/数据/SEO/资源验证、减法与三路复核。
- 未完成及原因：正向评分/品牌实页缺真实数据且本日明确禁止修改；购买区、顶层平板结构和Variation媒体/价格联动由后续Day负责。
- 实际工时与计划偏差：未记录；没有用计划工时代填。
- 今天学到的内容：先把商品事实、模板条件、Hook输出和CSS视觉分开，才能安全删除重复展示而不误删数据、Schema或动态购买状态。

## WordPress实战学习笔记收尾

- [x] 已生成[[WordPress实战笔记/Day57-WooCommerce商品摘要Hook与状态驱动样式]]并登记[[WordPress实战笔记/WordPress实战笔记索引|WordPress实战笔记索引]]。
- [x] 已讲清Summary优先级、模板空状态、品牌双链、clearfix伪元素、Variation库存和证据边界。
- [x] 已与Day56项目/学习笔记建立前后双向链接，未记录密码、Cookie、密钥或真实客户数据。
- 延期原因与补写节点：无。

## D58启动点

1. 先只读核对Simple/Variable购买区的数量、Variation选择、按钮、不可购买/售罄、错误和加载状态，以及Woo原生模板/脚本责任。
2. 提交D58最多3项验收结果与最小功能确认单；不得把D57信息视觉授权扩成购买逻辑实现。
3. 保留D59顶层移动/平板结构和D61 Variation媒体/价格优化边界，正式商品事实仍由业务录入审核。

## 可复用核心思想

### 跨平台不变量

商品数据、语义输出和视觉呈现是不同层。删除重复展示必须定位具体输出链，不能通过删除事实或隐藏整个信息区完成；状态样式必须消费平台真实状态，不能制造业务文案。

### WordPress/WooCommerce当前实现

DentAll在当前WooCommerce/Storefront版本复用经典单品模板与Summary Action，通过子主题`after_setup_theme`精确移除Storefront品牌缩略图，并以Product条件CSS调整原生标题、评分、价格、摘要、库存和Meta。空评分/摘要、空价格和Variation库存各有不同模板或动态合同，必须分别验证。

### Shopify或其他平台的对应机制

可迁移的是“平台商品模型为事实源、主题组件负责语义输出、最小扩展点删除重复展示、状态矩阵验证”的方法。Shopify Product/Variant、Liquid/JSON模板、Section、Metafield和结构化数据的具体映射在DentAll未验证，均标记待验证，也不进入本项目第一版实施范围。
