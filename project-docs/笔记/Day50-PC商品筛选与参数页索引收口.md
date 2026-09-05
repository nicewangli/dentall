---
项目: DentAll WooCommerce
工作日: D50
计划检查点: D50（不自动等于一个完整实际工作日）
日期: 2026-09-03
实际有效工时: 未记录；不使用计划工时代替
验收层级: Local PC商品筛选与参数页索引收口
状态: 已完成（Local确认范围；移动筛选留D51）
tags:
  - DentAll
  - Day50
  - WooCommerce
  - ProductFiltering
  - TechnicalSEO
---

# DentAll 每日复盘 D50：PC商品筛选与参数页索引收口

## 相关笔记

- 每日笔记索引：[[README|DentAll每日笔记索引]]
- 前置项目笔记：[[Day49-商品筛选合同与属性查询表]]
- 当日学习笔记：[[WordPress实战笔记/Day50-WooCommerce链接式筛选与参数治理]]
- 前置学习笔记：[[WordPress实战笔记/Day49-WooCommerce属性查询表与商品级筛选]]
- URL与SEO合同：[[../URL_SEO_MAP|URL与SEO映射]]
- 决策：[[../DECISIONS#ADR-031：PC商品筛选复用Woo原生查询并集中治理参数URL|ADR-031]]
- 后续项目笔记：[[Day51-手机与平板筛选抽屉]]
- 后续学习笔记：[[WordPress实战笔记/Day51-原生Dialog与单一筛选DOM]]

> [!check] 当前结论
> Day50已按用户确认范围在Local完成。DentAll子主题0.26.0只在Shop与商品分类输出Categories、Price、Size、Shade筛选结构，并在`>=1200px`显示240px常驻侧栏；属性沿用WooCommerce原生链接式Layered Nav，价格使用无JavaScript的Min/Max＋Apply，结果仍来自同一个Woo主查询。DentAll Core 0.2.7将所有价格、`filter_*`和`query_type_*`参数页收口为`noindex, follow`，Yoast Canonical继续指向基础归档。四宽、键盘、错误态、组合查询、URL白名单、SEO和数据不变量通过，三路独立复核最终P0/P1/P2/P3均为0。

## 授权与实施边界

用户于2026-09-03明确回复：

> 确认按推荐范围实施 Day50，仅限 Local：先关闭筛选参数页 robots P2；允许新增 inc/catalog-filters.php 并最小修改现有加载、样式和 SEO 模块；仅在 >=1200px 为 Shop/商品分类启用 Categories、Price、Size、Shade 常驻侧栏；采用无 JS 链接式属性筛选和 Min/Max＋Apply，复用 Woo 主查询及 D49 合同；390/768/1024 只做无回归；不做品牌、评分、计数、Chips、专门 Reset、移动抽屉、插件、商品数据或非 Local 变更。

本轮实施合同：

- 只在Local修改DentAll子主题和既有`dentall-core` SEO模块；不部署或同步非Local。
- 筛选入口仅限Shop与`product_cat`，商品搜索和其他taxonomy不继承D50侧栏。
- 分类、价格、Size、Shade继续使用D49参数与父商品级Variation语义，不建立第二商品查询。
- 链接、表单、排序和分页只传播有效筛选及Woo原生排序白名单；条件变化回基础归档第一页。
- 参数键只要包含价格、`filter_*`或`query_type_*`，无论值有效、空或未知属性，均防御性`noindex, follow`。

明确不做：

- 不做品牌、评分、Package Quantity入口、计数、Chips、专门Reset、价格Slider或严格同Variation匹配。
- 不做移动抽屉、筛选开关、AJAX或自定义JavaScript；390/768/1024只验证无回归。
- 不安装插件、不覆盖WooCommerce模板、不创建第二循环、不改缓存配置。
- 不恢复#120～#130，不修改商品、Variation、价格、库存、分类、term、属性归档或缺货设置。
- 不触碰Staging/Production、支付、物流、税费、订单、DNS或真实客户数据。

## 当日最多3项验收结果

1. [x] 1440px及`>=1200px`边界下，Shop和商品分类具有Categories、Price、Size、Shade常驻侧栏，仍复用WooCommerce原生主查询。
2. [x] 属性/价格组合、排序、分页、无效与空结果、URL白名单、`noindex, follow`及基础归档Canonical通过；Day49 robots P2关闭。
3. [x] 390/768/1024没有提前出现移动筛选UI或布局回归；1440键盘Focus、44px目标、长文本、错误态和空态通过，最终无P0～P3。

## 7个专注周期执行记录

| 周期 | 目标 | 实际结果 |
|---|---|---|
| C1 | 写前基线与robots P2 | 回读版本、商品、Variation、属性设置、lookup、Trash、页面Head和D49参数合同；确认只改Local运行代码 |
| C2 | URL白名单与分页归零 | 建立价格、Size、Shade与六个Woo排序值的集中清洗；分类、属性、价格、排序、分页均不复制未知参数或旧分页 |
| C3 | Categories、Price、Size、Shade语义输出 | 分类使用`wp_list_categories()`；价格为可见label的GET表单；属性复用`WC_Widget_Layered_Nav`并补选中状态语义 |
| C4 | PC侧栏与结果布局 | 单一DOM默认隐藏，75rem起切换240px＋结果区Grid；移除Storefront全站Sidebar但保持小屏结果区全宽 |
| C5 | 查询、排序与空结果联调 | 基础、属性、价格、组合、非法term、反向区间、分类、排序与商品搜索隔离通过；空分类保留Categories＋Price |
| C6 | 自动化与四宽浏览器验证 | 390/768/1024/1440、1199/1200边界、键盘Focus、错误ARIA、0横溢出、0页面Console错误及Head矩阵通过 |
| C7 | 独立Review、减法审查与修复 | 修复排序API Fatal、数组输入Fatal、未知参数传播、孤立`query_type`、未来属性robots、重复勾选和分页URL泄漏；三路终审无P0～P3 |

## 实施结构与关键取舍

### 展示职责留在子主题

新增`app/public/wp-content/themes/dentall/inc/catalog-filters.php`，由`functions.php`加载。模块只负责：

- 判断Shop/商品分类请求；
- 生成分类、价格、Size、Shade筛选结构；
- 在Woo模板渲染窗口内提供经过清洗的`$_GET`，结束后用`finally`恢复；
- 清洗分类、属性、排序和分页生成的URL；
- 输出并闭合侧栏＋结果区布局；
- 将空价格字段302收敛为不含空键的第一页URL。

没有覆盖Woo模板，也没有改写`WP_Query`。`WC_Widget_Layered_Nav`和Woo当前主查询继续承担属性term、AND/OR、lookup联接和产品结果；这让D49合同、D46分页和D45排序保持同一数据链。

### SEO职责留在站点级插件

`dentall-core/includes/seo-compatibility.php`新增晚优先级`wp_robots`过滤器。它只在非搜索的Shop/商品分类判断价格、任意`filter_*`或`query_type_*`键，并把结果改为`noindex, follow`。没有修改Yoast内部presentation，因此Yoast仍输出去筛选参数后的基础归档Canonical。

使用“参数键存在即noindex”，而不是只识别当前Size/Shade有效值。这样空值、非法term、Package Quantity直达参数和未来未知属性不会形成可索引重复页；普通`foo`或仅排序页仍沿用原索引合同。

### Mobile First与无JavaScript

筛选DOM在所有目标页面只输出一份，默认`.dentall-catalog-filters { display: none; }`；`@media (min-width: 75rem)`才显示，并把布局切成`minmax(13rem, 15rem) minmax(0, 1fr)`。390/768/1024不复制或重排筛选控件，D51可在重新确认后决定移动交互。

属性项是普通链接，价格是原生GET表单；没有加载脚本、远程请求或新前端资源。选中属性用可见勾选、背景、文字状态及`aria-current`共同表达，不只依赖颜色。

## 查询与URL验证矩阵

| 场景 | 结果 |
|---|---|
| Shop/分类基础页 | #44、#46；正常集合输出4组筛选 |
| Size Small / Shade Medium | 均只返回父商品#46 |
| Size Small或Large | 返回#46一次，符合同属性OR |
| Size Large＋Shade Medium | 返回#46，符合D49父商品级语义 |
| Price 25～40 | 返回#46，SQL使用价格lookup |
| Small＋Light＋35～45 | 返回#46，属性与价格lookup共同参与 |
| 无效Size term | 0结果，侧栏与Woo原生空态保留 |
| Price 50～10 | 0结果，并显示明确可访问错误 |
| `orderby=price` / `price-desc` | 分别为[44,46] / [46,44] |
| Package Quantity直达参数 | 没有UI入口；底层请求安全，页面noindex |
| 商品搜索 | 返回原结果且0个D50筛选面板，D47合同不变 |
| 空分类#24 | 原生0结果；仅输出Categories、Price，不展示必然为0的属性选项 |

- 属性请求SQL实际使用`wp_wc_product_attributes_lookup`，价格请求使用`wc_product_meta_lookup`。
- 单个Small的取消链接精确为`/shop/`；多选取消保留剩余term、对应`query_type=or`及合法排序。
- 分类、属性、价格和排序变化不传播`paged`、旧`/page/{n}/`、`foo`、数组参数或非法排序。
- 独立Review用临时只读`loop_shop_per_page=1`检查真实分页输出；`foo`泄漏最初被列为P2，改用最终`paginate_links`过滤后，第2页只保留规范化价格、有效属性和合法排序。
- 空价格在正常Shop/分类和`paged=1`返回302，带`X-Redirect-By: DentAll`及no-store，移除空值、未知参数和旧分页；有效筛选/排序保留。
- 价格只接受最长64字节且不超过Woo当前两位有效小数精度的普通非负十进制；额外尾随0可接受。`1e2`、负数、逗号、非标量、超长值或`49.994`统一302并移除无效键，不让Woo原始浮点解释与后续规范URL产生不同结果。

## SEO、浏览器与可访问性证据

### Head矩阵

| 请求 | robots | Canonical |
|---|---|---|
| 基础Shop | `index, follow` | `/shop/` |
| Size、Size＋Shade、价格、非法term、反向价格 | `noindex, follow` | `/shop/` |
| 商品分类＋筛选 | `noindex, follow` | 当前分类基础URL |
| Package Quantity / `filter_future` | `noindex, follow` | 当前基础归档 |
| 仅`foo`或仅合法`orderby` | `index, follow` | 基础Shop |
| 商品搜索 | `noindex, follow` | 无Canonical；D50回调直接返回原robots |

所有场景只有一组robots输出；参数URL不进入Yoast Sitemap。Local没有页面缓存/CDN，因此只冻结非Local必须区分参数缓存键的要求，未宣称缓存已验。

### 四端与状态

| 宽度 | 商品列 | D50侧栏 | 结果 |
|---:|---:|---|---|
| 390 | 2 | 隐藏 | 无横向溢出、无可聚焦筛选控件 |
| 768 | 2 | 隐藏 | 既有列表与工具栏无回归 |
| 1024 | 3 | 隐藏 | 既有网格无回归 |
| 1440 | 4 | 显示 | 240px侧栏＋结果区，无横向溢出 |

1199px实测隐藏，1200px实测显示，断点没有间隙。1440下筛选项最小高度44px，价格字段具备可见label；反向区间有`role="alert"`及两项`aria-invalid="true"`；键盘Focus为既有3px蓝色轮廓。已选属性有可见勾选、`aria-current="true"`及“activate to remove”说明。筛选链接和商品标题运行时`overflow-wrap:anywhere`，当前样本无溢出；未为长term修改数据，因此超长真实term仍是正式内容阶段的验证边界。页面Console warning/error为0，浏览器控制工具的Statsig超时不属于DentAll站点日志。

## 防误改与运行基线

| 对象 | 最终结果 |
|---|---|
| 发布商品 | 仍仅#44、#46 |
| #44 | Simple；29.99/24.99；库存8；分类#18不变 |
| #46 | Variable；最低价39.99；分类#18不变 |
| #51/#52/#53 | 价格、库存及Size/Shade组合不变 |
| #120～#130 | 11/11继续Trash |
| 属性归档 | Package Quantity、Size、Shade均继续false |
| 查询表 | enabled；7行、父商品[44,46] |
| 更新策略 | Direct=yes；Optimized=no |
| 缺货设置 | hide out of stock=no |

本轮没有任何商品、Variation、term、库存或配置写入；数据库只承接正常只读前台请求，没有重建D49查询表。

## 独立复核与问题关闭

- Code/架构Review检查模块职责、Woo公开扩展点、URL生成窗口、状态恢复和复杂度。
- Test/数据Agent独立重跑主查询、SQL、DOM、URL、Head、302、数组输入及商品/设置不变量。
- UX/SEO Agent独立检查390/768/1024/1440、1199/1200、44px、Focus、错误态、长文本、Console、robots和Canonical。
- Review期间发现并关闭：不存在的排序API导致Fatal、数组属性触发Layered Nav TypeError、科学计数法显示/查询不一致、未知参数由排序/分页传播、移除最后term留下孤立`query_type`、Package Quantity直达页仍可索引、Storefront原生标记与自定义勾选重复、分类当前态只靠颜色等问题。
- 最终结论：三路独立复核均为P0=0、P1=0、P2=0、P3=0。

已接受但不是缺陷的边界：

- 空分类无可用term时，Woo原生Layered Nav不输出Size/Shade，避免展示必然为0的选择；侧栏仍有Categories与Price。
- 人工构造的越界`/page/9/?min_price=`先命中D46真实404，因此不执行空价格302；正常UI不会传播旧分页。
- 未验证正式12+商品的筛选分页、Production缓存/CDN、非Local环境及正式业务数据；这不阻塞Local通用骨架，但不等于生产就绪。

## 修改文件与减法审查

运行文件：

| 文件 | 变化 | 保留理由 |
|---|---|---|
| `themes/dentall/inc/catalog-filters.php` | 新增416行、21个小函数 | 集中Shop/分类筛选输出、输入白名单、URL与Woo渲染窗口；避免把独立职责堆进主入口 |
| `themes/dentall/functions.php` | 新增1行加载 | 子主题主入口只加载职责模块 |
| `themes/dentall/assets/css/catalog.css` | 相对D50前233行净增166行；相关区段30个规则/媒体块 | 单一DOM、75rem断点、侧栏、价格表单、状态与现有网格兼容 |
| `plugins/dentall-core/includes/seo-compatibility.php` | 新增42行、1个函数 | robots是跨主题站点SEO规则，不能放展示主题 |
| `themes/dentall/style.css` | 0.25.0→0.26.0 | 使浏览器资源版本随主题变更失效 |
| `plugins/dentall-core/dentall-core.php` | 0.2.6→0.2.7 | 标记SEO行为变化 |

相对写前快照，运行源码净增约625行，新增1个运行文件、22个函数、0模板覆盖、0JavaScript、0插件/依赖、0第二查询、0Cron/远程请求。行数主要来自四类不可省略边界：输入/URL白名单与状态恢复、Woo原生Widget适配、可访问表单/错误态、仅PC显示的响应式样式。最终减法审查已删除不可达非法价格表单分支、重复term校验、无收益通用封装、`!important`和原生/自定义双重标记；没有为D51～D53预实现抽屉、Chips、Reset、计数或品牌。模块偏长但保持单一“目录筛选展示与URL适配”职责，继续拆成微型文件会增加加载与追踪成本；若D51引入独立交互生命周期，再按职责重新评估拆分。

静态验证：

```powershell
php -l app/public/wp-content/themes/dentall/inc/catalog-filters.php
php -l app/public/wp-content/themes/dentall/functions.php
php -l app/public/wp-content/plugins/dentall-core/includes/seo-compatibility.php
php -l app/public/wp-content/plugins/dentall-core/dentall-core.php
git diff --check
```

四个PHP文件均无语法错误，`git diff --check`通过。

收尾时额外执行一次WP-CLI只读回读：文件系统可确认WordPress 7.0.4，但Local数据库端点当时拒绝连接。为保留现有停机状态，本轮没有擅自重启服务；上文商品、设置、HTTP、DOM与Head结论来自停机前已完成并由独立Agent复核的实测证据。该前置已在[[Day51-手机与平板筛选抽屉]]开始时通过登录态Local在线验收关闭。

## 回滚

1. 从`functions.php`移除`inc/catalog-filters.php`加载并删除该新文件。
2. 从`catalog.css`移除D50侧栏/结果区规则，主题版本回到0.25.0。
3. 从`seo-compatibility.php`移除D50 `wp_robots`回调，DentAll Core版本回到0.2.6。
4. 清理Local页面/对象缓存后复测基础Shop、分类、商品搜索、排序、分页、Canonical与robots。

回滚不需要修改商品、Variation、属性查询表、term、URL、支付、物流或订单。移除SEO回调会让筛选参数页重新变成`index, follow`，因此若侧栏仍可产生参数链接，不能只单独回滚插件部分。

## 数据、URL、SEO、缓存与部署影响

| 检查面 | 结论 |
|---|---|
| 数据 | 0商品/Variation/term写入；D49 lookup和设置保持原值 |
| URL | 没有改Slug或固定链接；新增站内筛选参数链接、空价格302和白名单化分页/排序链接 |
| SEO | 参数页已`noindex, follow`且Canonical回基础归档；Sitemap成员不变 |
| 缓存 | 没有改Local缓存；非Local必须按分类、筛选、排序、分页区分或绕过，尚未验 |
| 性能 | 每个正常双属性侧栏在当前样本增加4条Woo term查询；主商品查询仍使用lookup。无正式规模对比，不宣称性能零影响或提升 |
| 支付/物流/订单 | 无影响；未触碰购物车、结账、支付、税费、运费、订单或退款 |
| 部署 | 仅Local工作树；Staging/Production没有代码、设置、缓存或抓取变化 |

## D51衔接

D51开始时仍先只读梳理并提交功能确认单。若实施移动筛选，推荐复用本日同一筛选DOM与参数合同，只新增明确的开关、抽屉、焦点进入/返回、Escape、滚动锁和390/768/1024适配；不得复制第二套筛选控件或顺手加入品牌、计数、Chips和Reset。正式商品与缓存规模不足仍分别影响内容/性能验收，不阻塞通用移动交互骨架。

## 可复用核心思想

### 跨平台不变量

筛选不是一组视觉控件，而是“输入白名单—集合查询—可恢复URL—索引策略—缓存隔离”的完整合同。所有会制造URL的出口必须共享同一白名单；只清理侧栏而遗漏排序或分页，就会继续复制未知参数。可见隐藏也不能替代请求身份判断，否则搜索、其他归档或辅助循环会意外继承行为。

### WordPress/WooCommerce当前实现

DentAll在WooCommerce 11.0.0中让主查询读取`min_price`、`max_price`、`filter_*`和`query_type_*`，用原生Layered Nav生成属性链接；子主题通过Action/Filter适配结构和URL，`dentall-core`通过晚优先级`wp_robots`治理索引。模板渲染期间临时改变`$_GET`时必须用`try/finally`恢复，因为全局请求状态会被后续组件继续读取。

### Shopify或其他平台的对应机制

其他平台同样需要明确集合、商品/变体语义、过滤参数、Canonical/robots和缓存键；但Woo Widget、PHP全局变量、WordPress Hook及lookup表都是平台特定实现。Shopify Collection过滤、Search & Discovery、URL参数和变体可用性语义在DentAll未实际验证，标记为待验证，不进入本项目实施范围。
