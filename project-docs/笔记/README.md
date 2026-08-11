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

## D1～D4

| 工作日 | 笔记 | 内容 |
|---|---|---|
| Day1 | [[Day1-范围与访问依赖基线]] | 第一版范围、访问依赖和风险基线 |
| Day2 | [[Day2-本地环境与WooCommerce基线]] | LocalWP、WordPress、WooCommerce 与本地环境基线 |
| Day3 | [[Day3-代码数据与恢复边界]] | Git、代码、数据、密钥、备份和恢复边界 |
| Day4 | [[Day4-Cloudways配置与常用操作手册]] | Cloudways Staging、备份、安全、SFTP/SSH 与运维路径 |
| Day4 | [[Day4-WordPress与WooCommerce配置及常用操作手册]] | WordPress/WooCommerce 配置、菜单地图和排错路径 |
