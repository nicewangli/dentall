---
类型: 项目Day笔记
项目: DentAll WooCommerce
日期: 2026-10-08
工作日: Day94
主题: 商品与组织Schema阶段验证
状态: 隔离副本技术抽样完成；D94整体未完成
验收层级: 当前分支代码＋独立旧Local数据库
tags:
  - DentAll
  - Day94
  - SEO
---

# Day94 商品与组织Schema阶段验证

## 结论

当前分支代码与独立旧Local数据库组成的隔离副本已完成11类GET抽样。关闭该副本的WooCommerce Coming Soon后，两个TEST商品及一个合法变体参数URL实际进入商品正文：Simple、Variable和变体均有一份Product和一份BreadcrumbList；Simple的Offer现价与页面促销现价一致，Variable的AggregateOffer范围与可见范围一致。随后浏览器选择合法Size/Shade变体，动态价与库存也和该变体数据一致。隔离首页的Yoast图谱有一份Organization。以上是技术输出的阶段证据，不是Staging或Production的公开Schema验收，D94不标Done。

## 相关笔记

- 当日学习笔记：[[WordPress实战笔记/Day94-商品Schema与可见内容一致性]]
- 前置商品Schema责任边界：[[Day65-商品详情结构化数据与SEO边界]]
- 四日共用抽样起点：[[Day91-SEO元数据模板与Staging验证]]
- URL与SEO合同：[[../URL_SEO_MAP|URL与SEO映射]]
- 每日笔记索引：[[README|DentAll每日笔记索引]]

## 范围与最多3项阶段验收

1. [x] 对Simple、Variable及合法变体URL核对Product、Offer类型、面包屑、Canonical与页面可见价格。
2. [x] 核对隔离首页的Organization节点及商品页面图片的`alt`存在性。
3. [ ] 浏览器动态变体已在隔离副本复核；仍须使用受控可见的目标环境和正式素材复核首页、商品、社交图片及在线结构化数据验证器。

本轮没有修改项目运行代码、共享Local、Staging或Production的商品、SEO选项、索引、缓存、支付、物流与部署。

## 环境与证据

- 隔离运行时：当前`de9a`工作树的`dentall-core`与子主题代码，配合只读复制的本机WordPress核心、第三方插件及独立旧Local数据库；WordPress 7.1、WooCommerce 11.0.0、Yoast 28.2、PHP 8.2.29。
- PHP与MySQL分别只监听`127.0.0.1:16575`和`127.0.0.1:16576`。副本关闭自动Cron，阻断邮件及WordPress出站HTTP；只在独立数据库中临时关闭Coming Soon以显示真实商品正文，收尾已恢复`yes`并停止两个服务。
- 副本保持`blog_public=1`以观察Yoast的正常Head分支；隔离路由对11个HTML GET发送HTTP `X-Robots-Tag: noindex, nofollow`。页面Meta与该外层保护头是两层不同证据，不能把这个设置套到Staging。
- 原始HTML与响应头在本机Git忽略目录`.codex-tmp/day92-94-runtime/evidence/raw-guarded/`；汇总为`evidence/summary.json`，商品Schema字段为`evidence/product-details.jsonl`，环境恢复记录为`evidence/closure.json`。没有把旧Local数据快照或凭据纳入Git。

## 商品与首页抽样

| 样本 | 状态与Canonical | JSON-LD观察 | 与页面内容的对照 |
|---|---|---|---|
| TEST D12 Simple Fixed Pack | 200；自身商品URL | Product 1、Offer 1、BreadcrumbList 1；Offer为USD 24.99、InStock | 页面原价USD 29.99划线，促销现价USD 24.99，与Offer一致 |
| TEST D12 Variable Size Shade | 200；自身商品URL | Product 1、AggregateOffer 1、BreadcrumbList 1；范围USD 39.99～49.99 | 页面可见同一价格范围 |
| 合法Size/Shade变体参数URL | 200；Canonical仍指无参数父商品 | Product 1、Offer 1、BreadcrumbList 1；Offer为USD 39.99 | 服务端初始HTML仍显示父商品39.99～49.99范围；浏览器选`small-98-mm`与`light`后动态区显示USD 39.99、`5 in stock`，匹配变体ID 51数据 |
| 隔离首页 | 200；自身URL | Yoast图谱中Organization 1 | 使用的是旧Local站点资料；不能证明正式Logo、公司信息和社交图片已经审核 |

三个商品响应中，图片`alt`均无缺失或空值；Yoast的WebPage没有对另一条已移除面包屑的悬空引用。隔离首页有一张空`alt`图片，但本轮未逐图判断它是否为装饰图，不能直接判为缺陷或验收通过。非商品页未发现Product节点；这与页面类型相符，不作为全面Schema回归结论。

浏览器变体复测的54个放行请求均到`127.0.0.1`，Google Fonts、PayPal SDK与emoji的4个外站请求被阻断，页面脚本错误0；没有点击加入购物车或结账。截图和结构化结果在忽略目录`evidence/day92-page2/browser/`。浏览器父商品页仍保留价格范围，已选变体价格另显示在动态区域；本轮没有证明浏览器会把父商品的JSON-LD改写成变体Offer。

## 机制与未验收边界

`app/public/wp-content/plugins/dentall-core/includes/seo-compatibility.php`在实际经典商品模板调用`get_header('shop')`时，保留WooCommerce的商品与面包屑输出，只过滤Yoast重复的BreadcrumbList及WebPage引用。此次商品输出与D65冻结的单一面包屑责任一致；没有新增Schema实现文件或字段。

- **目标环境：** Staging仍为`blog_public=0`并保留Coming Soon。此前匿名商品、Shop及分类正文是Coming Soon；首页未见Organization。该匿名输出不能验收真实公开首页或商品Schema。Production未测试。
- **内容与素材：** 两个商品是旧Local TEST数据。正式商品名称、价格、库存、品牌、合法组合、图片授权、Logo与社交图片仍须由业务方审核；不能把TEST值当发布内容。
- **结构化数据验证：** 本轮用本地JSON-LD解析与DOM对照，未运行Google Rich Results Test、Schema验证器，也未验证搜索平台富结果展示。
- **浏览器：** 隔离浏览器已验证选中变体的可见动态价与库存。正式目标环境、真实设备及页面上的JSON-LD是否随客户端选择变化未验；父商品AggregateOffer与变体参数URL的Offer应分别按各自请求身份判断。
- **图片：** 商品样本`alt`非空只证明属性存在；正式图片是否准确、有授权，空`alt`是否确属装饰图，还需逐图审查。OG/Twitter图片与正式Logo没有在本轮验收。

下一步以正式内容和受控可见商品页面复核Schema、图片及社交输出；在目标公开环境运行结构化数据验证器，并与D91～D93的URL、索引、Sitemap结果做共用样本回归。不存在完整正式商品样本时，不用TEST证据宣布D94完成。

## 影响与恢复

运行代码净增0文件、0函数、0规则块、0行。仅新增本阶段文档；隔离数据库的Coming Soon已恢复`yes`，为D92有效分页临时创建的11件TEST商品已删除并经九组相关表与设置快照核对，PHP/MySQL临时服务均已停止，端口无监听。源Local的`wp-config.php`哈希、`home`、`siteurl`、`blog_public`和Coming Soon关键原值已复核；Staging只读HEAD仍为`noindex,nofollow`。未执行Staging或Production写入，也未改URL、Canonical、Schema配置、缓存、支付、物流或部署。

## 可复用核心思想

### 跨平台不变量

机器可读商品价格应与用户看到的实际现价或范围同源；参数URL、服务端初始展示与客户端选择后的状态要分开核对。父商品范围和选定变体动态价可以同时出现，但须明确各自所代表的事实。缺少Product节点前先确认访客是否真正看到了商品正文。

### WordPress/WooCommerce当前实现

WooCommerce根据商品与变体事实生成Product及Offer，Yoast提供网页与Organization图谱；当前`dentall-core`只协调经典商品页的重复面包屑。Coming Soon、`blog_public`和外层HTTP保护头各自改变不同层的可见性与SEO输出，不能互相代替。

### Shopify或其他平台的对应机制

同样要核对可见价格、机器可读商品数据、规范URL与正式图片的一致性。其他平台具体的商品Schema生成和分享图片入口尚未验证，不能照搬WooCommerce或Yoast的Hook与字段。
