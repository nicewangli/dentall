# 部署、运维与恢复手册

> 当前Staging已确定使用Cloudways Flexible；标准代码部署采用Cloudways Via Git与代码专用部署分支。Production部署规则在正式上线准备阶段冻结。

## 环境信息

| 项目 | Local | Staging | Production |
|---|---|---|---|
| URL | LocalWP本地域名（以应用内显示为准） | 待确认 | 待确认 |
| 主机 | 本机 | Cloudways Flexible / DigitalOcean Premium 4GB | 待确认 |
| PHP/数据库 | PHP 8.2.29 / MySQL 8.4.0 | PHP 8.2.33 / MySQL 8.4 | 待确认 |
| 部署方式 | 本地开发 | Cloudways Via Git：`deploy/staging` → `public_html/` | 待确认 |
| 日志位置 | `app/public/wp-content/debug.log`及LocalWP站点日志 | 待确认 | 待确认 |
| 备份位置 | `backups/`（仅本地基线，不作为唯一副本） | 待确认 | 待确认 |

## Staging代码部署边界

- 完整项目历史保存在`main`；Cloudways只部署代码专用分支`deploy/staging`。
- 部署分支根目录必须从`wp-content/`开始，只包含DentAll自定义主题、`dentall-core`和已确认的mu-plugin。
- Cloudways仓库地址使用SSH；Deploy Key只读，不允许Cloudways向GitHub推送。
- Deployment Path固定为`public_html/`；首次部署前必须检查恢复点并核对部署分支文件清单。
- 数据库、uploads、WordPress核心、WooCommerce和第三方插件不通过Git同步。
- SFTP只用于只读排查、紧急回滚或已批准的应急发布；应急修改必须回补Local和Git，避免服务器漂移。
- 后续GitHub Actions＋rsync方案只有在密钥、目录白名单、删除策略、人工批准和回滚测试全部完成后才可替代当前流程。

## 标准发布流程

1. 确认发布范围、版本号和变更记录。
2. 确认P0/P1缺陷为0，关键回归通过。
3. 备份生产数据库和uploads，并验证文件存在和可读取。
4. 记录当前Git标签、WordPress、WooCommerce、主题和插件版本。
5. 在Staging使用相同发布包完成部署和冒烟测试。
6. 进入已批准的生产发布窗口。
7. 部署代码；执行经过审核的数据库变化或安全搜索替换。
8. 清理应用、页面、对象和CDN缓存。
9. 验证首页、商品、购物车、结账、账户、邮件和支付回调。
10. 记录证据并观察日志、性能和错误。

## 回滚触发条件

- 无法浏览核心页面或完成下单。
- 金额、库存、订单状态或支付出现错误。
- 登录、权限或客户数据存在安全问题。
- 数据库迁移失败或数据不一致。
- 错误率持续上升且无法在发布窗口内安全修复。

## 回滚流程

1. 停止继续部署和写入性操作，记录故障时间。
2. 根据风险决定是否短暂启用维护页，避免新订单进入错误状态。
3. 恢复上一个稳定Git标签或发布包。
4. 仅在确认数据库受影响时恢复数据库备份；先保存故障现场副本。
5. 必要时恢复uploads快照。
6. 清理全部缓存并重新验证关键流程。
7. 记录根因、影响订单、处理人和后续修复计划。

### 角色权限变更的回滚规则

- DentAll Core的角色能力会写入WordPress数据库。仅回滚PHP文件或降低`DENTALL_CORE_ROLE_VERSION`，不能可靠撤销已经授予的能力；旧代码白名单仍包含该能力时还会再次授予。
- 若发布后需要撤销Website Manager的某项能力，必须从角色白名单移除该能力，并把`DENTALL_CORE_ROLE_VERSION`提升到新的、单调递增的版本，再按正常部署与角色审计流程发布。禁止通过把角色版本号从`6`降回`5`完成撤权。
- 紧急止血必须临时从角色对象移除能力，而不是使用只会处理用户直授权的`wp cap remove`。经过审核的WP-CLI可执行`wp eval "get_role( 'dentall_website_manager' )->remove_cap( 'wpseo_edit_advanced_metadata' );"`，随后立即以目标Website Manager账号确认`current_user_can( 'wpseo_edit_advanced_metadata' )`为`false`，并复测其他白名单能力未丢失。同一发布窗口内仍必须补上版本化白名单修复；插件重新激活或角色再次同步前，不得把这项临时撤权视为永久完成。
- D18 C6若需撤销`wpseo_edit_advanced_metadata`，回滚包应基于当前稳定代码创建新版本：从Website Manager白名单移除该能力、提升插件及角色版本、运行角色与越权审计后再部署；普通`0.2.3`代码回滚只用于撤销界面隐藏和商品导出逻辑，不承担高级SEO撤权。

## 常见故障检查顺序

### 白屏/500

1. 查看PHP和Web服务器错误日志。
2. 检查最近代码/插件/主题变更。
3. 在可控环境复现，禁止直接在生产反复试错。
4. 回滚最近发布或停用明确故障组件。

### 购物车丢失或结账异常

1. 检查页面缓存是否缓存了购物车、结账和账户。
2. 检查Cookie、Session、对象缓存和CDN规则。
3. 检查支付/运费插件日志和WooCommerce状态页面。
4. 使用匿名和登录用户分别复现。

### 订单邮件未送达

1. 确认订单状态是否触发相应邮件。
2. 检查WooCommerce邮件配置和SMTP日志。
3. 检查发件域名、SPF、DKIM、DMARC和垃圾箱。
4. 不要通过重复创建真实订单盲测。

### 定时任务未执行

1. 检查WP-Cron和服务器Cron配置。
2. 检查WooCommerce Scheduled Actions的失败任务。
3. 查看任务日志、超时和并发锁。

## 备份策略

- 数据库：至少每日自动备份；发布前手动备份。
- uploads：增量备份，重大素材迁移前创建快照。
- 代码：Git远程仓库和版本标签。
- 配置：插件清单、环境版本、DNS/CDN/服务器配置说明。
- 保留周期：业务方和合规要求确认后填写。
- 每月至少一次恢复抽查；正式上线前必须完整恢复演练。

## 生产操作记录模板

- 操作ID：OPS-XXX。
- 操作时间：
- 操作人：
- 业务原因：
- 影响范围：
- 操作前备份：
- 执行步骤：
- 验证结果：
- 回滚是否需要：
- 相关日志、提交和截图：
