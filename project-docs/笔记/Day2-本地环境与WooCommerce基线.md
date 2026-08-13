# D2 LocalWP与WooCommerce本地基线

## 相关笔记

- 前置范围与依赖：[[Day1-范围与访问依赖基线]]
- 后续代码与数据边界：[[Day3-代码数据与恢复边界]]

> 日期：2026-08-10
> 计划：W1 / D2
> 状态：已完成
> Agent：主Agent处理；本地低风险配置和验证，不启动专项Agent

## 今日三个验收结果

- [x] 记录LocalWP、WordPress、WooCommerce、PHP、MySQL和Nginx实际版本。
- [x] 完成第一版语言、币种、税费和本地调试边界配置，不启用真实支付、物流或税率。
- [x] 验证固定链接、时区、WooCommerce页面、数据库初始化、日志和Mailpit本地邮件。

## 环境版本

| 项目 | 实际值 | 验证方式 |
|---|---|---|
| LocalWP | 10.1.1+6939 | LocalWP站点配置 |
| WordPress | 7.0.3 | `wp-includes/version.php`与WordPress运行时 |
| WooCommerce | 11.0.0 | 插件头、运行时常量和数据库版本 |
| PHP | 8.2.29 | LocalWP PHP CLI/CGI服务 |
| MySQL | 8.4.0 | LocalWP服务和MySQL客户端 |
| Nginx | 1.26.1 | LocalWP服务配置 |
| Mailpit | 1.24.1 | LocalWP服务配置和本地邮件收件验证 |
| Query Monitor | 4.0.7 | 插件头；仅限Local开发环境 |

## 已完成配置

| 配置 | 当前值 | 说明 |
|---|---|---|
| 站点前台语言 | `en_US` | 第一版英语；数据库中的空`WPLANG`表示WordPress默认英语 |
| 管理员语言 | `zh_CN` | 保留开发者中文后台，不改变前台语言 |
| WooCommerce币种 | `USD` | 第一版美元 |
| 税费计算 | 关闭 | 业务规则未确认前不配置税率 |
| 时区 | `Asia/Shanghai` | 当前开发与内容团队工作时区；生产前复核业务需求 |
| 固定链接 | `/%postname%/` | 已启用可读Slug结构 |
| 本地调试 | `WP_DEBUG=true`、记录日志、不向页面显示 | 只适用于Local；生产必须关闭调试输出 |
| 支付 | 未配置 | 不启用真实支付；后续仅在Staging使用沙盒 |
| 配送 | 未配置 | 等待配送国家、运费、免邮和承运商规则 |
| 商店地址 | 未确认 | 当前WooCommerce默认值不能视为正式业务地址 |

## WooCommerce初始化验证

- WooCommerce已安装并激活，数据库版本为`11.0.0`。
- 已建立14个`wp_woocommerce_*`表；商品属性表、Action Scheduler和HPOS订单表存在。
- 商店、购物车、结账和我的账户页面均已生成。
- 首页、登录、商店、购物车和账户页面返回HTTP 200。
- 空购物车访问结账页会重定向至购物车，这是当前空状态下的正常WooCommerce行为。
- WooCommerce后台6步引导不在D2全部执行：商品在D6准备；支付、税费、配送等待业务规则；主题定制从D26开始；当前不是正式发布。

## 邮件与日志验证

- 使用`wp_mail()`发送到虚构的本地域名收件人`d2-test@dentall.local`。
- Mailpit成功收到主题为`DentAll D2 local mail test`的邮件，没有发送到外部邮箱。
- Nginx错误日志当前为空。
- PHP日志可写；WordPress已配置本地调试日志且前台不显示错误。

## 已知问题与后续处理

| 等级 | 问题 | 处理计划 |
|---|---|---|
| P2 | LocalWP命令行PHP声明了`php_imagick.dll`，但扩展文件或依赖不可用 | D2不安装新组件；D11媒体规范前检查Web运行时Site Health和图片处理能力，再决定修复或移除无效声明 |
| 已解决历史项 | WooCommerce首次激活日志曾记录商品属性表不存在 | 当前表已建立，WooCommerce数据库版本正常；继续观察是否再次出现 |
| 待确认 | WooCommerce商店基础国家/州当前为安装默认值，不代表真实企业地址 | 配送与税费规则确认后再设置，不用临时值驱动业务逻辑 |

## 安全与影响

- 没有配置或测试真实支付、真实税率、真实物流、生产邮件、DNS或Cloudways。
- 本次更改了本地WordPress/WooCommerce数据库选项，并修改了被Git忽略的本地`wp-config.php`调试设置。
- 不影响生产数据、正式URL、SEO索引、缓存和部署。

## 可复用核心思想

- 环境基线必须记录WordPress、WooCommerce、PHP、数据库和Web服务器版本；“在我电脑上能运行”只有在版本可复现时才有意义。
- 安装成功不等于业务配置完成。支付、税费、配送和企业地址应等待真实规则，不能用安装向导默认值驱动正式逻辑。
- WordPress页面存在、WooCommerce页面已绑定、Slug正确是三种不同状态，需要分别验证。
- 命令行PHP与Web请求使用的PHP运行时可能不同；扩展故障要在实际运行上下文中验证，不能只看CLI输出。
- 本地邮件捕获器、错误日志和关闭前台报错共同组成安全调试闭环：错误可观察，但不能泄露给访客或发送到真实收件人。
