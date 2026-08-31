---
项目: DentAll WooCommerce
工作日: D35
计划检查点: D35（不自动等于一个完整实际工作日）
日期: 2026-08-28
计划工时: 6小时50分钟有效工作
实际有效工时: 未记录；不使用计划工时代填
验收层级: Local技术验收
状态: 推荐最小范围已在Local实现；正式Footer内容与后台绑定转D36
tags:
  - DentAll
  - Day35
  - Footer
  - Newsletter
  - Storefront
---

# DentAll 每日复盘 D35：PC页脚与Newsletter测试壳层

## 相关笔记

- 每日笔记索引：[[README|DentAll每日笔记索引]]
- 前置项目笔记：[[Day34-平板横屏Header与断点收敛]]
- 当日学习笔记：[[WordPress实战笔记/Day35-Storefront页脚Hook与菜单数据契约]]
- 前置学习笔记：[[WordPress实战笔记/Day34-CSS断点级联与可访问状态]]
- 后续项目笔记：[[Day36-手机与平板页脚和后台菜单绑定]]

> [!success] 当前结论
> D34实体设备缺口已由用户明确确认通过。D35在Local新增一套Storefront Hook驱动的Footer骨架：一个深度为2的`footer`菜单位置、动态站点品牌、Storefront原生动态版权，以及只在Local出现且明确不可提交的Newsletter预览。Footer菜单尚未绑定时只显示`[TEST]`空状态；未确认的社交账号、支付方式和业务链接完全不输出。正式菜单内容与手机/平板最终重排仍按D36验收，不能把当前空状态写成五列业务内容已完成。

## 用户授权

本次实施授权为：

> D34实体设备：已通过，同意按Day35推荐最小范围实施：仅Local、一个两级Footer菜单、Newsletter只做明确不可提交的测试壳层、未确认的社交和支付项不渲染。

授权边界解释：

- “一个两级Footer菜单”本日落实为菜单位置、单一DOM输出和`depth => 2`数据合同；计划已把原生后台绑定放在D36，因此D35不写菜单term、菜单项或Theme Location数据库配置。
- Newsletter只冻结可访问结构、视觉与不可提交状态，不创建`form`、字段`name`、提交端点、REST/AJAX、邮箱存储或第三方账户连接。
- “仅Local”既是本次部署边界，也是Newsletter预览的运行时边界；`wp_get_environment_type()`不是`local`时不输出该预览。
- 社交与支付没有正式URL、网关和素材事实，因此PHP中不输出占位链接、图标或品牌名称。

## 今日三个验收结果

- [x] 用户确认D34实体设备验收通过；D34项目笔记已关闭真实触摸、方向切换与设备级Reduced Motion缺口，同时保留“未提供设备型号/浏览器版本”的证据边界。
- [x] 子主题0.13.2新增独立Footer模块，通过`storefront_before_footer`和`storefront_footer`输出单一语义结构；注册一个两级`footer`菜单位置，未绑定时不回退为全部Page；Newsletter为0个表单、2个原生禁用控件。
- [x] 390、768、1024、1199、1200、1366和1440均无横向溢出；1440与390截图完成视觉核验，Home、Shop、Simple Product、Cart、My Account在390px均保持Newsletter/Footer唯一输出、0个表单和0溢出。

## 7个专注周期的实际落点

1. C1：对照总计划、D34移交、四端设计证据和Storefront父主题Footer Hook，确认D35/D36职责边界。
2. C2：冻结单一两级菜单、Local不可提交Newsletter、社交/支付不输出及空菜单诚实状态。
3. C3：新增`inc/site-footer.php`，注册菜单并接管Storefront Footer Widget/Credit扩展点，不覆盖`footer.php`。
4. C4：在既有`site-shell.css`建立Mobile First安全基线和`>=1200px`桌面增强。
5. C5：实际截图发现Storefront clearfix伪元素参与Grid、父主题Footer链接颜色压过品牌两项级联问题，并完成最小修正。
6. C6：完成PHP、CSS、HTTP资源、7个宽度和5类页面的静态/浏览器验证。
7. C7：完成独立代码、测试和视觉复核、减法审查、状态文档与WordPress实战学习笔记。

## 实际实现

### Hook与文件职责

- `functions.php`只增加一个模块加载入口。
- `inc/site-footer.php`承担Footer菜单注册、Storefront Hook配置、Newsletter预览和Footer主体输出；不继续把新领域堆入已有Header/Catalog混合模块。
- `site-shell.css`继续承载与Header同生命周期的全站壳层样式；没有为了一个组件新增第二个全站CSS请求。
- Storefront父主题`footer.php`保持原样；子主题没有模板覆盖、自定义JavaScript、插件或构建链。

### Newsletter不可提交合同

- 仅`wp_get_environment_type() === 'local'`时输出。
- 使用`section`、可见`label`、禁用`input[type=email]`和禁用`button[type=button]`。
- 不存在`form`、`name`、`action`、submit按钮、远程请求或持久化写入。
- 可见文案同时写明`[TEST] Preview only`和`Newsletter sign-up is not connected in this Local preview.`，不伪造成功状态。

### Footer菜单合同

- Theme Location键为`footer`，后台标签为`Footer navigation`。
- `wp_nav_menu()`只输出一棵菜单，`depth => 2`，`fallback_cb => false`。
- 顶级真实链接未来作为栏目入口，二级项作为栏目链接；不使用`#`伪链接，也不硬编码五套列表。
- 未绑定时Local显示`[TEST] Footer navigation is not assigned.`；非Local不显示测试提示。
- 品牌使用动态站点名和`home_url()`；版权继续复用Storefront动态年份/站点名，移除`Built with WooCommerce`供应商Credit，保留系统Privacy链接的可能性。

### 响应式职责

- 基础层让Newsletter、禁用控件、Footer品牌和空状态单列堆叠，390～1199保持完整内容且不溢出。
- `75rem`开始让Newsletter变为“文案＋预览控件”双列，Footer主体变为“导航＋品牌”，未来恰好五个顶级菜单项时使用五列Grid。
- D36再决定手机/平板菜单折叠或重排；D35不复制DOM，也不因390参考图省略链接而删除内容。

## 修改文件

| 文件 | 变更 |
|---|---|
| `app/public/wp-content/themes/dentall/functions.php` | 加载独立Footer模块 |
| `app/public/wp-content/themes/dentall/inc/site-footer.php` | 新增3个Footer职责函数、一个两级菜单位置和两个Storefront输出Hook |
| `app/public/wp-content/themes/dentall/assets/css/site-shell.css` | 新增Newsletter/Footer Mobile First基线、1200px桌面增强和父主题级联修正 |
| `app/public/wp-content/themes/dentall/style.css` | 缓存版本提升至0.13.2，并更新深色组件Focus注释 |
| `project-docs/PROJECT_STATE.md`、`CHANGELOG.md`、本日/索引/学习笔记 | 记录授权、证据、边界与后续责任 |

## 验证证据

### PHP、Hook与静态审计

- `functions.php`、`inc/setup.php`、`inc/storefront-hooks.php`和`inc/site-footer.php`全部通过`php -l`。
- Local PHP只读启动证明确认：环境为`local`、`footer`位置已注册但未绑定、Storefront默认Footer Widget Hook已移除、自定义Footer与Newsletter Hook均在优先级10。
- Local CLI启动同时提示当前PHP配置无法加载`php_imagick.dll`；启动与只读Hook检查仍成功，登录态网页验证未出现由此造成的Footer异常。该环境告警不由Day35代码引入，也不在本轮修改范围。
- `inc/site-footer.php`静态扫描为0个`form`、0个submit、0个`name`、0个远程请求、0个持久化写入；PHP文件中0个行内`style`/`script`/事件属性。
- `site-shell.css`最终为976行、24548字节、133/133对花括号和0个`!important`；HTTP与磁盘均为24548字节，SHA-256同为`9eefca9962fa8c642289cf72cf7d466c050b3187842b2de4052e351c5645846e`。
- `git diff --check`通过；仅有Git提示未来可能转换LF/CRLF，不是内容错误。

### 1440桌面证据

- Newsletter与Footer均只输出1次；CSS URL为`site-shell.css?ver=0.13.2`。
- Newsletter背景为`rgb(0, 58, 159)`，Footer为`rgb(3, 26, 58)`；深色Focus Token计算为`#ffffff`。
- Newsletter文案位于预览控件左侧，二者垂直中心对齐；Footer为导航/空状态与240px品牌列。
- Footer品牌最终计算为白色且无常驻下划线；初次截图发现的clearfix错位与父主题链接颜色覆盖已经修正。
- 页面横向溢出为0；Newsletter内不存在表单，Email与按钮均为disabled，Email没有`name`。

### 七个宽度与五类页面

- 390、768、1024、1199的Newsletter、预览控件和Footer主体均为单列；1200、1366、1440切换桌面双列。
- 七个宽度均满足`scrollWidth === clientWidth`；输入框、禁用按钮、空状态和品牌矩形均在内容视口内。
- 390截图确认长Newsletter状态文案、占位邮箱、禁用按钮、Footer空状态、品牌和动态版权完整可见。
- 390px下Home、Shop、Simple Product、Cart、My Account共5/5：每页Newsletter=1、Footer主体=1、Newsletter表单=0、禁用控件=2、横向溢出=0。
- 当前浏览器控制台仅保留WooCommerce既有`wc.wcBlocksData`依赖声明警告，来源为Cart页面；本轮没有新增JavaScript，不能把该既有警告归因于Footer实现。

## 独立复核

- 独立Code Review结论为P0/P1/P2/P3均0；Hook时序、转义与国际化、Local环境边界、两级菜单合同及无提交/远程/持久化路径均通过只读复核。
- 独立测试结论为P0=0、P1=0、P2=1、P3=0。唯一P2是匿名Local请求由WooCommerce Coming Soon模板接管，该模板仍输出默认LinkedIn、Instagram和Facebook链接；它不含Day35 Footer，也不是`site-footer.php`输出。若“不渲染未确认社交”解释为覆盖所有匿名Local页面，则必须另行确认并修改Coming Soon配置；本轮不能把独立页面配置变更默认吸收到Footer范围。
- 独立视觉复核结论为P0/P1/P2/P3均0，确认同一语义DOM、移动优先单列、1200px桌面增强和长文本防护；专项未独立完成全部宽度逐档截图，且未绑定菜单，不能独立背书填充态五列的精确间距或极端内容高度。
- 主执行链已关闭两项浏览器实测发现：Newsletter Grid受Storefront clearfix影响、Footer品牌颜色被父主题Customiser规则覆盖。登录态1440/390截图和七宽度结构证据作为本轮主视觉结论；Footer填充态仍留D36。
- 当前Day35组件范围P0/P1为0；上述环境P2由开发者/Codex登记，最晚在开放匿名Staging或Production预发布页前，通过WooCommerce原生Coming Soon编辑/配置单独确认、修正和复测。

## 减法审查与量化

- 运行文件净增1个：`inc/site-footer.php`，95行、3626字节、3个高内聚函数；独立文件的理由是Footer拥有稳定且不同于Header/Catalog的Hook、数据源和变更频率。
- `functions.php`只净增1条`require_once`；没有把完整功能塞回主入口。
- `site-shell.css`相对D34基线净增197行、4503字节和30个规则块；保留内容只覆盖两个实际组件的结构、不可提交状态、深色级联、Mobile First安全基线与1200px桌面增强。没有社交/支付状态、动画、折叠菜单或D36预实现。
- PHP函数净增3；JavaScript、模板覆盖、插件、依赖、数据库字段、Option、查询、远程请求和Cron净增0。
- 没有前后性能测量，因此只报告请求数不变和CSS/PHP体积，不宣称性能“零影响”。

## 未验证项与移交

| 未验证或未完成 | 原因 | 负责人、计划与最晚节点 |
|---|---|---|
| Footer正式栏目、子链接与URL | 业务页面/Slug尚未冻结，D35按计划不写数据库 | 业务方提供真实目标；D36由用户在原生菜单后台绑定并回归 |
| 真实五列菜单的长栏目/长链接视觉 | 当前`footer`位置未绑定，只有空状态；CSS合同已实现但不能冒充填充态浏览器证据 | D36用代表性TEST菜单完成四端与键盘验证 |
| 手机/平板最终菜单重排或折叠 | 属于D36范围 | D36确认交互后实施，不复制DOM |
| 真实Newsletter | 服务商、企业账户、名单、Double Opt-in、隐私文案、地区合规、DNS和邮件资产均未确认 | 独立功能确认后估算约1～3个工作日；不默认吸收 |
| 社交与支付 | 正式账号URL、支付网关和官方品牌素材未冻结 | 业务/支付节点确认前保持不渲染 |
| 匿名Coming Soon默认社交链接（P2） | WooCommerce独立Coming Soon模板仍有LinkedIn、Instagram、Facebook默认链接，不属于Day35 Footer输出 | 开发者/Codex在单独获得页面配置授权后用原生编辑/配置处理；最晚在匿名Staging或Production预发布页开放前复测 |
| Staging/Production | 本次授权仅Local | 独立部署确认后执行 |

## Newsletter为何不是“接上按钮就结束”

真实Newsletter是一条跨系统链：服务商/账户选择、名单归属、SPF/DKIM及可能的DMARC、同意与退订规则、Double Opt-in、站点安全集成、防滥用、成功/错误状态、邮件模板和端到端投递验收都需要被确认。若上述输入已准备好并采用成熟官方集成，可能压缩到约0.5～1天；若要选型、自定义API并完成完整合规与投递测试，约2～3天更现实。这个估算不是无条件承诺，也不能默认并入D35。

## 数据、URL与系统影响

- 数据：未写入菜单、商品、订单、客户、库存、邮箱、Option或任何数据库对象。
- URL/SEO：没有新增硬编码业务链接，不改变Slug、Title、Meta、Canonical、Schema、robots或Sitemap；未来Footer正式链接会形成全站内部链接，D36绑定前必须排除Draft、TEST、404和未确认页面。
- 缓存与性能：子主题版本升至0.13.2；继续复用一个`site-shell.css`请求，CSS较D34增加4503字节；无新查询、远程请求、Cron或autoload Option。
- 支付、物流与交易：不显示支付品牌，不改变价格、库存、税费、购物车、结账、支付、物流、订单或退款。
- 部署：仅Local；Staging、Production、DNS、索引保护和真实支付均未改变。
- 回滚：移除`functions.php`的Footer模块加载并恢复`site-shell.css`/`style.css`版本即可撤回运行输出；本轮无数据库恢复步骤。

## 可安全微调与DevTools路径

1. 在Elements中定位`.dentall-newsletter`或`.dentall-footer__main`，先临时修改Grid列宽、gap或Token观察结果。
2. 判断是公共颜色/间距Token、Footer组件规则还是D36断点差异，再回到子主题源码修改；不要改Storefront核心或把临时规则留在DevTools。
3. 重新检查390、768、1024、1199/1200和1440，并回归Home、Shop、Product、Cart、Account。
4. 若绑定菜单后出现异常，先查`footer`Theme Location、顶级/二级层级与真实URL，再查CSS；不要先复制HTML或增加Walker。

## 下一步

1. D36已由Administrator在Local创建并绑定代表性两级TEST菜单，详见[[Day36-手机与平板页脚和后台菜单绑定]]。
2. D36选择同一DOM静态重排并完成四端收口；没有新增Accordion或JavaScript。
3. Newsletter服务、社交账号与支付品牌继续保持独立需求；匿名Coming Soon独立模板按已登记环境P2另行处理。

## 可复用核心思想

### 跨平台不变量

- 视觉壳层、内容数据和真实业务服务应分层冻结；先验证结构不等于可以伪造数据或提交成功。
- 页脚链接是全站内部链接与承诺，未知目标宁可显示诚实空状态，也不能用`#`、Draft或猜测URL填满设计。
- 第三方服务的工作量来自账户、数据、合规、域名信誉和失败恢复的整条链，而不只来自一个输入框。

### WordPress/WooCommerce当前实现

- Storefront公开Action负责插入位置，WordPress Theme Location负责菜单数据，DentAll子主题只负责转义后的语义DOM与CSS；父主题模板无需覆盖。
- `fallback_cb => false`防止未绑定Footer时意外输出全部Page；`depth => 2`把栏目/链接合同收敛为一棵可维护菜单。
- `wp_get_environment_type()`让Local测试壳层不跟随代码误入非Local输出，但部署边界仍需独立控制。

### Shopify或其他平台

- 其他平台同样应把导航数据、主题布局和营销服务分开，并用正式URL/账户/合规信息驱动条件输出。
- Shopify菜单、Section、App Embed和邮件营销应用的具体映射本轮未验证，标记为待验证，不进入DentAll第一版范围。
