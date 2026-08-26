---
项目: DentAll WooCommerce
日期: 2026-08-25
工作日: D28
计划检查点: D28（不自动等于一个完整实际工作日）
周次: W5
计划工时: 6小时50分钟有效工作
实际有效工时: 未记录；不使用计划工时代填
验收层级: C1～C7已在Local完成；0.5.0四端/键盘历史证据已收口，0.5.2完成HTTP、定向计算样式、静态级联与独立Review
状态: 已完成
---

# DentAll 每日复盘 D28：基础控件与可访问状态

## 相关笔记

- 前置笔记：[[Day27-设计证据与Design-Token]]
- 后续笔记：[[Day29-三类卡片组件契约]]
- 当日WordPress实战学习笔记：[[WordPress实战笔记/Day28-基础控件状态与CSS级联]]
- 同主题笔记：[[WordPress实战笔记/Day27-Design-Token与Mobile-First容器|Day27-Design-Token与Mobile-First容器]]
- 当前事实入口：`project-docs/PROJECT_STATE.md`
- 代码规则入口：`project-docs/CODEX_WP_WC_RULES.md`
- 设计参考入口：`design-assets/README.md`

## 今日三个验收结果

- [x] 标题、正文文本链接、内容区操作按钮和常用表单控件使用现有Design Token形成一致且低权重的基础样式，覆盖适用的正常、Hover、Active、Focus、Disabled、Loading及字段错误状态。
- [x] Shop上下两处排序控件显示可见且与`select`程序化关联的英文`Sort by`标签，保留WooCommerce原生排序选项、GET参数、搜索与分页隐藏字段，不使用模板覆盖或JavaScript补标签。
- [x] 0.5.0在390、768、1024、1440px完成代表页面和控件状态回归；0.5.2维护性收口完成六页HTTP、Simple/Cart定向计算样式、静态级联与独立Review，最新Shop四端和键盘Tab未新增完整动态证据。最终P0/P1/P2/P3为0，且没有修改站点语言、内容或页面配置。

## 授权与范围

- 用户于2026-08-25明确回复：“确认按上述范围实施Day28，仅限Local，不部署Staging。”这覆盖本笔记记录的Day28最小实现、Local验证和必要项目文档更新。
- 用户随后明确回复“确认实施，开始C1吧”，授权先完成C1范围、状态、选择器边界及变更前基线冻结。
- 用户随后明确回复“开始c2吧”，授权在已冻结路线内补取商品控件基线，并实施Shop上下两处原生可见排序标签；没有扩大C2范围。
- 用户随后明确回复“开始C3吧”，授权按已冻结作用域实现正文区标题层级和普通文本链接基线；没有授权按钮、表单、Focus或页面组件提前实现。
- 用户随后明确要求“C4-C7一起帮我解决”，并要求整理笔记、提交Git和推送远程仓库；这授权在已冻结Day28范围内连续完成C4～C7、验证、文档和版本控制，不扩大为新页面、交易逻辑或部署。
- 用户在首次收口后明确回复“确认精简Day28 CSS”，授权在不改变既有功能、数据与技术路线的前提下压缩重复选择器、移除低收益装饰规则、同步笔记并重新验证；仍只保留一个运行时CSS，不新增构建链或依赖。
- 本次授权不覆盖Staging/Production部署、真实内容或配置修改、Header/Footer/卡片/页面组件提前实现、客户端表单校验、业务提交、插件/字体/依赖或WooCommerce模板覆盖。
- 如果后续发现必须改变上述技术路线、增加JavaScript、创建模板覆盖或修改数据库/配置，必须暂停并重新提交确认。

## 进度真实性检查

- 自然日期：2026-08-25。
- 实际有效工时证据：未记录；只按用户授权、文件差异和可复核证据判断进度。
- 当前最高完成层级：C7已完成。C4～C5公共控件规则已落地；0.5.0的C6完成四端、键盘、对比度和静态回归，0.5.2完成六页HTTP、定向计算样式、静态级联与独立Review。最新Shop四端与键盘Tab连接超时，作为版本限定的证据缺口保留。
- 当前运行版本：DentAll子主题0.5.2、WooCommerce 11.0.0、Storefront 4.6.2；Local活动主题仍为DentAll。
- C1～C7只修改现有`style.css`和`inc/storefront-hooks.php`及项目文档；没有修改模板、JavaScript、数据库、配置、商品、购物车、订单、账户、URL规则、SEO配置、缓存、支付、物流或部署状态。

## 专注周期记录

| 周期 | 计划 | 当前结果 | 证据/文件 | 用时 |
|---|---|---|---|---:|
| C1 | 冻结元素、状态、选择器和变更前基线 | 已完成；见本笔记C1章节 | 本笔记、当前子主题、WooCommerce模板、Local真实Home/Shop DOM、D27四端证据 | 未记录 |
| C2 | 用原生能力补齐Shop排序可见标签 | 已完成；两处标签语义与原排序查询通过定向Local回归 | `inc/storefront-hooks.php`、真实Shop与代表商品页 | 未记录 |
| C3 | 实现标题与正文链接基线 | 已完成；1440px代表页面定向回归与独立复核通过 | `style.css`、真实Home/Shop/Simple商品页 | 未记录 |
| C4 | 实现内容区按钮状态 | 已完成；Classic与Blocks按钮基线、Hover/Active/Loading/Disabled及44px目标通过 | `style.css`、Shop、Cart空态、Simple/Variable商品 | 未记录 |
| C5 | 实现表单、字段错误与Focus | 已完成；常用控件、Readonly/Disabled/Error与分区Focus基线通过 | `style.css`、排序/数量/Variation控件、键盘Focus夹具 | 未记录 |
| C6 | 静态、键盘、对比度和四端测试 | 已完成；代表页面四端无横向溢出，真实键盘Focus与颜色对比通过 | 390/768/1024/1440、计算样式、日志与静态检查 | 未记录 |
| C7 | 独立Review、复测和收口 | 已完成；Review/Test发现项均修复，最终P0/P1/P2/P3为0 | Code Review、测试/可访问性与定向复测 | 未记录 |

## C1已冻结：业务目标与边界

Day28解决的是“后续组件可复用的基础元素规则”，不是提前完成登录、商品、购物车或结账页面。基础样式必须在WordPress/WooCommerce真实HTML上可用，但不能把截图中的字段、交互或业务状态当成已实现能力。

- 使用者：所有前台访客间接受益；开发者维护样式和展示Hook；Website Manager不编辑代码。
- 使用频率：每次相关前台页面渲染；不增加后台操作步骤。
- 数据来源与数据量：只使用现有HTML、WooCommerce查询参数和Design Token；不新增持久化字段，数据库新增量为0。
- 代码位置候选：CSS继续放现有子主题`style.css`；排序展示Hook继续放现有`inc/storefront-hooks.php`；不创建新运行文件。
- 作用环境：仅Local实施和验证；Staging/Production保持不变。

## C1已冻结：元素与状态矩阵

| 元素 | D28必须覆盖 | D28明确不做 |
|---|---|---|
| 标题 | 正文区`h1`～`h6`的颜色、字重、字号、行高及长英文换行；H1/H2允许Mobile First渐进字号 | 不决定商品卡标题最终尺寸，不改Header站点标题，不硬编码每个页面标题 |
| 文本链接 | 正常、Hover、Active、Focus；正文内链接保持非颜色唯一识别 | 不实现导航、卡片、图标链接或分页的最终组件视觉；不按访问历史改变品牌色 |
| 操作按钮 | 正常、Hover、Active、Focus、Disabled、`aria-disabled`和已有`.loading`展示；最小44px触控高度 | 不新增加载脚本、重复点击锁、AJAX行为、图标或业务动作 |
| 文本控件 | text、email、tel、url、password、search、number、`select`和`textarea`的正常、Hover、Focus、Disabled、Readonly、Placeholder和已有错误状态 | 不新增字段、不提交表单、不实现客户端/服务端校验，不伪造成功状态 |
| checkbox/radio | 保留原生语义，设置可识别的选中强调、Focus和Disabled状态；关联标签共同形成可用目标 | 不自绘复杂开关、不改变WooCommerce字段名或勾选逻辑 |
| Focus | 键盘焦点必须清晰，浅色表面使用深操作蓝，深色表面使用白色；不以阴影或颜色变化替代outline | 不移除浏览器/Storefront回退Focus，不提前重构菜单、抽屉或弹层焦点管理 |
| 字段错误 | 只识别已有`aria-invalid="true"`和WooCommerce无效字段类，边框与文本均可识别 | 不建立D30通知系统，不把浏览器原生`:invalid`提前显示成业务错误，不新增错误文案 |

### 标题层级候选

- H1：手机32px，宽屏最多48px；700字重、1.2行高。
- H2：手机24px，宽屏最多32px；700字重、1.2行高。
- H3：24px、600字重；H4：18px、600字重；H5：16px、600字重；H6：14px、600字重。
- H1/H2使用`clamp()`在现有Token上下限之间渐进变化；不新增只为单张截图服务的随机断点。
- WooCommerce商品卡等更具体类允许以更高的组件规则覆盖基础字号，D29再冻结卡片契约。

### 控件视觉候选

- 主操作按钮复用`--dentall-color-action`与`--dentall-color-action-hover`，白字对比分别为5.54:1和6.60:1；使用现有中圆角和间距Token。
- 输入控件使用白色表面和强边界，装饰边框`#e6ebf2`不得成为唯一控件边界。
- Placeholder复用通用次文本色；`textarea`允许纵向调整，不使用固定高度承载未知内容。
- C1允许C5在现有`:root`中补充一个字段错误原始色和一个语义别名，候选`#b42318`在白、sky、blue和mint四种浅底的对比度为5.87:1～6.57:1；成功/警告/通知色仍留D30。

## C1已冻结：选择器与级联边界

1. 标题、链接、按钮和表单视觉以`.site-main`为主要作用域，避免D28提前改变Header搜索、菜单、购物车入口和Footer布局。
2. Focus以站点前台`.site`为范围，覆盖可聚焦链接、按钮、输入、`select`、`textarea`、`summary`及真实`tabindex`；当前深色Header只允许最小白色Focus例外，不改其布局和配色。
3. 优先使用`:where()`降低作用域辅助选择器权重，让WooCommerce商品卡、块组件和后续DentAll组件能够在最近位置安全覆盖。
4. 不使用ID选择器、深层DOM链、`!important`、行内样式、行内脚本或行内事件。
5. Classic WooCommerce与当前页面实际出现的WooCommerce Blocks按钮类均纳入审计；只为已存在或明确后续使用的类写规则，不预造通用框架。
6. 文本控件默认只建立盒模型、边界和状态；只有WooCommerce`.form-row`等明确字段容器内才使用全宽。Shop排序`select`继续自适应内容，不被全局`width:100%`拉满。
7. 数量输入、Variation选择和购买按钮属于受影响回归面，但D28不改变价格、库存、合法组合或加购逻辑。

## C1已冻结：Shop排序标签路线

- Storefront当前分别在`woocommerce_before_shop_loop`与`woocommerce_after_shop_loop`优先级10输出`woocommerce_catalog_ordering()`，因此真实Shop有上下两份排序表单。
- WooCommerce 11.0.0的`woocommerce_catalog_ordering( $attributes )`和`loop/orderby.php`原生支持`useLabel`/`use_label`。启用后模板会输出可见、可翻译的`Sort by`标签、唯一`id`和对应`for`，无需复制模板。
- C2推荐在现有`inc/storefront-hooks.php`中等待父主题Hook注册完成后，移除两处原回调并在相同优先级加入DentAll包装函数；包装函数只调用`woocommerce_catalog_ordering( array( 'useLabel' => true ) )`。
- WooCommerce未启用或函数不存在时必须安全不执行；不得产生Fatal Error。
- 标签文案冻结为WooCommerce原生英文`Sort by`，手机、平板和PC一致，不通过CSS制造`Sort:`等第二份文案。
- 必须保留排序选项、`orderby`、`paged=1`、搜索/筛选查询字段及自动提交行为；C2不是重写排序查询。
- 不创建`woocommerce/loop/orderby.php`模板覆盖，不使用JavaScript或伪元素生成可访问标签。

## C1变更前基线证据

### 文件与版本

| 文件 | 变更前事实 |
|---|---|
| `themes/dentall/style.css` | 0.3.2；4413字节；129行；SHA-256 `3FBFBBFA50704F8B060AAF381F9560BC0615F5A366E11C31B5BE51D3AFE82EF7` |
| `themes/dentall/inc/storefront-hooks.php` | 789字节；26行；SHA-256 `2344863E561B9014EA0BB898576A7C8B6B2E9E58DAC9FC26CB29D3C22EABF78A` |
| WooCommerce `templates/loop/orderby.php` | 模板版本9.7.0；SHA-256 `0B7F24EB18477007C264275FC742E7430245B6C8CFB9704A07F857D9FBA04686`；只读，禁止修改 |

- C1开始只读检查时`style.css`显示为已暂存修改；C1期间工作区外部新增`a0e2520`、`407cbb0`、`23b11b5`三个提交，结束复查时`style.css`与`storefront-hooks.php`均已和当前HEAD一致，内容与上述哈希未变。当前主Agent没有创建这些提交，也没有覆盖或回退它们。
- 工作区仍有D25～D28及其他用户差异和未跟踪草稿文件。D28必须叠加到当前HEAD的0.3.2，不能重置、覆盖或顺手处理无关导入草稿；C1没有执行Git暂存、提交、切分历史或清理未跟踪文件。

### Local真实DOM与计算样式

- 登录态Chrome真实Home确认`html lang="en-US"`，正文仍显示默认中文文章“世界，您好！”及中文日期/分类；这属于英语前台清单，不是D28样式缺陷。
- 登录态Chrome真实Shop确认只加载一次`style.css?ver=0.3.2`；页面存在1个H1、2个商品H2、2个商品按钮和上下2份排序表单，重复ID为0。
- Shop H1当前约41.89px、300字重、`#333333`；商品H2为16px、400字重。D28标题基线必须避免把商品卡H2直接放大为页面H2。
- 商品主链接当前为Storefront紫色`#7f54b3`；Add to cart与Select options按钮为浅灰`#eeeeee`背景、深灰文字、14px、无圆角、无最小高度声明。
- 两个排序`select`当前均为14px、白底、1px `#767676`边框、0圆角、0内边距且无最小高度；每个只有`aria-label="Shop order"`，没有可见`label`和`id`。
- 当前1920px CSS视口下Chrome内容布局宽为1905px，`scrollWidth === clientWidth`；C1没有发现横向溢出或重复ID。
- D27已完成Home、Shop、Cart、My Account四页×390/768/1024/1440的真实DOM、截图、溢出和日志基线，D28复用该证据判断变更前布局，不把相同证据冒充变更后回归。
- C1时Chrome批量视口与商品页补采连接超时，未产生站点写入；C2开始后已在编码前分别补取Simple/Variable数量、Variation与加购控件基线，证据见下一节。C1证据仍只代表变更前状态，不冒充C2或C6回归。

## C2实际实施与定向验收

### 商品控件实施前基线

- Simple商品`TEST D12 Simple Fixed Pack`的原生POST购买表单存在；数量框为`type="number"`、值1、可见且与`TEST D12 Simple Fixed Pack quantity`标签关联，`aria-label="Product quantity"`；`Add to cart`按钮可购买。
- Variable商品`TEST D12 Variable Size Shade`的原生POST购买表单包含3个Variation。`pa_size`与`pa_shade`分别关联可见`Size`和`Shade`标签；未选择规格时数量框保持可见，购买按钮保留WooCommerce原生`disabled wc-variation-selection-needed`状态类。
- C2没有改动上述数量、Variation、价格、库存、合法组合或加购逻辑；它们只是排序标签改动的受影响回归基线。

### 最小实现

- 仅修改`app/public/wp-content/themes/dentall/inc/storefront-hooks.php`，新增37行；文件现为1876字节、63行，SHA-256为`9D4F3CF115AAC42709C90071C5153438BC9FB24C81564F66956148CC2B0F4FD5`。
- `dentall_catalog_ordering_with_label()`只调用`woocommerce_catalog_ordering( array( 'useLabel' => true ) )`，文案、唯一ID、查询字段与模板语义继续由WooCommerce维护。
- `dentall_enable_catalog_ordering_labels()`在`after_setup_theme`优先级30执行，等待父主题注册完回调后，将上下两处Storefront排序输出替换为DentAll包装函数，前后位置仍保持原优先级10。
- 两个函数均在WooCommerce函数不存在时安全返回；没有创建模板覆盖、JavaScript、CSS、插件、依赖、后台入口或持久化行为。`style.css`仍为0.3.2，SHA-256仍为`3FBFBBFA50704F8B060AAF381F9560BC0615F5A366E11C31B5BE51D3AFE82EF7`。

### 定向Local验收证据

- 默认Shop仍有上下2个`GET`排序表单。两处均显示可见英文`Sort by`；对应ID分别为`woocommerce-orderby-1`与`woocommerce-orderby-2`，`label[for]`与`select[id]`逐一相等，`select.labels`均返回同一标签，页面重复ID为0。
- 两处`select`名称仍为`orderby`，隐藏字段仍有`paged=1`；排序值集合仍为`menu_order`、`popularity`、`rating`、`date`、`price`、`price-desc`。启用原生`useLabel`后显示文案采用WooCommerce短标签，不在DentAll代码中硬编码。
- 直接访问`/shop/?orderby=price-desc`时，两处选择值都为`price-desc`，结果顺序为Variable商品后Simple商品，结果说明仍显示按价格从高到低；原生查询行为保持。
- 1920px真实视口下两处标签可见、关联正确且无重复ID，浏览器页面`warn/error`日志为0。Storefront与子主题源码审计未发现针对排序标签的隐藏规则；390/768/1024/1440完整视觉与键盘回归仍属于C6，C2不提前宣称关闭第三项日验收。
- PHP 8.2.9 NTS执行`php -l`通过；`git diff --check -- app/public/wp-content/themes/dentall/inc/storefront-hooks.php`通过，仅提示现有Windows工作区下次Git处理时的LF/CRLF转换；确认不存在`woocommerce/loop/orderby.php`子主题覆盖，也没有CSS或插件差异由C2产生。
- C2公共前台Hook触发独立只读Code Review；父子主题加载时序、两处Hook替换、WooCommerce 11 `useLabel`、WooCommerce函数缺失保护及唯一ID逻辑均通过，P0/P1/P2/P3均为0。实际停用WooCommerce、四端、键盘、归档变体、PHP日志和缓存命中页面继续留C6验证。

## C3实际实施与定向验收

### 最小实现

- 仅修改`app/public/wp-content/themes/dentall/style.css`并将子主题版本从0.3.2提升为0.4.0。文件现为6725字节、211行，SHA-256为`4D1BA28797C75F0D8FE8F32714DC939CFDEC4B067F33F9A969484D4465C8712D`。
- 标题规则限定在`.site-main`内，以低权重`:where()`覆盖H1～H6的标题色、行高、字重和长文本换行；H1/H2使用现有字号Token和`clamp()`渐进变化，H3～H6使用固定Token。没有改Header/Footer，也没有建立D29商品卡最终字号。
- 普通文本链接只覆盖`.entry-content`、术语/页面描述、商品短描述和`.product_meta`中的无类名、无ID链接，保留下划线并提供正常、Hover与Active颜色；按钮、卡片链接、导航、分页和带独立组件标识的链接继续由组件规则维护。Focus统一基线仍按计划留C5。
- 初测发现Storefront的`h1 a`～`h6 a`会使正文标题中的直接子链接继续显示父主题300字重和紫色，记为P2；现已增加标题直接子链接继承规则。该规则不匹配Classic商品卡的`a > h2`结构，也不进入Header/Footer。

### 定向Local验收证据

- 真实Home在1440px下，文章标题容器与可见直接子链接均采用正文标题层级；Shop H1为48px/700字重/标题深蓝，商品卡H2仍为16px/400字重；Header链接、排序控件和商品按钮没有被C3改写。
- 真实Simple商品页H1为48px/700字重，商品区段H2保留WooCommerce已有约25.89px组件字号但继承700字重与标题色；Related商品卡仍为16px/400字重。`.product_meta`普通分类链接采用操作蓝和下划线，评分、编辑与购买按钮不匹配正文文本链接规则。
- 1440px代表页面未见横向溢出，最终页面`warn/error`日志为0。白底下标题、正文链接正常、Hover和Active候选色对比度分别为17.32:1、5.54:1、6.60:1和15.07:1；H1/H2的390/768/1024/1440计算候选分别为32/24、39.36/27.68、44.48/30.24、48/32px。
- 静态检查确认CSS花括号16/16、`!important`为0、行内`style=`为0；`git diff --check`通过，仅提示Windows工作区LF/CRLF转换。没有新增WooCommerce模板覆盖。
- 独立测试Agent发现上述标题直接子链接P2并在修复后定向复测：Home实际1440px下标题容器与链接同为32px/700字重/`#031a3a`，`scrollWidth === clientWidth === 1440`；Shop修复后排除项复测实际为1920px，商品卡仍为16px/400字重、按钮仍为原灰底且页面日志为0。独立Code Review确认P2已关闭，最终P0/P1/P2/P3均为0。Shop修复后1440px再确认、390/768/1024真实视口、长标题、Variable商品、真实H3～H6样本、Hover/Active交互、WooCommerce Blocks及C5合入后的键盘Focus仍留C6统一验证，不冒充完整四端验收。

## C4～C5实际实施

### 内容区按钮与WooCommerce Blocks兼容

- `style.css`将内容区原生按钮、按钮型`input`、Classic WooCommerce`.button`/`.added_to_cart`、Block按钮及`wp-block-button__link`纳入同一低权重基线：最小高度44px、现有中圆角、操作蓝背景、白字及1px实线边框。
- Normal、Hover、Active、原生`disabled`、`aria-disabled="true"`和`.disabled`只改变必要的颜色、透明度与光标；`.loading`直接沿用WooCommerce/Storefront已有透明度反馈，不再维护第二套装饰规则。CSS不创建加载行为、不拦截点击，也不改变WooCommerce可购买判断。
- Cart空态的推荐商品按钮同时命中Storefront更具体的`.hentry .entry-content .wp-block-button .wp-block-button__link`。为覆盖父主题灰底、零圆角和`border: 0`，只增加一条等作用域兼容规则；最终实际计算值为操作蓝、白字、44px、10px圆角和1px solid边框，没有使用`!important`。

### 表单、错误与Focus

- text、email、tel、url、password、search、number、`.input-text`、`select`和`textarea`统一最小44px、白底、强边界与6px圆角；字段清单使用受控`:is()`达到覆盖Storefront输入框所需的权重，只在`.form-row`字段容器内全宽，Shop排序`select`继续保持约147×44px自适应内容。
- `textarea`保留纵向调整；placeholder使用次文本色；disabled与readonly可识别；字段Hover沿用浏览器/Storefront原生反馈，不再为轻微边框变化单独维护一整组选择器；checkbox/radio保留原生语义并使用20px目标与操作蓝`accent-color`。
- 错误色新增原始Token`--dentall-red-700: #b42318`及语义别名`--dentall-color-error`。只响应已有`aria-invalid="true"`和WooCommerce`.woocommerce-invalid`，同时改变边框与关联标签/required文字，不新增校验或错误文案。
- Focus按真实视觉表面分区：`.site-content`和普通`.site-footer`使用深操作蓝，深色`.site-header`使用白色；Storefront手机固定底栏虽位于Footer DOM内但沿用Header深色背景，因此增加白色Focus例外。各区均使用3px实线outline与3px offset，并保留Storefront普通`:focus`作为不支持`:focus-visible`时的回退。
- C7首次收口时`style.css`为0.5.0、14604字节、547行，SHA-256为`1839259F241C9FD46F97846EE9C87CA8B225E97223AB9D9F02C985B874853721`；花括号39/39，`!important`与行内`style=`均为0。用户确认后的维护性精简见下方0.5.2章节。

## C6四端、键盘与状态验证（0.5.0首次收口证据）

### 代表页面矩阵

| 页面 | 390 | 768 | 1024 | 1440 | 适用状态与边界 |
|---|---:|---:|---:|---:|---|
| Home | 通过 | 通过 | 通过 | 通过 | 标题渐进字号、正文链接、无横向溢出 |
| Shop | 通过 | 通过 | 通过 | 通过 | 上下两处`Sort by`、商品卡排除项、44px按钮与排序控件 |
| Cart空态 | 通过 | 工具单页超时，未取得独立快照 | 通过 | 通过 | Block推荐按钮；390/1024/1440计算样式一致，未制造非空购物车 |
| Simple商品 | 通过 | 通过 | 通过 | 通过 | 数量框与Add to cart均44px；没有提交或加购 |
| Variable商品 | 通过 | 通过 | 通过 | 通过 | Size/Shade、数量与购买按钮均44px；保持未选择Variation状态 |
| My Account | 通过 | 通过 | 通过 | 通过 | 当前登录态Dashboard无溢出；页面没有表单，因此不外推登录/注册状态 |

- 已完成的23组页面宽度检查均加载`style.css?ver=0.5.0`，`scrollWidth === clientWidth`，重复ID为0，站点页面`warn/error`为0。Cart 768单页是浏览器自动化连接超时，不是页面失败；同一Block按钮已在390/1024/1440验证，768又由Home、Shop、两类商品和Account覆盖公共断点，因此记录为孤立证据缺口而非伪造“已测”。
- Home H2在390/768/1024/1440分别为24/27.68/30.24/32px；Shop H1分别为32/39.36/44.48/48px，商品卡H2始终16px/400，说明基础标题没有破坏D29卡片边界。
- Simple数量和购买按钮四端均为44px；Variable的Size、Shade、数量和购买按钮四端均为44px。当前Variable按钮带WooCommerce`disabled wc-variation-selection-needed`类，但DOM没有原生`disabled`或`aria-disabled`属性，这是现有WooCommerce状态事实，D28没有改写业务行为。
- 当前页面没有自然出现readonly、`aria-invalid`、加载中、Account表单和非空Cart。C6使用选择器、状态优先级和受控夹具验证展示规则，没有通过提交表单、制造错误、改购物车或写数据库来伪造业务证据；这些真实状态在相应页面工作日继续验收。

### 键盘Focus、对比度与静态证据

- 登录态Home的Header搜索框实际获得键盘焦点时，初测发现Storefront紫色仍覆盖预期白色，随后将Focus选择器按`.site-header`、`.site-content`和`.site-footer`真实结构提高到足以覆盖父主题的清晰作用域，并用完整`outline`而非只改颜色。
- 加载真实Storefront与DentAll 0.5.0样式的390px浏览器夹具通过真实`Tab`顺序验证：Header链接/搜索框及手机固定底栏为白色`rgb(255, 255, 255)`，正文链接/输入与普通Footer链接为深蓝`rgb(7, 86, 201)`；全部为3px solid、3px offset。内置浏览器连接的遥测请求曾超时，因此没有把连接错误写成站点失败或要求用户代测。
- 白字与按钮Normal/Hover/Active的对比度分别为5.54:1、6.60:1和15.07:1；错误红在白、sky、blue、mint浅底为5.87:1～6.57:1，均不依赖低对比装饰色单独传达状态。
- PHP 8.2.9执行`php -l inc/storefront-hooks.php`通过；CSS花括号、禁用`!important`、行内样式、模板覆盖和`git diff --check`均通过。公开页面响应200并引用`style.css?ver=0.5.0`。

## C7独立Review、修复与复测

- 独立测试/Review先后发现并关闭：Disabled按钮在Hover时被重新染色P2、错误字段在Hover时丢失红边P2、Cart Block按钮被Storefront高权重规则恢复为灰底/零圆角P2、Block按钮只改`border-color`但父主题仍为`border-style:none`的P3，以及手机固定底栏错误继承浅底深蓝Focus的P1。
- 两个状态冲突通过把完整Hover条件放进`:where()`降低权重，使Disabled与Error规则稳定获胜；Blocks兼容规则使用真实Storefront作用域和完整1px border；手机固定底栏以深色表面白色Focus例外关闭。
- 最终独立Code Review与测试复核结论为P0=0、P1=0、P2=0、P3=0。Storefront自身Disabled规则可能将目标透明度0.55计算为0.5，但状态仍清晰且项目没有为小数差异引入`!important`。
- 回滚只需恢复`style.css`、`inc/storefront-hooks.php`及主题版本；没有数据库迁移、配置切换或数据恢复步骤。

## 用户确认后的Day28 CSS维护性收口（0.5.2）

- 用户担心Day28为微小效果引入过多重复代码，并明确确认精简。最终继续只维护Storefront自动加载的同一个`style.css`；没有拆分运行文件、增加CSS请求、引入PostCSS或新增依赖。
- 按钮的Normal、Hover、Active和Disabled仍各只有一份声明；Storefront高权重Block按钮作为同组的独立逗号分支，既复用声明，也不把通用按钮整体抬到Block权重。自定义Loading装饰改为复用父主题原生反馈。
- Focus目标清单从四处合并为一处：`.site`默认提供浅色表面深蓝变量，Header与手机固定底栏只覆盖继承变量为白色。字段Hover改用平台原生表现，Readonly保留浅底/次文本色的最小可见区别。
- 没有采用提案中的`@custom-selector`：当前项目没有CSS预处理构建链，直接提交尚未稳定的扩展语法会把可读性成本转成浏览器兼容风险。标准`:is()`只用于可控的字段/按钮清单；高权重Block分支继续单列，避免`:is()`按最高参数计算特异性时连带抬高普通按钮。
- `style.css`从547行/14604字节/39个花括号块精简为443行/13168字节/33个花括号块（33/33配对），净少104行和1436字节；没有`@custom-selector`、强制重要性声明或规则内重复属性。最终SHA-256为`C32B7EEA5D6B20FC5A2BA02547470DCCF8CB594EB7B57D313BC4C9B569F30A7D`。
- 本地模拟gzip从3753字节变为3943字节，差异约190字节；重复文本本来就很容易压缩，因此本次目标是降低阅读和修改成本，不宣称网络性能提升。维持单个CSS请求也避免了为13KB级文件增加额外阻塞请求。
- 最终HTTP复测确认Home、Shop、Cart、My Account、Simple与Variable均为200，并各只加载一次`style.css?ver=0.5.2`；运行时CSS与磁盘文件字节数和SHA-256完全一致。真实Simple数量框为白底、1px边框、6px圆角和44px高，`.button.alt`仍为蓝底白字、44px高；Cart空态Block按钮在390px仍为蓝底白字、1px边框、10px圆角和44px高。
- 最终独立Review与测试对按钮、字段、Readonly、Error、Focus继承和Storefront级联复核后均为P0/P1/P2/P3=0。0.5.2页面没有自然出现Readonly、Error和Loading业务状态，最新Shop四端与键盘Tab自动复测又遇到浏览器连接超时；这些状态没有被伪报为最新版本的完整动态回归或真实业务流程通过，仍按下方节点复测。

## 第一版英语前台检查清单（D28建立，按页面日关闭）

| 页面/来源 | 当前证据 | 责任与计划 |
|---|---|---|
| 全站HTML语言 | `lang="en-US"`已存在 | 开发者保持技术输出与可翻译字符串一致；D28不改站点语言 |
| Home | 默认中文文章标题、正文、日期与分类可见 | Website Manager/内容提供正式英语内容；D37首页实现前关闭 |
| Shop | H1/Breadcrumb仍出现“商店”；商品TEST标题为英语 | 开发者区分WooCommerce翻译与内容来源；D43归档实现前关闭 |
| Cart | D27证据中空态/推荐区存在中文或中英混排 | D68响应式购物车与空态实现前关闭 |
| My Account | D27登录态Dashboard存在中文或中英混排 | D81账户首页实现前关闭；登录/注册另在D79 |
| 商品与正式内容 | 当前仅TEST英文商品，不能替代正式文案和授权 | Website Manager/业务方按录入与发布流程负责，未确认内容保持Draft/TEST |

- D28只建立清单和技术责任边界，不修改页面标题、文章、商品、翻译包、站点语言、菜单或数据库。
- 排序标签使用WooCommerce原生可翻译字符串`Sort by`，不在CSS或PHP中散落硬编码英语。

## Agent与风险判断

- 当前C1风险：低风险，只读盘点与文档冻结，由主Agent处理；没有可供独立Code Review的运行代码差异，因此不启动专项Agent。
- D28进入C2～C5后升级为中风险：涉及公共前台控件、展示Hook、多个WooCommerce页面和四端回归。C2已因公共展示Hook启动独立只读Code Review；C3启动独立Code Review与测试Agent，测试发现的标题直链P2已修复并复核关闭，当前P0/P1/P2/P3均为0。
- C6～C7已启动独立Code Review与测试/可访问性Agent。Review发现的状态优先级、Blocks边框和手机固定底栏Focus问题均由主Agent最小修复，再由原Review/Test路径复核关闭；最终P0/P1/P2/P3均为0。
- D28没有进入设计稿逐像素还原或新组件探索，因此没有启动设计还原Agent。
- 不涉及后台保存、权限、nonce、REST/AJAX、客户数据、价格、库存或订单，因此当前不触发安全Agent。若实现范围扩大到表单提交或校验逻辑，必须重新分级。

## 数据、URL与系统影响

- C1～C7实际数据影响：无。没有创建、编辑、发布、删除或迁移商品、文章、Page、媒体、订单、用户或配置。
- C2实际URL/SEO影响：排序表单继续使用原生GET参数并通过`price-desc`回归；没有改变Slug、固定链接、Title、Meta、Canonical、Schema、robots、Sitemap、状态码、内部链接或索引保护。HTML只新增WooCommerce原生可见`label`与唯一`id`关联。
- C2实际性能/缓存影响：每个Shop列表页仍输出原有两处排序控件，只增加两个轻量PHP包装回调；没有新增查询、HTTP请求、远程调用、Cron、自动加载选项、前端资源或缓存清理。未做前后性能量测，因此不宣称性能“零影响”。
- C3实际URL/SEO影响：只改变现有可见标题和普通文本链接的CSS表现，没有改变HTML内容、链接目标、Slug、固定链接、Title、Meta、Canonical、Schema、robots、Sitemap、状态码或索引保护。
- C3实际性能/缓存影响：继续复用原有单个子主题样式请求，仅随主题版本0.4.0更新缓存键；没有新增请求、查询、远程调用、Cron、自动加载选项或缓存清理。未做前后性能量测，不宣称性能“零影响”。
- C4～C7及维护性收口的实际性能/缓存影响：继续只有一个子主题CSS请求；最终`style.css`为13168字节，主题版本提升为0.5.2以更新缓存键。没有新增脚本、图片、字体、查询、远程调用、Cron、自动加载选项或缓存清理；gzip模拟量只用于解释拆分取舍，不等于真实Core Web Vitals量测，因此不宣称性能“零影响”或提升。
- C4～C7实际URL/SEO影响：只改变可见控件与Focus的CSS表现；没有隐藏可索引内容，也没有改变链接目标、Slug、固定链接、Title、Meta、Canonical、Schema、robots、Sitemap或状态码。
- C1～C7实际支付/物流影响：无。没有改变价格、库存、购物车、结账、支付、税费、运费或单位，也没有执行加购或下单。
- C1～C7实际部署影响：无。只改Local工作区；Staging/Production、DNS和正式支付均保持不变。远程Git推送只交付源码与文档，不等于部署。

## 当前风险与下一步

- `#f6a700`继续只作评分装饰，`#e6ebf2`继续只作装饰边界；D28不得让它们单独承担控件或状态识别。
- 当前代表页面回归已覆盖商品数量、Variation、Cart空态、Account登录态和WooCommerce Blocks；Checkout、Account登录/注册、非空Cart、真实错误、Readonly与Loading业务状态尚未自然出现，继续在对应页面日以真实流程验证，不能由D28外推为交易全流程通过。
- Cart 768的单页独立浏览器快照因自动化连接超时未取得；同一Block按钮已在390/1024/1440通过，768公共断点由其余五类代表页面覆盖。若D68改变Cart结构，必须重新执行四端完整回归。
- 0.5.2最新Shop四端与键盘Tab复测没有取得完整动态证据；若D29获得实施授权，Shop四端纳入D29 C6～C7商品卡回归，Focus Tab纳入D31全局Header/Footer回归。若这两个计划日尚未实施，则下一次前端回归优先补测，不把工具超时视为网站失败。
- D28已关闭，下一步进入D29前先按每日规则只读梳理商品卡契约；未经确认不提前实现商品卡、网格、Header/Footer或D30通知系统，也不部署Staging主题。

## 可复用核心思想

- 跨平台不变量：设计系统中的“基础控件”必须先冻结作用域和状态契约。直接从全局`button`或`input`开始写样式，很容易同时破坏导航、购买区和第三方组件；先定义受影响页面和明确排除项，能显著降低回归成本。
- 跨平台不变量：可见标签和可访问名称不是同一验收层级。`aria-label`能提供程序化名称，但普通用户仍需要可见上下文；优先使用原生`label`与唯一`for/id`关系，不用伪元素或脚本补一层看似相同的文字。
- WooCommerce/WordPress实现：先检查当前版本模板和Hook参数，再决定是否覆盖模板。核心已经提供`useLabel`时，通过主题展示Hook复用原生能力，比复制模板更少升级债，也能保留查询字段和国际化行为。
- Shopify或其他平台对照：不同平台的模板扩展点不同，但“复用平台原生语义、避免复制核心模板、把视觉规则限制在组件边界”仍然成立；具体API与升级兼容方式必须在对应平台重新验证。
