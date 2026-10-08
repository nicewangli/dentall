---
类型: 项目Day笔记
项目: DentAll WooCommerce
日期: "2026-10-08"
工作日: Day91
主题: SEO元数据模板与Staging验证
状态: 已验证三项全局模板及CAD/CAM单项标题修正；D91整体验收待收口
tags:
  - DentAll
  - Day91
  - SEO
---

# Day91 SEO元数据模板与Staging验证

## 结论

用户明确允许在Cloudways Staging应用`6604195`先备份，再仅修改Yoast `wpseo_titles`的三项模板，并在必要时清理受影响页面的缓存。三项模板已写入并由新WP-CLI进程按完整备份复核：目标值正确，其余172项严格不变，autoload仍为`auto`。全新404与TEST商品分类已输出预期英文标题，TEST分类标准URL的旧缓存已定向清理。用户随后另行授权修正`CAD/CAM Materials`分类term ID 32的唯一标题覆盖；该字段、Yoast索引及该分类标准URL缓存均已定向处理并复验。D91计划中的全部描述、社交分享和代表页面抽样尚未完成，不据此标记D91整体Done。

## 相关笔记

- 学习笔记：[[WordPress实战笔记/Day91-Yoast模板与缓存分层验证]]
- 本地分类模板基线：[[Day48-商品分类内容与W8列表回归]]
- URL与SEO映射：[[../URL_SEO_MAP|URL与SEO映射]]
- 当前项目状态：[[../PROJECT_STATE|项目当前状态]]
- 版本变更：[[../CHANGELOG|版本变更记录]]
- 学习索引：[[WordPress实战笔记/WordPress实战笔记索引]]
- 每日索引：[[README|DentAll每日笔记索引]]

## 授权与范围

- 目标：使Staging分类归档及404沿用已在D48 Local验证的英文标题模板，并核对Yoast公开输出。
- 读者与维护角色：访客读取前台元数据；开发者执行受控全局配置；Website Manager后续审核正式分类内容级覆盖。
- 授权写入：仅`wpseo_titles`的`title-tax-product_cat`、`social-title-tax-product_cat`、`title-404-wpseo`三键；修改前对整个选项做私有JSON备份；必要时只清理受影响页面缓存。
- 追加授权：先备份并核对原值，再仅移除`CAD/CAM Materials`（`product_cat` term ID 32）的`wpseo_title`覆盖，使其继承已验证的全局英文模板；只清该分类URL缓存并复查Title/OG。其余分类及描述、URL、robots、Canonical、全站缓存和Production均不在该单项授权内；预计30～45分钟是工作量估计，不作为完成承诺。
- 边界：没有授权修改其他逐分类Yoast元数据、文章或商品内容、其他Yoast键、URL/Canonical/robots、缓存策略、插件或运行代码，也没有授权全站清缓存、Production、DNS或支付物流操作。

## 最多3项验收结果

1. [x] 确认唯一目标站点、完整备份、三键更新和其余键不变。
2. [x] 全新404与商品分类未命中缓存请求的状态码、Title、Open Graph、robots及Canonical按当前Staging边界核对。
3. [x] 标准TEST分类URL旧缓存已定向清理并复验；获追加授权后，`CAD/CAM Materials`的分类覆盖、索引及标准URL缓存也已定向修正并复验。其他代表页面仍另列后续待办。

## 站点与安全预检

- Cloudways设置显示应用`6604195`的Folder为`deufrhswrj`，根目录是`/home/master/applications/deufrhswrj/public_html`。服务器有第二个WordPress根目录，不可凭搜索结果选第一个；当前应用由Cloudways UI、`wp-load.php`、`home`/`siteurl`交叉确认。
- Master SSH Terminal足以执行本轮操作，应用用户的SSH Access无需开启。WP-CLI从应用根目录运行；从`~`带`--path`时，现有`wp-config.php`中的相对`require('wp-salt.php')`找不到文件。该文件本身存在，切换工作目录后WP-CLI可正常运行。
- `home`和`siteurl`均为`https://wordpress-1658436-6604195.cloudwaysapps.com`；`blog_public=0`。这证明当前是禁止索引的Staging，不外推Production索引输出。

## 配置变更与恢复证据

| 键 | 原值 | 本轮值 |
|---|---|---|
| `title-tax-product_cat` | `%%term_title%%归档 %%page%% %%sep%% %%sitename%%` | `%%term_title%% %%page%% %%sep%% %%sitename%%` |
| `social-title-tax-product_cat` | `%%term_title%%归档` | `%%term_title%%` |
| `title-404-wpseo` | `(/ﾟДﾟ)/没找到页面 %%sep%% %%sitename%%` | `Page not found %%sep%% %%sitename%%` |

- 先以`umask 077`在Master用户主目录创建随机命名的私有备份文件，导出完整`wpseo_titles` JSON；验证为175键、有效JSON。现场路径为`/home/master/dentall-day91-wpseo_titles-before.jJJYTkyh`，SHA-256为`e7f76d8988a245e7e99b17ab4cc099b4fced1848aeaf4c538f47e877c188ca40`。仅记录路径与摘要用于恢复核对，不将配置内容或凭据加入Git；备份保留至本轮和后续索引审查完成。
- 写入前再次检查备份、站点URL、`blog_public`和当前选项仍与备份一致；一次更新三个键。WP-CLI返回`Success: 3 keys written`。
- 独立WP-CLI进程重新读取完整选项，与备份逐键比较：`Success: 3 keys correct; all other keys unchanged`，其余172键严格相等，autoload仍为`auto`。这比只读取三个目标值更能确认没有误写其他模板。
- 若需回滚，仅从备份恢复这三个键，并先核对现值仍为本轮目标值；不整项覆盖`wpseo_titles`，避免抹去其他人在备份后作出的独立修改。恢复后按同样方式验证公开输出。

### CAD/CAM Materials单项修正

- 修改前确认term ID 32、slug `cad-cam-materials`，原分类级标题为`%%term_title%% 归档 %%page%% %%sep%% %%sitename%%`，其余已存字段为`wpseo_linkdex`、`wpseo_content_score`。对完整`wpseo_taxonomy_meta`创建Webroot外私有JSON备份：`/home/master/dentall-day91-cadcam-yoast-before.68pvlPNx`，SHA-256为`ba48ef939789e704ddf4ec24e0ae3a1e7714fefa0fe56cf6eaa5a001548f59c6`；autoload为`auto`。
- Yoast 28.2的单term校验及完整Option保存前清洗分别只读预检通过。随后调用Yoast自身的`WPSEO_Taxonomy_Meta::set_values(32, 'product_cat', ...)`，将目标标题设为空；独立WP-CLI进程同时核对Option API与数据库原始选项：相对完整备份，严格仅删除term 32的`wpseo_title`，其余字段和autoload不变。
- 删除字段后，公网新请求为`MISS`仍显示旧标题；只读核对证实`yoast_indexable`目标行ID 46还保存旧模板。该term的父级为0、描述为空，旧索引URL与当前URL完全一致，关联SEO链接数为0，层级记录为1，Yoast当前允许保存索引。先将目标索引行与层级记录备份至Webroot外私有文件`/home/master/dentall-day91-index32-jQoZKX`，SHA-256为`a294477e76a5b41aa93f46f5b23797630a7baddae19b546ba383313b4f767e9b`，再调用Yoast 28.2自身的`Indexable_Term_Watcher::build_indexable(32)`定向刷新。
- 刷新后只读差异核对：目标索引行仅`title`、`updated_at`、`object_last_modified`变化；URL未变、层级最终记录与备份严格相同、目标SEO链接数仍为0，完整`wpseo_taxonomy_meta`仍只少目标标题覆盖。Yoast刷新期间会重写目标层级记录，此处的“相同”指最终内容。上述两个备份均保留到D91/D92索引复核结束；回滚时只恢复term 32的原标题并经同一路径定向刷新，以恢复公开输出；索引时间戳不会按字节回到旧值，不整项覆盖其他分类。

## 公开HTTP与缓存分层

| 请求 | 实测 | 结论 |
|---|---|---|
| 全新未知URL `/d91-seo-404-check-20261008-2/` | 404；Title和`og:title`为`Page not found - DentAll`；`noindex,nofollow`，无Canonical；`X-Cache: MISS` | 404模板与真实404状态吻合 |
| `.../product-category/test-d12-products/?dentall_d91_verify=20261008a` | 200；Title和`og:title`为`TEST D12 Products - DentAll`；`noindex,nofollow`；`X-Cache: MISS` | 未命中旧缓存时，分类全局模板生效 |
| 标准`/product-category/test-d12-products/` | 清理前200、旧`归档`标题、`X-Cache: HIT`、`Age`约7500秒；清理后首次GET为新Title和`og:title`、`X-Cache: MISS`、`Age: 0`，第二次GET相同Title、`X-Cache: HIT`、`Age: 13` | 旧缓存曾遮蔽新配置；定向清理后新HTML已进入页面缓存 |
| `.../product-category/cad-cam-materials/?dentall_d91_term_verify=20261008_after_index_1` | 索引刷新后200、`MISS`/`Age: 0`，Title与OG Title均为`CAD/CAM Materials - DentAll`，`X-Robots-Tag: noindex, nofollow`，未见Canonical | 分类覆盖和旧索引均已不再主导新HTML |
| 标准`/product-category/cad-cam-materials/` | 单URL清理前200、旧`归档`、`HIT`/`Age: 2081`；Breeze只清该URL后首次GET为200、新Title/OG、`MISS`/`Age: 0`，第二次仍为新Title/OG、`HIT`/`Age: 2`，两次均noindex/nofollow，未见Canonical | 该分类标准URL的新页面已进入缓存 |

- 商品分类Sitemap的16个URL中，原先15个匿名抽样已输出新英文标题；`CAD/CAM Materials`在新查询且`X-Cache: MISS`时仍带`归档`，由term级旧标题覆盖及随后确认的旧索引共同造成。该单项已获追加授权、完成定向修正；其余正式分类内容审核仍待Website Manager复核。
- 对精确TEST分类URL从公网尝试`PURGE`与`URLPURGE`均得405；对本机`:8080`直接`URLPURGE`为Apache 403，均不能作为缓存已清理的证据。停止直接发送原始方法，不扩大为全站清理。
- Breeze现场版本为2.5.12，插件的单URL清理函数`breeze_varnish_purge_cache`存在；只读检查得到Varnish host `127.0.0.1`、active `yes`。源码复核表明无查询参数的精确URL走单URL `URLPURGE`，而`?breeze`进入全站分支。分别对标准TEST与CAD/CAM分类URL执行带站点和精确无查询URL守卫的单URL调用；WP-CLI只证明“请求已发出”，实际生效证据是两条标准URL后续各自的公网MISS→HIT及新Title/OG。没有全站清理。

## D91～D94共用样本的只读基线

2026-10-08继续以匿名HTTP核对标准URL与带新查询参数的新响应；有`X-Cache`头的相关样本为`MISS`，Shop未返回该头，不能据此称为缓存未命中。以下均未写站点或清理缓存。四天使用同一代表清单，但每项变更仍须当场定向验证，D94后再联合回归。当前清单包括首页、Shop、两级商品分类、Blog、文章、普通Page、真实404，以及按需加入排序、筛选、搜索、有效/越界分页、品牌与商品标签。旧`/product/test-d12-simple-fixed-pack/`已是404，不再作为商品样本。

| 检查日 | 本轮只读事实 | 后续验收边界 |
|---|---|---|
| D91 元数据 | 首页、Shop、CAD/CAM与Zirconia分类、Dental Clinics、Blog和两篇文章均缺普通Meta Description；三个商品URL的Head有Description、OG Description和OG Image。Shop仍为`Shop - DentAll`，与已定`Products`不一致；商品搜索Title/OG仍有`您正搜索`。品牌`ADS`与商品标签`Aligner Chewies`的新响应仍带中文`归档`，不是旧页面缓存。抽样Twitter只有`summary_large_image`卡片，未输出独立Title/Description/Image。 | 正式页面描述与图片由Website Manager依据真实内容审核；技术模板及Staging同步差异需另定修正范围，不能用自动拼接描述代替正式文案。 |
| D92 URL与索引 | `/shop/page/1/`为301到Shop，第2页200且Title含页码，越界第999页为真实404；有效价格筛选200，两个非法筛选请求302到Shop并带`X-Redirect-By: DentAll`与`no-store`。当前文章分类`/blog/category/test-d12-content/`为200，根路径`/category/test-d12-content/`为404；映射文档后段已按此Staging事实修订，原Local记录仍待环境复核。 | 全站`X-Robots-Tag: noindex, nofollow`使所有样本无Canonical；不能用Staging匿名响应证明Production的Canonical、分页或`follow`策略。 |
| D93 抓取 | 未命中缓存的Sitemap Index为200并列7个子地图；品牌子图有3条URL，商品标签子图有248条URL，Page子图仍有样本页。未命中缓存的robots.txt为200并列Sitemap；HTTP同时有`noindex, follow`与`noindex, nofollow`两条`X-Robots-Tag`。 | 地图列出URL不等于当前允许索引；品牌入图与第一版Local约定不一致，标签与样本页的正式入图范围及两条响应头来源待D93核对。 |
| D94 结构化数据与图片 | 首页现有JSON-LD未见Organization，OG/Twitter图片未输出，Logo文件仍是占位素材；首页10张图均有alt属性，其中4张为空值，需按装饰性逐张判断。商品、Shop、分类和Shop第2页匿名正文实际是WooCommerce Coming Soon块，H1为`Great things are on the horizon`。 | Coming Soon响应没有真实商品内容，不能把未见Product Schema判为公开商品缺陷，也不能据此验收商品alt、分页结果或筛选集合；需用受控可见样本复核。 |

Staging仍为`blog_public=0`且WooCommerce Coming Soon开启；本轮没有为抽样解除保护。上述D92～D94只读发现用于安排工作，不代表三个Day已完成，也不代表Production索引或Schema已通过。

## 影响、风险与下一步

| 领域 | 本轮影响 |
|---|---|
| 数据 | 仅Staging的Yoast全局选项三键与term 32标题覆盖变化；定向索引行只变标题和两个更新时间字段，层级最终记录与备份一致、目标SEO链接数仍为0；没有商品、文章、订单或用户写入 |
| URL、索引、SEO | 没有改Slug、重定向、Canonical、robots或Sitemap配置；Staging继续`blog_public=0`；全局分类/404 Title与term 32实际Title/OG受影响，其他正式分类仍待审 |
| 缓存 | 仅精确TEST和CAD/CAM分类标准URL定向清理；全站缓存策略与其他URL不变 |
| 支付、物流、邮件 | 不涉及 |
| 部署 | Staging数据库配置变更，不涉及主题/Core发布；Production尚未重放 |

- D91/P2的`CAD/CAM Materials`旧标题已按追加授权关闭：term覆盖、Yoast索引与标准URL页面缓存三层均有独立证据。正式分类标题审核仍由Website Manager在D92索引策略审查前继续；当前Staging禁索引，不能把它当成Production SEO已通过。
- TEST和CAD/CAM标准URL的旧缓存问题已关闭；没有执行全站清理。其余URL不会因这两次定向清理而自动刷新，后续样本如发现旧页面应分别核对缓存层。
- D91描述、社交字段与代表页面已形成只读缺口清单；正式内容、英文模板与Staging同步差异尚待处理，D91仍未整体收口。D92～D94按共用清单分别验证，最终再做一次四日联合回归；当前Staging保护与Coming Soon不适合代替受控可见页面和Production索引验收。
- 凭据安全：本次排查中旧私钥口令曾在本机终端回显，应安排新密钥添加、验证后再撤销旧密钥；本笔记不保存口令。

## 减法审查

运行代码净增文件、函数、规则块和行数均为0。复用Yoast现有配置、单term索引维护路径和Breeze既有单URL能力，没有引入插件、脚本或新缓存逻辑。文档只记录授权、事实、验证与回滚；未将备份或临时取证文件加入版本库。

## 可复用核心思想

### 跨平台不变量

变更SEO全局模板时，要分开验证配置真相、内容级覆盖、插件派生索引与访客拿到的缓存页面；先备份完整对象，写入最小字段，再比较未授权字段。缓存命中只能说明旧响应仍在，不能证明新配置失败；新响应仍旧还需检查派生索引。

### WordPress/WooCommerce当前实现

Yoast将多个模板保存在同一`wpseo_titles`选项中，分类级覆盖保存在`wpseo_taxonomy_meta`；商品分类由WooCommerce `product_cat` taxonomy提供。索引行的旧标题可在Option更正后继续影响新HTML，须先定向刷新索引，再按精确URL清页面缓存。WP-CLI操作应在站点根目录并核对`home`，公开HTML仍需按状态码、Title、OG、robots、Canonical与缓存头逐项验收。

### Shopify或其他平台的对应机制

同样需要区分商店默认SEO模板、单个集合/页面的覆盖和CDN缓存；Shopify字段与缓存失效接口不在本轮验证范围，具体映射待查，不能把Yoast选项名照搬到其他平台。
