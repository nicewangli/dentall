---
类型: 项目Day笔记
项目: DentAll WooCommerce
日期: 2026-10-07
工作日: Day82
主题: 默认账单与配送地址
状态: 已完成隔离Local技术验收，目标环境待复验
---

# Day82：默认账单与配送地址

## 结论与用户授权

用户同一轮明确授权D81/D82功能确认单范围。D82复用WooCommerce 11.0.0的My Account地址列表、Billing/Shipping编辑表单、国家相关字段和`WC_Customer`保存；仅在`woocommerce_after_save_address_validation`增加Customer默认地址国家复核。隔离Local技术验收已通过，未写共享Local、Staging或Production。

范围与排期：仍对应总计划D82账单/配送地址检查点，未加入地址簿、多地址、Checkout改造或订单重写，新增排期为0个计划工作日。实际工时未由用户记录；目标环境原生国家设置与缓存验收另行安排，不能从隔离Local结果推算完成时间。

## 最多3项验收结果

1. Customer A可新增/修改自己的默认Billing和Shipping，Customer B与Guest不能修改A；Nonce、必填和原生字段错误正常提示。
2. Shipping仅接受US/CA/AU；Billing不套用三国白名单，按目标WooCommerce selling countries和对应国家字段规则保存。
3. 修改默认地址及Billing email不改账户登录邮箱、既有订单或已签发报价快照；390、768、1024、1440px的空地址、长文本、表单和错误可用。

## 实现与职责边界

WooCommerce原生表单负责页面、Nonce、当前登录用户、国家字段定义、必填、邮编、电话、邮箱与`WC_Customer`持久化。`dentall-core/includes/customer-account.php`只在Customer的My Account地址保存前核对目标ID、国家是否在Woo原生对应列表中，并为Shipping叠加D73既有US/CA/AU常量。原生Woo保存处理器未复核伪造POST国家是否在下拉列表，因此需要这处小型服务器校验。子主题的同一账户样式负责地址卡和表单的四端布局，不复制模板或地址数据模型。

My Account的默认地址是客户资料；既有订单与已签发报价保存自己的交易快照，不能用更新默认地址追写旧单。此Hook不覆盖Checkout或其他后台/REST写入，结账仍由Woo及D73报价合同各自验证。Woo原生My Account不会对伪造的州省下拉值做成员校验，本日不扩写通用地址验证器，后续若出现实际数据质量问题再单独评估。

地址列表沿用Woo的`.woocommerce-Addresses`和两张`.woocommerce-Address`，小屏单列、768px起两列；编辑表单仍是同一语义DOM。Storefront宽屏原样式把“Add/Edit address”文字移出可视区，只显示图标，本日仅在账户地址标题作用域恢复可见文字与44px目标。Chrome DevTools可在Elements中查看地址卡Grid、编辑链接Computed的`text-indent`和`::before`、各国字段的required/可见状态；微调只回子主题账户CSS，不改Woo模板或父主题。

## 验证证据

| 项目 | 当前结果 |
|---|---|
| 静态检查 | PHP语法、Node语法、`git diff --check`通过 |
| 纯PHP合同 | US/CA/AU与非目标Shipping、境外Billing、跨用户ID分支包含在26/26通过中；不是数据库持久化证据 |
| 隔离Local真实地址保存 | Woo原生表单9项中的FR Billing、US Shipping与GB伪造拒绝通过；Woo设置为Selling all、Shipping specific US/CA/AU |
| 既有订单与报价快照 | 客户默认地址修改后，旧订单customer_id、Billing/Shipping、status、归一化total与currency六类字段不变。另经D75签发回调建立TEST报价订单，Customer A通过原生HTTP把默认Billing→GB、Shipping→CA后5/5通过：新默认地址保存，旧报价地址/Shipping金额/总额/状态/签发标志/到期/签名/Token不变，签名匹配且仍按旧单条件可付款；只模拟成功邮件回调，未真实发信或付款 |
| 四端浏览器 | 空地址/资料/表单等5页×390/768/1024/1440px共20组、213/213断言通过；页面与Console错误均为0 |
| 独立复核 | 已修复Storefront地址标题伪元素Flex冲突；代码/视觉与安全终审开放P0/P1/P2=0，Woo原生州省成员校验缺口记P3观察 |

## 七个专注周期对应工作

| 周期 | 本日结果 |
|---|---|
| C1 | 核对D73 Shipping/Billing合同与Woo原生地址保存源码 |
| C2 | 确定默认客户地址与既有订单快照的边界 |
| C3 | 在原生保存前增加国家白名单复核 |
| C4 | 实现地址列表与表单四端样式 |
| C5 | 纯PHP合同和独立审查，修复地址标题样式冲突 |
| C6 | 隔离Local真实保存、订单及已签发报价快照、20组四宽浏览器通过 |
| C7 | 独立终审开放P0/P1/P2=0；隔离服务已停，目录删除被自动审批拒绝，待允许路径清理 |

## 风险、下一步及系统影响

- Woo原生Billing下拉和本次服务器校验都使用`get_allowed_countries()`；目标环境Selling countries须为all，Shipping locations须specific US/CA/AU，使下拉与服务器合同一致。My Account Page须使用Storefront Full width原生模板；这三项目前只在隔离Local配置，发布时需逐站核对，不能仅凭代码推断。
- 原生州省选项的伪造值未由本次小型国家Hook作成员校验，不能宣称所有地址字段具备强白名单。正常表单和结账合同仍需动态验证。
- 无新字段、Schema、插件、公开URL、SEO输出、Cron或远程请求。默认地址只由Woo客户CRUD写入，历史订单/报价不能随代码回滚自动恢复；需按对象快照和审计处理。前端仅登录态账户请求多一份条件CSS，未测目标性能或缓存。
- 支付与真实物流费用不变；Staging/Production、目标缓存及真实交易未操作。D83订单中心和D84全链路回归仍待。

## 相关笔记

- 每日笔记索引：[[README|DentAll每日笔记索引]]
- 前置项目笔记：[[Day79-登录注册与订单归属]]、[[Day81-账户仪表盘与资料策略]]、[[Day73-报价字段与客户身份合同]]
- 对应学习笔记：[[WordPress实战笔记/Day82-WooCommerce默认地址与订单快照]]
- 关联流程：[[Day75-人工物流与报价72小时生命周期]]

## 可复用核心思想

- 跨平台不变量：账户默认地址是未来交易的预填建议，已成立订单是历史合同快照；改默认值不能静默改旧交易。
- WordPress/WooCommerce当前实现：优先复用原生地址表单与`WC_Customer`，只补原生处理器遗漏的国家成员校验；Billing国家设置和Shipping业务白名单是两个不同约束。
- Shopify或其他平台：默认地址、结账地址和订单地址的具体存储与更新API要分别验证，不能假定三者同步或自动隔离，待验证。
