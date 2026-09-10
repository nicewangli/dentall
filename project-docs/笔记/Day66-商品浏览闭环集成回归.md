---
项目: DentAll WooCommerce
日期: 2026-09-08
收口日期: 2026-09-10
工作日: D66
周次: W11
验收层级: 集成与隔离Local回归
状态: 已完成（D66/M5 Local三项P2关闭并纳入main；非Local待验）
实际有效工时: 未记录
---

# Day66 商品浏览闭环集成回归

## 相关笔记

- 商品发现基线：[[Day54-商品发现链路回归与W9收口]]。
- 前置购买验证：[[Day61-原生变体选择与购买验证]]。
- 原生内容边界：[[Day63-原生扩展信息与公开资料空状态]]。
- SEO基线：[[Day65-商品详情结构化数据与SEO边界]]。
- 当日学习笔记：[[WordPress实战笔记/Day66-集成基线与商品全链路回归]]。
- 后续Cart候选：[[Day68-手机与平板响应式购物车候选验证]]（其历史候选证据不曾自动关闭本篇风险；RSK-035/037/038已于2026-09-10单独修复并在Local关闭）。
- 后续购物车状态候选：[[Day69-Header Cart与Mini Cart状态联动]]（RSK-039/040等期限性P2不因本日三项关闭而改变）。
- 后续购物车优惠券边界：[[Day70-优惠券规则与边界验证]]。

## 授权与范围

2026-09-08用户在已收到D66具体拆解后要求：“D61已经完成，你先整合工作树并提交到远程仓库，然后把临时工作树删除后，继续D66的工作”。本轮先完成Git集成、推送与已交付工作树清理，再执行已拆解的隔离Local回归；不新增业务能力、不把RSK-035修复或延期接受视为自动批准。

2026-09-10用户在复核三个既有P2后明确授权：“你先修复已有的三个P2”。该授权覆盖RSK-035/037/038的DentAll子主题最小实现、隔离Local验证、独立复核与中文文档收口；不授权Staging/Production部署、正式数据/内容、邮件、支付、税费、物流或缓存配置变更。

M5本轮按标准Simple/Variable的Local技术闭环检查。CR-005定制展示、正式商品事实、合格PDF正向路径、非Local与公开索引验收仍单列；没有合格PDF时不输出、不开放权限。没有结账、订单、真实支付、税费、物流、DNS或部署操作。

开发者负责集成、隔离、测试、缺陷与恢复；Website Manager/业务方负责正式事实、合法组合、认证及素材授权。使用明确TEST承载状态，不向共享Local写入测试事实。

## 三个验收结果

- [x] 列表/分类/搜索、筛选/排序/分页及恢复入口可以正确到达详情；实际报告与测试误报纠偏见下。
- [x] Simple/Variable正常与服务端拒绝合同保持；RSK-035的AJAX旧展示已由当前请求状态适配关闭，HTTP/parser/network/15秒timeout均安全失败且可访问提示。
- [x] RSK-037商品详情相邻Product Pagination已由版本化Hook移除，RSK-038长标签/长值已由局部属性表断行关闭；D66/M5按Local技术口径完成。

## 集成、远端与清理证据

- 集成提交：`278d20d818f07edcd8c2cd693314f82038db12dc`，保留D60 `c99126d`、D61 `6b51620`、D63 `0e5b428`的祖先历史；承接D59/D62/D64/D65。
- 合并仅发生Markdown冲突；保留各Day历史验收，更新当前事实与D66衔接。D63公开资料风险改为RSK-036，避免与D59 Sticky的RSK-034重号；RSK-035仍独立开放。
- 合成运行树与D61 `6b51620`完全一致；独立Review及PHP/JS语法、暂存差异/冲突检查通过，新增P0～P3为0；继承RSK-035/P2未关闭。
- `main`快进并`git push origin main`成功；`git ls-remote origin refs/heads/main`与本地完整SHA一致，`origin/main...main`为0/0。Git同步不是Staging/Production部署。
- D59/D60/D61/D63均已从`git worktree list`移除，受控代码可从远端恢复，原分支未删除。D67候选、当前D66、D25归档与Staging工作树保留。
- 三个独有忽略目录共160文件/14,031,884字节已复制到主根`.codex-tmp/worktree-archive-20260908/`，逐文件SHA-256相等；ACL仅SYSTEM与本机管理员，`manifest.json`被Git忽略。包括D59 bootstrap/权威报告和D60私密配置；不得推送或外发。
- D59工作树根已删除。D60/D63/D61的`dentall`根目录被Windows占用，Git已删除内容和登记，但留下空目录；额外清理D60空目录的命令被自动审批以“blocked by policy”拒绝，未强制结束进程或换路径绕过。三目录合计剩余子项0，不影响D66。

## 七个专注周期

| 周期 | 工作 | 当前证据 |
|---|---|---|
| C1 | 集成、远端、工作树清理及隔离设施 | Git、160文件私密归档；24个运行文件同基线、环回与拒绝护栏通过 |
| C2 | Variable与RSK-035优先复审 | 9月8日首次回归复现；9月10日完成当前请求AJAX安全状态适配与定向回归 |
| C3 | Simple/Variable交易边界 | Simple16/16；Variable独立12/12，未创建订单或进入结账 |
| C4 | 商品发现、URL和四端 | Shop/分类/搜索、分页/筛选/恢复、302/404、四端/1199/1200及Sitemap通过适用合同 |
| C5 | 内容、缺字段、Tabs和图库状态 | 9月8日新增RSK-037/038；9月10日以局部Hook和属性表CSS修复并完成正常/极端状态恢复回归 |
| C6 | 推荐、SEO、资源与独立复核 | 当前商品Schema/Canonical/条件资源；真实公开SEO与生产性能未验 |
| C7 | 清理、恢复、停机、状态与学习收尾 | 原回归恢复证据保留；三项P2经新授权实施，安全/交易独立终审P0/P1/P2/P3=0 |

周期是工作组织方式，不代填实际工时。最后60分钟用于W11验收、编辑反馈与后续准备；本轮不默认增加工作日。

## 2026-09-08首次验证与历史遗留

正式测试使用独立环回文件/数据库和浏览器会话；优先复用D61已关闭设施，旧证据、首快照和脚本保留，本轮使用新证据目录及快照。共享Local与Git工作树并不构成自动数据库隔离。

现有D54/D58/D59/D60/D61/D63/D64/D65证据只提供预期与用例；本日结果由集成版新鲜报告证明。旧脚本的固定端口、数据库和恢复范围须保持精确护栏，不直接指向源站。

### 实际环境与命令

- 复用已关闭D61隔离设施及既有D60 TEST种子，不重新从共享Local导出；运行地址`http://127.0.0.1:16062`、数据库`127.0.0.1:16063`，数据库名限定`dentall_day61_test_agent`。WordPress 7.0.4、WooCommerce 11.0.0、PHP 8.2.29、Storefront 4.6.2、Yoast 28.2、主题0.35.0/Core0.2.8。
- 24个受控运行文件与`278d20d`逐文件一致；准备报告`D:/LocalWP/dentall/.codex-tmp/day66-safety/evidence/ready-manifest.json`。测试数据、媒体、Cookie、会话和进程都在专用副本，安全Agent先准备、测试Agent独占写窗口、主Agent仅在稳定窗口访问。
- 沿用HTTP禁索引、环回绑定、禁外发邮件/远程请求、禁Cron/队列执行、禁结账与订单接口；为分页临时增加仅识别请求头的隔离mu，未修改每页条数Option或受控运行代码。没有关闭隔离保护来制造SEO通过。
- 浏览器命令：`node D:/LocalWP/dentall/.codex-tmp/day66-root/discovery.cjs discovery-final`；定向为同一脚本的`discovery-targeted targeted`参数。Variable、Simple与恢复入口复用D61/D58并指向本日独立证据，未覆盖历史报告。设施现已关闭，下列仅为执行记录，重演前必须重新批准隔离窗口。
- 本日测试脚本/报告全部位于被Git忽略的`.codex-tmp`，ACL只允许SYSTEM/本机管理员；代码、客户Cookie与私密配置不得混入提交。新的持久业务能力为0。

关键实际命令（工作目录`D:/LocalWP/dentall`）：

```powershell
node .codex-tmp/day66-root/browser.cjs inline-d66
node .codex-tmp/day66-root/browser.cjs ajax-d66
node .codex-tmp/day66-root/ajax-faults.cjs ajax-faults-d66
node .codex-tmp/day66-root/ajax-error-post.cjs
node .codex-tmp/day61-test-agent/fixtures/day66-independent-transactions.cjs anonymous
node .codex-tmp/day66-root/flows.cjs flows-ajax-d66
node .codex-tmp/day66-root/states.cjs A state-a-d66
& 'C:/Users/Administrator/.cache/codex-runtimes/codex-primary-runtime/dependencies/python/python.exe' .codex-tmp/day66-simple/transaction-audit.py
node .codex-tmp/day66-simple/content.cjs longtabs
node .codex-tmp/day66-simple/seo-exit.cjs
```

`content.cjs`另执行`emptytabs`、`emptyrelated`、`oneupsell`；`brand.cjs`仅建立一个临时品牌并恢复。每组状态切换和还原通过`wp-day66.ps1 eval-file`调用带精确环境护栏的本日helper，不让浏览器脚本写共享库。

### 新鲜结果与错误归因

| 证据组 | 实际结果 | 证明与限制 |
|---|---|---|
| Variable `inline-d66` / `ajax-d66` | 363/363；121/121 | 原生预载六宽及强制AJAX两个代表宽度；选择、售罄、无效组合、价格/库存/图片与按钮语义 |
| `ajax-faults-d66` / `flows-ajax-d66` / `state-a-d66` | 7/7；13/13；24/24 | 故障/恢复、URL/键盘、默认值/缺图回退/长描述；不代表新增错误UX |
| `ajax-error-post` | 4/4复现与安全拒绝断言 | RSK-035仍可复现；空ID键盘POST被拒绝且购物车为空，并非4项体验修复 |
| 独立Variable交易 | 12/12 | 独立核对属性/Variation、数量与实际cart；本轮不声称重复Variable POST已专项验证 |
| Simple交易 | 16/16 | 合法、越界、累计库存、不可购买等；重复合法POST数量1→2→3，原生加购非幂等；15个匿名cart逐一清空 |
| 内容四状态 | 原始自动80/80，长参数视觉未通过 | 空Tabs20、长参数DOM/四宽键盘28、无关联空态16、单Upsell16。自动断言漏掉内部表格撑宽，人工看图发现RSK-038；不得表述为内容全部验收通过 |
| 最小单品牌 | 原始12/14；合同复核14/14（未重跑） | 两项Oracle误要求品牌归档自Canonical；既定品牌合同为noindex且无Canonical，保存实页符合。新term及关系已恢复；不外推到30项规模 |
| Coming Soon退出分支 | 10/10 | 两件商品在封闭状态不泄露Product/购买表单，保留禁索引；不代替正式非Local访问验收 |
| `discovery-bounded` | 原始395/403、0 Page Error | 8失败为普通归档Oracle没有考虑隔离mu追加的noindex；源码与独立复核确认，原报告保留 |
| `discovery-final` | 原始458/462、0 Page Error | 四端32页＋分页/302/404/断点/Chip/Search/Sitemap；4失败为价格输入`50.00/10.00`被误要求字符串`50/10` |
| `discovery-targeted` | 15/15、0 Page Error | 四宽实际数值、错误关联/焦点及2页Tabs局部补证；修正4项格式误报，但中心hit-test不能代替文字无遮挡，RSK-037仍开放 |

首次`discovery-first`因等待所有lazy图片`decode()`无上限而停在首个详情；已终止本次runner，保留6张截图，修正为有界等待再跑。没有把测试脚本失败归为产品缺陷，也没有覆盖失败记录或把纠偏后的结果伪称原始全绿。

变量恢复首次仅父商品#46的`modified`漂移；按D61既有夹具的独立进程默认值恢复，再以第三个PHP进程对5个商品完整数据/modified核验相等。原`post-variable-restore-mismatch.json`保留。图库fullPage采集疑点以四宽实视口图、图片解码/几何补证；页缘小图另确认是原生相邻商品导航，不是Sticky或变体图库残影。

### 视觉与SEO证据边界

- 主Agent实际查看390筛选/反向价格、768/1024 Variable、1440 Shop/Simple等整页图及两张Tabs实视口图；测试Agent补看图库实视口，独立Review另核对768/1440 Tabs。列表Grid为2/2/3/4，推荐为1/2/3/3；正常页面无横向溢出不等于没有固定浮层覆盖。
- RSK-037：768px原生Product Pagination固定在左侧，露出45px；Tab始于x32，遮住约13px文字/焦点边缘。中心命中与键盘激活仍通过，故属P2而非功能完全不可用；1440该次实视口未见相同遮挡。截图为`discovery-targeted/tabs-viewport-768.png`，未注入隐藏CSS。
- RSK-038：234字符连续TEST参数在四宽均使原生附加信息表格宽2328.53125px，外层裁剪后页面overflow仍为0。测试Agent和主Agent均查看`day66-simple/evidence/longtabs/390.png`与`1440.png`确认文字被裁；`longtabs/report.json`保存四宽实数。原28/28是缺少内部边界断言的自动通过，视觉验收不通过，须补局部边界与真实可读性验证。
- 独立离线图像复核`longtabs/visual-qa.json`为0/4（没有重跑浏览器），SHA-256=`caa9d5d8a179578328b17b8542404e53dfab34a44df7c68b0c080a6c1f26406e`；品牌合同复核`brand/contract-normalized.json`明确`browserReplay:false`，SHA-256=`430cc836ad36a915a69a749f9a66149c5943ee34e2402c6fcf3f7fd8744a9dcc`。
- 当前商品Product/Breadcrumb各1、WebPage无悬空面包屑引用，Canonical/og:url指父商品；归档/搜索无Product。筛选Canonical回基础归档、搜索无Canonical，分页第二页自Canonical，四类非规范GET为DentAll 302/no-store，越界及未知对象为真实404。6个子Sitemap无筛选/排序参数URL。
- 测试时`blog_public=1`仅用于走基础SEO分支；隔离mu始终强制noindex，普通归档的基础`index` token与隔离noindex共存，筛选/搜索无index token。只验证条件分支，不声称公开页面可索引、Google富结果或Production缓存已通过。未重跑D53的30品牌规模/计数SQL性能基线，也不宣称性能零影响。

## 2026-09-08三项最小处置确认单（当时未实施）

| 内容 | RSK-035：Variable查询失败体验 | RSK-037：原生相邻导航遮挡 |
|---|---|---|
| 角色/频率/数据量 | 匿名和登录买家选择规格时；当前3个TEST Variation以预载为主，强制AJAX复现将来超阈值/第三方路径，正式数据量未确认 | 所有商品详情访客；当前2个代表商品，768px有相邻商品时复现 |
| 第一版候选 | 既有Variable脚本仅处理当前表单/当前请求的pending、真实失败和恢复，清旧展示与禁用语义；本地化可访问提示 | 原生后台：外观→自定义→WooCommerce→Product Page→取消Product Pagination→发布 |
| 明确不做 | 不重写匹配/价格/库存/交易；不新增请求、自动重试、插件、状态框架或全站监听；主动abort/过期响应不得覆盖新结果 | 不改父主题/新增CSS；不关闭Shop分页、Related/Upsells，不改既有Sticky配置；不擅自部署非Local |
| 候选比较 | 原生Clear/重选/刷新可恢复但不解决旧显示；官方11.0.1/11.1.0/trunk未找到适用修复；完整第三方替换对当前局部问题成本过高，未安装 | 原生开关成本最低；仅平板隐藏或重新布局需新增代码且保留跨断点维护，不默认采用 |
| 影响与恢复 | Variable条件资源，预计改现有JS及最少必要的本地化加载；无新业务字段/持久化/接口/订单。须复核当前请求竞态、资源、SEO/缓存输出；回退受控提交可恢复旧行为，实际资源成本需测量 | 写当前主题Theme Mod，不随Git同步；所有详情的prev/next内部链接入口减少，URL本身/Shop分页/推荐不变。先记原值，回滚还原；不直接改支付、物流或商品事实 |
| 工时变化 | 预计0.5～1个有效工作日，含实现、竞态/失败与独立回归；非无条件承诺 | 通常30～45分钟，保守60分钟，含原值记录、四端定向测试及恢复；不含非Local部署 |

第三项RSK-038面向所有商品详情访客，数据来自原生可见属性；当前极端样本为234字符无断点字符串，不等于正式业务字段。候选只在既有`product-detail.css`的属性表`th/td`采用已有`overflow-wrap:anywhere`方式，先不增加`table-layout:fixed`、全局word-break、隐藏溢出或JS截断；实际样本证明有效后才定稿。不新增文件、JS、字段、插件、全站选择器，不改商品数据或让编辑者手工插空格。原生填写短值不能解决通用骨架承载。不直接改变URL、Schema、缓存策略、支付/物流；既有条件样式资源变化需测量并回归，回退该CSS差异即可恢复原行为。通常30～45分钟、保守60分钟，包含四宽正常/长值/长标签、局部几何/截图及恢复；未经确认不实施。

三项合计暂估约0.6～1.3个有效工作日，需按实际修复范围校准，不是无条件承诺。负责人均为开发者；D66/M5前确定处置，最晚非Local部署前解决或由用户明确接受有原因、负责人和期限的P2延期。现在无延期接受记录，确认前不调整为已批准排期。

上游依据：[Woo 11.1.0经典变体源码](https://github.com/woocommerce/woocommerce/blob/11.1.0/plugins/woocommerce/client/legacy/js/frontend/add-to-cart-variation.js)、[官方Variable说明](https://woocommerce.com/document/variable-product/)；比较日期2026-09-08，后续升级必须重新核对。原生开关依据为本机Storefront 4.6.2的`class-storefront-woocommerce-customizer.php`与`storefront-woocommerce-template-functions.php`，不修改父主题。

最多3项后续验收：①Variant pending/真实失败/恢复、主动abort/旧回调不串状态，Simple/Variable合法与非法加购合同不变；②四宽Tabs/焦点无遮挡，长参数全文可读且表格不撑宽，原生相邻导航按确认关闭而Shop分页/推荐仍可用；③资源、Canonical/Schema、配置读回/回滚与隔离数据恢复通过。必须先取得针对候选范围的明确同意，再实施。

## 2026-09-10授权实施与关闭

用户明确授权“你先修复已有的三个P2”后，最终实现采用DentAll 0.41.0，保持WooCommerce为变体与交易真相源，只在既有子主题5个文件内增加必要的请求状态、Hook与局部CSS；DentAll Core保持0.2.9。本节是修复后当前事实，上述9月8日复现、失败截图、自动断言漏检和候选比较继续作为历史发现证据保留。

### RSK-035：当前Woo VariationForm的AJAX安全状态

- `product-variation.js`取得每个表单当前Woo `VariationForm`实例，只观察其现有XHR，不创建第二请求或重写Variation匹配。
- AJAX进入pending时，立即清空旧可见price、stock和`variation_id`，隐藏旧Variation并保持购买按钮禁用；`aria-busy=true`只设置在`.single_variation_wrap`，可见且`aria-live`的状态节点紧邻其前、位于busy子树之外，避免忙碌容器抑制状态播报。
- HTTP、parser、network及15秒timeout失败时保持旧展示已清除、`variation_id`为空和购买禁用，并显示可访问错误；没有自动重试或把失败结果伪装成售罄。
- 普通选择变化造成的主动abort不显示错误；pending期间收到`reset_data`会直接abort当前XHR，不依赖`readyState`时隙。XHR身份、选择签名和阶段共同阻止陈旧成功/失败/`show_variation`回调覆盖更新后的选择。
- Local综合证据：inline核心6/6、AJAX综合17/17通过。最终独立AJAX `ajax-final-busy-scope-independent-20260910-1225`为19/19、inline `inline-final-busy-scope-independent-20260910-1227`为6/6，pageerror均为0；最终四端AJAX `viewports-ajax-final-busy-scope-20260910-1232`为24/24、inline `viewports-inline-final-busy-scope-20260910-1233`为12/12，errors均为0。Simple页面不加载该脚本，服务端合法/非法加购职责不变。

### RSK-037：只移除商品详情相邻导航

- `dentall_configure_storefront_product_detail()`在`after_setup_theme`中，仅对Storefront存在的`storefront_single_product_pagination`执行`remove_action( 'woocommerce_after_single_product_summary', ..., 30 )`。
- 本次没有写Theme Mod、修改Storefront父主题或使用遮挡CSS；所有商品详情的相邻prev/next导航移除，但Shop归档分页、Related、Upsells、购买区和商品URL保持。
- Hook探针终态：Product Pagination为false，Upsells优先级15、Related优先级20、Shop Pagination优先级30。

### RSK-038：原生属性表局部安全断行

- `product-detail.css`只对商品详情`.woocommerce-tabs table.shop_attributes`中的`th/td`应用`overflow-wrap:anywhere`，并为`th`设置`min-width:6rem`；不使用全局`word-break`、隐藏溢出或JS截断。
- Local证据：最终inline四宽12/12、长标签＋长值布局40/40、恢复正常短值40/40通过。极端连续字符串全文可读，恢复短值后标签列与正常布局未发生回归。

### 当前验收边界

- 三项D66 P2在Local关闭，D66/M5按“商品浏览闭环Local技术验收”标记完成；安全/交易独立终审最终P0/P1/P2/P3=0，适用版本锁定WordPress 7.0.4、WooCommerce 11.0.0、Storefront 4.6.2。
- 最终独立变体回归合计61/61；随后确认五商品精确恢复，orders/refunds=0、sessions=1，Coming Soon恢复`yes`、本轮marker不存在；PHP应用错误扫描0，MySQL仅本地自签CA warning后正常shutdown，16662/16663监听0。
- 最终独立与四端运行名及通过数已写入上文；最终`product-variation.js` SHA-256为`B1619C901769BDE23E3CEFC617BD4B80CC75C790D0D86F2A2A40A0BAA0EA3A03`。真实读屏器尚未测试，WooCommerce/Storefront升级、单品模板或Variation机制变化时必须重验。
- 没有商品、Variation、价格、库存、媒体、订单或配置迁移；URL、Canonical、Schema、索引设置、邮件、支付、税费、物流与缓存配置不变。RSK-037会减少商品详情的相邻prev/next内部链接，Shop分页、Related与Upsells入口仍保留。
- Staging/Production、正式内容与素材、真实设备/辅助技术、Variation Gallery多图、真实缓存/CDN、邮件和完整交易链仍未验。RSK-039/040及D69/D72的期限性P2继续独立管理。
- 修复源提交`5c4cefb`已通过非快进合并纳入并推送`main`，远端SHA核验后移除D66临时工作树并保留修复分支；这只证明代码可追溯，不代表Staging/Production已部署。

## 2026-09-08首次回归的最终恢复、停机与独立复核

- 测试Agent完成受控恢复后，安全Agent用新的PHP进程独立复核：17/17终态、5/5属性/品牌补验、11/11文件隔离检查通过。完整状态哈希与首快照同为`e57b778c2853f625e7079ac9b59bb874de69431c9f2a062616802bcf0de54bdc`。
- 5个商品完整数据及modified、#44属性定义、全部品牌term集合/关系精确回原值；临时品牌#63已删除。会话回基线1，订单/退款0，pending14；测试用户、附件与新增上传文件0。原隔离Coming Soon=yes、blog_public=1及Theme Mod首快照恢复，不能把隔离旧快照误写为共享Local新配置。
- 原D61的96份权威报告/截图、原助手/首快照/安全mu哈希不变；24个受控运行文件仍匹配`278d20d`。本日临时分页/结账阻断助手留存安全归档后从runtime移除；数据库凭据与私有快照仍在受限ACL内保留，不宣称全部凭据销毁。
- 执行`stop-day66.ps1`后16062/16063监听及对应PHP/MySQL进程为0，MySQL正常SHUTDOWN；主Agent另用`Get-NetTCPConnection`复核无监听。PHP应用错误0、MySQL错误0，保留1条已核验的本地自签CA warning；历史失败证据未清空。
- 最终报告：`D:/LocalWP/dentall/.codex-tmp/day66-safety/evidence/final-isolation.json`，SHA-256=`45DADB6E88E1392E94FAB1E07803B9F147685D0AD0A9B68F221CFD0F0115A3F3`；10文件校验清单`final-evidence-sha256.json`的SHA-256=`6013B0B4FB4CB1D70C0E5018CAEC38ED78A665C33C545CD464EED768C05B6ADB`。
- 主Agent、独立测试与独立Review当时确认保留3项UX P2，安全新增未关闭P0/P1/P2为0；该段只描述9月8日首次回归，不覆盖9月10日授权后的修复结论。

## 影响与复杂度

9月8日首次集成未新增运行实现，运行净增文件/函数/规则/行数相对D61均为0；主要工作为保留并整合文档、验证与清理。9月10日经新授权修改5个既有DentAll主题文件，共新增257行、删除7行、净增250行；没有新增运行文件。`product-variation.js`由22行增至248行，净增8个职责单一的JS辅助函数，用于当前XHR识别、选择签名、busy/live状态、超时和竞态收敛；PHP函数净增0，既有Storefront详情函数只重命名并扩展；CSS净增2个局部规则块。保留这部分复杂度是因为AJAX交易展示必须区分pending、主动abort、真实失败、迟到回调与恢复，不能靠单一class切换可靠覆盖；实现仍未新增请求、匹配算法、通用状态框架、插件、模板、接口、字段或数据库迁移。主题从0.40.0升至0.41.0，Core保持0.2.9。

本轮D66提交共17个Markdown：新增2篇、更新15篇，489行新增/14行删除、净增475行。两篇分别承担项目交付/缺陷确认与实战学习，其他文档只更新事实入口、风险/测试摘要及8条双向链接，不新增运行模块。临时`discovery.cjs`172行只补缺失的发现链路浏览器验证入口，其他动态用例优先复用D58/D61，未引入测试框架/依赖；临时测试文件不作为站点发布代码。减法审查后保留原生开关与局部CSS候选，不为两个视觉问题引入新模块。

## 可复用核心思想

- 跨平台不变量：单项完成、提交入库、远端可恢复、集成版通过是不同证据。删除工作副本前，既要证明代码已保留，也要盘点不进Git的测试材料。
- WordPress/WooCommerce当前实现：Git只包含受控主题/插件与文档；数据库、媒体、Theme Mod、会话和隔离配置须独立核验。商品选择的页面状态与服务端可购买事实分别验证；DentAll只跟踪Woo当前表单请求并安全收敛旧展示，不复制Variation交易计算。
- Shopify或其他平台：保留代码/配置/业务数据/运行证据的分层原则；具体主题、变体和测试店恢复机制待验证，不直接照搬Woo实现。
