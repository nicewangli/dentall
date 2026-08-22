# DentAll 每日笔记索引

本目录统一存放 DentAll 项目 D1～D120 的每日工作笔记、阶段基线和当日形成的操作手册，方便通过 Obsidian 集中查阅。

## 存放规则

- 后续每日笔记一律创建在 `project-docs/笔记/`，不得散落在 `project-docs/` 根目录。
- 每篇笔记的文件名统一采用 `Day{天数}-中文主题.md`，例如 `Day5-编辑角色与权限.md`；技术专有名词可以保留英文。
- 同一天需要按主题拆分时，可创建多篇，例如 D4 的 Cloudways 与 WordPress 手册。
- 新笔记必须增加“相关笔记”章节，并使用 Obsidian `[[Wiki链接]]` 关联直接相关的前置、后续或同主题笔记。
- 双向关系必须在关联笔记两端都写入链接，不能只依赖 Obsidian 自动反向链接面板。
- 不为凑数量链接无关笔记；范围、环境、数据、开发、测试、发布和运维按真实依赖关联。
- 每日复盘沿用项目根目录的 `Obsidian每日复盘模板.md`。
- 笔记只记录配置结果、操作路径、验证证据、决策和风险，不记录密码、私钥或支付密钥。
- 当前事实仍以 `../PROJECT_STATE.md` 为准；笔记用于保存每日过程和详细证据。

## 周总结

| 周次 | 笔记 | 结论 |
|---|---|---|
| W1 | [[W1-环境与安全试录周总结]] | Local、受保护Staging、Git/恢复边界、最小权限与M1技术预验收完成；真实人员验收转D13 |
| W2 | [[W2-商品模型与双环境原型周总结]] | 商品规则v1候选、Local原型、Website Manager角色和Staging双环境验收完成；D18再候选冻结 |
| W3 | [[W3-商品样本与模型候选冻结周总结]] | 商品编辑与SEO样本、Simple/Variable职责、双环境权限和CSV验证完成；M2商品模型候选冻结通过 |

## D1～D6

| 工作日 | 笔记 | 内容 |
|---|---|---|
| Day1 | [[Day1-范围与访问依赖基线]] | 第一版范围、访问依赖和风险基线 |
| Day2 | [[Day2-本地环境与WooCommerce基线]] | LocalWP、WordPress、WooCommerce 与本地环境基线 |
| Day3 | [[Day3-代码数据与恢复边界]] | Git、代码、数据、密钥、备份和恢复边界 |
| Day4 | [[Day4-Cloudways配置与常用操作手册]] | Cloudways Staging、备份、安全、SFTP/SSH 与运维路径 |
| Day4 | [[Day4-WordPress与WooCommerce配置及常用操作手册]] | WordPress/WooCommerce 配置、菜单地图和排错路径 |
| Day5 | [[Day5-编辑角色与权限]] | 最小权限角色、Via Git部署、权限审计和Staging端到端验收 |
| Day6 | [[Day6-商品与文章安全试录]] | 开发者代理试录、M1技术预验收和D13真实编辑验收安排 |

## D7～D12

| 工作日 | 笔记 | 内容 |
|---|---|---|
| Day7 | [[Day7-商品资料盘点]] | 商品资料来源、可信度、代表场景和业务缺口 |
| Day8 | [[Day8-商品分类结构]] | 动态分类骨架、分类治理和Website Manager业务所有权 |
| Day9 | [[Day9-SKU品牌与属性规则]] | SKU、品牌、Global Attributes和Variation映射规则v1候选 |
| Day9 | [[Day9-商品业务确认清单]] | 逐商品事实延后到实际录入时按需确认的范围边界 |
| Day10 | [[Day10-商品类型与价格库存规则]] | Simple、Variable、Display Only、价格库存、物流尺寸、合法组合和D12原型输入 |
| Day11 | [[Day11-商品图片与资料文件规范]] | 商品图片比例、格式压缩、元数据、授权和上传边界 |
| Day12 | [[Day12-双环境角色与商品原型验收]] | Website Manager角色版本5、Local商品原型、Staging运营权限与双环境验收 |
| Day13 | [[Day13-真实编辑试录与简单商品流程]] | Website Manager培训者预演、独立复跑与简单商品发布字段候选 |
| Day14 | [[Day14-可变商品与Variation流程]] | Variable父子职责、默认值、合法组合、库存与购物车Local验收 |
| Day15 | [[Day15-库存与物流字段]] | 三层物流数据、Simple/Variable继承覆盖、库存模式、临时缺货与停售候选 |
| Day16 | [[Day16-商品SEO规则]] | Title唯一性、SEO字段职责、Slug/Canonical/301、缺货停售URL生命周期与双环境边界 |
| Day17 | [[Day17-代表商品录入与SEO验收]] | 5个累计代表样本、Simple/Variable、缺货Variation、多图、Yoast输出与D18冻结输入 |
| Day18 | [[Day18-商品模型候选冻结]] | 商品模型职责、Website Manager权限、Local/Staging CSV、M2候选冻结与D19交接 |
| Day18复盘 | [[Day18-真实性复盘]] | 纠正计划检查点、实际工时、技术验证、业务验收与可见成品的进度口径 |
| Day19 | [[Day19-博客分类与作者规则]] | 博客信息架构v1验收；原生Post/Category/Tag、`/blog/`路由、归档索引治理、内容作者与修订边界 |
| Day20 | [[Day20-文章录入模板]] | 文章字段职责、正文区块骨架、长文TEST草稿与草稿/预览/修订验证 |
| Day21 | [[Day21-固定页面清单与URL责任边界]] | About、Contact、政策页与FAQ候选清单、责任矩阵、菜单依赖和页面状态验收边界 |
| Day22 | [[Day22-Solutions内容模型]] | Solutions原生Page优先方案已在D24 C1获确认，不建CPT；原生Page编辑走查已由D24 C2完成，正式Solutions内容仍待完成 |
| Day23 | [[Day23-内容审核发布与媒体治理]] | 非强制互审已确认；`CONTENT_ASSET_REGISTER.md`现为唯一活动登记载体，`.xlsx`中的16条A/C3-A/C4记录迁移后生成文件已删除。D24 A/C3-A/C7技术与人员抽样已完成，正式内容/素材仍待，Git企业交接留D25 |
| Day24 | [[Day24-内容样本与操作培训]] | A/C5培训、A/C6 SOP辅助验收及A/C7状态/字段/预览/隔离/修订/登记抽样通过，开放P0/P1为0；3篇正式文章＋1个正式Page和授权16:9素材仍待，D24整体进行中 |
| Day24手册 | [[Day24-内容发布操作手册与WM-A培训]] | 基于Staging实测界面的发布、更新、恢复、审阅草稿、素材闸门与Markdown登记操作手册；明确无需背诵、必须理解与停止条件，A/C6-C7已通过 |
| Day24-B | [[Day24-B-真实样本与周验收]] | 真实来源第一轮盘点完成；正式文章与Page输入包0/4，技术骨架可继续、业务验收待输入，不能用TEST或未授权资料代替 |
