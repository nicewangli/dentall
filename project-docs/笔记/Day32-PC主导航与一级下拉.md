---
项目: DentAll WooCommerce
工作日: D32
计划检查点: D32（不自动等于一个完整实际工作日）
日期: 2026-08-27
计划工时: 6小时50分钟有效工作
实际有效工时: 未记录；不使用计划工时代填
验收层级: Local技术验收
状态: 已完成D32最小范围；手机抽屉、正式菜单内容与真实触屏路径按计划移交
tags:
  - DentAll
  - Day32
  - Navigation
  - WordPress菜单
  - Storefront
---

# DentAll 每日复盘 D32：PC主导航与一级下拉

## 相关笔记

- 每日笔记索引：[[README|DentAll每日笔记索引]]
- 前置项目笔记：[[Day31-PC公告栏与主页头结构]]
- 当日学习笔记：[[WordPress实战笔记/Day32-原生菜单与Storefront下拉机制]]
- 前置学习笔记：[[WordPress实战笔记/Day31-四端设计稿还原与组件拆解]]
- 后续计划：D33手机Header与抽屉菜单、D34平板导航收敛、正式内容到位后的菜单URL复核

> [!success] 当前结论
> D32已在Local完成`TEST D32 PC Navigation`的原生菜单创建、Primary位置绑定和PC一级下拉样式。导航仅在`>=1200px`显示；设计稿要求的白色连续导航区没有上下分隔线，`Shop by Categories`蓝色按钮上下各保留8px留白。9个顶级项、分类下拉3项和About下拉1项已在真实登录态页面通过边界、键盘及五页回归；未新增PHP、JavaScript、模板覆盖、插件或依赖。

## 用户授权与范围边界

本轮实施依据为用户明确授权：`按上述最小范围实施，指导我在Local创建和绑定 TEST D32 PC Navigation。`

实施过程中，用户又按设计稿明确纠正两项视觉事实：

1. `Shop by Categories`按钮上下应有空隙；
2. 导航区域没有分隔线。

因此D32允许并已完成：

- 用户在Local原生菜单后台创建、排序、嵌套并绑定TEST菜单；
- 在既有`site-shell.css`中补充PC导航、一级下拉、Hover和键盘Focus状态；
- 提升子主题版本以刷新CSS缓存；
- 回归D31公告、Logo、搜索、Account和Cart主行。

D32明确不做：

- 手机抽屉、平板最终导航、Mega Menu、第二级以下层级；
- 新插件、新模板覆盖、自定义后台保存逻辑或新JavaScript；
- 正式菜单文案、正式分类结构、正式URL冻结或SEO发布；
- Staging、Production、DNS、支付、物流或真实订单改动。

## 今日三个验收结果

- [x] 用户通过WordPress原生后台创建并保存`TEST D32 PC Navigation`，绑定Primary位置；13个菜单项形成9个顶级项和两个一级下拉父项。
- [x] 子主题`0.10.0`在`>=1200px`输出无分隔线PC导航，并完成分类按钮留白、顶级导航分布、下拉方向、Hover、Focus与长文本边界。
- [x] 390/768/1024/1199/1200/1440边界、1440键盘链、五个登录态代表页面、Console、HTTP资源和静态检查完成；当前范围P0/P1为0。

## Local后台操作记录

### 顶级项在哪里创建

WordPress菜单没有单独的“创建顶级项”按钮。路径为`外观 → 菜单`：从左栏把页面、产品分类或自定义链接添加到右侧菜单结构后，只要把该菜单项拖到最左侧、不缩进到其他项下面，它就是顶级项；向右缩进一级则成为上方项目的子项。

### 自定义链接的详细步骤

1. 在`外观 → 菜单`中选择`TEST D32 PC Navigation`。
2. 展开左侧`自定义链接`。
3. 在“URL”输入Local TEST目标，如`/shop/`或`/`；在“链接文字”输入设计稿显示名。
4. 点击“添加至菜单”。
5. 在右侧将项目拖到最左侧形成顶级项，或向右缩进形成一级子项。
6. 展开`Shop by Categories`，在“CSS类”填写`dentall-menu-categories`。若看不到“CSS类”，先在页面右上角“显示选项”中勾选“CSS类”。
7. 在“菜单设置/显示位置”只勾选Primary；本轮不绑定Secondary或Handheld。
8. 点击“保存菜单”，再刷新登录态前台验证。

> [!warning] TEST URL边界
> 当前多个自定义链接临时指向`/shop/`或`/`，只用于验证结构和交互。这会让WordPress把多个重复目标误判为当前项，因此D32没有为顶级`current-menu-item`额外制造高亮。正式页面和Slug到位后，应逐项替换目标并重新验收当前态、Canonical及内部链接。

### 实际菜单结构

| 顶级项 | 类型/临时目标 | 一级子项 |
|---|---|---|
| Shop by Categories | 自定义链接`/shop/`，CSS类`dentall-menu-categories` | TEST D12 Products、Uncategorized、测试（原生产品分类） |
| Products | 自定义链接`/shop/` | — |
| Custom Dental | 自定义链接`/shop/` | — |
| Packaging Solutions | 自定义链接`/shop/` | — |
| Brands | 自定义链接`/shop/` | — |
| Deals | 自定义链接`/shop/` | — |
| Resources | 自定义链接`/` | — |
| About Us | 自定义链接`/` | `[TEST] About child`（原生Page） |
| Contact Us | 自定义链接`/` | — |

菜单共13项，Primary绑定到菜单term ID 25；Secondary与Handheld未绑定。以上均为Local TEST事实，不是正式业务信息架构。

## 实际实现

### 修改文件与职责

| 文件 | D32职责 |
|---|---|
| `app/public/wp-content/themes/dentall/assets/css/site-shell.css` | 复用D31～D36全站壳层文件，增加PC导航显示条件、布局、分类按钮、一级下拉及键盘/Hover状态；不接管菜单数据 |
| `app/public/wp-content/themes/dentall/style.css` | 子主题版本由`0.9.0`提升至`0.10.0`，刷新既有CSS缓存键 |

没有新增运行文件、PHP/JavaScript函数、模板覆盖、插件、依赖或构建步骤。

### 为什么继续使用原生菜单

- WordPress继续拥有菜单项、层级、类型与主题位置数据；Website Manager未来可从后台维护，不需要开发者改代码。
- Storefront继续通过`storefront_header` Hook和`wp_nav_menu()`输出`#site-navigation`、`.primary-navigation`及`.sub-menu`。
- Storefront既有`navigation.js`会在键盘焦点进入菜单项时给父`li`加`.focus`；D32 CSS同时支持`:hover`、`.focus`和`:focus-within`，没有复制相同JavaScript。
- 子主题已有`fallback_cb=false`保护：Primary未绑定时不会自动把所有页面变成菜单。

### 响应式与视觉合同

| 宽度 | D32导航行为 |
|---:|---|
| 390、768、1024、1199 | PC导航隐藏；D33再实现手机抽屉，D34收敛平板 |
| 1200、1440 | PC导航显示；同一套菜单DOM，9个顶级项横向分布 |

PC视觉规则：

- Header底边和导航容器边线均为0，形成设计稿中的连续白色区域；
- `.primary-navigation`上下各8px内边距，使蓝色分类按钮与上下内容保留真实空隙；
- 顶级链接最小高度48px，分类按钮使用动作深蓝和白字；
- 分类下拉从父项左边向右展开；其他下拉以父项右边为锚点向左展开，降低右侧越界风险；
- 长文本允许折行，不以隐藏或截断掩盖数据问题；
- 当前只支持一级下拉，未预实现Mega Menu和更深层交互。

### 正常与边界状态

- 正常：鼠标Hover或键盘Focus进入父项时显示下拉，离开该菜单组后隐藏。
- 空菜单：Primary没有项目时，不因PC导航产生额外16px空白带。
- 小屏：导航隐藏时，指向`#site-navigation`的Skip Link同步隐藏；D33启用手机导航时必须恢复相应可访问入口。
- 长文本：顶级项允许`min-width:0`和`overflow-wrap:anywhere`，不会强制撑破页面。
- 右边界：普通下拉默认向左展开；分类项作为左侧业务入口向右展开。
- 重复TEST URL：不额外输出顶级当前项高亮，等待正式URL可区分后再验收。
- 加载/错误：当前菜单由PHP随页面同步输出，没有前台异步请求，因此没有独立Loading/Error UI；服务器或菜单数据失败走WordPress页面错误与`fallback_cb=false`空输出边界。
- 缺图/售罄/不可购买：本导航不输出商品图、价格、库存或购买动作，不属于D32适用状态；进入对应商品页面后的状态由商品卡、详情和交易工作日负责。

## 验证证据

### 浏览器实测

- 390/768/1024/1199：PC导航隐藏、无横向溢出；D31在各宽度应显示的公告、Logo、搜索、Account和Cart保持原行为。
- 1200：9个顶级项全部出现，首尾位于容器内，分类按钮上下各8px，无横向溢出。
- 1440：主导航实际宽1256px，位于1320px外框内；分类按钮计算色为`rgb(0, 58, 159)`、文字为白色、最小高度48px；Header与导航边线计算值均为0。
- 分类下拉由隐藏状态`visibility:hidden; opacity:0; pointer-events:none`切换为可见状态，范围约为`left 92px → right 332px`。
- About下拉右对齐于父项，范围约为`left 978.5px → right 1218.5px`，没有越出导航容器。
- Home、Shop、Simple Product、Cart、My Account五个登录态页面均保留9个顶级项、3个分类子项和1个About子项；无重复ID或页面Console error/warning。

### 真实键盘链

- 焦点从`Shop by Categories`进入第一个子项后，下拉保持可见，子项获得3px深蓝可见Focus；继续Tab依次到3个分类项，离开到Products后下拉关闭。
- 从Products按`Shift+Tab`回到分类父项时，下拉重新显示。
- 焦点从About Us进入`[TEST] About child`时，下拉保持可见；Tab到Contact Us后关闭。
- 未新增Escape专用JavaScript；当前最小范围依靠原生链接Tab顺序和Storefront `.focus`机制。真实触屏设备的首击展开/次击跳转仍列为未验证边界。

### 静态与资源证据

- `site-shell.css`最终为464行、12261字节、65/65对花括号、0个`!important`，SHA-256为`9B23EEF3DA081058511E4BA7545947D1F1C16D54CB006EF1A0BADC6B9B124576`。
- Local HTTP读取`site-shell.css?ver=0.10.0`返回200、12261字节，并与磁盘逐字节一致；浏览器加载的两个子主题CSS均使用`ver=0.10.0`。
- `git diff --check`通过；页面Console error/warning为空。
- WP-CLI仍提示本机`php_imagick.dll`不可用；既有GD路径可用，该警告与D32菜单无关且未阻塞本轮。

## 独立复核

- Code Review初审发现3个P2和1个P3：小屏Skip Link目标隐藏、空菜单PC空白带、长文本边界、下拉方向依赖末尾位置；已全部修正。
- Code Review复审确认上述问题关闭，未发现新P0/P1/P2；花括号65/65。
- 独立设计复核未发现P0～P3，并确认无分隔线、按钮上下各8px、容器对齐和两个240px下拉边界；截图接口超时，但DOM尺寸、计算样式、原始设计稿和源码证据一致。
- 独立测试在其完成的数据库、静态、HTTP及首页390/768/1024/1200/1440范围内未发现P0/P1/P2；其1199真实取值、1440键盘链和另外四页登录态浏览器批量检查因工具超时未形成独立证据，因此不以该报告单独签字。上述缺口由主执行链的1199、真实Tab/Shift+Tab和五页登录态浏览器证据覆盖；TEST重复URL仍保留为P3限制。

## 减法审查与量化

- 相对D31基线，运行文件净增0个；继续复用已按全站壳层职责建立的`site-shell.css`。
- D32在该CSS中净增161行、4927字节、22个规则块；`style.css`只替换1处版本号，净行数为0。
- PHP函数、JavaScript函数、模板覆盖、插件、依赖和数据同步脚本均净增0。
- 保留的规则只覆盖4类必要职责：PC显示边界、横向布局、分类按钮/下拉视觉、Hover/Focus/异常边界。已删除分隔线、脆弱的`nth-last-child`方向判断和重复URL造成的假当前态，没有预写手机抽屉、Mega Menu或正式路由状态。
- D32没有新增资源请求：CSS继续通过D31已有handle加载；文件体积有实际增加，但未做前后性能测量，因此不宣称性能零影响或提速。

## 未验证项与移交

| 未验证或未完成 | 原因 | 接续点 |
|---|---|---|
| 手机抽屉及小屏Skip Link恢复 | D32明确只做PC导航 | D33 |
| 平板最终密度与导航联动 | 768/1024本轮只验证PC导航应隐藏 | D34 |
| 真实触屏设备首击展开/次击跳转 | 当前证据来自桌面浏览器与键盘 | D33/D34真机回归 |
| 正式文案、正式页面/分类与唯一URL | 属于业务“血肉”，当前只用TEST样本 | 内容到位后由Website Manager维护，开发者复核URL/SEO |
| 正式当前项高亮 | 重复TEST URL会制造多个伪当前项 | 正式URL可区分后验证 |
| 匿名真实商城内容 | WooCommerce Coming Soon仍保护站点 | 后续受控Staging/发布验收 |
| Staging与Production | 本轮只授权Local | 后续独立部署闸门 |

## 数据、URL与系统影响

- 数据：用户通过WordPress原生菜单后台写入Local菜单、菜单项层级、CSS类及`nav_menu_locations.primary`绑定；未改商品、订单、客户、库存、价格或媒体。Codex没有自定义数据库写入。
- URL/SEO：当前菜单增加Local内部链接，但多个目标是TEST用`/shop/`或`/`，不构成正式信息架构；未改固定链接、Title、Meta、Canonical、Schema、robots或Sitemap。
- 缓存与性能：子主题版本升至`0.10.0`刷新CSS缓存键；请求数不变，既有CSS增加4927字节。没有新查询逻辑、远程请求、Cron或autoload Option。
- 支付、物流、交易：未改变支付、物流、税费、价格、库存、购物车、结账或订单流程。
- 部署：仅Local；Staging、Production、DNS及真实支付均未改变。

## 下一步

1. D33先处理D31移交的390折叠Search键盘越界、1×1 submit可见Focus和Cart Blocks移动入口，再为同一菜单DOM实现手机抽屉并恢复小屏Skip Link。
2. D34复核768/1024的平板密度、触控和中间宽度导航联动。
3. 正式菜单内容到位后逐项替换TEST链接，再做当前项、404、重定向、Canonical和内部链接验收。

## 可复用核心思想

### 跨平台不变量

- 菜单结构应由内容维护系统拥有，前端只解释布局和交互；把名称、URL和层级硬编码进模板会放大维护与SEO风险。
- 视觉微调要落到可验证规则：本轮“有空隙、无分隔线”分别对应8px容器留白与0边线，而不是凭截图口头判断。
- 下拉验收不能只看鼠标Hover；至少要覆盖键盘进入、组内移动、离开关闭、左右边界、长文本和空数据。

### WordPress/WooCommerce当前实现

- WordPress用菜单项与主题位置分离“内容树”和“显示插槽”；Storefront通过`wp_nav_menu()`输出语义DOM，并用既有脚本维护`.focus`。
- `fallback_cb=false`能防止空位置意外列出全部页面；CSS还要避免空菜单包装器留下视觉空带。
- 产品分类作为原生菜单项时，其URL由WordPress术语对象维护；自定义链接适合本轮TEST骨架，但正式阶段必须治理重复目标和当前项状态。

### Shopify或其他平台

- 其他平台也应分离导航数据、主题位置和前端呈现；具体后台名称、Liquid对象、菜单层级限制和发布机制本轮未验证，标记为待验证。
- 可迁移的是“内容系统拥有链接树＋主题渲染一套语义结构＋四端/键盘真实验证”，不是照搬WordPress类名或Hook。
