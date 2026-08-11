# 技术架构与工程边界

## 总体结构

```text
浏览器
  ↓ HTTPS
Web服务器/CDN（待确认）
  ↓
WordPress
  ├─ 自定义子主题：页面结构、样式和响应式
  ├─ WooCommerce：商品、库存、购物车、订单和结账
  ├─ 项目定制插件或mu-plugin：与主题无关的业务逻辑
  ├─ 必要第三方插件：SEO、缓存、SMTP、支付、备份等
  ├─ 数据库：配置、内容、商品、客户和订单
  └─ uploads：商品图、文章图和下载文件
```

## 代码职责

| 内容 | 推荐位置 | 原则 |
|---|---|---|
| 页面展示和响应式 | 子主题 | 不把业务规则硬塞进模板 |
| WooCommerce模板调整 | 子主题`woocommerce/`覆盖 | 记录覆盖版本，升级时复查 |
| hooks、短代码和业务规则 | 项目定制插件 | 与主题切换解耦 |
| 必须始终启用的安全/环境逻辑 | mu-plugin | 保持极小和可审计 |
| ACF字段 | ACF JSON或PHP注册 | 必须进入版本控制 |
| 密钥和生产配置 | 环境变量/主机密钥系统 | 禁止写入Git |
| 商品与内容 | 数据库 | 通过备份、导入或迁移流程同步 |
| 媒体文件 | uploads/对象存储 | 不提交普通Git |

## 环境

| 环境 | 用途 | 规则 |
|---|---|---|
| Local | 开发和单元验证 | 使用脱敏或测试数据，不调用真实支付 |
| Staging | 编辑录入、联调和验收 | 密码保护、`noindex`、支付沙盒 |
| Production | 真实业务 | 禁止调试输出；变更必须有备份和回滚 |

## 开发原则

- 不修改WordPress、WooCommerce、父主题和第三方插件核心文件。
- PHP输入清洗验证、输出按上下文转义；后台动作检查nonce和capability。
- SQL使用WordPress API或预处理查询。
- 所有自定义查询考虑分页、缓存和空结果。
- 所有外部请求设置超时、失败处理和日志，不能阻塞结账主流程。
- 重要设置应可导出、可记录或通过脚本重建，减少只存在数据库里的“隐形配置”。

## 环境与版本待确认

| 项目 | 当前值 | 建议/约束 |
|---|---|---|
| PHP | 8.2.29 | LocalWP本地版本；插件引入前检查兼容性 |
| 数据库 | MySQL 8.4.0 | LocalWP本地版本；明确字符集和备份方案 |
| Web服务器 | Nginx 1.26.1 | LocalWP本地版本；Cloudways实际版本D4补充 |
| WordPress | 7.0.3 | 已安装；升级先走Staging |
| WooCommerce | 11.0.0 | 已安装并激活；记录模板兼容性 |
| 托管平台 | Cloudways，配置已选定，待正式购买 | D2/D4记录实际PHP、数据库、Web服务器、缓存和日志位置 |
| Node构建工具 | 待确认 | 只有主题构建需要时引入并锁定版本 |
| Composer | 待确认 | 只有采用依赖管理时引入并提交lock文件 |

## 代码仓库建议

- 仓库根目录使用`D:\LocalWP\dentall`；WordPress根目录是`app/public`。
- 设计PNG、ZIP、数据库和uploads不进入普通Git历史。
- 提交`.env.example`，忽略`.env`和`wp-config.php`中的真实密钥。
- 提交根目录项目文档、自定义主题、`dentall-core`定制插件、mu-plugin、构建配置和测试。
- 是否跟踪WordPress核心文件在安装方案确定后决定；不要混合两种策略。

## 发布架构原则

- 代码发布、数据库变化和媒体同步分开记录。
- Staging代码通过Cloudways Via Git从`deploy/staging`部署；该分支根目录为`wp-content/`且只包含DentAll运行代码。
- `main`保留完整代码、测试和项目文档，不直接部署到Cloudways Web根目录。
- Cloudways Deploy Key保持只读；标准Deployment Path为`public_html/`。
- SFTP仅作为排查和应急手段；D25前后再评估GitHub Actions＋SSH/rsync自动化。
- 每次发布绑定Git标签、数据库备份、uploads快照和插件版本清单。
- 缓存清理必须在部署清单中显式执行。
- 数据库搜索替换必须先备份，并使用可处理序列化数据的安全工具。
