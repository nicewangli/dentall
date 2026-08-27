---
项目: DentAll WooCommerce
工作日: D31
计划检查点: D31（不自动等于一个完整实际工作日）
日期: 2026-08-27
计划工时: 6小时50分钟有效工作
实际有效工时: 未记录；不使用计划工时代填
验收层级: Local技术验收
状态: 已完成D31最小范围；P2动态路径与手机/平板最终交互按计划移交
tags:
  - DentAll
  - Day31
  - Header
  - Responsive
  - WooCommerce
---

# DentAll 每日复盘 D31：PC公告栏与主页头结构

## 相关笔记

- 每日笔记索引：[[README|DentAll每日笔记索引]]
- 前置项目笔记：[[Day30-设计系统v1与系统状态]]
- 当日学习笔记：[[WordPress实战笔记/Day31-四端设计稿还原与组件拆解]]
- 前置学习笔记：[[WordPress实战笔记/Day30-响应式栅格与系统状态]]
- Token来源与后继纠偏：[[Day27-设计证据与Design-Token]]、[[WordPress实战笔记/Day27-Design-Token与Mobile-First容器]]
- 后续项目笔记：[[Day32-PC主导航与一级下拉]]
- 后续学习笔记：[[WordPress实战笔记/Day32-原生菜单与Storefront下拉机制]]
- 后续计划：D33手机主页头、D34平板页头、D69 Mini Cart

> [!success] 当前结论
> D31经用户逐项确认后，已在Local落地公告栏、Logo、WooCommerce商品搜索、Account与原生Header Cart主行，并以DentAll子主题`0.9.0`完成五个登录态真实页面×四个基准宽度的结构回归和1440完整键盘Focus。390已确认My Account、Search触发器和Cart具有3px可见焦点，激活Search后的输入没有裁切；但折叠态直接Tab仍会进入未展开输入并下缘越界，展开后的1×1 submit也缺少足够可见的焦点停点，两项均作为D33 P2移交。当前不是正式品牌或内容验收：公告与Logo仍是Local TEST/占位，币种、语言和Help Center只是非交互位置槽，WPML/WCML未安装，真实搜索提交、Add-to-Cart fragment替换、非空Mini Cart及非Local Custom Logo仍未完成运行路径验收。

## 用户授权与范围边界

本轮实施依据不是笼统的“继续”，而是以下可追溯的明确确认：

1. `同意按D31最小范围实施；公告栏使用仅Local的[TEST]占位文案，正式Logo和公告内容后补。`
2. `同意从D31起按领域拆分CSS：保留style.css基础层，新增site-shell.css承载D31～D36。`
3. Logo与图标先使用占位资源时，用户确认：`没事，先用着。`
4. Logo二次调整要求：`logo效果不太好，你重新生成一张logo图片？现在的logo图片蓝色不够深，文字也太小`，并补充`牙齿的外围是要蓝色的。`
5. 全站动作蓝纠偏要求：`搜索按钮的蓝色，好像和设计稿的蓝色不一样，太浅了一点？网站的总蓝色设定是不是和设计稿有一点出入？`
6. 公告栏结构要求：`pc端靠左有3个公告，靠右有币种和语言切换和help center，你看着处理，要考虑到4个端。`
7. 多语言/多币种边界：`先不实现多语言、多币种`，但希望为以后WPML保留设计稿位置；随后确认`现在只是想把位置先给占据着，后续插件安装后能替换。`

因此本轮允许：修改子主题展示Hook、基础Token、全站壳层CSS和Local占位图片；不允许也未执行：安装WPML/WCML、实现切换器、建立Help Center路由、写数据库、改变搜索参数、添加Mini Cart业务逻辑或部署Staging/Production。

## 今日三个验收结果

- [x] 在Local建立三条TEST公告与三个右侧非交互槽位，并按390/768/1024/1440可用空间渐进披露。
- [x] 复用Storefront/WooCommerce公开输出完成Logo、商品搜索、Account与Header Cart主行；没有复制交易数据、修改父主题或新增模板覆盖。
- [x] 五个代表页面×四宽度完成结构、溢出、重复ID、资源版本和动作蓝回归；适用的动态路径缺口已按P2明确移交。

## 实际实现

### 文件与职责

| 文件 | 本轮职责 |
|---|---|
| `app/public/wp-content/themes/dentall/style.css` | 子主题版本升至`0.9.0`；全局动作蓝Token从`#0b63d8/#0756c9`纠偏为`#003a9f/#002f82` |
| `app/public/wp-content/themes/dentall/inc/setup.php` | 以`dentall-site-shell`登记全站壳层CSS，并依赖`storefront-child-style` |
| `app/public/wp-content/themes/dentall/inc/storefront-hooks.php` | Local公告、Local占位品牌、Account入口及Storefront原生Header Cart重排 |
| `app/public/wp-content/themes/dentall/assets/css/site-shell.css` | D31～D36全站壳层职责；当前承载公告栏与Header响应式，不把页面壳继续堆回基础层 |
| `app/public/wp-content/themes/dentall/assets/images/logo-placeholder-v2.png` | Local且未配置Custom Logo时使用的透明占位Logo |
| `app/public/wp-content/themes/dentall/assets/images/icon-user.svg` | Account轮廓图标蒙版 |
| `app/public/wp-content/themes/dentall/assets/images/icon-cart.svg` | Cart轮廓图标蒙版 |

`style.css`继续承担Design Token、基础排版、基础控件、卡片和系统状态；`site-shell.css`只承担跨页面、同生命周期的Header/Navigation/Footer壳层。拆分依据是职责、依赖和后续D32～D36共同变更频率，不是因为文件接近1000行就机械拆分。

当前页面实际样式顺序为Storefront子主题基础样式 → `dentall-site-shell` → WooCommerce Brands扩展样式；因此`site-shell.css`只保证晚于`storefront-child-style`，不能声称它是全站绝对最后一份CSS。

### 新增函数与输出链

本轮新增5个职责明确的函数：

1. `dentall_enqueue_site_shell_styles()`：登记全站壳层CSS。
2. `dentall_announcement_bar()`：仅在`wp_get_environment_type() === 'local'`时输出TEST公告与占位槽。
3. `dentall_site_branding()`：Local且没有正式Custom Logo时输出占位PNG；其他环境或存在Custom Logo时立即委托`storefront_site_branding()`。
4. `dentall_header_account_link()`：通过`wc_get_page_permalink( 'myaccount', '' )`输出Account链接。
5. `dentall_configure_storefront_header()`：在父主题完成注册后替换品牌回调，并把Account与完整Storefront Header Cart追加到搜索之后的优先级40。

购物车继续使用Storefront原生`storefront_header_cart()`及其`woocommerce_add_to_cart_fragments`契约；D31只改变Hook位置与视觉布局，没有复制小计/数量或新写AJAX。

### 公告栏与右侧扩展槽位

Local公告栏当前左侧三项全部带`[TEST]`：

- `[TEST] Free Shipping on Orders Over $199`
- `[TEST] 5–10-Day Easy Returns`
- `[TEST] Trusted by 10,000+ Dental Professionals`

右侧预留三个`li`槽位：

- Currency：显示WooCommerce当前币种代码与符号，本轮为`USD $`；只读展示，不执行切换。
- Language：显示`English`；只读展示，不执行切换。
- Help：显示`Help Center`；只读展示，没有链接或目标页面。

这些槽位冻结的是信息架构和视觉位置，不是插件接口实现。后续经单独授权安装WPML Multilingual CMS、WooCommerce Multilingual & Multicurrency等既有评估方案后，可以在同一DOM职责与CSS槽位内替换为插件真实输出；仍需重新验证语言/币种状态、URL、缓存维度、购物车金额、SEO和键盘操作。本轮没有安装、启用或模拟WPML/WCML，也没有伪造可点击切换行为。

### Logo与图标

- Logo使用内置图像生成模式生成横版候选，要求牙齿外围为深蓝、`DentAll`主文字更大、整体蓝色更深；先生成在单色背景上，再移除色键、裁切并保留透明通道。
- 最终占位PNG为`1024×240`、144221字节、32位ARGB；四个角点Alpha均为0。HTML声明同尺寸，CSS以`max-width: 13.75rem`和`max-height: 3.75rem`限制显示，避免CLS与拉伸。
- 该图片是AI生成的Local临时资源，不是正式商标、品牌规范或授权证据。正式Logo仍由业务方提供并通过WordPress Custom Logo管理。
- 视觉专项Review把“占位Logo横向略宽、144221字节偏重”登记为P2：不阻塞Local Header骨架，但正式素材到位时应重新裁定比例并在D98或上线前优化体积，不能把当前占位质量当品牌验收。
- Account与Cart使用本地SVG蒙版，颜色继承文字状态；未加载图标字体、第三方CDN或JavaScript。

### 动作蓝全局纠偏

D27原始Token的历史值`#0b63d8/#0756c9`在当时属于有证据的候选，但D31把真实设计稿、Header搜索按钮和全站组件放在同一页面对照后，确认整体偏浅。按用户明确授权，D31修改原始动作蓝：

| 用途 | D27～D30历史值 | D31当前值 | 白字对比度 |
|---|---|---|---:|
| Normal / `--dentall-color-action` | `#0b63d8` | `#003a9f` | 9.97:1 |
| Hover/Focus / `--dentall-color-action-hover` | `#0756c9` | `#002f82` | 12.15:1 |

新Normal在白、浅蓝、浅天蓝、浅薄荷背景上的对比度为8.91:1～9.97:1；新Hover为10.85:1～12.15:1。它们继续超过WCAG AA普通文本门槛。因为修改的是共享原始Token，所有消费语义动作色的按钮、文本链接、Focus、Notice等都会变化；因此执行的是全站五页×四宽度回归，而不是只看搜索按钮。D27/D28/D30中的旧值与旧哈希仍是各自日期的历史快照，不反向改写。

## 四端响应式合同与实测结果

### 公告栏

| 视口 | 实际披露 |
|---:|---|
| 390 | 仅第一条TEST公告，居中；工具槽隐藏 |
| 768 | 仅第一条TEST公告，居中；工具槽隐藏 |
| 1024 | 左侧三条TEST公告；右侧只显示当前币种`USD $` |
| 1440 | 左侧三条TEST公告；右侧显示`USD $`、`English`、`Help Center` |

Home四个宽度复测均无横向溢出。规则采用内容逐级披露：基础宽度只保留第一优先公告，`64rem`显示三条公告与币种，`75rem`再显示语言和Help槽；不是复制四套HTML。

### Header主行

| 范围 | 一套DOM的实际布局 | 当前边界 |
|---|---|---|
| `<768px` | Header内只显示品牌；搜索、Account、Header Cart隐藏 | D31不提前实现D33手机Header；Storefront Handheld Footer在适用页面承担唯一动作入口 |
| `>=48rem`且`<75rem`（默认16px初始字号时为768～1199px） | 第一行品牌/Account/Cart，第二行商品搜索 | Handheld Footer隐藏；为平板保留可用而非最终D34像素收口 |
| `>=75rem`（默认16px初始字号时为1200px） | 品牌、商品搜索、Account、Cart同一行 | D31 PC主要验收状态 |

390下的Cart Blocks页面是明确例外：Storefront的Block脚本没有输出Handheld Footer，同时D31 Header三动作按范围隐藏，因此不能宣称该页当前始终存在移动端动作入口；此项已登记D33解决。

## 测试与验证证据

### 五页×四宽度登录态浏览器矩阵

主Agent在登录态真实页面完成20格验证：

| 页面 | 路径 | 390 | 768 | 1024 | 1440 |
|---|---|---:|---:|---:|---:|
| Home | `/` | 通过 | 通过 | 通过 | 通过 |
| Shop | `/shop/` | 通过 | 通过 | 通过 | 通过 |
| Simple Product | `/product/test-d12-simple-fixed-pack/` | 通过 | 通过 | 通过 | 通过 |
| Cart | `/cart/` | 通过；记录Blocks例外 | 通过 | 通过 | 通过 |
| My Account | `/my-account/` | 通过 | 通过 | 通过 | 通过 |

20格共同证据：页面无意外横向溢出、目标DOM没有重复ID、`style.css?ver=0.9.0`与`site-shell.css?ver=0.9.0`已加载，动作按钮计算色为`#003a9f`。390下Header三动作隐藏，Storefront Handheld Footer仅在父主题实际输出的页面保留一个入口；`>=768px`三动作显示且Handheld Footer隐藏。

### 静态、资源与HTTP证据

- `setup.php`与`storefront-hooks.php`执行`php -l`均无语法错误。
- `site-shell.css`经`.NET ReadAllLines()`与LF分隔符统计均为303个物理行，PowerShell `Get-Content`因不返回末尾空记录显示301条；文件为7334字节、43/43对花括号、0个`!important`，gzip模拟约2069字节，SHA-256为`656330EEC3979A8D07A2BB6D9BAB0CF8AEEA25DF8518D415754B6BAE8768E2FF`。
- PNG为144221字节；Account/Cart SVG分别253/320字节。Local HTTP读取`style.css`、`site-shell.css`和三张图片均返回200，响应长度与磁盘一致。
- `git diff --check`退出码为0；当前提示仅为Git未来可能执行LF→CRLF转换，不是空白错误。
- PC运行时相对D30新增1个CSS与3个图片请求；没有新JS、字体、模板覆盖、插件、自定义SQL、远程调用、Cron或新增autoload Option。代码会读取主题版本、Custom Logo、币种与WooCommerce页面绑定等既有API/Option，未使用Query Monitor做前后查询数基线，因此不把查询影响写成0，也不宣称性能“零影响”或更快。
- 匿名HTTP仍被WooCommerce Coming Soon保护，只能作为保护边界与资源可达性smoke，不能替代上述登录态Header浏览器证据。

### 真实键盘Focus与Console证据

- 1440代表页真实Tab顺序为：Search输入 → Search按钮 → Account → Cart；Search按钮、Account与Cart均获得`#002f82 solid 3px`、`outline-offset: 3px`的可见焦点，轮廓未被裁切。
- 390代表页测试序列为：`Built with WooCommerce` → `My Account` → `Search`入口；在Search入口按Enter后，Storefront从底栏上方展开搜索表单（`y=702.23`、`h=74`），再Tab进入Search input（`y=716.23`、`h=46`）→ Search submit → Cart。My Account、Search触发器与Cart均显示同一3px深蓝轮廓，展开后的input没有裁切；不能把这个结果扩大到submit。
- 如果不先在Search入口按Enter而直接继续Tab，浏览器会进入仍位于底栏内部的输入，其下缘越界；这说明移动端必须按“先激活Search入口，再进入表单”的真实交互合同验收，不能只机械连续Tab后宣称裁切缺陷或通过。
- 移动搜索的submit虽存在于DOM、具有可访问名称且可Tab到达，但实际只有1×1px，几乎不可见，不能据此宣称其可见Focus满足要求；它与手机搜索最终视觉一起登记为D33 P2。
- `tab.dev.logs({ levels: ['error', 'warning'] })`返回空数组。浏览器控制工具自身出现过Statsig网络超时，但它不在站点Console中，不能登记为DentAll页面错误。

### Review结论

- 设计还原、WordPress输出映射与文档契约均进行了独立只读复核。
- 当前D31最小范围已发现P0=0、P1=0；P2均已明确移交，不用“未触发”冒充通过。
- Cart Blocks 390入口例外、真实搜索提交、非空Cart/fragment与正式Logo环境边界已经写入验收限制；1440 Focus、390三个底栏动作及展开后input、站点Console已补证，390折叠态直接Tab越界和1×1 submit可见Focus不足仍按D33 P2保留。

## 未验证项与移交

| 未验证或未完成 | 为什么本轮不制造证据 | 接续点 |
|---|---|---|
| 商品搜索实际提交、空输入/特殊字符及结果页 | D31只确认原生GET契约、可见标签和按钮；没有提交搜索 | D33移动搜索可用性；D47搜索结果页完整验收 |
| 真实Add-to-Cart后的fragment替换 | 保留Storefront原生Filter不等于已经触发成功 | D69 Mini Cart AJAX与缓存状态测试 |
| 非空Mini Cart、长金额和片段失败 | 当前最终矩阵主要为空Cart状态 | D69 |
| 非Local输出与正式Custom Logo | 代码具有显式降级分支，但本轮未部署Staging，也没有正式Logo | 正式素材到位后及后续Staging部署验收 |
| 占位Logo比例与体积 | 当前横向略宽且144221字节，仅满足Local骨架 | 正式Logo替换时重新校准；体积优化接D98/上线前资源审计 |
| WPML/WCML语言与币种切换 | 本轮仅保留非交互槽位，插件未安装/启用 | 经后续功能确认单授权后独立实施与验收 |
| Help Center链接与内容 | 目标页面/URL尚未确认 | 固定页面与导航相应工作日 |
| 390折叠态直接Tab进入未展开Search input并下缘越界 | 已验证的可用路径要求先在Search入口按Enter；隐藏/折叠状态仍不应让焦点进入越界表单 | D33调整折叠态焦点管理并复验键盘顺序、可见性和底栏遮挡 |
| 390展开搜索后的submit仅1×1px | DOM中可达且有可访问名称，不等于具备足够可见的焦点停点 | D33提供清晰可见、尺寸合理的提交动作并复验Focus与触控目标 |
| 手机Header搜索/菜单/动作完整方案 | 不提前吸收D33 | D33；Cart Blocks 390入口列为首要回归项 |
| 平板最终密度、键盘和中间宽度 | D31只保证结构安全；CSS `48rem`与Storefront固定768px行为在非默认初始字号下可能错位 | D34在非默认初始字号及相关缩放条件复验入口断档/重复 |

P2责任与计划：开发者负责在D33/D34/D47/D69及后续Staging部署节点关闭技术验证债；Website Manager/业务方负责提供正式Logo、授权和公告/Help内容；WPML/WCML只有在新的功能确认单获批后才由开发者集成。未到对应节点前继续保留当前Local安全占位，不将未验状态升级为完成。

## 专注周期记录

| 周期 | 实际结果 | 证据/文件 | 用时 |
|---|---|---|---:|
| C1 | 核对设计证据、Storefront Header Hook与D31最小合同 | 本笔记、父主题源码、真实DOM | 未记录 |
| C2 | 冻结CSS领域拆分、Local TEST与原生动态数据边界 | 用户授权、`AGENTS.md`、`setup.php` | 未记录 |
| C3 | 实现公告栏、壳层样式加载与三类右侧槽位 | `setup.php`、`storefront-hooks.php`、`site-shell.css` | 未记录 |
| C4 | 实现占位Logo、Account和原生Header Cart重排 | PHP与3张本地图片 | 未记录 |
| C5 | 完成四端渐进布局、动作蓝全局纠偏和减法审查 | `style.css`、`site-shell.css` | 未记录 |
| C6 | 五页×四宽度、公告四端、1440完整Focus、390底栏动作/输入及两项P2边界、Console、静态/HTTP/资源/对比度验证 | 登录态浏览器与命令证据 | 未记录 |
| C7 | 专项Review、P2移交、项目状态和学习笔记收口 | 本笔记、学习笔记、`PROJECT_STATE.md`、索引 | 未记录 |

## WordPress实战学习笔记收尾

- [x] 已基于[[WordPress实战笔记/WordPress实战学习笔记模板|WordPress实战学习笔记模板]]核心骨架更新[[WordPress实战笔记/Day31-四端设计稿还原与组件拆解]]。
- [x] 已补入D31真实Hook、CSS领域拆分、Logo透明处理、四端响应规则、运行证据、排错顺序与未验证边界，不以空泛教程替代项目事实。
- [x] 已在[[WordPress实战笔记/WordPress实战笔记索引|WordPress实战笔记索引]]登记，并与本项目Day笔记及D27/D30直接前置知识显式互链。
- [x] 文档未记录密码、Cookie、私钥、支付密钥、真实客户资料或本机个人目录。
- 延期原因与补写节点：笔记不延期；个人费曼自测和动手复演保持未勾选，不由Agent代替用户完成。

## 减法审查与量化

- 净增运行文件：1个CSS领域文件＋3张图片；Logo旧运行SVG不继续作为活动资源，设计源文件仍保留在`design-assets`。
- 既有运行文件净变化：`inc/setup.php`净增20行，`inc/storefront-hooks.php`净增131行，`style.css`为3行新增/3行删除、净0行（版本与两个Token替换）；新`site-shell.css`为303个物理行、43个带大括号规则/分组块。图片属于二进制/矢量资源，不用伪造“代码行数”。
- 净增PHP函数：5个；每个函数分别负责enqueue、公告、品牌、Account和Hook编排，没有新增类、通用框架或模板层。
- 新CSS只有一个职责域、303行和3个真实行为断点；没有按每个设备、每张图或每个函数拆成微型文件。
- 公告三条和右侧三槽共用一套语义DOM；四端只改变披露与布局，不生成四份Header。
- 未加入分类搜索、语言/币种假切换、Help假链接、移动抽屉、额外JS或自研Cart fragments。

## 数据、URL与系统影响

- 数据：无数据库、商品、订单、用户、媒体库、Option或配置写入；Logo为版本化主题文件，不是媒体库正式资产。
- URL/SEO：未改固定链接、Title、Meta、Canonical、Schema、robots或Sitemap。Account使用WooCommerce页面API，品牌使用`home_url()`，搜索继续原生`GET s + post_type=product`；未新增语言、币种或Help URL。
- 缓存与性能：主题版本升至`0.9.0`作为两个CSS缓存键；新增1 CSS＋3图片请求。未来正式Logo或槽位实现变化需要再次提升版本/清缓存并做真实页面回归。
- 支付、物流、价格、库存、订单：未改变。公告中的免邮、退货与信任数字全部是Local TEST，不构成可发布承诺。
- 部署：仅Local修改；Staging、Production、DNS和真实支付未改变。非Local分支与正式Custom Logo仍须部署时验证。
- 插件：WPML/WCML未安装、未启用、未写配置；当前位置槽不代表兼容性已经验收。

## 明日启动点

1. D32先只读盘点WordPress Primary/Handheld菜单位置、Storefront导航Hook和设计稿二级导航，不提前写移动抽屉。
2. 功能确认后再实现PC主导航、下拉、后台菜单绑定与键盘边界，并回归D31 Header主行。
3. D33首先关闭Cart Blocks 390无动作入口、折叠Search直接Tab越界与1×1 submit可见Focus不足，再完成手机搜索、菜单和购物车；D34完成平板最终密度、中间宽度、`48rem`/固定768px断点一致性及导航联动收敛。

## 可复用核心思想

### 跨平台不变量

- 设计稿只确定“应占据什么位置”时，可以先冻结稳定槽位，但不能伪造尚不存在的业务能力；占位必须在语义、视觉和验收文档中明确为非交互。
- 响应式不是把所有信息等比例缩小，而是按优先级渐进披露：小屏保留最高优先内容，中屏增加必要事实，大屏再显示辅助设置。
- 改共享Token就是全局变更。即使触发点是一个搜索按钮，也必须回归所有真实消费者，且旧文档值保留为历史证据。
- 文件拆分依据是稳定职责、依赖和生命周期，而不是行数焦虑；高内聚的一个领域文件通常比按截图或设备制造多份微文件更可维护。

### WordPress/WooCommerce当前实现

- Storefront通过Hook组合品牌、搜索与购物车；DentAll只在子主题正确生命周期中替换/重排回调，继续让WooCommerce拥有搜索、账户URL和购物车真相源。
- `wp_get_environment_type()`与`has_custom_logo()`组成占位资源的双闸门：Local可继续验证骨架，正式环境和正式Logo不会被临时图片强占。
- WPML/WCML未来可以替换已预留的语言/币种职责位置，但真正集成仍要处理插件输出、URL、缓存、金额、SEO和可访问性，不能把CSS槽位等同于插件兼容完成。

### Shopify或其他平台

- 其他平台可能用Liquid Section、Theme Settings、App Block或前端组件实现Header，但“平台真实数据＋稳定槽位＋Mobile First披露＋真实状态验收”仍然成立。
- Shopify具体语言、市场、币种、账户和Cart机制本轮未验证，标记为待验证；知识迁移不扩大DentAll第一版范围。
