# 发布与上线检查清单

## 发布信息

- 版本：
- 发布日期/窗口：
- 发布负责人：
- 业务确认人：
- Git提交/标签：
- 变更请求：
- 数据库变化：有 / 无。
- 预计影响：

## Staging首轮完整同步专用清单

> 本节只适用于从冻结运行代码提交`97ebdc3637a313d53e10abdbc5dc181b99f69fef`首次同步DentAll `0.41.0`和Core `0.2.9`。后续纯文档提交可以推进`main`分支头；运行发布源由提交可达性、两个运行tree和清单指纹共同确认。勾选必须有命令输出、Cloudways状态、截图或操作记录；未勾选的硬闸门不得凭口头判断跳过。详细白名单与恢复边界见[部署、运维与恢复手册](RUNBOOK.md)。

### 发布包与删除语义

- [ ] 冻结提交可读取，当前`main`和`origin/main`均包含它；主题tree为`27852ee731af90a3644130aca25612a196e7c3eb`，Core tree为`39b316ed03c32f1842d4ea7528b74a592c090529`。
- [ ] 上次已部署基线为`501e5e5fc2e8a800f637a7fd6b5e6a2d0947c8c6`；不要求候选生成后的`deploy/staging`分支头仍等于该值。
- [ ] 已记录A“依赖预置”和B“切换”两个候选SHA：A相对基线只有19A，B相对A只有5M/4D，B最终相对基线为19A/5M/4=/4D。
- [ ] 已确认两分支无共同祖先；发布包从Git对象映射生成，没有直接merge、递归复制Local目录或读取未跟踪文件。
- [ ] 候选只含`wp-content/themes/dentall/**`和`wp-content/plugins/dentall-core/**`，最终精确为28文件、385,771字节。
- [ ] 清单指纹为`9912fcce92c95dd269c8657f3de23c730816586f75fb40889ffd4bf729774fd7`。
- [ ] 相对基线精确得到19新增、5更新、4不变、4删除；没有第三个路径前缀或DentAll mu-plugin。
- [ ] 4个未跟踪的商品导入相关文件、项目文档、测试/证据、数据库、uploads、Core、父主题和第三方插件均未进入候选。
- [ ] PHP 13/13 lint、JS 4/4语法、报价PHP 36/36、报价JS 23/23、`git diff --check`和路径守卫通过。
- [ ] 已接受Cloudways Pull不会自动删除旧文件；4个旧模板的核对、隔离和恢复步骤已写入本次操作记录。
- [ ] 回滚时必须撤回的19个新增文件清单指纹为`23d298b6113cdb0631aaeb0aa448f9e17a757231b8b43cdf551a49baa47d6da7`。
- [ ] 没有把A和B作为一次Pull执行；若现场无法两阶段Pull，已有Cloudways原子部署证据，或已有真正隔离WordPress请求/Cron且独立于当前插件/主题的恢复方案，否则`NO-GO`。

### Staging现场只读硬闸门

- [ ] 在任何HTTP/WP-CLI WordPress引导之前，已从授权只读数据库入口确认实际表前缀下的`theme_switched`和`theme_switched_via_customizer`均无真值；任一真值都只判`NO-GO`，没有在备份前请求消费。若需归一化，已转独立授权且先备份的主题状态修复窗口，完成后从头重跑本节。
- [ ] Password Protection、HTTPS、全站`noindex`和真实支付关闭均现场通过。
- [ ] `wp_get_environment_type()`精确为`staging`，不是`local`、`development`或`production`。
- [ ] PHP、数据库、WordPress、WooCommerce、Storefront、Yoast、DentAll和DentAll Core现场版本已记录；与Local验证基线的差异已有兼容结论。
- [ ] Storefront已安装、完整且兼容；活动基线精确为`stylesheet=storefront/template=storefront`，目标DentAll声明`Template: storefront`。其他现场状态已进入单独处置分支。
- [ ] 两个DentAll服务器目录的路径、文件、权限和哈希已盘点；未知文件或内容漂移为0，或已有明确`NO-GO`处理结论。
- [ ] `home/siteurl`、前台/文章页映射、首页模板、WooCommerce四个页面ID已保存快照。
- [ ] `stylesheet/template/current_theme`、两个`theme_mods_*`、`sidebars_widgets`均已记录“原本存在/不存在”及完整值；活动主题将通过WordPress主题API/WP-CLI切换，不直接回填核心主题option。
- [ ] `theme_switched`和`theme_switched_via_customizer`仅作诊断快照且未纳入配置回填；获准主题切换后才由实际加载目标主题及正常插件集、不带`--skip-themes`/`--skip-plugins`的请求消费。目标主题Fatal时改走回滚，不强行消费；任何回滚都不会回灌旧真值。Storefront/DentAll的`custom_css`记录和私密恢复副本已按ID/修改时间/内容哈希登记。
- [ ] 全部菜单对象和两个主题location映射已快照；`secondary`目标未绑定、`handheld`目标未注册，发布窗口不会创建、删除、改名或改写菜单项。
- [ ] 货币、销售/配送国家、税费、Shipping Zone/Rate、支付网关状态和报价邮箱“空/有效”状态已记录；没有输出或保存凭据/邮箱明文到Git。
- [ ] 属性查询表开关、Direct/Optimized Updates、表存在性/行数、缺货隐藏、Brands taxonomy和Yoast taxonomy规则已记录。
- [ ] Page/Post/Product/Variation/Media/Order各状态计数和最近修改时间已记录，网站人员录入的商品/媒体边界清楚。
- [ ] `dentall_core_role_version=7`；Website Manager/Content Editor完整capability已排序保存并生成Pull前现场哈希，Pull后将以该值作逐项不变量比较。版本不是7时另行评审角色数据库写入。
- [ ] Breeze、对象缓存、Varnish/CDN及其清理入口已现场确认。
- [ ] Cloudways可用磁盘空间满足恢复预留；不足时停止。
- [ ] SSH、Git/文件级恢复和Cloudways恢复入口独立可用；仅在只读数据库已证明两项切换哨兵无真值、且当前活动主题＋正常插件集可稳定加载后，才用`--skip-plugins --skip-themes`完成WP-CLI只读命令演练。skip只读演练未被当作skip主题切换授权；实际激活使用完整WordPress栈。任一Fatal不以普通WP-CLI停插件/切主题；应用/PHP/Web日志精确路径与读取权限已记录。

### TEST内容与配置重放

- [ ] 已扫描发布态内容、分类/标签/品牌、菜单、Widget、站点文案、媒体alt/caption和SEO字段；全部登记TEST对象的ID、状态、入口、用途和后续处置责任清楚。
- [ ] `GO-TECH`允许登记TEST夹具仅在Password Protection＋全站`noindex`下用于回归；它们未冒充正式内容，也未因代码发布被撤稿、改名、解绑或删除。
- [ ] 已确认Local专用公告、Newsletter、占位Logo和Trust数字在Staging实际HTML中为0；未为Staging裁剪同一代码包。
- [ ] `GO-PRESENTATION`所需正式Simple/Variable和关键页面样本已识别；若需处置TEST对象，已有独立、可逆的内容变更单，完成后用户可见明确测试标记为0。
- [ ] 配置重放逐项对比并留痕，没有复制Local数据库或uploads。
- [ ] `primary`、`footer`、`homepage_categories`、`homepage_solutions`目标映射已准备，`secondary`明确未绑定；切换后由精确location值覆盖WordPress自动映射结果，菜单对象本身不变。
- [ ] 首页目标Page使用Storefront `template-homepage.php`；Custom Logo及正式菜单/内容具备不显示TEST字眼的安全结果。
- [ ] 报价邮箱为空时已明确只验代码部署、不验D72业务闭环；若要验报价闭环，已配置企业控制的有效邮箱并安排真实邮件客户端验证。

### 备份与写入窗口

- [ ] 网站人员已收到短暂停止编辑通知；冻结开始时间、最后内容、订单、媒体和配置修改时间已记录。
- [ ] Cloudways应用级On-Demand Backup已完成，不只是开始；Web文件与数据库均在范围内。
- [ ] UTC恢复点、Last Backup Date、状态、应用标识和证据截图已记录。
- [ ] 恢复界面可选择该恢复点，并确认Complete、Web Files only、Database only的现场可用范围。
- [ ] `501e5e5`代码基线、4个旧模板及19个新增文件的回滚清单可读取。
- [ ] 已记录备份之后是否发生任何内容、订单、媒体或配置写入；发生未登记写入立即停止。

### 两阶段部署、主题切换与冒烟

- [ ] 只把远端`deploy/staging`快进到A并第一次Pull；19个依赖文件匹配，Core仍为0.2.6、DentAll仍为0.1.0、4个旧模板仍在，日志无新增Fatal。
- [ ] 活动主题仍为`stylesheet=storefront/template=storefront`时，4个旧模板逐项通过Git blob核对并移到`public_html`外的本次隔离目录；没有通配符或递归删除。
- [ ] 只把远端`deploy/staging`快进到B并第二次Pull；开始/完成时间、操作人及部署日志已记录。
- [ ] 第二次Pull后4个旧模板不存在；28个目标文件全部匹配冻结Git对象；没有未知额外文件。
- [ ] 保持Storefront活动完成Core 0.2.9加载、角色不变量、交易闸门、主题父子关系、后台、前台与PHP日志预检，无Fatal后才激活DentAll。
- [ ] 通过WordPress主题API/WP-CLI激活DentAll，并以实际加载DentAll及正常插件集、不带`--skip-themes`/`--skip-plugins`的受保护请求完成一次引导；`stylesheet/template/current_theme`结果正确，`theme_switched`/`theme_switched_via_customizer`已消费为无真值。Fatal时已转回滚而非强行消费；随后精确恢复目标Theme Mod/location。`secondary`未绑定、`handheld`未注册、前台仅一棵主导航，菜单对象/项不变；首页模板、Custom Logo和旧Custom CSS实际输出均已复核。
- [ ] 已清理实际启用的Breeze、对象、Varnish/CDN和浏览器缓存。
- [ ] `GO-TECH`使用登记样本完成匿名与登录态Home、Shop、分类、搜索、Simple、Variable、Cart、Checkout、My Account、404及已登记直接URL回归。
- [ ] Staging HTML中Local专用TEST/占位/Trust输出为0；登记TEST内容若仍用于回归，不把本项误写成`GO-PRESENTATION`。
- [ ] RSK-035的AJAX pending/503/network/parser/timeout/abort/迟到响应，RSK-037商品详情相邻导航移除及RSK-038长属性四端均在Staging通过。
- [ ] Header Cart/Mini Cart、优惠券、金额、人工报价入口和未报价Checkout守卫按本轮可用配置通过；“已有Shipping待付款订单”仅在现场已有获准登记样本时验证，否则标记`N/A`并写明原因，不得为勾选而创建或修改业务订单。
- [ ] robots、Canonical、Sitemap、筛选参数、品牌归档、404、PHP日志和浏览器控制台无新增P0/P1。
- [ ] 部署后内容/商品/媒体/订单计数与写入预期一致；没有用代码部署覆盖数据库或uploads。
- [ ] Sticky、Yoast、品牌noindex、属性查询表/Direct Updates、报价邮箱及TEST内容处置均未与纯代码切换混写；每项另有批准、旧值和回滚证据。
- [ ] 正式内容复跑后，页面文本、`alt/title/aria-label`、HTML源、菜单、Sitemap和缓存中的明确测试标记为0，才标记`GO-PRESENTATION`。

### GO/ROLLBACK决定

- [ ] `GO-TECH`：代码、主题切换、环境保护与技术回归通过；是否保留登记TEST夹具已明确，记录结束时间后解除本次代码写入冻结。
- [ ] `GO-PRESENTATION`：正式内容和独立TEST处置完成，用户可见明确测试标记为0；只有本状态可按用户要求交接浏览。
- [ ] `ROLLBACK`：已记录触发原因、故障页面/订单、最后成功步骤、是否有备份后写入和选择的恢复范围。
- [ ] 回滚前先经只读数据库判断切换哨兵并验证当前活动主题＋正常插件集：只有完整栈可加载且两项哨兵无真值时，才允许先正常切回Storefront；任一truthy或任一活动栈Fatal（包括Storefront被Core Fatal阻断）时，禁止先运行普通WP-CLI，先在WordPress引导之外Pull精确回滚代码。基线代码可加载后经非skip请求消费可能存在的生命周期；若DentAll仍活动，再正常激活Storefront并以非skip请求完成新生命周期。仍不可加载则转已评估的Cloudways恢复。
- [ ] 文件故障优先精确回滚两个DentAll目录；无论停在A还是完成B，均已在远端追加恢复`501e5e5` tree的前向回滚提交并Pull，不reset/force-push，也不留下“远端A/B、服务器手工基线”的漂移状态。
- [ ] 若使用Git/SSH回滚，19个新增文件已按发布哈希精确撤回、4个旧模板已恢复、最终13个基线文件及Core `0.2.6`/DentAll `0.1.0`均已核对。
- [ ] 若使用Web Files only，已证明备份后无uploads或其他Web文件写入，或已保存并对账增量；已接受它会覆盖整个`public_html/private_html`而不恢复数据库。
- [ ] 若恢复数据库或Complete Restore，已保存故障现场并书面评估恢复点之后的商品、媒体、订单和配置损失。
- [ ] 最终`theme_switched`/`theme_switched_via_customizer`均无真值；活动主题/持久配置、环境保护、Home、Shop、Simple/Variable、Cart、Checkout、Account、Website Manager权限、缓存和日志复测通过后才解除冻结。

## 正式Production或完整业务发布前

以下通用清单不作为本次受保护Staging代码部署取得`GO-TECH`的前置条件；暂不适用项标记`N/A`并写明理由。沙盒订单、支付、邮件或正式内容测试所需的数据写入必须另有明确授权、备份和清理方案，不得为了勾选清单而改动业务数据。

### 范围和测试

- [ ] 发布范围、验收标准和已知问题已确认。
- [ ] `CHANGELOG.md`已更新。
- [ ] P0/P1缺陷为0，P2有明确处理结论。
- [ ] 手机、平板横竖屏、PC关键页面通过。
- [ ] Staging完成至少一笔沙盒测试订单。
- [ ] 缓存开启时再次完成购物和账户回归。

### 数据和回滚

- [ ] 生产数据库备份已完成并记录路径、大小和时间。
- [ ] uploads快照/增量备份已完成。
- [ ] 当前生产Git标签和插件版本已记录。
- [ ] 回滚包和回滚步骤可执行。
- [ ] 数据库脚本或搜索替换已在Staging演练。

### 配置和外部服务

- [ ] Staging仍保持noindex，Production索引开关按计划配置。
- [ ] 支付生产凭证、Webhook和回调URL已核对。
- [ ] 运费、税费、货币和邮件发件信息已核对。
- [ ] 域名、DNS、HTTPS、CDN和缓存规则已准备。
- [ ] SMTP、备份和监控账户归企业所有并启用MFA。

## 部署中

- [ ] 记录开始时间和操作人。
- [ ] 部署正确代码标签/发布包。
- [ ] 执行经过审核的数据变化。
- [ ] 清理页面、对象、浏览器/CDN相关缓存。
- [ ] 无未记录的生产临时修改。

## 部署后冒烟测试

- [ ] 首页、菜单、搜索和页脚正常。
- [ ] 商品列表、筛选、搜索、详情和图片正常。
- [ ] 匿名用户和登录用户购物车正常。
- [ ] 优惠券、配送、税费和结账金额正确。
- [ ] 完成受控测试订单，支付回调和订单状态正确。
- [ ] 客户和管理员邮件送达。
- [ ] 登录、密码重置、地址和订单中心正常。
- [ ] robots、Sitemap、Canonical、Schema和301正确。
- [ ] PHP日志、Web日志和浏览器控制台无新增严重错误。
- [ ] 性能和缓存行为没有明显退化。

## 观察与交接

- [ ] 记录部署结束时间。
- [ ] 观察支付、订单、邮件、404和错误日志。
- [ ] 将已知问题和后续任务写入`PROJECT_STATE.md`。
- [ ] 更新版本、备份、发布证据和操作记录。
- [ ] 业务方完成验收或留下书面反馈。

## 发布结论

- 结果：成功 / 已回滚 / 部分成功。
- 证据链接：
- 发现问题：
- 后续负责人和截止时间：
