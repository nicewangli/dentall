---
项目: DentAll WooCommerce
日期: 2026-09-07
工作日: D59
计划检查点: D59（不自动等于一个完整实际工作日）
周次: W10
实际有效工时: 待用户选择是否记录
验收层级: 共享Local配置＋隔离Local动态技术验收
状态: 已完成（共享Local配置＋隔离Local确认范围）
---

# DentAll 每日复盘 D59：商品详情四端购买区与Sticky收口

## 相关笔记

- 详情骨架：[[Day55-商品详情字段与PC骨架]]
- 图库与响应式图片：[[Day56-商品图库与响应式图片]]
- 购买区基线：[[Day58-简单商品购买区与隔离购物车验证]]
- 推荐区与原遮挡留项：[[Day64-原生关联商品与推荐空状态]]
- SEO回归基线：[[Day65-商品详情结构化数据与SEO边界]]
- 当日学习笔记：[[WordPress实战笔记/Day59-响应式单品布局与主题配置边界]]
- 技术决定：[[../DECISIONS#ADR-036：商品详情平板采用顶层堆叠并按环境关闭Storefront Sticky|ADR-036]]
- 后续项目笔记：[[Day60-商品详情代表内容回归与W10收口]]。
- 动态Variation续接：[[Day61-原生变体选择与购买验证]]。

## 结论与完成边界

D59按用户确认的推荐最小范围实施：390px继续使用原有移动单列；768～1199px只把WooCommerce经典商品详情的Gallery与Summary改为同一内容宽度的上下文档流；1200px起恢复D55的Gallery主列、Summary辅列。Tabs/Description继续位于两者之后，购买区仍在原生Summary内，不复制DOM、不重排Woo Hook。

Gallery变宽后，同步把初始图片的`sizes`平板分支改成内容区全宽公式；五图缩略图在窄屏可收缩、从768px起每列封顶100px。共享Local通过Storefront原生主题设置关闭Sticky Add-To-Cart，避免D64/D65复现的约91px固定条遮挡推荐图；不新增替代固定购买按钮。

本日不实现D61的Variation动态图片、价格、库存、默认组合或不可购买状态联动，不改变Simple POST加购、商品事实、模板、Schema、URL、插件、字段、AJAX或缓存算法。

## 功能确认与授权

用户于2026-09-07明确回复：

> 确认按上述推荐最小范围实施Day59，允许隔离Local可逆测试，并关闭Local的Storefront原生Sticky。

该授权覆盖：既有子主题CSS和Gallery图片属性Filter的最小调整、主题资源版本、共享Local原生Sticky配置、隔离Local可逆商品状态测试、独立复核、中文项目/学习记录及Day59单独提交。该授权不覆盖Staging/Production部署、真实支付、DNS、正式商品数据、插件安装、自定义固定购买条、模板覆盖或D61动态Variation能力。

### 业务问题、角色与使用频率

- 访客需要在手机、平板和PC上按“先看图、再读摘要并购买、再看描述/推荐”的顺序浏览；这是一条每次商品详情访问都会使用的展示路径。
- Website Manager继续通过WooCommerce原生商品编辑页维护图片、摘要、价格、库存与Variation，不增加新的后台字段或入口。
- Sticky属于站点展示配置，修改频率低且可逆；当前只要求Local关闭，因此使用Storefront原生Theme Mod比在子主题写死全环境行为更符合配置边界。
- 当前数据源为既有#44 Simple、#46 Variable及Variation 51～53；附件47～50只在隔离副本形成多图状态。TEST数据不代表正式商品事实。

### 候选方案与取舍

| 方案 | 结论 | 原因与边界 |
|---|---|---|
| 保留Storefront 768～1199px双列 | 不采用 | 与已选平板竖屏参考的上下阅读顺序不一致，且摘要列较窄 |
| 同一原生DOM＋局部CSS取消float | 采用 | 不复制模板、不改Hook；只在既有48rem～75rem断点改变顶层排布 |
| 自定义Grid/Flex重写完整详情结构 | 不采用 | 当前float覆盖已足够，重写会扩大选择器、清除机制与升级面 |
| Storefront原生Sticky设置 | 关闭Local | 原生开关能同时阻止对应DOM与脚本输出，适合低频、按环境配置 |
| 子主题`remove_action()`强制关闭Sticky | 不采用 | 会把Local决定写死到所有环境，超出本次授权 |
| 第三方Sticky/图库/页面构建插件 | 不采用 | 没有能力缺口，不增加依赖、查询、脚本或供应商生命周期 |

## 三个验收结果

- [x] 390/768/1024/1199/1200/1440px下，Gallery、Summary、Tabs保持唯一且顺序正确；768～1199上下堆叠，1200起恢复PC双列，页面无横向溢出。
- [x] Simple购买区、Variable初始表单、单图/五图/缺图/长文本及Gallery `sizes`按当前范围回归；缩略图不超过100px且不横溢出。
- [x] Sticky关闭、D64推荐区、D65 Schema/面包屑、精确数据恢复、日志与独立终审完成；权威26页/559项断言无未关闭问题。

## 实施内容

### 运行代码

1. `app/public/wp-content/themes/dentall/assets/css/product-detail.css`
   - 既有五列缩略图统一使用`repeat(5, minmax(0, 6.25rem))`：可在390px收缩，从768px起封顶100px。
   - 在`48rem`至`74.999rem`仅对顶层Gallery/Summary设置`float: none`、`width: 100%`和逻辑方向尾边距0。
   - 保留`75rem`起D55的56.52%/39.13%两列合同。
2. `app/public/wp-content/themes/dentall/inc/storefront-hooks.php`
   - 复用既有`woocommerce_gallery_image_html_attachment_image_params` Filter，只把`48rem`分支从旧双列宽改为`calc(100vw - 4rem)`。
   - 390px与1200px以上公式不变；没有增加第二条图片输出链或Variation监听。
3. `app/public/wp-content/themes/dentall/style.css`
   - DentAll由0.33.0升至0.34.0，为按主题版本加载的现有资源刷新缓存键。

### 共享Local原生配置

实施前`theme_mods_dentall`没有显式`storefront_sticky_add_to_cart`键；Storefront 4.6.2通过默认值`true`启用。实施后只新增该键并保存为`false`，其他Theme Mod值逐项保持不变。Storefront函数读取到非`true`后提前返回，因此不输出`.storefront-sticky-add-to-cart` DOM，也不入队其脚本。

回滚有两条等价路径：在Customizer的Single Product Page重新勾选Sticky Add-To-Cart；或移除该Theme Mod键，使Storefront回到当前版本的默认`true`。配置是数据库状态，代码回退不会自动恢复它；未来若要在Staging/Production采用相同决定，必须分别授权、保存和验证，不能假定Git会同步Theme Mod。

## 减法审查与代码规模

- 新增运行文件0、函数0、Hook 0、模板0、JavaScript 0、插件/依赖0、字段0、查询0、远程请求0。
- 运行差异只涉及3个既有文件，共12行新增、8行删除，净增4行；新增1个媒体查询规则，移动并复用1条缩略图声明，修改1个既有`sizes`字符串和1个版本值。
- 没有为四个视口复制四套DOM，没有增加空断点、兼容框架、通用布局抽象或预实现D61状态。
- 保留`margin-inline-end`而不是写死`margin-right`，因为它只清除父主题当前浮动尾边距，并维持RTL方向语义。

## 验证设计与证据

### 环境隔离

正式验证副本位于本机忽略目录`D:\LocalWP\dentall\.codex-tmp\day59-runtime-v2`，HTTP/MySQL只监听`127.0.0.1`；副本禁外部HTTP、邮件、Cron和支付，并保持Coming Soon/noindex。除已授权的Sticky Theme Mod外，共享Local商品、附件和业务数据只用于只读快照；多图、缺图和长文本只写副本。

首轮夹具把`ABSPATH`重复定义警告渲染到页面顶部，属于隔离`wp-config.php`问题而不是DentAll主题问题。夹具改为仅在常量未定义时设置后，旧截图保留为废弃证据，最终场景重新采集；不能把被警告推移过的几何或截图冒充终态。

### 静态与独立复核

- 子主题相关PHP语法检查通过；CSS花括号配对、`git diff --check`和作用域检查通过。
- 独立Code Review确认768/1199/1200边界、父子主题级联、LTR/RTL逻辑边距、`sizes`数学、缩略图上限及D61/交易/SEO排除项；P0=P1=P2=P3=0。
- 独立动态测试最终为P0=P1=P2=P3=0；测试专项未修改跟踪源码，私有夹具和证据由Git忽略。

### 权威动态结果

权威四份JSON合计26个页面、47张截图、559/559项断言通过，HTTP均为200，Console error/warning、Page Error及Request Failure均为0：

| 场景 | 结果 | 关键事实 |
|---|---:|---|
| `normal-final-v2` | 247/247 | #44/#46各六宽fresh page；1199单列、1200双列；12页正文`ABSPATH` warning为0 |
| `multigallery-final` | 144/144 | #44五图六宽；5 slides/5 thumbs/5 columns；390最大63.61px，其余100px，溢出0 |
| `missingimage-final` | 80/80 | #44缺图在390/768/1024/1440保持原生占位、购买区、顺序和无溢出 |
| `long-final` | 88/88 | #44长标题/短描述在390/1199/1200/1440换行且不破坏边界 |

正常态Gallery/Summary外框宽度在390/768/1024/1199px分别为350/704/960/1135px且均`float:none`；1200/1440恢复约642.08/444.52px与709.91/491.47px左右列。对应主图可见槽位约348/702/958/1133/640/708px，fresh page所选候选描述符均不小于槽位。Gallery、Summary、Tabs顺序为1/2/3，Tabs始终位于前两者最大底边之后；购买区在Summary内。

Simple保留可见`Quantity`、`Product quantity`可访问名称、44px输入/按钮和Tab顺序；Variable初始保留1个Variation form和2个Select。本日没有选择组合或加购。D64推荐区继续为390/768/1024及以上的1/2/3列、尾距48px；1440推荐图片顶部5px取点命中图片本身，`stickyCount=0`。D65回归为Product 1、BreadcrumbList 1、WebPage面包屑引用0和悬空引用0。隔离Coming Soon/noindex基线下Canonical为0，本次只证明保持基线，不冒充公开可索引环境Canonical验收。

多图各图片保留D59公式；WordPress为两个懒加载副图合法输出`auto, <D59公式>`，属于Core增强而非Filter失败。最终恢复和新进程审计均为`products_equal=true`、`orders_equal=true`、`refunds_equal=true`；#44/#46/51～53完整数据及modified时间一致，订单/退款仍为0。

截至权威汇总`2026-09-07 07:29:57Z`，修正后夹具基线`07:16:15Z`以来，`php-error`/`wp-debug`只新增10条已知WP-CLI进程的Imagick启动警告，`ABSPATH`重复定义、Fatal和其他Warning均为0，PHP内置Web服务器运行错误行0。基线前`07:15:34Z`一条已废弃snapshot碰撞的fixture Fatal只属于测试工具，未进入权威结果。

权威汇总冻结后，主Agent在收尾读回时有一条只读`wp eval`因PowerShell到`wp.bat`的引号转换失败，于`07:42:41Z`在隔离`wp-debug.log`新增一条`Undefined constant`工具侧Fatal；该命令只包含读操作，未写数据库，也未进入559项浏览器结论。随后改用无内联PHP的`wp theme get`与`wp option get`成功读回DentAll 0.34.0和`storefront_sticky_add_to_cart=false`。15959/15960端口及对应PHP/MySQL进程均已停止。最终汇总位于本机忽略目录`.codex-tmp/day59-independent/final-summary.json`；凭据、SQL、cookie和运行时不提交。

## 七个专注周期与责任

| 周期 | 实际内容 | 责任边界 |
|---|---|---|
| C1 | 只读核对经典单品DOM、Storefront float/Sticky和设计证据 | 主Agent；先冻结顶层结构，不改业务事实 |
| C2 | 六宽基线、Gallery槽位与`sizes`推导 | 主Agent＋隔离运行专项 |
| C3 | 平板顶层最小CSS和图片提示同步 | 主Agent；只改既有职责文件 |
| C4 | 缩略图上限与Local原生Sticky配置 | 主Agent；配置仅限Local |
| C5 | Simple/Variable、推荐与SEO回归 | 独立测试专项；不提交购买或Variation选择 |
| C6 | 多图/缺图/长文本、边界与独立Code Review | 测试/Review专项；P0/P1不得留待Done |
| C7 | 精确恢复、日志、文档、学习笔记与提交 | 主Agent；不虚构工时或用户掌握度 |

开发者负责布局、版本、配置守门、测试隔离和回滚；Website Manager/业务方负责正式图片、名称、价格、库存、合法组合、文案与授权。真实内容不足不阻塞通用骨架，但仍阻塞正式内容验收。

## 影响、风险与回滚

| 领域 | 本次实际影响 |
|---|---|
| 数据 | 运行代码不写商品；共享Local只持久化一个Theme Mod布尔值。隔离TEST最终必须恢复#44/#46/51～53及modified时间 |
| 权限/安全 | 没有新输入、后台动作或权限；原生配置由Administrator保存。若未来暴露自定义配置入口，需另行Capability与Nonce设计 |
| URL/SEO | 不改Slug、路由、Title、Meta、Canonical、robots、Sitemap或Schema；D65结构化数据只做回归 |
| 性能/缓存 | 不新增请求、查询、脚本或缓存；关闭Sticky减少其DOM/脚本输出。主题版本使详情CSS取得新缓存键，但没有实测CWV，不能宣称性能提升 |
| 支付/库存/订单/物流 | 不改；本轮不提交加购、不选择Variation、不创建订单或退款 |
| 部署 | Day59代码已纳入共享Local的`main`；Theme Mod已经按授权只改共享Local。Staging/Production、`deploy/staging`、DNS和真实支付不变 |
| 回滚 | 逆向Day59提交恢复布局/图片提示/版本；再单独勾回或移除Sticky Theme Mod键。两类回滚不能互相替代 |

## 后续衔接

- D60在真实内容样本下做详情基础回归，重点处理长标题、正式图片、异常状态与四端视觉偏差；本日TEST不能替代正式内容。
- D61继续负责Variation选择后的动态图片、价格、库存、默认值、无效组合和不可购买状态；D59只验证初始Variable骨架未回归。
- D66在D60/D61/D63完成后复核Simple/Variable/库存/内容/推荐/SEO整链路。
- 非Local部署时把代码和Theme Mod当作两个独立交付对象；未明确授权前不在Staging/Production关闭Sticky。

## 可复用核心思想

### 跨平台不变量

响应式布局、图片候选提示和固定覆盖层是三条独立责任链：改变列宽后必须同步检查图片槽位；关闭遮挡组件时要验证它的DOM、脚本和点击命中，而不只是视觉隐藏。测试配置也必须与代码分开快照和回滚。

### WordPress/WooCommerce当前实现

当前经典单品由WooCommerce输出Gallery、Summary和Tabs，Storefront用float与After Footer Sticky增强，DentAll子主题仅在48rem～75rem覆盖顶层计算样式并通过既有Gallery Filter调整`sizes`。Local使用Storefront原生Theme Mod关闭Sticky，未把环境配置写死进代码。

### Shopify或其他平台的对应机制

可迁移的是“一套语义结构渐进重排、布局宽度与图片请求合同同步、固定层用平台开关优先、配置按环境验证”的方法。Shopify主题Section、图片过滤器、变体媒体和固定购买栏的具体实现尚未在DentAll验证，均属待验证知识，不构成本项目实施范围。
