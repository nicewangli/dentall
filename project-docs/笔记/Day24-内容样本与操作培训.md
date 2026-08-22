---
项目: DentAll WooCommerce
日期: 2026-08-21
最近更新: 2026-08-22
工作日: D24
计划检查点: D24（不自动等于一个完整实际工作日）
周次: W4
计划工时: 6小时50分钟有效工作
实际有效工时: 待用户按需记录
验收层级: A/C2按WM-A角色级范围完成；A/C3与A/C4通过；A/C5有指导手册与培训完成；C6按SOP辅助验收通过；C7抽样与收口通过
状态: 进行中
---

# DentAll 每日复盘 D24：内容样本与操作培训

## 相关笔记

- 前置笔记：[[Day23-内容审核发布与媒体治理]]
- 操作手册：[[Day24-内容发布操作手册与WM-A培训]]
- 真实样本与周验收：[[Day24-B-真实样本与周验收]]
- Solutions结构：[[Day22-Solutions内容模型]]
- 固定页面边界：[[Day21-固定页面清单与URL责任边界]]
- 后续笔记：D25综合验收笔记完成后回填

## 今日三个验收结果

- [x] 收口D21～D23的结构决定，并用Page #76完成A/C3单账号状态、发布、缓存与受控导航技术路径；动作证据已统一进入Markdown登记与项目文档。
- [x] 由WM-A完成Page已发布更新、修订恢复、撤回、普通回收站恢复、“立即撤销”及登记联动演练，关闭D23当前范围内的P1。
- [x] 完成中文操作手册修订、WM-A培训及C6-C7收口；#11、#24、#90、#68、#76覆盖排除、源基线、独立审阅草稿、内容字段、预览、公开隔离与修订恢复。3篇正式文章＋1个正式Page仍待业务样本。

## 进度真实性检查

- 自然日期：2026-08-21。
- 实际有效工时证据（可选）：待用户记录。
- 今天完成或推进的计划检查点：D24 A/C1完成；A/C2已按WM-A代表Website Manager角色级路径收口；A/C3完成Draft→Pending Review→发布→撤回→缓存清理→受控菜单路径；A/C4完成Published更新、V1/V2修订、普通恢复、立即撤销与最终Draft收口；A/C5完成中文手册修订与WM-A有指导操作；2026-08-22完成C6 SOP辅助验收及C7状态、字段、预览、公开隔离、修订恢复和Markdown登记抽样。
- 本日最高验收层级：A/C2角色级路径、A/C3-A/C4技术路径、A/C5有指导培训、C6 SOP辅助人员验收及C7抽样收口均通过。#11排除，#24源基线不改，#90高风险审阅Draft隔离，#68与#76保持Draft；该结论不是正式内容、正式素材或Production验收。
- 可由用户直接查看、运行或复演的结果：ADR-023、ADR-024确认范围；`CONTENT_ASSET_REGISTER.md`活动登记；[[Day24-内容发布操作手册与WM-A培训]]；#11、#24、#90、#68、#76的状态与截图；Page #76的公开URL、REST、Sitemap、缓存响应头及菜单#29的Primary/Handheld结果。
- 尚未完成的业务、环境或Production验收：3篇正式文章＋1个正式固定Page、授权16:9素材及全部Production验收。公司控制Git远程的所有权/备份/交接属于D25/M3开放批量录入前门槛。第二身份、跨账号修订、多人并发登记及两名人员培训已由CR-007排除出当前范围，不能写成已通过。
- 当前结论：A/C6-C7已通过；正式内容与授权素材仍缺，因此D24整体不得标记完成。

## 专注周期记录

| 周期 | 计划 | 实际结果 | 证据/文件 | 用时 |
|---|---|---|---|---:|
| C1 | 收口D21～D23结构决定、账号与Page预检、建立登记载体 | Page优先和非强制互审已确认；截图只读盘点完成。最终决定以`CONTENT_ASSET_REGISTER.md`为唯一活动登记载体，`.xlsx`记录迁移后生成文件已删除 | `DECISIONS.md`、`PROJECT_STATE.md`、`CONTENT_ASSET_REGISTER.md` | 待用户记录 |
| C2 | Page字段与角色级权限走查 | WM-A已创建并保存Page #76；字段回读、登录态预览、匿名404、Sitemap排除和五类系统入口拒绝通过；依据CR-007按Website Manager角色级路径收口，WM-B改为特殊条件触发时补验 | `EDITOR_WORKFLOW.md`、`TEST_PLAN.md`、`PROJECT_STATE.md`、用户截图 | 待用户记录 |
| C3 | 新内容状态和发布检查演练 | A/C3技术路径通过：Pending Review、首次发布、撤回Draft、缓存清理、受控菜单及最终发布均完成回归；发现并关闭导航fallback P1与撤回缓存P0；6条动作已保留为项目证据 | `EDITOR_WORKFLOW.md`、`TEST_PLAN.md`、`PROJECT_STATE.md`、`CONTENT_ASSET_REGISTER.md`、用户截图与HTTP响应头 | 待用户记录 |
| C4 | 已发布更新、修订、撤回与两类恢复演练 | 通过；Published低风险更新、V2、revision #85恢复及两条回收站恢复均完成：普通恢复得到Draft且保持404，提示条“立即撤销”恢复先前Published并使URL/Sitemap重新公开。最终已撤回Draft，URL 404无正文泄漏、REST 401、Sitemap与两份导航均无#76；10条动作已保留为项目证据 | `TEST_PLAN.md`、`EDITOR_WORKFLOW.md`、`CONTENT_ASSET_REGISTER.md`、用户截图、REST与HTTP响应头 | 待用户记录 |
| C5 | 内容对象走查、修订中文操作手册并培训 | 有指导演练完成：#11排除；#24保持Published基线；#90走独立审阅Draft；#68与#76完成字段操作并保持Draft。手册已覆盖状态、SEO、内链、素材闸门、登记和恢复分支 | [[Day24-内容发布操作手册与WM-A培训]]、`CONTENT_ASSET_REGISTER.md`、用户截图 | 待用户记录 |
| C6 | 匿名访问、Sitemap、内链、素材与登记抽查 | 通过（SOP辅助、项目负责人接受）：复用A/C3～A/C5实操与证据；2026-08-22只读回归确认#24为200，#90/#68/#76匿名ID路径为404，Draft Slug不在Post/Page Sitemap且首页无#76；没有补造闭卷后台操作 | [[Day24-内容发布操作手册与WM-A培训]]、`TEST_PLAN.md`、`CONTENT_ASSET_REGISTER.md`、CR-009/ADR-026 | 待用户记录 |
| C7 | 可查SOP抽样、P0/P1关闭和D25交接 | 通过：四对象状态/用途一致；#90字段与预览正常；#76临时标记保存5→6后恢复至7，C7消失、C4保留且非修订字段不变；恢复前后匿名404、REST 401、Sitemap/首页排除；Markdown登记和Git差异完成，开放P0/P1为0 | `TEST_PLAN.md`、`CONTENT_ASSET_REGISTER.md`、用户确认、HTTP/REST/Sitemap结果 | 待用户记录 |

## C1事实与决策

- C1曾按用户口头信息记录两个独立Website Manager账号均能登录；C2实际切换账号时用户更正确认目前只有WM-A就绪。CR-007进一步确认D24-D25不再等待或创建WM-B，由WM-A代表同一Website Manager角色完成当前技术验收；历史测试账号或Administrator不作为WM-B。
- 用户确认ADR-023：第一版Solutions使用原生WordPress Page优先，不注册Solutions CPT。`/solutions/`仍是候选URL，不代表已创建、发布或冻结Slug。
- 用户接受ADR-024的非强制互审边界：Website Manager角色具备发布能力，`Pending Review`只是协作信号，不是系统审批；当前由WM-A自查并发布，第一版不为强制职责分离新增角色、工作流或审计插件。
- 用户最初选择普通`.xlsx`，随后正式决定停止继续维护Excel；当前唯一活动登记载体为Git中的`project-docs/CONTENT_ASSET_REGISTER.md`。原`.xlsx`中的16条D24 A/C3-A/C4记录已迁移，生成文件已删除且不再作为当前事实来源。
- 用户截图显示Staging Page列表共10项，其中8项已发布、2项草稿；以`Solutions`搜索未找到Page；Reading设置仍以`Home`为首页、`Blog`为文章页，并继续勾选建议搜索引擎不索引。本轮没有创建、修改、发布或删除Page，也没有更改阅读设置。
- 截图没有证明Local、Production、回收站、其他`post_type`或Slug不存在`solutions`，也没有验证匿名URL、Canonical或Sitemap。C1不创建对象，因此不把这项未完成审计扩写为数据或URL已冻结。

## A/C2 WM-A实测事实

- 用户以实际WM-A登录Staging；左侧可见文章、媒体、页面、评论和已授权的WooCommerce运营入口，不显示外观、插件、用户、工具和WordPress全局设置。
- WM-A创建并保存Page #76 `TEST D24 Page Field Walkthrough`。Slug为`test-d24-page-field-walkthrough`，C2阶段状态为Draft，作者为WM-A，默认模板、无父页面、评论关闭；正文使用Paragraph、H2和List，没有正文H1。
- 登录态访问`?page_id=76&preview=true`预览成功；页面模板输出一个页面H1，`Scope`为H2，三项列表保持列表语义。当前默认主题视觉不是最终前端验收。
- 无痕窗口在未登录WordPress时访问`?page_id=76`返回Error 404，TEST正文不可见；无痕访问`page-sitemap.xml`并搜索Slug为`0/0`，Page #76未进入Sitemap。
- WM-A直接访问`users.php`、`plugins.php`、`themes.php`、`options-general.php`和`tools.php`均被拒绝；用户与工具入口有截图，另外三项由用户逐项操作确认。
- C2当时未点击发布，未创建正式Solutions内容，也未设置特色图或正式SEO元数据；Page #76在该阶段保持明确TEST草稿。A/C3随后执行状态、发布、缓存与菜单演练，A/C4继续完成更新、修订和两类恢复；最终状态以A/C4章节为准，现为Draft。
- WordPress原生新增用户界面不能留空邮箱。CR-007不要求当前创建WM-B；若未来第二人上岗或出现权限差异、强制互审、交接、并发登记、审计或账号级插件差异，仍必须使用公司控制、唯一且可收信的邮箱或已验证公司别名建立独立账号，不得使用虚构邮箱、共享账号、Administrator、历史测试账号或改显示名替代。C2当前只对角色级能力作结论，不对第二身份或跨账号证据作通过结论。

## A/C3状态、发布、缓存与菜单实测事实

- WM-A把Page #76从Draft改为Pending Review。此时匿名精确URL仍返回404，Page Sitemap不包含该Slug；同一WM-A仍能看到并执行“发布”，实证`Pending Review`只是协作信号，不是强制互审或职责分离。
- WM-A首次发布后，精确URL返回200，Page Sitemap包含`test-d24-page-field-walkthrough`。同时发现Storefront在Primary与Handheld均未分配显式菜单时使用页面fallback，导致Page #76随其他已发布Page自动进入两处导航；这不是“自动添加新的顶级页面”复选框造成。
- 为限制公开面，先把#76撤回Draft。Sitemap立即移除该Slug，但精确URL仍返回缓存的已发布内容，响应为HTTP 200、`X-Cache: HIT`。因此只看后台状态或Sitemap不足以证明撤回完成，该情况按P0处理。
- 用户清除Breeze缓存，并在可用时同步清除Varnish；随后精确URL返回404、`X-Cache: MISS`，首页和Sitemap均不再出现#76，撤回缓存P0关闭。
- Administrator创建原生菜单#29 `TEST Staging Controlled Navigation`，项目顺序为Home、Blog、Cart、Checkout、My account、Shop；分配给Primary Menu与Handheld Menu，关闭“自动将新的顶级页面添加至此菜单”，Secondary Menu不分配。菜单不含Page #76、D12 TEST Page或Sample Page，导航fallback P1关闭。
- 受控菜单就绪后，WM-A最终重新发布#76。发布瞬时精确URL为200、`X-Cache: MISS`、`Age: 0`，Page Sitemap包含Slug，Primary与Handheld各一份且均只保留上述六项。后续只读回归中该Published页面正常转为`X-Cache: HIT`、Age约614，Last-Modified为`2026-08-21 06:36:02 GMT`；长期HIT是已发布页面的正常缓存结果，不要求持续MISS。
- A/C3技术路径通过；Page #76曾保持Published作为A/C4明确TEST夹具。A/C4结束后已安全收口为Draft。该阶段结果不是正式Solutions内容验收，也不能单独替代后来A/C5培训、C6人员验收、C7收口或Production回归；第二身份与跨账号行为仅在CR-007触发条件出现时专项补验。

## A/C4已发布更新、修订与恢复实测事实

- Published #76新增`TEST C4 low-risk update marker — 2026-08-21.`后，状态保持Published，公开URL立即显示V1；改成临时V2后修订数3→4，公开URL立即显示V2，证明原生“更新”不会保留待审公开副本。
- 原生修订界面选择WM-A于07:17形成的revision #85并恢复后，状态继续Published、修订数4→5，公开URL恢复V1且V2消失；标题、Slug、作者、模板、Sitemap与导航边界不变。
- 第一次从Published移入回收站并清缓存后，精确URL为404/MISS且无正文泄漏；在“页面 → 回收站”执行普通“恢复”得到Draft，不会自动重新公开，V1与5个修订保持。
- 为验证提示条路径，重新发布后再次移入回收站并立即点击“撤销”。后台与REST均恢复为Published，精确URL重新200、Sitemap重新包含Slug，证实该按钮可能直接重新公开，不能当成普通安全恢复。
- 最终将#76撤回Draft并清Breeze与适用的Varnish；截图确认V1、5个修订、标题、Slug、作者和模板均保持。匿名精确URL为404且无V1/V2正文，REST为401，Sitemap与Primary/Handheld均无#76。并发首轮回归后命中安全的404缓存（HIT、Age 0）不构成泄漏；旧Published正文返回200才是失败。
- A/C4共10个用例通过，开放P0/P1为0；低风险已发布更新、修订与两类恢复已写入中文手册和项目证据。高风险更新审阅草稿与有指导培训已在A/C5继续演练；C6-C7于2026-08-22按SOP辅助与实际抽样口径通过，正式内容/素材仍待，Git交接留D25。

## A/C5有指导手册与培训事实

- Page #11 `Refund and Returns Policy`只做只读风险检查。因正文仍含样例政策和未经业务批准的30天条款，已从D24正式Page样本中排除；未修改、未发布，继续保持Draft。
- Post #24 `TEST D12 Manager Published Post`作为Published源基线只读保留，A/C5未修改。它不能被扩写为已完成的正式短文样本。
- Post #90 `TEST D24 Review Copy — Manager Published Post`作为高风险更新的独立审阅Draft隔离。已引导检查正文结构、摘要、分类/标签、内链、评论与Yoast；登录态预览可见、匿名访问404、Sitemap排除，且源Post #24保持不变。授权16:9特色图仍待提供。
- Post #68 `TEST D20 Long-form Article`已在有指导状态下补充TEST警告、标签、评论、内链与Yoast，并移除不符合要求的特色图；正文内TEST图片仍保留。对象保持Draft，授权16:9特色图仍待业务提供；2026-08-22匿名`?p=68`为404且Post Sitemap排除。
- Page #76 `TEST D24 Page Field Walkthrough`保留原正文及C4标记，在有指导状态下补充内容级Yoast；继续保持Draft、无父页面、默认模板、评论关闭。它不是正式业务Page；2026-08-22匿名`?page_id=76`为404，Page Sitemap与首页均无该对象。
- A/C5当时允许的结论仅为“WM-A有指导内容操作与高风险独立审阅Draft路径已演练，技术字段覆盖建立”；不得扩写为3篇正式文章＋1个正式Page、正式素材授权或公司控制文件治理。后续C6的SOP辅助通过口径见下节及[[Day24-内容发布操作手册与WM-A培训]]。

## A/C6 SOP辅助人员验收事实

- 2026-08-22用户以项目负责人身份明确要求将C6标记通过，并确认开发人员/Website Manager无需背诵TEST对象ID、Slug、修订编号、字段示例值、按钮位置或完整点击顺序；这些信息应写入并查阅批准的中文Markdown SOP。
- C6的“独立”定义为：可以查SOP和检查清单，但由操作者自行判断对象、状态、风险分支、验证与登记；不以现场逐点击口令代替判断。遇到事实、授权、状态、URL、缓存或恢复结果不确定时必须停止并升级。
- 通过依据为A/C3～A/C5已完成的WM-A实操、截图、修订/恢复和登记证据，及当时开放P0/P1为0；本次没有补造一轮闭卷或完全无提示的WordPress后台操作。
- 2026-08-22只读公开面回归：`?p=24`为200；`?p=90`、`?p=68`和`?page_id=76`均为404；#90/#68对应Draft Slug不在Post Sitemap，#76对应Slug不在Page Sitemap且首页无#76。
- 结论：**A/C6通过（SOP辅助、项目负责人接受）**。后续A/C7也已完成；D24-B真实样本、3篇正式文章＋1个正式Page、授权16:9素材、第二账号条件性补验和Production仍未通过，公司控制Git交接留D25。

## A/C7可查SOP抽样与收口事实

- WM-A重新打开#24、#90、#68与#76并确认当前状态和用途一致：#24为Published源基线，不做高风险直接修改；#90/#68/#76均为明确TEST Draft。
- 只读公开回归确认`?p=24`为200；`?p=90`、`?p=68`、`?page_id=76`为404；匿名REST对三个Draft为401；#68/#76不进入相应Sitemap，首页不含#76。
- Page #76新增独立段落`TEST C7 temporary revision marker — 2026-08-22.`并保存草稿，修订数5→6；保存后仍为Draft且公开隔离不变。随后恢复不含C7标记的上一版本，修订数变为7；C7标记消失、既有C4标记保留，状态及Slug、作者、模板、父级、评论、Yoast填写状态等非修订字段未变化，登录态预览正常。
- Post #90独立读回为Draft：摘要、分类/标签、评论关闭、无特色图、`published source article`内链及Yoast Title/Meta均符合A/C5基线，登录态预览正常；没有改动Published源Post #24。
- C7开放P0/P1为0，**A/C7通过**。这只关闭当前TEST技术与人员抽样；3篇正式文章＋1个正式Page和授权16:9素材仍是D24整体未完成项，公司控制Git远程/备份/交接移交D25/M3。

## 登记载体与历史快照

- 唯一活动登记载体为`project-docs/CONTENT_ASSET_REGISTER.md`；后续内容发布、变更及素材授权只更新该Markdown，由WM-A维护业务记录，开发者维护结构、Git版本与交付证据。
- `DentAll-内容发布与素材授权登记.xlsx`曾包含D24 A/C3的6条与A/C4的10条记录，共16条。该文件完成当时的结构、错误扫描与视觉检查；记录迁移后生成文件已删除且不入库，不得重新生成第二套事实源或覆盖Markdown中的较新记录。
- 用户确认无需Google表格，因此已停止Google登录与云端转换；没有把文件上传到未知或个人账号。
- 当前Git远程仍由开发者个人账户控制；公司控制的远程所有权、备份、访问与交接路径最晚在D25确认。这个缺口不阻塞继续写Markdown，但阻止把登记治理写成公司已完整接管。

## 测试与验证

- 执行的检查：自定义代码内`solutions`引用只读搜索；项目文档状态与决策交叉核对；历史工作簿值/日期/表格结构检查与视觉渲染；Page #76字段保存回读、登录态预览、五类系统入口负向检查、Draft/Pending/Published状态流转、公开URL、Page Sitemap、Primary/Handheld菜单及Breeze/Varnish缓存响应回归；A/C5对象状态、隔离、字段与手册步骤逐项核对；2026-08-22对#24/#90/#68/#76匿名ID路径、Post/Page Sitemap和首页执行只读HTTP回归。
- 浏览器/设备：网站步骤由用户在Chrome/内置浏览器的实际Staging会话中操作并截图，Codex逐步核对；Codex没有取得账号密码，也没有直接控制网站或执行管理员操作。
- 通过项：Page列表数量与状态、Solutions标题搜索、Home/Blog绑定、Staging noindex、结构决定与Markdown登记边界；WM-A Page #76字段、预览、匿名隔离、权限、Pending Review、首次发布、撤回缓存、受控菜单、Published更新、修订恢复、普通恢复、立即撤销及最终Draft公开面回归；A/C5有指导手册培训，以及#11排除、#24基线不改、#90审阅Draft隔离、#68/#76引导式操作；C6按SOP辅助口径通过；C7状态/字段/预览/公开隔离/修订恢复/登记抽样通过。
- 未通过或未执行项：`solutions` Slug/回收站/公开URL全量检查、3篇正式文章＋1个正式固定Page、授权16:9素材。公司控制Git远程备份与交接为D25/M3门槛；WM-B创建、跨账号编辑/修订和多人读写属于当前不适用的条件性补验，不列为当前失败项。

## Codex Agent 调度与审查

- 今日风险等级：高；涉及内容公开状态、权限、URL、导航、缓存及授权证据。A/C3曾发现1个撤回缓存P0和1个导航fallback P1，均已在受保护Staging关闭；A/C4开放P0/P1为0；A/C5通过独立Draft避免改动Published源基线。
- 启动的Agent及职责：范围审计Agent复核D21～D23遗漏与跨文档口径；验证审计Agent设计D23状态/权限矩阵和D24样本验收边界；C2审查Agent复核Page对象复用、双账号矩阵与完成门槛；导航诊断Agent复核Storefront Primary/Handheld fallback机制；主Agent完成登记表、实测指导和中文手册修订。
- Review结果：A/C3与A/C4技术路径P0=0、开放P1=0；A/C5有指导操作按对象边界收口，#11未误作正式政策、#24未被高风险改写、#90保持审阅Draft、#68/#76保持Draft。C2按CR-007角色级范围关闭，WM-B跨账号不再是当前P1门槛。RSK-020已通过实测关闭；RSK-024继续保留只用WM-A可能漏掉账号级或多人差异的残余风险；RSK-021继续防止未来用空邮箱、虚构邮箱、共享账号或历史测试账号凑第二账号。
- 已关闭问题：Page/CPT方向、是否接受非强制互审、登记载体选型三项结构性未知已关闭。
- 延期问题及计划：A/C6-C7已通过；`CONTENT_ASSET_REGISTER.md`为唯一活动登记载体，`.xlsx`生成文件已删除且不再生成。正式3篇文章＋1个Page和授权16:9素材仍待业务输入；公司控制Git远程的所有权、备份和交接留D25/M3处理，不等待WM-B。

## 决策与范围变化

- 今日决定：Page优先、不建Solutions CPT；第一版非强制互审；`CONTENT_ASSET_REGISTER.md`作为唯一活动业务登记载体，`.xlsx`记录迁移后删除生成文件。
- 新需求：用户明确把中文操作手册修订和培训列为D24重点；它属于原D24计划范围，不新增插件或前端实现。
- 预计增加工时：当前不因结构决定增加范围；若后续要求强制职责分离、同一已发布对象保留待审新版或完整后台审计，必须另登记变更并估时。
- 是否已确认：上述结构决定、CR-007与CR-009范围调整已确认，A/C2角色级范围、A/C3-A/C4技术路径、A/C5有指导培训及A/C6-C7已通过；完整正式内容与授权素材尚未完成，公司Git交接留D25，跨账号流程仅在触发特殊情况时另行确认。

## 明确未做

- 未创建正式Solutions Page或CPT。只在受保护Staging使用明确TEST的Page #76，并为控制fallback创建临时原生菜单#29；#76现保持Draft，不能作为正式内容。除清理Staging的Breeze/Varnish缓存外，未修改Reading、Canonical、robots、WordPress/WooCommerce代码、主题、插件、角色能力、支付、物流、DNS或Production。
- 未把截图范围扩写为数据库全量审计；C6按SOP辅助与项目负责人接受收口，但未虚构闭卷或完全无提示复跑；也未把已删除`.xlsx`的历史记录扩写为公司登记治理已经验收。
- 未把WM-A角色级路径通过扩写成第二身份或跨账号通过，也未用#24、#68、#90或Page #76的TEST对象替代D24正式内容样本；未取得可用于正式特色图的授权16:9素材。

## 下一启动点

- 在业务提供真实事实与授权后完成3篇正式文章＋1个正式固定Page，并补齐授权16:9特色图；在此之前相关对象保持Draft或明确TEST，不把高风险改动直接写入Published源对象。
- 进入D25综合验收前置：继续使用`CONTENT_ASSET_REGISTER.md`作为唯一活动登记载体，确认公司控制Git远程的所有权、备份、访问与交接；不得重新生成或恢复历史`.xlsx`。
- WM-B不属于当前下一启动点。只有第二人正式上岗、角色/界面差异、强制互审、交接、多人并发登记、账号级插件差异或审计要求出现时，才重新登记范围；届时由Administrator用公司控制的唯一邮箱创建独立账号，并专项验证跨账号修订、权限负向项和交接，不共享WM-A。

## 可复用核心思想

- 跨平台不变量：结构决策、工具选型和实际可用性是三个不同验收层级。选择Git Markdown作为活动登记载体，仍不能替代公司所有权、访问权限、备份和真实记录的验证；历史快照应只读，避免双写形成分叉事实。
- WordPress/WooCommerce当前实现：`Pending Review`表达协作状态，不会自动形成同角色之间的强制职责分离；已发布内容的直接更新、普通回收站恢复和即时撤销必须分别验证公开状态。
- 导航与缓存边界：没有显式分配Primary/Handheld菜单时，主题可能fallback列出全部已发布Page；把Published改回Draft也不会主动失效所有页面缓存。状态、Sitemap、导航和精确URL响应必须作为四条独立证据链验证。
- 数据与URL治理：后台标题搜索未找到对象只是低成本预检，不能替代Slug、回收站、全部内容类型、公开URL、Sitemap和多环境审计；在尚未创建对象时，应保留这一边界而不是制造虚假的“已冻结”。
- 培训原则：操作手册必须与实际界面路径、异常分支和恢复结果同步演练；人员验收应考察能否借助受控SOP正确判断、停手、验证和留证，而不是能否背诵对象编号或按钮位置。
- Shopify或其他平台对照：同样应把内容状态、发布权限、素材授权和外部登记分层治理；具体审核、版本和恢复行为依平台能力而异，未经实测不假定与WordPress一一对应。
