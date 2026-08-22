---
项目: DentAll WooCommerce
日期: 2026-08-18
工作日: D18
周次: W3
计划工时: 6小时50分钟有效工作
实际工时: 待用户记录
状态: 已完成；C1～C7完成，M2商品模型候选冻结通过
---

# DentAll 每日复盘 D18

## 相关笔记

- 前置笔记：[[Day17-代表商品录入与SEO验收]]
- 真实性复盘：[[Day18-真实性复盘]]
- 后续笔记：[[Day19-博客分类与作者规则]]
- D25综合验收：[[Day25-综合验收与批量录入开放]]
- 周总结：[[W3-商品样本与模型候选冻结周总结]]
- 规则来源：Day13简单商品、Day14可变商品、Day15库存物流和Day16商品SEO的已验收结论

## 今日三个验收结果

- [x] 形成商品模型候选冻结结论：Simple/Variable、父子SKU、合法组合、价格、库存真相源、物流继承/覆盖、图片和SEO职责无冲突。
- [x] 使用Website Manager复测代表商品的保存持久化、权限边界及WooCommerce原生CSV可导出性，关闭已执行范围的P0/P1。
- [x] 明确TEST对象去留和未验收清单，同步项目状态与M2结论；D25前继续禁止批量录入。

## C1：冻结结构与业务值边界矩阵

> 本矩阵是C1分析结果和D18候选冻结输入；C2～C6已完成验证，C7按证据收口后形成M2商品模型候选冻结结论。

| 层级 | 可进入D18候选冻结的结构/职责 | 本次不冻结的业务值或实现 | 证据与后续动作 |
|---|---|---|---|
| 商品底层 | 继续使用WooCommerce原生Product；Simple表示单一可售单位，Variable＋Variations表示有限、合法且互斥的购买组合 | 逐商品正式类型、定制展示字段、Inquiry流程 | D12～D17已验证Simple/Variable；Display Only尚未实现，Inquiry当前不实施 |
| 发布闸门 | 标准可购买商品发布前具备英文Slug、来源明确且全站唯一的SKU、正数Regular price、明确库存状态、至少一个分类、长短描述、主图及主图Alt | 正式名称、分类归属、品牌字段载体、商品文案和素材授权 | D13验证草稿可容纳缺失资料；缺失正式事实时保持草稿，不编造数据 |
| SKU | Simple使用独立SKU；Variable父商品保留系列级SKU，每个可售Variation拥有独立唯一SKU；SKU不承担分类或SEO职责 | 正式制造商货号、DentAll编号实例、Variation后缀映射 | D13、D14和D17的TEST SKU只验证父子职责与唯一性 |
| 全局属性 | 同义且跨商品复用、需要筛选或形成购买选择时使用Global Attribute；属性展示、筛选和Variation是三个独立决定；`Size`与`Shade`名称、Slug及语义可进入候选冻结 | `TEST Size A/B`、`TEST Shade A/B`及正式属性项；Material、Color、Package Quantity、Compatible System/Device、Intended Use仍未全部用代表样本验证 | D14与D17只实际验证Size、Shade；不能把D9整份候选清单误记为全部冻结 |
| 合法组合与默认值 | 先计算理论组合，只创建业务确认的合法组合；默认Variation只能指向合法、启用、有价格且可购买的组合，没有真实默认时保持为空 | 正式商品合法组合、默认组合和逐Variation图片 | D14与D17均以3个合法组合＋1个非法组合验证机制；TEST矩阵不代表真实锆块组合 |
| 价格 | Standard Simple及每个可售Variation使用业务确认的正数Regular price；Variable父级价格由Variations派生；Sale price使用原生字段且低于Regular price | 正式USD金额、促销日期、免费样品和Display Only参考范围 | D13验证促销输入，D14/D17验证同价、异价和父级价格区间；真实价格尚未验收 |
| 库存 | 同一可售对象只有一个数量真相源；Variable默认逐Variation管理，只有真实共享物理库存池时才使用父级库存；父子不得同时管理数量；Backorders默认关闭 | 正式数量、共享库存选择、盘点频率、补货承诺及订单扣减/回补 | D14已修复父子重复库存P1；D15和D17复验机制，订单阶段仍未验证 |
| 物流 | 产品规格、单个销售包装Shipping数据和最终订单外箱分层；Simple维护自身物流；Variable真实一致时继承父值，不同时由Variation覆盖 | Production重量/尺寸单位、正式数值、承运商、Shipping Class、体积重、装箱与运费 | D15与D17只证明继承/覆盖；当前`lbs/in`及所有数值仍是环境或TEST事实 |
| 图片与媒体 | 主图、图库、缺图和Alt具有独立职责；商品图采用1:1内容基线，文件命名、压缩、授权和Alt按媒体规范治理 | 正式图片、最终主题派生尺寸、逐Variation正式图片、PDF公开授权 | D11、D13和D17已有机制证据；C3再复核后台帮助与执行口径 |
| SEO与URL | 商品名称/H1、SEO Title、Meta Description、Slug、Canonical和robots职责分离；商品路径为`/product/{slug}/`，`/shop/`为归档；Canonical不能代替301 | 正式SEO文案、Production Canonical/索引/301/Sitemap/缓存输出 | D16与D17只验证Local/Staging和受保护环境边界；Production另验 |
| 缺货与停售 | 临时缺货保持稳定父商品URL并阻止对应Simple/Variation购买；永久停售必须按严格替代301、保留200或真实404/必要410分流 | 真实永久停售事实、替代关系、停售文案和`Discontinued` Schema | D15/D16规则可进入候选；D17缺货Variation不能替代真实停售验收 |
| TEST数据 | TEST只用于Local或受保护、`noindex`且未启用真实支付的Staging；可作为登记过的回归夹具 | TEST名称、SKU、属性项、价格、库存、物流、图片和文案不得转成正式商品事实 | D25前决定继续保留、归档、删除或替换；C5登记对象级方案 |

## C1一致性检查结果

### 已对齐

- D13简单商品发布闸门与D14父子SKU、价格和库存职责兼容：草稿可保存缺失资料，但正式发布不能以空值或猜测值绕过业务确认。
- D14逐Variation库存、D15唯一库存真相源和D17 Staging Variable样本一致；父商品可保留系列SKU，但独立库存模式下不得管理数量。
- D15物流三层职责与D17父级继承/Variation覆盖一致；继承表示父值真实适用，不表示未知数据可以不确认。
- D16 SEO字段和URL职责与D17字段保存、单一Title/H1及Staging `noindex, nofollow`证据一致；没有把Staging结果外推为Production通过。
- 5个Staging样本的计数口径保持为“D17新建3个＋补全既有2个”，没有把#31和#39纳入代表矩阵。

### 后续周期必须收口的口径

1. 全局属性不能整体冻结：D14/D17实际验证的是`Size`和`Shade`。其名称、Slug和语义进入候选冻结，TEST属性项和其余D9属性继续保持候选。
2. 品牌需要区分业务结论与技术载体：标准商品发布前应有明确品牌结论，但品牌字段、归档URL、筛选和索引方案仍按D52冻结，本日不创建品牌CPT、标签替代或插件。
3. `DATA_DICTIONARY.md`中“TEST物流值在D17～D18前替换”的旧表述容易被理解为必须填值。C7应改为：可信正式数据不足时保留TEST夹具或让正式商品保持草稿，不得为满足节点编造物流数值；D25前决定TEST对象去留。
4. 可下载资料、真实促销和永久停售未形成可信样本。它们属于真实内容验收缺口，不阻塞Simple/Variable通用骨架，也不能在M2中标记为已验证。

## C2：Variable父子职责复核

### Local当前数据审计

| 检查对象 | 实际结果 | C2结论 |
|---|---|---|
| 父商品#46 | 类型为Variable；父SKU为`TEST-D12-VARIABLE-001`；不管理数量库存；默认属性为空 | 父商品可以承载系列身份和选择器，但不与独立Variation争夺数量库存真相源 |
| 全局属性 | `Size`和`Shade`均可见且用于Variation | 属性展示、Variation用途与C1候选职责一致；正式属性项仍未冻结 |
| 合法组合 | 2×2理论组合中只存在#51、#52、#53三个合法Variations；Large/Medium未建立 | 理论笛卡尔积不自动等于可售集合；不存在的组合不能靠虚假价格或零库存伪装 |
| 父级价格 | 最低39.99、最高49.99，由可售Variations派生 | Variable父商品不保存第二套成交价；TEST金额不进入正式冻结 |
| #51 Small/Light | 唯一TEST SKU；Regular/current price均39.99；库存5、`instock`、禁止Backorders | 有货、同价和逐Variation库存职责通过 |
| #52 Small/Medium | 唯一TEST SKU；Regular/current price均39.99；库存0、`outofstock`、禁止Backorders | 缺货Variation继续存在于合法组合，但不能购买；它不等于永久停售 |
| #53 Large/Light | 唯一TEST SKU；Regular/current price均49.99；库存3、`instock`、禁止Backorders | 异价与逐Variation库存职责通过 |
| 物流继承 | #51/#52原始Shipping字段为空，有效读取父级2 lb、8×8×3 in | 空值只有在父值真实适用时表示继承；TEST数值不代表Production标准 |
| 物流覆盖 | #53原始及有效Shipping值为2.5 lb、9×9×4 in | 真实包装不同的Variation应明确覆盖；不另建重复物流字段 |

- 既有`day12-c5-variable-audit.php`重跑17/17通过，覆盖父级库存、价格区间、Size/Shade、三个合法组合、非法组合缺失、逐Variation价格库存、Backorders、物流继承/覆盖和图片。
- 新增只读`day18-c2-variable-audit.php`并通过8/8，补齐父SKU、父级不管理库存、默认属性为空、三个子项、子ID与SKU唯一及共同父商品检查。
- 两个脚本均只使用WooCommerce CRUD/API读取既有TEST夹具，没有调用`save()`、库存写入或数据库更新。
- Local匿名商品URL返回200和正确SEO Title，但WooCommerce Site Visibility当前输出Coming soon页面，未展示商品选择器。本周期不改变站点可见性，也不把该匿名响应记为Variable前台复测；D14登录态选择器、缺货、非法组合与购物车证据继续有效，C6再做独立复核。
- D17 Staging #47的已登记证据与Local结果同构：父级不管理数量，三个Variation分别为库存5/0/3、价格39.99/39.99/49.99，B/B未建立且一个Variation覆盖物流。本周期没有登录或修改Staging，因此该部分属于跨环境证据对照，不是新的Staging运行证据。

### C2结论边界

- 父子SKU、合法组合、父级派生价格、逐Variation库存、默认值为空及物流继承/覆盖之间未发现冲突，可继续作为D18候选冻结输入。
- C2冻结的是职责和互斥约束，不冻结`Small/Light`、`A/B`、39.99、49.99、库存5/0/3或`lbs/in`等TEST值。
- C2未创建订单、未验证库存扣减/回补、正式运费、结账、支付、缓存下库存刷新或Production；这些不能从CRUD持久化审计推导为通过。

## C3：Simple发布字段、图片与SEO后台帮助复核

### 身份与环境边界

- 执行环境为Local，后台身份明确显示`DentAll D12 Manager`，即项目的`DentAll Website Manager`独立测试账号；本周期不使用Administrator作为验收证据，也不测试低权限`DentAll Content Editor`。
- Website Manager可见文章、媒体、页面、评论、WooCommerce、商品、支付、分析、营销、个人资料及受限Yoast入口；WordPress用户、插件、主题、工具和系统设置入口未出现，业务角色与Administrator边界仍成立。
- 代表对象为已发布Simple商品#44。用户切换账号后页面短暂显示旧Administrator编辑锁；等待锁释放并刷新后继续，只展开帮助或媒体详情，不接管、不更新、不保存商品。
- 本周期只复核Local后台；Staging等待C6同步后进行关键路径复验，不在C3～C5重复全量操作。

### 字段与帮助结果

| 范围 | Local Website Manager实际结果 | 缺口与处理建议 |
|---|---|---|
| 名称、Slug、长短描述、分类和品牌 | 字段与入口均可见；长描述和简短描述已有原生用途提示；发布框明确显示状态、可见性和目录可见性 | 原生帮助未形成DentAll发布闸门，正式SKU、价格、分类、主图和Alt等完整检查继续由操作手册约束，不新增重复字段 |
| 价格与促销 | Regular price、Sale price和促销日期可见；促销日期已有起止时间提示 | 没有提醒“正式价必须业务确认”“Sale price低于Regular price”；真实促销样本尚未验收，C7补入手册而非新增代码 |
| SKU与库存 | SKU、GTIN、数量、Backorders、低库存阈值和库存状态均可见；WooCommerce提供通用SKU与库存说明 | 通用帮助没有说明DentAll发布前SKU必填、父子SKU职责、唯一库存真相源及默认关闭Backorders；列为P2培训/手册缺口，C4继续验证保存边界 |
| 物流 | 重量、长宽高和Shipping Class可见，单位回显为`lb/in`；原生提示只解释十进制重量和长宽高 | 未说明字段代表“单个销售包装”，也未区分产品规格、销售包装和订单外箱，更未解释Variable继承/覆盖；列为P2手册缺口，正式单位与数值仍不冻结 |
| 主图、图库与Alt | 商品图片面板明确提示1000×1000或更大、JPEG/PNG和5MB；媒体详情显示文件名、17KB、1254×1254像素，并提供Alt“描述用途，纯装饰可留空”的准确说明 | 当前允许的WebP未出现在WooCommerce面板提示中，列为P3文案缺口；现有D11媒体规范已覆盖WebP、命名、授权和压缩，不为此新增字段或插件 |
| 日常SEO | Yoast Focus keyphrase、SEO Title、Slug、Meta Description、预览、可读性及社交字段已加载，Website Manager可见内容级SEO入口 | 页面级Title/Meta职责可继续由Website Manager承担；顶部WordPress“帮助”仅提供WooCommerce通用支持链接，不能替代DentAll字段操作手册 |
| Yoast高级元数据 | 实际页面没有Advanced、Canonical、robots/noindex和nofollow字段；CLI读取角色能力也未找到`wpseo_edit_advanced_metadata` | **开放P1**：代码白名单声明该能力，但数据库角色版本5未同步，和ADR-015/016及现有文档不一致；需在C4前后按授权修复角色版本同步，并在Local与Staging复验 |
| WordPress自定义字段 | Website Manager页面显示可编辑的“自定义字段”面板，可直接看到`total_sales`以及属性相关键 | **开放P2**：业务人员不需要直接维护WooCommerce原始元数据，误改会绕过正常商品字段语义；建议在商品页对Website Manager隐藏该面板，仍不替代服务端数据/API边界 |

### C3结论

- Simple发布所需原生字段、5MB图片限制、主图/图库入口和Alt帮助均可用，没有发现需要新增ACF、CPT或第三方插件的结构性缺口。
- SKU、库存、物流和SEO职责的主要缺口属于项目口径与原生通用帮助之间的差异，应优先在C7更新编辑手册；不为每条说明预先开发自定义后台字段。
- 当前不能把“Website Manager拥有Yoast高级元数据权限”继续记为已通过：代码期望和Local角色实际能力不一致。该P1未关闭前，D18不能形成无开放P1的候选冻结结论。
- 自定义字段面板属于最小权限与误操作风险，不影响读取本次Simple数据，但应作为P2在权限修复时一起评估最小实现。

## C4：Website Manager权限、真实保存与发布边界复测

### 最小修复

- DentAll Core从`0.2.3`升至`0.2.4`，角色定义版本从`5`升至`6`。Local加载后按既有白名单重新同步`DentAll Website Manager`，使数据库角色获得代码已声明但此前未落库的`wpseo_edit_advanced_metadata`。
- 仅在商品编辑页对Website Manager隐藏WordPress原始“自定义字段”面板，避免业务人员直接误改`total_sales`等技术元数据。该措施是后台误操作防护，不是抵御恶意请求的服务端安全边界；商品数据仍应通过WooCommerce原生字段与CRUD/API维护。
- 没有新增字段、CPT、插件或后台设置页；低权限Content Editor的白名单定义未改变，并按用户确认不纳入C3～C5账号验收。

### Local验证结果

| 范围 | 结果 | 边界 |
|---|---|---|
| 角色白名单 | 测试账号只有`dentall_website_manager`角色且无用户级额外能力；数据库角色能力与代码白名单完全一致 | 角色版本升级会重放两个DentAll角色的既有白名单，Content Editor定义未改，但本周期未测试该低权限账号 |
| 允许能力 | 可编辑已发布商品与临时草稿、可发布商品，并拥有`wpseo_edit_advanced_metadata` | 该Yoast能力同时开放Canonical、robots、advanced robots、Breadcrumbs Title等高级元数据，不是只开放两个输入框 |
| 禁止能力 | 用户、插件、主题和系统设置管理能力继续为否，后台对应菜单入口为0 | 未测试恶意构造请求；现有capability仍是服务端授权边界 |
| 商品技术元数据 | Website Manager的商品页不再显示原始“自定义字段”面板 | 只降低后台误操作概率，不改变数据库结构、REST/API或WooCommerce CRUD |
| Yoast高级界面 | Advanced、Canonical URL、robots/noindex、nofollow与Meta robots advanced均可见 | 本周期未修改任何SEO字段，也未改变现有Title、Canonical、robots或Schema输出 |
| 真实后台保存 | 在商品#44的“购物备注”写入C4测试标记并点击“更新”，WooCommerce数据层读到相同值，证明Website Manager的真实表单、capability、nonce与保存请求链路有效 | 浏览器尝试恢复空值时界面点击没有真正落库，测试没有把表面操作误记为成功；随后用WooCommerce CRUD清空并由数据层确认最终为空 |
| CRUD草稿/发布 | 临时Simple商品完成草稿保存、发布、返回草稿及永久清理，审计15/15通过 | CRUD验证持久化和状态转换，不替代上面的真实后台表单授权验证 |
| 回归 | Simple商品审计23/23、Variable商品审计17/17、D18父子补充审计8/8通过 | 商品#44内容恢复，保存测试会更新其修改时间；临时SKU与临时商品无残留 |

### 安全与流程结论

- C3的Yoast高级能力未同步P1已关闭；原始自定义字段面板P2已按最小实现关闭。后台字段帮助不足、WebP提示和真实业务内容等原有P2/P3不因本次权限修复而自动关闭。
- `wpseo_edit_advanced_metadata`是Yoast提供的一组高级元数据能力，无法只靠该原生capability细分为Canonical与robots。继续沿用ADR-015/016的流程控制：高影响SEO修改记录旧值、新值、原因、受影响URL、复核人并做页面回归；若未来需要技术上逐字段隔离，应另立范围评估。
- 审计脚本增加`WP_ENVIRONMENT_TYPE=local`且站点主机为`dentall.local`的运行时双重保护，防止误在Staging或Production创建、发布临时商品；清理同时按对象ID、实例ID和SKU兜底，并把清理异常计为失败。
- C4仅修改Local代码和Local角色数据。Staging仍为DentAll Core `0.2.3`、角色版本`5`，等待C6部署同版本代码后复测Website Manager关键路径；Production未触及。

## C5：WooCommerce原生CSV导出与TEST对象去留候选

### Website Manager商品导出权限修复

- 初次直接访问WooCommerce商品导出页时返回“不能访问此页面”。本地WooCommerce 11.0.0源码确认导出器同时要求`edit_products`和WordPress核心`export`；Website Manager已有商品编辑能力，但角色白名单没有全局导出能力。这是权限缺口，不是WooCommerce商品、支付、税费或物流配置未完成。
- DentAll Core升级至`0.2.5`。没有把`export`永久写入角色数据库，而是只在商品列表、`product_exporter`页面、WooCommerce商品导出AJAX与下载请求中临时满足该能力检查；WordPress原生`/wp-admin/export.php`和其他后台请求仍拒绝。
- WooCommerce继续负责`edit_products`、nonce、CSV生成和下载；本次不新增导出器、不修改WooCommerce核心，也不开放低权限Content Editor。
- Local能力边界审计7/7通过，C4角色与商品持久化回归15/15通过；真实后台商品列表显示一个“导出”入口，导出页可访问并完成下载，WordPress全站内容导出页实际仍返回拒绝。

### CSV结果核对

| 检查项 | 实际结果 | C5结论 |
|---|---|---|
| 文件 | `D:\Downloads\wc-product-export-18-8-2026-1787044345322.csv`，2590字节，SHA-256为`9679D3E4E063C4CF3B568E1F8D18E1254039CD8F09AC1C788D6DFCBAF0DBFC86` | 只作为Local测试证据，不提交到Git，不作为跨环境备份 |
| 行与对象 | 49列表头、5行数据；ID恰为#44、#46、#51、#52、#53，ID和SKU均唯一 | Simple父商品、Variable父商品和3个Variations均被原生导出器覆盖 |
| Simple #44 | SKU、Regular/Sale price、库存8、禁止Backorders、1.2 lb、6×6×2 in、分类、图片和Package Quantity均与CRUD审计一致 | Simple核心字段导出通过 |
| Variable #46 | 系列SKU、父级无数量库存、父级价格留空、Size/Shade、2 lb与8×8×3 in均正确 | 父级价格由Variations派生，导出结果没有制造第二套价格或数量 |
| Variations #51～#53 | 父级均以父SKU关联；价格39.99/39.99/49.99，库存5/0/3，有货状态1/0/1，Backorders均关闭；#51/#52物流留空、#53输出2.5 lb和9×9×4 in | 父子关系、独立库存、缺货、继承空值和物流覆盖均保持原模型语义 |
| 图片 | 5行均输出对应Local绝对图片URL | 证明媒体引用可导出，但`dentall.local` URL不能直接当作Staging/Production可恢复资产；uploads仍需独立备份/迁移 |
| SEO与自定义元数据 | 本次保持“导出自定义元数据”未选中；CSV没有Yoast或其他`Meta:`列 | 日常商品CSV只覆盖WooCommerce核心数据，不等于完整SEO备份；Yoast设置/元数据和数据库恢复路径另验 |
| 中文表头 | “交叉销售”出现两次，对应英文Upsells与Cross-sells；PowerShell标准`Import-Csv`因此拒绝解析，按列位置解析后5行数据正常 | **开放P2**：中文本地化CSV不适合作为未经处理的通用回导文件；D25前由开发者验证英文后台导出、表头规范化或隔离环境原生回导，Website Manager不得直接批量回导 |

### TEST对象去留候选

> C5只登记候选，不删除、改名、改Slug或把TEST对象转成正式内容。最终动作在D25备份、导出和恢复抽查后决定。

| 环境与对象 | 当前覆盖价值 | D25候选动作 |
|---|---|---|
| Local #44 | Simple、促销、库存、物流、图片、属性、发布和CSV回归 | 保留为Local自动回归夹具；不得同步到Production |
| Local #46、#51～#53 | Variable父子SKU、合法/非法组合、同价/异价、缺货、逐Variation库存、物流继承/覆盖和CSV父子关系 | 整组保留为Local自动回归夹具；不能拆开删除父级或单个Variation |
| Staging #32、#35 | 已发布Simple与SEO输出样本 | 保留至D25复验；开放正式录入前替换为可信内容或转草稿/回收站，禁止进入Production |
| Staging #45 | 单图Simple草稿和登录态预览样本 | 保留至D25；若正式样本已覆盖同一流程则回收，否则继续作为受保护草稿夹具 |
| Staging #47～#50 | Variable父子、缺货、非法组合、异价和物流覆盖样本 | 整组保留至D25关键回归；之后替换为可信样本或整组回收，禁止只删除部分子项 |
| Staging #52 | 主图＋3张图库与Alt样本 | 保留至D25媒体回归；正式授权素材覆盖后回收TEST对象与对应无引用TEST媒体 |
| Staging #31 | 空壳草稿，无代表矩阵覆盖价值 | D25前备份并确认无引用后永久删除候选，不创建Production重定向 |
| Staging #39 | 历史不完整草稿，存在`__trashed` Slug痕迹但未形成可信公开价值 | 先核对修订、引用和是否曾公开；无唯一内容或外链证据时回收/永久删除，有证据时另立URL处置记录 |

### C5边界

- C5证明Website Manager能导出Local WooCommerce核心商品数据，并发现中文表头的可移植性P2；它没有证明CSV可无损回导、Yoast元数据可恢复、uploads已备份或Local绝对图片URL可跨环境使用。
- Staging/Production未访问、未部署、未导出、未删除对象。C6同步`0.2.5`后只做Staging关键路径与独立综合复核；D25再抽查恢复路径。
- 商品CSV包含价格、库存、描述和素材URL，属于业务数据文件；正式环境导出应限制接收人、保存位置和保留期限，不通过Git、公开链接或聊天工具传播。

## C6：Staging同步与独立综合复核

### 部署与回滚边界

- C4～C5源码以`main`提交`b1faac2`进入Git；提交只包含`dentall-core.php`与`includes/product-governance.php`。代码专用部署提交`e9e21c4`相对Staging 0.2.3同样只修改这两个文件，分支根目录继续保持`wp-content/`。
- 用户在Cloudways完成`deploy/staging`的Fetch与Pull，Staging同步到DentAll Core 0.2.5。数据库、uploads、WordPress核心、WooCommerce、第三方插件、测试CSV和项目文档均未通过Git部署；Production未触及。
- 安全复核发现“回滚到0.2.3不会撤销已经写入角色数据库的Yoast高级能力”P2。`RUNBOOK.md`已明确：撤权必须从白名单移除能力并提升新的单调递增角色版本；紧急时临时从角色对象移除并立即验证，同一发布窗口仍需完成版本化修复。修订后安全Agent确认P0～P3均为0。

### Website Manager后台复测

- Staging以XuDan Website Manager账号完成复测，没有使用Administrator或低权限Content Editor。后台菜单未出现用户、插件、主题、Tools和WordPress系统设置入口。
- 商品#47的Yoast Advanced区域显示搜索引擎显示、链接跟随、高级Meta Robots、面包屑标题与Canonical字段；黄色全站`noindex`提示符合受保护Staging预期，不代表Production索引或Canonical已验收。
- 商品#47右上角“显示选项”没有Custom Fields，页面也没有原始自定义字段面板；本轮只读查看，没有修改或保存商品字段。
- 商品列表显示“导出”，WooCommerce原生商品导出页可访问并完成CSV下载；直接访问WordPress`/wp-admin/export.php`仍被拒绝，证明`export`能力只在商品导出请求范围内临时成立。

### Staging CSV复核

| 检查项 | 实际结果 | C6结论 |
|---|---|---|
| 文件 | `D:\Downloads\wc-product-export-18-8-2026-1787046468271.csv`，6312字节，SHA-256为`537CCCB2B9E2C4ADCB2FD06EF83806A390552AD5BB9FD6E9AB01C3C59A0F8D90` | 只作为Staging测试证据，不提交Git，不作为完整备份或回导包 |
| 行与对象 | 49列、10行；ID为#31、#32、#35、#39、#45、#47～#50和#52，ID唯一且非空SKU唯一 | 代表矩阵、Variable子项与两个待清理历史对象均被导出；没有因此改变其去留候选 |
| Variable #47～#50 | 父SKU关系正确；价格39.99/39.99/49.99、库存5/0/3、有货状态1/0/1、Backorders关闭；A/A、A/B、B/A存在，B/B缺失 | 父子SKU、独立库存、缺货、三个合法组合和非法组合边界与D17一致 |
| 物流 | 父级2 lb与8×8×3 in；#48/#49原始物流字段为空，#50输出2.5 lb与9×9×4 in | 继承空值和Variation覆盖保持原模型语义；TEST单位与数值不进入正式冻结 |
| 图片与元数据 | 代表商品图片均为Staging uploads绝对URL；未勾选自定义元数据，没有Yoast或其他`Meta:`列 | 核心CSV不等于SEO元数据、数据库或uploads备份 |
| 断言与已知问题 | 39/39项按列位置断言通过；“交叉销售”仍重复两次 | 已执行范围无新增缺陷；中文表头P2继续由开发者在D25前完成英文导出、规范化或隔离回导验证 |

### C6结论边界

- DentAll Core 0.2.5已在Local与受保护Staging完成权限、字段界面和核心商品CSV双环境验证；C6已执行范围P0=0、P1=0。
- 本周期没有执行CSV回导、商品保存、订单、库存扣减/回补、结账、支付、邮件、真实运费、缓存或Production。CSV回导与恢复路径仍属于D25门槛，不能因为导出成功而提前开放批量录入。
- 独立Code Review最终P0～P3均为0；安全Review的角色撤权回滚P2已通过运行手册修订关闭；独立测试仅保留C5已登记的中文CSV重复表头P2。

## C7：M2候选冻结与D19交接

### 文档收口

- `DATA_DICTIONARY.md`新增D18商品模型候选冻结结论，并修正“TEST物流值必须在D17～D18前替换”的旧口径：可信正式数据不足时保留已登记TEST夹具或让正式商品保持草稿，不得为了节点编造数值。
- `EDITOR_WORKFLOW.md`的D18检查项已完成；明确Local 5行与Staging 10行CSV均通过核心字段核对，M2不等于D25批量录入许可。
- `RISK_REGISTER.md`更新商品字段与TEST数据风险口径，并新增`RSK-016`中文CSV重复表头回导风险；负责人为开发者，D25前完成英文导出、表头规范化或隔离回导与恢复验证。
- `RUNBOOK.md`补充角色权限数据库持久化与撤权规则；普通代码降级不能代替角色撤权，临时角色对象撤权必须在同一发布窗口补上版本化白名单修复。

### M2商品模型候选冻结结论

- **M2候选冻结通过**：WooCommerce原生Simple/Variable、父子SKU职责、合法组合、父级派生价格、唯一库存真相源、Variation库存、Backorders、物流继承/覆盖、图片与SEO字段职责之间未发现结构冲突；已执行范围P0=0、P1=0。
- Website Manager在Local与Staging均可完成当前商品编辑职责：Yoast Advanced可见、商品原始Custom Fields隐藏、WooCommerce商品CSV可导出、WordPress全站内容导出仍拒绝。低权限Content Editor不属于本轮验收范围。
- M2冻结的是结构、职责和互斥约束，不冻结TEST名称、SKU、属性值、价格、库存、物流、文案、图片授权或正式合法组合。业务方在实际录入、审核和发布时填写逐商品事实；开发者只保证系统能动态、安全地承载。
- 中文CSV重复表头为开放P2，不阻塞只读导出或D19～D24内容骨架工作，但在D25批量录入与恢复门槛前必须关闭。CSV、Yoast Meta、数据库和uploads仍是不同备份面，不能互相替代。
- M2不开放正式商品批量录入。D25综合验收前，TEST对象继续按候选保留；#31和#39不在代表矩阵中，不在本日删除或改变状态。

### D19交接

- D19进入博客分类、标签和作者规则，只冻结文章信息架构与治理职责；具体文章标题、正文和业务主题不作为开工阻塞。
- 继续使用WordPress原生Post、Category与Tag；未证明需要独立内容类型前不新增CPT。先检查重复分类、Slug、作者归属、URL和SEO索引影响。
- Website Manager已有文章、分类、媒体和内容级SEO能力；D19不得为博客工作扩大用户、插件、主题、系统设置或全站导出权限。
- 商品模型、Product URL、支付、税费、物流、缓存、Production和正式数据在D19保持不变。若博客方案改变导航、URL、Schema、索引或跨内容关联，再按结构性影响登记。

## 专注周期记录

| 周期 | 计划 | 实际结果 | 证据/文件 | 用时 |
|---|---|---|---|---:|
| C1 | 对照5个样本与D13～D17规则，建立“冻结结构／不冻结业务值”矩阵 | 已完成；核心模型无冲突，识别全局属性、品牌载体、TEST物流期限和缺失样本四类待收口口径 | Day13～Day17笔记、`DATA_DICTIONARY.md`、`EDITOR_WORKFLOW.md`、`URL_SEO_MAP.md`、`PROJECT_STATE.md` | 待用户记录 |
| C2 | 复核Variable父子SKU、合法组合、价格、库存真相源和物流继承/覆盖 | 已完成；Local既有核心审计17/17、新增父子补充审计8/8通过；与D17 Staging证据一致，匿名前台受Coming soon边界影响未计为新证据 | `project-docs/tests/day12-c5-variable-audit.php`、`project-docs/tests/day18-c2-variable-audit.php`、Local #46/#51～#53、D14/D15/D17记录 | 待用户记录 |
| C3 | 复核Simple发布字段、图片规范、SEO职责和后台帮助缺口 | 已完成；Website Manager身份与系统边界通过，图片5MB和Alt帮助准确；发现Yoast高级能力未同步P1及原始自定义字段可编辑P2 | Local #44后台、角色能力清单、DentAll Core角色白名单 | 待用户记录 |
| C4 | 使用Website Manager复测权限、保存持久化和草稿/发布边界 | 已完成；修复角色版本同步与商品原始自定义字段面板，真实后台保存已由数据层确认；临时商品CRUD 15/15、Simple 23/23、Variable 17/17和D18补充8/8通过 | DentAll Core 0.2.4、`day18-c4-website-manager-audit.php`、Local #44与临时商品 | 待用户记录 |
| C5 | 使用WooCommerce原生CSV导出代表商品并登记TEST对象去留候选 | 已完成；修复Website Manager商品专用导出权限，Local原生CSV包含5个预期对象且核心字段一致；登记Local/Staging TEST对象去留候选，发现中文重复表头P2 | DentAll Core 0.2.5、`day18-c5-product-export-capability-audit.php`、Local CSV及D17对象记录 | 待用户记录 |
| C6 | 独立测试Agent复核价格、库存、非法组合、权限和导出证据 | 已完成；0.2.5部署Staging，XuDan确认Yoast Advanced可见、Custom Fields隐藏、商品导出可用且全站导出拒绝；Staging CSV 39/39断言通过 | `b1faac2`、`e9e21c4`、Staging #47、Staging CSV、`RUNBOOK.md` | 待用户记录 |
| C7 | 文档收口、M2候选冻结结论和D19交接 | 已完成；数据字典、编辑流程、风险、测试、版本和项目状态已收口，M2候选冻结通过并形成D19边界 | `DATA_DICTIONARY.md`、`EDITOR_WORKFLOW.md`、`RISK_REGISTER.md`、`TEST_PLAN.md`、`PROJECT_STATE.md`、本笔记 | 待用户记录 |

## 测试与验证

- 执行的命令：C1定向交叉核对项目文档；C2使用Local PHP 8.2.29与WP-CLI重跑`day12-c5-variable-audit.php`并执行新增`day18-c2-variable-audit.php`；C3使用WP-CLI只读列出角色能力；C4运行角色与商品审计；C5运行三个PHP语法检查、商品导出能力边界7项、C4回归15项、WooCommerce源码定向核对、Local CSV逐列解析、唯一性/父子字段断言及SHA-256计算；C6复跑同一组Local回归、核对部署分支差异，并对Staging CSV执行39项按列断言与SHA-256计算。
- 浏览器/设备：C1未操作浏览器；C2匿名响应被WooCommerce Coming soon页面覆盖；C3～C5使用Local Website Manager复核字段、真实保存和导出；C6使用Staging XuDan Website Manager确认高级SEO、Custom Fields隐藏、导出入口、CSV下载及`export.php`拒绝。Production未访问。
- 通过项：C1跨文档核心规则无结构冲突；C2 Variable核心17/17和父子补充8/8通过；C3原生字段、图片与菜单边界通过；C4角色/临时商品15/15；C5权限边界7/7、C4回归15/15、CSV 5行对象与核心字段映射及PHP语法检查通过；C6部署边界、Staging字段/权限及CSV 39/39断言通过。
- 已关闭项：C3的Yoast高级元数据能力未同步P1和原始自定义字段面板P2已在C4关闭；真实后台保存标记成功落库，最终已由WooCommerce CRUD恢复为空。
- 未通过项补充：首次一次性WP-CLI `eval`补充命令因Windows参数转义未执行，未触及商品；已改用可重复脚本并通过，不属于商品审计失败。
- 未验证项：CSV无损回导、Yoast元数据恢复、uploads恢复、C2新增登录态前台证据、Production与正式业务内容不在本周期范围；C6 Staging关键路径和独立综合复核已完成。

## Codex Agent 调度与审查

- 今日风险等级：C1为低风险文档与数据模型核对；C2涉及价格与库存事实但仅执行Local只读CRUD审计；C4～C6涉及角色权限、后台保存、业务数据导出与Staging部署，按高风险处理。
- 启动的Agent及职责：C1～C3由主Agent处理；C4～C6因权限、后台行为和Staging部署，启动Code Review、安全与独立测试三个专项Agent；C7为文档和Git收口，由主Agent处理。
- Review结果：C4首轮复核提出审计脚本缺少Local运行时限制P1、CRUD不能替代真实表单授权验证P2、临时商品清理异常兜底P3，修复后代码、安全与独立测试均为零缺陷。C5最终代码与安全复核P0/P1/P2/P3均为0；C6 Code Review无缺陷，安全Review发现普通代码回滚不能撤销角色数据库能力的P2，修订`RUNBOOK.md`后关闭并确认P0～P3均为0；测试Agent确认权限7/7、C4 15/15、Simple 23/23、Variable 17/17、D18 C2 8/8和CSV断言通过。仅保留已登记的WooCommerce中文重复表头P2，负责人为开发者，D25前完成英文导出、表头规范化或隔离回导验证。
- 剩余风险或未验证项：中文CSV重复表头、无损回导、SEO元数据与uploads恢复、正式业务内容、实际工作人员人员验收、Production SEO、订单库存和正式物流仍未验证。

## 决策与范围变化

- 今日决定：C1只形成候选矩阵；C2只读取Local TEST夹具并保存可重复审计脚本；C3～C6按用户确认仅验收最高业务权限`DentAll Website Manager`，不测试低权限Content Editor，也不把Administrator界面当作业务验收证据；C5允许商品CSV导出但不永久授予WordPress全局`export`；C6完成双环境证据后，C7宣布M2商品模型候选冻结通过。
- 新需求：用户明确C3～C5只测试Website Manager，不测试低权限账号；并授权在不影响开发的前提下提供所需权限。C5按安全边界把该授权落实为商品导出专用临时能力，没有扩大成全站永久`export`，不增加插件或计划工时。
- 预计增加工时：无。
- 是否已确认：用户已明确授权执行C1～C6，并在C6完成后授权直接进入C7。

## 问题与风险

- 阻塞：商品骨架无阻塞；真实业务内容尚不能验收。商品导出权限P1已在C5关闭；中文重复表头为不阻塞只读导出的P2，但D25开放批量录入前必须完成回导/恢复处置。
- 技术债：品牌字段载体与归档URL、Display Only字段、公开PDF和永久停售真实样本按原计划后续处理。
- 需要他人提供：正式SKU来源、逐商品合法组合、价格库存、包装物流、素材授权、永久停售及替代关系。

## 今日复盘

- 完成：C1冻结边界矩阵；C2 Variable父子职责审计；C3 Website Manager字段、图片与SEO帮助复核；C4权限、保存与元数据界面修复；C5商品专用导出权限、Local CSV字段核对及TEST对象去留候选登记；C6 Staging同步、权限/字段与CSV综合复核；C7文档收口、M2候选冻结和D19交接。
- 未完成及原因：中文CSV无损回导、Yoast元数据、数据库与uploads恢复抽查按D25门槛处理；两名实际工作人员无指导试录等待到岗；正式业务内容、Production SEO、订单库存、支付、税费和正式物流不属于D18完成范围。
- 实际工时与计划偏差：待用户记录。
- 今天学到的内容：候选冻结应锁定可复用的数据职责和约束，而不是把TEST夹具中的具体值升级为正式业务事实。

## 明日启动点

- 明日第一件事：启动D19博客分类、标签与作者规则的只读拆解，等待用户确认后再实施。
- 需要提前准备：盘点现有文章、分类、标签、作者和URL；区分文章信息架构骨架与逐篇业务内容，不操作Production或正式数据。

## 可复用核心思想

- 跨平台不变量：冻结应优先锁定“哪个对象负责什么”和“哪些状态不允许同时成立”，例如父级与变体只能有一个库存真相源；具体价格、库存和规格值仍由业务数据填充。
- 跨平台不变量：验证过一个属性模型不等于验证了全部属性。冻结范围必须与代表样本的真实覆盖一致，避免把候选清单误升级为系统承诺。
- WooCommerce/WordPress实现：Product、Variation、Global Attribute、Shipping字段和Yoast字段各自有明确职责；使用原生能力足够时不新增ACF、CPT或插件。
- WooCommerce/WordPress实现：Variable父商品可以保留系列级SKU并派生价格范围；逐Variation库存模式下父级不管理数量，子项分别承担SKU、价格、库存和实际不同的物流覆盖。
- SEO治理：Staging禁止索引是环境保护证据，不是Production Canonical、Sitemap和缓存验收。环境边界必须保留在结论中。
- 跨平台不变量：能导出一张商品表不等于具备完整备份与恢复能力。商品核心字段、插件元数据、媒体二进制、环境URL和数据库关系必须分别验证，恢复演练才是最终证据。
- 权限治理：业务角色需要某项动作时，优先把权限限定到具体对象、页面和请求，而不是因为“不会影响开发”就授予全站能力；开发便利不能替代数据最小暴露原则。
- WooCommerce/WordPress实现：原生商品CSV用父SKU表达Variation关系，以空物流字段表达继承；自定义Meta默认不导出。后台语言还可能让不同英文列获得相同翻译表头，批量回导前必须验证实际文件而不能只确认下载成功。
- Shopify或其他平台同样需要产品、变体、属性、库存和URL职责分离，但父子SKU、库存共享和停售机制的具体实现尚未在本项目实测，标记为待验证。
