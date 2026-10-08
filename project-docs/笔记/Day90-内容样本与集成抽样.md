---
项目: DentAll WooCommerce
日期: 2026-10-08
工作日: D90
计划检查点: D90（不自动等于一个完整实际工作日）
周次: W15
计划工时: 6小时50分钟有效工作
实际有效工时: 未记录；不使用计划工时代填
验收层级: D81/D82＋D87/D88＋D89合成源码的隔离Local TEST内容抽样；M7未完成
状态: 已完成授权的TEST抽样及长表格修复；正式内容与非Local未验
---

# DentAll 每日复盘 D90：内容样本与集成抽样

## 相关笔记

- 每日索引：[[README|DentAll每日笔记索引]]
- 前一工作日：[[Day89-Contact表单与商品上下文]]
- 通用Page基线：[[Day85-通用内容页模板与编辑回归]]
- 单篇文章和署名：[[Day87-文章详情与公开署名]]
- Solutions内容：[[Day88-Solutions原生Page前台验证]]
- 当日学习：[[WordPress实战笔记/Day90-内容样本集成与发布门槛]]

## 结论与三个验收结果

- [x] 把主线D81/D82与已提交的D87/D88源码合成，并在同一隔离Local站点加入D89候选；为D90另建两篇不同后台作者TEST Post与长/空TEST Page。初轮48次浏览器访问覆盖内容与共享页，正常页200、真实404为404，主结构唯一且无外层横向溢出或JavaScript pageerror。
- [x] 找到390px单篇长表格“外层宽正常、内部宽3489px”的P2缺口；在既有D85规则内给单篇正文同样的`overflow-wrap:anywhere`，再给单篇表格固定布局。独立四宽16次文章/Page/Blog回归通过，长表格内部宽收敛到350/704/736/736px，390px两列各175px。
- [x] 保持M7开放：正式3篇文章＋1个Page与授权16:9素材未登记完成；匿名Users REST仍公开后台显示名（RSK-057）、隔离文章URL与D19合同不同、D83/D84和非Local交易/缓存/SEO验收未在本轮完成。

## 授权、范围和责任

用户要求先提交该提交的内容，再按已建议的方案开始。D87待交源码先提交，随后从`origin/main@d2ba25b`以快进方式接纳已审查的D87/D88集成提交`09dd437`、`e31f362`、`ceea1c4`；D89在当前分支实施。D90只用隔离副本中的TEST内容核页面骨架、署名和链接，不替业务方填正式文章标题、分类、文案、产品事实或素材授权。

D90在W15总计划中属于内容抽样联调与周验收检查点；本轮没有更改120天计划、增加正式内容交付承诺或估算额外工时。实际用时未记录。若要求正式内容生产、改D19 URL、解决RSK-057、上线表单/邮件/缓存或引入新的编辑流程，需分别确认范围、负责人、工时与排期。正式内容审核由Website Manager/业务方负责；开发者负责结构、响应式、SEO输出与技术回归。

## 七个专注周期与收尾

| 周期 | 目标 | 实际结果 | 用时 |
|---|---|---|---:|
| C1 | 合成源码与隔离基线 | D81/D82＋D87/D88快进整合，保留D89隔离Form/TEST数据；记录文件同步哈希 | 未记录 |
| C2 | 内容代表样本 | 创建两名不同后台作者的TEST Post #211/#212、长/空Full width Page #213/#214 | 未记录 |
| C3 | 四端内容矩阵 | 内容页四宽、共享页两宽共48次访问；正常200、404真实404，单H1/main | 未记录 |
| C4 | 署名/URL/内部链接 | HTML与Yoast Article作者均为团队；原生Users REST与隔离URL差异单列 | 未记录 |
| C5 | 长表格缺口修复 | DevTools临时验证后回源码两处最小CSS，再复测16次及表格两列视觉 | 未记录 |
| C6 | 独立复核 | 独立Agent重新测四宽、截图和日志；新增开放P0/P1/P2=0 | 未记录 |
| C7 | 周验收边界与收尾 | 正式内容、素材、账户/交易、SEO/缓存和非Local保留开放，不标M7 Done | 未记录 |

## 集成样本与真实证据

隔离副本为WordPress 7.1、WooCommerce 11.0.0、Storefront 4.6.2、Yoast 28.2、PHP 8.2.29，使用`127.0.0.1:18989/18990`。D90初轮抽样时源码头为DentAll 0.48.1/Core 0.8.1；最终新增功能候选按次版本规则升至DentAll 0.49.0/Core 0.9.0。同步仅限第一方主题/Core所需文件，未复制第三方插件目录或碰共享Local。TEST Contact #207、FAQ #208、展示型Product #209与Fluent Forms Form #3来自D89隔离工作，不算正式内容。

| 样本/检查 | 实际结果 | 限制 |
|---|---|---|
| 初轮矩阵 | Home、Blog、两篇Post、长/空Page、Contact、FAQ、404、Solutions候选详情四宽；Shop、Product、Cart、My Account在390/1440px，共48次 | 初轮长Post表格内部溢出另行修复；Product/Cart只作布局烟测 |
| 两名后台作者 | Post实际作者ID为2/3，单篇byline、HTML作者相关输出与Yoast REST Article.author均呈DentAll Editorial Team Organization，Person节点0、旧后台名未进Yoast | 匿名Users REST仍200并暴露后台显示名，RSK-057/P2开放 |
| 单篇长文 | 修复前390px表格3489px、正文可见350px；仅测`documentElement.scrollWidth`会漏报。修复后四宽内容/表格同为350/704/736/736px，390px双列各175px | 两列极端TEST表格通过；真实多列表格、正式文案/图另验 |
| 修复后回归 | 长/空Post、普通长Page、Blog在390/768/1024/1440px共16次：HTTP200、唯一H1/main、无pageerror、无内外横向溢出 | 无实体设备/读屏；隔离全站noindex |
| 最终版本烟测 | Contact、长Post、普通Page、Blog在390/1440px共8/8为200、唯一H1/main、无pageerror/Console错误/溢出；实际加载`style.css?ver=0.49.0`，Core启用版本0.9.0 | 只验证版本和资源加载，没有重做交易或正式内容 |
| Solutions/内部链接 | 首页四卡指向既有四张Page且均为text-only；`View all solutions`禁用，`/solutions/`404；文章内FAQ链接200 | 历史候选Page非正式内容，聚合与层级URL仍候选 |
| URL合同 | 隔离库`permalink_structure=/%postname%/`，文档合同`/blog/{slug}/`返回301到根级文章 | 没有修改隔离URL配置；不能据此宣布D19正式URL或Canonical验收 |
| 共享/错误 | 页面无浏览器pageerror；Product/Cart的PayPal client token因隔离外呼保护请求500，PHP新测试期间无新增Fatal/Parse/500 | 不代表支付、交易或第三方外呼正常 |

证据在忽略目录`.codex-tmp/d89-local/evidence/`：`d90-test-summary.md`、`d90-browser-results.json`、`d90-article-regression-results.json`、`d90-final-version-smoke-results.json`、同步哈希与四宽截图。证据是TEST副本，不是正式内容或可部署数据库。D89邮件故障注入曾因隔离`fsockopen`限制留下历史Fatal，已撤销该测试夹具并改用收件人故障注入；D90新测试日志没有同类增量，不能把历史夹具错误归因于当前源码。

最终版本烟测后，经隔离目录/PID检查的`stop.ps1`已关闭PHP、MySQL和Mailpit；18989～18992监听0。四个D90 TEST对象与D89表单/条目仍只留在已停止的忽略目录副本，便于必要的复核；未同步到共享Local、Staging或Production，也未把TEST内容计作正式资产。

## 实现边界与减法审查

D90没有新增PHP、模板、JS、插件或数据字段。只在既有主题`style.css`给D85的正文换行规则增加一个`.single-post .entry-content`选择器，并为单篇表格新增一个仅含`table-layout: fixed`声明的规则块；未把所有站点表格全局改写，也未复制Page模板。该选择器解决内部最小宽计算，表格规则解决极端长串导致首列逐字竖排，两者职责不同，故保留。主题最终版本变为0.49.0以刷新样式资源。没有新增远程调用、查询、Cron、表单字段或SEO输出；未做性能前后测量。

## M7与后续节点

- 技术候选：D85 Page、D86归档、D87单篇、D88 Solutions与D89 Contact在各自隔离范围有证据；这次合成抽样关闭新增长表格P2，但D83订单中心、D84账户链路未在当前合成候选验收。
- 内容：`CONTENT_ASSET_REGISTER.md`仍未完成正式3篇文章＋1个Page、授权16:9素材；TEST Post/Page与历史Solution短页不替代业务验收。Website Manager需按真实内容和授权链录入、审核、预览及发布。
- URL/SEO/隐私：RSK-057在正式文章对外发布前处置；目标环境核D19文章URL、Canonical、Sitemap、缓存和D89查询参数/表单隐私收件门槛。D88可见Preview路径和精确恢复需求在人员培训前补证。
- 环境/交易：Staging/Production、真实设备/读屏、支付、订单、邮件、动态页面缓存与完整账户链路均未由本轮通过。PayPal 500仅说明隔离外呼被阻断，不能代替网关验收。

本轮没有改Staging或Production数据、公共URL、索引设置、支付、物流、订单、DNS、缓存配置或部署。下一步先完成正式内容与业务闸门，再按风险分阶段推进目标环境验收；不能仅凭D90计划编号将M7标为Done。

## 可复用核心思想

- 跨平台不变量：响应式验收必须测内容容器内部尺寸，页面总宽没有溢出不代表文字、表格或图片未被裁；TEST样本证明技术边界，正式内容与素材还需业务事实和授权链。
- WordPress/WooCommerce当前实现：原生Post/Page保存内容和作者事实，Storefront输出模板，子主题只修局部阅读样式；Yoast派生作者与原生Users REST是不同公开表面，必须分别核对。Woo商品、Cart与支付插件只做各自负责的交易验证。
- Shopify或其他平台：内容模型、模板、URL、权限与索引机制需按目标平台确认；“数据事实—公开输出—浏览器呈现—真实发布”分层验收的原则可以迁移，WordPress Hook与URL默认值不能照搬。
