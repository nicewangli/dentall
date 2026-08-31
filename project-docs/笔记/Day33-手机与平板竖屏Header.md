---
项目: DentAll WooCommerce
工作日: D33
计划检查点: D33（不自动等于一个完整实际工作日）
日期: 2026-08-27
计划工时: 6小时50分钟有效工作
实际有效工时: 未记录；不使用计划工时代填
验收层级: Local技术验收
状态: 已完成D33推荐最小范围；Cart Block同页即时数量桥接与平板最终收敛按后续节点接续
tags:
  - DentAll
  - Day33
  - Header
  - Navigation
  - WooCommerce
---

# DentAll 每日复盘 D33：手机与平板竖屏Header

## 相关笔记

- 每日笔记索引：[[README|DentAll每日笔记索引]]
- 前置项目笔记：[[Day32-PC主导航与一级下拉]]
- 当日学习笔记：[[WordPress实战笔记/Day33-单一导航DOM与购物车Fragment]]
- 前置学习笔记：[[WordPress实战笔记/Day32-原生菜单与Storefront下拉机制]]
- 后续项目笔记：[[Day34-平板横屏Header与断点收敛]]
- 后续计划：D34平板最终密度与触控收敛、D47搜索结果、D69购物车与Cart Block整合

> [!success] 当前结论
> D33已按用户确认的最小范围在Local落地：手机与768px平板竖屏的Logo实际图片精确居中，菜单在左，Account与带真实商品数量的Cart在右；搜索只显示`Search products…`占位词，手机放大镜恢复完整尺寸。移动导航复用唯一Primary菜单DOM和Storefront原生按钮，展开为非模态面板，一级子项常显，没有新增JavaScript。子主题版本为`0.11.10`。

## 用户授权与设计纠正

实施授权为：

> 同意按Day33推荐最小范围实施：单一Primary DOM、非模态展开面板、一级子项常显、不新增JS、仅Local。

实施过程中，用户根据设计稿补充并纠正：

1. 手机端Logo必须居中；
2. 平板竖屏Logo也必须居中；
3. Account与Cart靠右，Cart显示商品数量；
4. 搜索放大镜不能缩小或残缺；
5. 所有端不显示可见的`Search for:`，只保留`Search products…`。

这些要求均按整体Header关系统一复核，而不是分别堆叠临时偏移。

## 今日三个验收结果

- [x] 390与768实际Logo图片中心相对视口偏差均为0；左侧Menu和右侧Account/Cart动作组无碰撞，375、390、414、768均无横向溢出。
- [x] 唯一Primary菜单DOM在手机与平板竖屏复用；非模态面板关闭时不可见、不可点击，展开后一级子项常显；无Handheld第二菜单、无Handheld Footer、无新增JavaScript。
- [x] Header Cart读取WooCommerce真实数量并保留经典fragment刷新；搜索输入只显示`Search products…`，可访问名称仍保留，手机按钮与输入均为48px高且放大镜完整。

## 实际实现

### WordPress与Storefront输出边界

- `storefront_header`继续是Header输出入口。
- Storefront的Primary导航包装器、导航和关闭包装器从原优先级移到10/11/12，使同一导航DOM进入Header网格。
- 子主题在`after_setup_theme`优先级40注销`handheld`位置，防止以后误绑后输出第二棵菜单树。
- Storefront原Handheld Footer DOM及其只服务该DOM的脚本被移除；Account和Cart已经在Header提供入口。
- 搜索仍由WooCommerce/Storefront原生商品搜索表单输出，没有复制表单模板。

### Header布局

- 手机采用三列Grid：左侧Menu、中间品牌、右侧动作组；两侧轨道等宽，Logo链接和图片自身都使用自动外边距，因此居中基于视口而不是“看起来接近”。
- 768px平板竖屏沿用相同三列关系，中间品牌轨道扩大到220px；Logo图片实测左右各42px自动外边距。
- Account与Cart共享右侧动作区，各为44×44px，间距为0；390右侧gutter为20px，768为32px。
- 1024～1199继续遵守D27/D32冻结边界，不提前显示完整PC导航；`>=1200px`恢复D32桌面导航。

### 搜索

- 删除D31把`.screen-reader-text`做成可见浮动标签的规则，所以所有宽度不再看见`Search for:`。
- 原生Label仍以1×1裁剪方式为屏幕阅读器提供可访问名称；输入框只显示原生`Search products…`占位词。
- 手机搜索输入与按钮均为48px高，按钮宽48px；放大镜由20px圆环和10px斜柄组成，并显式恢复父主题隐藏的`::after`。
- 768及以上恢复文字型Search按钮，避免把手机图标样式机械放大到所有端。

### 动态购物车数量

- `dentall_cart_link()`从`WC()->cart->get_cart_contents_count()`读取数量，而不是把`0`写死在模板。
- 可见数字放在`.dentall-cart-count`徽标中；`aria-label`使用单复数国际化文案，数字徽标本身对辅助技术隐藏，避免重复朗读。
- `woocommerce_add_to_cart_fragments`继续返回`a.cart-contents`，经典加购流程可以替换同一链接。
- `woocommerce_cart_fragment_name`为D33标记结构使用固定的`_dentall_header_v1`浏览器存储键，防止D31旧`sessionStorage`片段在升级后覆盖新徽标；这是一次标记结构缓存迁移，不随每个CSS版本反复变化。
- 保留Storefront Mini Cart容器，只替换顶端链接结构；768～1199的Mini Cart宽度已独立复核，不再继承44px动作按钮宽度。

> [!warning] Cart Block边界
> WooCommerce Cart Block在当前页直接改数量走Store API和Blocks事件，不会天然反向触发经典`wc_fragment_refresh`。D33按用户明确的“不新增JS”边界，只验收首屏服务端真实数量、页面刷新后的真实数量及经典fragment；不在本轮偷偷增加Blocks事件桥接。Cart Block同页即时同步并入D69购物车整合验收，不能把当前实现表述为所有Blocks交互都已实时同步。

### 移动导航状态

- Menu按钮继续由Storefront原生`navigation.js`切换`.toggled`，本轮JavaScript改动为0。
- 面板是Header下方的绝对定位非模态区域，不创建遮罩、不锁定页面滚动、不冒充Dialog。
- 关闭态同时使用`visibility:hidden`、`opacity:0`和`pointer-events:none`；展开态恢复可见和可操作。
- 一级子菜单在移动面板中使用静态布局并常显，不增加每一项独立开合状态。
- `prefers-reduced-motion: reduce`时取消面板过渡，但保留开闭状态。

## 修改文件

| 文件 | 变更 |
|---|---|
| `app/public/wp-content/themes/dentall/inc/storefront-hooks.php` | 重排Primary导航Hook；输出动态Cart链接、Mini Cart容器和fragment；迁移旧fragment缓存键；移除Handheld重复入口 |
| `app/public/wp-content/themes/dentall/inc/setup.php` | 保持全站壳层CSS加载并移除已无DOM消费者的Handheld Footer脚本 |
| `app/public/wp-content/themes/dentall/assets/css/site-shell.css` | 手机/平板Header、非模态导航、搜索、动作组、Cart徽标、桌面复位与Reduced Motion |
| `app/public/wp-content/themes/dentall/style.css` | 子主题缓存版本从`0.10.0`升至`0.11.10` |

没有新增运行文件、模板覆盖、插件、依赖、构建链或自定义JavaScript。

## 验证证据

### 四端与边界

- 375/390/414：Logo中心偏差0；Menu、Account、Cart均44px；搜索输入与按钮均48px；无横向溢出。
- 768：实际Logo图片中心为384px，视口中心为384px；Account与Cart各44px，右侧gutter 32px；无横向溢出。
- 1024：保持D34前的中间宽度收敛状态；搜索、Account、Cart、真实数量和无横溢出通过，完整PC导航不提前出现。
- 1440：Menu切换按钮隐藏，D32的9个顶级Primary项继续显示；只有一棵Primary菜单，Handheld为0；无横向溢出。

### 搜索与购物车

- 所有抽查宽度的可见`Search for:`规则为0；原生可访问Label保持1×1裁剪，placeholder为`Search products…`。
- 空购物车徽标显示`0`；CLI内存态数量`1`和`12`分别输出相应徽标及正确单复数可访问文案，全程未持久化购物车数据。
- fragment响应只包含`a.cart-contents`和Mini Cart内容，不再包含Handheld Footer fragment；自定义链接中数量类为1，旧金额结构为0。

### 静态、Hook与资源

- `storefront-hooks.php`、`setup.php`、`functions.php`通过`php -l`。
- 最终`site-shell.css`为770行、19620字节、102/102对花括号、0个`!important`，SHA-256为`8F2A2C6A80B5C0B26A7AA8CEF47413674D995ED570C6998CD6A057BEB6CA0585`。
- Local HTTP读取`site-shell.css?ver=0.11.10`返回19620字节，SHA-256与磁盘一致。
- 运行时导航Hook为10/11/12，DentAll Cart为40、fragment为20；父Cart、父fragment、Handheld Footer均未注册。
- 运行时只注册Primary/Secondary位置，实际映射仅`primary => 25`；页面`#site-navigation`为1，Handheld导航和Footer均为0。
- Handheld Footer脚本HTTP输出为0；`git diff --check`通过；JavaScript变更数为0。

## 独立复核

- 设计复核最初发现768品牌轨道居中但实际Logo图片仍偏左42px的P1；修复图片与链接自身的自动外边距后，390/768实际图片中心偏差均为0，P1关闭。
- Code Review最初发现768～1199 Mini Cart可能继承44px按钮宽度的P1，及Handheld误绑、死脚本两个P2；调整Mini Cart宽度、注销Handheld位置并dequeue脚本后全部关闭。
- 0.11.9增量复核确认Reduced Motion只关闭过渡，不改变开闭状态；0.11.10继续补上旧fragment浏览器缓存迁移。
- 0.11.10 Code Review确认公开过滤点、运行时新键、无后置覆盖、一次刷新和回滚路径正确；不改变Cart Hash、Cookie、Woo Session、商品或订单。旧键只在现有标签的`sessionStorage`暂留，关闭标签后自然清除；当前P0/P1/P2/P3代码缺陷为0。
- 独立测试完成静态、Hook、fragment、新缓存键、动态12件内存态及Shop的390/768/1024/1440运行时检查；当前范围P0/P1为0。完整键盘开合链和另外四类页面的四宽批量回归因浏览器控制超时列为P2证据缺口，不冒充已通过。

## 减法审查与量化

- 相对D32基线，运行文件净增0个；修改4个既有文件。
- `site-shell.css`相对D32净增306行、7359字节、37个规则块；增加来自同一DOM在手机/平板的布局、导航开闭、动作组、搜索图标、Cart徽标及桌面复位，没有用拆文件或压缩写法隐藏复杂度。
- PHP净增4个小函数：Cart链接、Cart容器、fragment回调、固定版本的浏览器fragment存储键；原Header配置与enqueue函数只改名和扩展职责，不算新增能力壳层。
- JavaScript文件/函数、模板覆盖、插件、依赖均净增0；同时移除1个已无DOM消费者的父主题脚本请求。
- 已删除可见浮动Search Label、Handheld重复动作入口和第二菜单位置；没有预写遮罩、焦点陷阱、Escape逻辑、多级手风琴、Cart Blocks桥接或D34平板最终规则。
- CSS资源确有7359字节增量，且没有做前后性能测量，因此不宣称性能零影响；请求数因移除死脚本反而少1，但也不据此宣称页面一定更快。

## 未验证项与移交

| 未验证或未完成 | 原因 | 接续点 |
|---|---|---|
| 真实物理手机/平板触屏 | 当前证据来自Local桌面浏览器模拟和DOM/计算样式 | D34真机触控回归 |
| Enter展开→再次关闭→焦点留在Menu的完整键盘链 | Chrome控制在交互时超时；关闭态`aria-expanded=false`、不可见、不可点击已证实 | 负责人：开发者/Codex；D34首先补验390与768 |
| Home、Simple、Cart、Account各自四宽批量回归 | Shop四宽已完成；全站共用Hook/DOM/CSS但不能替代页面证据 | 负责人：开发者/Codex；D34用390/768/1024/1440批量补验 |
| Cart Block当前页改数量后Header即时同步 | 需要Blocks事件/Store API与经典Header的JS桥接，超出“不新增JS” | D69购物车整合 |
| 搜索提交、结果页、空结果与错误拼写 | D33只修Header表单呈现 | D47搜索页 |
| 非空Mini Cart完整交互 | D33验证结构、宽度与数量fragment，不制造持久化购物车场景 | D69 |
| 正式Logo、正式菜单URL与非Local | 正式素材/内容及部署闸门未开放 | 素材到位、内容节点及独立部署验收 |
| 1024与平板横屏最终密度 | D33保留D27/D32冻结的`>=1200px`PC导航边界 | D34 |

## 数据、URL与系统影响

- 数据：没有写入商品、订单、客户、库存、价格、菜单或设置；动态数量来自当前WooCommerce Session，CLI数量测试仅内存态。
- URL/SEO：没有修改Slug、固定链接、Title、Meta、Canonical、Schema、robots或Sitemap；现有TEST菜单URL继续沿用D32边界。
- 缓存与性能：子主题版本升至`0.11.10`刷新CSS缓存键；经典fragment切换到固定D33存储键以避开旧Header HTML，旧浏览器键会自然失效但不会主动删除；CSS增加7359字节，移除1个死的Handheld Footer脚本请求；无新查询、远程请求、Cron或autoload Option。
- 支付、物流、交易：没有更改支付、税费、物流、价格、库存、结账、订单或退款逻辑。
- 部署：仅Local；Staging、Production、DNS、索引保护和真实支付均未改变。

## 下一步

1. D34在768、1024、1199/1200及真实触屏设备收敛平板竖屏/横屏和中间宽度，不改动已通过的390/768居中关系。
2. D47验证搜索提交、搜索结果、空结果和错误输入；继续保留可访问Label，不重新显示`Search for:`。
3. D69以Cart Block为真实数据源验证同页数量变更、非空Mini Cart、Add to Cart与跨页Session，再决定是否引入最小事件桥接。

## 可复用核心思想

### 跨平台不变量

- 真正的Logo居中要测量图片相对视口的几何中心；只让某个Grid轨道居中并不能证明图片居中。
- 购物车徽标必须来自真实状态，并明确“首屏正确、经典事件刷新、现代Store事件刷新”是三种不同验收层级。
- 同一内容树应尽量只输出一份DOM；响应式主要改变布局和可见状态，不应复制菜单、搜索或账户入口。

### WordPress/WooCommerce当前实现

- Storefront负责Primary菜单DOM与原生切换状态，DentAll通过Hook重排和Mobile First CSS复用它；`unregister_nav_menu()`关闭不再使用的Handheld插槽。
- WooCommerce Header数量来自`WC()->cart`并通过经典`woocommerce_add_to_cart_fragments`刷新；标记结构升级时要同步迁移`woocommerce_cart_fragment_name`，否则旧浏览器缓存可能覆盖新DOM。Cart Blocks使用另一套Store API事件，不能假设两者自动互通。
- 可见提示词和可访问名称职责不同：本轮隐藏视觉`Search for:`，但保留语义Label，placeholder不替代Label。

### Shopify或其他平台

- 其他商城也应验证“服务端初始状态＋客户端购物车状态源＋跨组件同步事件”，不能只看一个静态数字；具体Shopify Cart API、主题事件和Section刷新机制本轮未验证。
- 可迁移的是单一语义结构、真实状态源、几何测量和分层验收，不是Storefront类名、WordPress Hook或WooCommerce fragment键。
