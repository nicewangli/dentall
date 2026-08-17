# 插件与许可证清单

## 选择原则

- 能用WooCommerce或WordPress原生能力完成的，不额外安装插件。
- 插件必须有明确用途、维护状态、更新记录和兼容性验证。
- 支付、SEO、缓存、安全、备份等高风险插件先在Staging测试。
- 禁止两个插件重复控制同一核心功能。

## 候选/已安装插件

| 插件 | 类别 | 用途 | 状态 | 版本 | 许可证/归属 | 数据影响 | 替代/移除方案 |
|---|---|---|---|---|---|---|---|
| DentAll Core | 项目业务 | 跨主题角色、权限、网站级SEO兼容及后续商城业务规则 | Local与Staging已安装并激活；0.2.3已部署Staging，Local完成重复Title修复验证 | Local/Staging 0.2.3 | GPL/项目自有 | 中 | 停用后保留角色数据；主入口与`includes/`必须同版本部署，回滚代码并重新分配账号角色 |
| WooCommerce | 商城核心 | 商品、订单、购物车和结账 | 已安装并激活，已完成D2基础配置 | 11.0.0；11.0.1可用但本日不升级 | GPL/项目 | 高 | 不可轻易替换 |
| Query Monitor | 本地开发 | 查询、Hook、请求和错误诊断 | Local已安装；D16冲突隔离后保持停用，仅限Local | 4.0.7 | GPL/开发者 | 低 | 停用并删除，不进入生产必需清单 |
| ACF Pro | 字段 | 仅在原生字段不足时补充定制商品展示、技术参数和资料下载等结构化字段 | Local已安装；D16冲突隔离后保持停用，许可证归属仍待核对 | 6.8.7 | 商业许可证/公司账户待核对 | 中 | 字段定义通过Local JSON或PHP版本化；停用前评估模板依赖和数据迁移 |
| Yoast SEO Free | SEO | SEO标题、Meta描述、Canonical、XML Sitemap、基础Schema和编辑辅助 | Local与Staging均已安装并激活；Local完成启停回退，Staging由用户手动激活后完成五页Title与`noindex`矩阵 | Local/Staging 28.2 | GPL/免费 | 中 | 导出设置后替换；禁止与其他SEO插件重复输出元数据和Schema；Staging无Canonical受全站`noindex`影响，Production另验 |
| Site Kit by Google | 分析/站长工具 | 接入Search Console、GA4和PageSpeed Insights，并在后台展示数据 | 已选型；当前受保护Staging不连接正式Google服务，Production上线准备阶段再配置 | 待安装时记录 | GPL/免费；Google资产归公司账户 | 中 | 可停用并改为手工部署Google Tag；停用前确认GA4标签不会重复或丢失 |
| WPML Multilingual CMS | 多语言 | 未来翻译商品、页面、字符串和WooCommerce前台内容 | 未来方案已选型；第一版仍为英语/美元，暂不安装 | 启用时记录 | 商业许可证/公司账户 | 高 | 启用前完成URL、字段翻译模式、库存同步和SEO回归；移除需评估翻译数据与URL影响 |
| ACF Multilingual | 多语言字段 | 配置ACF字段的翻译、复制和同步模式 | 随WPML启用，不单独提前安装 | 启用时记录 | 随WPML方案/公司账户 | 高 | 启用前逐字段定义Translate/Copy/Copy once；移除前保留翻译字段数据 |
| WPML SEO | 多语言SEO | 协调Yoast元数据、Sitemap和多语言SEO输出 | 随WPML启用，不单独提前安装 | 启用时记录 | WPML免费兼容扩展/公司账户 | 高 | 停用前检查hreflang、Sitemap、Canonical和各语言元数据 |
| WooCommerce Multilingual & Multicurrency | 多语言商城 | 未来同步商品翻译、库存和WooCommerce前台字符串；第一版不启用多币种 | 随WPML启用，不单独提前安装 | 启用时记录 | 随WPML方案/公司账户 | 高 | 启用前验证商品、变体、库存、结账和邮件；币种功能保持关闭直到另行立项 |
| 缓存插件 | 性能 | 页面缓存和资源优化 | 待选型 | - | 待确认 | 高 | 关闭并清缓存 |
| SMTP插件 | 邮件 | 订单邮件可靠发送 | 待选型 | - | 企业邮件账户 | 高 | 切换SMTP服务 |
| 备份插件/主机备份 | 运维 | 数据库和文件备份 | 待选型 | - | 企业账户 | 高 | 保留独立离线备份 |
| WooCommerce Stripe Gateway | 支付 | 信用卡、借记卡及经确认的钱包支付 | 方向已选，待公司主体、销售国家和Stripe开户资格确认；当前不安装、不连接真实账户 | 安装时记录 | GPL/免费插件；企业Stripe账户按交易收费 | 高 | 先用Test Mode和Webhook回归；禁用前处理待捕获、退款和Webhook |
| WooCommerce PayPal Payments | 支付 | PayPal付款 | 方向已选，待企业PayPal账户确认；当前不安装、不连接真实账户 | 安装时记录 | GPL/免费插件；企业PayPal账户按交易收费 | 高 | 先用Sandbox回归；禁用前处理退款、争议和Webhook |
| WooCommerce Direct Bank Transfer（BACS） | 离线支付 | 客户银行转账，到账后人工确认订单 | WooCommerce原生能力已选；正式收款信息和负责人待确认，当前保持关闭 | WooCommerce内置 | GPL/免费 | 高 | 关闭网关即可；停用前保留历史订单付款说明和人工核账记录 |
| 品牌能力 | 商品 | 品牌归档、筛选和展示 | 待决策 | - | 待确认 | 中 | 数据迁移后替换 |

## 已冻结的职责边界

- `Yoast SEO Free`负责SEO标题、Meta描述、Canonical、XML Sitemap和Schema；`Site Kit by Google`不得重复生成或改写这些SEO输出。
- `Site Kit by Google`负责连接Search Console、GA4和PageSpeed Insights。GA4标签只能由一个来源部署，启用前必须排查主题、Google Tag Manager和其他插件中的重复标签。
- 已冻结的标签架构为：Site Kit部署唯一GA4 Google tag并放置GTM容器；GTM不重复部署GA4基础标签，只管理后续广告标签和Site Kit未覆盖的项目自定义事件。
- WooCommerce标准电商事件优先采用Site Kit原生插件转化跟踪；联系表单、资料下载和实际启用的条件功能通过项目`dataLayer`事件与GTM补充，避免再安装职责重叠的GA4电商跟踪插件。询价事件仅在CR-004进入实施时增加。
- 受Cloudways Password Protection保护且设置`noindex`的Staging不连接正式Search Console和GA4，不产生正式业务统计；Production上线准备阶段再由公司Google账户完成连接。
- 面向EEA、瑞士或英国用户时启用Site Kit Consent Mode，并配套WP Consent API与经确认的CMP；销售地区和隐私要求未确认前不提前安装CMP。
- SEO人员可使用Yoast处理内容层优化；开发者负责插件配置、权限、索引、Canonical、Sitemap、Schema和前端技术验证。
- Search Console、GA4、Google Tag Manager及Site Kit连接使用公司持有的Google账户；不得长期绑定开发者个人账户。
- ACF Pro只补充WooCommerce原生字段不能表达的结构化展示字段；价格、SKU、库存、分类、属性、变体、重量和尺寸继续使用WooCommerce原生能力。

## 每次更新检查

- [ ] 当前生产版本和更新目标已记录。
- [ ] 阅读变更说明和兼容要求。
- [ ] 数据库、uploads和代码已备份。
- [ ] 先在Staging更新并完成核心回归。
- [ ] 检查WooCommerce模板过期提示。
- [ ] 检查缓存、支付、邮件和定时任务。
- [ ] 记录更新时间、结果和回滚方式。
