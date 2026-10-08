---
类型: 项目Day笔记
项目: DentAll WooCommerce
日期: "2026-10-08"
工作日: Day91
主题: SEO元数据模板与Staging验证
状态: 已验证授权的三项模板配置；D91整体验收待收口
tags:
  - DentAll
  - Day91
  - SEO
---

# Day91 SEO元数据模板与Staging验证

## 结论

用户明确允许在Cloudways Staging应用`6604195`先备份，再仅修改Yoast `wpseo_titles`的三项模板，并在必要时清理受影响页面的缓存。三项模板已写入并由新WP-CLI进程按完整备份复核：目标值正确，其余172项严格不变，autoload仍为`auto`。全新404与商品分类的未命中缓存请求已输出预期英文标题；标准TEST分类URL的旧缓存已按单URL清理，并通过MISS→HIT验证新页面可正常缓存。D91计划中的全部描述、社交分享和页面抽样尚未完成，不据此标记D91整体Done。

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
- 边界：没有授权修改逐分类Yoast元数据、文章或商品内容、其他Yoast键、URL/Canonical/robots、缓存策略、插件或运行代码，也没有授权全站清缓存、Production、DNS或支付物流操作。

## 最多3项验收结果

1. [x] 确认唯一目标站点、完整备份、三键更新和其余键不变。
2. [x] 全新404与商品分类未命中缓存请求的状态码、Title、Open Graph、robots及Canonical按当前Staging边界核对。
3. [x] 标准TEST分类URL旧缓存已定向清理并复验，`CAD/CAM Materials`旧标题来源已只读定位；其修正及其他代表页面另列后续待办。

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

## 公开HTTP与缓存分层

| 请求 | 实测 | 结论 |
|---|---|---|
| 全新未知URL `/d91-seo-404-check-20261008-2/` | 404；Title和`og:title`为`Page not found - DentAll`；`noindex,nofollow`，无Canonical；`X-Cache: MISS` | 404模板与真实404状态吻合 |
| `.../product-category/test-d12-products/?dentall_d91_verify=20261008a` | 200；Title和`og:title`为`TEST D12 Products - DentAll`；`noindex,nofollow`；`X-Cache: MISS` | 未命中旧缓存时，分类全局模板生效 |
| 标准`/product-category/test-d12-products/` | 清理前200、旧`归档`标题、`X-Cache: HIT`、`Age`约7500秒；清理后首次GET为新Title和`og:title`、`X-Cache: MISS`、`Age: 0`，第二次GET相同Title、`X-Cache: HIT`、`Age: 13` | 旧缓存曾遮蔽新配置；定向清理后新HTML已进入页面缓存 |

- 商品分类Sitemap的16个URL中，15个匿名抽样已输出新英文标题；`CAD/CAM Materials`在新查询且`X-Cache: MISS`时仍带`归档`。随后只读WP-CLI确认它是`product_cat` term ID 32，`wpseo_taxonomy_meta`中的分类级`wpseo_title`为`%%term_title%% 归档 %%page%% %%sep%% %%sitename%%`，单独读取的term meta标题为空。因此它确有内容级覆盖，本轮未修改。
- 对精确TEST分类URL从公网尝试`PURGE`与`URLPURGE`均得405；对本机`:8080`直接`URLPURGE`为Apache 403，均不能作为缓存已清理的证据。停止直接发送原始方法，不扩大为全站清理。
- Breeze现场版本为2.5.12，插件的单URL清理函数`breeze_varnish_purge_cache`存在；只读检查得到Varnish host `127.0.0.1`、active `yes`。源码复核表明无查询参数的精确URL走单URL `URLPURGE`，而`?breeze`进入全站分支。对标准TEST分类URL执行一次带站点、Host、无查询参数及启用状态守卫的函数调用；WP-CLI返回“请求已发出”。随后公网首次GET为200、新Title/OG、`noindex,nofollow`、无Canonical、MISS/Age0；第二次GET同题且HIT/Age13，构成实际生效证据。

## 影响、风险与下一步

| 领域 | 本轮影响 |
|---|---|
| 数据 | 仅Staging数据库一项Yoast选项的三个键变化；没有商品、文章、订单或用户写入 |
| URL、索引、SEO | 没有改Slug、重定向、Canonical、robots或Sitemap配置；Staging继续`blog_public=0`；Title/社交Title/404 Title受影响，逐分类覆盖仍待审 |
| 缓存 | 仅考虑精确TEST分类URL的旧页面缓存；全站缓存策略与其他URL不变 |
| 支付、物流、邮件 | 不涉及 |
| 部署 | Staging数据库配置变更，不涉及主题/Core发布；Production尚未重放 |

- D91/P2：`CAD/CAM Materials`即使缓存MISS仍出现旧`归档`，term ID 32的Yoast分类级标题已确认是原因。负责人开发者与Website Manager，最晚D92索引策略审查前决定该单项内容修正及正式分类标题审核范围；须先获得独立授权并备份原值。当前Staging禁索引，不能把它当成Production SEO已通过。
- TEST分类标准URL旧缓存问题已关闭；没有执行全站清理。其余URL不会因这一条定向清理而自动刷新，后续样本如发现旧页面应分别核对缓存层。
- D91后续还须按计划复核描述、社交分享、代表页面与D92索引/Canonical/Sitemap；本轮三键更新不代替这些检查。
- 凭据安全：本次排查中旧私钥口令曾在本机终端回显，应安排新密钥添加、验证后再撤销旧密钥；本笔记不保存口令。

## 减法审查

运行代码净增文件、函数、规则块和行数均为0。复用Yoast现有配置和Breeze既有单URL能力，没有引入插件、脚本或新缓存逻辑。文档只记录授权、事实、验证与回滚；未将备份或临时取证文件加入版本库。

## 可复用核心思想

### 跨平台不变量

变更SEO全局模板时，要分开验证配置真相、内容级覆盖与访客拿到的缓存页面；先备份完整对象，写入最小字段，再比较未授权字段。缓存命中只能说明旧响应仍在，不能证明新配置失败。

### WordPress/WooCommerce当前实现

Yoast将多个模板保存在同一`wpseo_titles`选项中；商品分类由WooCommerce `product_cat` taxonomy提供。WP-CLI操作应在站点根目录并核对`home`，公开HTML仍需按状态码、Title、OG、robots、Canonical与缓存头逐项验收。

### Shopify或其他平台的对应机制

同样需要区分商店默认SEO模板、单个集合/页面的覆盖和CDN缓存；Shopify字段与缓存失效接口不在本轮验证范围，具体映射待查，不能把Yoast选项名照搬到其他平台。
