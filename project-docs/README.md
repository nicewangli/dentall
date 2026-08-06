# DentAll 项目文档索引

本目录是项目事实来源。Codex、开发者和编辑人员应优先以这里的已确认内容为准；聊天记录、口头说明和旧截图不能覆盖已确认文档。

## 开始任务前读取

1. `../AGENTS.md`：长期开发规范。
2. `PROJECT_CONTEXT.md`：目标、范围和角色。
3. `PROJECT_STATE.md`：当前进度、阻塞和下一步。
4. 与任务相关的专项文档，例如数据、SEO、测试或发布清单。

## 文档职责

| 文件 | 维护内容 | 更新时机 |
|---|---|---|
| `PROJECT_CONTEXT.md` | 项目目标、范围、角色、假设 | 项目方向或范围改变 |
| `REQUIREMENTS.md` | Must/Should/Could/不包含、验收标准 | 需求确认或变更批准 |
| `ARCHITECTURE.md` | 技术结构、环境、代码边界 | 技术方案改变 |
| `CODEX_WP_WC_RULES.md` | Codex 生成 WordPress/WooCommerce 代码时必须遵守的实现、显示和验收规则 | 工程规范或前端基线改变 |
| `DATA_DICTIONARY.md` | 商品、属性、变体、ACF和内容字段 | 数据模型改变前 |
| `EDITOR_WORKFLOW.md` | 编辑账号、商品/文章试录、审核和批量录入门槛 | 编辑流程或字段改变前 |
| `URL_SEO_MAP.md` | URL、索引、Canonical和重定向 | 页面或URL改变前 |
| `PROJECT_STATE.md` | 实际进度、阻塞、下一步 | 每天下班、每周第6天 |
| `CHANGE_REQUESTS.md` | 新增、修改、删除需求及工时影响 | 每次范围变化 |
| `DECISIONS.md` | 已批准的关键决策及原因 | 关键选择确认后 |
| `CHANGELOG.md` | 每个发布版本的变化 | 每次发布 |
| `TEST_PLAN.md` | 测试矩阵和发布门槛 | 功能或风险改变 |
| `PLUGIN_INVENTORY.md` | 插件用途、版本、许可证和替代方案 | 安装/更新/删除插件 |
| `LOCALWP_AND_GIT.md` | LocalWP真实路径、Git边界和插件同步规则 | 本地/Git方案改变 |
| `RISK_REGISTER.md` | 风险、概率、影响和应对 | 每周检查 |
| `RUNBOOK.md` | 部署、故障、缓存、备份和恢复 | 运维流程改变 |
| `RELEASE_CHECKLIST.md` | 上线前后检查和证据 | 每次发布 |

历史计划和失效文档统一放在`archive/`，不得作为当前执行基线。

## 状态和优先级

- 状态：`Inbox → Needs clarification → Ready → In progress → Review → Staging → Awaiting approval → Done`
- P0：生产故障、安全、数据丢失或交易阻塞。
- P1：当前里程碑核心任务或高影响缺陷。
- P2：正常计划任务。
- P3：增强、清理或后续版本。

只有通过验收标准并留下验证证据，任务才能标记为 `Done`。
