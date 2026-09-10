# 部署、运维与恢复手册

> 当前Staging已确定使用Cloudways Flexible；标准代码部署采用Cloudways Via Git与代码专用部署分支。Production部署规则在正式上线准备阶段冻结。

## 环境信息

| 项目 | Local | Staging | Production |
|---|---|---|---|
| URL | LocalWP本地域名（以应用内显示为准） | 待确认 | 待确认 |
| 主机 | 本机 | Cloudways Flexible / DigitalOcean Premium 4GB | 待确认 |
| PHP/数据库 | PHP 8.2.29 / MySQL 8.4.0 | PHP 8.2.33 / MySQL 8.4 | 待确认 |
| 部署方式 | 本地开发 | Cloudways Via Git：`deploy/staging` → `public_html/` | 待确认 |
| 日志位置 | `app/public/wp-content/debug.log`及LocalWP站点日志 | 待确认 | 待确认 |
| 备份位置 | `backups/`（仅本地基线，不作为唯一副本） | Cloudways应用异地备份：每日一次、保留一周；重要批量写入前On-Demand Backup | 待确认 |

## Staging代码部署边界

- 完整项目历史保存在`main`；Cloudways只部署代码专用分支`deploy/staging`。
- 部署分支根目录必须从`wp-content/`开始，只包含DentAll自定义主题、`dentall-core`和已确认的mu-plugin。
- Cloudways仓库地址使用SSH；Deploy Key只读，不允许Cloudways向GitHub推送。
- Deployment Path固定为`public_html/`；首次部署前必须检查恢复点并核对部署分支文件清单。
- 数据库、uploads、WordPress核心、WooCommerce和第三方插件不通过Git同步。
- SFTP只用于只读排查、紧急回滚或已批准的应急发布；应急修改必须回补Local和Git，避免服务器漂移。
- 后续GitHub Actions＋rsync方案只有在密钥、目录白名单、删除策略、人工批准和回滚测试全部完成后才可替代当前流程。

### 2026-09-10首轮完整同步基线

| 项目 | 已冻结值 |
|---|---|
| 冻结运行代码源 | `97ebdc3637a313d53e10abdbc5dc181b99f69fef`；后续纯文档提交可推进`main`分支头，但不得改变下列两个运行子树或目标清单指纹 |
| 运行子树 | DentAll主题tree `27852ee731af90a3644130aca25612a196e7c3eb`；DentAll Core tree `39b316ed03c32f1842d4ea7528b74a592c090529` |
| 上次已部署基线 | `501e5e5fc2e8a800f637a7fd6b5e6a2d0947c8c6`；生成候选后`deploy/staging`分支头会前进，不能再用分支头等于该SHA作为检查条件 |
| 历史关系 | 两分支没有共同祖先，禁止直接merge或使用`--allow-unrelated-histories` |
| 路径映射 | `main:app/public/wp-content/...` → `deploy/staging:wp-content/...` → `public_html/wp-content/...` |
| 目标版本 | DentAll `0.41.0`；DentAll Core `0.2.9` |
| 目标清单 | 28文件、385,771字节；PHP 13、CSS 6、JS 4、SVG 3、PNG 1、TXT 1 |
| 清单指纹 | 按目标路径排序，以`<Git blob SHA-1><两个空格><目标路径><LF>`组成UTF-8文本，其SHA-256为`9912fcce92c95dd269c8657f3de23c730816586f75fb40889ffd4bf729774fd7` |

相对当前部署基线，状态前缀`A/M/=`分别表示新增、更新、内容不变。以下是唯一允许进入发布分支的28个文件：

```text
M wp-content/plugins/dentall-core/dentall-core.php
= wp-content/plugins/dentall-core/includes/admin-access.php
= wp-content/plugins/dentall-core/includes/media-policy.php
= wp-content/plugins/dentall-core/includes/product-governance.php
= wp-content/plugins/dentall-core/includes/roles.php
M wp-content/plugins/dentall-core/includes/seo-compatibility.php
A wp-content/plugins/dentall-core/includes/shipping-quote.php
M wp-content/plugins/dentall-core/readme.txt
A wp-content/themes/dentall/assets/css/cart.css
A wp-content/themes/dentall/assets/css/catalog.css
A wp-content/themes/dentall/assets/css/homepage.css
A wp-content/themes/dentall/assets/css/product-detail.css
A wp-content/themes/dentall/assets/css/site-shell.css
A wp-content/themes/dentall/assets/images/icon-cart.svg
A wp-content/themes/dentall/assets/images/icon-user.svg
A wp-content/themes/dentall/assets/images/logo-placeholder-v2.png
A wp-content/themes/dentall/assets/images/trust-icons.svg
A wp-content/themes/dentall/assets/js/cart-header-sync.js
A wp-content/themes/dentall/assets/js/catalog-filters.js
A wp-content/themes/dentall/assets/js/product-variation.js
A wp-content/themes/dentall/assets/js/shipping-quote.js
M wp-content/themes/dentall/functions.php
A wp-content/themes/dentall/inc/catalog-filters.php
A wp-content/themes/dentall/inc/homepage.php
A wp-content/themes/dentall/inc/setup.php
A wp-content/themes/dentall/inc/site-footer.php
A wp-content/themes/dentall/inc/storefront-hooks.php
M wp-content/themes/dentall/style.css
```

发布源必须读取冻结提交的Git对象，不能递归复制Local文件系统。当前Local另有4个未被`main@97ebdc3`跟踪的导入相关文件，全部禁止进入发布包：

```text
app/public/wp-content/plugins/dentall-core/assets/js/product-import-guard.js
app/public/wp-content/plugins/dentall-core/assets/templates/dentall-products-v1.csv
app/public/wp-content/plugins/dentall-core/includes/product-import-schema.php
app/public/wp-content/plugins/dentall-core/includes/product-import.php
```

本轮没有受控DentAll mu-plugin。不得因白名单为空而删除Cloudways或其他组件已有的`mu-plugins`。WordPress Core、Storefront、WooCommerce、Yoast、Breeze、Object Cache Pro及其他第三方插件、`uploads/`、数据库、语言包、缓存、日志、备份、配置、凭据、项目文档和测试证据均不进入发布包，也不得被部署命令批量删除。

### 删除语义与服务器漂移硬闸门

相对`501e5e5`必须移除以下4个旧独立主题模板；其前置Git blob值用于确认服务器文件没有未登记漂移：

```text
e3bb5602131e838769a4ef72deefbec0db8c0f79  wp-content/themes/dentall/footer.php
2c2ef3422f47df7845698c693c5e286c2e7dbee8  wp-content/themes/dentall/front-page.php
37db0123a745ba9bfc06f408ba6443f0400e7a8b  wp-content/themes/dentall/header.php
e84448d97b2fa176fe2fbcff5eacf3f1b67d39ff  wp-content/themes/dentall/index.php
```

这4项清理清单按相同格式生成的SHA-256为`4caa85ea4b852f76fdd649bc109760b75178f4c102b65783abfdab3061d23cb8`。Cloudways Via Git当前文档明确说明：源仓库中不存在的文件不会自动从部署目录删除。因此发布分支中的4个删除记录不等于服务器文件已经消失，必须执行一次受控清理：

1. 先确认活动主题基线精确为`stylesheet=storefront`、`template=storefront`，并确认目标`dentall/style.css`声明`Template: storefront`；若现场`stylesheet=dentall`或为其他值，停止并进入单独切换/回滚分支。
2. 在On-Demand应用备份成功后，逐个核对上述4个精确路径与Git blob；任一内容不符都按服务器漂移停止，不覆盖也不删除。
3. 把4个文件移动到已解析且确认位于该应用根目录、但位于`public_html`之外的本次发布隔离目录；隔离目录只用于快速恢复，不替代Cloudways备份和Git。
4. 禁止使用通配符、递归删除或对整个`wp-content`执行`--delete`。服务器第一方目录若存在其他未知文件，先登记路径、大小和哈希，再单独评审。
5. Pull后再次核对两个DentAll目录：4个旧模板不存在、28个目标文件全部存在且与冻结Git对象一致、没有未批准的额外文件，才允许激活子主题。

[Cloudways Via Git说明](https://support.cloudways.com/en/articles/5124087-how-to-deploy-code-to-your-application-using-git-on-cloudways-flexible)是本节“不自动删除”的事实依据。

### 写入Staging前的现场预检

在任何会引导WordPress的HTTP或WP-CLI命令之前，先从已授权的只读数据库入口查询实际表前缀下的`theme_switched`与`theme_switched_via_customizer`。任一option有真值都立即`NO-GO`，本轮不触发请求去消费它。若需归一化，必须转入独立授权的主题状态修复窗口：先取得应用级文件＋数据库备份，再由实际加载目标主题和正常插件集的非skip请求完成生命周期；验证两项无真值及菜单/Widget/重写规则后，从头重跑本节全部只读预检。查询结果和证据不得包含数据库凭据。

以下任一项未通过即为`NO-GO`，只记录结果，不开始备份后的写入：

1. 冻结运行代码提交不可读取、当前`main/origin/main`不再包含该提交、两个运行子树或清单指纹发生变化，或最终部署候选相对上次已部署基线不再精确得到19项新增、5项更新、4项不变、4项删除、最终28文件。文档提交改变`main`分支头不构成运行代码变化。
2. Staging的Password Protection、全站`noindex`、HTTPS或真实支付关闭边界失效。
3. `wp_get_environment_type()`不是精确的`staging`；尤其不得为`local`，否则Local专用TEST公告、Newsletter、Logo和Trust数字可能输出。
4. 现场PHP、WordPress、WooCommerce、Storefront或Yoast版本与已验证基线不同但尚未做兼容性分析；已验证Local基线为PHP 8.2.29、WordPress 7.0.4、WooCommerce 11.0.0、Storefront 4.6.2、Yoast 28.2。
5. Storefront父主题缺失、损坏或不兼容；当前活动主题、DentAll安装状态与历史记录不一致但未解释。
6. 两个DentAll第一方目录存在未知文件、已修改文件或权限异常；禁止把服务器漂移静默纳入发布包。
7. Cloudways可用磁盘空间不足。按其当前恢复文档，恢复前建议至少保留应用数据约2.5倍的可用空间。
8. 网站人员未进入短暂写入冻结期，或备份点之后仍可能继续编辑商品、文章、媒体、菜单、订单及配置。
9. `dentall_core_role_version`不是精确的`7`。虽然0.2.6与0.2.9的角色常量及`roles.php`相同，版本不一致仍可能让Core在首次加载时写入角色数据库，必须另行评审和授权；版本为7时，先保存Website Manager/Content Editor两组排序后capability清单与哈希，Pull后要求与该现场前值完全一致。
10. 尚未准备在主题或插件Fatal时可用的SSH、Git/文件级恢复和Cloudways恢复入口；或在切换哨兵确认无真值且当前活动主题＋正常插件集可稳定加载的前提下，尚未确认WP-CLI能以`--skip-plugins --skip-themes`运行。PHP/应用错误日志的精确路径和读取权限也必须先验证。
11. 上述最先执行的只读数据库检查不能完成，或`theme_switched`/`theme_switched_via_customizer`任一仍有真值。它们是WordPress主题切换生命周期哨兵，不是应回灌的持久配置；此处只判`NO-GO`，不在备份前引导WordPress去消费。

现场只读快照至少登记：

- Staging URL、`home/siteurl`、环境类型、PHP/数据库/WordPress版本，以及全部活动插件和主题版本。
- `stylesheet`、`template`、`current_theme`、`theme_mods_storefront`、`theme_mods_dentall`和`sidebars_widgets`；每个option同时记录“原本存在/不存在”与完整值，不能把不存在和空数组混为一谈。活动主题必须通过WordPress主题API/WP-CLI切换，`stylesheet/template/current_theme`只用于验证结果，不以原始option写入代替主题激活。
- `theme_switched`和`theme_switched_via_customizer`只做诊断快照：备份前只读预检必须已是无真值，否则停止；不得用“检查请求”顺手消费。获准主题切换后，才由实际加载目标主题及正常插件集、不带`--skip-themes`/`--skip-plugins`的请求完成生命周期，两项随后仍应为无真值。它们不纳入配置回填；回灌旧真值会再次触发`after_switch_theme`、刷新重写规则，并可能重新映射菜单或Widget，在跳过主题注册的引导中消费也可能得到错误映射。行为依据见[WordPress `check_theme_switched()`参考](https://developer.wordpress.org/reference/functions/check_theme_switched/)。
- `post_type=custom_css`中与`storefront`、`dentall`关联的记录ID、状态、修改时间、内容哈希及私密恢复副本。旧DentAll 0.1.0若曾激活，同一stylesheet slug留下的Customizer CSS会在0.41.0激活时重新生效，必须在激活后检查实际输出CSS。
- 全部菜单对象ID/slug/项数及两个主题的`nav_menu_locations`。WordPress切换主题时会按相同slug和推测规则自动映射菜单；`primary`预期可能自动命中，`secondary`必须保持未绑定，`handheld`必须未注册，前台只能有一棵主导航。发布窗口不得创建、删除、改名或改写菜单项来处理位置映射。
- `show_on_front`、`page_on_front`、`page_for_posts`、首页的`_wp_page_template`，以及Shop、Cart、Checkout、My Account页面ID。
- 货币、销售/配送国家、税设置、Shipping Zone/Rate、支付网关状态和`dentall_shipping_quote_email`是否为空或有效；记录状态即可，不输出邮箱明文。
- `woocommerce_attribute_lookup_enabled`、Direct/Optimized Updates、查询表存在性/行数、缺货隐藏设置及`product_brand` taxonomy可用性。
- Yoast商品分类两个模板、品牌归档索引规则、全站robots/Sitemap/Canonical现状。
- `dentall_core_role_version`及Website Manager/Content Editor角色capability的完整排序清单与哈希；本次客观基线就是Pull前现场值，Pull后逐项/哈希必须完全相同。仅记录能力名和布尔值，不记录用户凭据。
- 发布、草稿、回收站的Page/Post/Product/Variation/Media/Order计数及最近更新时间，作为部署前后数据不变量基线。
- 当前页面缓存、对象缓存、Varnish/CDN及其清理入口；不得用Local缓存结论代替现场结果。

配置必须逐项对比并记录旧值，不得通过整库复制同步：

| 配置 | 当前候选目标 | Staging处理边界 |
|---|---|---|
| 活动主题 | DentAll子主题，`template=storefront` | 先记录现场值并验证父主题，再由明确步骤切换；失败恢复现场值 |
| 菜单位置 | `primary`、`footer`、`homepage_categories`、`homepage_solutions`；`secondary`未绑定；`handheld`未注册 | 使用Staging现有对象ID；先让WordPress完成主题切换，再精确覆盖目标主题的location映射；不复制Local的TEST菜单ID，也不创建/删除/改名菜单项 |
| 首页模板 | `template-homepage.php` | 只核对并定点设置现有首页Page，不复制Page内容或ID |
| Storefront Sticky | `storefront_sticky_add_to_cart=false` | 先读`theme_mods_dentall`；仅在批准重放后写入并保留旧值 |
| Woo属性查询表 | Enabled=`yes`、Direct Updates=`yes`、Optimized Updates=`no` | 必须先核对真实表、属性和产品量；需要重建时作为独立数据库操作执行并留恢复值 |
| 商品分类SEO模板 | `title-tax-product_cat=%%term_title%% %%page%% %%sep%% %%sitename%%`；`social-title-tax-product_cat=%%term_title%%` | 只修改`wpseo_titles`两个精确键，保留整项旧值/哈希并验证实际Head |
| 品牌归档 | 第一版`noindex`且不进Sitemap | 先确认当前Yoast版本使用的真实键；不得猜键或复制整个Yoast option |
| 报价邮箱 | 空值，或企业控制且验证有效的邮箱 | 空值允许技术部署但不通过D72业务验收；日志和Git只记录“空/有效”，不记录明文 |
| 销售国家、运费、税费、支付 | 保持Staging现场已批准状态 | 绝不从Local复制；真实规则继续由业务/税务节点单独确认 |

### “网页无TEST字眼”发布门槛

- 相同代码包应进入各环境，不为Staging手工剥离Local专用代码或图片；环境隔离由`wp_get_environment_type()`和配置承担，避免形成不可追踪的环境分叉。
- **代码输出硬闸门：** 源码中的`[TEST]`、Newsletter预览、占位Logo和未经证明的Trust数字均受`local`环境判断保护；它们在Staging实际HTML中必须为0。CSS的`::placeholder`及WooCommerce缺图class是技术词，不是用户文案。
- 代码扫描不能证明数据库和缓存没有TEST内容。写入前须只读检查已发布Page/Post/Product、分类/标签/品牌、菜单项、Widget、站点标题/副标题、摘要/正文、图片alt/caption和SEO字段中的`TEST/test`、`placeholder`、`lorem`及`example.com`等明确测试标记；正常英文中的“for example”不按测试内容误判。
- **`GO-TECH`技术Staging：** 既有、已登记的TEST对象可以在Password Protection＋全站`noindex`持续有效时保留并用于Simple、Variable和RSK回归；必须列出对象ID、状态、入口和清理责任，不得冒充正式内容。该口径不等于“网页无TEST字眼”或可交给业务浏览。
- **`GO-PRESENTATION`展示Staging：** 用户已明确要求最终网页不出现TEST字眼。技术回归完成后，必须使用网站人员现有正式内容复跑关键页，并让发布态、菜单可达、Sitemap可见、缓存可读和页面HTML中的明确测试标记为0；Draft、Private和Trash中的登记夹具可保留。
- TEST对象撤稿、取消菜单位置或替换为正式内容属于单独、可逆的内容变更单，不得隐藏在代码发布步骤中，也不得创建/删除/改名菜单项来修复主题映射。先只读列出精确对象和替代样本；若没有可验证的正式Simple/Variable等必要样本，再向用户确认内容处置，不擅自永久删除或改写网站人员录入的商品。
- 激活后以匿名和登录态覆盖Home、Shop、商品分类、商品搜索、Simple、Variable、Cart、Checkout、My Account、404和已登记直接URL。技术回归可使用登记TEST路由；展示验收必须改用正式路由，并在缓存清理前后复核页面文本、`alt/title/aria-label`及HTML源。

正式报价邮箱不是生成代码包的前置条件：空值会安全禁用Cart邮件入口，但Core仍会阻止实体商品普通Checkout。因此空邮箱允许完成受保护Staging的代码部署，不允许把D72报价流程标记为业务验收通过；要验收该流程时必须配置企业控制的有效收件箱并完成真实邮件客户端测试。

### 首轮完整同步执行顺序

Cloudways没有承诺把整个目录原子切换。当前活动Core入口更新后会立即`require`新增`shipping-quote.php`，所以单次Pull最终快照存在“入口已更新、依赖尚未落盘”的短暂Fatal风险；网站人员停止编辑不能阻止HTTP读请求、WP-Cron或健康检查。本轮默认采用两阶段兼容部署，若无法分两次Pull，就必须先取得原子部署证据，或建立能真正隔离WordPress请求/Cron且不依赖当前插件与主题的恢复入口，否则`NO-GO`。

1. 从冻结运行代码Git对象在上次已部署基线`501e5e5`之上生成两个连续候选提交，不merge、不读取未跟踪文件：A“依赖预置”只新增19个文件，保留旧入口、旧样式及4个旧模板；B“切换”再更新5个文件并在Git中删除4个旧模板，最终精确为28文件。
2. 记录A/B两个SHA。分别验证A相对基线只有19A，B相对A只有5M/4D，B相对基线为19A/5M/4=/4D；对最终树运行PHP 13/13 lint、JS 4/4语法、人工报价PHP 36/36、JS 23/23、`git diff --check`、路径守卫和三个清单指纹。候选先保存在独立审阅分支，不提前推进远端`deploy/staging`。
3. 完成现场只读预检、TEST对象分层盘点、恢复命令只读演练与日志路径确认，通知网站人员进入短暂写入冻结；记录冻结开始时间和最后一条内容、订单、媒体及配置修改时间。
4. 在Cloudways对该应用执行On-Demand Backup，必须覆盖Web文件和数据库；等待成功并记录UTC恢复点、完成状态与Last Backup Date。仅看到“已开始”不算完成。
5. 只把远端`deploy/staging`快进到A并执行第一次Pull。核对19个新增依赖均已落盘且哈希正确，同时Core仍为0.2.6、DentAll仍为0.1.0、4个旧模板仍在；这一步不应改变运行入口。若异常，停止，不推进B。
6. 保持`stylesheet=storefront/template=storefront`，按“删除语义”把4个旧模板移出`public_html`，保留隔离目录和哈希记录。
7. 再把远端`deploy/staging`快进到B并执行第二次Pull。新增依赖已经存在，因此入口更新不会引用缺失文件；仍需保持访问保护、观察Cron/健康检查和PHP日志，直到核对最终28文件及4个旧模板不存在。
8. 继续保持Storefront活动，先验证Core 0.2.9可加载、角色版本/能力未改变、后台无Fatal，并单独验证人工报价交易闸门的预期行为；再确认DentAll 0.41.0被识别为Storefront子主题、父主题完整。
9. 保存上述持久主题配置及切换哨兵的诊断快照后，通过WordPress主题API/WP-CLI激活DentAll。使用实际加载DentAll及正常插件集、不带`--skip-themes`/`--skip-plugins`的受保护HTTP请求完成一次WordPress加载，让`theme_switched`/`theme_switched_via_customizer`生命周期被消费并确认均无真值；若此时Fatal，停止请求并进入代码/主题回滚，不用隔离读命令强行消费。随后再精确核对/恢复`theme_mods_dentall`的location映射。`primary/footer/homepage_categories/homepage_solutions`按预定对象，`secondary`未绑定，`handheld`未注册，菜单对象及菜单项本身不变，首页Page继续使用`template-homepage.php`，前台只有一棵主导航。
10. 只完成主题切换所需的最小配置和缓存清理，先执行代码输出硬闸门、关键页面冒烟及RSK-035/037/038，作`GO-TECH/ROLLBACK`决定。`storefront_sticky_add_to_cart`、Yoast两个模板、品牌noindex、属性查询表重建/Direct Updates和报价邮箱分别作为可回滚配置变更单；现场值不一致时不与首轮代码切换捆绑写入。
11. `GO-TECH`之后再执行获准的逐项配置与TEST内容处置；使用正式内容完成最终页面复核，满足`GO-PRESENTATION`后才能按“Staging网页无TEST字眼”交接。每个配置或内容步骤都保留自己的旧值、数据计数和回滚结果。

[Cloudways单应用On-Demand Backup说明](https://support.cloudways.com/en/articles/5123364-how-to-backup-a-specific-application)确认应用备份覆盖`public_html`、`private_html`和数据库，并保存在异地存储。本轮必须使用该应用级备份，不只依赖每日计划备份。

### Fatal时的独立恢复入口

- 写入前记录Staging的绝对WordPress路径、WP-CLI可执行文件、SSH账号权限，以及Cloudways应用/PHP/Web错误日志的精确路径；不得把账号、主机密钥或邮箱明文写入Git。
- 只有最先执行的只读数据库检查已证明`theme_switched`与`theme_switched_via_customizer`均无真值，且当前活动主题＋正常插件集可稳定加载，才可使用现场绝对路径验证`wp --path=<public_html绝对路径> --skip-plugins --skip-themes core version`，并保存输出。普通WP-CLI命令即使带skip参数仍会引导WordPress，不能绕过这些前置检查。
- 仅在上述双前置条件成立时，才可通过完整WordPress栈执行预先核对的`wp --path=<绝对路径> theme activate storefront`或等价主题API，不带`--skip-plugins`/`--skip-themes`，以保留活动插件和旧/新主题可能注册的即时`switch_theme`回调。skip参数只授权用于上一项只读CLI/路径演练，不授权实际切换。激活动作新写入的主题切换生命周期仍须由后续非skip请求完成；Core或主题任一Fatal时不使用普通WP-CLI停插件或切主题，统一执行下一项WordPress外部代码恢复。
- 若任一切换哨兵有真值，或当前活动主题与正常插件集组合发生Fatal（包括Storefront仍活动但被Core Fatal阻断），禁止运行普通WP-CLI（无论是否带skip）。先从WordPress引导之外，使用已审核的Git前向回滚/精确文件恢复把当前栈恢复到可加载代码，再以非skip受保护请求完成可能存在的生命周期；当前活动主题若为DentAll，随后在正常引导下激活Storefront并再次完成生命周期。若精确代码恢复仍不能让当前栈安全加载，转入已评估增量损失的Cloudways Web Files、Database或Complete Restore，不用临时SQL猜改主题option。
- 紧急停用Core或切换主题只用于恢复管理入口，不等于完整回滚；随后仍要恢复文件、主题option、角色/菜单不变量、缓存并完成冒烟。若WP-CLI也不可用，使用Cloudways恢复入口，不直接编辑数据库猜值。

### 首轮同步回滚预案

回滚目标不是“页面看起来差不多”，而是回到现场快照：活动主题和数据库配置恢复原值；DentAll Core为`0.2.6`、旧DentAll为`0.1.0`；两个第一方目录恢复13个基线文件；4个旧模板恢复；19个本轮新增文件撤回；5个更新文件恢复旧内容；4个不变文件保持不变。

按风险从低到高处理：

1. **只回滚配置/主题：** 仅当28文件正确、Core可稳定加载且只剩映射异常，并经只读数据库检查确认两项切换哨兵无真值时，才用WordPress主题API/WP-CLI激活现场基线主题；再以实际加载该基线主题和正常插件集、不带`--skip-themes`/`--skip-plugins`的受保护请求完成一次引导，让自动映射及新切换生命周期结束。随后验证`stylesheet/template/current_theme`为预期，并精确恢复两个`theme_mods_*`、`sidebars_widgets`和本轮逐项配置的原始“存在状态＋值”。`theme_switched`和`theme_switched_via_customizer`必须已消费为无真值，禁止回灌旧真值。若已有truthy哨兵或目标主题不能安全加载，先进入第2项WordPress外部代码回滚；精确代码恢复仍无法加载时再评估Cloudways恢复，不运行会提前bootstrap的普通WP-CLI。菜单对象/菜单项保持不变，清缓存后复测；Core 0.2.9交易闸门是否保留必须另作结论。
2. **精确第一方代码回滚（代码故障首选）：** 无论停在A还是已经完成B，都先在当前`deploy/staging`历史上追加一个“tree内容恢复为`501e5e5`”的前向回滚提交，禁止reset/force-push，也禁止只在服务器删文件而让远端仍停在A/B。执行顺序按当前完整栈和切换哨兵分叉：只有当前活动主题与正常插件集可稳定加载、且两项哨兵均无真值时，才先走WordPress主题流程——DentAll活动则正常激活Storefront并以非skip请求完成新生命周期，Storefront已活动则无需重复切换——随后Pull回滚提交。只要任一哨兵truthy，或当前活动主题与正常插件集组合Fatal（包括Storefront仍活动但Core Fatal），就禁止先引导WordPress，直接从WordPress外部Pull回滚提交，使5个文件恢复旧内容、4个旧模板重新出现。基线代码使当前栈可安全加载后，以非skip受保护请求消费可能存在的生命周期；若当前活动主题仍为DentAll，再正常激活Storefront并以非skip请求完成新生命周期。若基线代码仍不能安全加载，停止并转Cloudways恢复。确认远端回滚提交、服务器Core入口0.2.6且不再引用`shipping-quote.php`后，才按全部`A`项发布哈希精确移除19个新增文件，因为Cloudways不会自动删除它们。该清单SHA-256为`23d298b6113cdb0631aaeb0aa448f9e17a757231b8b43cdf551a49baa47d6da7`；最终远端tree与服务器13文件必须同时匹配基线，禁止只“Pull旧代码”或只“服务器清理”就宣称完成。
3. **Cloudways Web Files恢复（有条件）：** `Web Files only`会恢复整个`public_html`和`private_html`，包括`uploads`、WordPress Core、父主题和第三方插件文件；它只是不恢复数据库。仅当能证明备份后没有媒体或其他Web文件写入，或已保存并能对账恢复这些增量时才使用。否则会产生数据库仍有新附件记录、实际文件却被回退等不一致。恢复后仍须定点恢复主题/配置数据库值。
4. **Database或Complete Restore（最后手段）：** 只有确认数据库被本轮改坏、无法定点恢复，且已经保存故障现场并评估恢复点之后的数据损失时才使用。即便在Staging，也不得为普通代码故障恢复整库而覆盖网站人员新录入的商品、媒体或订单。若写入冻结完整保持，可在书面记录“备份后无新写入”后评估；Complete Restore还会同时覆盖全部Web文件。

Cloudways Flexible当前支持Complete、Web Files only和Database only恢复；恢复点时间按UTC显示。恢复会创建恢复前回退点，且当前文档说明未手动删除时该临时回退点在24小时后自动移除，不能替代本轮独立On-Demand备份。详见[Point-in-Time Restore说明](https://support.cloudways.com/en/articles/5123320-how-to-do-a-point-in-time-restore-of-your-application)。

任何回滚都必须记录：触发时间、故障页面/订单、最后成功步骤、活动主题、代码版本、是否发生备份后写入、采用的恢复范围、缓存清理、日志和恢复后数据计数。回滚后至少验证环境保护、Home、Shop、Simple/Variable、Cart、Checkout阻断、Account、后台Website Manager权限和PHP日志，再解除写入冻结。

## 标准发布流程

1. 确认发布范围、版本号和变更记录。
2. 确认P0/P1缺陷为0，关键回归通过。
3. 备份生产数据库和uploads，并验证文件存在和可读取。
4. 记录当前Git标签、WordPress、WooCommerce、主题和插件版本。
5. 在Staging使用相同发布包完成部署和冒烟测试。
6. 进入已批准的生产发布窗口。
7. 部署代码；执行经过审核的数据库变化或安全搜索替换。
8. 清理应用、页面、对象和CDN缓存。
9. 验证首页、商品、购物车、结账、账户、邮件和支付回调。
10. 记录证据并观察日志、性能和错误。

## 回滚触发条件

- 无法浏览核心页面或完成下单。
- 金额、库存、订单状态或支付出现错误。
- 登录、权限或客户数据存在安全问题。
- 数据库迁移失败或数据不一致。
- 错误率持续上升且无法在发布窗口内安全修复。

## 回滚流程

1. 停止继续部署和写入性操作，记录故障时间。
2. 根据风险决定是否短暂启用维护页，避免新订单进入错误状态。
3. 恢复上一个稳定Git标签或发布包。
4. 仅在确认数据库受影响时恢复数据库备份；先保存故障现场副本。
5. 必要时恢复uploads快照。
6. 清理全部缓存并重新验证关键流程。
7. 记录根因、影响订单、处理人和后续修复计划。

### 角色权限变更的回滚规则

- DentAll Core的角色能力会写入WordPress数据库。仅回滚PHP文件或降低`DENTALL_CORE_ROLE_VERSION`，不能可靠撤销已经授予的能力；旧代码白名单仍包含该能力时还会再次授予。
- 若发布后需要撤销Website Manager的某项能力，必须从角色白名单移除该能力，并把`DENTALL_CORE_ROLE_VERSION`提升到新的、单调递增的版本，再按正常部署与角色审计流程发布。禁止通过把角色版本号从`6`降回`5`完成撤权。
- 紧急止血必须临时从角色对象移除能力，而不是使用只会处理用户直授权的`wp cap remove`。经过审核的WP-CLI可执行`wp eval "get_role( 'dentall_website_manager' )->remove_cap( 'wpseo_edit_advanced_metadata' );"`，随后立即以目标Website Manager账号确认`current_user_can( 'wpseo_edit_advanced_metadata' )`为`false`，并复测其他白名单能力未丢失。同一发布窗口内仍必须补上版本化白名单修复；插件重新激活或角色再次同步前，不得把这项临时撤权视为永久完成。
- D18 C6若需撤销`wpseo_edit_advanced_metadata`，回滚包应基于当前稳定代码创建新版本：从Website Manager白名单移除该能力、提升插件及角色版本、运行角色与越权审计后再部署；普通`0.2.3`代码回滚只用于撤销界面隐藏和商品导出逻辑，不承担高级SEO撤权。

## 常见故障检查顺序

### 白屏/500

1. 查看PHP和Web服务器错误日志。
2. 检查最近代码/插件/主题变更。
3. 在可控环境复现，禁止直接在生产反复试错。
4. 回滚最近发布或停用明确故障组件。

### 购物车丢失或结账异常

1. 检查页面缓存是否缓存了购物车、结账和账户。
2. 检查Cookie、Session、对象缓存和CDN规则。
3. 检查支付/运费插件日志和WooCommerce状态页面。
4. 使用匿名和登录用户分别复现。

### 订单邮件未送达

1. 确认订单状态是否触发相应邮件。
2. 检查WooCommerce邮件配置和SMTP日志。
3. 检查发件域名、SPF、DKIM、DMARC和垃圾箱。
4. 不要通过重复创建真实订单盲测。

### 内容撤回后精确URL仍可访问

1. 先在后台确认对象已变为`Draft`、`Private`或`Trash`，并在发布/变更登记中记录对象ID、原URL、执行账号、时间和原因；不要只看Sitemap变化。
2. 在未登录窗口直接请求不带随机查询参数的精确原URL，同时查看状态码、`X-Cache`与`Age`。已发布页面正常出现`200`和缓存`HIT`；只有对象已撤回却仍返回旧公开HTML时才是缓存泄露风险。
3. 若撤回对象仍返回`200`或旧正文，清理Breeze页面缓存及当前环境实际启用的Varnish缓存；不要通过修改Slug、删除对象或无关301掩盖旧缓存。
4. 清理后首次复核精确URL：草稿或回收站对象应不再公开，D24 Staging实测为`404`且`X-Cache: MISS`。若仍返回旧内容，停止后续发布并升级排查服务器、CDN或其他缓存层。
5. 再核对Sitemap不含该URL、Primary与Handheld导航无入口，并把缓存处理与结果补入同一条变更记录。重新发布后必须重新验证URL、Sitemap和菜单；此时正常页面后续重新成为缓存`HIT`不构成故障。

### 定时任务未执行

1. 检查WP-Cron和服务器Cron配置。
2. 检查WooCommerce Scheduled Actions的失败任务。
3. 查看任务日志、超时和并发锁。

## 备份策略

- Staging应用文件＋数据库：Cloudways每日自动备份、保留一周；发布、商品批量导入或其他高风险写入前执行On-Demand Backup并记录时间。
- Production数据库：至少每日自动备份；发布前手动备份，具体保留期在Production环境确认。
- uploads：由应用文件备份覆盖，并在重大素材迁移前创建额外快照；商品CSV不能替代uploads备份。
- 代码：Git远程仓库和版本标签。
- 配置：插件清单、环境版本、DNS/CDN/服务器配置说明。
- 保留周期：业务方和合规要求确认后填写。
- 每月至少一次恢复抽查；正式上线前必须完整恢复演练。

### 商品批量录入的备份与恢复责任

| 事项 | 责任人 | 第一版要求 |
|---|---|---|
| 定期应用文件＋数据库备份 | 开发者/管理员 | 核对Cloudways计划、保留期和最近成功时间；商品、Post、Page及其元数据都以数据库备份为完整恢复基础 |
| 每批商品导入前CSV导出 | Website Manager | 使用WooCommerce原生商品导出，保存带日期时间的文件并登记文件名/指纹；它是商品数据快照，不是完整站点备份 |
| 当前批准的商品CSV导入 | Website Manager | 只在受保护Staging使用Simple模板v1、新SKU和`Published=-1`，保持`Update existing products`未勾选，完成页`Updated`必须为0；Variable/Variation CSV与Production未开放 |
| 小量新建Draft误操作 | Website Manager | 按批次SKU清单核对后移入回收站，操作和结果写入批次登记；不自行永久删除 |
| 已有价格/库存/Variation/Slug受影响或大批异常 | 开发者/管理员 | 立即停止写入，保存故障现场；按商品导出差异决定定点修复，无法安全修复时才评估数据库/应用恢复 |
| 数据库或应用恢复 | 开发者/管理员 | Website Manager不获得Cloudways、数据库或服务器恢复权限；恢复前评估恢复点之后的新内容、订单和媒体是否会丢失 |

第一版不提供WooCommerce导入批次一键回滚。存在备份不等于恢复已通过；M3只要求批量录入前保护、明确升级路径和代表性恢复抽查，完整灾难恢复演练仍按M9执行。

## 生产操作记录模板

- 操作ID：OPS-XXX。
- 操作时间：
- 操作人：
- 业务原因：
- 影响范围：
- 操作前备份：
- 执行步骤：
- 验证结果：
- 回滚是否需要：
- 相关日志、提交和截图：
