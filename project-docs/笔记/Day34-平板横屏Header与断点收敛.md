---
项目: DentAll WooCommerce
工作日: D34
计划检查点: D34（不自动等于一个完整实际工作日）
日期: 2026-08-28
计划工时: 6小时50分钟有效工作
实际有效工时: 未记录；不使用计划工时代填
验收层级: Local技术验收
状态: 已完成；Local自动化与用户确认的实体设备验收均通过
tags:
  - DentAll
  - Day34
  - Header
  - Responsive
  - Accessibility
---

# DentAll 每日复盘 D34：平板横屏Header与断点收敛

## 相关笔记

- 每日笔记索引：[[README|DentAll每日笔记索引]]
- 前置项目笔记：[[Day33-手机与平板竖屏Header]]
- 当日学习笔记：[[WordPress实战笔记/Day34-CSS断点级联与可访问状态]]
- 前置学习笔记：[[WordPress实战笔记/Day33-单一导航DOM与购物车Fragment]]
- 后续项目笔记：[[Day35-PC页脚与Newsletter测试壳层]]
- M4整链路复核：[[Day42-首页全链路校准与M4技术验收]]
- 后续计划：D36手机和平板页脚、D47搜索结果、D69 Cart Blocks同步

> [!success] 当前结论
> D34已按用户确认的最小范围在Local落地：1024～1199继续使用单一Primary DOM的紧凑菜单，`>=1200px`才切换完整PC导航。实现只调整子主题CSS与缓存版本，没有新增JavaScript、插件、模板、运行文件或数据库写入。独立代码与设计复核最终P0/P1/P2/P3均为0；断点、键盘、Skip Link、Reduced Motion CSS级联与五类页面20/20跨页回归均已通过。用户于2026-08-28明确确认D34实体设备验收通过；未提供设备型号与浏览器版本，因此只记录人工验收结论，不补写不存在的设备细节。

## 用户授权与项目总规范补充

本次实施授权为：

> 同意按Day34推荐最小范围实施：1024–1199保留紧凑菜单，1200以上使用完整PC导航；仅Local、优先CSS、不新增JS/插件/模板，并按上述三项验收。

同一轮还补充项目总规范：当设计稿信息不足、无法支撑手机、平板竖屏、平板横屏和PC四端时，可提出使用GPT Image 2补全设计证据；但每次生成或编辑图片前必须先说明缺口、范围与用途，并取得用户明确确认。D34现有四端设计包、断点文档和用户确认已足够，因此本轮没有生成图片。

## 今日三个验收结果

- [x] 390、768、1024、1199、1200、1366和1440均只有一个`#site-navigation`；1199保持44×44px菜单按钮与关闭抽屉，1200隐藏按钮并显示9个一级项；各宽度无横向溢出。
- [x] 390、768、1024的Enter开→关、`aria-expanded`与焦点返回均通过；390 Tab/Shift+Tab不会进入隐藏菜单，1024展开态13个链接可见；Skip Link已实际聚焦并激活到`#site-navigation`，同构原生片段夹具确认下一次Tab进入Menu；真实父/子主题CSS在强制Reduced Motion的浏览器夹具中全部变为`transition:none`。用户于2026-08-28确认实体设备触摸、方向切换与设备级Reduced Motion验收通过。
- [x] 最新0.12.0跨页批量回归完成20/20：Home、Shop、Simple Product、Cart、My Account在390/768/1024/1440均保持唯一导航、正确紧凑/PC状态、Header/Search/Account/Cart入口和0横向溢出。

## 7个专注周期的实际落点

1. C1：对照总计划、D33移交、四端设计包和现有CSS，确认1024～1199存在导航空窗风险。
2. C2：冻结最小方案为“紧凑菜单延续至1199，完整PC导航从1200开始”，不改变DOM或Storefront脚本。
3. C3：把768～1023的紧凑Header媒体查询延伸到1199，并删除1024起提前隐藏导航的冲突规则。
4. C4：把Logo左对齐、Account可见文字和完整导航统一放入`>=75rem`桌面增强区。
5. C5：浏览器实测390～1440的DOM、计算样式、几何中心、菜单状态与溢出。
6. C6：独立Code Review发现关闭态子菜单和Reduced Motion两项P2；完成最小修复并复审关闭。
7. C7：完成静态、HTTP、PHP、独立设计/测试复核、减法审查、项目状态与学习笔记收尾。

## 实际实现

### 断点职责

- 基础规则负责手机与紧凑菜单的共同状态。
- `48rem`继续负责平板gutter和搜索按钮增强。
- `48rem～74.999rem`统一使用三列紧凑Header：左Menu、中间Logo、右Account/Cart，Search单独占第二行。
- `64rem`只增加公告栏密度，不改变导航模式。
- `75rem`开始左对齐Logo、显示Account文字、隐藏Menu按钮并把Primary菜单静态横向展开。

### 关闭态与Reduced Motion修复

- 子菜单默认设为`visibility:hidden`与`pointer-events:none`，避免祖先面板关闭时，子项把自身重新设为可交互。
- 仅在`<1200px`且`.main-navigation.toggled`时恢复子菜单可见和可操作。
- `prefers-reduced-motion: reduce`同时关闭面板、Storefront内层`ul.nav-menu`及Menu按钮三条伪元素的过渡，不能只关外层动画。

### 保持不变的职责

- WordPress菜单term、Primary位置、菜单项和正式URL均未修改。
- Storefront原生`navigation.js`继续负责Menu按钮和`.toggled`；JavaScript改动为0。
- D33的动态Cart、Search、Account、唯一Primary DOM和经典fragment契约保持不变。
- Cart Blocks同页数量桥接仍属于D69；搜索提交/结果仍属于D47。

## 修改文件

| 文件 | 变更 |
|---|---|
| `AGENTS.md` | 增加“四端设计证据不足时可提议GPT Image 2，但生成前必须取得用户确认”的项目总规范 |
| `app/public/wp-content/themes/dentall/assets/css/site-shell.css` | 延伸紧凑Header到1199；收拢1200桌面增强；修复关闭态子菜单与Reduced Motion级联 |
| `app/public/wp-content/themes/dentall/style.css` | 子主题缓存版本由`0.11.10`提升至`0.12.0` |
| `project-docs/PROJECT_STATE.md`及D34索引/笔记 | 记录授权、证据、风险、影响和后续边界 |

没有新增运行文件、PHP函数、JavaScript、模板覆盖、插件、依赖或构建链。

## 验证证据

### 登录态浏览器与断点

- 390：Logo中心偏差0；Menu、Account、Cart均44×44px；Search为350×48px；横向溢出0。关闭态DOM快照不再暴露菜单子链接。
- 768：Logo中心偏差0；三项操作均44×44px；Search为704×48px；紧凑菜单关闭；溢出0。
- 1024：唯一导航；Menu、Account、Cart均44×44px；Search为945×48px；展开面板宽1009px、高437px，13个链接最小高度44px；溢出0。
- 1199：仍为紧凑菜单，Account文字保持视觉隐藏，Logo几何居中，溢出0。
- 1200：`(min-width:75rem)`命中；Menu按钮`display:none`，Account文字恢复，9个一级项静态显示，溢出0。
- 1366与1440：完整PC导航保持9个一级项，无横向溢出；1440导航宽1216px，左右约112px安全空间。

### 键盘与状态

- 390、768、1024独立测试均确认Enter开/关时`aria-expanded`依次为`false → true → false`，关闭后焦点仍在Menu按钮。
- 390关闭态Tab从Menu进入Logo，Shift+Tab从Logo返回Menu，没有进入隐藏导航。
- 收起快照只显示Menu按钮，不包含菜单链接；展开后一级项与一级子项可见。
- 关闭态面板和子菜单均为不可见、不可点击；桌面子菜单仍只在Hover、`.focus`或`:focus-within`时展开。
- Skip Link在390px已实际聚焦并激活：URL片段切换为`#site-navigation`，目标导航可见。Chrome原生片段跳转后`activeElement`回到`BODY`；使用相同链接、导航目标与Menu顺序的独立夹具确认，下一次Tab进入Menu。该结果记录浏览器原生行为，不为追求焦点驻留额外增加`tabindex`或JavaScript。
- Reduced Motion自动化夹具加载Storefront与DentAll真实CSS：普通偏好下外层面板为0.2s、内层菜单为0.8s、三条伪元素为0.2s；强制`reduce`后五个目标均为`transition-property:none`、`duration:0s`。该证据证明CSS级联，不替代实体设备触感。

### 跨页批量证据

- Home、Shop、Simple Product、Cart、My Account在390/768/1024/1440共20组全部通过：每组Header、Search、Account、Cart均存在，`#site-navigation=1`，顶级菜单为9项，紧凑/PC状态正确且横向溢出0。
- 390/1024均显示44×44px Menu并保持关闭态；768同样保持紧凑Header；1440隐藏Menu并显示完整PC导航。
- 768的Home没有纵向滚动条，`clientWidth=768`；其余四页因纵向滚动条为753px，但均满足`scrollWidth === clientWidth`，不是横向溢出。

### 静态、HTTP与PHP

- `site-shell.css`最终为779行、20045字节、103/103对花括号、0个`!important`。
- SHA-256为`939573937FAB8F131EB7F414658D28C98536D456B789A528769121E24F5D90D1`；Local HTTP的`?ver=0.12.0`为20045字节且逐字节相同。
- `functions.php`、`inc/setup.php`、`inc/storefront-hooks.php`共3个PHP文件通过`php -l`。
- `git diff --check`通过；仅有Git对未来LF→CRLF转换的提示，不是内容错误。

## 独立复核

- Code Review首轮发现2项P2：关闭面板内的子菜单可能重新进入Tab/指针层；Reduced Motion没有覆盖Storefront的内层菜单和按钮伪元素。两项修复后复审为P0/P1/P2/P3=0。
- 设计复核实测390/768/1024/1199/1200/1440的Logo、操作尺寸、Search、展开面板、链接高度和溢出，未发现P0/P1/P2。
- 1024图是生成的方向草图，图中横向导航与配套断点文档冲突；项目断点文档及本次用户明确授权优先，所以1024～1199采用紧凑菜单是有意决定，不是还原偏差。
- Local首页正文目前仍是默认WordPress博客内容；D34只验收Header，不能据此评价完整首页还原度。

## 减法审查与量化

- 运行文件净增0；只修改既有`site-shell.css`与`style.css`，没有复制四套DOM。
- 单纯断点修正一度使CSS相对D33减少2行、13字节和1个规则块：删除1024起提前隐藏导航及提前切换Logo/Account的重复职责。
- 为关闭独立审查发现的两个可访问性P2，最终相对D33基线净增9行、425字节和1个规则块；这些增量只承担关闭态子菜单与Reduced Motion边界，不是预实现新功能。
- PHP函数、JavaScript文件/函数、模板、插件、依赖、数据库字段和HTTP请求均净增0。
- 没有做前后性能测量，因此只记录CSS字节变化，不宣称性能“零影响”或必然提升。

## 未验证项与移交

| 未验证或未完成 | 原因 | 负责人、计划与最晚节点 |
|---|---|---|
| 正式Logo、菜单URL与内容当前态 | 正式素材和业务URL尚未冻结 | 素材/内容节点；部署前重新回归内部链接与SEO输出 |
| 搜索提交、结果/空结果 | D34只验证Header表单布局 | D47 |
| Cart Blocks同页数量同步、非空Mini Cart | 需要独立状态事件验收，且可能引入最小JS | D69，编码前重新确认 |
| Staging/Production | 本次授权仅Local | 独立部署确认后执行 |

## 数据、URL与系统影响

- 数据：未写入商品、订单、客户、库存、菜单、设置、Option或数据库。
- URL/SEO：未修改Slug、固定链接、Title、Meta、Canonical、Schema、robots、Sitemap或菜单目标；TEST URL继续只是骨架。
- 缓存与性能：版本升至`0.12.0`刷新既有CSS查询参数；请求数不变，CSS相对D33增加425字节；无新查询、远程请求、Cron或autoload Option。
- 支付、物流与交易：未改变价格、库存、税费、支付、物流、购物车数据、结账、订单或退款。
- 部署：仅Local；Staging、Production、DNS、索引保护和真实支付均未改变。

## 下一步

1. D34实体设备证据已由用户确认关闭；后续若指定设备出现回归，按新证据重新打开缺陷而不是改写本次结论。
2. [[Day35-PC页脚与Newsletter测试壳层]]按已确认最小范围实现PC Footer骨架；真实Newsletter、社交和支付仍需独立业务确认。
3. D36再完成手机/平板页脚、Footer菜单后台绑定及全局壳层四端回归；D47和D69继续保留搜索与Cart Blocks的独立边界。

## 可复用核心思想

### 跨平台不变量

- 断点应由内容“何时放不下、何时需要改变交互模式”决定，而不是机械追随某一张截图的画布宽度。
- 响应式验收必须同时检查结构数量、可见状态、交互状态和几何溢出；只看截图不能证明关闭态不会进入Tab顺序。
- Reduced Motion要沿实际动画调用链逐层关闭，不能只处理最外层容器。

### WordPress/WooCommerce当前实现

- WordPress保存菜单数据，Storefront输出唯一Primary DOM并用原生`navigation.js`切换`.toggled`；DentAll只用Mobile First CSS决定紧凑或完整导航。
- `rem`媒体查询在当前默认16px根字号下使`75rem`对应1200px；若根字号策略变化，必须重新验证而不是把换算当永久事实。
- Header是全站公共输出，页面回归应验证同一Hook与DOM在Home、Shop、商品、Cart和Account中的实际存在，不能把单页通过外推为全站通过。

### Shopify或其他平台

- 其他平台同样应保持单一导航内容源、单一语义结构、内容驱动断点和可访问开闭状态；具体主题模板、菜单对象和前端状态API需要按平台重新验证。
- Shopify Navigation、Theme Sections及相关交互事件的具体映射本轮未实际验证，标记为待验证，不进入DentAll第一版实施范围。
