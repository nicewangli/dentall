---
项目: DentAll WooCommerce
日期: 2026-08-17
工作日: D16
周次: W3
计划工时: 6小时50分钟有效工作
实际工时: 待用户记录
状态: 已完成
---

# DentAll 每日复盘 D16

## 相关笔记

- 前置笔记：[[Day15-库存与物流字段]]
- 权限基线：[[Day12-双环境角色与商品原型验收]]
- Simple流程：[[Day13-真实编辑试录与简单商品流程]]
- Variable流程：[[Day14-可变商品与Variation流程]]
- 后续笔记：[[Day17-代表商品录入与SEO验收]]
- 文章SEO后续：[[Day20-文章录入模板]]
- 周总结：[[W3-商品样本与模型候选冻结周总结]]

## 今日三个验收结果

- [x] 修复Local重复Title根因，将DentAll Core拆分为可维护模块，并把Yoast兼容逻辑部署到Staging；启停回退和代表页面Title唯一性通过。
- [x] 明确商品名称/H1、SEO Title、Meta Description、Slug、Canonical和robots职责，形成已发布URL变更、临时缺货和永久停售生命周期规则。
- [x] Local与Staging商品路径统一为`/product/{slug}/`；Staging安装并激活Yoast 28.2，五页矩阵通过受保护环境的Title与禁止索引边界，形成D17交接。

## 专注周期记录

| 周期 | 计划 | 实际结果 | 证据/文件 | 用时 |
|---|---|---|---|---:|
| C1 | 只读诊断Title与SEO基线 | 定位Yoast与WordPress区块主题兼容层同时输出Title；确认修复必须保留插件停用回退 | 页面源码、既有实现与配置 | 待用户记录 |
| C2 | 修复重复Title并改善插件维护性 | DentAll Core主入口由430行收敛为23行，功能拆分为模块；0.2.3仅在Yoast启用时移除重复核心Title输出，Local矩阵与Staging部署通过 | DentAll Core、Local五页矩阵、提交`0467e1a` | 待用户记录 |
| C3 | 明确SEO字段与权限职责 | 区分H1、Title、Description、Slug、Canonical和robots；Website Manager保留Yoast高级元数据能力，以流程和复核控制高风险变更 | 数据字典、编辑流程 | 待用户记录 |
| C4 | 明确Slug、301与Canonical流程 | 首发前可直接修正Slug；已发布永久迁移使用一跳301并让新页自身Canonical；Canonical不代替重定向 | URL/SEO映射、测试计划 | 待用户记录 |
| C5 | 定义缺货与停售生命周期 | 临时缺货保留200和稳定URL；永久停售按严格替代301、保留200或真实404/必要410分流 | URL/SEO映射、数据字典 | 待用户记录 |
| C6 | 核对双环境与Yoast边界 | 用户授权后在Staging安装并激活Yoast 28.2；Local与Staging统一`/product/`，Staging五页Title唯一且全站`noindex, nofollow` | Staging后台、前台矩阵、WooCommerce核心行为核对 | 待用户记录 |
| C7 | 跨文档收口与D17交接 | 修正阶段性旧结论，更新总档案、项目状态、测试计划、笔记索引和本笔记，并完成Git检查与提交 | 项目文档与Git记录 | 待用户记录 |

## 技术实现与验证

- DentAll Core先完成0.2.2纯结构重构，再以0.2.3增加独立SEO兼容模块。展示层未写入父主题或WooCommerce核心；网站级SEO兼容逻辑继续由按功能拆分的项目插件承载。
- 兼容模块只在Yoast启用时，于`wp_head`优先级0移除WordPress重复的`_block_template_render_title_tag`；Yoast停用后WordPress核心Title自动恢复，避免主题切换或插件停用时页面失去Title。
- Local首页、商店、Simple #44、Variable #46和真实404在Yoast启用、停用及恢复后均只有1个Title；Yoast启用时正常页面各1个Canonical，404无Canonical。
- DentAll Core 0.2.3已通过`deploy/staging`提交`0467e1a`部署，Staging确认无致命错误。独立Code Review与安全审查无P0～P3；测试Agent保留一项历史证据计数口径P3，不影响当前功能结论。
- Staging安装Yoast 28.2后，首页、商店、两个已发布Simple商品和真实404均只有1个Title并输出`noindex, nofollow`。因全站禁止索引，五页均无Canonical；这只能证明Staging环境边界，不能替代Production自身Canonical验收。

## 商品SEO与URL规则

- 商品名称是后台与H1的主要内容事实；SEO Title和Meta Description用于搜索结果表达，不应为追关键词而与页面内容失真。Slug是URL标识，Canonical和robots是技术控制字段，不能互相替代。
- 首次发布前且无外部使用的Slug可直接修正；已发布URL若必须永久迁移，登记旧新映射、使用一跳301、验证目标200并让新页Canonical指向自身。无等价替代时不得批量跳到首页、商店或不相关分类。
- Canonical只用于内容重复或多URL指向同一主版本的场景；它不是301替代品，也不能修复错误导航或内部链接。
- 临时缺货保持200、原URL、可索引与自身Canonical，以`OutOfStock`阻止购买；不因为库存归零删除商品、改Slug、301或noindex。
- 永久停售由业务方确认：严格一对一替代才301；旧页仍有说明、配件或支持价值时保留200停售页；无替代且无持续价值时返回真实404，只有明确撤销且有业务依据时评估410。
- WooCommerce 11.0.0没有原生`discontinued`状态。当前没有真实停售样本，因此不提前添加字段、Schema或自动重定向代码。

## 环境、权限与责任边界

- 开发者负责Title兼容、字段职责、URL机制、测试矩阵、部署与回滚边界；不替业务方编造正式商品名称、文案、替代关系或停售事实。
- Website Manager负责日常商品与Yoast字段录入，可访问高级元数据；已发布Slug、Canonical、索引和高级robots属于高影响操作，必须按记录、复核和回归流程处理。
- 当前没有独立SEO岗位。Website Manager负责关键词意图、Title/Description和内容级自检；Canonical、索引策略及已发布URL等高影响技术SEO由Website Manager记录业务理由、开发者复核技术影响。
- 业务方负责正式名称、产品事实、是否临时缺货/永久停售、严格替代关系与内容合规。真实输入不足时，骨架可继续，但真实内容尚不能验收。
- Local与Staging已统一`/product/{slug}/`，`/shop/`保留为归档。Staging旧TEST路径已确认发生跳转，但未取得原始301/302状态码，不能写成已验证301。

## 决策与范围变化

- Storefront继续作为父主题，DentAll子主题承载展示；重复Title属于网站级SEO兼容问题，放在按模块拆分的DentAll Core中，不依赖当前主题存活。
- 只在必须由代码稳定实现时增加项目代码；后台可安全完成的内容维护继续由Website Manager操作。插件数量不是性能的直接决定因素，执行路径、查询、资源加载和维护边界才是主要判断依据。
- D16在用户明确授权后安装Staging Yoast并同步Local/Staging商品固定链接；未操作Production，也未升级WooCommerce、Breeze或其他插件。
- 新需求：无。D16规则收口不代表5～10个真实代表商品内容已经验收。

## 问题与风险

- 数据风险：正式SEO内容、停售状态和替代关系必须由业务方确认；TEST名称、描述和URL不得作为正式内容发布。
- URL风险：Local与Staging无Production历史流量，当前TEST旧路径不进入Production 301登记；未来任何已发布Slug变更仍须逐条登记并取得响应状态证据。
- SEO风险：Staging全站`noindex, nofollow`且无Canonical是受保护环境事实，不能推导Production索引和Canonical已正确。
- 缓存风险：Staging启用Breeze与Object Cache Pro，但本轮没有改缓存策略，也未做Production缓存下的SEO输出验收。
- 权限风险：Website Manager保留Yoast高级元数据能力，依赖培训、变更记录和复核；该高影响操作边界在D25前继续纳入综合验收。
- 部署风险：DentAll Core 0.2.3已部署Staging；Production未部署、未改URL、重定向、robots、Sitemap、Canonical、索引或缓存。
- 支付与物流风险：本轮未触碰支付、税费、正式物流、订单、邮件、DNS或上线配置。

## 今日复盘

- 完成：重复Title根因修复、DentAll Core模块化、双环境部署与插件基线、SEO字段职责、Slug/301/Canonical、缺货/停售URL生命周期和D17交接。
- 未完成及原因：真实商品SEO内容、Variable/当前缺货/可下载/永久停售代表样本、两名实际工作人员无指导试录和Production Canonical不具备本轮输入或授权，转后续节点。
- 实际工时与计划偏差：待用户记录；D13、D14和D15实际工时仍待补录，不影响D17骨架启动。
- 今天学到的内容：SEO修复必须同时处理“唯一输出、插件停用回退、主题切换边界和环境索引策略”，单看某一次页面源码不足以形成可维护结论。

## 明日启动点

- D17先只读梳理5～10个代表商品样本和可用真实资料，再等待用户确认实施；优先覆盖Simple与Variable，缺货、下载和停售仅在有可信业务事实时纳入。
- Website Manager实际录入并保存商品名称、SEO Title、Meta Description和Slug，开发者核对前台H1、Title、Description、状态码、robots与Canonical；不批量改URL，不操作Production。
- D17输出作为D18商品模型候选冻结输入。骨架可继续；正式名称、文案、替代关系或内容授权不足的样本，明确标记为“真实内容尚不能验收”。

## 可复用核心思想

- 跨平台不变量：页面标题、可见H1、搜索摘要、URL标识、主版本声明和索引指令是不同职责。把它们塞进同一字段或用Canonical代替重定向，会让内容、抓取和用户访问产生互相矛盾的信号。
- 跨平台不变量：临时交易状态不应自动改写长期URL生命周期。缺货可以变化，已积累的URL身份应保持稳定；永久撤销才需要基于替代关系和持续价值决定301、200、404或410。
- WooCommerce/WordPress实现：父主题提供展示兼容，子主题承载DentAll展示，跨主题网站级SEO兼容放项目插件；使用Hook最小化修复，并验证插件启用、停用和恢复三种状态。
- WooCommerce/WordPress实现：固定链接后台的“默认”保存后可能被核心规范化并回显为`product/`自定义基础；判断结果应看最终URL与核心保存逻辑，不只看单选框标签。
- Shopify或其他平台同样需要稳定Handle/URL、搜索摘要、Canonical和停售重定向治理，但其自动重定向、下架状态与索引控制的具体行为尚未在本项目验证，不能假设与WooCommerce一一对应。
