# DentAll 每日笔记索引

本目录统一存放 DentAll 项目 D1～D120 的每日工作笔记、阶段基线和当日形成的操作手册，方便通过 Obsidian 集中查阅。

## 存放规则

- 后续每日笔记一律创建在 `project-docs/笔记/`，不得散落在 `project-docs/` 根目录。
- 每篇笔记的文件名统一采用 `Day{天数}-中文主题.md`，例如 `Day5-编辑角色与权限.md`；技术专有名词可以保留英文。
- 同一天需要按主题拆分时，可创建多篇，例如 D4 的 Cloudways 与 WordPress 手册。
- 新笔记必须增加“相关笔记”章节，并使用 Obsidian `[[Wiki链接]]` 关联直接相关的前置、后续或同主题笔记。
- 双向关系必须在关联笔记两端都写入链接，不能只依赖 Obsidian 自动反向链接面板。
- 不为凑数量链接无关笔记；范围、环境、数据、开发、测试、发布和运维按真实依赖关联。
- 每日复盘沿用项目根目录的 `Obsidian每日复盘模板.md`。
- 自Day27起至Day120，每个Day在当天收尾时，默认生成一篇基于当天真实开发、配置、验证或排错内容的WordPress实战学习笔记，保存到`WordPress实战笔记/`；同一自然日期推进多个Day时仍分别收尾。学习笔记必须登记到专题索引，并与对应项目Day笔记和直接相关学习笔记显式双向链接。D1～D25不自动追溯补写，除非用户另行要求。
- 笔记只记录配置结果、操作路径、验证证据、决策和风险，不记录密码、私钥或支付密钥。
- 当前事实仍以 `../PROJECT_STATE.md` 为准；笔记用于保存每日过程和详细证据。

## 专题学习笔记

D59～D65各日已按批准范围完成；D60/D61/D63已通过`278d20d`合入并推送main。D68响应式购物车、D69 Header Cart/Mini Cart、D70原生优惠券、D71金额链和D72人工运费报价已形成D67～D72单一Local集成候选并通过合成树终验，待合入main。D66的RSK-035/037/038仍是非Local部署前门槛，M5暂不标Done；D69和D72的期限性P2继续管理。公开环境、正式内容、真实税费/物流、真实辅助技术、CWV与Variation Gallery多图另验。

| 专题 | 入口 | 用途 |
|---|---|---|
| WordPress实战 | [[WordPress实战笔记/WordPress实战笔记索引\|WordPress实战笔记索引]] | 从DentAll真实开发代码中学习WordPress、WooCommerce、子主题、Hook、安全与排错；学习笔记与对应Day项目笔记显式双向链接 |

## 周总结

D72候选记录：[[Day72-人工运费邮件报价与购物车收口]]；对应学习：[[WordPress实战笔记/Day72-人工运费报价与结账安全边界]]。D71候选记录：[[Day71-运费税费与金额摘要候选验证]]；对应学习：[[WordPress实战笔记/Day71-WooCommerce运费税费与金额真相]]。D70阶段记录：[[Day70-优惠券规则与边界验证]]；对应学习：[[WordPress实战笔记/Day70-WooCommerce优惠券校验与金额真相]]。D69候选记录：[[Day69-Header Cart与Mini Cart状态联动]]；对应学习：[[WordPress实战笔记/Day69-Cart Store与经典Fragments桥接]]。D68候选记录：[[Day68-手机与平板响应式购物车候选验证]]；对应学习：[[WordPress实战笔记/Day68-Cart-Block响应式布局与状态证据]]。D66阶段记录：[[Day66-商品浏览闭环集成回归]]；对应学习：[[WordPress实战笔记/Day66-集成基线与商品全链路回归]]。

| 周次 | 笔记 | 结论 |
|---|---|---|
| W1 | [[W1-环境与安全试录周总结]] | Local、受保护Staging、Git/恢复边界、最小权限与M1技术预验收完成；真实人员验收转D13 |
| W2 | [[W2-商品模型与双环境原型周总结]] | 商品规则v1候选、Local原型、Website Manager角色和Staging双环境验收完成；D18再候选冻结 |
| W3 | [[W3-商品样本与模型候选冻结周总结]] | 商品编辑与SEO样本、Simple/Variable职责、双环境权限和CSV验证完成；M2商品模型候选冻结通过 |
| W5 | [[Day30-设计系统v1与系统状态|W5前端设计系统周验收]] | D26～D30前端设计系统轨道通过，Design System v1完成；测试范围P0/P1/P2/P3为0，M3业务内容/素材/Git治理仍独立待办 |
| W7 | [[Day42-首页全链路校准与M4技术验收|W7首页与全局框架周验收]] | D31～D42登录态Local四端整链路、资源、可访问性与只读数据合同通过；M4按Local技术v1完成，正式内容、营销事实、非Local部署与生产性能仍为独立门槛 |
| W8 | [[Day48-商品分类内容与W8列表回归|W8商品列表与搜索（功能基础就绪）]] | D43～D48完成Shop/taxonomy/search原生主查询与单一语义DOM、2/2/3/4列Grid、顶部工具栏、底部分页、搜索正常/空态、分类描述和Yoast模板；D47空结果P2已补证，0.25.0修复长分类内容Grid裁切。恢复态Shop/有结果搜索/短内容分类的最终0.25.0完整四端证据已在D49配置前补齐，原P2关闭；正式内容与非Local仍是独立门槛 |
| W9 | [[Day54-商品发现链路回归与W9收口|W9商品筛选与发现链路Local技术收口]] | D49～D54冻结商品级筛选合同与lookup基线，完成PC侧栏、移动Dialog、原生品牌、已选条件、动态计数、公开GET治理及整链路回归。恢复态四端、分页、搜索、SEO、缓存和独立复核P0=P1=P2=0；D54运行代码0改动。30品牌以外、真实设备/辅助技术、正式内容、非Local缓存/部署和稳定Git基线仍是独立门槛 |

## D1～D6

| 工作日 | 笔记 | 内容 |
|---|---|---|
| Day1 | [[Day1-范围与访问依赖基线]] | 第一版范围、访问依赖和风险基线 |
| Day2 | [[Day2-本地环境与WooCommerce基线]] | LocalWP、WordPress、WooCommerce 与本地环境基线 |
| Day3 | [[Day3-代码数据与恢复边界]] | Git、代码、数据、密钥、备份和恢复边界 |
| Day4 | [[Day4-Cloudways配置与常用操作手册]] | Cloudways Staging、备份、安全、SFTP/SSH 与运维路径 |
| Day4 | [[Day4-WordPress与WooCommerce配置及常用操作手册]] | WordPress/WooCommerce 配置、菜单地图和排错路径 |
| Day5 | [[Day5-编辑角色与权限]] | 最小权限角色、Via Git部署、权限审计和Staging端到端验收 |
| Day6 | [[Day6-商品与文章安全试录]] | 开发者代理试录、M1技术预验收和D13真实编辑验收安排 |

## D7～D12

| 工作日 | 笔记 | 内容 |
|---|---|---|
| Day7 | [[Day7-商品资料盘点]] | 商品资料来源、可信度、代表场景和业务缺口 |
| Day8 | [[Day8-商品分类结构]] | 动态分类骨架、分类治理和Website Manager业务所有权 |
| Day9 | [[Day9-SKU品牌与属性规则]] | SKU、品牌、Global Attributes和Variation映射规则v1候选 |
| Day9 | [[Day9-商品业务确认清单]] | 逐商品事实延后到实际录入时按需确认的范围边界 |
| Day10 | [[Day10-商品类型与价格库存规则]] | Simple、Variable、Display Only、价格库存、物流尺寸、合法组合和D12原型输入 |
| Day11 | [[Day11-商品图片与资料文件规范]] | 商品图片比例、格式压缩、元数据、授权和上传边界 |
| Day12 | [[Day12-双环境角色与商品原型验收]] | Website Manager角色版本5、Local商品原型、Staging运营权限与双环境验收 |
| Day13 | [[Day13-真实编辑试录与简单商品流程]] | Website Manager培训者预演、独立复跑与简单商品发布字段候选 |
| Day14 | [[Day14-可变商品与Variation流程]] | Variable父子职责、默认值、合法组合、库存与购物车Local验收 |
| Day15 | [[Day15-库存与物流字段]] | 三层物流数据、Simple/Variable继承覆盖、库存模式、临时缺货与停售候选 |
| Day16 | [[Day16-商品SEO规则]] | Title唯一性、SEO字段职责、Slug/Canonical/301、缺货停售URL生命周期与双环境边界 |
| Day17 | [[Day17-代表商品录入与SEO验收]] | 5个累计代表样本、Simple/Variable、缺货Variation、多图、Yoast输出与D18冻结输入 |
| Day18 | [[Day18-商品模型候选冻结]] | 商品模型职责、Website Manager权限、Local/Staging CSV、M2候选冻结与D19交接 |
| Day18复盘 | [[Day18-真实性复盘]] | 纠正计划检查点、实际工时、技术验证、业务验收与可见成品的进度口径 |
| Day19 | [[Day19-博客分类与作者规则]] | 博客信息架构v1验收；原生Post/Category/Tag、`/blog/`路由、归档索引治理、内容作者与修订边界 |
| Day20 | [[Day20-文章录入模板]] | 文章字段职责、正文区块骨架、长文TEST草稿与草稿/预览/修订验证 |
| Day21 | [[Day21-固定页面清单与URL责任边界]] | About、Contact、政策页与FAQ候选清单、责任矩阵、菜单依赖和页面状态验收边界 |
| Day22 | [[Day22-Solutions内容模型]] | Solutions原生Page优先方案已在D24 C1获确认，不建CPT；原生Page编辑走查已由D24 C2完成，正式Solutions内容仍待完成 |
| Day23 | [[Day23-内容审核发布与媒体治理]] | 非强制互审已确认；`CONTENT_ASSET_REGISTER.md`现为唯一活动登记载体，`.xlsx`中的16条A/C3-A/C4记录迁移后生成文件已删除。D24 A/C3-A/C7技术与人员抽样已完成，正式内容/素材仍待，Git企业交接留D25 |
| Day24 | [[Day24-内容样本与操作培训]] | A/C5培训、A/C6 SOP辅助验收及A/C7状态/字段/预览/隔离/修订/登记抽样通过，开放P0/P1为0；3篇正式文章＋1个正式Page和授权16:9素材仍待，D24整体进行中 |
| Day24手册 | [[Day24-内容发布操作手册与WM-A培训]] | 基于Staging实测界面的发布、更新、恢复、审阅草稿、素材闸门与Markdown登记操作手册；明确无需背诵、必须理解与停止条件，A/C6-C7已通过 |
| Day24-B | [[Day24-B-真实样本与周验收]] | 真实来源第一轮盘点完成；正式文章与Page输入包0/4，技术骨架可继续、业务验收待输入，不能用TEST或未授权资料代替 |
| Day25 | [[Day25-综合验收与批量录入开放]] | C1～C7技术/人员验收完成：Staging Simple模板v1新增2个Draft、既有数据0变化、重复SKU跳过、#110普通恢复、创建者追溯与Markdown批次登记通过；M3正式内容/素材与公司Git治理门槛仍待 |
| Day25手册 | [[Day25-Website Manager商品导入导出与恢复手册]] | Website Manager原生Simple CSV导入/导出、批次前快照、映射检查、结果登记、停止和分层恢复SOP；Staging实测通过，Variable/Variation CSV与Production未开放 |
| Day26 | [[Day26-Storefront子主题骨架]] | 现有DentAll Starter转为Storefront子主题；旧阻断模板退出继承链，资源顺序、关键页面和导航回退保护完成Local验证，视觉实现留D27 |
| Day27 | [[Day27-设计证据与Design-Token]] | 已完成：冻结设计证据与冲突口径，子主题0.3.2落地63个Design Token、最小`body`基线、1320px外框及20/32px响应式gutter；C6真实Shop与C7四页×四端登录态DOM、截图、状态、日志及双重独立复核通过，P0/P1为0 |
| Day28 | [[Day28-基础控件与可访问状态]] | 已完成：子主题0.5.0完成原生`Sort by`、标题/文本链接、按钮、表单、Error与分区Focus基线；代表页面四端、真实键盘夹具、对比度、静态检查和独立Review收口，最终P0/P1/P2/P3为0；未部署Staging |
| Day29 | [[Day29-三类卡片组件契约]] | Product、Category、Solution三类卡片内部展示契约、13种状态、19-ID非持久化夹具、真实Shop四端和独立Review完成；真实分类/Page接入留D39/D40 |
| Day30 | [[Day30-设计系统v1与系统状态]] | D29/D30受控合并为0.8.6；Grid、Section、Loading、Empty、Classic/Blocks通知及真实页面DevTools排错完成，第5周前端设计系统周验收通过 |
| Day31 | [[Day31-PC公告栏与主页头结构]] | 子主题0.9.0已在Local完成三条TEST公告/三个右侧非交互槽位（币种/语言未来插件位置＋Help页面位置）、透明占位Logo、搜索/Account/原生Cart主行、动作蓝纠偏与`site-shell.css`；五页×四宽和1440完整Focus通过，390底栏三动作/展开后input已取证，折叠态直接Tab与1×1 submit转D33 P2；搜索提交、fragment/非空Cart、非Local正式Logo与WPML/WCML仍未验 |
| Day32 | [[Day32-PC主导航与一级下拉]] | 用户在Local创建并绑定`TEST D32 PC Navigation`；子主题0.10.0完成`>=1200px`的无分隔线PC导航、分类按钮上下8px留白、两个一级下拉、键盘/边界与五页回归，未新增PHP/JS/模板/插件；手机/平板、真实触屏和正式URL按D33/D34及内容节点接续 |
| Day33 | [[Day33-手机与平板竖屏Header]] | 子主题0.11.10在Local完成单一Primary DOM、手机/768实际Logo居中、左Menu/右Account与动态Cart数量、非模态面板、一级子项常显、完整手机放大镜及仅`Search products…`的视觉呈现；无新JS，Cart Blocks同页即时同步转D69，键盘完整链与四类页面批量回归由D34补证 |
| Day34 | [[Day34-平板横屏Header与断点收敛]] | 子主题0.12.0在Local把紧凑Header连续覆盖至1199，并在1200切换完整PC导航；关闭态子菜单、强制Reduced Motion夹具、390～1440边界、390/768/1024 Enter链、Skip Link实际激活及五类页面20/20通过，无新JS/插件/模板；用户于2026-08-28确认实体设备验收通过 |
| Day35 | [[Day35-PC页脚与Newsletter测试壳层]] | 子主题0.13.2新增独立Footer模块：一个深度2且无Page回退的菜单位置、Local不可提交Newsletter、动态品牌/版权与诚实空状态；Day35 Footer未输出社交/支付，七个宽度0溢出、390五类页面5/5及静态/HTTP/独立复核通过；匿名Coming Soon独立模板的默认社交链接登记为环境P2，正式菜单绑定和手机/平板收口转D36 |
| Day36 | [[Day36-手机与平板页脚和后台菜单绑定]] | Administrator在Local创建并绑定9项TEST Footer菜单；子主题0.14.1以同一DOM完成390单列、768四列、1024五列及1200起菜单＋品牌静态重排，未新增JS/Walker/模板/插件；正式栏目、真实Newsletter、社交/支付及非Local部署仍待独立确认 |
| Day37 | [[Day37-PC首页Hero与原生首页路由]] | 已完成（Local推荐最小技术范围）：子主题0.15.3用原生Home/Blog Reading路由、Storefront Homepage Hook、核心区块＋特色图完成Hero；根URL、`/blog/`和四端安全回归通过，当前3:2实色图仍仅限Local占位 |
| Day38 | [[Day38-手机与平板首页Hero精调]] | 已完成（Local确认范围）：同一Hero DOM以Mobile First Grid叠层完成四端精调；390/768隐藏辅助卖点、1024/1440显示三列，四端高度约320/320/333/352px且无横向溢出；正式透明素材、正式内容与非Local部署仍待 |
| Day39 | [[Day39-首页精选分类入口与自适应换行]] | 已完成（Local确认范围）：原生菜单选择/排序真实顶级非空`product_cat`，复用Woo CategoryCard；总数不限，390/768/1024/1440按3/5/9/9容量自动换行并居中末行，0/1/9/10项、缺图、长标题、Focus和循环恢复通过；正式分类内容与非Local部署仍待 |
| Day40 | [[Day40-首页方案Page映射与四端区域]] | 已完成（Local技术范围）：专用菜单选择/排序真实已发布Page，过滤后最多4张D29 SolutionCard；4个TEST Page逐页noindex且不进Sitemap，缺摘要/缺图/长标题/0～4+项通过，无`View all`或`/solutions/`；真实首页四端与相邻节奏已由D42补证，正式内容与非Local部署仍待 |
| Day41 | [[Day41-首页累计热卖与信任指标条]] | 已完成（Local技术范围）：按Woo累计`total_sales`读取最多5个真实可见商品，0销量整区隐藏；复用ProductCard和纯CSS横滑完成四端1/3/4/5卡容量。五项Trust数据/图标仅Local预览，三路独立复核最终P0/P1/P2=0；整页Newsletter邻接已由D42补证，真实订单正销量、正式指标与非Local部署仍待 |
| Day42 | [[Day42-首页全链路校准与M4技术验收]] | 已完成（M4 Local技术v1）：登录态390/768/1024/1440整链路、0页面溢出、Console、主题资源、PHP与15/15只读审计通过；无P0/P1或阻塞M4的P2，运行代码0改动、主题保持0.19.0且未创建TEST订单；正式内容、非Local部署和生产性能另行验收 |
| Day43 | [[Day43-商品归档信息架构与PC骨架]] | 已完成（Local确认范围）：Shop公开标题为`Products`且`/shop/`不变；Shop/taxonomy复用WooCommerce原生Archive Header、主查询、循环和ProductCard，0.20.0条件加载归档CSS；四端、正常/空态、排序、搜索隔离及独立复核通过，D44～D49未提前实施 |
| Day44 | [[Day44-商品网格响应式]] | 已完成（Local确认范围）：子主题0.21.0用CSS Grid完成390/768/1024/1440的2/2/3/4列和16/24/24/24px gap，Woo仍按3×4保持12项/页；正常/空taxonomy、搜索隔离、320边界及三路独立复核通过。真实1/5/12项与D29特殊状态动态整合、Console/独立HTTP和非Local仍待验 |
| Day45 | [[Day45-商品排序与结果信息]] | 已完成（Local确认范围）：子主题0.22.0复用Woo原生GET排序与主查询，将Shop/taxonomy收敛为一组顶部工具栏；320～1024视觉隐藏但保留结果状态，1440结果左/排序右。五宽、排序URL、正常/空taxonomy、搜索隔离、Canonical及三路独立复核通过，未改12项、Grid、分页、搜索或数据 |
| Day46 | [[Day46-商品归档分页与URL归一化]] | 已完成（Local确认范围）：子主题0.23.0保留Woo每页12项和原生分页，只输出一组底部导航；44px、Focus、五宽、Shop/taxonomy 12/1、排序参数、Page 1直链、Canonical/404、空态和搜索隔离通过。11个临时商品已移入回收站，发布基线恢复2项；未提交Git或部署非Local |
| Day47 | [[Day47-商品搜索结果与边界状态]] | 已完成（Local确认范围）：子主题0.24.0让明确商品搜索复用原生标题、面包屑、主查询、ProductCard、Grid、顶部工具栏与底部分页；无效输入302、单结果原生302、零结果双CTA、noindex/无Canonical、真实12/1和最终夹具回收通过。有结果与空结果页登录态四宽、Focus、Console和截图均已完成；未提交Git或部署非Local |
| Day48 | [[Day48-商品分类内容与W8列表回归]] | 已完成（Local功能与最终视觉证据）：用#18可逆验证分类长标题、多段描述、链接及Yoast内容级覆盖，移除商品分类Title/Social Title中的`Archives`；0.25.0以3条局部CSS声明修复390px长token Grid裁切。#18已恢复、#120～#130未恢复、无正式分类；最终恢复态Shop、商品搜索和短分类四端证据已在D49配置前补齐，原P2关闭；正式内容和非Local配置重放仍待 |
| Day49 | [[Day49-商品筛选合同与属性查询表]] | 已完成（Local确认范围）：关闭Day48证据P2；冻结分类、价格、Size、Shade商品级筛选合同及父商品Variation语义；完整重建并启用WooCommerce属性查询表，最终7行/2父商品、Direct=yes、Optimized=no。商品、库存、属性归档、缺货设置及#120～#130均未改变；筛选UI、参数页robots实现、品牌、评分、正式数据、缓存和非Local另行验收 |
| Day50 | [[Day50-PC商品筛选与参数页索引收口]] | 已完成（Local确认范围）：DentAll 0.26.0仅在1200px起为Shop/商品分类显示Categories、Price、Size、Shade常驻侧栏，复用Woo主查询与Layered Nav且无JS；DentAll Core 0.2.7将筛选参数页设为noindex/follow并保留基础归档Canonical。四宽、组合、非法输入、排序/分页URL、键盘与数据不变量通过，最终P0～P3=0；移动抽屉、品牌、计数、缓存与非Local另行验收 |
| Day51 | [[Day51-手机与平板筛选抽屉]] | 已完成（仅Local确认范围）：DentAll 0.27.0在小于1200px以原生dialog承载D50同一Categories、Price、Size、Shade筛选aside，完成Filter入口、关闭/遮罩/Escape、焦点进入返回、滚动锁、方向/断点/BFCache恢复；1200px起仍为240px常驻侧栏，商品搜索不加载脚本。未新增查询、参数、数据、插件或非Local变更；当日最终矩阵证据P2已在D52实施前补跑关闭 |
| Day52 | [[Day52-品牌数据与筛选基线]] | 已完成（仅Local确认范围）：DentAll 0.28.0复用WooCommerce原生`product_brand`，冻结扁平/最多一个/无品牌留空、角色、CSV、默认`/brand/`与第一版noindex合同，并在Shop/商品分类同一筛选aside接入品牌。数组Fatal、搜索污染、空品牌和ARIA问题已关闭；清理前正向19/19、四端/组合/零结果及SEO通过，清理后0品牌/0关系、旧URL404、商品Schema与缓存恢复；真实规模档位、>30控件/性能和非Local另验 |
| Day53 | [[Day53-已选条件计数与重置]] | 已完成（仅Local确认范围）：用户确认首版预计30个有效品牌；DentAll 0.29.0在Shop/商品分类统一输出价格、Size、Shade、Brand已选条件，支持逐项移除与保留分类/合法排序的Clear，并为Size/Shade/Brand补齐其他条件下的父商品动态计数。公开GET归一化、非法/非规范参数302、搜索隔离、30品牌完整文字列表、16场景、四端交互、冷最多3/暖0条计数查询和独立终审P0=P1=P2=0通过；无新运行文件/JS/AJAX/插件/自定义缓存/第二商品查询，>30和非Local另验 |
| Day54 | [[Day54-商品发现链路回归与W9收口]] | 已完成（W9 Local技术回归）：恢复态2商品/0品牌下，Shop、分类、搜索、排序、请求内两页分页、Price/Size/Shade、Chips/Clear、零结果、7类302、robots/Canonical/Sitemap、缓存及四端/断点交互通过；14张截图、最终恢复态和独立静态/Test/UX复核P0=P1=P2=0。运行代码0改动；3项P3、正式内容、真实设备、非Local与稳定Git基线继续待办 |
| Day55 | [[Day55-商品详情字段与PC骨架]] | 已完成（Local确认最小范围）：DentAll 0.30.0冻结经典单品字段/Hook责任并条件加载详情CSS；1200px起形成图库主列/摘要辅列。#44/#46、四端、1199/1200、Shop资源隔离、静态检查与独立复核通过；768堆叠留D59，信息、图库和购买区按D56～D59接续 |
| Day56 | [[Day56-商品图库与响应式图片]] | 已完成（Local确认最小范围）：DentAll 0.31.0复用Woo原生Gallery、FlexSlider、Zoom和PhotoSwipe，完成单图、多图、缺图方形画布、缩略图、44px灯箱入口及初始Gallery响应式图片提示。#44媒体夹具已精确恢复，六宽、#46、交互、资源隔离及独立复核通过；网络失败替换、移动精确圆点和Variation动态媒体优化未实施 |
| Day57 | [[Day57-商品基础信息与原生品牌输出]] | 已完成（Local确认最小范围）：DentAll 0.32.0复用Woo原生标题、评分、价格、摘要、库存和Meta，在既有详情CSS建立层级并关闭Sale顶线P3；移除Storefront重复品牌缩略图，保留Meta文字品牌和Schema。#44/#46、六宽、资源/数据/SEO不变量及专项复核通过；正向评分/品牌实页、D61动态媒体和非Local未验，D59顶层堆叠已后续完成 |
| Day58 | [[Day58-简单商品购买区与隔离购物车验证]] | 已完成并合入`main`：DentAll 0.33.0复用原生Simple POST、库存、cart和notice，只增加4个局部CSS规则与1个展示Filter；16项交易矩阵、五商品精确恢复、六宽、键盘、异常状态、Variable/Shop回归及三路终审通过，P0～P3=0。集成后数量2加购、notice、金额和清空链路再次通过；非Local、Variable加购、真实辅助技术和防重/AJAX未实施 |
| Day59 | [[Day59-商品详情四端购买区与Sticky收口]] | 已完成并纳入`main`：DentAll 0.34.0以一个平板媒体规则让768～1199px Gallery/Summary满宽堆叠，1200px恢复D55双列；同步修正Gallery `sizes`和缩略图上限，并以Storefront原生Theme Mod关闭Local Sticky。正常/多图/缺图/长文本共559/559、数据恢复和独立复核通过；D61动态Variation、正式内容、实体设备/辅助技术和非Local仍待 |
| Day60 | [[Day60-商品详情代表内容回归与W10收口]] | 已完成（W10 Local技术v1）：业务来源材料仅作隔离Local代表TEST，Simple/Variable初始、长文案、五图、缺图、售罄、无价格、一次Simple加购及D64/D65边界共30页、419/419；商品/session/附件/69张派生图/账号精确恢复，服务关闭。运行代码0改动；正式内容、D61和非Local仍待 |
| Day61 | [[Day61-原生变体选择与购买验证]] | 已完成（隔离Local技术v1；本轮集成）：DentAll 0.35.0复用Woo原生Variable选择、动态价格/库存/媒体与服务端交易，只补动态`sizes`、数量标签、5块CSS及按钮ARIA映射；4改+1个720字节JS，净+108行。9份报告581/581次断言、50张截图，独立交易/合同回归、8组新进程快照、17/17终态与服务关闭通过；自定义差分P0～P3=0，原生AJAX失败后旧按钮状态及可见价格/库存体验P2 `RSK-035`由D66复审。正式内容、真实辅助技术、CWV、Variation Gallery多图及非Local未验 |
| Day62（并行） | [[Day62-原生字段复核与零扩展收口]] | 已完成（授权的零扩展文档范围）：当前代表商品复用原生属性、Variations和描述；免费ACF 6.8.7保持停用，许可证未知项、PDF权限与CR-005另行处理。运行代码/字段/数据/部署0变更，D58～D61和D63不因此完成 |
| Day63（并行） | [[Day63-原生扩展信息与公开资料空状态]] | 已完成（当前Local技术收口）：复用Woo原生Description与Additional Information；#44/#46参数、源码空态、同运行代码四端/单一键盘路径、PDF拒绝、5MB和匿名公开直链边界通过。当前无合格PDF，因此入口0；运行代码/字段/部署0变更、数据库业务/配置显式写入0，本轮未做全表前后哈希，正式文件与撤回链未验 |
| Day64并行 | [[Day64-原生关联商品与推荐空状态]] | 已完成并合入`main`：Related/Upsells最多3项、1/2/3/3列、空态/角色/全量排除通过；净48行、无新运行文件，相邻间距P2关闭。集成后四端Grid、横向溢出与D65面包屑合成通过；当时的1440px Sticky局部遮图P3已由D59关闭Local配置；D60/D61其后另行完成，D63已另行完成，非Local仍待 |
| Day68候选 | [[Day68-手机与平板响应式购物车候选验证]] | 已完成批准范围内的独立Local技术验证，未标Day Done：继承D67重放`21f2941`，DentAll候选0.37.0以Mobile First基础层补Cart Block六宽、触控、长文本、异常与空态；最终198/198，Page ID 8英文候选验证后恢复中文，源Local只读且服务停止。`Add coupons` 20px触控P2转D70；D66三项P2、真实设备/辅助技术、非Local及集成仍待 |
| Day69（并行候选） | [[Day69-Header Cart与Mini Cart状态联动]] | DentAll 0.36.0候选在Block Cart页以公开Cart Store变化触发经典fragment，补同页数量/移除与Mini Cart；保留Header锚点、键盘/触控和BFCache。当前仅独立Local分支，Web Storage禁用及极端跨标签错序为期限性P2，D66三项P2、W12集成和非Local均未完成 |
| Day70 | [[Day70-优惠券规则与边界验证]] | 已完成（隔离Local原生范围）：三券型与TEST矩阵配置前/后105/105，权威浏览器17通过/1项P2、独立交易8/8，恢复12/12；原私有目录及23个同源回收站条目（含对应数据与元数据）精确销毁。运行代码/插件/权限/金额算法0改动；源`RSK-039`映射为集成`RSK-041`，后由W12六宽合成回归在Local关闭；D71/D75/D78边界不提前外推 |
| Day71候选 | [[Day71-运费税费与金额摘要候选验证]] | 已完成批准范围的独立Local技术验证：以`0ca4ba4`继承D67→D68，运行代码0变化；177/177主断言覆盖地区、TEST运费/税费、含税/未税、舍入、重量、库存、会话、安全和六宽金额。Cart内没有地址编辑表单，地区切换仅验证原生Store API合同；正式政策、D72集成、D73地址与D75物流另验 |
| Day72候选 | [[Day72-人工运费邮件报价与购物车收口]] | 已按CR-012实现邮箱人工运费报价：Cart邮件包含商品/SKU/规格/数量/小计和可选WhatsApp；三路服务端守卫阻断普通结账，Pending order的Shipping与`order-pay`保留。PHP 36/36、JS 23/23、隔离Local 18/18通过；正式邮箱、真实设备邮件客户端、支付/邮件及非Local仍待 |
