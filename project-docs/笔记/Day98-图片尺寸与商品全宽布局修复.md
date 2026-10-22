---
类型: 项目Day笔记与实施确认
项目: DentAll WooCommerce
日期: 2026-10-22
工作日: Day98
状态: 两项已授权修复通过隔离验证；未部署Staging，D98整体仍待目标环境与性能对比
---

# Day98 图片尺寸与商品全宽布局修复

## 结论与授权

用户2026-10-22明确回复“同意两项最小修复与隔离验证”：①仅前台商品详情让父主题`sidebar-1`在当前请求不活跃，由Storefront原生机制同时输出全宽class并省略侧栏；②修正首页分类图`sizes`。仅本独立工作树及隔离环境，不部署共享Staging、不写业务数据、不改购物车、不下单或发信。此前Staging27条临时缓存排除、关闭与恢复验证另见[[Day97-性能基线与资源缓存审计]]，不能混作本补丁已部署证据。

## 最小功能确认单

- 问题与角色：所有前台访客每次访问详情或首页时使用；数据仍来自原生Product、Product Categories与附件。本轮使用9个代表分类，数量仍按既有菜单输出；不新增字段或逐商品事实。Staging的PDP实际有博客侧栏，缺少图库CSS依赖的全宽class；768／1024px首图实宽约207.7／285.6px，而`sizes`声明704／960px。首页分类图实宽约62～86px，原提示上限324px。
- 第一版必须做：前台PDP单请求侧栏状态约束、首页分类渲染期间的局部`sizes`上限`6rem`。不清空或保存Widget配置，不改Blog、Shop、账户、Footer，不重写图库或变体，不新增图片资产、插件、模板、后台入口或缓存层。
- 方案比较：原生全局清空Sidebar会改变Blog；单PDP移除侧栏动作加body class要维护双轨规则；WordPress公开`is_active_sidebar`过滤器可让父主题统一决定两者。首页Woo原生分类img不经`wp_get_attachment_image_attributes`，采用既有分类函数try/finally内临时`wp_calculate_image_sizes`过滤器。第三方插件对此规模增加独立维护成本，无安装必要。
- 边界与维护：两项都属于主题展示，保留在既有`storefront-hooks.php`产品展示区和`homepage.php`分类渲染函数，不建微型模块；新增2个回调，预计约25～35行及必要中文注释。分类CSS上限变化时同步尺寸提示；PDP继续复用父主题和已有图库`sizes`合同。
- 影响与恢复：不新增数据库查询、远程调用、Cron、enqueue或持久化；不改变价格、库存、订单、支付、物流、邮件、角色、Slug、Canonical、Schema或robots。PDP不再输出博客Widget及其内部链接，是明确的展示变化。首次部署后须按批准范围失效HTML缓存并复验，不能只靠资源版本；本轮不执行共享部署。回退本补丁及主题版本即可，无数据库回滚。没有前后测量不承诺提速百分比。
- 排期：属于D98既有图片与展示修正，预计实现及定向验证1～3个专注周期，取决于隔离环境；不是无条件工期承诺，不扩展商业功能。

## 本轮最多三个验收结果

1. 有活动`sidebar-1`时，前台PDP仍由父主题得到全宽class且不输出侧栏；Blog／其他页面原Widget状态保持，后台与主查询前不受影响。
2. 首页真实分类图保留原生srcset／尺寸／链接，在分类渲染范围使用`6rem`提示，函数结束与异常后过滤器清理；缺图沿用原生回退，其他图片不受影响。
3. 隔离首页／Simple／Variable完成390／768／1024／1440px及适用状态；Blog等控制页做HTTP前后对照。保留实际证据与未验项，不以共享Staging旧版本截图代替补丁验证。

## 验证与状态

两项补丁已在本树实施，DentAll版本`0.49.2→0.49.3`。修改既有`inc/storefront-hooks.php`、`inc/homepage.php`、`style.css`三文件，新增1个具名过滤器回调和1个局部闭包；31行新增、1行删除，净增30行（含中文注释、空行与版本），没有新运行文件、CSS规则、JS、图片、插件或依赖。减法审查保留两个职责边界：PDP布局在既有详情Hook区，首页分类尺寸只在既有分类渲染的try/finally中生效；不另造模板、全宽class维护分支或图片框架。

隔离环境为新建`127.0.0.1:19798`／MySQL19799，WP7.1、Woo11.0.0、Storefront4.6.2、PHP8.2.29、MySQL8.4.0，仅启用Woo；全新数据库与TEST内容，没有复制共享wp-config、业务数据库、邮件或支付凭据。邮件、WP外部HTTP、Cron／异步任务均阻断，noindex。它与Staging的WP7.1.3／PHP8.2.35及额外插件、缓存组合不同，不能替代目标回归。先从HEAD导出三文件建立0.49.2基线，再精确替换0.49.3，源／运行副本SHA逐一相同。

| 实际验证 | 结果与边界 |
|---|---|
| 两个PHP文件`php -n -l`，`git diff --check` | 通过；没有用静态检查代替浏览器 |
| 独立9页原生HTTP前后对照 | 全部200／noindex；Home、Shop、Simple、Variable、Blog、Post、Page、Category、Search，CSS引用数不增加 |
| PDP与控制页面 | Simple／Variable原生`sidebarActive`从true变false、`#secondary`移除、出现`storefront-full-width-content`；Blog保持原有active=true但无侧栏输出，普通Page／Post仍有Widget。Widget option SHA前后相同，订单0→0 |
| 首页／Simple／Variable × 390／768／1024／1440 | 12条记录无页面横向溢出。首页8张有图分类保留srcset且sizes均6rem，第9项缺图占位、长分类名正常；PDP全宽及无侧栏四宽一致 |
| 原生变体Small／Large／重置 × 四宽 | 12条状态的图库sizes合同保持、无溢出；重置清空选择和variation_id、折叠结果区。未点击加购，不以disabled属性单独判断原生不可购买状态，不算交易验证 |
| 临时长商品标题＋无图＋售罄PDP × 四宽 | 4条记录占位图已加载、标题换行、Out of stock、本商品form.cart按钮0，无侧栏或页面横溢出；只用隔离WC CRUD样本 |
| 一次原生分类模板异常 | 真正命中模板throw；finally后图片回调表、既有Woo loop与外部图片sizes均恢复；测试Hook移除 |
| 独立审查 | Code Review未见P0～P3；资源／视觉专项复核通过。首次Simple768白图单独查明为原生0.25s图库淡入，computed opacity从0.003→1后稳定截图可见；原截图保留，没有为截图时机改代码 |

| 视口 | 修复后Gallery外宽 | 修复后主图实宽 | 布局 |
|---:|---:|---:|---|
| 390 | 335px | 333px | 图库→Summary单列 |
| 768 | 689px | 687px | 满宽堆叠；基线主图207.6875px |
| 1024 | 945px | 943px | 满宽堆叠 |
| 1440 | 709.90625px | 707.90625px | 图库／Summary双列 |

浏览器DPR1，默认滚动条占15px，名义视口与可用容器不同；sizes是候选提示，不要求与带边框实宽逐像素相等。分类实宽约62～86px，6rem为现有CSS的保守上限。测试中Hero和8分类共享同一源图，缓存可使currentSrc继续采用300／324px；没有冷缓存独立网络前后数据，不计算节省百分比、不乘8，也不宣称CWV改善。

机器摘要：`project-docs/tests/day98-layout-image-20261022.json`。详细HTTP／DOM／状态／截图与原生finally证据在本树忽略目录`.codex-tmp/fg-d98-20261022/evidence/`；关键截图为`browser-after-simple-768-stable.png`、`browser-after-simple-1440-final.png`及`browser-pdp-state-390.png`。

临时状态样本37已用WC CRUD精确删除、manifest恢复冻结基线；原2商品／2变体清理前后数据哈希及Widget配置不变，订单0、测试Hook残留0。仅本次PHP／MySQL已停，19798／19799监听0；逐一核对本树绝对路径与无重解析点后删除独立site、mysql-data和private，包含wp-config、MU守卫及凭据；保留证据、日志、无秘密配置及安全／夹具脚本，共享目录未动。浏览器临时Local页已关、视口已恢复。隔离初始化早期ABSPATH／翻译notice及清理工具首次image_id严格类型预检停止保留在历史日志；0.49.3前端验证阶段新增PHP日志0，不写成所有历史日志零错。

## 代表性实现与安全微调

HTML仍由Woo经典单品模板与原生分类模板输出。WordPress的`is_active_sidebar`公开过滤器只在前台PDP、主查询就绪后返回false；Storefront用同一状态决定全宽class和侧栏输出，配置本身不变。既有`product-detail.css`移动优先，768px起满宽堆叠，1200px起恢复图库56.521739%／Summary39.130434%双列；这次只修其生效条件。

分类图的CSS仍为`width:min(70%,6rem)`，PHP临时sizes提示与该上限一致。要微调时先在DevTools Elements选中body／Gallery／img，查看Computed宽度、opacity及Matched CSS；分别确认应改Token、组件CSS还是局部规则，再回源码修改。若分类CSS上限改变，应同步本函数的sizes，并回归四宽及非分类图；不能把DevTools临时值当源码。

无图沿用Woo占位；长标题／分类名依靠现有换行；售罄不输出本商品购买表单；加载态保留Woo图库淡入。错误路径本轮针对真实模板异常验证清理，不新增面向访客的错误系统。

## 专注周期与后续边界

| 周期 | 实际检查点 |
|---|---|
| C1 | 复现侧栏与sizes不一致，形成两项最小确认单 |
| C2 | 用户明确授权；读取规则与现有实现，独立Code Review |
| C3 | 公开Filter与既有finally内实施，两项补丁净30行 |
| C4 | 全新隔离站0.49.2基线与0.49.3原生对照 |
| C5 | 四宽正常、分类缺图／长名、动态变体及PDP状态样本 |
| C6 | 模板异常清理、稳定截图与独立资源复核 |
| C7 | 保存证据、清理临时运行环境、学习与项目记录收尾 |

周期是结果检查点，未记录实际工时。两项已授权隔离交付通过；D98整体、D97～D102及M8仍不标Done。待目标候选统一部署与批准的缓存失效后，只补真实Staging受影响页面、下载／性能对比及最终插件组合回归；本轮没有部署或再占用共享窗口。回退上述三文件即可回退补丁，无业务数据库迁移。URL、SEO字段、支付、物流、SMTP与缓存策略没有新增持久变化；首次发布的HTML变化需在部署窗口处理缓存。

## 相关笔记

- 前置事实与缓存窗口：[[Day97-性能基线与资源缓存审计]]。
- 原详情布局：[[Day59-商品详情四端购买区与Sticky收口]]。
- 回归入口：[[Day107-系统回归准备与执行包]]。
- 本日学习：[[WordPress实战笔记/Day98-布局状态与图片尺寸合同]]。
- 后续资源审计与目标接续：[[Day99-资源审计与缓存验证接续]]。

## 可复用核心思想

尺寸提示必须与实际布局身份一致，不能用更小的图片提示掩盖错误侧栏。WordPress当前实现优先让父主题通过同一个公开状态过滤器决定class和输出；这是“减少双轨规则”的跨平台原则。其他平台的具体扩展点本轮未验证。
