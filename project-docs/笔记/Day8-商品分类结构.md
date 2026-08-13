---
项目: DentAll WooCommerce
日期: 2026-08-13
工作日: D8
周次: W2
计划工时: 6小时50分钟有效工作
实际工时: 待用户记录
状态: Day8职责与动态分类骨架已确认；文档收尾完成，未写入WooCommerce
---

# DentAll 每日复盘 D8

## 相关笔记

- 前置笔记：[[Day7-商品资料盘点]]
- 后续笔记：[[Day9-SKU品牌与属性规则]]
- 同日能力复盘：[[Day8-远程项目业务沟通与边界能力]]

## 本日结论

D8不冻结正式商品分类树，而是冻结“动态分类骨架＋统一业务所有权”。业务方使用一个`DentAll Website Manager`账号自行管理商品、商品分类、属性、品牌、文章、文章分类、页面、媒体和内容级SEO；开发者负责权限、安全、URL机制、备份恢复和前端容错，不替业务方决定分类与产品事实。

首页设计稿中的9个入口以及下文8个顶级分类仅保留为设计分析和测试参考，不是开发实施基线。前端不得硬编码这些分类的ID、名称或数量；首页分类区域采用可配置的精选分类。Local只读查询未发现现有Product Categories，因此当前不存在分类迁移或兼容历史URL的问题。

## 分类设计原则

1. Product Categories回答“这是哪类可售商品”，用于商品归档、导航和SEO落地页。
2. Global Attributes回答“客户如何筛选”，例如材质、尺寸、色号、适用设备和包装数量。
3. Brands回答“谁生产”，不复制成商品分类。
4. Solutions回答“为谁或解决什么问题”，例如Dental Clinics、Dental Labs和Custom Restorations；不与商品分类重复建树。
5. `On Sale`回答“当前是否促销”，由价格状态动态生成，不作为长期分类。
6. 第一版分类最多两级；没有足够真实商品和独立浏览价值时不提前创建空二级分类。

## 业务所有权与分类治理（已确认）

- `DentAll Website Manager`使用同一个角色管理全部商品、商品分类、全局属性项、品牌、SKU、价格、库存、变体、媒体、文章、页面、文章分类、标签、内容级SEO和发布状态；工作人员不需要为了分类、商品或文章来回切换角色。每位工作人员仍使用独立账号，禁止共享登录凭据。
- `DentAll Content Editor`继续作为低权限录入角色，按既有结构提交草稿；开发者使用`Administrator`维护技术骨架。
- Website Manager不得访问订单、退款、支付、插件、主题、用户、固定链接、全站SEO设置、数据库或部署。
- 日常分类、属性项和标签由Website Manager直接维护；批量导入/删除/合并、大量已发布Slug或层级变化、固定链接及全局属性定义才升级给开发者评估。
- 角色代码和WordPress capability尚未实施。本日只冻结职责与验收边界；权限实现和冲突测试按D12～D25推进。

该方案用于填补缺少产品经理造成的职责真空：业务方对“产品是什么、归到哪里、价格库存是否真实”负责；开发者对“系统是否安全可维护、URL是否稳定、数据是否可恢复”负责。

## 设计导航与商品分类映射

| 设计入口 | 推荐实现 | 原因/限制 |
|---|---|---|
| Dental Materials | Product Category | 稳定商品族群，列表稿已有二级分类证据 |
| Custom Dental | Solutions/销售路径候选 | 更接近定制修复与询价场景；标准化可售定制商品出现后再评估分类 |
| Dental Packaging | Product Category | 可容纳现货包装和定制包装商品；销售模式另行控制 |
| Equipment | Product Category | 只承载设备，不把bur、polisher等器械耗材全部塞入 |
| Disposables | `Infection Control & Disposables`分类候选 | 需确认正式商品范围，避免与Materials重叠 |
| Lab Supplies | `Dental Lab Supplies`分类候选 | 只承载实验室专用工具/耗材，不按客户身份重复归类 |
| Implants | `Implant Dentistry`分类候选 | 有正式商品后才创建和索引 |
| Orthodontics | `Orthodontics & Thermoforming`分类候选 | 兼容EVA材料片候选，但需确认与成品护齿的关系 |
| On Sale | `/deals/`动态集合候选 | 由促销状态生成，不创建Product Category |

## 推荐顶级分类v1候选

| 顺序 | 英文名称 | Slug候选 | 当前依据 | 状态 |
|---:|---|---|---|---|
| 1 | Dental Materials | `dental-materials` | 设计稿及锆块/材料样本 | 候选 |
| 2 | Instruments & Rotary | `instruments-rotary` | HP0103G、FG0312D样本需要稳定归属 | 候选；名称需业务确认 |
| 3 | Orthodontics & Thermoforming | `orthodontics-thermoforming` | 设计入口及EVA/护齿场景 | 候选；销售对象待确认 |
| 4 | Dental Packaging | `dental-packaging` | 设计稿及定制包装资料 | 候选 |
| 5 | Equipment | `dental-equipment` | 设计稿 | 候选；首版销售范围待确认 |
| 6 | Infection Control & Disposables | `infection-control-disposables` | 设计稿Disposables入口 | 候选；首版销售范围待确认 |
| 7 | Dental Lab Supplies | `dental-lab-supplies` | 设计稿Lab Supplies入口 | 候选；与Materials分界待确认 |
| 8 | Implant Dentistry | `implant-dentistry` | 设计稿Implants入口 | 候选；首版销售范围待确认 |

纯定制询价内容可以继续使用WooCommerce Product承载，但不代表必须建立`Custom Dental`顶级商品分类。是否显示购买区由商品销售模式控制，不能仅靠分类决定。

## Dental Materials二级分类候选

商品列表设计稿提供了以下标签，可作为验证起点：

| 英文名称 | Slug候选 | 当前状态 | 建立条件 |
|---|---|---|---|
| Restorative Materials | `restorative-materials` | 候选 | 具有多条正式商品且用户会按该族群浏览 |
| Impression Materials | `impression-materials` | 候选 | 同上 |
| Cements & Liners | `cements-liners` | 候选 | 同上 |
| Preventive Materials | `preventive-materials` | 候选 | 同上 |
| Whitening Products | `whitening-products` | 候选 | 同上 |
| Endodontic Materials | `endodontic-materials` | 候选 | 同上 |

代表样本另支持以下二级候选，但它们不是设计稿已批准标签：

| 父分类 | 英文名称 | Slug候选 | 样本依据 |
|---|---|---|---|
| Dental Materials | CAD/CAM Materials | `cad-cam-materials` | 爱迪特锆块 |
| Instruments & Rotary | Polishing Kits | `polishing-kits` | HP0103G |
| Instruments & Rotary | Preparation Kits / Diamond Bur Kits | 待正式英文名后确定 | FG0312D |
| Orthodontics & Thermoforming | Thermoforming Materials | `thermoforming-materials` | 仅当销售对象为EVA材料片 |
| Orthodontics & Thermoforming | Mouthguards | `mouthguards` | 仅当销售对象为成品运动护齿套 |

设计稿中的商品数量均为视觉占位，不作为真实数量。其他顶级分类暂不凭空建立二级分类；D9～D17使用代表商品验证后再补充。

## 分类、属性、品牌和Solutions边界

| 信息 | 正确归属 | 不应处理为 |
|---|---|---|
| Zirconia、PMMA、EVA等材质 | Global Attribute：Material | 顶级分类 |
| 尺寸、色号、颜色、包装数量 | Global Attribute；影响购买时用于Variation | 分类 |
| CAD/CAM或适用手柄/设备 | Global Attribute：Compatible System/Device候选 | 重复分类树 |
| Aidite、Toboom、Supsmile | Brand能力 | Product Category |
| Dental Clinics、Dental Labs | Solutions页面/导航 | Product Category |
| Custom Dental、Custom Restorations | Solutions/销售路径候选 | 未确认就同时建立商品分类和Solutions |
| Sale/Deals | 动态促销集合 | Product Category |

## 多分类与主分类规则

- 每个商品必须有且只有一个业务主分类；可以增加最多一个确有独立浏览价值的辅助分类。
- 不因同一商品有多种材质、颜色、尺寸、品牌或用途而增加多个分类；这些信息使用属性。
- 不为了首页入口、筛选器或活动页重复创建近义分类。
- `Custom Dental`、`Dental Packaging`与Solutions页面发生交叉时，商品仍只保存真实商品类别；Solutions通过查询、精选关系或手工链接聚合。
- 当前商品固定链接为`/product/{slug}/`，主分类变化不改变商品URL；分类层级或Slug变化会改变分类归档URL，D25后必须评估重定向和SEO影响。
- Yoast安装并验证前，内容清单中先记录“业务主分类”；D16再验证Primary Category能力及Canonical输出。

## 五个代表场景验证

| 场景 | 主分类候选 | 辅助分类/属性 | 结论与缺口 |
|---|---|---|---|
| 爱迪特锆块 | Dental Materials → CAD/CAM Materials候选 | Material=Zirconia；尺寸/色号为属性 | 结构可覆盖；二级分类名称待业务确认 |
| 运动护齿套/EVA护齿材料片 | Orthodontics & Thermoforming候选 | Material=EVA；颜色为属性 | 先确认它是成品护齿套还是制作材料片，再选择Mouthguards或Thermoforming Materials |
| HP0103G抛光套装 | Instruments & Rotary → Polishing Kits候选 | 产品用途、适用手柄为属性 | 需确认行业正式英文分类和可售单位 |
| FG0312D备牙套装 | Instruments & Rotary下二级待定 | 套装用途、适用手柄为属性 | 确认是diamond bur kit还是更广义preparation kit |
| 定制包装 | Dental Packaging | 定制属性主要用于询价信息，不生成Variation | 结构可覆盖；销售模式为展示/询价 |

五个场景均能进入候选树，没有阻塞D9的分类缺口；其中运动护齿产品和抛光/备牙套装的正式主分类必须在D17样本验收前确认。

## 重复与扩展性检查

- `Dental Materials`与`Dental Lab Supplies`可能按产品性质和客户场景发生重叠：前者应按材料本体，后者只保留实验室专用工具/耗材；不能因为牙科技工所会使用就双重归类。
- `Equipment`只承载设备；handpiece、bur、polisher及套装优先在`Instruments & Rotary`验证，避免产品与设备混为一类。
- `Custom Dental`与`Custom Restorations`含义接近：当前均按Solutions/销售路径候选处理；只有出现稳定的标准可售商品群才重新评估Product Category。
- `Dental Packaging`既可能包含现货包装，也可能包含定制询价；用商品销售模式区分，不拆成两套同名分类。
- 第一版只保留两级结构；未来商品量足够时才评估第三级或重构，不用空分类预测全部970条物料。

## 需业务确认的问题

| 问题 | 建议默认 | 最晚节点 | 影响 |
|---|---|---:|---|
| 推荐的8个顶级分类是否覆盖第一版正式销售范围 | 先作为v1候选 | D12前 | D12原型与D18冻结 |
| 设计稿9入口是正式信息架构还是视觉占位 | 当前只作设计证据 | D12前 | 首页导航与正式分类树 |
| Custom Dental是否只作为Solutions/销售路径 | 默认不建同名Product Category | D12前 | 分类与询价模板 |
| Lab Supplies与Dental Materials的分界 | 按产品性质而非客户身份 | D12前 | 重复分类与筛选 |
| Instruments & Rotary及其二级分类的正式英文名 | 先用候选名 | D10前 | HP0103G/FG0312D归属 |
| 运动护齿资料代表成品还是EVA材料片 | 不猜测，暂标待确认 | D10前 | 商品类型、主分类与属性 |
| Implants和Orthodontics是否确有第一版商品 | 没有正式商品则保留导航候选但不创建空归档 | D12前 | 首页入口和空页面 |
| On Sale使用`/deals/`还是商城动态筛选URL | 倾向`/deals/`营销入口，避免伪分类 | D16/D43 | URL、Canonical与索引 |

## D8候选验收结果

以下结果为本轮分析建议，需用户确认后才作为Day8正式实施与验收口径：

1. 商品分类结构v1候选：8个推荐顶级分类、Solutions映射、1个动态促销入口，以及设计/样本支持的二级候选。
2. 五个代表场景覆盖检查：当前没有阻塞D9的资料缺口，歧义已记录最晚确认点。
3. 分类、属性、品牌、Solutions与Sale的职责边界及Slug/SEO风险候选。

最终确认采用统一Website Manager角色承担商品、分类、文章、页面和内容级SEO业务所有权。上方8个具体顶级分类仅作为测试参考，不需要开发者或D8冻结，也未写入WooCommerce。

## 风险与Agent记录

- 风险等级：中。分类层级与Slug影响导航、归档URL、SEO和后续批量数据。
- 主Agent：核对D7输入、项目文档、现有主题、Local分类和设计效果图。
- 项目/需求专项Agent：因设计导航、商品分类和Solutions存在语义重叠而启动，只读复核后将`Custom Dental`调整为Solutions候选，并增加`Instruments & Rotary`商品分类候选。
- 未启动Code Review、安全或测试Agent：本日不编码、不写数据库、不操作支付库存或生产部署。

## 明确未做

- 未在Local或Staging创建、修改或删除Product Categories。
- 未修改客户资料、商品、URL配置、固定链接、菜单或数据库。
- 未把设计稿数量、仓库分类或示例内容当成正式业务数据。
- 未安装Yoast、ACF或其他插件。

## 下一启动点

- D9基于分类v1候选设计SKU、品牌和Global Attributes规则。
- 优先验证五个代表场景所需的Material、Size/Shade/Color、Package Quantity和Compatible Device/System候选。
- 分类结构获得业务确认前，不批量写入WooCommerce；D12原型只创建必要的最小分类集合。

## 可复用核心思想

- 商品分类回答“这是什么商品”，属性回答“它有什么可比较规格”，品牌回答“谁对外销售”，Solutions回答“为谁解决什么问题”；不要用一棵分类树承载所有语义。
- 导航入口、商品分类、筛选条件和活动集合是四种不同结构。界面上同时出现，不代表数据库里都应该创建Product Category。
- `On Sale`这类状态应由价格动态产生，不应创建长期分类；把动态状态固化为分类会产生维护漂移。
- 分类Slug和层级会形成归档URL与SEO资产；商品日常归类可以交给业务方，但批量改Slug、合并和迁移必须评估重定向与回滚。
- 前端不应硬编码分类ID、名称或数量。可配置精选项和合理的空值、缺图、长名称状态，才能让业务方自主维护内容。
- 没有足够真实商品和独立浏览价值时，不提前创建空分类。信息架构应由真实查找需求驱动，而不是试图一次预测未来全部商品。
