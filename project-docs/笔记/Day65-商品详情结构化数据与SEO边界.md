---
项目: DentAll WooCommerce
日期: 2026-09-07
工作日: D65
周次: W11
实际有效工时: 未统计
验收层级: 隔离Local独立范围及主分支集成复验
状态: 主分支Local集成范围已完成
---

# Day65：商品详情结构化数据与SEO边界

## 相关笔记

- 后续集成复核：[[Day66-商品浏览闭环集成回归]]（保留本篇历史证据范围）。

- SEO基础：[[Day16-商品SEO规则]]
- 品牌合同：[[Day52-品牌数据与筛选基线]]
- Sticky与详情布局回归：[[Day59-商品详情四端购买区与Sticky收口]]
- 动态选择与参数URL续验：[[Day61-原生变体选择与购买验证]]
- 当日学习笔记：[[WordPress实战笔记/Day65-渲染生命周期与结构化数据去重]]
- 项目目录：[[README]]

## 授权、范围与计划位置

用户于2026-09-07明确确认：“确认按上述最小范围实施 Day65，并将‘分享基础’限定为现有社交预览元数据”。该答复承接本任务第一轮只读盘点及最小功能确认单，授权隔离Local验证、经典商品详情重复面包屑修复、独立复核、文档与中文提交。

D65在总计划中承接商品详情字段、交互和关联推荐。专项分支起点为`0fe9d0b`，当时工作树已有D57未提交内容，D62和D64尚未集成；因此先完成独立SEO修复。随后D57基线及D58、D62、D64、D65增量已分别提交并纳入`main`，完成合成复验；当时不代表D59～D61或D63完成，D59～D61与D63其后已按各自批准范围完成，D66仍待整链路回归。

- 使用角色与频率：访客、搜索引擎和社交平台逐次读取页面；Website Manager在实际内容录入时维护原生商品/Yoast字段。
- 数据来源与规模：现有2个已发布TEST父商品#44/#46、3个合法Variation#51/#52/#53，原生品牌term及评价均为空；不代填真实业务内容。
- 第一版必须做：保留Woo原生Product/Offer和可见面包屑；仅经典商品模板移除Yoast重复BreadcrumbList及其WebPage引用；核对现有Title、Canonical、robots、OG/Twitter。
- 明确不做：分享按钮、复制链接、SDK、追踪、插件/依赖、模板覆盖、Product自定义拼接、伪造品牌/评价、业务字段、购买逻辑、索引策略、源Local写入或非Local部署。
- 方案取舍：原生Yoast主分类配置不能去重且会改变路径；切换可见面包屑或购买Yoast WooCommerce SEO扩展超出当前需要。复用现有`seo-compatibility.php`的最小过滤器满足当前边界。
- 工时与排期：保留原D65计划检查点；隔离环境和依赖合并后的复验属于明确记录的验证工作。未统计实际工时，不承诺在一个完整工作日内完成集成，也不把集成等待归咎于商品资料不足。

## 最多三个验收结果

1. 经典#44/#46只保留与可见导航一致的Woo BreadcrumbList，Product/Offer不变，无已删除Yoast面包屑的悬空引用。
2. Coming Soon、非商品页面及Yoast退出路径保留原输出责任；Title、Canonical、robots与已有社交预览无非预期变化。
3. 完成实际验证、独立Review、学习收尾和D58/D62/D64主分支合成复验；明确公开环境未验收。

## 七个专注周期

| 周期 | 工作与责任 | 落地边界 |
|---|---|---|
| C1 | 主Agent核对真实模板、Schema来源和并行工作树 | 不把计划文档当实现证据 |
| C2 | 环境Agent复制受保护Local到独立数据库/端口 | 原Local只读，禁止真实邮件和外连 |
| C3 | 主Agent在既有Core SEO模块最小修复 | 不修改主题、商品或索引配置 |
| C4 | HTTP前后对比与原生变体URL验证 | 只使用现有TEST事实 |
| C5 | 四端和键盘走查现有输出 | 发现相邻Day问题记录集成门槛 |
| C6 | 独立代码/分支测试与减法审查 | 没有P0/P1未关闭才可完成独立范围 |
| C7 | 状态、SEO规则、两类笔记和中文提交 | 不合并D57脏改动，不宣称整体验收 |

## 实现与职责边界

`dentall-core/includes/seo-compatibility.php`新增2个函数、1个Action注册、2个条件Filter注册。WordPress在实际调用`get_header('shop')`时，才检查`is_product()`、Yoast存在和已挂载的Woo可见面包屑，再关闭Yoast BreadcrumbList并删除WebPage的`breadcrumb`属性。Coming Soon普通页头/区块模板不进入该门槛。

保留现有模块是因为这是站点级SEO输出协调，生命周期与既有Title兼容逻辑一致；不新增独立插件、文件或主题模板。Core版本从0.2.7到0.2.8，角色版本保持7，不触发角色迁移。减法审查保留的两个函数分别负责“何时启用”和“过滤返回值”，没有通用Schema框架、第三方私有方法调用或复制Coming Soon权限判定。

运行源码净增40行、0个运行文件；加版本和readme后3个Core文件净增43行。新增文件均为本项目要求的Day笔记与学习笔记。文档只记录可验证结果，不新增业务合同。

本次修改清单：Core的`dentall-core.php`、`includes/seo-compatibility.php`、`readme.txt`；项目文档`CHANGELOG.md`、`PROJECT_STATE.md`、`TEST_PLAN.md`、`URL_SEO_MAP.md`；笔记目录`README.md`、Day16/Day52项目笔记、Day55学习笔记、学习索引；新增本Day65项目笔记与Day65学习笔记。后四类既有笔记仅补入口或双向关联，没有重写历史验收。

## 验证记录

测试环境为`http://127.0.0.1:16565`，独立MySQL为`127.0.0.1:16566 / dentall_day65`，PHP 8.2.29；复用WordPress 7.0.4、WooCommerce 11.0.0、Storefront 4.6.2、Yoast 28.2和现有DentAll 0.32.0。副本保留Coming Soon，只监听loopback，关闭自动Cron并短路邮件/WordPress HTTP外连。

| 实测项 | 结果 | 证据 |
|---|---|---|
| PHP与独立代码分支 | lint通过；10项stub通过；P0/P1/P2未发现 | SEO模块、独立Review |
| 真实HTML前后比较 | 修复前12页、修复后12页；另4页退出分支；169/169断言通过 | `http-verification.json`、`before-valid/`、`after/`、`after-branches/` |
| Simple/Variable | 商品44/46及合法变体URL均Product1、Woo BreadcrumbList1；Yoast引用已清除 | `after/seo-report.json` |
| 变体URL | #51=39.99/InStock，#52=39.99/OutOfStock，#53=49.99/InStock；非法/不完整属性回父Product | 同上；Canonical及og:url仍指父商品 |
| 匿名与退出 | 匿名Coming Soon仍Product0/Breadcrumb1；请求级Yoast未加载或Schema关闭时44/46保留Woo输出 | 不修改持久插件/SEO配置的分支测试 |
| 非商品隔离 | Shop、分类、搜索与404状态和图谱保持基线；前三者既有双面包屑未扩范围修复 | 12页前后矩阵 |
| 元数据与资源 | 12页Title、全部meta、Canonical、Product、原生面包屑和CSS/JS清单前后相同 | 169项断言包含各页不变量 |
| Chrome四端 | #44/#46分别390/768/1024/1440；页面无横向溢出、可见面包屑1、H1为1、无可见坏图 | `browser/`的8张截图与`visual-audit.json` |
| 键盘与缺货 | #52显示39.99、Out of stock，动态容器`role=alert`；桌面ArrowDown移动到Additional information，Enter激活，蓝色3px focus保留 | `browser/visual-audit.json`；Console warn/error为空 |
| 数据不变量 | 源wp-config、URL/可见性/插件/主题选项不变；两次只读备份25个含数据表INSERT内容哈希一致；副本CRUD事实前后相同 | `source-verification.json`与`isolation-audit*.json` |

证据根目录：`.codex-tmp/day65-runtime/`，全部被Git忽略。8张截图为桌面Chrome视口模拟，不等于实体设备或屏幕阅读器测试。截图含用户浏览器扩展的悬浮图标，不属于站点新增资源。早期`before/`含错误属性slug，只采用纠正后的`before-valid/`作为正式基线。

实际执行及复演命令（在本工作树运行，服务启动时）：

```powershell
$day65Paths = Get-Content -Raw '.codex-tmp/day65-runtime/runtime-paths.json' | ConvertFrom-Json
& $day65Paths.php -c '.codex-tmp/day65-runtime/php.ini' -l 'app/public/wp-content/plugins/dentall-core/includes/seo-compatibility.php'
& $day65Paths.php -n '.codex-tmp/day65-runtime/review/review-stub.php'
& './.codex-tmp/day65-runtime/capture-pages.ps1' -Label 'after'
node '.codex-tmp/day65-runtime/analyze-pages.mjs' 'after' 'before-valid'
node '.codex-tmp/day65-runtime/verify-http.mjs'
git diff --check
```

重新取证应换一个新的Label，保留本次前后基线。退出分支由`capture-branches.ps1`使用仅隔离环境生效的请求级夹具复演；不是在源站停用Yoast。原环境只读备份不进Git，也不复制给外部验证器。

日志边界：初建副本曾记录Imagick DLL加载与WP-CLI重复ABSPATH工具警告，已调整隔离配置；本次副本未加载Imagick，保留GD。最终页面请求没有新增PHP警告/Fatal，未删除历史日志，图片加工能力不在本次验证范围。

收尾已恢复Chrome视口、关闭本任务临时标签，并通过`stop.ps1`停止专用PHP/MySQL；16565/16566无监听，所有证据和副本保留。停机脚本初次使用mysqladmin读取带database键的配置被拒绝，改为同一已校验端口的mysql `SHUTDOWN`后正常停机；未影响源Local服务。

## 独立复核与剩余风险

- 主Agent实现与浏览器走查；环境/独立HTTP Agent建立副本并执行169项断言；另一SEO Review Agent审查实现、运行10项分支测试，并独立解析28份HTML，43/43断言通过（`review/html-review.json`）。没有把主Agent自检称为独立Review。
- 当前修复未发现P0/P1/P2。接受1项P3兼容边界：可见导航回调存在不保证未来其他插件未删除Woo Schema生成回调。当前没有这种集成，暂不引入对Woo内部实例的额外守卫；负责人主Agent，最晚在更换主题、升级Woo/Yoast或接入Schema扩展前复验。
- D58可见Quantity标签已合入并通过四端及Simple加购复验；#52按钮只有`disabled`样式类、没有原生`disabled`或`aria-disabled`的继承问题仍交D61处理，不属于本次SEO补丁引入。
- 已完成D62/D64主分支集成验证；未执行屏幕阅读器、真实移动设备、Google Rich Results Test/Schema在线验证、生产缓存/CWV或真实品牌/评价正向样本。这些项目保留为公开环境或对应Day的验收边界。

## 主分支集成复验

2026-09-07将D57基线与D58、D62、D64、D65增量按顺序纳入`main`，再把合成树同步到原D65隔离运行时复验。Coming Soon只在隔离数据库中临时由`yes`改为`no`以走查匿名商品和购物车，收尾前已恢复为`yes`；专用PHP/MySQL正常关闭，16565/16566无监听。

- 12页Schema/DOM复验：Simple、Variable和合法Variation URL均为Product 1、BreadcrumbList 1、WebPage面包屑引用0、悬空引用0；Shop、分类、搜索、404无回归。
- #44在390/768/1024/1440无横向溢出，Quantity可见，输入与按钮均44px；D64推荐Grid依次为1/2/3/3列。当时复现的1440px Sticky局部遮图约91px P3，已由D59关闭Local原生配置并完成命中回归。
- 数量2加购成功，notice为`role=alert`，Header显示2 items，购物车金额为$49.98，移除后空购物车状态出现。没有写入共享Local数据库。

## 内容缺口与集成门槛

| 项目 | 当前事实 | 责任与最晚节点 |
|---|---|---|
| D62字段与展示型价格 | D62零扩展结论已合入，没有新增字段实现 | 展示型参考价格未进入Offer；未来新增字段仍须重新确认，不能凭字段名预实现 |
| D64关联与推荐 | D64增量已合入，四端Grid、DOM及Schema合成复验通过 | 空数据、角色和重复边界沿用D64隔离证据；D59已关闭Local Sticky遮挡，D66再跑整链路 |
| D61动态变体 | 静态URLSchema与客户端选择是不同生命周期 | 合并后核对可见价格/库存、参数URL、父级Canonical和Schema一致性 |
| 商品Meta Description | 现有两项TEST页面未输出该meta | Website Manager正式录入/发布时依据可见内容填写；不阻塞去重修复 |
| 品牌、评价、社交资料 | TEST无品牌/评价；Organization logo是占位图，sameAs与Twitter账号尚无业务确认证据 | 业务方在正式内容/公开索引前确认；不创建假品牌、评价或账号 |
| 独立结构化数据校验 | 本地解析和不变量检查只证明输出合同 | 公开Staging/上线前用Google Rich Results Test与Schema验证器复验，不能承诺富结果或排名 |
| 受保护环境 | Coming Soon不等于noindex；原Local商品响应存在index/follow基线 | 本次不改变保护或索引，非Local继续按既有环境规则单独验收 |

## 数据、SEO、性能与部署影响

- 数据/权限/交易：实现只过滤内存中的Schema数组，无CRUD写入、SQL、权限扩大、表单、Nonce或新的用户输入；价格、库存、支付、订单、物流、邮件不变。
- URL/SEO：仅满足门槛的商品响应减少Yoast重复面包屑及引用；Woo Product/Offer、Title、Meta、Canonical、robots、Sitemap、Slug和内链规则保持原职责。多条有效面包屑本身并非Google处罚依据，本次目标是与既有可见路径一致。
- 性能：不增加CSS/JS、远程调用、Cron或自定义缓存。不能据此宣称零性能影响；响应体和资源对比见验证记录，生产CWV/TTFB另验。
- 部署：代码已纳入本机`main`，不把数据库、用户、上传文件或隔离服务配置纳入Git；未推送`origin/main`，未部署Staging/Production，共享Local数据库未修改。
- 回滚：只撤回本次3个Core文件的变更或逆向本次代码提交，保留其他Day内容；部署后若存在页面缓存，必须清除受影响商品HTML缓存并重验，两条面包屑会恢复原状。不是通过停用整个Core回滚。

## 参考依据

- [Yoast Schema API](https://developer.yoast.com/features/schema/api/)：按piece控制是否输出，并同步清理WebPage引用。
- [Yoast WooCommerce SEO职责](https://developer.yoast.com/features/schema/plugins/woocommerce-seo/)：扩展插件有额外商品Schema整合职责，本项目未安装。
- [Google Breadcrumb说明](https://developers.google.com/search/docs/appearance/structured-data/breadcrumb)：允许有意义的多条导航路径，不保证搜索展现。
- 实际源码：WooCommerce 11.0.0 `templates/single-product.php`、`ComingSoonRequestHandler.php`、`class-wc-structured-data.php`；WordPress 7.0.4 `get_header()`；Yoast 28.2 Schema generator。

## 可复用核心思想

### 跨平台不变量

同一URL并不总返回同一种页面。输出去重必须先证明当前响应由谁生成，再撤掉另一份输出；删除实体时同时审计引用。业务内容缺失与技术骨架缺陷分开验收，不能用推测填充结构化数据。

### WordPress/WooCommerce当前实现

`is_product()`描述主查询身份，`get_header('shop')`在当前经典模板中表明实际进入商品输出阶段；Woo负责商品事实与可见面包屑，Yoast负责通用网页与社交元数据。过滤器必须在输出之前注册，并保留Coming Soon分支。

### Shopify或其他平台的对应机制

可迁移的是“真实响应分支、输出责任、实体与引用一致性”，不是WordPress Hook名称。其他平台的主题模板、SEO应用和商品数据出口需要重新验证；具体Shopify映射待验证，不属于DentAll实施范围。
