---
类型: 项目Day笔记
项目: DentAll WooCommerce
日期: "2026-10-07"
工作日: Day87
主题: 文章详情与公开署名
状态: 隔离Local技术验证完成；临时副本删除受自动审批拦截，未部署
tags:
  - DentAll
  - Day87
  - Article
---

# Day87 文章详情与公开署名

## 结论

用户明确批准按D87最小范围在隔离Local实施。单篇文章继续使用WordPress原生Post及Storefront详情模板；子主题只替换暴露后台作者的元信息并限制长文阅读宽度，`dentall-core`让Yoast的普通Article、HTML作者元标签与社交预览统一表达`DentAll Editorial Team`。两篇不同后台作者的TEST文章已通过网页、Yoast REST、四端、键盘和原生编辑器回归。隔离服务已停、TEST文章已删，临时WordPress/数据库副本仍在忽略目录；未进入Staging或Production。本任务未连接或写入共享Local，但测试期间其监听端口有并发变化，不能宣称其全局状态未变。

## 相关笔记

- 学习笔记：[[WordPress实战笔记/Day87-文章详情署名与SEO输出边界]]
- 前置：[[Day86-博客列表分类分页与空状态]]、[[Day85-通用内容页模板与编辑回归]]
- 学习索引：[[WordPress实战笔记/WordPress实战笔记索引]]
- 每日索引：[[README|DentAll每日笔记索引]]
- 当前状态：[[../PROJECT_STATE|项目当前状态]]
- 合成站点单篇与长表格回归：[[Day90-内容样本与集成抽样]]

## 授权、范围与责任

- 业务问题：D19的ADR-017已确定前台统一署名，但Storefront单篇与Yoast Article原先公开了后台真实作者；D86只处理列表，未处理详情。
- 使用角色：匿名访客和客户阅读、分享；Website Manager继续用个人账号维护原生Post及其修订。内容数量和发布频率由业务录入决定，代码不写死篇数。
- 数据来源：原生Post的标题、正文、日期、图片、评论与真实`post_author`；不新增作者账号、字段或第二套作者事实。
- 必须做：单篇可见团队署名与日期、普通Article作者一致、长文和媒体四端可读、原生内链和相邻文章导航、现有OG/Twitter基础分享输出回归。
- 明确不做：作者归档重开、自动相关推荐查询、目录、分享SDK/按钮、临床文章类型、正式文章事实代填、SEO全局配置、支付或缓存调整。
- 开发边界：模板/可见HTML与局部CSS在子主题；跨主题SEO身份合同留在既有`dentall-core` SEO模块。编辑团队负责真实名称、图片、Alt、分类、文案、内链及授权素材的审核发布。

## 最多3项验收结果

- [x] 两名后台作者的单篇都显示`DentAll Editorial Team`，后台`post_author`保持原值；HTML与Yoast REST不公开后台显示名，Article作者为Organization且图谱无悬空Person引用。原生Users REST例外另列RSK-057。
- [x] 390/768/1024/1440px的长文、缺图、正文图片、表格和长标识符可读、无横向溢出；Blog与普通Page不受单篇规则影响。
- [x] 原生内链、相邻导航和评论/键盘路径可用；静态检查、独立审查、服务关停与TEST文章清理有证据。临时副本删除受自动审批拦截，单独记录。

## 实施与关键取舍

| 文件 | 职责 |
|---|---|
| `app/public/wp-content/themes/dentall/inc/article.php` | 在单篇请求的`wp`阶段移除Storefront默认作者元信息，输出日期、团队署名和适用时的评论入口 |
| `app/public/wp-content/themes/dentall/functions.php` | 加载文章展示模块 |
| `app/public/wp-content/themes/dentall/style.css` | 单篇`.site-main`限制46rem阅读行长，元信息自然换行；主题版本升至0.47.0以更新资源版本 |
| `app/public/wp-content/plugins/dentall-core/includes/seo-compatibility.php` | 按Yoast目标文章上下文统一Article作者、移除旧Person及WebPage引用、修正HTML与社交预览署名 |
| `app/public/wp-content/plugins/dentall-core/dentall-core.php`、`readme.txt` | Core版本升至0.7.0并记录变化 |

WordPress和Storefront继续负责原生Post查询、`single.php`模板、唯一标题、正文区块、特色图、文章分类、相邻导航和评论。没有覆盖父主题模板，也没有第二次文章查询。Yoast目标对象的`indexable.object_type/object_sub_type`用于同时覆盖网页与按文章ID生成的REST预览；仅看WordPress当前主查询会漏掉REST。现有Yoast Article日期、图片、publisher及OG/Twitter标题、描述保持其原机制；站点级X账号作为品牌creator回退保留。

阅读宽度只在隔离浏览器发现桌面正文扩至1256px后增加；复用D85已经验证的46rem尺度，不复制四套DOM或断点。表格和长标识符在四宽实测没有横向溢出，未预先增加额外裁切规则。没有目录和自动相关内容，因为当前TEST长文与原生相邻导航未证明它们必要。

## 隔离Local验证记录

- 隔离环境：WordPress 7.1、WooCommerce 11.0.0、Storefront 4.6.2、Yoast 28.2、PHP 8.2.29；`127.0.0.1:17871` HTTP和`127.0.0.1:17872` MySQL。数据库、WordPress副本及TEST内容仅位于忽略目录`.codex-tmp/d87-local/`；源Local只作为只读副本来源。
- 两篇TEST Post保留不同后台作者：#206的ID2 `DentAll D6 Editor`，#207的ID3 `DentAll D12 Manager`。源SQL快照的Woo Coming Soon初始遮住正文，测试人员仅在隔离库关闭后重新取证，没有把占位页200误计为详情通过。
- 网页及Yoast REST终测：两篇均为200；可见署名、`meta name="author"`、Twitter `Written by`和REST的相应Yoast字段均为编辑团队；Article.author为Organization，WebPage无后台Person引用，图谱无Person节点，HTML无作者归档链接。原生REST `post.author`仍返回实际后台作者ID，这是保留的原生数据身份，不应误写为团队账号。
- 品牌X账号格式回归：`twitter:site`与`twitter:creator`均为`@dentall`，没有个人`article:author`或个人X Creator；REST `yoast_head_json.twitter_creator`同为品牌账号。隔离库临时将Yoast站点身份置空触发`site_represents=false`，网页与REST仍无WebPage作者悬空引用、无Person节点，随后精确恢复`company`。
- 分享与图谱：两篇均有正确的`og:type=article`、OG标题和自身URL；A正文图用作OG图，临时设特色图后OG改取特色图且图谱有一个ImageObject，恢复后回正文图；B无图时不制造`og:image`。`twitter:card=summary_large_image`，当前Yoast未单独输出Twitter标题/图标签；无图B的完整图片卡不作为已验。两篇Article与WebPage节点各1、`@id`无重复。隔离响应与Meta均noindex，Canonical缺席是保护设置结果，不能外推公开环境。
- 匿名原生Users REST边界：`/wp-json/wp/v2/users/2`和`/users/3`均为200，仍含后台显示名、账号Slug和已关闭作者归档的链接。此为既有公开接口，不在D87最小实施范围；记录[[../RISK_REGISTER|RSK-057]]/P2，不把单篇和Yoast输出统一表述为全站匿名。
- 四端Chrome终测：两篇单篇、Blog和普通Page在390/768/1024/1440px共16次页面检查均200、主内容H1各1、`documentElement.scrollWidth`等于视口宽，`pageerror=0`；两篇单篇各只有1个Title和1个JSON-LD脚本。长文含H2/H3、列表、引用、表格、图片、270字符连续标识符和手工内链。单篇最终正文宽350/704/736/736px，图片滚动后成功`decode()`；缺图样本未出现空媒体占位。
- 状态边界：单篇为服务端渲染，没有独立异步loading；隔离库临时清空B正文后，390/1440px仍200、H1及团队署名可见、正文容器为空、无溢出和页面错误，随后恢复原正文。无效`/blog/test-d87-nonexistent-slug/`由WordPress返回404；文章不涉及售罄/不可购买。
- 键盘实测：390px首次和第二次Tab分别到`Skip to navigation`、`Skip to content`，两者有3px可见焦点；Enter跳到`#content`。正文Blog内链、相邻Next导航及byline评论链接均可通过Tab聚焦并用Enter到达目标，评论textarea有可见`Comment *`标签和3px焦点；全程`pageerror=0`。
- 静态检查：PHP 8.2.9 CLI对修改的PHP文件`-l`无语法错误；`git diff --check`通过。独立Code Review终态P0/P1/P2/P3=0。隔离网络阻断了Storefront Google Fonts，真实字体与设备仍待非Local复核。
- 原生编辑器回归：隔离Website Manager #3进入Gutenberg，给B加`[TEST SAVE]`临时标题并用`core/editor`原生保存，前台读到新标题且编辑器无脏状态；同一路径恢复原题，标记消失。两篇后台作者ID持续为2/3，B的Slug和发布状态未变；仅有WordPress编辑元数据及Yoast分析元数据变化，D87未新建自定义字段。预览和自动保存未覆盖，独立REST `context=edit`请求因无编辑器nonce返回401，不计为编辑失败。
- 特色图可逆回归：A短时指定现有带Alt的TEST媒体#45，390/1440px分别选择合适响应式源并显示350×350/736×736，尺寸属性、Alt、加载与无横向溢出通过；A已恢复无特色图，B始终无图。正文图另经四端滚动加载验证。
- 清理与隔离：两篇TEST Post已从隔离库删除，一次性登录凭据文件已清空；`17871/17872`服务、进程与监听均为0。自动审批两次拒绝递归`Remove-Item`删除`.codex-tmp/d87-local/public`与`mysql-data`，仅返回`blocked by policy`，未给更具体原因；两份临时副本仍在Git忽略目录，截图与脚本留供复核。目标绝对路径已确认属于本工作树隔离目录，未尝试绕过拒绝。共享Local的`10011`端口在测试期间由另一PID启动，本任务未连接或写入，不能据此声称共享Local全局不变量已复核。

## 风险、页面影响与后续

| 检查面 | 当前结论 |
|---|---|
| 数据 | 运行代码不写文章、用户或订单数据；TEST文章及可逆选项操作只在隔离副本，TEST文章已删除。临时数据库副本因自动审批拦截仍留忽略目录；真实内容和素材仍待业务验收 |
| URL | 沿用`/blog/{slug}/`与`/blog/category/{slug}/`，未改Slug、固定链接或301；D86文档中的短路径笔误已纠正 |
| SEO | 单篇公开作者输出发生有意变化；不改Title、Meta Description、Canonical、robots、Sitemap或Article类型。隔离站强制noindex，不能证明Production索引与Canonical |
| 缓存与性能 | 单篇沿用主题已有`style.css`请求，无新增JS、远程调用、Cron、自动加载选项或自定义查询；主题/Core版本变化需要部署时资源缓存正常失效。未做前后性能测量，不宣称零影响 |
| 支付、物流、订单 | 不涉及 |
| 部署 | 当前仅隔离Local分支；Staging、Production、CDN/页面缓存及真实浏览器辅助技术仍待对应授权和回归 |

## 减法审查与安全微调

- 运行层只新增一个子主题文章职责文件，不增加模板覆盖、插件、JS、构建链、TOC、相关查询或分享集成。SEO过滤器留在既有Core模块，避免第二套相同生命周期的SEO入口。
- 运行差分为新增运行文件1个、函数9个、CSS规则块2个、净增184行（含版本、readme与注释；跟踪文件净增132行，新文件52行）；Yoast网页/REST、Article/WebPage图谱、HTML作者、OG/Twitter/Slack各有独立输出路径，故保留对应小过滤器。
- 阅读布局先在Chrome DevTools暂试`.single-post .site-main`的`max-width`，判断为单篇局部规则后回写`style.css`；后续微调先区分公共Token、普通Page共用尺度和单篇局部规则，再复验四端及Blog/Page。
- 没有新查询、字段、资源请求、模板、JavaScript或依赖；未来若出现真实编辑导航需求，再对目录或相关内容单独确认。

## 可复用核心思想

### 跨平台不变量

内容生产账号、公开责任署名和结构化数据作者是不同职责；一旦对外采用统一署名，页面、分享预览与机器可读数据必须指向同一公开身份，同时保留后台真实操作归属。响应式长文先量实际行长和溢出，再决定局部限制。

### WordPress/WooCommerce当前实现

原生Post与`post_author`承担内容和后台归属；Storefront Hook承担详情元信息显示；Yoast的目标`indexable`承担网页与REST共同的SEO事实。子主题负责HTML/CSS，`dentall-core`负责跨主题SEO过滤，WooCommerce交易域不参与。

### Shopify或其他平台的对应机制

平台也需要区分后台编辑者、前台署名和结构化数据，但Shopify的Blog/Article模板及元数据扩展点应按实际主题与官方机制再验证，不能把Storefront Hook或Yoast过滤器照搬。
