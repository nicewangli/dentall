# LocalWP与Git工作区说明

## 真实路径

- LocalWP项目根目录：`D:\LocalWP\dentall`。
- WordPress根目录：`D:\LocalWP\dentall\app\public`。
- `wp-content`：`D:\LocalWP\dentall\app\public\wp-content`。
- Git建议根目录：`D:\LocalWP\dentall`。

项目管理文档放在LocalWP项目根目录和`project-docs/`，不得放进`wp-content`。

## 进入Git

- 根目录README、AGENTS、计划和项目文档。
- 自定义主题/子主题，例如`app/public/wp-content/themes/dentall`。
- 项目插件`app/public/wp-content/plugins/dentall-core`。
- `app/public/wp-content/mu-plugins`中的项目代码。
- ACF JSON或PHP字段定义。
- 自定义CSS、JavaScript、SVG、构建配置和测试。
- 不含密钥的`.env.example`。

## 不进入Git

- LocalWP生成的`conf/`、`logs/`和数据库文件。
- WordPress核心、WooCommerce和第三方插件。
- 父主题、uploads、缓存、语言包、升级临时文件和备份。
- `.env`、真实`wp-config.php`、支付/SMTP/数据库密钥。
- 大型PNG、ZIP和生产数据。

## 初始化前检查

1. 查看根目录`.gitignore`，确认计划中的自定义主题和插件目录名称一致。
2. 确认`git status`不会包含数据库、uploads、日志、WordPress核心或第三方插件。
3. 在公司GitHub/GitLab组织创建私有仓库，老板/企业账号保留Owner权限。
4. 初始化后先提交项目文档和安全基线，再开始自定义代码。

## 插件版本同步

- 自定义插件源码进入Git。
- WooCommerce和第三方插件文件不进入Git，版本记录在`PLUGIN_INVENTORY.md`。
- 若后续使用Composer管理依赖，提交`composer.json`和`composer.lock`。
- 付费插件安装包放受控私有存储，确认许可证允许，不放公共仓库。

## 数据和媒体

- 数据库通过备份、导出或迁移流程管理，不用Git同步。
- uploads通过备份或对象存储同步，不用Git同步。
- 本地使用测试/脱敏数据，禁止把客户真实资料发送给AI。

