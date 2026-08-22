# 测试计划与发布门槛

## 测试原则

- 测试与开发同步，不等全部页面完成后集中测试。
- 每个任务至少覆盖正常、空数据、错误和边界状态。
- 高风险流程必须在Staging用代表数据验证。
- 缺少测试证据的任务不能标记为Done。

## 环境与设备矩阵

| 类型 | 最低验证 |
|---|---|
| PC | Chrome、Edge，约1440px；抽查更宽和更窄窗口 |
| 手机 | 约390px；抽查375和414px |
| 平板竖屏 | 约768px |
| 平板横屏 | 约1024px |
| 键盘 | 导航、菜单、表单、购物和账户关键交互 |
| 登录状态 | 匿名客户、已登录客户、编辑、管理员 |

## 核心测试清单

### 商品与后台

- [ ] 简单商品、变体商品、促销、缺货和隐藏商品。
- [ ] SKU唯一、价格和库存显示一致。
- [ ] 标准Simple空价格和默认不允许的`0`价格不会误发布为可购买商品。
- [ ] Sale price低于Regular price，促销开始前、期间和结束后价格状态正确。
- [ ] Variable父商品价格由可售Variations派生；同价显示单价，异价显示范围，选择后显示准确Variation价格。
- [ ] 启用但缺少价格、已禁用或非法组合的Variation不可购买，且有明确状态。
- [ ] USD由WooCommerce格式化，不在模板中硬编码货币符号；TEST金额不会混入正式商品。
- [ ] Simple数量按可售包装/销售单位扣减；充足、刚好、少于购买量和归零后的状态正确，未启用数量跟踪时不出现虚假数量。
- [ ] Variable独立库存模式下，各Variation只扣减自身数量，父商品可购买性随可售Variations正确汇总；父级共享库存模式下只扣减父库存且不存在父子重复扣减。
- [ ] Backorders默认关闭；缺货商品不可加入购物车。经批准启用时，允许/通知状态、预计交期文案、购物车、结账、订单和邮件保持一致。
- [ ] 固定整套按整套库存扣减；共享部件组装场景不会被误标为已有原生组件库存联动。
- [ ] 临时缺货商品保留稳定URL并显示明确不可购买状态；停售商品不因库存变化被直接删除或擅自改变Slug。
- [ ] 临时缺货页面保持200、可索引和自身Canonical，结构化数据为`OutOfStock`，恢复库存后购买控件和可用性一致恢复。
- [ ] 永久停售严格替代301、保留200停售页、无替代404/410三条路径按业务事实选择；不把同分类或热门商品当作自动替代。
- [ ] 保留200的停售页无购买入口、文案与Schema状态一致；若实现`Discontinued`映射，前台、WooCommerce、Yoast、Feed和结构化数据验证器结果一致。
- [ ] Display Only不显示交易库存、数量、Backorder或低库存，也不能通过直接请求触发库存扣减。
- [ ] 低库存阈值和通知只发送给已确认责任人；TEST库存不会进入正式商品或Production。
- [ ] 产品规格尺寸与Shipping重量/长宽高分别维护；锆块直径、厚度等属性不会被误当成快递包装尺寸。
- [ ] Simple使用自身物流数据；Variable相同物流数据时正确继承父商品，不同时由具体Variation覆盖，空值继承不会掩盖未知或错误数据。
- [ ] Shipping字段只保存当前全站单位下的有效数值，不重复写单位；导入、导出、API和前台显示的换算结果一致。
- [ ] 单个销售包装与最终订单外箱职责分开；单件、多件、不同数量和混合商品不会直接复用一个商品尺寸作为整单包裹。
- [ ] 净重、发货毛重、产品本体尺寸和包装尺寸的测量对象有来源记录；空值、`0`、负数、超大值和TEST值不会误进入正式运费计算。
- [ ] 改变Variation的Package Quantity、重量或尺寸后，受支持的运费方法得到相应输入；Display Only不进入普通物流计算。
- [ ] 物流插件确定后，验证公制/英制、Length×Width×Height顺序、体积重/计费重、Shipping Class及地区边界，不使用虚假重量模拟承运商规则。
- [ ] 创建Variations前记录理论组合数和合法组合表；系统只创建合法组合，不自动把完整笛卡尔积当作正式可售集合。
- [ ] 每个可售Variation的属性组合唯一，并具有唯一SKU、有效价格、唯一库存真相源和明确的物流继承/覆盖状态；不存在重复或歧义通配组合。
- [ ] 默认Variation为空时能够要求客户完成必要选择；设置默认值时只指向合法、启用且可购买的组合。
- [ ] D12 Variable TEST原型覆盖2×2理论组合、3个合法组合和1个非法组合，并验证同价/异价、独立库存、缺货、父物流继承及Variation物流覆盖。
- [ ] HP0103G、FG0312D及运动护齿/EVA等未知业务事实不会被TEST原型固化为正式套装、单支、包装或Variation关系。
- [ ] 主图、图库、缺图、长标题和特殊字符。
- [ ] D12使用`AST-011`验证：1254×1254 WebP主图和3张Variation图正常；未分配图片时显示缺图状态；640×480 `TEST INVALID`样本能被识别为内容质量不合格并在验证后删除。
- [ ] 媒体Title、alt、Caption和Description职责分离；Size等画面不可见的属性不进入alt，纯装饰图由最终组件输出空alt。
- [ ] 内容角色只允许JPEG、PNG和WebP且单文件上限5MB；PDF、SVG、伪装扩展和超限文件被拒绝，公开媒体URL不承载敏感或受控文件。
- [ ] 编辑人员无管理员权限也能完成日常录入。

### 列表和搜索

- [ ] 分类、搜索、排序、分页和筛选组合。
- [ ] 无结果、少商品、多商品和参数重置。
- [ ] 筛选和排序URL的Canonical/index策略正确。

### 商品详情

- [ ] 图库、价格、库存、数量和加入购物车。
- [ ] 变体有效/无效组合、默认选项和变体图片。
- [ ] 技术参数、下载、相关商品和Schema。

### 定制商品展示与条件询价

- [ ] 仅显式启用`display_only`的商品进入展示状态，标准商品仍显示正常价格、库存和加购。
- [ ] 展示商品不以空价格或`$0`进入普通购物车、结账、库存扣减或购买事件。
- [ ] 参考价格下限/上限均为有效USD数值、下限不高于上限；缺失、相等、长数字和无效数据有安全状态，不输出可购买Offer Schema。
- [ ] 展示商品不存在可绕过前端直接加购的入口；`Contact Us About This Product`指向正确通用联系流程并携带有效Product ID。
- [ ] Contact页由服务端重新读取并显示正确商品名称、URL和参考范围；无效或篡改Product ID被安全忽略，不信任查询参数中的名称和金额。
- [ ] Contact提交和通知包含商品上下文及`source=custom_product`，但不创建购物车、报价或WooCommerce订单。
- [ ] 390、768、1024和1440px下，展示购买区、长内容、缺图和联系入口可键盘操作且无横向溢出。
- [ ] 若CR-004未来启用，必须恢复并执行询价表单、后台记录、邮件、权限、人工建单、付款链接和事件测试，不能以通用联系表单测试代替。

### 购物车和结账

- [ ] 新增、修改数量、删除和空购物车。
- [ ] 优惠券成功、失败、过期和重复使用。
- [ ] 运费地区、免邮边界和金额舍入。
- [ ] 地址必填、格式错误和错误定位。
- [ ] 支付成功、取消、失败、重复回调和超时。
- [ ] 下单后库存、订单状态、客户邮件和管理员邮件。

### 账户和权限

- [ ] 注册、登录、退出和密码重置。
- [ ] 地址增改、订单列表和订单详情。
- [ ] 用户不能访问其他客户的订单和数据。
- [ ] 编辑不能管理插件、主题和系统配置。

### SEO、性能和安全

- [ ] Sitemap、robots、Canonical、Schema和301。
- [ ] 404返回真实404状态，不伪装200。
- [ ] 已发布Slug迁移时，旧URL一跳301到最相关新URL，新URL返回200且只有一个自身Canonical。
- [ ] 301不存在循环或重定向链；旧URL从Sitemap和主要内链移除，新URL未被意外`noindex`。
- [ ] Canonical目标返回200、允许索引且内容重复/高度相似；Canonical不代替永久迁移301，也不指向不相关页面。
- [ ] 缓存不影响购物车、结账、账户和登录状态。
- [ ] 页面无明显PHP警告、控制台错误和敏感日志。
- [ ] 表单具备nonce、权限、清洗、验证和转义。

### GA4、GTM与转化测量

- [ ] Production只存在一个GA4基础标签来源；Site Kit、GTM、主题和其他插件没有重复部署。
- [ ] Site Kit连接正确的公司Search Console、GA4属性/数据流和GTM容器，开发者个人账号不是唯一所有者。
- [ ] `view_item_list`、`select_item`、`view_item`、`add_to_cart`、`view_cart`和站内搜索事件触发一次且商品参数正确。
- [ ] `begin_checkout`、`add_shipping_info`、`add_payment_info`和`purchase`的币种、金额、商品、数量及交易ID与WooCommerce订单一致。
- [ ] Stripe、PayPal和BACS测试路径不会在失败、取消或未到账时误报`purchase`。
- [ ] 刷新感谢页、返回历史页面和重复Webhook不会造成同一交易ID重复计数。
- [ ] 联系表单提交和公开资料下载事件可在GA4 DebugView中识别；询价事件仅在CR-004实际启用时加入并验证。
- [ ] 登录用户、开发者和测试流量按规则排除；Staging事件不进入Production数据流。
- [ ] 使用Tag Assistant和GA4 DebugView留存事件证据，并检查浏览器控制台无标签错误。
- [ ] 适用时，拒绝统计/营销同意后不写入非必要Cookie；同意后标签状态和事件恢复符合Consent Mode设计。

## D12双环境权限与商品原型验收记录

| 范围 | 环境 | 结果 | 证据摘要 |
|---|---|---|---|
| Website Manager能力白名单 | Local | 通过 | 角色版本5；独立Code Review、安全与测试复核；WordPress用户、插件、主题和系统设置未开放 |
| TEST Simple商品 | Local | 通过 | 商品44完成23项持久化审计、前台检查及回收站恢复 |
| TEST Variable商品 | Local | 通过 | 商品46与Variations 51～53最终17项审计通过；非法组合不可选，价格、库存和缺货状态正确 |
| 代码部署与菜单边界 | Staging | 通过 | DentAll Core 0.2.1部署；`users.php`、`plugins.php`、`themes.php`、`options-general.php`拒绝访问；空Tools入口已隐藏 |
| 文章与评论 | Staging | 通过 | TEST文章完成发布、更新、回收站恢复；评论批准状态、回收站和恢复可操作 |
| 页面与媒体 | Staging | 通过 | TEST页面发布和恢复；WebP上传、页面区块Alt及媒体库Alt保存后仍存在 |
| 简单商品 | Staging | 通过 | TEST商品完成价格19.99、库存2、SKU、重量尺寸、分类、品牌、图片、发布、更新和恢复；前台可购买状态正常 |
| 商品属性 | Staging | 通过 | TEST全局属性与Small/Large项可新增、编辑和删除；测试结束时属性已删除 |
| 优惠券 | Staging | 通过 | `TEST-D12-STAGING-10`发布，百分比10、总使用次数1，编辑后最终基线正确 |
| 订单、客户与报表 | Staging | 通过 | 空订单页及“添加订单”入口正常；客户空状态和WooCommerce报表正常；未创建真实订单或客户 |

- D12确定目标协作结构下的网站操作人员统一使用`DentAll Website Manager`并各自持有独立账号；D24确认当前仅WM-A完成实测，WM-B按CR-007条件性补验。低权限Content Editor测试经用户决定不纳入D12人员验收。
- D12 TEST对象不清理，作为D13及下周回归夹具；它们不是正式业务内容，受Staging访问保护与`noindex`约束，D25前再次评估归档或删除。
- 本轮未测试真实支付、退款、邮件、税费、物流或Production操作；不得从本记录推导这些流程已通过。

## D13 Website Manager培训者预演与简单商品验收记录

| 范围 | 环境 | 结果 | 证据摘要 |
|---|---|---|---|
| Website Manager菜单与边界 | Staging | 通过 | 独立账号可见内容、商品及商城运营入口；用户、插件、主题、工具和系统设置入口未显示；未进入或配置支付 |
| C1引导预演 | Staging | 通过 | TEST简单商品完成草稿、预览和发布；TEST文章完成草稿、摘要、分类、特色图和预览 |
| C2无提示复跑 | Staging | 通过 | 培训者在不到5分钟内独立完成另一个TEST简单商品和TEST文章草稿，无停顿、误操作或错误 |
| SKU发布规则 | 文档与Staging流程 | 通过 | 确认SKU为标准可购买商品发布必填字段；资料未齐时允许留空保存草稿，不允许正式发布 |
| 即时促销 | Staging | 通过 | 29.99常规价与24.99促销价同时正确显示，原价删除线及SALE标识生效，库存未变化 |
| 计划促销 | Staging | 通过 | 促销日期设为2026-08-18至2026-08-19；在2026-08-17只显示29.99常规价，未提前显示SALE或促销价 |
| 非法促销价 | Staging | 通过 | 输入高于29.99常规价的30.99时，后台即时提示必须小于常规价，未保存无效促销状态 |
| 缺货与Backorders | Staging | 通过 | 数量0且禁止Backorders时显示Out of stock并隐藏购买控件；允许但通知客户时显示Available on backorder并恢复购买控件 |
| 缺图与恢复 | Staging | 通过 | 删除主图后显示稳定占位图，无破图或布局塌陷；恢复原TEST图片后价格、库存和购买状态保持基线 |
| 回收站恢复 | Staging | 通过 | 独立复跑商品草稿移入回收站后成功还原；SKU、价格39.99、库存4、分类、ADS品牌、图片和长短描述均保持 |

- C4-C6结束后，培训商品恢复为常规价29.99、库存3、禁止Backorders、原TEST主图、无促销日期的已发布TEST基线。
- 本轮未创建订单、未加入购物车、未进入结账、未启用或配置真实支付，也未修改Production、DNS、税费、物流、邮件、插件、代码或数据库。
- 当前P0/P1为0；本记录只覆盖培训者/角色路径，不能扩写成第二身份人员验收通过。WM-A的D24 A/C6人员门槛已于2026-08-22按可查SOP口径通过；第二人30分钟无指导验收按CR-007触发时补验。
- C7收口截图确认培训商品最终库存数量为3且Backorders为“不允许”，补齐C5基线恢复证据。
- D13复用D12已完成Alt持久化验证的同一TEST媒体；D13只确认恢复图片时Alt非空，没有另行截取Alt字段值，不把该项记为新的独立Alt测试证据。
- 计划促销只验证了无日期即时生效和未来开始日前不提前生效，未等待验证日期区间内及结束后的自动切换；缺货测试只验证前台购买控件状态，未尝试直接加购、购物车或结账。这些不阻塞D13，留到后续交易回归覆盖。

## D14 Local可变商品与Variation验收记录

| 范围 | 环境 | 结果 | 证据摘要 |
|---|---|---|---|
| Website Manager操作身份 | Local | 通过 | 独立`dentall_d12_manager`账号完成父商品、属性、Variation、默认值及前台流程；未使用Administrator |
| 父商品库存真相源 | Local | 修复后通过 | 启动时发现父商品数量库存1与Variation独立库存并存；取消父级数量库存并保存，重新打开库存页确认仍未勾选，父SKU继续保留 |
| 属性与组合 | Local | 通过 | Size、Shade均可见且用于Variation；2×2理论组合只创建3个合法组合，Large/Medium不出现在可选集合 |
| 父/变体SKU与价格 | Local | 通过 | 父SKU唯一；#51/#52为39.99，#53为49.99；父级显示39.99～49.99范围，选择后显示准确Variation价格 |
| 独立库存与Backorders | Local | 通过 | #51库存5、#52库存0、#53库存3，均禁止Backorders；#52显示Out of stock并禁用加购 |
| 默认Variation | Local | 通过并恢复 | 临时默认Small/Light后自动显示39.99、库存5及正确图片；结束时Size和Shade恢复无默认 |
| 非法组合 | Local | 通过 | 选择Large 105 mm后Shade不提供Medium，客户无法构造未建立的Large/Medium组合 |
| 物流继承与覆盖 | Local | 通过 | #51/#52有效值继承父级2 lb、8×8×3 in；#53覆盖2.5 lb、9×9×4 in |
| 加购与购物车 | Local | 通过并清理 | #53数量1成功加入；购物车显示Size、Shade、49.99单价与合计；结束后购物车恢复0件 |

- 本轮未创建订单，因此未验证库存扣减、取消/退款回补、订单状态、邮件、税费、运费或支付；这些不能从加购成功推导为已通过。
- 本轮未临时清空Variation价格，也未把默认值指向缺货Variation；两项作为后续边界测试保留，不代表当前已发现缺陷。
- #51/#52物流原始字段为空并继承父值的持久化证据沿用D12自动审计；D14只重新确认后台有效显示。普通终端因缺少LocalWP数据库连接环境未能重跑既有审计脚本，不得记为D14自动审计通过。
- 本轮未操作Staging、Production、DNS、真实支付、税费或物流配置，也未安装插件或修改代码。
- D14结果是D18前商品模型候选，不冻结正式商品组合、SKU、价格、库存或物流数据。

## D15 Local库存与物流字段验收记录

| 范围 | 环境 | 结果 | 证据摘要 |
|---|---|---|---|
| 三层数据职责 | Local与文档 | 通过 | 产品规格、单个销售包装Shipping数据和最终订单外箱已区分；Shipping字段不承载产品技术规格或整单外箱 |
| Simple物流 | Local #44 | 通过 | Website Manager确认TEST值1.2 lb、6×6×2 in保存在Simple自身；不冻结为正式商品数据 |
| Variable物流 | Local #46/#51～#53 | 通过 | 父级2 lb、8×8×3 in；#51/#52原始字段空且有效继承；#53明确覆盖2.5 lb、9×9×4 in |
| 数量与状态库存 | Local #44 | 通过并恢复 | 数量8模式切到仅`instock`后仍可购买且不显示数量；显式恢复数量8和禁止Backorders，后台、CRUD及前台一致 |
| 临时缺货 | Local #44 | 通过并恢复 | 数量0后保持发布、目录可见和原URL，前台显示Out of stock且无购买控件；恢复8后显示8 in stock并恢复加购 |
| 永久停售候选 | 文档审查 | 通过 | 不删除、不改Slug、不改发布/目录可见性；替代商品、301、Canonical、索引与文案交由D16决定 |
| 最终一致性 | Local | 通过 | 主审计45/45；独立Simple 23/23、Variable 17/17；最终父子库存、价格、组合、继承/覆盖和购物车基线一致 |

- D15最终基线：#44数量8、`instock`、禁止Backorders；#46不管理父级数量；#51/#52/#53数量5/0/3且禁止Backorders；默认Variation为空；购物车0。
- #52的`is_purchasable=true`只代表价格、发布状态等基础购买资格；它同时为`is_in_stock=false`、`outofstock`且禁止Backorders，结合D14前台证据确认实际不可购买。
- 当前Local单位仍为`lbs/in`，但承运商、物流插件和真实单个销售包装样本均未确定；未验证单位换算、Shipping Class、体积重、计费重、地区、装箱和正式运费。
- 本轮未创建订单，未验证库存扣减/预留/回补、结账、支付、税费、邮件、缓存或Production；未操作Staging、DNS、插件、代码或部署。
- 独立Review结论为P0=0、开放P1=0、C6新增P2=0；保留既有安全P2：Website Manager高影响WooCommerce设置仍依赖流程控制，D25前纳入培训和综合验收。

## D16商品SEO流程验收记录

| 周期 | 环境/依据 | 结果 | 证据摘要 |
|---|---|---|---|
| C2 Title唯一性 | Local首页、商店、#44、#46、404 | 通过 | Yoast启用、停用和恢复后均为1个Title；恢复后正常页面1个Canonical、404无Canonical，状态码保持200/404 |
| C4 URL流程 | 文档与WordPress 7.0.4核心核对 | 通过 | 已区分首次发布前修正、已发布永久迁移、重复页Canonical和无替代撤销；当前无真实301映射，未改任何环境URL或配置 |
| C5 缺货/停售生命周期 | 文档、D15交接证据与WooCommerce 11.0.0核心核对 | 通过（骨架） | D15临时缺货测试仅作为前置证据；D16 C5已规定临时缺货保持200/原URL/自身Canonical，永久停售按严格替代301、保留200或真实404/必要410分流。无真实停售样本，`Discontinued`文案与Schema尚未验收 |
| C6 Staging前置审计 | Staging后台与已发布TEST商品 | 部分通过 | WordPress 7.0.4、WooCommerce 11.0.0、DentAll Core 0.2.3和角色账号结构已核对；未安装Yoast，无法验收SEO字段界面/保存/输出。现有4个商品均为Simple TEST数据；已发布商品实际使用`/shop/{slug}/`，与候选`/product/{slug}/`不一致，需在D17真实URL验收前决定 |
| C6 Yoast授权安装与矩阵补测 | Staging首页、商店、两个已发布Simple商品、真实404 | 通过（Staging边界） | Yoast 28.2由Administrator安装、用户手动激活；5页均只有1个Title且输出`noindex, nofollow`，无重复Title。全站禁止索引时5页均无Canonical，不能替代Production自身Canonical验收 |
| C6 商品固定链接切换 | Staging商店、两个新商品URL、两个旧TEST URL | 通过（状态码待补） | 用户选择WooCommerce默认`/product/`；`/shop/`归档正常，两个`/product/{slug}/`商品页H1与单一Title正常并保持`noindex, nofollow`。旧`/shop/{slug}/`自动到达对应新URL；已确认发生跳转，未取得原始301/302状态码 |
| C6 Local固定链接同步 | Local后台与WooCommerce 11.0.0核心实现 | 通过（配置） | 用户选择“默认”并保存；刷新后回显第四项`product/`是核心将空默认值规范化为实际产品基础后的预期表现，最终URL仍为`/product/{slug}/`。未操作Production |

- C6初始阶段为只读审计；获得用户明确授权后，Administrator在Staging安装并由用户手动激活Yoast 28.2，用户将Staging与Local商品固定链接统一为`/product/{slug}/`。未操作Production，未升级WooCommerce、Breeze或其他插件，未改商品Slug、Canonical、robots、Sitemap、缓存或部署配置。
- C6未发现已执行范围内的P0/P1缺陷；Local与Staging固定链接差异已关闭。旧Staging TEST商品路径已确认发生跳转，但原始响应状态码未取得，仍不得写成已验证301。
- D16收口时的D17前置缺口为Variable、当前缺货、可下载、真实永久停售及Website Manager SEO字段持久化。D17已补Variable、TEST缺货Variation和SEO字段持久化；可下载、真实永久停售及Production Canonical仍待后续。第二人无指导试录未覆盖，但CR-007后不属于D24-D25当前待补；WM-A的内容流程人员门槛已在D24 A/C6按SOP辅助口径通过。TEST夹具不能替代真实业务内容。

## D17代表商品与SEO骨架验收记录

| 范围 | 环境 | 结果 | 证据摘要 |
|---|---|---|---|
| 5个代表样本 | Staging | 通过（骨架） | #32、#35为既有已发布Simple并补全Yoast字段；D17新建Simple草稿#45、Variable草稿#47和多图Simple草稿#52。累计5个完整样本，不是D17新建5个 |
| Simple字段与SEO | Staging #32/#35/#45/#52 | 通过（TEST） | 名称/H1、Slug、唯一TEST SKU、价格库存、分类、长短描述、主图及Yoast Title/Description保存与输出符合预期；#52另验证1张主图和3张图库 |
| Variable父子模型 | Staging #47/#48～#50 | 通过（TEST） | 父商品不管理数量库存；3个Variations各自维护唯一SKU、价格、数量和Backorders；A/A 39.99库存5，A/B 39.99库存0，B/A 49.99库存3 |
| 缺货与非法组合 | Staging #47 | 通过（TEST） | A/B显示Out of stock且加购不可用；B/B未建立并从Size B的Shade选项中省略；父商品与其他合法组合保持可用 |
| 物流继承与覆盖 | Staging #47 | 通过（TEST） | A/A、A/B继承父级2 lb、8×8×3 in；B/A覆盖2.5 lb、9×9×4 in；仅证明机制，不冻结Production单位或数值 |
| SEO与环境边界 | Staging 5样本 | 通过（Staging边界） | 单一Title/H1、Meta Description及`noindex, nofollow`符合预期；全站禁止索引时无Canonical，不能替代Production验收 |
| 发布与草稿隐私 | Staging | 通过 | 已发布#32/#35匿名请求200；草稿#45/#47/#52登录态预览正常、匿名请求404 |
| 独立回归 | Staging与既有证据 | 通过 | 主回归与独立测试Agent结论P0=0、P1=0、P2=0、P3=0；用户手动核对3个合法组合和1个非法组合，未执行加购 |

- 未覆盖：可下载商品、真实永久停售、真实业务促销、Production Canonical/索引/301、缓存、四端响应式视觉和第二身份人员无指导试录。前述业务/技术项分别等待可信资料或后续里程碑；第二身份按CR-007只在触发条件出现时补验，不属于D24-D25当前待补。它们不影响当前商品骨架继续，但真实内容尚不能验收。
- #31空壳草稿和#39历史不完整草稿未进入代表矩阵，D17未删除或修复；D18登记归档/清理候选，D25前决定TEST对象去留。
- 本轮未创建订单、未点击加购、未进入结账，未修改Production、插件、代码、数据库结构、固定链接、Canonical、robots、Sitemap、301、缓存、支付、税费、物流或部署。

## D18 C5 Local商品CSV导出验收记录

| 范围 | 环境 | 结果 | 证据摘要 |
|---|---|---|---|
| Website Manager商品导出权限 | Local | 修复后通过 | DentAll Core 0.2.5仅在商品列表、商品导出页、对应AJAX和下载请求临时满足`export`；角色数据库不持久化该能力 |
| 负向权限 | Local | 通过 | WordPress原生`/wp-admin/export.php`真实页面仍拒绝；用户与插件管理继续拒绝；能力边界审计7/7通过 |
| 原生CSV生成 | Local | 通过 | Website Manager从商品列表进入WooCommerce原生导出页，保持全部核心列、全部类型和全部分类，未勾选自定义元数据，成功下载2590字节CSV |
| 对象与唯一性 | Local | 通过 | 49列、5行；ID为44、46、51、52、53，ID与SKU均唯一；Simple、Variable及3个Variations齐全 |
| 父子与业务字段 | Local | 通过 | 支持重复表头的按列位置解析完成98项断言，父SKU关系、价格、库存、缺货、Backorders、属性、物流继承空值/覆盖和图片均与CRUD审计一致 |
| 独立回归 | Local | 通过 | C5 7/7、C4 15/15、Simple 23/23、Variable 17/17、D18 C2 8/8均PASS；角色版本6、插件0.2.5、临时SKU无残留 |
| 中文CSV表头 | Local | P2，延期到D25 | Upsells与Cross-sells都翻译为“交叉销售”，标准PowerShell `Import-Csv`因重复成员拒绝解析；按列位置解析数据正常，但开放批量回导前必须完成英文导出、表头规范化或隔离回导验证 |

- CSV SHA-256：`9679D3E4E063C4CF3B568E1F8D18E1254039CD8F09AC1C788D6DFCBAF0DBFC86`。文件只保存在本机下载目录，不进入Git。
- 默认核心CSV没有Yoast、`wpseo`或其他自定义Meta列；图片均为`http://dentall.local`绝对URL。因此本文件不是完整SEO备份、uploads备份或可直接跨环境导入包。
- C5没有执行CSV回导、Staging/Production导出、媒体迁移、商品删除或TEST对象状态变化；这些边界分别留给C6和D25。

## D18 C6 Staging同步与综合复核记录

| 范围 | 环境 | 结果 | 证据摘要 |
|---|---|---|---|
| 部署边界 | Git / Cloudways Staging | 通过 | `main`提交`b1faac2`只包含DentAll Core两个源码文件；`deploy/staging`提交`e9e21c4`相对0.2.3只修改相同两个文件，Cloudways Fetch/Pull成功。未同步数据库、uploads、第三方插件、测试CSV或项目文档 |
| 部署前回归 | Local | 通过 | PHP语法3项通过；C5权限7/7、C4角色与保存15/15、Simple 23/23、Variable 17/17、D18父子SKU 8/8均PASS。已知LocalWP CLI `php_imagick.dll`启动警告不影响GD、MySQL或本次审计 |
| Website Manager身份与系统边界 | Staging / XuDan | 通过 | 后台确认账号为Xu, Dan；用户、插件、主题、Tools和WordPress系统设置入口均未开放 |
| Yoast高级字段 | Staging #47 | 通过 | 商品编辑页显示Yoast Advanced、搜索引擎显示、链接跟随、高级Meta Robots、面包屑标题及Canonical字段；全站`noindex`警告符合受保护Staging预期 |
| 原始自定义字段面板 | Staging #47 | 通过 | “显示选项”没有Custom Fields，商品页面也没有原始自定义字段面板；本轮未修改或保存商品字段 |
| 商品导出权限 | Staging | 通过 | 商品列表显示“导出”，原生商品导出页可访问并完成CSV下载；直接访问`/wp-admin/export.php`仍被拒绝，证明未开放WordPress全站内容导出 |
| CSV结构与对象 | Staging | 通过 | 文件6312字节、49列、10行；ID为31、32、35、39、45、47、48、49、50、52，ID唯一且非空SKU唯一；6个Simple、1个Variable及3个Variations齐全 |
| 父子与业务字段 | Staging | 通过 | 39/39项按列断言通过：父SKU关系、价格39.99/39.99/49.99、库存5/0/3、有货状态1/0/1、Backorders关闭、Size/Shade三个合法组合、B/B非法组合缺失、两项物流继承空值、一项2.5 lb及9×9×4 in覆盖和Staging图片URL均符合D17基线 |
| 中文CSV表头 | Staging | P2，延期到D25 | 与Local一致，Upsells与Cross-sells均翻译为“交叉销售”；只读导出和按列验证正常，但Website Manager不得直接批量回导，开发者在D25前完成英文导出、表头规范化或隔离回导验证 |
| 独立Review | Local差异与C6证据 | 通过 | Code Review最终P0～P3均为0；安全复核发现角色权限普通代码回滚不能撤权的P2，补充RUNBOOK的版本化撤权规则后关闭，最终P0～P3均为0；测试Agent仅保留既有中文CSV表头P2 |

- Staging CSV SHA-256：`537CCCB2B9E2C4ADCB2FD06EF83806A390552AD5BB9FD6E9AB01C3C59A0F8D90`。文件只保存在本机下载目录，不进入Git。
- 本轮没有勾选“导出自定义元数据”，CSV没有Yoast或其他`Meta:`列；因此仍不能代替Yoast元数据、数据库或uploads备份与恢复演练。
- C6同步了插件代码并由角色版本6触发Website Manager白名单同步；没有修改商品内容、URL、Slug、Canonical、robots、Sitemap、301、缓存、支付、税费、物流配置或Production。高级SEO能力属于数据库角色数据，撤权路径见`RUNBOOK.md`。

## D20长文TEST夹具验证记录

| 范围 | 环境 | 结果 | 证据摘要 |
|---|---|---|---|
| 草稿字段与媒体 | Staging #68 / Website Manager | 通过（TEST机制） | 标题、摘要、Slug、作者和草稿状态读回；分类为`TEST D12 Content`，`Uncategorized`未选；特色图与正文图均使用附件#59，正文图读回上下文alt |
| 正文与登录态预览 | Staging登录态预览 | 通过 | 文章输出1个H1、4个业务H2、2个业务H3、无序/有序列表、特色图、正文图和1条相关内链；正文不含中文`正文`、连写错误或恢复测试标记 |
| 内容级SEO | Yoast搜索外观 / 预览 | 通过 | SEO Title为`TEST D20 Long-form Article \| DentAll`，Meta Description按TEST说明保存并读回；Focus keyphrase留空，Slug未变，Canonical/robots未手工覆盖 |
| 修订恢复 | Staging修订界面 | 通过 | 在正文保存临时标记后恢复修订#73，修订数5→7；服务器重新载入后标记消失，草稿状态、分类和特色图保留 |
| 匿名与链接边界 | Staging匿名HTTP / Sitemap | 通过 | `/?p=68`最终404；`post-sitemap.xml`不包含#68或Slug；正文内部链接目标最终200；文章未发布 |
| 正式内容门槛 | D20范围 | 待D24 | #59源图1280×1372，仅验证媒体机制，不满足正式特色图16:9与授权验收；真实事实、正式素材和Production SEO未验 |

## 缺陷等级

- P0：支付错误、数据丢失、安全漏洞、生产不可用。
- P1：无法下单、主要页面阻塞、严重响应式问题。
- P2：普通功能或视觉缺陷，有合理绕行方案。
- P3：轻微视觉、文案和优化建议。

## 发布门槛

- P0和P1缺陷为0。
- P2缺陷有负责人、计划和业务接受记录。
- 至少完成一笔Staging完整测试订单。
- 缓存开启状态下再次完成交易回归。
- 数据库和uploads备份可用；回滚步骤明确。
- SEO、邮件、支付、物流和关键页面有验证证据。
- GA4/GTM关键事件、金额与交易ID有验证证据，且无重复购买事件。

## D23内容治理验证清单

- [x] 依据CR-007，D24-D25由WM-A使用本人独立账号完成Website Manager角色级创建、编辑、待审核、发布和撤回矩阵；WM-B不在当前范围。未来第二位人员正式上岗或出现权限/交接/审计差异时，仍必须使用独立账号补验，禁止Administrator代替或共享WM-A。
- [x] 已由WM-A验证`Pending Review`在当前单一Website Manager角色下只是流程信号，不会强制阻止作者自行发布；若业务要求强制职责分离，测试应明确判定当前方案不满足并触发新范围评估。
- [x] 已对从未发布的Page #76验证`Draft → Pending Review → Publish`；确认`Pending Review`只适用于未发布内容，且同一Website Manager仍可自行发布。
- [x] 已对Published Page #76做受控低风险正文修改，实证点击“更新”立即改变公开内容，不存在原生“旧版本继续公开、新版本待审”。
- [x] 高风险更新已在A/C5使用明确标记的独立审阅Draft #90演练，源Post #24保持不变；审阅副本匿名404、未进入Post Sitemap，也未作为正式内链或公开内容使用。
- [x] 已验证`post_author`表示当前内容作者；通过V1/V2正文标记和revision #85验证受支持正文的修订恢复、修改账号与时间。没有把分类、特色图或Yoast元数据算入恢复范围，也没有从`post_author`或内容修订推断状态动作人。
- [x] 发布、更新、撤回与回收站状态动作由执行人使用本人账号，并已在发布/变更登记中记录对象ID、动作、账号、时间、依据与恢复方案；公司控制的唯一存放位置、备份责任和首条真实记录仍待验。
- [x] 已对Page #76分别验证普通“从回收站恢复”和提示条“立即撤销”：普通恢复得到Draft且不公开；立即撤销恢复移入前的Published并重新公开。最终已撤回Draft并完成匿名下线回归。
- [x] TEST机制与发布检查单已覆盖业务事实、分类、媒体alt、授权登记、Slug、SEO字段和相关内链；#90完成字段抽样，高风险事实未确认时保持草稿或待审核。正式样本执行仍待业务输入。
- [ ] 每个拟公开素材在业务可访问的登记入口具有资产ID、授权依据位置、批准人及日期和发布状态，并按项目责任同步到`CONTENT_ASSET_REGISTER.md`；`待核验`、`仅内部`与`仅开发占位`素材不进入正式发布。
- [x] 已在明确TEST Page #76演练误发布紧急撤回为Draft、登记账号/原URL/缓存处理，并验证匿名精确URL、REST、Sitemap与导航均不再公开；永久URL的301/410、Canonical与正式替换方案仍只在真实迁移场景另验。
- [x] 已明确并通过机制/边界验证：原生修订与素材登记不覆盖所有WooCommerce、媒体与SEO元数据操作；未安装审计日志插件，第二版按需评估。

## D24 A/C2 Page草稿与权限走查记录

环境为受保护且禁止索引的Staging。所有结果均来自用户以实际WM-A账号操作并提供的2026-08-21截图；未使用Administrator代替Website Manager执行Page流程。依据CR-007，C2结论收口为“WM-A代表Website Manager角色级技术路径通过，WM-B当前不适用/条件性补验”。这不证明第二身份、跨账号归属、多人协作或两名人员培训，也不等于D24整体完成。

| 用例ID | 操作与对象 | 预期 | 实际结果 | 状态 |
|---|---|---|---|---|
| D24-C2-01 | WM-A登录并核对左侧菜单 | 可见内容与商城日常入口；不可见用户、插件、主题、工具和WordPress全局设置 | WM-A菜单符合预期；未使用Administrator作为测试账号 | 通过 |
| D24-C2-02 | WM-A创建并保存Page #76 `TEST D24 Page Field Walkthrough` | 标题、首次发布前Slug、正文原生区块、作者和Draft状态可保存并回读 | Slug为`test-d24-page-field-walkthrough`；作者为WM-A；状态Draft；默认模板、无父页面、评论关闭；正文为Paragraph、H2和List | 通过 |
| D24-C2-03 | WM-A登录态预览Page #76 | 预览可见且标题由模板输出H1，正文H2与List层级正确 | `?page_id=76&preview=true`登录态预览正常；未点击发布 | 通过 |
| D24-C2-04 | 无痕窗口访问`?page_id=76` | 未登录WordPress时草稿不可公开访问 | 返回Error 404，TEST正文未泄露 | 通过 |
| D24-C2-05 | 无痕访问`page-sitemap.xml`并搜索Page #76 Slug | Draft不得进入Sitemap | 浏览器查找结果为`0/0` | 通过 |
| D24-C2-06 | WM-A直接访问`users.php`、`plugins.php`、`themes.php`、`options-general.php`和`tools.php` | 五类系统管理入口均拒绝 | 用户报告5项均拒绝；`users.php`与`tools.php`有截图证据 | 通过 |
| D24-C2-07 | WM-B打开并修改WM-A创建的Draft Page，再由WM-A回读修订 | 仅用于验证第二身份、跨账号归属和人员协作，不重复证明相同角色能力 | CR-007已将该项从D24-D25当前范围移除；没有创建WM-B，也没有用Administrator、历史测试账号、共享账号或改显示名伪造结果。出现第二人上岗、权限差异、强制互审、交接、并发登记或账号级插件差异时再启用 | 不在当前范围（CR-007） |

截至C2结束时没有发布Page #76，也没有修改菜单、Reading设置、Canonical、robots、全局SEO、缓存、支付、物流、DNS或Production；当时Page #76仍为明确TEST草稿。后续A/C3按下节继续状态与导航实测。CR-007之后，C2按WM-A角色级路径关闭，不再等待WM-B；在C2收口时，ADR-024仍需A/C4的已发布更新、修订、回收站与两类恢复，以及登记簿单一存放位置和备份责任。前一组A/C4缺口已在下节关闭，文件位置与备份仍待后续验收。

## D24 A/C3 Page状态、缓存与受控导航验证记录

环境继续为受保护且禁止索引的Staging。Page #76标题为`TEST D24 Page Field Walkthrough`，Slug为`test-d24-page-field-walkthrough`。Page状态动作由WM-A使用本人Website Manager账号完成；建立站点级受控菜单由Administrator执行。结论为“A/C3的WM-A角色级技术路径通过”；CR-007排除的第二身份与多人协作没有执行，也不能写成通过。A/C3收口时3篇代表文章、培训和D24整体均未完成；后续A/C5培训、A/C6 SOP辅助验收及A/C7抽样见本文件后续章节，正式内容仍待。

| 用例ID | 操作与对象 | 预期 | 实际结果 | 状态 |
|---|---|---|---|---|
| D24-C3-01 | WM-A将Page #76从`Draft`改为`Pending Review`并保存 | 待审状态保存；未发布内容继续不可匿名访问，也不进入Page Sitemap | 编辑器状态回读为待审；匿名`?page_id=76`仍返回404；`page-sitemap.xml`搜索Slug仍为`0/0` | 通过 |
| D24-C3-02 | 同一WM-A在`Pending Review`状态检查发布能力 | 第一版非强制互审模型下，同角色Website Manager仍具备发布能力 | 同一WM-A仍看到并可使用“发布”按钮，证明`Pending Review`只是协作信号，不是系统强制审批闸门 | 通过（边界已证实） |
| D24-C3-03 | WM-A首次发布Page #76 | 精确固定链接200、正文可见、Page Sitemap包含Slug | 匿名精确URL正常显示TEST页面；Sitemap由4条变为5条，浏览器搜索Slug为`1/1` | 通过 |
| D24-C3-04 | 首次发布后检查桌面和手持导航 | 发布Page不应未经选择自动进入正式导航 | 发现站点没有任何已分配菜单；Storefront对未分配的`Primary`和`Handheld`位置使用Page列表fallback，Page #76随发布自动进入两份导航 | P1，已关闭 |
| D24-C3-05 | WM-A将已发布Page #76撤回为`Draft` | Sitemap移除Slug，匿名精确URL不可访问 | Sitemap已移除Slug，但精确固定链接仍返回HTTP 200，响应为旧发布HTML、`X-Cache: HIT`且当时`Age`约102秒；随机查询参数请求为404/MISS，证明是Breeze/Varnish旧缓存 | P0，已关闭 |
| D24-C3-06 | 清理Breeze缓存，并在可用时同步清理Varnish，再复测精确原URL | 不带随机参数的精确原URL返回404，且Sitemap与导航均不包含Page #76 | 用户执行清缓存后，精确原URL返回HTTP 404、`X-Cache: MISS`、`Age: 0`；Sitemap和首页均无Page #76 | 通过，关闭P0 |
| D24-C3-07 | Administrator建立菜单ID 29 `TEST Staging Controlled Navigation` | 显式控制Storefront导航，不再依赖fallback | 菜单顺序为Home、Blog、Cart、Checkout、My account、Shop；显式分配`Primary Menu`与`Handheld Menu`，关闭“自动添加新的顶级页面”，`Secondary Menu`未分配 | 通过，关闭P1 |
| D24-C3-08 | 受控菜单回归 | Primary与Handheld都包含6个批准入口，不包含Sample、D12 TEST或Page #76 | 服务端HTML同时找到Primary与Handheld两份菜单；6个批准入口均存在，Sample Page、D12 TEST Page和D24 Page #76均不存在 | 通过 |
| D24-C3-09 | WM-A重新发布Page #76并完成最终回归 | 精确URL200、Sitemap包含Slug，但Primary/Handheld不自动增加该Page | 最终精确URL200，TEST正文可见；Sitemap包含Slug；Primary和Handheld都未出现Page #76、D12 TEST或Sample Page。首次即时回归为`X-Cache: MISS`、`Age: 0`，后续已发布页面变为`X-Cache: HIT`、`Age`约614秒属于正常缓存，页面仍为200 | 通过 |
| D24-C3-10 | 状态与配置留痕 | 状态动作不能只依赖`post_author`或修订记录 | 当时写入`.xlsx`的6条A/C3记录已完整迁移到`CONTENT_ASSET_REGISTER.md`；2026-08-21起Markdown为唯一活动登记簿，生成的Excel已于2026-08-22删除且不入库 | 通过（登记结构）；公司控制的Git远程归属与备份责任待验 |

A/C3最终状态：Page #76保留为已发布TEST夹具，精确URL200并进入Page Sitemap，但不会因发布状态自动加入Primary或Handheld菜单。撤回为Draft时必须同时验证Sitemap和**不带随机参数的精确原URL**；若原URL仍命中旧缓存并返回200，清理Breeze/Varnish后复测到404才算真正下线。当前只操作受保护Staging；没有修改Reading设置、固定链接、Canonical、robots、全局SEO、支付、物流、DNS或Production。

## D24 A/C4 已发布更新、修订与两类恢复验证记录

A/C4继续复用受保护Staging的Page #76，执行账号为WM-A。普通“从回收站恢复”和移入回收站后的提示条“立即撤销”按两个独立用例完成；最终状态为Draft，并在清理Breeze/Varnish后用无参数精确URL验收。

| 用例ID | 操作与对象 | 预期 | 实际结果 | 状态 |
|---|---|---|---|---|
| D24-C4-01 | 建立Published公开面基线 | 精确URL 200；Page Sitemap含Slug；Primary/Handheld不含#76；正文无C4标记 | 2026-08-21只读HTTP复核：精确URL 200、`X-Cache: HIT`、`Age: 2262`；Sitemap含Slug；两份菜单均为既定6项且不含#76；正文无`TEST C4`标记 | 通过 |
| D24-C4-02 | 在Published #76末尾新增`TEST C4 low-risk update marker — 2026-08-21.`并更新 | 状态保持Published；无参数精确URL显示标记；Sitemap仍含；导航仍不含；标题/Slug/作者/模板/Yoast不变 | 用户截图确认状态仍为Published、修订数为3且标题、Slug、作者、模板未变；无参数精确URL显示完整标记并返回200、`X-Cache: MISS`、`Age: 0`，Page Sitemap仍含Slug，Primary与Handheld各6项且均不含#76 | 通过 |
| D24-C4-03 | 把标记改为`TEST C4 temporary revision V2 — 2026-08-21.`并更新 | 公开页显示V2，修订数增加；仍为Published | 用户截图确认状态仍为Published、修订数3→4且标题、Slug、作者、模板未变；无参数精确URL显示完整V2、V1消失，返回200、`X-Cache: MISS`、`Age: 0`；Sitemap仍含，Primary与Handheld各6项且均不含#76 | 通过 |
| D24-C4-04 | 从原生修订恢复包含第一条标记的上一版本 | 仍为Published；第一条标记恢复、V2消失；修订账号/时间可核对；标题/Slug/作者不变 | 修订界面确认目标为revision #85（WM-A，2026-08-21 07:17），其右侧新增内容为V1；恢复后编辑器显示V1、状态Published、修订数4→5，标题/Slug/作者不变。无参数精确URL显示V1、V2消失，返回200、`X-Cache: MISS`、`Age: 0`；Sitemap仍含，Primary/Handheld均不含#76 | 通过 |
| D24-C4-05 | 从Published移入回收站，不点击“立即撤销”，清Breeze/Varnish | 对象在Trash；无参数精确URL 404/MISS；Sitemap与导航不含#76 | 用户截图确认#76位于“页面 → 回收站”且回收站计数为1，未使用即时撤销；清缓存后无参数精确URL返回404、`X-Cache: MISS`、`Age: 0`且无TEST正文，Page Sitemap移除Slug，Primary/Handheld各6项且均不含#76 | 通过 |
| D24-C4-06 | 在“页面 → 回收站”点击普通“恢复” | 恢复为Draft，不自动公开；精确URL继续404，Sitemap与导航继续无#76 | 用户从回收站对#76执行普通恢复后，“草稿”筛选计数变为3且列表包含#76；无参数精确URL继续404、`X-Cache: MISS`、`Age: 0`且无TEST正文，Page Sitemap无Slug，Primary/Handheld各6项且均不含#76。重新打开编辑器后状态Draft、V1保留、V2不存在、修订数仍为5，标题/Slug/作者/模板未变 | 通过 |
| D24-C4-07 | 重走发布检查并重新发布 | 精确URL 200；Sitemap含Slug；导航仍不含#76 | 用户截图确认V1保持并出现“页面已发布”提示；无参数精确URL返回200、`X-Cache: MISS`、`Age: 0`并显示V1、无V2，Page Sitemap重新包含Slug，Primary/Handheld各6项且均不含#76 | 通过 |
| D24-C4-08 | 再次移入回收站并点击页面提示条“立即撤销” | 验证恢复移入前的Published状态并重新公开；不得误写成普通恢复到Draft | 用户在Published #76移入回收站后立即点击系统提示中的撤销，未在两步之间清缓存或使用普通恢复；截图确认#76回到Published列表，公开REST返回`status: publish`、revision count 5。无参数精确URL返回200、`X-Cache: MISS`、`Age: 0`并显示V1、无V2，Sitemap重新包含，Primary/Handheld各6项且均不含#76 | 通过；确认存在误重新公开风险 |
| D24-C4-09 | 最终改回Draft并清Breeze/Varnish | 无参数精确URL返回404且无正文泄漏；Sitemap与两份导航无#76；对象不留Trash或Published | 用户截图确认后台状态Draft、V1保留、V2不存在、修订数5，标题/Slug/作者/模板未变。清Breeze与适用的Varnish后，无参数精确URL返回404且无V1/V2/标题正文泄漏；匿名REST返回401，Page Sitemap与Primary/Handheld均无#76。并发首轮回归后该404响应可被安全缓存为`X-Cache: HIT`、`Age: 0`，未出现旧200 | 通过；最终保持Draft |
| D24-C4-10 | 登记上述状态动作 | 每项具有对象ID/URL、前后状态、执行人、证据、时间、恢复方案与验证结果 | 当时写入`.xlsx`的D24-C4-0001～0010共10条记录已完整迁移到`CONTENT_ASSET_REGISTER.md`，连同C3共16条历史记录；本轮未用媒体，未新增虚构素材记录。Excel生成文件已删除且不入库 | 通过（登记结构）；公司控制的Git远程归属与备份责任仍待验 |

A/C4停止条件：Draft、Trash或普通恢复后清缓存仍返回200，普通恢复意外成为Published，或内容丢失为P0；修订恢复错误版本/状态、清缓存后内容仍旧、导航出现#76或Sitemap与状态持续不一致为P1。出现P0/P1时停止后续状态动作，优先恢复Draft、清缓存并保留证据。

A/C4结论：10个用例全部通过，P0/P1开放项为0。普通恢复与即时撤销的状态差异、已发布更新会直接公开、修订恢复会直接改变Published对象以及撤回后的缓存门槛均已实测并写入中文手册。Page #76最终保持Draft。A/C4收口时高风险更新审阅与培训尚未执行；后续A/C5已完成有指导演练，A/C6已按SOP辅助口径通过，A/C7也已按后文抽样通过。上述技术与人员结论仍不等于真实内容/素材、公司控制Git远程与备份或D24整体通过。

## D24 A/C5 真实样本边界与引导培训记录

A/C5由WM-A在指导下完成，用于验证代表对象、独立审阅草稿和内容级字段路径。它不是无提示人员验收，也不能把TEST对象、示例政策或未授权素材升级为正式业务内容。

| 用例ID | 操作与对象 | 预期 | 实际结果 | 状态 |
|---|---|---|---|---|
| D24-C5-01 | 只读检查Page #11 `Refund and Returns Policy` | 识别示例文案和未经批准的政策，不修改、不发布 | 发现示例文本及未经业务批准的“30天”政策；对象保持Draft，未作为正式固定Page样本 | 通过（正确排除） |
| D24-C5-02 | 记录Post #24 `TEST D12 Manager Published Post`公开源基线 | Published源不被高风险演练直接修改 | 已记录Published状态、分类`TEST D12 Content`、标签`test-d12-manager`、评论开放和2个修订；没有直接修改源对象 | 通过（基线保持） |
| D24-C5-03 | 建立Post #90 `TEST D24 Review Copy — Manager Published Post`独立审阅草稿 | 高风险改写在独立Draft内准备，源#24继续公开且不变 | WM-A按指导完成正文结构、摘要、分类/标签、评论、内链与Yoast；#90保持Draft，源#24未改 | 通过（机制） |
| D24-C5-04 | 验证#90预览与公开隔离 | 登录态可预览；匿名精确URL与`?p=90`不可见；Post Sitemap不含#90 | 登录态预览可用；匿名访问为404，Sitemap排除，未进入正式导航/内链；源#24仍可公开访问 | 通过 |
| D24-C5-05 | 验证#90特色图与授权边界 | 不合比例或无授权证据的素材不得作为正式特色图 | 竖版TEST特色图被判定不符合16:9基线并移除；没有真实授权素材记录，正式16:9素材仍待业务提供 | 通过（治理分支）；正式素材未验收 |
| D24-C5-06 | 引导更新Post #68 `TEST D20 Long-form Article` | 保持Draft/TEST，完成长文结构、内链、标签、评论和内容级Yoast检查 | WM-A按指导补充TEST警示、标签、内链和Yoast，关闭评论，移除不合格特色图并保留正文TEST图；用户确认完成 | A/C5有指导完成；A/C6后续按SOP辅助口径通过 |
| D24-C5-07 | 预览#68并交接复核边界 | 登录态预览可用；匿名不可公开；不在本步把用户操作等同独立验收 | 用户提供`?p=68`登录态预览并确认完成；A/C5当时未继续逐页代审，2026-08-22只读回归确认匿名ID路径404且Post Sitemap排除 | A/C5有指导完成；A/C6后续通过 |
| D24-C5-08 | 引导检查Page #76并保存内容级Yoast | 保持Draft、默认模板、无父级、评论关闭；Yoast内容级字段可保存 | 用户按指导完成并确认；Page继续Draft，既有C4正文标记保留。2026-08-22只读回归确认匿名ID路径404、Page Sitemap与首页排除 | A/C5有指导完成；A/C6后续通过 |

A/C5结论：WM-A有指导的Post/Page字段操作、高风险独立审阅草稿和素材拒绝分支已覆盖，开放P0/P1为0；相关8条事实已补录到`CONTENT_ASSET_REGISTER.md`。该结论描述A/C5收口时状态；后续A/C6-C7结果见下文。截至A/C5当时，正式3篇文章＋1个业务Page、获授权16:9素材和C7仍未完成，因此D24整体仍在进行中；公司控制Git远程归属/备份属于D25/M3门槛。

## D24 A/C6 SOP辅助人员验收记录

2026-08-22，项目负责人明确接受“可查阅批准的中文Markdown SOP/检查清单”的人员验收方式，并要求将C6标记通过。对象ID、Slug、字段示例值、修订编号、按钮位置与完整点击顺序无需背诵；操作者必须理解公开状态、风险分支、素材/事实闸门、缓存、修订边界、停止条件和登记责任。允许查SOP不等于允许旁人逐点击发口令，也不等于免除验证与留证。

| 用例ID | 操作与对象 | 预期 | 实际结果 | 状态 |
|---|---|---|---|---|
| D24-C6-01 | 调整人员验收方法 | 可查批准SOP；不把“独立”误写成背诵或闭卷；不确定时停止并升级 | CR-009与ADR-026记录新口径，操作手册写明“无需背诵/必须理解/停止条件”；项目负责人明确接受 | 通过 |
| D24-C6-02 | 复核既有实操与缺陷状态 | A/C3～A/C5已有WM-A实际操作、截图、修订/恢复与登记证据；开放P0/P1为0 | 既有D24记录满足；本轮不补造新的闭卷后台操作或精确操作时间 | 通过（证据复用） |
| D24-C6-03 | 只读公开面安全回归 | Published源#24可访问；Draft #90/#68/#76匿名隔离且不进入相应Sitemap/首页 | 2026-08-22：`?p=24`为200；`?p=90`、`?p=68`、`?page_id=76`均为404；#90/#68对应Slug不在Post Sitemap，#76对应Slug不在Page Sitemap且首页无#76 | 通过 |
| D24-C6-04 | 验收边界 | C6通过不得扩写为C7、真实内容/素材、WM-B、Git治理或Production通过 | A/C6收口时文档继续保留C7与正式内容/素材为未完成、Git交接为D25门槛；WM-B为CR-007条件性补验。C7后来依据独立实操抽样关闭 | 通过 |

A/C6结论：**通过（SOP辅助、项目负责人接受）**。结论依据A/C3～A/C5既有实操与回归证据、开放P0/P1为0、2026-08-22只读公开面回归及项目负责人明确接受；不声称完成过闭卷或完全无提示复跑，也不代表当天新增或修改了WordPress内容。截至A/C6收口时C7与D24整体尚未通过；后续C7结果见下节。

## D24 A/C7 可查SOP抽样与收口记录

2026-08-22，WM-A依据批准的中文SOP完成当前对象状态、Post/Page字段、登录态预览、匿名隔离、修订恢复和登记抽样。网站操作由用户在实际WM-A会话中完成；Codex执行匿名HTTP、REST、Sitemap、首页和Git文档差异复核。本节不把TEST对象扩写为正式内容或授权素材。

| 用例ID | 操作与对象 | 预期 | 实际结果 | 状态 |
|---|---|---|---|---|
| D24-C7-01 | 只读核对#24/#90/#68/#76状态、用途与风险边界 | #24为Published源基线且不直接做高风险修改；#90/#68/#76为明确TEST Draft | WM-A逐项打开并确认四项一致；本步未保存或发布任何对象 | 通过 |
| D24-C7-02 | 抽查Post #90字段与登录态预览 | Draft；摘要、TEST分类/标签、评论、内链和Yoast可读回；无未授权特色图；登录预览可用 | WM-A确认Draft、摘要已填、分类/标签已选、评论关闭、特色图为空、`published source article`链接指向#24公开源、Yoast Title/Meta已填，登录预览正常 | 通过 |
| D24-C7-03 | Page #76保存临时正文标记 | 只改变明确TEST正文；状态保持Draft；匿名URL/REST/Sitemap/首页继续隔离 | 新增`TEST C7 temporary revision marker — 2026-08-22.`并保存草稿，修订数5→6；公开URL 404、REST 401、Page Sitemap和首页均无#76 | 通过 |
| D24-C7-04 | 恢复#76无C7标记的上一版本并复核非修订字段 | C7标记消失，既有C4标记保留；仍为Draft；Slug、作者、模板、父级、评论与Yoast填写状态不变 | 恢复后修订数为7，C7消失、C4保留；用户确认状态及非修订字段均未变化，登录态预览正常；公开URL继续404、REST 401、Sitemap/首页排除 | 通过 |
| D24-C7-05 | 公开面全链路回归 | #24公开正常；三个Draft匿名不可见且不进入对应公开发现面 | `?p=24`为200且标题/公开路由存在；`?p=90`、`?p=68`、`?page_id=76`为404；四条最终响应均为`X-Cache: MISS`、`Age: 0`；匿名REST对#90/#68/#76为401；#68/#90不在Post Sitemap，#76不在Page Sitemap或首页 | 通过 |
| D24-C7-06 | Markdown登记、Git差异与移交边界 | 只登记真实动作；P0/P1为0；未完成业务输入与治理门槛准确移交 | `CONTENT_ASSET_REGISTER.md`新增D24-C7记录，保存后已重开；定向Git差异与`git diff --check`通过，没有虚构素材授权。3篇正式文章＋1个正式Page和授权16:9素材仍是D24未完成项；公司控制Git远程/备份/交接为D25/M3门槛 | 通过 |

A/C7结论：**通过，开放P0/P1为0。** 当前内容生产线的TEST状态、字段、预览、公开隔离、修订恢复、登记和停止边界完成抽样；D24整体仍因正式3篇文章＋1个Page及授权16:9素材未验收而保持进行中。公司控制Git远程、备份和交接继续作为D25/M3门槛；Production、WM-B条件性补验和正式SEO输出不在本结论内。

## D25 C1 导入前恢复点与只读基线

C1只建立可恢复点、Local/Staging当前事实和TEST对象保护边界；没有导入、保存、发布、删除、清缓存、部署或修改全站设置。

| 用例ID | 操作与对象 | 预期 | 实际结果 | 状态 |
|---|---|---|---|---|
| D25-C1-01 | Cloudways应用备份 | 商品导入前存在包含文件与数据库的恢复点；不点击Restore | 用户提供Cloudways完成截图；Last Backup Date为`2026-08-22 03:29:57 UTC`（本地`11:29:57`），本轮未执行恢复 | 通过（备份存在）；恢复演练未测 |
| D25-C1-02 | Git、DentAll Core与角色代码基线 | 记录可复现代码版本；Website Manager当前不得拥有持久全局导入能力 | `main` HEAD `f883ab92abc415c07accd5acfedceb2d11500845`；DentAll Core 0.2.5/角色版本6；角色无`import/export`，商品导出只在指定请求临时授予`export` | 通过（只读） |
| D25-C1-03 | Local SQL与商品基线 | 锁定导入前父子、SKU、Slug和全局属性；不启动或修改数据库 | `app/sql/local.sql`为2,903,320 bytes、SHA-256 `A07944D2990D0E19388CEE9C90B60946B7B4908679BE3D055AB561047F3D471C`；#44/#46/#51～#53完整，无重复SKU/Slug或孤儿Variation；#43为空auto-draft | 通过（静态只读） |
| D25-C1-04 | Staging当前商品导出 | 固定文件指纹、表头、行数和对象列表 | `wc-product-export-22-8-2026-1787369790244.csv`为6,884 bytes、UTF-8 BOM、49列、11行，SHA-256 `20F56DF58F68A425EF9624A5DEC69209319CEB7FE799822148BF9B24B9D5CBC7` | 通过（只读证据） |
| D25-C1-05 | 新旧Staging CSV逐列差异 | 已有对象不得出现未解释变化；只允许已登记新增对象 | D18旧10个对象的数据行逐列完全不变，仅新增Draft #58；无重复非空SKU、无孤儿Variation，空SKU仅#31 | 通过 |
| D25-C1-06 | 中文表头安全边界 | 不把含重复表头的本地化导出当作受控回导模板 | 第35/36列仍同为“交叉销售”；`RSK-016`继续开放到C2，当前CSV禁止直接回导 | P2，已守门 |
| D25-C1-07 | 重量/尺寸单位差异 | 识别数值语义变化并在批量录入前固定第一版单位 | 新旧表头从`lbs/in`变为`kg/cm`且旧数值不变；用户确认第一版采用`kg/cm`。历史TEST值不换算；Local对齐列为C2写入前门槛 | 通过（ADR-027）；C2需对齐Local |
| D25-C1-08 | TEST对象保护清单 | 现有代表商品、内容、媒体和菜单不得被C1清理或覆盖 | 已锁定商品#32/#35/#45/#47～#50/#52/#58、Post #24/#68/#90、Page #76、媒体#59/#60和菜单#29等保护对象；删除候选同样未动 | 通过 |
| D25-C1-09 | 环境版本事实 | 不继续沿用失真的“双环境版本一致”口径 | Local为WordPress 7.0.4；Staging后台显示7.1。未升级/降级；C2在Staging做实际兼容回归 | P2，已登记`RSK-028` |

C1结论：**通过，开放P0/P1为0。** 恢复点、Local/代码静态基线、Staging CSV指纹、TEST保护边界和第一版`kg/cm`单位已确定。中文重复表头、Local单位对齐和WordPress版本差异作为C2明确守门项；CSV导入、错误行隔离、回滚、备份恢复、正式商品内容与D25批量开放均尚未通过。

## D25 C2 原生导入权限与可追溯边界

用户已通过ADR-029/CR-010确认第一版使用WooCommerce原生商品CSV导入/导出，并接受全局`import`为粗粒度权限；第一轮“只新增Draft”属于SOP与验收约束，不是自定义服务端硬锁。C2当前只完成Local权限同步和只读审计，没有上传CSV、创建/更新商品或部署Staging。

| 用例ID | 操作与对象 | 预期 | 实际结果 | 状态 |
|---|---|---|---|---|
| D25-C2-01 | DentAll Core最小权限改动 | Website Manager持久获得`import`；角色版本单调提升；自定义导入模块仍不加载 | 插件0.2.6、角色版本7；`roles.php`只新增`import`，主入口仍只加载既有五个模块 | Local通过；Staging待部署 |
| D25-C2-02 | Website Manager正向能力 | 商品列表Import/Export、商品导入页和Woo AJAX所需能力可用 | `day25-c2-import-capability-audit.php`对应检查全部PASS | Local通过 |
| D25-C2-03 | 权限负向与粗粒度边界 | Content Editor无`import`；Website Manager无全局`export`；全局`import`在非商品上下文仍为真并被明确记录 | Content Editor拒绝、全站内容导出拒绝；Website Manager在`import.php`及其他上下文仍有`import`，符合已接受风险 | Local通过 |
| D25-C2-04 | 原生可追溯源码边界 | 新建商品记录创建账号；不声称商品有原生修订或完整批次日志 | WooCommerce 11源码确认新建商品`post_author=get_current_user_id()`；Product不支持revisions，原生错误结果也不是持久完整批次审计 | 通过（源码证据） |
| D25-C2-05 | 静态检查 | 修改PHP无语法错误 | `dentall-core.php`、`roles.php`及权限审计脚本均通过PHP 8.2.29 `-l` | 通过；Local CLI仍提示既有Imagick DLL启动警告 |
| D25-C2-05A | Local单位与币种回读 | C3写入前为`kg/cm`与`USD`，不转换既有TEST数值 | 2026-08-22通过WordPress运行时回读`woocommerce_weight_unit=kg`、`woocommerce_dimension_unit=cm`、`woocommerce_currency=USD` | 通过 |
| D25-C2-06 | Staging第一版币种 | WooCommerce全局币种为`USD`，位置在左，千位`,`、小数`.`、两位小数 | 用户在Staging `WooCommerce → 设置 → 常规 → 币种选项`保存并提供截图，显示“美元（$）— USD”及目标格式 | 通过（用户截图） |
| D25-C2-07 | Staging密码重置邮件 | 不把页面受理请求误写成实际送达；SMTP未配置时有受控人工恢复路径 | 用户确认问题仅发生在Staging；既有D4/插件清单已记录SMTP未配置。当前由管理员受控重置Website Manager密码；自助邮件送达留到企业事务邮件服务选型与正式身份流程验收 | 已知依赖；不阻塞C3商品导入 |

独立Review结论：本轮Local最小权限改动没有开放P0/P1。旧自定义导入器的预检、锁和AJAX能力脚本已可逆重命名为`.disabled`，不得再作为绿色验收证据；当前有效自动证据仅包括原生能力审计及既有商品导出回归。剩余P2是Staging真实账号入口、AJAX/导入器边界和首批新建Draft CSV尚未实测。

C3原生CSV写入按以下标准执行；当前批准范围收紧为Simple模板v1，Variable/Variation CSV不在本轮绿色证据内：

- 从受控TEST示例导出或WooCommerce官方sample/schema形成独立空白模板；全量商品快照保持原样，不把其中的现有数据行直接回导。
- 使用唯一TEST新SKU、无现有商品ID、Simple商品`Published=-1`，价格只填数值且目标环境币种为`USD`，并保持`Update existing products`未勾选。
- 导入前后对比既有商品ID、SKU、状态、价格、库存和父子关系；完成页`Updated=0`，任何现有对象变化立即停止。
- 新商品保持Draft，`post_author`等于执行导入的Website Manager；匿名精确URL不可公开、商品Sitemap不包含该商品。
- 保存Imported/Variation/Updated/Skipped/Failed结果、CSV指纹、导入前商品导出和应用备份时间到商品CSV批次登记。
- 小量新建Draft恢复路径和涉及已有数据时的开发者升级路径均可查；不把回收站、商品CSV或Cloudways备份入口误写成整批一键回滚。

## D25 C3～C6 Staging原生CSV、恢复与追溯记录

| 用例ID | 操作与对象 | 预期 | 实际结果 | 状态 |
|---|---|---|---|---|
| D25-C3-01 | 部署Website Manager CSV MIME白名单 | 只在既有媒体白名单增加`text/csv`，不加载或部署自定义导入器 | `main`提交`66a1c63`、部署提交`501e5e5`均只修改`media-policy.php`；WM-A可进入原生导入映射页，自定义导入草稿不在部署树 | 通过 |
| D25-C3-02 | 导入2行Simple TEST源CSV | 新增2个Draft；Imported 2、Updated 0、Skipped 0、Failed 0 | #109/#110创建成功，完成页为Imported 2、Imported variations 0、Updated 0、Skipped 0、Failed 0 | 通过 |
| D25-C3-03 | 导入前后全量CSV对比 | 只新增目标对象，既有ID/SKU/状态/价格/库存不变 | 11→13条记录，仅新增#109/#110；既有11条在49个导出列中0处变化，无删除或重复非空SKU | 通过 |
| D25-C3-04 | 新对象字段与状态抽查 | 两个Simple保持Draft，价格/库存/SKU符合源文件 | #109为$29.99、库存5/有货；#110为$49.99、库存0/无货；均为Draft | 通过；本批未另存独立匿名URL截图，公开隔离沿用D17商品Draft基线 |
| D25-C4-01 | Post录入回归 | 短文可保存和预览，长文/审阅路径无需重复造数据 | #111保存并预览正常；#68/#90既有长文、Yoast、内链、修订与Draft隔离证据继续有效 | 通过（证据复用） |
| D25-C5-01 | Page录入回归 | 创建、状态、发布、修订、恢复、菜单和缓存路径可执行 | 复用D24 Page #76完整证据，最终保持Draft | 通过（证据复用） |
| D25-C6-01 | #110普通Trash → Restore | 恢复后ID/SKU、Draft状态、价格和库存不变 | 用户确认#110恢复后仍为Draft，$49.99、库存0/无货，ID/SKU正常 | 通过 |
| D25-C6-02 | 同一源CSV重复上传，更新框未勾选 | 已存在SKU在写入前跳过；Imported/Updated/Failed均0，Skipped 2 | 完成页与URL参数均为Imported 0、Imported variations 0、Updated 0、Skipped 2、Failed 0 | 通过 |
| D25-C6-03 | WP-CLI只读创建者与数量核验 | #109/#110作者相同且为WM-A；不产生重复顶级商品 | `siteurl`正确；顶级`product`为10；#109/#110均为Draft、`post_author=4`，用户4角色为`dentall_website_manager`。全量CSV另含3个Variation，合计13条记录 | 通过 |
| D25-C6-04 | 独立代码/权限审查 | P0/P1为0；冻结导入器未加载/部署 | P0=0、P1=0；保留全局`import`粗粒度与未跟踪草稿误纳入两项P2，未经授权不处理草稿 | 通过（P2已登记） |
| D25-C6-05 | CSV开放范围 | 只按真实实写证据开放，不把Simple样本扩写为Variable/Variation通过 | 当前仅开放Simple模板v1、仅新增Draft；Variable/Variation CSV、Images、自定义Meta与更新已有商品均未开放 | 通过（边界已收紧） |

C6结论：**当前Simple模板v1技术/人员路径通过，开放P0/P1为0。** 普通回收站恢复、重复SKU停止路径和创建账号追溯已抽查；没有执行永久删除、更新已有商品、Cloudways整站恢复或Variable/Variation CSV。商品作者只能证明创建账号，批次动作仍以`CONTENT_ASSET_REGISTER.md`登记为准。

## 测试记录模板

| 用例ID | 环境/设备 | 前置条件 | 步骤 | 预期 | 实际 | 状态 | 证据/缺陷 |
|---|---|---|---|---|---|---|---|
| TC-001 | Staging/Chrome | 测试商品有库存 | 加购并完成沙盒支付 | 订单成功、库存减少、邮件送达 | 待测 | 未开始 | |
