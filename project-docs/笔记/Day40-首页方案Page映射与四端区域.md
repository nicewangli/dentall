---
项目: DentAll WooCommerce
工作日: D40
计划检查点: D40（不自动等于一个完整实际工作日）
日期: 2026-08-31
实际有效工时: 未记录；不使用计划工时代填
验收层级: Local技术实现与静态合同完成；真实浏览器四端视觉复验转D42
状态: 已完成授权范围内代码、Local noindex TEST数据、边界测试与独立Review；浏览器控制链路超时形成P3证据缺口
tags:
  - DentAll
  - Day40
  - Homepage
  - Page
  - Responsive
---

# DentAll 每日复盘 D40：首页方案Page映射与四端区域

## 相关笔记

- 每日笔记索引：[[README|DentAll每日笔记索引]]
- 前置项目笔记：[[Day39-首页精选分类入口与自适应换行]]
- 卡片展示合同：[[Day29-三类卡片组件契约]]
- 当日学习笔记：[[WordPress实战笔记/Day40-菜单驱动的Page映射与原生摘要]]
- 前置学习笔记：[[WordPress实战笔记/Day39-菜单驱动的分类查询与Flex换行]]
- 后续项目笔记：[[Day41-首页累计热卖与信任指标条]]

> [!success] 当前结论
> D40推荐范围已在Local落地。首页新增独立`Homepage solutions`原生菜单位置，由菜单选择和排序真实已发布Page；Page标题、固定链接、手工Excerpt、特色图和发布状态仍是事实源。过滤后最多输出4张既有SolutionCard，0项整区不输出，首个有效项自动高亮，缺摘要不回退正文，缺图使用既有text-only合同。当前menu ID 28绑定4个明确标记且逐页`noindex`的TEST Page；未创建、链接或推断`/solutions/`，因此不显示`View all`。代码、数据、SEO、静态DOM和独立Review已完成；由于Chrome调试连接持续超时，未取得真实首页390/768/1024/1440截图与键盘Focus证据，该P3明确转D42复验，不能表述为真实浏览器四端视觉通过。

## 用户授权与功能确认单

用户于2026-08-31明确授权：

> 确认按推荐范围实施Day40；允许使用Local-only noindex TEST Page，未确认 /solutions/ 前不显示 View all。

已确认的第一版范围：

- 业务问题：首页SolutionCard需要读取可由后台维护的真实Page，而不是硬编码标题、链接、图片或正式业务事实。
- 使用角色与频率：Website Manager维护Page内容；Administrator通过低频原生菜单选择和排序，因为Website Manager当前没有`edit_theme_options`。
- 数据来源与数量：Page标题、手工Excerpt、固定链接、特色图和发布状态为事实源；菜单只选择与排序；过滤后最多输出前4个有效Page。
- 必须做：启用Page原生Excerpt；排除非Page、草稿、密码保护、重复、空标题及失效链接；首个有效项高亮；缺摘要不输出摘要；缺图切换text-only；0项整区不输出；390/768/1024/1440采用1/1/2/4列合同。
- 明确不做：不建CPT、ACF、插件、模板覆盖、JavaScript、逐卡CTA字段或Featured字段；不创建或硬编码`/solutions/`；不输出`View all`；不实现D41；不改Staging/Production。
- TEST边界：只在Local创建明确标记并设为`noindex`的TEST Page；正式标题、Slug、摘要、图片、文案和素材授权未冻结。
- URL/SEO：TEST Page产生Local URL及首页内部链接，但逐页`noindex`且不得进入Yoast Sitemap；不存在候选`/solutions/`路由或链接。
- 性能/缓存：复用现有Homepage CSS请求和WordPress图片API；不新增JS、远程请求、Cron或缓存策略；主题版本升级只用于刷新既有CSS缓存键。
- 部署/回滚：仅Local。取消菜单位置绑定即可让整区空输出；TEST Page和菜单可按本文ID单独清理；源码按D40最小差异回滚。
- 排期影响：仍为D40一个计划检查点；实际工时按需记录，不增加总计划工作日。

## 最多三个验收结果

- [x] 专用菜单按顺序映射真实Page，过滤后最多4项；0/1/4/4+、草稿、密码保护、重复、非Page和失效项行为取得可复演PHP证据。
- [x] 复用D29 SolutionCard单链接DOM，代码与静态夹具实现390/768/1024/1440的1/1/2/4列合同，并覆盖缺摘要、缺图、长文本、首卡高亮和无行内脚本/样式；真实首页四宽截图、Focus、卡高和溢出量测因Chrome链路超时登记为D42 P3，不冒充通过。
- [x] 4个Local TEST Page逐页`noindex`且不在Page Sitemap；页面未输出`View all`或`/solutions/`。独立代码、设计和测试Review完成，未关闭P0/P1/P2为0。

## 7个专注周期的实际落点

| 周期 | 计划 | 实际结果 | 证据 | 实际用时 |
|---|---|---|---|---|
| C1 | 冻结Page字段、菜单选择与URL边界 | 确认“菜单选项＋Page事实”的最小模型，冻结不建`/solutions/` | 本笔记功能确认单 | 未记录 |
| C2 | 启用Page摘要与注册菜单位置 | `page`增加原生Excerpt支持，注册`homepage_solutions` | `inc/setup.php`、`inc/homepage.php` | 未记录 |
| C3 | 实现有效Page查询与4项上限 | 一次`get_posts()`批量读取；发布、无密码、去重、标题/链接过滤后再取前4项 | PHP边界测试 | 未记录 |
| C4 | 映射SolutionCard及缺省状态 | 首项featured、只读手工Excerpt、WordPress响应式图片、缺图text-only、统一CTA | 实际渲染DOM审计 | 未记录 |
| C5 | 完成1/1/2/4渐进布局 | 单一DOM，48rem显示摘要、64rem两列、75rem四列，并补sky/blue/mint层次 | `homepage.css`、静态夹具 | 未记录 |
| C6 | 建立Local noindex TEST数据并验证 | 4个TEST Page、menu ID 28、Yoast noindex和Sitemap排除完成 | Local API/HTTP证据 | 未记录 |
| C7 | 独立Review、减法审查和文档收尾 | Code/Design/Test专项复核，关闭P0～P2；浏览器链路P3转D42 | 专项Review、本笔记与学习笔记 | 未记录 |

## 实际实现

### 数据链与执行顺序

1. `after_setup_theme`为Page启用核心Excerpt输入框，并注册`Homepage solutions`菜单位置。
2. Administrator在`外观 → 菜单`把真实Page加入专用菜单，菜单只负责“选哪些、按什么顺序”。
3. Storefront Homepage Action依次输出Hero（10）、精选分类（20）和Solutions（30）。
4. `dentall_get_homepage_solutions()`只接受`post_type/page`菜单项，先按对象ID去重，再用一次`get_posts()`读取所有候选Page。
5. 查询限制为`publish`且无密码；结果继续排除空标题和失效固定链接，过滤后截取前4项。
6. `dentall_homepage_solutions()`从Page重新读取标题、固定链接、原始手工Excerpt和特色图，不采用菜单自定义标签或自定义URL。
7. 0项在输出`section`前返回；有项时输出单一语义DOM，CSS按Mobile First合同渐进为1/1/2/4列。

### 字段映射

| 卡片内容 | 事实来源 | 缺失行为 | 菜单能否覆盖 |
|---|---|---|---|
| 标题 | Page标题 | 该Page不进入结果 | 否 |
| 链接 | Page固定链接 | 该Page不进入结果 | 否 |
| 摘要 | 原始`post_excerpt`，去短代码与HTML | 不输出摘要，不回退正文 | 否 |
| 图片 | Page特色图，经`wp_get_attachment_image()` | 增加`text-only`类，不输出假图 | 否 |
| 高亮 | 过滤后第一项的位置 | 始终由有效结果自动决定 | 菜单排序间接决定 |
| CTA | 统一可翻译`Learn more`＋装饰箭头 | 不增加逐Page字段 | 否 |

Page摘要支持只是让核心Page编辑界面具备原生Excerpt；它不会自动生成摘要，也不会改变正文、URL或公开状态。前台明确读取`raw post_excerpt`，所以空摘要不会把正文截断后意外带到首页。

### HTML与响应式合同

- 区域结构为`section[aria-labelledby] > div > h2 + ul > li > a`，每张卡只有一个整卡链接和一个`h3`。
- 图片由WordPress图片API生成`width`、`height`、`srcset`与`sizes`；卡片链接已有全局`:focus-visible`基线。
- 390px与768px均为1列，1024px为2列，1440px为4列；不是复制四套DOM。
- 390px隐藏摘要，`>=48rem`显示；长标题和摘要沿用D29的`min-width:0`与换行保护。
- 第一张为深色featured；第2、3、4张分别使用既有sky、blue、mint表面层次。颜色按展示位置而不是写入Page业务字段。
- 缺图卡不制造占位图；统一CTA箭头标记`aria-hidden="true"`，不会重复进入可访问名称。

## Local TEST数据

| ID | Slug | 状态用途 | 特色图 | 摘要 |
|---:|---|---|---|---|
| 106 | `test-d40-practice-setup` | 正常＋首卡featured | attachment 45 | 有 |
| 108 | `test-d40-infection-control-planning` | 正常＋长摘要 | attachment 47 | 有 |
| 110 | `test-d40-digital-workflow` | 验证空Excerpt不回退正文 | attachment 48 | 空 |
| 112 | `test-d40-custom-restoration-support-long-title` | 缺图＋长标题 | 无 | 有 |

- 菜单：`TEST D40 Homepage Solutions`，menu ID 28，按106、108、110、112排序并绑定`homepage_solutions`。
- 四个Page均已发布，但正文明确写明Local-only TEST，不是批准业务内容。
- 四个Page均通过Yoast API设置`noindex`；Yoast Indexable读取结果均为`robots_index=noindex`。
- `page-sitemap.xml`返回HTTP 200，四个D40 Slug命中0；`/solutions/`命中0。
- Website Manager具备`edit_pages`和`publish_pages`，但没有`edit_theme_options`；因此内容由Website Manager维护，菜单继续由Administrator管理。

## 正常、异常与边界状态

| 状态 | 实际行为 | 证据 |
|---|---|---|
| 未绑定菜单/0项 | 整个Solutions区域0字节 | 临时取消位置的非持久化PHP测试 |
| 1项 | 1张卡且自动featured | 非持久化菜单过滤测试 |
| 4项 | 4张卡，按菜单Page顺序 | menu ID 28实际渲染 |
| 4+项 | 有效过滤后只取前4项 | 混合候选测试返回106、108、110、2，后续ID不占位 |
| 草稿 | 排除 | `post_status=publish`负向测试 |
| 密码保护 | 排除 | 临时密码Page负向测试，结束后原值已恢复 |
| 重复Page | 只保留第一次出现 | `$seen_ids`测试 |
| 自定义链接/非Page | 排除 | 菜单类型白名单测试 |
| 菜单自定义标题/URL | 忽略，继续使用Page事实 | 非持久化菜单对象覆盖测试均为`no` |
| 空Excerpt | 不输出摘要，也不使用正文 | Page 110与DOM审计 |
| 缺图 | 输出text-only卡，无媒体节点 | Page 112与DOM审计 |
| 空标题/失效链接 | 不进入有效结果，也不占4项名额 | 查询后过滤分支审计 |

## 验证证据

### 运行时与DOM

- Local版本：WordPress 7.0.4、WooCommerce 11.0.0、Yoast SEO Free 28.2、Storefront 4.6.2、PHP 8.2.29、DentAll 0.18.0。
- Page Excerpt支持为`yes`；`homepage_solutions`已注册；未绑定时查询结果0且输出0字节。
- 最终DOM计数：section 1、cards 4、links 4、titles 4、summaries 3、images 3、featured 1、text-only 1、CTA装饰箭头4。
- 输出中`View all`为0，`/solutions/`为0；菜单自定义标题与URL均未进入最终HTML。
- 三张图片均带尺寸和`srcset`，装饰图Alt为空；WordPress可能在`sizes`前自动补`auto,`，属于核心加载优化行为。
- 首次本机CLI样本约增加13次查询、约5.02ms；同请求缓存后的第二次输出增加0次查询、约1.62ms。该数据只说明当前Local样本，不外推生产性能，也不宣称“零影响”。

### 代码、CSS与夹具

- 子主题全部PHP文件在Local PHP 8.2下通过语法检查；仅打印既有`php_imagick.dll`缺失启动警告，本轮未修改PHP扩展。
- `homepage.css`为311行、7329字节、55/55花括号、0个`!important`；资源HTTP 200并使用0.18.0版本键。
- 静态夹具含4卡、4链接、1个H2、4个H3、3段摘要、3图、1个featured、1个text-only及9个唯一ID；0个行内`style`、`script`和事件属性，6个引用资源均存在。
- `git diff --check`通过；工作树原有其他未提交改动保持不动，本轮没有清理或覆盖用户文件。

### 独立Review

- Code Review：P0/P1/P2/P3均为0；PHP输出转义、查询过滤、WordPress图片API、CTA装饰语义和局部表面Token无问题。
- Design Review：P0/P1/P2为0；已关闭“非featured层次不足”和“CTA缺箭头”两项候选。保留的真实量测项为P3。
- Test Review：查询、DOM、SEO/noindex、空/混合状态与恢复边界通过；浏览器控制链路问题不算功能通过证据。

### 未取得的浏览器证据

Chrome能列出标签页，但认领页面后`domSnapshot()`和定向求值持续超时并重置调试内核。按浏览器工作流停止重复尝试，也没有用另一个脚本浏览器伪造同一证据。因此本轮不能宣称：

- 真实登录态首页在390/768/1024/1440均已截图通过；
- D40区块真实计算卡高、16px gap和D39/D40纵向间距已量测；
- 键盘实际Tab顺序、3px Focus与水平溢出已在D40真实页重新验证；
- 当前方形TEST图能够代表正式Solution素材的裁切与视觉重心。

这些均是P3视觉/证据债，不改变已验证的查询、安全、SEO和静态结构合同。D42应优先在真实页复演；如果截图证明间距偏松，只局部调整D40 gap或顶部间距，不修改全局Section Token。

## 减法与可维护性审查

- 运行文件净增0：继续复用D37建立的`inc/homepage.php`与`assets/css/homepage.css`，另只修改既有`inc/setup.php`和`style.css`版本。
- 依据D39记录的UTF-8基线，`homepage.php`从269行/7553字节变为431行/12182字节，净增162行/4629字节；其中Solutions函数区块160行，另有1个菜单位置和1个Homepage回调注册。保留2个职责相邻函数，没有把字段或查询拆成无收益微文件。
- `homepage.css`从272行/6219字节变为311行/7329字节，净增39行/1110字节/9个叶子规则块；只承担区域背景、标题、1/1/2/4列、摘要可见性和卡片表面层次。
- 非运行时新增1个75行/4263字节noindex静态夹具，用于稳定复演四卡状态；不创建路由、不执行JS、不写数据库。
- 净增JavaScript、模板覆盖、插件、依赖、CPT、ACF、自定义字段、Options、Cron、远程请求和交易逻辑均为0。
- 保留理由：查询与输出同属Homepage Solutions职责且生命周期一致；Page Excerpt支持属于主题编辑能力；响应式规则继续放既有条件Homepage样式，避免全站加载和职责扩散。

## 开发与业务责任边界

- 开发者：维护菜单位置、Page过滤、输出转义、响应式合同、空/错误状态、测试与文档。
- Website Manager：维护Page正式标题、Excerpt、正文、特色图、发布状态和内容级SEO；不能通过菜单别名改变首页事实。
- Administrator：低频维护专用菜单选项与顺序；不因方便而扩大Website Manager到主题设置权限。
- 业务方：确认正式Solution集合、URL/Slug、文案、图片授权和是否需要总览页；当前TEST事实不得迁移为Production内容。

## DevTools安全微调路径

1. 在Elements定位`.dentall-home-solutions`、`.dentall-home-solutions__grid`与`.dentall-solution-card__link`，先确认命中的规则来自`homepage.css?ver=0.18.0`。
2. 临时试验`gap`、局部`padding-block-start`或已有Surface Token；不要在页面编辑器写行内样式。
3. 判断改动属于全局卡片合同、Homepage公共布局还是D40局部规则；没有跨页面证据时保持最靠近D40的低权重覆盖。
4. 回到子主题源码修改，重新验证390、768、1024、1440，以及Home相邻Hero/D39分类区和Shop中的D29卡片。
5. 核对键盘Focus、长文本、缺图、0项、`View all`/`/solutions/`缺席、CSS版本与控制台，再记录真实截图或数值。

## 站点影响与回滚

| 检查面 | D40实际影响 | 回滚/后续 |
|---|---|---|
| 数据 | Local新增4个已发布TEST Page和menu ID 28 | 先取消位置绑定；需要时按本文ID清理，不能误删正式内容 |
| URL | 产生4个Local TEST Page URL和首页内部链接 | 正式Slug未冻结；不迁移至非Local |
| SEO | 逐页Yoast noindex，Page Sitemap无D40 URL；没有`/solutions/` | 正式发布前重新审查index、Canonical、Sitemap和内链 |
| 缓存 | 子主题版本升至0.18.0刷新现有CSS查询键 | 未改页面/对象缓存策略 |
| 性能 | 一次菜单读取＋一次批量Page查询＋最多3张当前TEST图 | D42/上线前在真实缓存与素材下复测，不外推Local样本 |
| 支付/物流/订单 | 无影响 | 未接触交易流程或数据 |
| 部署 | 仅Local，Staging/Production未修改 | 部署必须另行授权并走备份、回滚与回归 |

## 下一步

1. D41先只读拆解Best Sellers与信任区，提交最多3项验收结果和功能确认单；不能把D40授权扩展到D41。
2. D42真实浏览器优先复验D40四宽截图、卡高、gap、相邻区块纵向节奏、Focus、水平溢出及正式/代表素材裁切；P3有证据后再决定是否做局部减法微调。
3. 业务方后续确认正式Solutions Page、Slug、Excerpt、素材授权及是否创建`/solutions/`总览；确认前持续隐藏`View all`，TEST Page不进入Staging/Production。

## 可复用核心思想

### 跨平台不变量

“选择与排序”和“内容事实”应分开：运营入口可以决定展示集合，但标题、链接、状态、图片等事实必须回到唯一内容对象读取。上限要在过滤有效项之后应用，否则坏项会占名额；缺字段应有诚实降级，不应用猜测正文或假链接补齐。

### WordPress/WooCommerce当前实现

DentAll在Local用原生菜单保存Page ID与顺序，用`get_posts()`批量读取已发布、无密码Page，并通过Page核心Excerpt、固定链接和特色图API输出D29 SolutionCard。Storefront `homepage` Hook决定模块顺序，子主题Mobile First CSS负责1/1/2/4列；Yoast只负责当前TEST Page的noindex证据，不改变内容模型。

### Shopify或其他平台的对应机制

跨平台仍应保留“运营集合引用真实内容对象、有效过滤后限量、缺省状态和URL/SEO闸门”。Shopify可能使用Navigation、Metaobject、Collection或Theme Section完成相似选择与展示，但具体权限、可引用对象、SEO索引和发布机制均待官方资料与实站验证；该对照不进入DentAll第一版实施范围。
