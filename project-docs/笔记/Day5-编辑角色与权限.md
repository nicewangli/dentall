---
项目: DentAll WooCommerce
日期: 2026-08-11
工作日: D5
周次: W1
计划工时: 6小时50分钟有效工作
实际工时: 待收工记录
状态: 进行中
---

# DentAll 每日复盘 D5

## 相关笔记

- 前置笔记：[[Day4-WordPress与WooCommerce配置及常用操作手册]]
- 同主题笔记：[[Day4-Cloudways配置与常用操作手册]]
- 后续笔记：任务完成后回填

## 今日三个验收结果

- [x] 冻结D6内容试录员的最小权限与SEO/商城职责边界。
- [x] 在Local建立非管理员测试账号并配置最小媒体规则。
- [ ] 将同一实现部署到受保护Staging，并用非管理员账号完成后台浏览器走查。

## 专注周期记录

| 周期 | 计划 | 实际结果 | 证据/文件 | 用时 |
|---|---|---|---|---:|
| C1 | 盘点角色、插件和需求边界 | 内置Editor与Shop manager均越权，SEO插件未选型 | `PROJECT_STATE.md`、`PLUGIN_INVENTORY.md` | 待记录 |
| C2 | 设计能力白名单 | 冻结本人文章/商品草稿与媒体边界 | `dentall-core.php` | 待记录 |
| C3 | 实现项目业务插件 | 新增并激活DentAll Core 0.1.2 | `app/public/wp-content/plugins/dentall-core/` | 待记录 |
| C4 | 建本地测试账号 | 创建`dentall_d6_editor`，随机密码未落盘 | Local数据库 | 待记录 |
| C5 | 自动化权限审计 | 48项能力、对象所有权、媒体、商品标签及后台入口检查通过 | `project-docs/tests/day5-role-audit.php` | 待记录 |
| C6 | 独立Review与修复 | P0/P1为0；已修复角色同步失败重试和P3能力项 | Code Review、安全与测试Agent | 待记录 |
| C7 | Staging走查与回归 | 待安全解锁WinSCP会话后执行 | 受保护Staging | 待记录 |

## 测试与验证

- 执行的命令：PHP语法检查、`wp plugin status`、`wp user get`、`wp cap list`、`wp eval-file project-docs/tests/day5-role-audit.php`、插件停用/重新激活幂等检查。
- 浏览器/设备：Local后台已完成菜单、关键入口、商品标签隐藏和5MB上传提示回归；Staging非管理员浏览器走查尚未完成。
- 通过项：插件语法；角色创建；8项能力白名单；本人/他人文章与商品对象级权限；媒体MIME和5MB过滤；临时对象清理。
- 已修复项：移除内容试录员的商品标签输入框并在服务端拒绝其创建商品标签；媒体界面显示上限与服务端5MB规则保持一致。
- 已验证限制：直接访问用户和插件管理页会被拒绝；WooCommerce任务中心的商品入口因需要`manage_woocommerce`而拒绝，但经典商品列表与编辑入口可用。
- Staging首轮菜单走查：核心权限符合预期；发现WordPress默认显示评论和工具入口，已在0.1.2中增加菜单移除与直接URL 403拦截，等待再次Pull后回归。
- 未验证项：其余直接URL/REST越权、真实JPEG/PNG/WebP上传、SVG/伪装MIME拒绝、草稿预览及Staging部署。

## Codex Agent 调度与审查

- 今日风险等级：高。
- 风险判断理由：涉及WordPress后台角色、内容保存和文件上传边界。
- 启动的Agent及职责：Code Review Agent检查兼容性与生命周期；安全Agent检查越权和上传；测试Agent检查正常、失败与边界用例。
- Review结果：P0 0、P1 0；初审P2 2、P3 1～2。角色同步P2和能力白名单P3已修复；真实上传与浏览器/REST P2仍待Staging运行时验证。
- 延期问题及计划：Staging浏览器走查待部署后完成，本日不得据此标记Done。
- 剩余风险或未验证项：附件对象级权限、REST/直接URL和真实上传仍需运行时验证。

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
- 新需求：无。
- 预计增加工时：无。
- 是否已确认：属于D5既定范围的最小实现。

## 问题与风险

- 阻塞：无；Cloudways Via Git已通过只读Deploy Key连接GitHub，等待部署前提交和人工点击。
- 技术债：SEO插件选型后需单独验证元数据权限；D19前再评估页面草稿权限。
- 需要他人提供：无；Deploy Key保持只读，任何密码、私钥或验证码均不得写入聊天和项目文件。

## 今日复盘

- 完成：Local最小角色、测试账号、媒体规则、45项权限审计、两个浏览器P2修复，以及Cloudways Via Git只读连接和部署分支准备。
- 未完成及原因：尚未执行首次Staging部署及部署后非管理员浏览器走查。
- 实际工时与计划偏差：待收工记录。
- 今天学到的内容：WooCommerce原生Shop manager不能直接作为内容试录角色；SEO插件未选型时不能宣称插件权限已落地。

## 明日启动点

- 明日第一件事：先完成Staging部署和非管理员走查，再进入D6商品与文章安全试录。
- 需要提前准备：准备1张合规测试图片；部署前确认Cloudways分支与路径。
