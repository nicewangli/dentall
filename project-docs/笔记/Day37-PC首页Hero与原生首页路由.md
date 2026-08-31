---
项目: DentAll WooCommerce
工作日: D37
计划检查点: D37（不自动等于一个完整实际工作日）
日期: 2026-08-28
实际有效工时: 未记录；不使用计划工时代填
验收层级: Local技术验收（通过）
状态: D37推荐最小范围已在Local完成；根URL与/blog/路由已验证
tags:
  - DentAll
  - Day37
  - Homepage
  - Hero
  - Storefront
---

# DentAll 每日复盘 D37：PC首页Hero与原生首页路由

## 相关笔记

- 每日笔记索引：[[README|DentAll每日笔记索引]]
- 前置项目笔记：[[Day36-手机与平板页脚和后台菜单绑定]]
- 当日学习笔记：[[WordPress实战笔记/Day37-Homepage-Hook与响应式Hero]]
- 设计系统依据：[[Day27-设计证据与Design-Token]]
- 后续项目笔记：[[Day38-手机与平板首页Hero精调]]
- M4整链路复核：[[Day42-首页全链路校准与M4技术验收]]

> [!success] 当前结论
> D37推荐最小范围已经完成。`Home`（ID 102）与`Blog`已在`设置 → 阅读`分别绑定为静态首页和文章页；站点根URL实际输出Homepage Hero，`/blog/`实际输出文章归档。当前3:2实色特色图仍只允许作为Local结构占位，它把背景烘焙在图片内，无法达到正式稿的无接缝效果；正式透明前景素材和手机/平板视觉精调不阻塞D37，分别在业务供图与D38处理。

## 用户授权

本次实施授权为：

> 同意按Day37推荐最小范围实施；Local使用原生Home/Blog＋Homepage模板，Hero使用核心区块＋特色图，并允许现有3:2占位图制作仅限Local的优化副本。

授权边界：

- 只实现Storefront `Homepage`模板内的PC Hero最小骨架，并保证较小视口先安全单列；D38再做手机和平板最终视觉精调。
- Home正文继续使用WordPress核心区块，Hero媒体继续使用页面特色图；不新增ACF、CPT、模板覆盖、JavaScript、插件、依赖或持久化字段。
- 只从已冻结3:2开发占位图制作同视觉的Local WebP；不调用GPT Image 2、不生成新页面或新文案素材、不修改`design-assets/final-versions/v1/`。
- Home/Blog、Reading设置、媒体附件和TEST区块由Administrator通过原生后台创建；开发代码不自动写数据库。
- 不改正式业务文案、导航层级、商品事实、Staging、Production、DNS、支付、物流或索引策略。
- 排期影响为0：D37本来就在W7总计划内。此前因误把“批量生成缺页设计稿与定向返修”扩入范围而提出的5～8个工作日估算已随该生成范围撤销，W7与M4不因此顺延。

## 今日三个验收结果

- [x] 子主题新增独立首页模块与条件样式：接管Storefront `homepage` Action、推进主循环、输出核心区块与响应式特色图，并卸载父主题已失去用途的旧Homepage布局脚本。
- [x] 冻结源图保持不变；已制作1536×1024、WebP q84、34,244字节的Local专用优化副本，源工作副本被Git忽略；Administrator将该副本上传为TEST特色图，WordPress据此生成响应式派生图。
- [x] `/home/`完成1440/1024/768/390px真实浏览器回归，且根URL与`/blog/`路由实际生效：单H1、列表正常换行、响应式图片派生选择正确且四端无横向溢出。

## 进度真实性检查

- 自然日期：2026-08-28。
- 实际有效工时：未记录；没有用计划工时或D日编号代填。
- 今天完成的计划检查点：D37 PC Hero与原生首页路由。
- 本日最高验收层级：Local技术验收通过。
- 可复演结果：登录Local后访问`/`可见Home Hero，访问`/blog/`可见文章归档；在390/768/1024/1440px可复核Hero安全布局。
- 尚未完成：正式透明Hero素材、D38手机/平板最终视觉精调、Staging/Production部署与正式业务内容验收。

## 专注周期记录

| 周期 | 计划 | 实际结果 | 证据 | 实际用时 |
|---|---|---|---|---|
| C1 | 盘点Homepage输出与后台能力 | 确认Storefront `homepage` Action、核心区块和特色图路径 | `inc/homepage.php`与后台页面设置 | 未记录 |
| C2 | 收敛字段和素材范围 | 冻结原生Home/Blog＋Homepage模板＋特色图，不新增字段或页面 | 本笔记“用户授权” | 未记录 |
| C3 | 实现PC Hero | 新增独立Homepage模块与条件CSS | `inc/homepage.php`、`homepage.css` | 未记录 |
| C4 | 准备Local占位媒体 | 生成同构图1536×1024 WebP优化副本，不改冻结源图 | `design-assets/runtime-local/day37/` | 未记录 |
| C5 | 修复布局偏差 | 关闭列表逐字竖排、图片反向撑高Hero和空媒体结构问题 | 浏览器DOM/CSS证据 | 未记录 |
| C6 | 四端与异常边界复核 | 390/768/1024/1440无横向溢出；代码级无图/无内容降级通过Review | 本笔记“当前验证证据” | 未记录 |
| C7 | 路由与独立Review收口 | 根URL、`/blog/`生效，独立Review无P0/P1 | 浏览器路由证据与Review结论 | 未记录 |

## 当前实现边界

- 新增`inc/homepage.php`，只负责Homepage Hook、条件资源加载和Hero输出。
- 新增`assets/css/homepage.css`，只负责Homepage模板容器复位、较小视口安全单列，以及1200px起“CSS整区底色＋右侧前景媒体层”的桌面Hero。
- `functions.php`只增加一个模块加载；`style.css`版本升至`0.15.3`，用于使本轮Hero修正立即刷新缓存键。
- 无特色图时不输出空`figure`并退化为单列；正文与特色图同时为空时不输出空Hero。
- 图片由`wp_get_attachment_image()`输出真实尺寸、`srcset`、`sizes`、`alt`、`loading=eager`和`fetchpriority=high`，不使用CSS背景图或原始URL拼接。
- Hero高度由内容与版式控制，不由上传图片比例决定；桌面媒体层脱离网格尺寸计算，图片始终`object-fit: contain`并右侧锚定，不拉伸、不用`cover`裁切关键商品。
- 编辑器意外留下的空段落不参与Grid间距；卖点列表使用原生marker，关闭了匿名Grid文本被挤进12px列而逐字竖排的问题。

## 当前验证证据

- 全部子主题PHP通过本机PHP语法检查，`homepage.php`无语法错误。
- `/home/`真实页面加载`homepage.css?ver=0.15.3`；Shop等非Homepage页面仍由条件加载边界隔离。
- `设置 → 阅读`已保存Homepage=`Home`、Posts page=`Blog`，未改变搜索引擎可见性等其他Reading选项。
- 根URL`/`实际标题为`Home - Dentall`并输出`Your One-Stop Partner for Dental Solutions`的Homepage Hero；`/blog/`实际标题为`Blog - Dentall`并输出文章条目`世界，您好！`，证明静态首页与文章归档路由均已接管。
- 1440px：Hero 1425×352px，媒体框768×352px，三项卖点均为173×52px，文档`scrollWidth`与`clientWidth`同为1425px。
- 1024/768/390px：均为安全单列且无横向溢出；390px下浏览器自动选择`416×277`派生图。1440px普通1×屏幕选择`768×512`派生图是`srcset`的正常结果，上传原件仍为1536×1024，并未被裁剪或替换。
- `homepage.css`共172行、3722字节、花括号28/28、`!important`为0、`object-fit: cover`为0；桌面媒体层仅保留1处职责明确的绝对定位，使图片不再反向撑高Hero。
- 独立Code Review与最终视觉Review均未发现P0/P1；发现的无图空`figure`、空内容和列表逐字竖排问题均已关闭。非阻塞P2是1024/768/390当前Hero仍偏高（留给D38精调），以及含`<br>`或`&nbsp;`的伪空段落不会被`:empty`隐藏。
- `php -l app/public/wp-content/themes/dentall/inc/homepage.php`与`git diff --check`通过。WordPress CLI使用系统PHP时无法连接LocalWP数据库，因此未把CLI Hook运行检查记为通过；真实页面浏览器证据作为本轮主要运行证据。

## Hero正式素材合同（候选，暂不生成素材）

- 第一选择是“CSS整区底色/渐变＋透明前景产品组合图”，不是把HTML标题、按钮或底色烘焙进一张Banner。
- 业务优先只交一张透明主图：建议1600×800，最低1400×700，sRGB、四周约5%透明安全区，主体采用右侧构图并为Hero左侧约45%的HTML内容保留安全区；发布首选带Alpha的WebP，保留高质量PNG/PSD源文件。
- WordPress负责从同一附件派生多种像素尺寸并输出`srcset`；四个验收端不等于业务要交四张图。只有390px下同一构图确实不可辨认时，才另行确认一张移动端精简构图及`<picture>`/独立字段范围。
- 构图合格但文件过大时，可以保留原件后做下采样、转WebP和移除无用元数据；带实色背景、内嵌文案/按钮、水印、主体已被裁断、授权不清或只能靠拉伸填满的素材必须退回。
- 当前1536×1024实色WebP只属于Local临时占位。其矩形边界来自源图背景，并非CSS裁剪；未经新授权不做抠图、生成或内容感知扩图。

## Codex Agent调度与审查

- 风险等级：中；涉及公共Homepage Hook、新运行CSS、响应式布局和首页路由，但不涉及交易或持久化自动写入。
- 专项Agent：Code Review、最终视觉复核、Hero素材合同复核。
- Review结果：P0/P1为0；D38保留较小视口Hero偏高的P2，伪空段落和实色素材边界已如实登记。
- 未启动安全/交易测试Agent：本次没有价格、库存、购物车、结账、订单、支付、权限或数据迁移变更。

## WordPress实战学习笔记收尾

- [x] 已生成[[WordPress实战笔记/Day37-Homepage-Hook与响应式Hero]]，使用学习模板核心骨架。
- [x] 已讲清Reading路由、Storefront Homepage Hook、主循环、响应式图片和CSS布局合同。
- [x] 已在[[WordPress实战笔记/WordPress实战笔记索引|WordPress实战笔记索引]]登记，并与D36学习笔记和本项目笔记显式互链。
- [x] 未记录密码、Cookie、私钥、支付密钥、客户数据或个人临时目录。
- 延期原因与补写节点：无。

## 后续衔接

1. D38只处理同一Hero DOM在390/768/1024px的最终间距、文字、按钮与媒体比例，不复制四套页面，也不顺手实现分类、Solutions或商品区。
2. 正式视觉阶段按上面的透明前景素材合同向业务收图；在新授权前不生成、不抠图、不修改冻结稿。
3. 当前TEST文案、3:2实色图片和Local路由证据不等于正式内容、素材授权或非Local部署验收。

## 可复用核心思想

### 跨平台不变量

- 内容字段、媒体字段与展示布局应分责：编辑器负责语义内容，媒体系统负责响应式图片，主题只负责可维护的布局合同。
- 缺图不应伪造空媒体语义；结构应自然降级，并把开发占位与正式授权素材明确隔离。

### WordPress/WooCommerce当前实现

- Storefront Homepage模板通过`homepage` Action组装页面；替换父主题回调时必须由新回调推进主循环，否则核心区块和全局Post状态都会失效。
- `wp_get_attachment_image()`比CSS背景图更适合内容型Hero，因为它保留附件替代文本、尺寸、`srcset`和浏览器资源选择能力。

### Shopify或其他平台

- 可迁移的是“可编辑语义内容＋独立媒体对象＋响应式展示组件”的分层；Shopify Section与媒体选择器的具体字段和发布机制本日未验证，标记为待验证。
