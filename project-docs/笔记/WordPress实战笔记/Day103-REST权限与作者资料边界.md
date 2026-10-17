---
类型: WordPress实战学习笔记
项目: DentAll WooCommerce
日期: 2026-10-08
工作日: Day103
主题: REST权限与作者资料边界
状态: REST及Post公开作者出口已隔离验证；目标环境与SSH授权待验
掌握度: 初识，待本人费曼自测
验证环境: 首轮WP7.1/Core0.7.1、整合Core0.9.1；10月17日WP7.1.3/Core0.9.2/Yoast28.2；Woo11.0/PHP8.2.29，均仅TEST作者与内容
tags:
  - DentAll
  - WordPress实战
  - REST权限
---

# Day103 WordPress实战：REST权限与作者资料边界

## 相关笔记

- 学习索引：[[WordPress实战笔记索引]]
- 对应项目笔记：[[../Day103-权限与账户暴露审计]]
- 前置公开署名学习笔记：[[Day87-文章详情署名与SEO输出边界]]
- 相邻安全检查点：[[../Day104-插件主题更新与安全配置审计]]
- 相邻学习笔记：[[Day104-插件更新与数据库迁移回滚]]
- 后续公开署名与缓存复验：待形成真实笔记后双向回填。

## 今日学习成果

- [x] 能沿真实入口解释 Users REST 列表、数字单项、`/me`、文章 `author` 与 `?_embed=author` 的边界。
- [x] 能指出定向读取守卫为何要同时检查路由、方法、能力、本人 ID 和请求参数 ID；独立审查发现的两处旁路已修复并隔离复测。
- [ ] 能在目标站点核对 oEmbed、Feed、作者归档、缓存与真实编辑客户端；后续虽补了全新 TEST 的 Cookie＋Nonce 和更多 Feed 格式，目标站点及个人费曼自测仍未做。

## 真实项目场景与学习范围

DentAll的已发表TEST文章有后台作者。首轮隔离发现匿名Users REST可返回该作者的显示名、slug与链接；这些不是登录凭据或邮箱。收紧该接口后，oEmbed和Feed仍含后台身份，因此10月17日又沿各自公开扩展点补齐Post自动作者输出。本篇核心是**一个授权边界只覆盖它实际拦截的输出路径**，并以目标环境/缓存/第三方客户端尚待验收限定结论。

首轮范围为WordPress原生`/wp/v2/users` GET/HEAD集合和数字单项、本人/内容人员读取、文章数字关联及嵌入，入口`rest-user-access.php`挂于`rest_request_before_callbacks`优先级11。10月8日借既有D81/D82客户写守卫作隔离回归；10月17日经新的A包及Blog链接确认，才增加Post oEmbed/Feed自动作者治理，详见后文。登录防护、作者归档策略、D104更新和目标发布仍有各自验收。下述首轮结果只对应WP7.1/Woo11.0/Core0.7.1/PHP8.2.29，后续证据另列，不改写历史计数。

## 先建立整体模型

**一句话模型：** 文章可以继续指向作者的数字 ID，而公开 Users 资料的读取必须另按身份和用途授权；每个公开输出通道都要单独验证。

记忆宫殿：文章像图书，扉页写一个馆内作者编号；Users REST 像馆员档案柜，读者不应仅凭编号取得后台馆员资料。工作人员凭工作证可读，客户只能读自己的档案。馆外书目摘要和订阅快报则是其他出口，锁上档案柜不会自动改掉它们。这个比喻只帮助记忆：真实机制分别是 WordPress Posts REST 的 `author` 整数及 `_links`、Users REST 的数据响应、当前用户与 capability、oEmbed 和 RSS 输出，不存在物理“档案柜”可一次锁住所有传播副本。

| 记忆对象 | WordPress真实对象 | 不可混淆的边界 |
|---|---|---|
| 作者编号 | Posts REST `author`、`_links.author` | 关联 ID 不等于姓名、slug 或登录凭据。 |
| 档案柜 | 核心 Users REST 集合/数字单项 | 只守这组路由，不自动改 HTML、oEmbed、Feed。 |
| 工作证 | `current_user_can( 'edit_posts' )` 或 `list_users` | 按实际 capability 判断，不仅按角色名称猜权限。 |
| 本人档案 | 当前用户 ID 与请求路径/参数中的 ID | 路径数字和有效请求参数都要指向本人。 |

```mermaid
mindmap
  root((作者资料边界))
    输入
      请求方法
      REST路由
      当前身份
      请求参数ID
    决策
      前序错误保留
      本人数值项
      编辑能力
      其他403
    旁路检查
      大小写路由
      参数覆盖
      文章嵌入
      oEmbed与RSS
    验证
      独立HTTP角色矩阵
      目标缓存待验
```

主干是“先识别真实请求，再按能力和本人身份授权，最后从所有输出通道复查结果”。

## 请求与生命周期调用链

```mermaid
flowchart TD
  A["REST请求进入WordPress"] --> B["核心匹配路由并准备处理器"]
  B --> C["rest_request_before_callbacks"]
  C --> D{"DentAll守卫判断方法与Users路由"}
  D -->|未命中| E["交回原生处理器"]
  D -->|命中且有权限| E
  D -->|命中且无权限| F["WP_Error 403"]
  E --> G["核心permission_callback及控制器"]
  G --> H["REST响应"]
  I["Posts REST _embed=author"] --> B
  J["oEmbed或RSS"] --> K["其他输出路径；本守卫不覆盖"]
```

触发条件是 REST 请求在 WordPress 核心分发时到达该 Filter。插件主文件加载模块并注册回调；Filter 收到前序响应、处理器和 `WP_REST_Request`，必须返回响应或 `WP_Error`。本实现先保留前序错误，只处理 GET/HEAD 及 Users 集合/数字项；符合本人或编辑能力时交回原响应，其余返回 403。对文章的 `_embed=author`，隔离站证实内部作者请求再次经过守卫，文章本身仍为 200，嵌入作者只保留错误对象。oEmbed/RSS 属其他输出机制，隔离站实测仍露出 TEST 显示名。

## 核心概念卡与真实代码

| 概念 | 准确定义 | DentAll证据与常见误区 |
|---|---|---|
| 路由与方法 | 本守卫只识别 `/wp/v2/users` 及其数字子项的 GET/HEAD | 正则大小写不敏感；不能假设客户端始终使用小写路径。 |
| 有效 ID | 控制器读取的请求参数可能覆盖 URL 中的 `id` | Customer 的路径与参数互换员工/本人 ID 均在隔离站返回 403。 |
| Capability | 当前用户是否拥有 `edit_posts` 或 `list_users` | Content Editor、Website Manager、Administrator 实测可读；并非只凭角色名放行。 |
| 前序响应 | Filter 链上较早回调的结果 | 前序 `WP_Error` 原样保留；前序非错误响应仍接受本守卫收紧。 |
| 嵌入作者 | 文章响应按 `_links` 内部请求用户资料 | 保留文章 `author=3` 与 `_links`，`_embedded.author` 不含姓名/slug/link。 |

真实代码节选，源自 `app/public/wp-content/plugins/dentall-core/includes/rest-user-access.php`：

```php
if ( ! preg_match( '#^/wp/v2/users(?:/([0-9]+))?/?$#i', $request->get_route(), $matches ) ) {
	return $response;
}

if ( isset( $matches[1] ) ) {
	$current_user_id = get_current_user_id();
	// GET参数可覆盖路由中的id；两个值都必须指向本人。
	if (
		$current_user_id > 0
		&& (int) $matches[1] === $current_user_id
		&& is_numeric( $request['id'] )
		&& (int) $request['id'] === $current_user_id
	) {
		return $response;
	}
}

if ( current_user_can( 'edit_posts' ) || current_user_can( 'list_users' ) ) {
	return $response;
}
```

正则末尾的 `i` 避免大小写路径漏过；路径和有效 `id` 双重相等避免参数覆盖；用 capability 而非硬编码角色名单保留内容编辑权限。代码片段省略了方法检查、前序错误保留和最后的 `WP_Error( ..., array( 'status' => 403 ) )`，应回源文件看完整执行顺序。去掉守卫后，隔离站匿名 Users REST 会回到原生公开作者资料行为；不要为了教学在共享环境临时停用安全模块。

## Hook、职责与安全影响

| 项目 | 当前结论 |
|---|---|
| WordPress Core | 负责 REST 路由匹配、请求身份、原生 permission callback、Posts/Users/oEmbed/RSS 输出；不改核心文件。 |
| `dentall-core` | 主入口加载独立 `rest-user-access.php`；优先级 11 的 `rest_request_before_callbacks` 回调仅收紧站点所需 Users 读边界。 |
| WooCommerce与主题 | 本次不改商品/订单 CRUD、不改 Storefront 或子主题展示；Customer 身份仍来自 WordPress 当前会话。 |
| 输入与权限 | D103 读取守卫用方法、路由、路径数字、有效参数 `id` 和 capability 决策；它本身不执行后台写动作。后续整合 TEST 用真实 Cookie + REST Nonce 复测本人读取与既有客户写守卫；Nonce 不取代 capability。 |
| 数据与输出 | 不写数据库、不更改文章作者 ID、不新增输出字段；拒绝响应由 WordPress REST 编码，PHP 文案经国际化函数。 |
| URL/SEO/缓存 | Users REST 受限读取状态变 403，文章 200 与数字作者链接保持；Title/Meta/Canonical/Schema/robots/sitemap 未直接改写。目标缓存、第三方客户端与索引效果待验，不宣称零性能影响。 |
| 支付、物流、部署 | 无支付、物流、价格、库存、订单改动；只在隔离站验证，目标部署与回滚另验。 |

## 运行证据、动手练习与排错

独立隔离 WordPress 的匿名 HTTP 9/9、角色 REST 15/15、前序 Filter 响应 6/6 通过。访客 Users 集合、数字项、HEAD、大小写路径为 403；匿名 `/me` 为原生 401；Customer 本人数字项和 `/me` 为 200，非本人及 ID 覆盖为 403；编辑三种角色可读。文章 10 原生与 `?_embed=author` 均为 200，作者数字 ID 3 与 `_links` 保留。Batch v1 内层 GET 被 WordPress 核心 schema 返回 400，不能由此推断批量写接口已测。oEmbed 与 RSS2 的 TEST 作者显示名仍公开；这项失败证据直接限制了“已解决”的表述。

### 整合分支的后续隔离证据

后续在 `e078fc5` 的新建 TEST 站以 WordPress 7.1、WooCommerce 11.0.0、DentAll Core 0.9.1 复演；该站未装 Yoast、Breeze 或 Varnish。Customer 经 `wp-login.php` 获得真实 Cookie，再由 `admin-ajax.php?action=rest-nonce`获取 REST Nonce。混合大小写 `GET /wp/v2/UsErS/Me` 返回 200、`id=2`；同一路径和数字 `/2` 的改邮箱 POST 均返回 403 `dentall_account_email_locked`，PATCH 改密码返回 403 `dentall_account_password_form`，小写 POST 对照也是 403。独立复测无 Nonce 的本人 GET 为原生 401，有 Nonce 为 200，改邮箱 POST 为 403。这说明当前整合候选的代表性 Cookie＋Nonce 路径未因大小写绕过既有客户写边界，不能推出所有写端点或正式用户已验。

验证“被拒绝的写请求未落库”时，基线必须取在**登录后、写请求前**：后续请求前后的 `WP_User->data` 与 usermeta SHA-256 严格相等。第一次登录本身会生成 session token 并改变 usermeta，拿登录前快照与登录后相比会把正常会话变化误判为写请求副作用。此比对只覆盖该 TEST 用户的这些数据面，不等于全库不变。

匿名请求公开 TEST Post 的 oEmbed JSON 为 200，`author_name` 与 `author_url` 仍暴露后台作者；RSS2、Atom、RDF 和作者 Feed 均为 200 且含 TEST 后台名。TEST Atom 的作者 `<uri>` 存在并指向此站；目标作者个人网址是否有值待查。没有 Yoast 的此站作者归档为 200；历史 Staging 在 Yoast 关闭作者归档后曾测得 301，不能用其中一个环境替另一个作证。TEST 没有 Breeze/Varnish，旧 REST、oEmbed、Feed 是否命中目标缓存仍未知；D91 只证明 Staging 旧公开 HTML 可被 Varnish 保留。两项 TEST 服务已停，20430/20431 不再监听，运行代码未因本轮复验改变。TEST `http-error.log` 未匹配 Fatal/Warning；`wp-content/debug.log` 有一条 `wp_update_plugins()` 因 `WP_HTTP_BLOCK_EXTERNAL=true` 无法连接 WordPress.org 的 Warning，不应写成所有日志零错误，也不是 REST 守卫运行错误。oEmbed/Feed 若要治理，须另行确认输出范围、品牌 URL、客户端与缓存回滚。

### 2026-10-17公开署名的四条输出路径

用户在A包续作中确认oEmbed/Feed的品牌链接使用现有Blog `/blog/`。源码复核把剩余输出分成四条：oEmbed的JSON/XML共同消费`oembed_response_data`；RSS2/RDF的`dc:creator`和Atom的`name`消费`the_author`；Atom个人网址消费`get_the_author_user_url`，而Yoast `%%AUTHORLINK%%`还消费`author_link`；作者Feed标题由`get_wp_title_rss()`取文档标题，直接读作者对象，绕过`the_author`。因此共享一个URL回调、另设标题回调比全局替换用户显示名更能保留后台语义，也无需覆盖核心Feed模板。

作者Feed可能没有条目；判断标题不能依赖全局当前Post。实现按Feed、非评论、作者查询和`post_type`选择适用范围；空/default、`post`、`any`及包含Post的数组均覆盖，Product-only保持原生标题。条目作者回调仍按当前Post限定。Feed自身的self链接继续保留既有nicename路由，正文人工文本、评论作者、Page/Product和非Feed资料各有自己的语义；这不是全站账号信息脱敏。

品牌链接用`get_post_type_archive_link('post')`复用WordPress的文章页设置。API在未配置静态文章页时可能退回首页；发布前必须核`show_on_front`、`page_for_posts`和实际结果，不能把API回退当作用户批准了首页。oEmbed保持数据值交核心统一编码，Feed文本/URL在对应输出上下文转义；站名含`&`也是XML解析样本。本轮实际回归结果及用户/Post归属摘要见对应项目笔记，不以这段机制说明代替运行证据。

| 练习 | 安全边界与预期 |
|---|---|
| 只读观察 | 在隔离站分别请求 Users 集合、数字作者、文章、`?_embed=author`、oEmbed、RSS2；比较状态码与字段，特别区分 `author` 整数和 `author_name`。 |
| 会话基线 | 用 TEST Customer 登录后才记录用户数据和元数据摘要，再带 Cookie＋Nonce 发代表性本人读取和拒绝写请求；不要把登录生成的 session token 计为写请求结果。 |
| 最小改动与回滚 | 仅在独立 TEST 工作树修改守卫的路由判断，复跑访客/Customer/编辑矩阵；用版本控制恢复文件，不更改共享站点或正式用户。 |
| 故障推演 | 若 Customer 可读员工项，先记录原始方法、路由、查询/body `id` 与响应，再查回调是否加载、优先级及 capability；若文章嵌入破裂，再查 `_links` 与嵌入错误对象。 |

| 症状 | 第一项检查 | 原因与最小验证 |
|---|---|---|
| 小写路径被挡，大写路径可读 | `$request->get_route()` 与正则 | 只大小写敏感匹配时会漏过；用两种路径同测。 |
| 路径是本人，却读出员工 | 有效 `$request['id']` | 请求参数可能覆盖路径 ID；互换路径和参数 ID 测 403。 |
| 文章仍出现作者名字 | 输出实际来自哪个机制 | 对比 Users REST、oEmbed、RSS、HTML 与缓存，不能把一个 REST Filter 当全站过滤器。 |
| 编辑器取不到资料 | 当前 capability 和原生核心权限 | 用 TEST 编辑账号测 `edit_posts`、`list_users` 与原生响应，避免放宽访客规则。 |

## 掌握标准与费曼自测

当前仅有项目技术复演，没有用户本人作答，掌握度仍为“初识”。合格答案须包含通俗解释、准确 API/Hook 和 DentAll TEST 证据；以下问题尚未评分。

1. 为什么文章保留 `author=3`，仍可以限制匿名读取作者资料？
2. 记忆宫殿中的“档案柜、工作证、馆外摘要”分别对应哪个真实机制？比喻在哪儿失效？
3. 一次匿名 `/wp/v2/users/3` 从路由匹配到 403，按顺序由哪些对象处理？
4. 代码为何同时比较路径 ID 与 `$request['id']`，且正则必须大小写不敏感？
5. 为什么 `?_embed=author` 的文章 200 不代表嵌入的作者资料也成功？
6. oEmbed/RSS 仍有作者显示名时，应如何界定本次修复范围和下一步治理？
7. 若另一个站点使用不同作者展示策略，哪些授权原则可迁移，哪些 WordPress 细节要重新验证？

## 间隔复习与后续提问

| 节点 | 计划日期 | 状态 | 复查主题 |
|---|---|---|---|
| D+1 | 2026-10-09 | 待复习 | Filter 顺序与前序错误。 |
| D+3 | 2026-10-11 | 待复习 | 路径和请求参数 ID 覆盖。 |
| D+7 | 2026-10-15 | 待复习 | `_embed`、oEmbed、RSS 的不同输出路径。 |
| D+14 | 2026-10-22 | 待复习 | 目标缓存和真实内容验收结论。 |

向 AI 提问时先给 WordPress/WooCommerce/PHP/Core 版本、请求方法与完整路径、角色/capability、最小真实代码、状态码和响应字段，并注明是否经过缓存；要求它先区分“已证事实、源码推断、待验证项”，给出隔离站的最小复现与回滚。不要提供 Cookie、Nonce、真实客户资料或密钥。

## 变种应用到其他项目

| 场景 | 保持不变的原则 | 必须重新查证 |
|---|---|---|
| 另一个 WordPress 主题 | 身份资料最小授权、所有公开出口逐项核对 | WordPress版本、作者内容策略、插件 Filter 与缓存。 |
| 区块主题或无 WooCommerce 站点 | 服务端读权限不依赖主题样式 | Posts/Users REST、区块嵌入和主题公开作者输出。 |
| Shopify或其他平台 | 区分员工身份、内容署名与凭据；按输出通道复验 | 具体 API、内容作者字段、权限、Feed 与缓存机制均待官方资料和实测验证。 |

## 可复用核心思想

### 跨平台不变量

安全边界须按数据来源、请求主体和输出通道定义。修好一个接口只能证明该接口的访问规则，不能推出缓存、嵌入、订阅或 HTML 已同步收紧。

### WordPress/WooCommerce当前实现

本项目在WordPress的`rest_request_before_callbacks`对Users REST GET/HEAD做定向授权，保留本人和`edit_posts`/`list_users`访问；整合TEST的Cookie＋Nonce验证既有客户写守卫。10月17日另用oEmbed/Feed的定向Filter统一Post自动作者名和Blog链接，WP7.1.3/Yoast28.2组合隔离通过；作者归档、缓存与目标实配仍单独验收。登录会写入会话元数据，比对应从登录后开始；WooCommerce交易数据不由此作者治理改写。

### Shopify或其他平台的对应机制

同类目标可迁移，API、角色、内容署名、订阅输出与缓存合同不能照搬 WordPress；具体对应机制待验证，未纳入 DentAll 第一版实施范围。
