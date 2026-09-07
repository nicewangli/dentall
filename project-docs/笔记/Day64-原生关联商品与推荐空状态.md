---
项目: DentAll WooCommerce
日期: 2026-09-07
工作日: D64
计划检查点: D64独立并行检查点；D64完成当时不代表D58～D63已完成
周次: W11
计划工时: 6小时50分钟有效工作
实际有效工时: 未记录，不使用计划工时代填
验收层级: 隔离Local技术验收及主分支集成回归
状态: 已完成并合入main
---

# DentAll 每日复盘 D64：原生关联商品与推荐空状态

## 相关笔记

- 卡片契约：[[Day29-三类卡片组件契约]]
- 单品输出骨架：[[Day55-商品详情字段与PC骨架]]
- 相邻扩展信息边界：[[Day63-原生扩展信息与公开资料空状态]]
- 本任务继承基线：[[Day57-商品基础信息与原生品牌输出]]
- 当日学习笔记：[[WordPress实战笔记/Day64-WooCommerce关联商品与原生循环边界]]
- 决策记录：[[../DECISIONS#D64并行决定：原生关联商品与推荐空状态|D64并行决定]]
- SEO集成：[[Day65-商品详情结构化数据与SEO边界]]
- 后续：D59处理Sticky遮挡，D66完成整链路验收。

## 当前结论

D64已按批准范围完成独立Local技术验收并纳入`main`：最小代码、动态矩阵、TEST业务字段恢复与清理、独立复核和服务关闭均有证据。与D58/D62/D65合成后，推荐Grid四端1/2/3/3列、横向溢出和商品Schema/面包屑再次通过；1440px原生Sticky局部遮图P3交D59。D64提前完成不替代其他Day独立验收；D63已在后续任务另行完成，D59～D61仍待。

Related保留当前Storefront提供的最多3项；Upsells通过一个Filter限为最多3项，继续使用原生选择、可见性与排序。两区复用D29 ProductCard，以同一DOM形成390/768/1024/1440的1/2/3/3列；不足不拉伸补满、为空不输出整个区块。不新增推荐算法、补位查询或购物车Cross-sells展示。

## 功能确认与授权

用户明确回复：

> 按上述 D64 最小范围实施，包含隔离 Local 验证及可逆 TEST 样本。

授权覆盖独立工作树最小实现、隔离Local验证、可逆TEST、独立复核、项目与学习笔记及独立中文提交；不覆盖Staging/Production部署、真实支付、DNS、生产数据、第三方插件、高级推荐或个性化。

### 业务问题、角色与数据

- 访客需要在商品详情继续发现相关商品；Website Manager通过原生商品编辑页维护分类、标签和Linked Products，不增加后台入口或权限。
- 维护发生在录入、更新和审核商品时；实际业务操作频率与正式商品总量尚未统计，不用测试规模代替生产规模。
- 原Local基线只有#44 Simple、#46 Variable与Variations 51～53；正式推荐对象、兼容关系与素材由业务审核。相关商品只表达基础关联，不构成兼容或替代承诺。

### 原生能力与最小方案

| 方案 | 本日结论 | 维护边界 |
|---|---|---|
| WooCommerce原生Related与Linked Products | 采用 | 保留原生数据与输出条件，不造第二套关联字段 |
| D29 ProductCard、D55条件资源与Storefront Hook | 复用 | 不复制模板或重写卡片购买动作 |
| 最小PHP Filter与详情CSS | 采用 | 只收敛Upsells数量和推荐区网格 |
| 自定义推荐查询、独立/第三方插件 | 不采用 | 当前原生能力足够，不引入额外生命周期、查询或配置 |

## 今日三个验收结果

- [x] 原生Related、Upsells与Cross-sells边界清楚，正常、无数据及跨区重复场景按原生合同验证。
- [x] 两个详情推荐区复用D29卡片，四端及缺图、长标题、售罄/不可购买、可见Focus和资源隔离通过。
- [x] 隔离TEST业务字段恢复及清理、数据/URL/SEO/缓存边界记录、独立复核与学习收尾完成；SEO现状观察不代替D65专项验收。

## 原生推荐规则

| 能力 | 数据来源与位置 | D64处理 |
|---|---|---|
| Related | Woo依据当前商品的分类/标签找候选；After Summary优先级20 | 保留原生最多3项及随机结果，不按品牌、评分、库存相似度重写算法 |
| Upsells | Website Manager在Linked Products中手选；Storefront在After Summary优先级15调用原生展示 | 最多3项，原生可见性过滤和随机排序保持；不保证手选顺序 |
| Cross-sells | 原生Linked Products中的另一组ID；经典购物车有对应输出链 | 只说明和核对数据边界，不接到详情，不扩展Cart Blocks或购物车流程 |

Related原生调用把全部已配置Upsell ID作为排除集，并排除当前商品；不是只排除最终显示的3项。因此不跨区重复也可能减少Related候选，不能承诺一定满3项。Related先取得有上限的ID，再执行可见性过滤；不足后不补第二次查询。Upsells先从手选ID读取可见商品，再排序截取上限。无可见结果时，两份原生模板都在输出section前退出，不展示空标题、骨架或虚构商品。

## 实施内容与减法审查

| 运行文件 | 本日变更 | 保留理由 |
|---|---|---|
| `app/public/wp-content/themes/dentall/inc/storefront-hooks.php` | 新增`dentall_product_upsells_limit()`及`woocommerce_upsells_total` Filter，净15行 | 详情展示数量属于既有主题适配模块；非Product请求返回原值 |
| `app/public/wp-content/themes/dentall/assets/css/product-detail.css` | 净33行、5个叶规则块、2个媒体查询 | 同一详情生命周期；Grid、clearfix复位、卡片宽度/margin复位、长词换行及区段间距 |
| `app/public/wp-content/themes/dentall/style.css` | 分支版本0.32.0→0.33.0 | 刷新既有子主题资源缓存键；合并时统一版本 |

运行源码合计净增48行，0个新运行文件、1个命名函数、1个Filter；没有新JS、模板、字段、插件、依赖、查询或缓存层。D57继承差异不计入D64。展示职责与加载周期相同，新增文件不会改善边界；CSS只命中商品详情根下Related/Upsells，不接管Shop Grid、Tabs或购买区。

动态首轮发现P2：1440px的Upsells网格底部与Related标题顶部同为1975.5px，间距为0。卡片`margin:0`消除了父主题浮动卡片原有底部留白；已在现有推荐列表规则补入`margin-block-end:var(--dentall-space-48)`，无新规则块。独立四端复测间距均48px且overflow为0，P2已关闭。不能把首次静态Review无问题写成全过程未发现问题。

## 隔离Local与可逆TEST

- 版本：PHP 8.2.29、WordPress 7.0.4、WooCommerce 11.0.0、Storefront 4.6.2，D64分支DentAll 0.33.0。
- Web绑定本机`127.0.0.1:16464`，独立数据库端口16411；主题通过本机junction指向本分支。源库仅执行只读导出，不能把副本写入误记为源Local变更。
- 副本禁用邮件、支付、外部请求和Cron，并保持noindex；没有对外部署。
- 授权TEST为6个商品#159～#164、2个分类#63/#64、1个标签#65；#44/#46只在副本临时调整关联、分类或标签。
- `restore.json`中`restored=true`，#44/#46及Variations #51～#53共5个原对象的快照业务字段全部与基线一致：SKU/类型、当前/常规/促销价、库存数量与状态、主图、图库、分类、标签、Upsells及Cross-sells；6个TEST商品remaining为空。`restored-terms.json`中本次TEST分类/标签查询结果均为空，2分类与1标签均不存在。
- 恢复仅覆盖快照所列业务字段。TEST保存会改变副本modified时间及缓存，未作数据库逐字节恢复，不能写成“整库完全未变”。源库未被本任务写入。
- 证据存于本机被Git忽略的`outputs/day64/`；数据库导出、运行配置、Cookie、个人目录及凭据不写入文档或提交。
- 验证后用本隔离运行环境的`stop.ps1`关闭PHP与MySQL。`runtime-stop.txt`记录成功，`runtime-stop.json`确认16464/16411监听数为0、`files_retained=true`；证据和运行文件保留，不宣称已删除整个副本。

## 七个专注周期记录

| 周期 | 任务 | 当前证据状态 |
|---|---|---|
| C1 | 对照D29/D55/D57及原生三类关联 | 已完成只读盘点和用户确认 |
| C2 | 冻结数量、空态、去重、隔离与恢复边界 | 已确认最小范围 |
| C3 | 最小Filter与同一推荐区Grid | 3个既有运行文件，初版净47行；间距修复后净48行 |
| C4 | 建隔离副本及可逆TEST矩阵 | 上限、单项、空态、标签及角色矩阵通过 |
| C5 | 四端与卡片状态、相邻区段检查 | 1/2/3/3列；间距P2修复后四宽48px、overflow 0 |
| C6 | 数据/资源/SEO/缓存与恢复检查 | 原生回调冷/暖查询基线一致；5对象业务字段恢复、TEST清理与5页恢复态通过 |
| C7 | 独立复核、减法、文档和提交 | 最终独立复核通过，服务已关闭，项目/学习记录完成；专项提交由主Agent形成 |

不按上述周期推算实际工时。当前未记录实际有效工时，D64仍沿用原计划一个检查点；新算法、插件、生产缓存验证或额外业务范围须另行估时。

## 测试与验证

| 检查面 | 当前结果 | 证据边界 |
|---|---|---|
| PHP语法、差异 | PHP 8.2.29对`storefront-hooks.php` lint通过，`git diff --check`通过 | `final-static-check.json`为lint exit 0、PHP错误匹配0、恢复true；MySQL初始化提示单列 |
| 最终独立Code Review | `final-code-review.md`：重读3文件差异、场景/角色/查询/恢复/静态证据并目视390/1440修复图，无未关闭P0/P1/P2 | 代码与证据复核，不冒充第二人重跑全部场景 |
| 原生数量/空态/跨区排除 | cap为Upsells 3、Related 2，全部4个配置Upsell均排除；one四端1/0；empty四端0/0；related-only为3 | 上限是最多数量，不补空位 |
| 分类/标签与角色 | tag-only返回#159/#160/#161；guest只显示#162，Website Manager显示#164/#162；隐藏#163均不显示 | 草稿#164对有编辑权限者原生可见，不能写所有角色都不可见 |
| 390/768/1024/1440及卡片状态 | `mixed-browser.json`11行通过，1/2/3/3、errors为空、图片全部完成解码；独立间距复测四宽48px/overflow 0 | 响应式图片ERR_ABORTED取消单列，不误报缺图 |
| 最终TEST清理和原值读回 | `restore.json`为true，5对象快照业务字段一致，6商品/2分类/1标签均清理 | 不包含modified时间、缓存或数据库逐字节恢复 |
| 恢复态资源与页面 | `restored-browser.json`：Simple/Variable各Related 1，5页200、overflow 0、无JS错误或非取消请求错误；仅2商品页各加载1次详情CSS | Shop/Home/Cart详情CSS均0；恢复态为1440px抽查，不冒充清理后再跑完整四宽 |

补充证据：6条推荐商品链接为200，Draft #164匿名URL为404；`roles-audit.json`对guest的`can_edit_draft`、`draft_visible_to_role`、`catalog_hidden_visible`均为false，Website Manager分别为true/true/false。该角色差异属于原生`is_visible()`与编辑权限行为，D64不添加草稿可见性规则，也不把后台预览当访客输出。

`baseline-query-audit.json`与`d64-query-audit.json`分别在PHP 8.2.29全新CLI进程量测原生推荐回调，冷/暖均为32/0条查询。本结果只证明该样本下回调查询基线一致，不是整页TTFB、Core Web Vitals、大目录负载或Production缓存验收。

恢复态SEO现状：Simple、Variable、Shop、Home、Cart均只有1个H1，robots为`noindex, nofollow`，Canonical标签实际缺失；商品页有原生Product Schema，同时可观察Yoast与Woo各一份BreadcrumbList。两份面包屑交D65合成SEO核对，本日不改SEO规则，也不把这些观察写成SEO全部通过或变更前后完全一致。

本次隔离日志未发现PHP页面错误；停机后扩大检索`Fatal|Warning|Parse|Deprecated|Notice`命中2条MySQL首次初始化警告（临时本机账户初始化及自签CA），见`final-log-check.json`。它们不属于前台PHP异常；不能把PHP匹配0表述为所有日志无警告。隔离服务已关闭，两端口无监听。

### 实际执行命令与证据入口

以下为本次已执行记录；`php`实际选用PHP 8.2.29，`wp.ps1`指向本次隔离副本包装器，不可换成源站命令。隔离服务现已关闭，重新复演须先恢复同一隔离环境。

```text
php -l app/public/wp-content/themes/dentall/inc/storefront-hooks.php
git diff --check
node .codex-tmp/day64/browser.cjs mixed
node .codex-tmp/day64/browser.cjs restored
wp.ps1 eval-file .codex-tmp/day64/fixture.php restore
wp.ps1 eval-file .codex-tmp/day64/audit.php baseline
wp.ps1 eval-file .codex-tmp/day64/audit.php d64
wp.ps1 eval-file .codex-tmp/day64/roles.php
wp.ps1 term list product_cat '--include=63,64' '--fields=term_id,slug' --format=json
wp.ps1 term list product_tag --include=65 '--fields=term_id,slug' --format=json
node outputs/day64/independent-check.cjs
```

浏览器脚本还分别执行`cap`、`one`、`empty`、`related-only`、`tag-only`、`visibility`场景，每次只传一个场景；不能把这些名称用shell管道连成命令。`fixture.php`在对应场景前准备隔离TEST，`roles.php`在visibility夹具存在期间运行，baseline/d64审计各使用全新CLI进程。脚本与证据均是忽略目录中的本机复演材料，不移进生产资源。

证据按职责区分：`mixed-browser.json`及各场景JSON为主执行矩阵；`independent-review.md`、`independent-results.json`、`independent-gap-retest.json`和四端修复截图为独立浏览器执行；`final-code-review.md`为最终独立代码/证据复核；`restore.json`、`restored-terms.json`、`restored-browser.json`、`final-static-check.json`与`runtime-stop.json`记录恢复、静态和停机。

## 专项复核与风险

展示层按中风险处理：跨3个运行文件、多个状态和页面回归面，启用独立Code Review及动态测试；项目/需求专项负责范围与文档。静态首轮无问题，动态发现的相邻间距P2已修复并经独立四宽复测关闭；最终独立代码/证据复核无未关闭P0/P1/P2。

独立浏览器执行仅覆盖mixed场景、四宽、48个推荐Tab焦点、6个游客商品链接和Draft游客404，以及修复后间距；未激活加购，不代替主Agent的0/1/超过3项、tag-only、角色、恢复、查询或日志执行。最终Code Review检查这些已有证据和源码，没有宣称全部场景由第二人重跑。

保留一项P3：1440px下原生Storefront Sticky区域局部遮住推荐卡片图片顶部，标题、价格和按钮仍可见，键盘链未发现焦点完全被遮挡。D58/D65合成后已复现约91px遮挡，责任人为主任务开发者/Codex，交D59处理；D64不顺手改写Sticky购买区或扩大购买交互。

剩余边界包括真实目录规模、正式关联内容、实体设备/辅助技术、RTL、Production页面缓存/CDN与Core Web Vitals。原生随机推荐不承诺每次顺序稳定，也不因暖缓存存在就承诺零查询。

## 数据、URL与系统影响

| 领域 | 本次边界 |
|---|---|
| 数据与权限 | 运行代码无写入、新字段或新权限；隔离TEST已清理、5对象快照业务字段恢复，modified/缓存不作逐字节还原 |
| URL与SEO | D64不新增路由、参数或SEO输出；独立验收时商品有Product Schema及两份BreadcrumbList，D65集成后已收敛为Woo BreadcrumbList 1份且无悬空引用 |
| 查询与缓存 | 保留Woo原生Related transient及商品读取；不新增推荐查询/缓存，静态资源版本改变 |
| 支付、库存、订单、物流、邮件 | 不改交易实现，不接购物车交叉销售；隔离副本阻断真实支付、邮件、外请求及Cron |
| 部署与回滚 | 未部署非Local；D64专项提交已纳入`main`且可单独逆向，主题版本已统一；TEST恢复已验证，代码回退后需复测 |

## 合并与后续衔接

- 本分支以`6ece0ce`保存继承的D57快照并形成D64单独中文提交；主任务已仅选取D64增量，没有重复带入D57。
- 主任务已先固定D57，再按D58、D62、D64、D65顺序合成并复核HTML/SEO；该合成收尾当时D59～D61、D63与D66继续按计划验收。D63现已在后续任务另行完成零运行代码技术收口。
- 共享冲突面为`product-detail.css`、`storefront-hooks.php`、主题版本、状态/决策/测试记录及笔记索引。D59顶层布局与D61动态状态合入后再回归推荐区，不被D64提前吸收。
- `00-项目总档案.md`与中央状态已在集成收尾中同步；顺序主线仍只完成至D58，不因D64提前完成而改写其他Day状态。D63后续独立完成不改变D59～D61仍待的事实。

## 学习收尾与下一步

- [x] 已按学习模板核心骨架建立D64学习笔记，并补直接D29/D55/D57笔记与索引双向链接。
- [x] 动态验证、清理、最终复核与停机证据已读回，本笔记、状态、测试计划和索引已同步。
- Git交付：D64专项原提交`d680b4a`已作为主分支集成提交`757734a`纳入；共享冲突按上一节边界人工合成。
- 学习笔记已生成，掌握度与费曼自测仍由开发者实际完成；没有以生成笔记替代用户掌握度。

## 可复用核心思想

### 跨平台不变量

关联事实、候选选取、可见性过滤、数量上限和网格列数是不同责任。数量不足可以是正确结果；为填满布局追加查询或伪造关联会增加业务与维护成本。隔离副本的测试必须同时证明结果和恢复，不能只证明创建成功。

### WordPress/WooCommerce当前实现

当前WooCommerce/Storefront通过After Summary Action、商品关联ID、分类/标签、原生模板和ProductCard组成推荐区；DentAll只过滤显示上限并用Product条件CSS排列。空结果在原生模板输出section前处理，跨区排除使用全部Upsell ID。

### Shopify或其他平台的对应机制

可迁移的是平台事实源、展示上限、空态与隔离验证的分层方法。Shopify或其他平台的推荐服务、主题组件和人工关联配置未在本项目验证，具体对应待验证，不构成DentAll第一版扩项。
