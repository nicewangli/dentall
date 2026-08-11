---
项目: DentAll WooCommerce
日期: 2026-08-11
工作日: D5
周次: W1
计划工时: 6小时50分钟有效工作
实际工时: 待收工记录
状态: 已完成
---

# DentAll 每日复盘 D5

## 相关笔记

- 前置笔记：[[Day4-WordPress与WooCommerce配置及常用操作手册]]
- 同主题笔记：[[Day4-Cloudways配置与常用操作手册]]
- 后续笔记：[[Day6-商品与文章安全试录]]

## 今日三个验收结果

- [x] 冻结D6内容试录员的最小权限与SEO/商城职责边界。
- [x] 在Local建立非管理员测试账号并配置最小媒体规则。
- [x] 将同一实现部署到受保护Staging，并用非管理员账号完成后台浏览器走查。

## 专注周期记录

| 周期 | 计划 | 实际结果 | 证据/文件 | 用时 |
|---|---|---|---|---:|
| C1 | 盘点角色、插件和需求边界 | 内置Editor与Shop manager均越权，SEO插件未选型 | `PROJECT_STATE.md`、`PLUGIN_INVENTORY.md` | 待记录 |
| C2 | 设计能力白名单 | 冻结本人文章/商品草稿与媒体边界 | `dentall-core.php` | 待记录 |
| C3 | 实现项目业务插件 | 新增并激活DentAll Core 0.1.2 | `app/public/wp-content/plugins/dentall-core/` | 待记录 |
| C4 | 建本地测试账号 | 创建`dentall_d6_editor`，随机密码未落盘 | Local数据库 | 待记录 |
| C5 | 自动化权限审计 | 48项能力、对象所有权、媒体、商品标签及后台入口检查通过 | `project-docs/tests/day5-role-audit.php` | 待记录 |
| C6 | 独立Review与修复 | P0/P1为0；已修复角色同步失败重试和P3能力项 | Code Review、安全与测试Agent | 待记录 |
| C7 | Staging走查与回归 | Via Git部署成功；菜单、直接URL、草稿、预览、上传与清理通过 | 受保护Staging、Deployment Logs | 待记录 |

## 测试与验证

- 执行的命令：PHP语法检查、`wp plugin status`、`wp user get`、`wp cap list`、`wp eval-file project-docs/tests/day5-role-audit.php`、插件停用/重新激活幂等检查。
- 浏览器/设备：Local与受保护Staging后台均完成菜单、关键入口、商品标签、草稿、预览和5MB上传边界走查。
- 通过项：插件语法；角色创建；8项能力白名单；本人/他人文章与商品对象级权限；媒体MIME和5MB过滤；临时对象清理。
- 已修复项：移除内容试录员的商品标签输入框并在服务端拒绝其创建商品标签；媒体界面显示上限与服务端5MB规则保持一致。
- 已验证限制：直接访问用户和插件管理页会被拒绝；WooCommerce任务中心的商品入口因需要`manage_woocommerce`而拒绝，但经典商品列表与编辑入口可用。
- Staging首轮菜单走查：核心权限符合预期；发现WordPress默认显示评论和工具入口，0.1.2增加菜单移除与直接URL 403拦截，Pull后回归通过。
- Staging直接URL验证：用户、插件、工具、评论、设置和WooCommerce订单入口均被拒绝，菜单隐藏与服务端权限边界一致。
- Staging内容验证：本人文章与简单商品均可保存草稿和预览，只能提交复审、不能发布；小于5MB的JPG上传成功，大于5MB的JPG和PDF被拒绝，界面显示5MB上限；内容试录员可删除自己的测试草稿和媒体，管理员已完成最终清理。
- 未验证项：REST越权、PNG/WebP真实上传、SVG/伪装MIME拒绝，转入后续媒体专项回归，不阻塞D6安全试录。

## Codex Agent 调度与审查

- 今日风险等级：高。
- 风险判断理由：涉及WordPress后台角色、内容保存和文件上传边界。
- 启动的Agent及职责：Code Review Agent检查兼容性与生命周期；安全Agent检查越权和上传；测试Agent检查正常、失败与边界用例。
- Review结果：P0 0、P1 0；初审P2和P3均已修复或通过Staging回归关闭。REST及更多媒体格式作为后续专项验证项保留。
- 延期问题及计划：无D5阻塞项；PNG/WebP、SVG/伪装MIME和REST边界进入后续媒体与接口测试。
- 剩余风险或未验证项：尚未做REST专项审计和全部允许/拒绝格式的真实文件矩阵。

## 重点学习：`deploy/staging`分支是如何创建的

### 1. 为什么不直接部署`main`

`main`是完整工作室，包含代码、项目文档、测试脚本和学习笔记；Cloudways只需要网站运行代码。`deploy/staging`相当于发货专用箱，根目录只保留`wp-content/`，因此部署到`public_html/`后会形成正确的`public_html/wp-content/`路径。

```text
main: app/public/wp-content/... → deploy/staging: wp-content/... → Cloudways: public_html/wp-content/...
```

### 2. 首次创建分支时实际执行的操作

以下PowerShell命令使用Git底层对象创建独立部署历史，不切换当前工作目录，也不会删除`main`中的文档：

```powershell
$deployTree = git rev-parse "main:app/public"
$deployCommit = "部署：建立Staging代码专用分支`n" | git commit-tree $deployTree
git update-ref refs/heads/deploy/staging $deployCommit
git push origin refs/heads/deploy/staging:refs/heads/deploy/staging
```

- `git rev-parse "main:app/public"`：找到`main`中可部署目录对应的Git目录快照。
- `git commit-tree`：把该快照封装成一个不继承`main`文档历史的部署提交。
- `git update-ref`：让本地`deploy/staging`分支指向该部署提交。
- `git push`：把部署分支发布到GitHub，Cloudways随后通过`Fetch`发现它。

### 3. 以后更新部署分支

先把代码正常提交到`main`，再创建一个以上次部署提交为父提交的新部署快照：

```powershell
$deployTree = git rev-parse "main:app/public"
$deployCommit = "部署：更新Staging代码`n" | git commit-tree $deployTree -p deploy/staging
git update-ref refs/heads/deploy/staging $deployCommit
git push origin refs/heads/deploy/staging:refs/heads/deploy/staging
```

更新后的固定顺序是：`main提交 → 自动/人工测试 → 更新deploy/staging → Cloudways Fetch → Start deployment → Staging回归`。禁止把数据库、uploads、密钥、WordPress核心、第三方插件或项目笔记装进部署分支。

## 决策与范围变化

- 今日决定：D6只开放本人文章与商品草稿；暂不开放页面、发布、全局分类/属性管理、PDF或SVG上传。
- 扩权原则：保留后续按业务动作逐项扩大权限的能力，不把当前角色边界视为永久冻结；每次扩权需明确是发布、编辑他人内容、管理分类/属性还是商城管理，并同步角色版本、菜单、直接URL和自动测试。订单、退款、客户、用户、插件、主题和设置属于高风险权限，必须单独确认后再进入Staging验证。
- 新需求：无。
- 预计增加工时：无。
- 是否已确认：属于D5既定范围的最小实现。

## 问题与风险

- 阻塞：无；Cloudways Via Git已通过只读Deploy Key连接GitHub，首次部署及后续Pull均成功。
- 技术债：SEO插件选型后需单独验证元数据权限；D19前再评估页面草稿权限。
- 需要他人提供：无；Deploy Key保持只读，任何密码、私钥或验证码均不得写入聊天和项目文件。

## 今日复盘

- 完成：Local最小角色、测试账号、媒体规则、48项权限审计、浏览器P2修复、Cloudways Via Git部署，以及Staging非管理员端到端走查和数据清理。
- 未完成及原因：无D5阻塞项；扩展媒体格式与REST矩阵按风险转入后续专项验证。
- 实际工时与计划偏差：待收工记录。
- 今天学到的内容：WooCommerce原生Shop manager不能直接作为内容试录角色；SEO插件未选型时不能宣称插件权限已落地。

## 明日启动点

- 明日第一件事：让编辑人员在受保护Staging独立试录1个简单商品和1篇文章，观察而不代操作。
- 需要提前准备：1个代表性简单商品资料、1篇短文章素材和30分钟编辑试录窗口。
