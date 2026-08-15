---
项目: DentAll WooCommerce
日期: 2026-08-10
工作日: D4
周次: W1
文档类型: 配置记录与操作手册
状态: 已完成
---

# D4 Cloudways 配置与常用操作手册

## 相关笔记

- 周总结：[[W1-环境与安全试录周总结]]
- 前置代码、数据与恢复边界：[[Day3-代码数据与恢复边界]]
- 同日 WordPress 与商城配置：[[Day4-WordPress与WooCommerce配置及常用操作手册]]
- 后续编辑角色与权限：[[Day5-编辑角色与权限]]

## 本次验收结果

- [x] 建立可远程访问的 Cloudways Staging，启用 Password Protection。
- [x] HTTPS、异地备份和应用恢复点验证通过。
- [x] WordPress、Password Protection、数据库和 Redis 凭据完成轮换并复测。

## 当前配置快照

| 项目 | 当前配置 |
|---|---|
| Cloudways 产品 | Flexible |
| 底层云厂商 | DigitalOcean |
| 服务器方案 | Premium |
| 区域 | New York |
| 规格 | 约 2 vCPU、4 GB 内存、80 GB NVMe、4 TB 带宽 |
| PHP | 8.2.33，支持 64 位值 |
| 数据库 | MySQL 8.4 |
| 应用 | WordPress + WooCommerce 11.0.0 |
| 当前入口 | Cloudways 临时域名；正式 Staging 域名和 DNS 待确定 |
| HTTPS | 已通过浏览器证书验证 |
| 访问保护 | Password Protection 已开启并轮换凭据 |
| 备份 | 每日一次、保留一周、Local Backups 关闭 |
| 恢复 | 已存在可选择的应用恢复点 |
| 凭据 | WordPress、访问保护、数据库和 Redis 均已轮换；本文不记录密码 |
| SFTP 客户端 | WinSCP 6.5.6 |
| SSH 验证 | Ed25519 密钥登录已通过，私钥使用独立口令保护 |

## 先分清 Server 和 Application

- **Server（服务器）**：管理 PHP、数据库、Web 服务、资源、服务器备份和服务器级安全。
- **Application（应用）**：管理某一个 WordPress 网站的域名、SSL、访问凭据、恢复、Cron 和应用设置。
- 修改前先确认自己所在层级。服务器级操作可能影响同一服务器上的所有应用，应用级操作通常只影响当前网站。

## Server 页面地图

路径：**Flexible → Servers → 选择 `dentall-nyc-premium-4gb`**。

| 页面 | 经常查看或操作的内容 | 注意事项 |
|---|---|---|
| Master Credentials | 服务器公网 IP、主 SSH/SFTP 用户、SSH 公钥 | 权限范围大，不分享主账号；优先为协作者创建独立凭据 |
| Monitoring | CPU、内存、磁盘、流量和应用请求情况 | 网站变慢或出现 5xx 时先看这里，不要先盲目扩容 |
| Manage Services | Nginx、Apache、MySQL、PHP-FPM、Redis、Varnish 等服务状态 | 重启会造成短暂影响；必须先确认故障范围 |
| Settings & Packages → Basic | PHP 执行时间、上传大小、内存上限、错误显示、PHP 时区 | 生产环境保持 Display Error 关闭 |
| Settings & Packages → Advanced | PHP、数据库和服务器高级参数 | 没有明确原因不改；修改前记录原值 |
| Settings & Packages → Packages | 平台允许切换的软件包或组件版本 | PHP 版本不一定在所有界面直接显示；可从 WordPress 站点健康确认 |
| Settings & Packages → Optimization | 服务器性能相关设置 | 缓存策略需在功能稳定后统一制定 |
| Settings & Packages → Maintenance | 维护类设置 | 操作前先备份并确认影响范围 |
| Security | SSH/SFTP 访问和 IP 白名单 | 固定办公 IP 可加入白名单；不要无依据封锁当前管理 IP |
| Vertical Scaling | CPU、内存、磁盘扩容 | 通常会增加费用；先用 Monitoring 证明资源不足 |
| Backups | 自动备份时间、频率、保留期、本地备份、立即备份 | 当前每日一次、保留一周；重要改动前执行 On-Demand Backup |
| SMTP | 服务器邮件服务接入 | D4 未配置；正式邮件测试前不能假设订单邮件可送达 |

## Application 页面地图

路径：**Flexible → My Applications → 选择 DentAll 应用**。

| 页面 | 经常查看或操作的内容 | 注意事项 |
|---|---|---|
| Access Details | 临时网址、后台入口、数据库、Redis、Password Protection、应用凭据 | 含敏感信息；禁止截图、粘贴到聊天或写进 Git |
| Domain Management | 绑定正式 Staging 或正式域名 | DNS 未准备好时不要提前替换；切域名前确认回滚方法 |
| Site Manager | Cloudways 提供的网站管理入口 | 可用于查看站点概况；具体 WordPress 配置仍以后台为准 |
| Cron Job Management | 查看和设置定时任务 | WooCommerce Action Scheduler 与系统 Cron 不完全相同，不要随意删除任务 |
| SSL Certificate | 安装或续期 Let's Encrypt、自定义证书 | 绑定域名并解析正确后再申请正式域名证书 |
| Backup and Restore | 选择恢复点并恢复应用文件或数据库 | 恢复会覆盖当前状态；恢复前再做一次即时备份并确认恢复范围 |
| Deployment via GIT | 配置 Git 部署 | D4 未启用；上线流程确定前不要直接连接生产分支 |
| Application Settings | HTTPS 跳转、访问状态、应用级 PHP/缓存行为等 | 每次只改一个设置，保存后立即验证前后台 |
| Web Rules | 重定向和 Web 访问规则 | 会影响 URL、SEO 和缓存；修改前记录需求与测试用例 |
| Cloudflare | Cloudways/Cloudflare 集成功能 | 当前未接入；接入后要同时检查 DNS、SSL 和缓存 |
| Migration Tools | 从其他主机迁移网站 | 本项目从零开始，目前不需要迁移 |

## 最常用操作

### 查看网站和后台入口

1. 打开应用的 **Access Details**。
2. `Application URL` 是前台入口，`Admin Panel` 是 WordPress 后台入口。
3. 不要把该页的密码复制到项目文档。

### 使用 SFTP 查看 WordPress 文件

1. 打开 WinSCP 中已保存的 DentAll Cloudways 会话，文件协议选择 SFTP、端口使用 `22`。
2. 使用 Cloudways 对应 SSH/SFTP 凭据的用户名；不能把 WordPress 后台账号当作 SSH 用户名。
3. 在 **高级 → SSH → 验证**中选择本机 `.ppk` 私钥，登录时输入私钥口令。
4. 进入 `public_html`。
5. 插件目录为 `public_html/wp-content/plugins/`。
6. 主题目录为 `public_html/wp-content/themes/`。
7. 上传媒体通常在 `public_html/wp-content/uploads/`。
8. 禁止直接修改 WordPress、WooCommerce、父主题或第三方插件核心文件。

### SSH 密钥的创建与维护

1. WinSCP 安装目录自带 PuTTYgen；本次使用 EdDSA/Ed25519 生成密钥。
2. 公钥添加在 Cloudways 对应凭据的 **SSH Public Keys / View SSH Keys** 页面。
3. 私钥保存在本机 `.ssh` 目录并设置独立口令；私钥、口令不得上传到服务器、网盘、聊天或 Git。
4. WinSCP 会话不保存服务器密码；通过私钥加口令完成验证。
5. 更换电脑时应安全迁移私钥，或为新电脑生成新密钥并在 Cloudways 添加新公钥。
6. 电脑丢失、私钥疑似泄漏或人员离开时，立即从 Cloudways 删除对应公钥并生成新密钥。

### 重要改动前立即备份

1. 服务器 → **Backups**。
2. 点击 **Take Backup Now**。
3. 等待备份完成，再开始升级、恢复、域名、SSL 或数据库相关操作。

### 恢复应用

1. 应用 → **Backup and Restore**。
2. 选择明确的恢复时间点和恢复范围。
3. 恢复会覆盖较新的内容；先确认是否存在新订单、用户或编辑内容。
4. 恢复后检查首页、后台、插件、数据库和固定链接。

### 检查服务器负载

1. 服务器 → **Monitoring**。
2. 重点看 CPU、空闲内存、磁盘、带宽和请求异常。
3. 结合 WordPress 错误日志与慢请求判断原因，再决定优化或扩容。

### 清缓存的原则

- 页面修改不生效时，先确认是否保存成功，再清应用缓存或 Varnish。
- 清缓存只解决旧内容残留，不会修复代码、数据库或配置错误。
- 支付、购物车、结账和账户页必须避免被整页缓存；缓存策略在后续专门配置。

### 域名与 SSL 的正确顺序

1. 确认域名所有权和 DNS 访问权限。
2. 在 Domain Management 添加域名。
3. 修改 DNS 指向 Cloudways。
4. 等待解析生效。
5. 在 SSL Certificate 申请证书。
6. 验证 HTTPS 后再开启强制 HTTPS。
7. 最后检查 WordPress 地址、站点地址、重定向和 noindex。

## 故障排查入口

| 现象 | 第一检查位置 | 下一步 |
|---|---|---|
| 网站变慢 | Server → Monitoring | 检查 CPU、内存、磁盘及请求；再看 WordPress 插件与日志 |
| 502/503 | Manage Services、Monitoring | 查看 PHP-FPM/Web 服务状态；不要连续重启所有服务 |
| 数据库连接错误 | Access Details、Manage Services | 核对数据库服务与凭据变更；不要把密码发到聊天 |
| 登录前没有访问保护 | Application → Access Details → Password Protection | 确认开关和新无痕窗口测试 |
| HTTPS 警告 | SSL Certificate、Domain Management | 检查证书域名、DNS 和过期时间 |
| 页面还是旧内容 | Application Settings/缓存入口 | 保存后清缓存，再核对浏览器缓存 |
| 误改或升级失败 | Backup and Restore | 先判断文件恢复还是数据库恢复，避免覆盖新数据 |
| SFTP 找不到插件 | `public_html/wp-content/plugins/` | 确认进入的是正确应用目录 |

## 安全禁区

- 不在笔记、截图、聊天、Git 或工单正文中记录明文密码。
- 不向编辑人员提供 Master Credentials、数据库或 Redis 凭据。
- 未经确认不切换 DNS、不连接真实支付、不恢复数据库、不扩大服务器规格。
- 不在没有备份和回滚方案时升级 PHP、数据库或关键插件。
- 不把 Password Protection 当成 WordPress 用户权限；两者是两层独立保护。

## D4 测试与验证

- Password Protection：未授权访问被拦截；轮换后新凭据可用。
- HTTPS：临时域名前后台均为 `https://`，浏览器无证书警告。
- 备份：每日一次、保留一周，已生成应用恢复点。
- 数据库与 Redis：轮换后前台、后台、页面列表和 Object Cache Pro 均正常。
- SSH/SFTP：WinSCP 6.5.6 使用 Ed25519 私钥登录成功，可查看应用级 `public_html` 等目录，未执行远程文件修改。
- 未验证：正式域名、DNS、正式域名证书、SMTP、真实支付、生产缓存策略。

## 后续维护频率

- 每周：查看备份是否持续生成、磁盘使用量和异常告警。
- 重要发布前：立即备份，并记录恢复点时间。
- 每月：检查服务器负载、PHP/数据库支持状态、SSL 到期情况和访问账号。
- 人员变动时：立即撤销或轮换相关 WordPress、SFTP/SSH 和访问保护凭据。

## 可复用核心思想

- 托管平台通常分为Server与Application两层：服务器管运行资源和服务，应用管域名、SSL、备份、部署和站点访问；排障先判断问题属于哪一层。
- Staging安全是分层防护：访问保护阻挡访客，WordPress权限限制后台动作，`noindex`降低搜索引擎收录风险，关闭真实支付隔离交易风险。
- `noindex`不是访问控制，Password Protection也不是WordPress权限；不同安全层解决不同问题，不能互相替代。
- 改密码或密钥后的完成标准不是“点击保存”，而是新凭据可用、旧凭据失效、网站与依赖服务仍正常。
- 重要变更前创建恢复点，并提前知道恢复入口。没有回滚路径的升级和配置修改，不应直接进入生产。
- SSH/SFTP应使用个人密钥和最小作用域，人员变动时撤销访问；共享主账号会破坏审计能力。
