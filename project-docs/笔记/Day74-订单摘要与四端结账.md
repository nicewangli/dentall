---
类型: 项目Day笔记
项目: DentAll WooCommerce
日期: 2026-09-22
工作日: Day74
主题: 订单摘要与四端结账
状态: 源分支及最新主线Local合成通过，推送与Staging发布待执行
验证环境: 独立Local；WordPress 7.0.4、WooCommerce 11.0.0、Storefront 4.6.2、PHP 8.2.29
tags:
  - DentAll
  - WooCommerce
  - Checkout
---

# Day74：订单摘要与四端结账

> 整合更新（2026-10-06）：D74已以`78b6473`整合到最新主线，最终运行源码`12274cd`为主题0.46.0/Core 0.6.0；本次完整合成的生命周期101/101与真实Checkout/order-pay四端150/150通过，D79/D80/D85回归和实际Woo门禁通过。GitHub push、冻结后新备份和Staging Pull未执行。本篇下文0.44.0/Core 0.4.1及59/95/46计数保留为2026-09-22历史证据；ADR-044/RSK-055不变。完整记录见[[Day86-D74与D86整合发布记录]]，当前状态见[[../PROJECT_STATE]]。

## 相关笔记

- 每日笔记索引：[[README|DentAll每日笔记索引]]。
- 对应学习笔记：[[WordPress实战笔记/Day74-WooCommerce订单摘要与服务端截止时间]]。
- 前置项目：[[Day73-报价字段与客户身份合同]]、[[Day75-人工物流与报价72小时生命周期]]。
- 前置学习：[[WordPress实战笔记/Day73-WooCommerce报价字段与客户身份边界]]、[[WordPress实战笔记/Day75-WooCommerce报价过期与订单生命周期]]。

## 结论

D74采用“WooCommerce金额事实不动，DentAll只补展示层和客户可见责任说明”的最小方案：虚拟商品走普通Checkout Block，实体人工报价走原生`order-pay`。两条路径都不建立第二套金额算法；人工报价页额外显示服务端保存的绝对截止时间、站点时区、Importer of Record及进口费用/卖方代收税的边界。

当前主题候选为0.44.0，Core候选为0.4.1。纯合同、独立真实WooCommerce/四端浏览器和代码/安全/测试三路终审已经通过，D74按Local技术范围完成；这不外推为正式税务、网关、邮件或非Local验收完成。

## 已确认业务合同

- 公司注册地：中国深圳。
- 第一版主要销售地：美国、加拿大、澳大利亚。
- 主要客户：企业客户。
- 展示币种与价格：USD未税价。
- 客户是Importer of Record；import duties、import taxes、customs clearance fees及carrier brokerage fees由客户直接向海关或承运商支付，不进入DentAll订单总额。
- DentAll依法必须代收的Sales Tax、VAT、GST或HST才进入WooCommerce订单并单列；没有真实税额时不显示`Tax 0`。
- 以上只是第一版界面和责任合同，不替代各司法辖区对注册义务、阈值、商品分类、B2B凭证、计税地址、税率及申报的财税判断。

## 最多三个验收结果

- [x] 普通虚拟Checkout和实体报价`order-pay`继续输出WooCommerce原生商品、Shipping、Tax、Fee和Total，不在主题或插件重算金额。
- [x] 有效报价显示服务端绝对截止时间和站点时区，重发不续期；未签发、过期、内容变化或关闭报价不输出，且没有浏览器倒计时。
- [x] 390、768、1024和1440px真实页面、金额一致性、Guest验证、Console/PHP错误及测试数据恢复完成独立Local终验。

## 实际实现

### Core业务层

`app/public/wp-content/plugins/dentall-core/includes/shipping-quote-lifecycle.php`新增两个小职责：

1. `dentall_core_format_shipping_quote_expiry()`按WordPress站点日期格式、时间格式和时区格式化既有UTC到期事实；不在请求时重新加72小时。
2. `dentall_core_render_shipping_quote_payment_terms()`挂到`before_woocommerce_pay_form`。WooCommerce在触发该Hook前已经完成订单key、订单归属和Guest邮箱验证；回调仍要求订单是有效人工报价，并以`edit`上下文读取签发/到期meta。

输出使用语义化`section`、关联标题和`time[datetime]`。可见文本使用站点本地时间及明确时区，机器值保持UTC。文本与属性分别转义；函数不接收请求中的订单ID、不读取客户端金额、不写数据库。

Customer Invoice条款同时改为：客户是Importer of Record；进口环节费用客户直付；DentAll依法代收的税在订单金额中单列。

### 子主题展示层

`app/public/wp-content/themes/dentall/inc/setup.php`只在`is_checkout()`且不是订单完成页时加载`assets/css/checkout.css`。该CSS：

- 对Checkout Block和`order-pay`内部可收缩项设置`min-width:0`，长商品名、规格、标签和金额允许安全断行。
- 付款按钮最小高度为44px。
- `order-pay`原生金额表使用固定布局和明确列比例，移动端保持单列可读。
- 1200px起为原生金额表和支付区建立两列层级；不复制DOM、不覆盖模板、不增加JavaScript布局。

Cart报价邮件文案同步费用合同；主题和Core版本号更新用于未来部署时刷新资源缓存。

## 七个专注周期记录

| 周期 | 工作 | 结果 |
|---|---|---|
| C1 | 核对D73/D75运行事实与Woo付款Hook | 确认展示Hook位于Guest校验之后，不改变生命周期守卫 |
| C2 | 冻结税费与Importer of Record合同 | 用户确认USD未税、进口费用客户直付、卖方依法代收税单列 |
| C3 | 实现服务端截止与付款条款 | 有效报价输出；无效分支静默不输出 |
| C4 | 实现Checkout/`order-pay` Mobile First样式 | 新增单一按页CSS，无模板覆盖和金额重算 |
| C5 | 扩展合同测试 | D73 59/59、D75 95/95、邮件46/46 |
| C6 | 代码和安全独立审查 | P0～P3均为0；Importer of Record缺口和Hook断言已补齐 |
| C7 | 隔离Local真实Woo与四端收尾 | 浏览器150/150，错误0，TEST数据、独立库、进程和端口清理完成 |

## 验证证据

已通过：

- PHP语法：Core主文件、生命周期模块、主题`setup.php`和测试入口通过。
- JavaScript语法：报价邮件脚本和合同入口通过。
- D73 PHP合同：59/59。
- D74扩展后的D75生命周期合同：95/95；故障注入日志是预期安全分支，最终状态为pass。
- 报价邮件合同：46/46。
- `git diff --check`通过，仅有工作区LF/CRLF提示。
- 独立代码复核：P0=0、P1=0、P2=0、P3=0。
- 独立安全复核：P0=0、P1=0、P2=0、P3=0。

独立Local最终证据：

- 真隔离副本使用loopback HTTP `17474`、MySQL `17475`和独立数据库；外发HTTP、PHP mail、真实网关、WP-Cron和文件修改关闭。
- Chrome浏览器150/150。普通Checkout与实体`order-pay`各覆盖390、768、1024、1440px。
- 实体报价商品`2 × $99.95 = $199.90`、Shipping `$35.00`、Documentation fee `$7.25`、Total `$242.15`四端一致；没有`Tax $0`或虚构税行。
- 有效报价显示Importer of Record、进口费用直付、卖方依法代收税单列；截止`datetime=2026-09-25T01:40:03Z`，可见Asia/Shanghai。Asia/Shanghai和America/New_York浏览器上下文的文本与机器值一致，重发不续期。
- Guest验证前没有条款或订单金额；正确Billing email后才输出，错误order key不泄露条款、商品或金额。
- 普通虚拟Checkout长名称商品Subtotal/Total均为`$123.45`，没有Shipping、`Tax $0`、报价截止或Importer文案。
- 首轮普通Checkout付款按钮被Woo Blocks规则覆盖为42px，最终提高限定选择器特异性且不使用`!important`；四端均≥44px。两条路径无根级/表格/条款溢出，键盘焦点可见。
- Console=0、pageerror=0、HTTP≥400=0，最终HTTP PHP日志的Fatal/Parse/Warning/Deprecated/Uncaught/DB error为0，`wp-debug`无条目。
- TEST商品、报价订单、checkout draft、相关计划任务、独立数据库和测试账号清零；PHP/MySQL进程及17474/17475监听关闭。源Local `wp-config.php`哈希以及商品/选项不变量通过；证据文件留在本机隔离目录，不进入Git。

## 减法审查

- 没有复制WooCommerce Checkout或`form-pay`模板。
- 没有新增Checkout JavaScript、倒计时、轮询、REST端点、数据库字段、订单meta、插件或依赖。
- 没有重算商品、coupon、Shipping、Tax、Fee或Total。
- 运行代码仅新增1个职责明确的125行CSS文件（21个含媒体查询的规则块）、2个Core展示函数和1个条件加载函数；Core保存生命周期事实，主题只负责页面样式，职责边界与现有架构一致。
- 两条结账路径共用WooCommerce原生事实，不为手机、平板和PC复制页面。

## 影响与边界

| 范围 | 影响 |
|---|---|
| 数据 | 不迁移、不新增订单meta；读取D75已有签发/到期事实 |
| URL/SEO | 不新增URL、Canonical、Schema、robots或Sitemap输出 |
| 缓存 | 新CSS按主题版本失效；Checkout和`order-pay`仍必须排除页面/边缘缓存 |
| 支付 | 不改网关、付款状态或金额；真实网关晚到webhook仍留D76/D78 |
| 物流 | 不改Shipping line；进口费用不进入DentAll订单总额 |
| 税务 | 冻结展示和责任合同；正式注册、税率与申报仍待财税确认 |
| 邮件 | 只更新文案；真实SMTP投递和退信仍留D77 |
| 部署 | 未修改共享Local、Staging、Production或DNS，当前分支尚未合并 |

## 安全微调与DevTools路径

需要调间距或列宽时，先在目标页面DevTools中检查`.wp-block-woocommerce-checkout`、`.woocommerce-order-pay #order_review`和`.dentall-quote-terms`，临时修改后判断应落到公共Design Token还是`checkout.css`局部规则。随后回到子主题源码修改，并回归390、768、1024、1440px、虚拟Checkout和实体`order-pay`。不要修改WooCommerce、Storefront核心文件，也不要在页面编辑器或模板写行内样式。

## 未完成与下一步

- D76/D78在目标支付网关沙盒验证晚到webhook、库存、coupon和订单状态。
- D77验证正式SMTP实际投递、退信和目标主机Cron。
- 上线前由财税负责人确认美国、加拿大、澳大利亚的具体注册义务、B2B凭证、阈值、商品分类、计税地址、税率及申报。
- 隔离Local继承的普通Checkout标题、隐私文案和日期格式含中文；按RSK-055在第一版英语站展示验收前统一并重跑两条结账路径与邮件。
- 本轮不部署Staging/Production，不启用真实支付，不写正式税率。

## 可复用核心思想

### 跨平台不变量

金额只有一个事实源；界面可重新排版，但不能复制计算。进口费用承担方与卖方代收税是两个法律和交易问题，文案必须分开。任何倒计时都只是显示，真正的截止资格必须由服务端事实和交易守卫决定。

### WordPress/WooCommerce当前实现

WooCommerce的Order、Cart、Store API、Checkout Block和`form-pay`负责金额；`dentall-core`读取订单`edit`事实并通过已验证后的Hook补业务条款；子主题按页加载Mobile First CSS。交易页继续排除缓存，所有金额和订单写入使用WooCommerce API。

### Shopify或其他平台的对应机制

Shopify也应让平台Checkout、Draft Order或订单API成为金额事实源，并把进口责任与平台依法代收的税分开；具体B2B税务、Markets、Duty和结账扩展能力依套餐、地区和当前平台规则而变，本项目未实际验证，迁移时必须重新查证，不能把WooCommerce Hook或meta直接类比为一一对应能力。
