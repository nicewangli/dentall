---
日期: 2026-08-22
项目: DentAll WooCommerce
工作日: D25
状态: C1～C7技术/人员验收完成；M3整体等待业务内容与公司Git治理门槛
---

# Day25 综合验收与批量录入开放

## 第一版范围确认（2026-08-22）

- 用户确认Website Manager持久获得WordPress全局`import`能力，第一版只使用WooCommerce原生商品CSV导入/导出；被冻结的自定义导入模块继续不加载、不扩写、不部署。
- 第一轮CSV只新增商品并导入为Draft：不勾选`Update existing products`，不携带现有商品ID，使用新SKU，`Published`列使用`-1`。该边界是SOP和验收规则，不宣称原生界面已形成服务端强制锁。
- 开发者负责应用文件＋数据库定期备份、恢复路径和批次异常处置；Website Manager在每次商品批量导入前自行导出商品CSV。商品CSV不等于完整网站备份。
- 第一版通过独立账号、Post/Page原生作者与受支持字段修订、商品创建者`post_author`及商品批次登记完成基础追溯。原生能力不能追踪全部商品后续修改或状态动作，后台自动活动日志作为第二版待选型增强项。
- M3按“可审计＋有明确恢复路径”验收，不承诺整批一键回滚。文章和Page日常继续使用WordPress原生编辑器，不把CSV导入列为M3要求。

## 今日目标

1. 在任何CSV导入前建立可恢复点、对象基线和TEST批次边界。
2. 以受控方式跑通商品单录、商品CSV新建草稿、文章录入和页面录入，并关闭编辑端P0/P1。
3. 形成网站编辑人员可直接查阅的中文操作总手册；技术路径通过与正式业务内容通过分别记录。

## 相关笔记

- 商品模型前置：[[Day18-商品模型候选冻结]]
- 物流单位前置：[[Day15-库存与物流字段]]
- 内容发布前置：[[Day24-内容发布操作手册与WM-A培训]]
- 正式样本边界：[[Day24-B-真实样本与周验收]]
- 商品导入操作手册：[[Day25-Website Manager商品导入导出与恢复手册]]

## C1：导入前恢复点与只读基线

### 授权与操作边界

- C1只建立恢复点、读取Local/Staging事实和划分TEST对象，不导入、不更新、不发布、不删除商品、文章、Page或媒体。
- 商品CSV只作只读证据；含原ID、现有SKU、Staging媒体URL和重复中文表头的文件不得原样回导。
- #31、#39等删除候选在备份、引用和URL处置完成前同样受保护；“候选删除”不等于已授权删除。

### Cloudways恢复点

| 项目 | C1事实 | 边界 |
|---|---|---|
| 应用 | Cloudways Staging应用 `6604195` | 未触及Production |
| 备份范围 | Cloudways界面明确为应用文件与数据库 | 不等于商品CSV，也不等于已完成恢复演练 |
| Last Backup Date | `2026-08-22 03:29:57 UTC`，本地时间`2026-08-22 11:29:57` | 用户提供完成截图；本轮没有点击Restore、下载备份或执行恢复 |
| 当前状态 | 导入前恢复点已建立 | `RSK-009`完整恢复演练仍按后续计划执行 |

### Git、代码与Local静态基线

| 项目 | C1事实 |
|---|---|
| Git | `main`，HEAD `f883ab92abc415c07accd5acfedceb2d11500845`；C1开始时工作树干净 |
| DentAll Core | `0.2.5`，角色定义版本`6` |
| 导入权限 | `dentall_website_manager`持久角色没有`import`或`export`；商品导出仅在指定WooCommerce商品导出请求临时授予`export` |
| WooCommerce门槛 | WooCommerce 11.0.0原生商品导入器同时要求`edit_products`与`import`；C1只读基线时Website Manager不能导入。该事实随后由ADR-029/CR-010替代，C2 Local已最小增加全局`import` |
| Local SQL快照 | `app/sql/local.sql`，2,903,320 bytes，SHA-256 `A07944D2990D0E19388CEE9C90B60946B7B4908679BE3D055AB561047F3D471C`，修改时间`2026-08-21 17:56:24 CST` |
| Local商品 | 原始6行：#43为空`auto-draft`；#44 Simple、#46 Variable及#51～#53三个Variations为可用TEST基线；非空SKU和Slug无重复，无孤儿Variation |
| Local全局属性 | `Package Quantity`、`Size`、`Shade`三项；具体TEST术语仅作回归夹具，不是正式业务值 |

Local仍是WordPress 7.0.4、WooCommerce 11.0.0、Yoast 28.2和Storefront 4.6.2。C1没有启动或修改Local数据库；上述商品与角色结果来自最新静态SQL快照和代码只读审计。

### Staging商品CSV指纹

文件：`D:\Downloads\wc-product-export-22-8-2026-1787369790244.csv`

| 检查项 | C1结果 |
|---|---|
| 文件指纹 | 6,884 bytes；UTF-8 BOM；SHA-256 `20F56DF58F68A425EF9624A5DEC69209319CEB7FE799822148BF9B24B9D5CBC7` |
| 结构 | 49列、11行、48个唯一表头；第35和36列仍同名为“交叉销售” |
| 对象 | #31、#32、#35、#39、#45、#47～#50、#52、#58；相较D18的10行基线只新增#58，没有移除对象 |
| 逐列差异 | 旧10个对象的数据行逐列完全不变；新旧表头只有重量/尺寸单位从`磅/英寸`变为`公斤/厘米` |
| SKU与父子完整性 | 非空SKU无重复；空SKU仅#31；#48～#50均正确指向父SKU `TEST-D17-AIDITE-ZIRCONIA`，无孤儿Variation |
| Variable基线 | #47父级＋#48～#50三个合法组合；价格39.99/39.99/49.99、库存5/0/3、缺货项与物流继承/覆盖结构保持 |
| 新增#58 | Draft Simple，SKU `TEST-D18-UPLOAD-SIMPLE`，价格29.99、库存7、品牌ADS、全局Size TEST属性、主图＋图库引用均被导出 |
| 发布状态 | 2个Published标志为`1`，其余9行为非公开标志`-1`；没有因导出改变状态 |

该文件继续只作Staging只读基线，不是回导模板。重复中文表头仍使直接回导存在错列风险；绝对Staging图片URL、原ID和现有SKU也不能进入“新建草稿”测试批次。

### C1对象保护清单

- 商品：保护#32、#35、#45、#47～#50、#52、#58；#31、#39虽为待处置候选，C1也不删除或修改。
- Post：保护#24、#68、#90；#36、#40及默认`Hello world!`在去留确认前同样不清理。
- Page：保护#76、Home、Blog及系统绑定Page；#11、默认Sample Page和ID尚未锁定的D12 TEST Page在引用检查前不清理。
- 媒体：保护#59/#60及全部尚未完成引用矩阵的TEST媒体；不得按文件名、日期或猜测ID批量删除。
- 菜单：保护受控菜单#29及其Primary/Handheld分配，避免Storefront fallback重新暴露不应进入导航的Page。
- Local与Staging ID不能混用；例如Local #52是Variation，Staging #52是Simple商品。

### 第一版重量和尺寸单位决策

- 2026-08-18 Staging商品CSV表头为`lbs/in`，2026-08-22新CSV为`kg/cm`；旧10行数值未变化，说明全站单位曾发生未记录变化，不能把它当成普通翻译差异。
- 用户确认没有主动修改单位，并于2026-08-22接受第一版使用当前`kg/cm`作为WooCommerce Shipping基础单位。
- 选择公制是为了贴合当前供应商资料并减少编辑换算错误；英语和USD不要求Shipping字段必须使用英制。
- 历史TEST重量和尺寸不换算、不升级为正式物流事实。它们只证明Simple、父级继承和Variation覆盖机制；正式值由业务方按单个销售包装实测后录入。
- C1只读时Local仍记录为`lbs/in`；C2已于2026-08-22重新读取当前数据库，确认Local现为`kg/cm`且币种为`USD`。历史TEST重量与尺寸数值仍不换算，也不升级为正式物流事实。
- 批量录入后不得只修改WooCommerce单位下拉框。未来改单位必须登记数据迁移、换算、CSV/API、运费、Variation、页面显示、缓存和回滚验证。

### 环境与版本差异

- 2026-08-22 Staging后台仪表盘显示WordPress 7.1与Storefront；项目此前记录Staging为WordPress 7.0.4。
- Local核心文件仍为WordPress 7.0.4。C1没有更新或降级任何环境，也没有重新核验Staging全部插件版本。
- 该差异当前列为C2兼容性守门项：实现需以已检查的WooCommerce 11.0.0源码为基线，并在Staging 7.1实际复验；未有双环境证据前不得宣称版本一致。

## C1验收结论

| 验收项 | 结果 |
|---|---|
| 导入前Cloudways文件＋数据库恢复点 | 通过（备份存在）；恢复演练未执行 |
| Local代码、角色、SQL、商品和属性基线 | 通过（只读） |
| Staging当前商品CSV、文件指纹和旧对象差异 | 通过（只读） |
| TEST对象与媒体保护边界 | 通过 |
| 第一版基础单位 | `kg/cm`已获用户确认 |
| C1开放P0/P1 | 0 |
| 开放P2 | 中文CSV重复表头；Local/Staging单位尚待C2对齐；WordPress 7.0.4/7.1环境差异待兼容复验 |

**C1完成。** 该结论只证明导入前恢复点、只读基线和实施边界已经建立，不证明CSV可导入、备份可恢复、正式商品内容通过、D24正式3篇文章＋1个Page与授权素材完成，亦不开放批量录入。

## C2入口条件与下一步

1. 先把Local WooCommerce重量/尺寸单位对齐为`kg/cm`，用只读查询回读并记录；不转换现有TEST值。
2. 在既有角色白名单中最小增加全局`import`并提升角色版本；不加载`product-import-schema.php`或`product-import.php`，不实现新的导入入口、预检器或回滚器。
3. 使用WooCommerce原生商品导入器完成第一轮“只新增Draft”TEST：不勾选更新已有商品，不使用现有ID/SKU，`Published=-1`；导入前后读取既有商品数量、SKU、价格、库存和状态，发现更新数非0立即停止。
4. 把每批导入前的全量商品快照与新商品空白录入模板分开：快照保持原样；模板从受控TEST示例导出或WooCommerce官方sample/schema清洗，只保留已验收列。使用唯一、可识别的TEST CSV并保存模板/源文件指纹；不得把当前含重复中文表头、现有ID/SKU和Staging绝对媒体URL的全量导出文件原样回导。
5. Local通过后再部署Staging；以Website Manager独立账号验证入口、草稿结果、商品创建者、原生结果页、批次登记和升级恢复路径。未通过前不开放正式批量录入。

## C2 Local权限实施与只读审计结果

| 项目 | 结果 |
|---|---|
| 最小代码 | DentAll Core更新为0.2.6、角色版本7；Website Manager白名单只新增全局`import` |
| 自定义导入 | `dentall-core.php`继续不加载`product-import-schema.php`或`product-import.php`；未恢复自定义模板、预检、互斥锁或回滚代码 |
| 角色同步 | Local加载插件后数据库角色版本由6同步到7；Website Manager持久`import`生效 |
| Local单位/币种 | WordPress运行时回读为`kg/cm`与`USD`；已满足C3写入前环境门槛，既有TEST数值未换算 |
| 正向权限 | 商品列表Import/Export、商品导入页及WooCommerce导入AJAX所需能力通过 |
| 负向权限 | Content Editor无`import`；Website Manager无全局`export`，不能管理用户、插件、主题或WordPress系统设置 |
| 粗粒度事实 | Website Manager的`import`在`import.php`和非商品请求仍为真；这是用户接受的原生权限边界，不是商品专用技术隔离 |
| 语法与测试 | 三个PHP文件语法通过；D25权限审计14项及D18商品导出审计7项全部PASS |
| 已知环境警告 | Local WP-CLI仍提示既有`php_imagick.dll`缺失；本轮使用GD/MySQL和权限API正常，不把该警告误写为已修复 |

C2尚未完成Staging部署与账号级浏览器复验，也没有上传CSV或创建/更新商品。C3必须先完成原生小批量TEST导入，才能判断第一版批量录入是否可开放。

2026-08-22补充环境事实：用户已在Staging确认并保存WooCommerce全局币种为`USD`，价格格式为左侧符号、千位`,`、小数`.`和两位小数；CSV价格列只填写纯数值。密码重置邮件未送达的问题只发生在Staging，且项目既有记录明确SMTP尚未配置；该问题不阻塞C3商品导入，当前Website Manager账号恢复由管理员受控执行，企业事务邮件服务与自助重置送达留到既定身份/邮件节点验收，不新增临时代码。

独立Review未发现本轮Local最小权限改动的P0/P1。旧自定义导入器的预检、锁和AJAX能力脚本已可逆改名为`.disabled`，不再计入有效验收证据；剩余P2为Staging真实账号入口、AJAX/导入器边界和首批新建Draft CSV尚未实测。

## C3：Staging原生商品CSV小批次

### 部署与权限结果

- `main`提交`66a1c63`和`deploy/staging`提交`501e5e5`只在既有`media-policy.php`中为Website Manager增加`csv => text/csv`；没有新增函数、模块、插件或后台入口。
- 用户完成Cloudways Pull后，WM-A在Staging商品列表看到WooCommerce原生Import/Export，并能进入导入映射页。Content Editor仍只允许JPEG/PNG/WebP，Website Manager没有`unfiltered_upload`；用户、插件、主题和WordPress系统设置边界未扩大。
- `product-import.php`、`product-import-schema.php`及配套`assets/`仍只存在于Local未跟踪工作区，主入口未加载，目标提交与Staging部署树均不包含它们。

### 首次TEST批次结果

| 项目 | 结果 |
|---|---|
| 源文件 | `outputs/day25-c3-20260822/dentall-c3-simple-test-v1.csv`；763 bytes；SHA-256 `A11482FCAF9CC849FC692A39C74C83DC6669B8316E528A17236427E5C069B555` |
| 批次边界 | 2个Simple、22个唯一英文表头；ID/Parent/Images为空；`Published=-1`；`Update existing products`未勾选 |
| 原生结果 | Imported 2、Imported variations 0、Updated 0、Skipped 0、Failed 0 |
| 新建对象 | #109 `TEST-D25-C3-SIMPLE-001`，Draft，$29.99，库存5/有货；#110 `TEST-D25-C3-SIMPLE-002`，Draft，$49.99，库存0/无货 |
| 既有数据 | 导入前11条CSV记录，导入后13条；仅新增#109/#110，原11条在49个导出列中0处变化，无删除、重复ID或重复非空SKU |
| 导出证据 | 两个新商品导出SHA-256 `1FD276A12960E30A0553934D9314FD44C27950C15960236550D63531ED4F11AB`；导入后全量导出SHA-256 `F4D885D9C817E795E33509AD837DDB1C1DAA436D1D4E95178FB1A67C585772CC` |

全量CSV的13条记录由9个Simple、1个Variable和3个Variation组成；WordPress `product`顶级对象数为10。两种口径不得混写为“13个商品”。

## C4～C5：文章与Page回归复用

- WM-A在Staging保存并正常预览Post #111 `TEST D25 C4 Short Post — Dental Product Catalog Checklist`，保持Draft；该结果作为D25短文回归。
- 长文、标题层级、列表、正文图、内链、Yoast、修订与Draft公开隔离沿用D20 Post #68及D24 Post #90证据；无需为凑数量重复创建两篇同类TEST文章。
- 原生Page的创建、草稿、送审、发布、更新、修订恢复、回收站恢复、菜单/缓存检查和最终撤回沿用D24 Page #76证据；无需重复创建同类TEST Page。
- 上述结论证明技术与人员操作路径可用，不替代3篇正式文章、1个正式Page及授权16:9素材的业务验收。

## C6：恢复、重复导入与追溯抽查

| 检查 | 证据与结论 |
|---|---|
| 普通恢复 | 用户将#110执行Trash → Restore；恢复后仍为Draft，ID/SKU、$49.99和库存0/无货均正常。未永久删除，也未执行Cloudways整站恢复 |
| 重复SKU保护 | WM-A再次上传同一源CSV且保持更新框未勾选；完成页为Imported 0、Imported variations 0、Updated 0、Skipped 2、Failed 0，符合WooCommerce 11在`process_item()`前按已存在SKU跳过的原生逻辑 |
| 对象数量 | 重复测试后WP-CLI只读查询顶级`product`为10；结合导入后全量CSV中的3个Variation，仍为13条导出记录，没有产生重复顶级商品 |
| 创建账号 | WP-CLI确认#109/#110均为`product`＋`draft`，`post_author=4`；用户ID 4为WM-A，角色`dentall_website_manager` |
| 审计边界 | `post_author`只证明创建账号，不能证明谁执行了恢复或后续每次修改；恢复动作及CSV文件、结果、备份仍须依靠批次登记 |
| 独立审查 | 代码/权限审查P0=0、P1=0；没有必须在C6修复的问题 |

剩余P2为已接受的WordPress全局`import`粗粒度边界，以及未跟踪自定义导入草稿未来被宽泛`git add`或整目录复制误纳入的交付卫生风险；后者由开发者在每次提交/部署前检查暂存与部署清单，未经新授权不处理这些草稿。P3为`media-policy.php`旧注释及5MB提示仍只写“image”，不影响当前CSV权限与导入结果。

**C6完成。** 该结论证明当前已验收的Simple模板v1可以“只新增Draft”，重复SKU停止路径、少量误删恢复和创建账号追溯可执行；Variable/Variation CSV、带图片迁移、自定义Meta和覆盖已有商品仍未验收。第一版不承诺完整活动日志、后续修改人追踪、整批一键回滚或Cloudways完整恢复演练。

## C7：手册、批次登记与M3边界收口

### 本轮整理结果

- `Day25-Website Manager商品导入导出与恢复手册.md`已从“待Staging实测”更新为当前实测版，并把CSV开放范围收紧为Simple模板v1。
- `CONTENT_ASSET_REGISTER.md`已登记首次2行Draft导入、同源重复SKU负向复跑及#110普通回收站恢复；全量CSV的13条记录明确拆分为10个顶级Product＋3个Variation。
- `TEST_PLAN.md`、`EDITOR_WORKFLOW.md`、`PROJECT_STATE.md`、总计划、项目总档案和笔记索引同步当前证据与边界；没有生成DOCX、安装插件或新增功能代码。

### M3当前验收矩阵

| 范围 | 当前结论 |
|---|---|
| WM-A原生商品Import/Export与权限边界 | 通过；受保护Staging可用，WordPress系统管理入口仍关闭 |
| 商品CSV | Simple模板v1、仅新增Draft、小批次通过；Variable/Variation、Images、自定义Meta和更新已有商品未开放 |
| 商品单录 | Simple/Variable后台单录、价格、库存、属性、变体和草稿流程沿用D13～D18证据通过 |
| 文章录入 | 短文#111回归及#68/#90长文、SEO、修订、预览和Draft隔离证据通过 |
| Page录入 | #76创建、状态、发布、修订、普通恢复、菜单、缓存和撤回证据通过 |
| 可追溯与恢复路径 | 两个CSV批次和#110恢复已登记；创建账号可查，Cloudways导入前备份存在。完整活动日志、修改人追踪、整批一键回滚和整站恢复演练未承诺 |
| 正式业务内容与素材 | 3篇正式文章、1个正式Page及授权16:9素材待业务验收；不以TEST替代 |
| 公司Git治理 | 公司控制远程的所有权、备份、访问与交接仍待关闭 |
| Staging密码重置邮件 | SMTP未配置，当前由管理员受控重置；作为已知外部依赖转后续邮件节点，不阻塞Staging Draft录入 |
| Production | 未开放CSV、未同步内容、未触及订单/客户数据，也未完成Production验收 |

**C7文档范围完成，M3整体仍未标记完成。** WM-A可以立即在受保护Staging按Simple模板v1优先录入小批量Draft商品；正式内容/素材与公司Git治理门槛继续独立跟踪。扩大CSV商品类型、进入Production或更新已有商品必须重新验收。

## 数据、URL与系统影响

- 数据：用户建立Cloudways导入前应用备份；C3在受保护Staging新增两个Draft TEST商品#109/#110，C6对#110执行普通回收站恢复，并重复上传同一CSV验证2行均被跳过。既有11条导出记录未改变；Production未触及。
- URL/SEO：没有改变固定链接、Canonical、robots、导航或重定向；两个新对象保持Draft。CSV不包含完整Yoast元数据，正式发布前仍须逐项审核。
- 缓存：没有修改Breeze/Varnish配置，也没有因为本轮Draft数据执行全站清缓存。
- 支付/物流：真实支付仍关闭；`kg/cm`只冻结基础字段单位，不代表承运商、运费、税费或正式包装数据已确定。
- 部署：Staging已部署仅含既有媒体白名单小改动的DentAll Core版本；没有更新WordPress、WooCommerce、主题、DNS或Production。公司控制Git远程归属、备份与交接仍是M3治理门槛。

## 可复用核心思想

- 跨平台不变量：备份、导出和数据基线是三种不同证据。能下载CSV不等于能恢复站点；有备份时间也不等于恢复演练通过；任何批量写入都应先锁定对象级差异和回滚目标。
- 跨平台不变量：单位是数据语义的一部分，不是界面装饰。只改全局单位而不迁移数值，会让同一个数字代表完全不同的重量或尺寸；批量录入后变更单位必须作为数据迁移处理。
- WooCommerce/WordPress实现：Shipping字段只存纯数值，全站`woocommerce_weight_unit`和`woocommerce_dimension_unit`决定解释；CSV表头会随全站单位变化，因此新旧文件对比必须同时核对表头和数据行。
- WooCommerce/WordPress实现：原生商品CSV可以表达Product/Variation父子关系，但本地化表头、环境绝对URL、插件Meta和角色级全局能力会破坏可移植性；受控模板和请求级权限边界比直接开放原生全局导入更安全。
- Shopify或其他平台对照：同样应区分产品规格单位、物流计费单位和最终包裹单位，并在批量导入前固定商店级单位与变体语义；具体CSV字段和迁移API需按目标平台实测，当前不为DentAll实施Shopify能力。
