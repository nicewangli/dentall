---
项目: DentAll WooCommerce
日期: 2026-08-15
工作日: D12
周次: W2
计划工时: 6小时50分钟有效工作
实际工时: 待用户记录
状态: 已完成
---

# DentAll 每日复盘 D12

## 相关笔记

- 周总结：[[W2-商品模型与双环境原型周总结]]
- 前置笔记：[[Day11-商品图片与资料文件规范]]
- 权限基线：[[Day5-编辑角色与权限]]
- 商品模型依据：[[Day10-商品类型与价格库存规则]]
- 后续笔记：[[Day13-真实编辑试录与简单商品流程]]（D13创建后补回链）

## 今日三个验收结果

- [x] Local商品原型可用：TEST Simple与TEST Variable完成字段持久化、变体、库存、前台和恢复验证。
- [x] Website Manager双环境可用：DentAll Core 0.2.1部署到Staging，业务内容与商城运营动作通过，WordPress开发和系统权限保持关闭。
- [x] 双环境回归基线形成：验收证据、人员角色、SEO/Site Kit/GTM边界和保留TEST夹具的决定已记录。

## 专注周期记录

| 周期 | 计划 | 实际结果 | 证据/文件 | 用时 |
|---|---|---|---|---:|
| C1 | 收敛Website Manager业务职责 | 角色版本5采用明确能力白名单，统一承接内容、商品和商城运营 | `dentall-core.php`、ADR-015 | 待用户记录 |
| C2 | 验证高权限业务能力与系统边界 | 订单、优惠券、客户、报表及商品删除能力开放；用户、插件、主题、系统设置未开放 | 独立Review、安全与测试结果 | 待用户记录 |
| C3 | 完成商品和媒体操作准备 | Website Manager可编辑全部业务内容与媒体元数据，安全位图和5MB边界保持 | Local后台走查 | 待用户记录 |
| C4 | 建立TEST Simple原型 | 商品44通过23项持久化审计、发布、前台和回收站恢复 | `day12-c4-*.php`审计脚本 | 待用户记录 |
| C5 | 建立TEST Variable原型 | 商品46与Variations 51～53最终17项审计通过，非法组合不可选 | `day12-c5-*.php`审计脚本 | 待用户记录 |
| C6 | 部署并验证Staging | DentAll Core 0.2.1和Storefront生效；文章、页面、媒体、商品、属性、优惠券、订单、客户、报表、评论通过 | Staging浏览器截图与人工操作 | 待用户记录 |
| C7 | 修复与收工整理 | 修复空Tools入口；保留TEST回归夹具并更新项目状态、测试计划、流程和决策 | Commit与本笔记 | 待用户记录 |

## Local验收结果

### Website Manager角色

- 角色定义版本为5，使用明确能力白名单覆盖旧能力，避免权限移除后残留。
- 允许完整管理文章、页面、媒体元数据、分类、标签、评论/商品评价、商品、商品术语、订单、优惠券、客户创建和WooCommerce报表。
- 不授予`manage_options`、WordPress用户管理、插件、主题、核心更新、代码、数据库或部署权限。
- Website Manager和Content Editor仍只允许JPEG、PNG、WebP，单文件上限5MB。

### TEST Simple商品

- Local商品ID：44。
- 已验证商品类型、SKU、价格、库存、重量尺寸、分类/品牌、描述、图片、发布状态和数据持久化。
- 23项审计通过；回收站、恢复和恢复后字段一致性通过。

### TEST Variable商品

- Local父商品ID：46；Variation ID：51、52、53。
- 2个Size×2个Shade只建立3个合法Variations，未把理论笛卡尔积误建为可售组合。
- 最终17项审计通过；价格范围、库存、缺货、变体SKU、物流继承/覆盖及非法组合不可选均符合预期。

## Staging验收结果

### 部署与账号

- `main`提交`df119d7`完成角色与商品原型；`f6aa249`修复业务角色空Tools入口。
- `deploy/staging`修正为以`wp-content/`为部署根，并部署对应代码；Staging最终运行DentAll Core 0.2.1。
- Staging安装并启用Storefront，用于商品前台冒烟。
- 已建立独立Website Manager账号；密码未写入项目文件。

### 系统负向边界

- Website Manager直接访问`users.php`、`plugins.php`、`themes.php`和`options-general.php`均被拒绝。
- 0.2.0初次部署后Tools入口为空页面，定级为P2后台体验问题；0.2.1隐藏入口并增加直接请求拦截，Staging菜单复测后入口消失。
- 未启用真实支付，未创建真实订单或客户，未修改Production、DNS、正式邮件、税费或物流配置。

### 内容与媒体

- 文章`TEST D12 Manager Published Post`完成创建、发布、更新、移至回收站、恢复和重新发布。
- 页面`TEST D12 Manager Published Page`完成创建、发布、图片插入、移至回收站和恢复。
- `test-variable-product-front.webp`正常上传并显示；页面图片区块Alt和媒体库全局Alt均可编辑，重新打开后仍存在。
- 评论批准状态、回收站和恢复操作正常。

### 商品与商城运营

- `TEST D12 Staging Simple Product`完成SKU、19.99美元价格、库存2、禁止Backorders、1 lb、6×4×2 in、分类、ADS品牌、图片、描述和发布验证。
- 前台显示价格、2件库存、SKU、分类、品牌及Add to cart；随后完成价格/库存更新、回收站恢复并回到19.99/2基线。
- 创建TEST全局Size属性及Small/Large项，完成编辑和删除；测试结束时该属性已删除。
- 优惠券`TEST-D12-STAGING-10`以百分比10、总使用次数1发布，编辑后最终基线正确。
- 订单空状态、“添加订单”入口、客户空状态、WooCommerce报表和CSV入口均正常。

## TEST回归夹具保留决定

- 用户明确决定D12不清理TEST数据，因为D13及下周开发测试仍需复用。
- Local保留TEST Simple商品44、TEST Variable商品46及Variations 51～53。
- Staging保留TEST文章、页面、媒体、简单商品、`TEST D12 Products`分类和优惠券。
- 已删除的TEST全局属性不恢复；误建但未使用的“简单产品”分类暂记为非基线对象，D25前随测试数据一起复核。
- 所有对象继续视为TEST数据，不得改名冒充正式内容；Staging继续受Password Protection和`noindex`保护。

## 人员、SEO、Site Kit与GTM边界

- 当前两名网站人员均需承担内容和商城运营，因此都使用`DentAll Website Manager`，但必须一人一账号，禁止共享凭据。
- `DentAll Content Editor`保留为未来可选角色，D12不再安排低权限人员对照测试。
- 网站人员可维护内容级SEO：SEO标题、Meta描述、Slug、内链、图片Alt和插件提供的内容编辑字段。
- WordPress“插件”页面继续关闭。SEO插件、Site Kit等由Administrator安装、更新和首次配置。
- Site Kit上线后通过Dashboard Sharing向Website Manager提供只读数据，不为查看数据授予Administrator。
- GTM日常标签工作在Google Tag Manager平台按公司账号授权；WordPress端容器接入、重复标签和全局测量配置由开发者负责。
- 固定链接、robots、Sitemap、Canonical、全局Schema、批量重定向及索引策略仍需开发与变更流程。

## 测试与验证

- 执行的命令：PHP语法检查、角色能力审计、商品持久化/恢复审计、`git diff --check`及部署树检查。
- 浏览器/设备：Local与受保护Staging桌面浏览器；Storefront文章、页面和商品前台。
- 通过项：Local商品原型、角色白名单、Staging内容/媒体/商品/商城运营、系统直接URL边界和Tools入口修复。
- 未通过项：无P0/P1；1个P2空Tools入口已在0.2.1关闭。
- 未验证项：真实支付、退款、税费、物流、正式邮件、Production、Yoast实际字段兼容、Site Kit共享面板、GTM与GA4事件。

## Codex Agent调度与审查

- 今日风险等级：高；涉及角色权限、文件上传、商品删除、订单与优惠券能力。
- 启动的Agent及职责：独立Code Review检查正确性与维护性；安全Agent检查越权、上传与系统边界；测试Agent检查对象级能力、恢复和商品原型。
- Review结果：P0=0，P1=0；Staging空Tools入口为P2并已修复复测。
- 剩余风险：`manage_woocommerce`仍允许部分WooCommerce日常设置；支付、税费、物流、邮件、Webhook和Production操作必须继续依赖变更流程、备份和测试，不能把“界面可见”理解为可无审批修改。

## 决策与范围变化

- 两名网站人员统一使用Website Manager，低权限角色暂不参与当前人员验收，但保留实现。
- 不开放WordPress插件管理；Site Kit使用只读共享，GTM使用Google平台独立授权。
- D12 TEST对象不清理，保留为D13及下周回归夹具；D25前再次决定归档或删除。
- 本轮决定不增加插件、不修改URL机制、不启用真实支付，不产生额外实施工时估算。

## 问题与风险

- 阻塞：D13真实业务人员独立试录尚未进行；这影响人员验收，不阻塞已完成的技术骨架。
- 技术债：Staging保留多个TEST URL与媒体；虽然受保护且`noindex`，D25前仍需统一复核，不能迁移为Production正式内容。
- 需要他人提供：D13真实商品最小样本；正式域名、支付、物流、税费、SMTP及Google公司资产按计划节点提供。

## 今日复盘

- 完成：Local商品原型、Website Manager角色版本5、Staging部署、业务正向操作、系统负向边界、Tools修复及文档收口。
- 未完成及原因：未做低权限人员测试，因用户确认当前两名网站人员都需要Website Manager；Yoast、Site Kit和GTM尚未到安装连接阶段。
- 实际工时与计划偏差：待用户记录；D12同时覆盖角色扩权、商品原型、部署和Staging人工验收，范围高于原计划的单纯2～3个商品原型。
- 今天学到的内容：能力白名单只是技术边界的一部分；高权限业务角色还需要独立账号、操作流程、测试数据、恢复路径和外部平台权限共同构成可运营边界。

## 明日启动点

- 明日第一件事：两名实际网站人员分别使用自己的Website Manager账号做30分钟无指导试录，优先记录无法完成、数据丢失或越权等P0/P1问题。
- 需要提前准备：一个经业务方确认的简单商品最小样本；D12 TEST对象保持不变供对照和回归。

## 可复用核心思想

- “能做业务”与“能管理系统”应分离。内容、商品、订单和报表属于运营能力；用户、插件、主题、代码、数据库和部署属于系统控制面。两者混在Administrator里虽然省事，却会扩大误操作和账号失陷的影响半径。
- 最小权限不等于给每个人最低角色，而是给每个真实职责完成工作所需的最小权限。两名人员承担相同完整运营职责时，可以使用同一种高权限业务角色，但仍必须使用独立账号以保留审计和撤销能力。
- WordPress插件的“功能页面”和“插件管理页面”是不同权限面。运营人员可以使用SEO或分析插件提供的业务界面，而无需获得安装、启用、更新或删除插件的能力。
- 第三方平台权限应在数据和配置真正归属的平台分配。GTM标签在Google Tag Manager管理，GA4/Search Console数据在Google资产中授权；不应为了外部平台工作把WordPress账号提升为Administrator。
- 测试数据不一定要立即删除。若对象命名明确、环境受保护、不会被当作正式内容，并且有复核截止点，保留稳定夹具可以显著降低后续回归成本；没有命名、环境和生命周期约束的“临时数据”才会变成污染。
- 技术验收和人员验收不能互相替代。自动审计能证明字段、权限和持久化，真实业务人员的无指导试录才能发现术语、后台认知和操作路径问题。
- 商品Variation的合法集合必须来源于真实可售组合，而不是属性值的机械笛卡尔积。这一原则跨WooCommerce、Shopify及其他电商平台成立；具体变体限制和库存实现需要按平台验证。
- 高权限商城角色即使不具备插件和系统权限，仍可能通过支付、税费、物流或Webhook设置影响交易。技术能力、操作规范、备份、审计和变更审批必须共同存在，任何单层控制都不能提供完整保证。
- Staging的Password Protection、`noindex`和支付关闭分别控制访问、索引意愿和交易风险，三者职责不同，不能用其中一个替代另外两个。
- 部署分支的目录根本身就是发布契约。部署前验证树结构能在代码到达服务器前阻止错误路径；这比部署后依赖人工清理更安全、更容易回滚。
- WooCommerce当前通过capability白名单、CRUD与回收站验证业务边界；Shopify通常通过员工角色、应用权限和平台配置实现类似目标，但具体权限粒度、数据恢复和应用管理机制必须单独实测，不能假定一一对应。
