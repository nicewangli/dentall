---
项目: DentAll WooCommerce
日期: 2026-09-08
工作日: D61
计划检查点: D61（不自动等于一个完整实际工作日）
周次: W11
实际有效工时: 用户未记录
验收层级: 隔离Local技术验证
状态: 已完成（隔离Local技术v1；保留RSK-035原生P2）
---

# Day61 原生变体选择与购买验证

## 相关笔记

- 前置项目笔记：[[Day60-商品详情代表内容回归与W10收口]]。
- 后续项目笔记：[[Day62-原生字段复核与零扩展收口]]；D62已作为并行检查点完成。
- 直接相关：[[Day59-商品详情四端购买区与Sticky收口]]、[[Day65-商品详情结构化数据与SEO边界]]。
- 当日学习笔记：[[WordPress实战笔记/Day61-变体生命周期与展示语义]]。

## 授权、前置与范围

用户明确批准：“你看一下day59-60的完成情况，如果正常了，同意按上述 Day61 v1 实施范围推进”，随后补充“day60已完成”。主Agent与代码、安全、独立测试三路审计确认：D59提交`7220fe5`完整，D60提交`c99126d`以12个Markdown记录代表内容回归，工作树干净。D61分支`codex/day61-variable-purchase`直接基于`c99126d`，共享`main`未移动。

D59的559/559覆盖详情布局与图库；D60的419/419为原始401/419经源码合同校正18项测试Oracle后的权威结论，未重新浏览器回放。D60只验证Variable初始未选状态，因此D61必须重新形成动态选择、图片、价格库存和加购证据。

使用角色为前台访客/Customer，商品事实由Website Manager在WooCommerce原生后台维护。代表输入为现有TEST父商品#46及#51～#53，实际业务维护频率和正式商品数量未用TEST规模代填。

批准的v1：复用原生属性下拉、默认值、匹配、价格、库存、不可购买和POST加购；在既有详情CSS调整属性控件、动态信息和Quantity；共用D59尺寸公式覆盖Variation数据；新增一个条件加载的`aria-disabled`适配脚本；仅在新隔离Local使用可逆TEST状态与购物车，完成代码/安全/独立交易复核及项目/学习收尾。

候选比较结论：原生Woo已覆盖购买规则；现有主题CSS/Filter能承载展示；成熟插件和独立自研均会扩大维护面而没有本版必要收益。选用最小子主题增强。没有自定义Variation系统、模板覆盖、自定义AJAX、后台入口、字段、插件、依赖、默认值硬编码或正式商品事实代填。

范围仍落在总计划D61“可变商品可购买”检查点；没有新增功能或增加计划工作日。实际工时未记录，不把计划周期或工具等待时间当实际工时。

## 今日三个验收结果

- [x] 原生Variable选择、价格、库存、图片、默认值、无效/缺货/不可购买及Clear可验证；原生网络错误体验P2单列延期，不宣称已修复。
- [x] 390/768/1024/1440及1199/1200的标签、焦点、稳定态禁用语义、布局和资源加载通过。
- [x] 合法与异常加购、参数URL/Schema、快照数据恢复和独立复核通过，并完成学习收尾；不等于整库逐字节或Production验收。

## 实施边界与减法审查

- `inc/setup.php`继续只在商品页加载详情CSS，Variable父商品额外enqueue一个依赖Woo原生Variation脚本的小文件。
- `inc/storefront-hooks.php`复用既有数量Filter并扩展到Variable；一个纯字符串helper共享初始/动态`sizes`，一个公开Filter只替换`image.sizes`。
- `assets/css/product-detail.css`沿用Storefront原生属性表堆叠，D58数量样式扩展到Variable。新增5块分别负责label间距、select满宽、Clear间距、动态长词折行和价格层级。
- `assets/js/product-variation.js`只负责按钮可访问语义，保持Woo的焦点、提示和服务端验证。初始化保守设置不可用，原生show/hide事件之后读取原生禁用class。
- 主题版本为0.35.0，Core保持0.2.8。
- 首稿新增10个CSS块已减至5个，删除重复的Grid、表格/单元格重设、空容器边距、display和按钮padding。JS具有独立的浏览器加载生命周期，单独成文件；PHP仍属于当前主题展示Hook，规模与职责不足以新增模块或插件。
- 最终运行层为4个既有文件修改、1个22行JS新文件；合计120行新增/12行删除、净+108行。净增2个PHP函数、1个Filter、5个CSS规则块；数量函数改名不算新增函数。测试助手与数据库证据留在Git忽略目录，不进入运行交付。

## 专注周期记录

| 周期 | 对应工作 | 状态 |
|---|---|---|
| C1 | D59/D60基线、授权与Woo原生生命周期 | 已核对 |
| C2 | 最小实现与代码/安全审查 | 静态通过；初始化语义已修复 |
| C3 | 新隔离Local、产品/会话/订单基线 | 新实例、专用最小账号与快照已建立 |
| C4 | 六宽选择、图片与键盘 | 363/363及13/13通过 |
| C5 | 默认值、异常状态、原生AJAX路径 | 两宽121/121；A/B/C四宽24/20/16项通过，原生P2登记 |
| C6 | 独立加购、URL/Schema和恢复 | 交易16项、inline契约6项、模型25项及恢复17项通过 |
| C7 | 修复复测、文档学习收尾与提交 | 夹具修复后新进程复证，项目/学习/索引更新；D61单独提交 |

## 验证与独立审查

已运行两个修改PHP文件的`php -l`、JS的`node --check`和`git diff --check`；语法/空白检查通过。实际PHP为8.2.29、WordPress 7.0.4、WooCommerce 11.0.0、Storefront 4.6.2、Yoast 28.2。

风险分级为交易关键：虽实现只改展示，但验收涉及价格、库存与购物车，使用Code Review、安全和独立测试Agent。静态审查发现Woo延迟初始化前短暂宣告`aria-disabled=false`，已改为初始true并由原生事件收口；所有权威浏览器报告均使用修正后的同一份5文件哈希。

### 主Agent真实浏览器回放

| 报告 | 场景 | 实际结果 |
|---|---|---|
| `inline-verified/browser.json` | 390/768/1024/1199/1200/1440，每宽初始、#51、售罄#52、#53、Clear；Quantity/44px/焦点/语义/图片/价格/库存/布局/SEO与资源退出 | 363/363，本地页面错误0 |
| `flows-verified/flows.json` | 合法#53、售罄#52、部分/非法参数URL，键盘无效提示，#51数量2原生POST与cart清空 | 13/13，USD行小计7998美分；不结账 |
| `ajax-verified/browser.json` | 真实阈值切换后的`data-product_variations=false`，390/1440五状态及资源退出 | 121/121，本地页面错误0 |
| `ajax-faults-verified/faults.json` | 首次1200ms延迟、503失败、重选恢复、已有选择的pending与快速重选 | 7/7，无未捕获JS异常；503是主动注入的TEST响应 |
| `flows-ajax-verified/flows.json` | AJAX模式合法/售罄/部分/非法参数URL、键盘提示、原生POST与清空 | 13/13；补齐AJAX非法Large/Medium |
| `state-a-verified/states.json` | 四宽真实默认#51、动态长连续文本、变体缺图回退父图 | 24/24；首轮夹具误写父短描述20/24，修正后重跑 |
| `state-b-verified/states.json` | 四宽父/变体均缺图、默认#51、原生单售 | 20/20；占位图与Quantity/label整体隐藏 |
| `state-c-verified/states.json` | 四宽默认#51无价格不可购买、选#53库存1 | 16/16；非单售Variation仍显示Quantity，min=max=1 |
| `ajax-error-post/report.json` | 已可购#51→切#52时503→键盘POST | 4/4；复现原生P2，服务端要求重选且cart为空，不代表P2已修复 |
| `media-viewport/media.json`及2张局部图 | 390/768滚回图库，核对实际图像、computed样式与浏览器绘制 | 实际Variation图正常；非整页截图推断 |

六宽截图抽查覆盖手机、两种平板和1199/1200边界、PC。390整页截图在图库已滚出视口时出现一次合成留白，已用滚回视口后的图库截图核实；不能把该整页空白当成实际缺图。768以上左缘的小商品图属于Storefront既有product-pagination，不是Sticky或本次新增UI。

首轮`inline-final`为358/363、`flows-inline`为9/13、`ajax-faults`为6/7，均保留原始报告。分别纠正Clear强制退回初次currentSrc候选、noindex环境Canonical输出前提、以及Variation ID先于300ms通知事件更新的测试等待条件后，进行了上述真实重跑，未把派生归一报告冒充实跑。外部Google Fonts与WordPress emoji请求被测试隔离主动拦截，其console错误单列为`blockedExternal`；本地脚本错误另计，不宣称所有网络请求均成功。

### 原生边界与证据解释

- `aria-disabled`映射Woo的disabled class，不建立独立交易状态机。已有可购选择再次发AJAX时，Woo先清空Variation ID并显示遮罩，但旧class/价格可暂留，因此pending时ARIA可能仍为false；503无专项网络错误文案，用户需Clear/重选或刷新。本版记录原生限制，不虚称已实现完整错误重试UX。
- Woo `single_variation`用`role=alert`/`aria-relevant=additions`，Clear通知区才是`aria-live=polite`。实际屏幕阅读器未测。
- Zoom可预载全尺寸图，Clear后浏览器可复用更大的父图缓存候选；验收应检查父图src/srcset/sizes及当前候选归属，不要求currentSrc逐字等于首次下载文件。
- 参数URL在受保护隔离分支临时将`blog_public`设1以观察Yoast Canonical；HTTP始终发送`noindex,nofollow,noarchive`并只监听环回。客户端选择未修改Canonical、og:url或Schema；这不等于公开搜索引擎或富结果验收。
- Woo11可选Variation Gallery开关为`no`，父商品与三个Variation的gallery IDs全空。本版验证每变体单主图；未来开启多图图库或增加Quick View，必须重新检查动态`sizes`与DOM路径。
- 初始ARIA浏览器断言等待Woo初始化稳定，只证明稳定态；主题在DOM ready先写true，不能宣称首屏HTML或第一毫秒开始始终具有正确ARIA。

### 独立交易与开放留项

独立测试Agent以独立浏览器/会话验证匿名12/12、Customer2/2和无价Variation POST2/2。匿名#51×2=$79.98、Customer#53×1=$49.99；超库存、售罄、属性/ID不一致与无价提交均拒绝且拒绝用例cart为空。Customer只做一次正向代表验证，不冒充双角色完整矩阵。价格、库存、订单由Woo API核对，未执行Checkout。

自定义差分Code Review P0/P1/P2/P3=0；全任务另有继承的Woo原生错误体验P2（RSK-035）。负责人：开发者；计划：D66整链路复审，最晚非Local部署前。延期原因：当前授权只映射原生class、不新增loading/error状态系统；D66先比较原生/上游与最小适配，新增实现须另行确认范围和工时。已有可购→503时旧可见状态确实保留；本次键盘POST实测返回“Please choose product options”且cart为空，交易边界仍有效。

最终安全与独立测试已放行本版提交，无未关闭P0/P1；静态Review、主Agent回放、独立交易和恢复审查不是同一人重复自检。P3证据边界：没有专项验证同一合法POST重复提交，本版不实现幂等/去重，Woo原生成功POST可累计数量；未来多图Variation Gallery、第三方变体调用与模板升级须复测。

## 数据、站点影响与回滚

运行代码不写商品/Variation、价格、库存、订单、角色或配置。商品与库存真相源仍为Woo CRUD/API，公开加购和get_variation沿用Woo原生合同。没有自定义SQL、nonce入口或权限绕过；PHP仅传固定sizes元数据，HTML与JSON继续由平台转义。

URL、Title、Canonical、Schema、robots和sitemap实现未改；参数页已在本次隔离环境观察，但不等于公开搜索引擎验收。新增720字节未压缩JS只进入Variable商品页，主题版本更新静态资源缓存键，没有自定义缓存、Cron或远程调用；复用`wc_get_product`获取当前主商品，不新增第二商品结果查询，未量测整页SQL差异。未测Production缓存/CWV，不能宣称性能零影响或排名改善。

隔离测试允许可逆商品状态/默认值和cart session，不创建订单、不结账；源Local、Staging、Production、真实支付、物流、邮件和部署不在本次写入范围。回滚D61代码时恢复0.34.0对应文件，商品事实无持久迁移；本轮隔离快照恢复和16062/16063停机均已验证。

未来新增Quick View或其他调用`get_variation`的非详情布局时，必须重审其图片sizes合同。真实设备/辅助技术、公开搜索引擎验证和非Local部署仍独立验收。

## 证据入口与复现边界

- 主Agent：`D:\LocalWP\dentall\.codex-tmp\day61-root\evidence\`；权威根清单`final-root-manifest.json` SHA-256 `95e1b6cd0e7a9b6515b75f0d37875ca0eea5fca4670aadb9ef1e20276a4624ac`。9份自动报告合计581/581次断言、50张保留截图；其中4项是已知P2的复现与安全拒绝，不代表无错误体验缺陷。截图只抽查代表状态，不声称逐张像素审查。
- 独立Agent：`D:\LocalWP\dentall\.codex-tmp\day61-test-agent\evidence\`；原生inline Filter开关深比较6/6、AJAX对应JSON仅差`image.sizes`，D12商品模型17/17和D18父子审计8/8通过。
- seed为D60保留的可重复TEST转储，不是本轮新导出的共享Local快照；SHA-256 `9cdf33811d093f850d1aebd81e0a36c9a5d17dc31d3a81d95b0d356b671157a0`。新DB实例、账号和环回端口16062/16063与真实站点分离；1142证据只证明新实例账号不能跨库读取，不宣称连接过真实源库。
- 源seed的`blog_public=1`、Coming Soon=yes、Sticky键缺失；prepare临时为0/no/false，SEO分支回1，最终按首快照恢复。安全依据是环回绑定、ACL、HTTP noindex及外发/邮件/Cron/Action Scheduler/Checkout禁用，不依赖单一WP选项。
- 两次恢复审计之间发现父#46 modified唯一差异：最后保存子Variation触发父级同步，同进程缓存使早先检查未见差异。已在子项全部完成后恢复父modified，用新PHP进程和主Agent独立深比较确认8组字段与首快照一致；首次`post-restore-mismatch.json`保留，不能用早先`restore-products.json`代替最终证据。
- 最终`final-wordpress-audit.json`为17/17；5商品与modified、原1条session、订单/退款0、pending actions14、选项、图库开关和版本均与首快照相同。临时Customer账号及其凭据、AJAX marker已移除，测试新增附件/上传文件0；16062/16063监听及对应PHP/MySQL进程均0，主Agent另行读回监听0。
- PHP应用错误匹配0；MySQL error0、1条自签本地CA warning，正常关闭。不声称所有日志没有warning。已停机副本仍保留复跑所需DB账号、受限cnf/wp-config/private baseline；并非全部凭据销毁。访问仅SYSTEM/本机Administrator，禁止提交/外发，保留期结束后再按精确目录清理。
- 权威哈希：`runtime-restored.json`为`8E97F430FA382007FDCC19C63D3F6494805A1A23B9734BF61BFBFCA5ED8EF2E4`；新进程`fresh-restored-audit.json`为`21D591AE82B067DC281885551B6A015DE3FC11421E7A27A0C3CCE38A793F0229`；`final-wordpress-audit.json`为`184F5769ED6BE62B94B554D782C3D61E9D8E2EDB80AD59EB1D02FA84DC9B937E`；停机隔离`final-isolation.json`为`5C4DD7CEE8FB2E776DE271E0AFA61B20F40F2670C189EB1678E732CAAE8A7665`；独立37项`evidence-sha256.txt`清单自身为`F155521669E447CB2CC04C2A837028A02999885ED459EE50BBE8288A1608F952`。

实际主要命令（测试helper只用于该隔离runtime，不能直接指向真实站点）：

~~~powershell
node 'D:\LocalWP\dentall\.codex-tmp\day61-root\browser.cjs' 'inline-verified'
node 'D:\LocalWP\dentall\.codex-tmp\day61-root\browser.cjs' 'ajax-verified'
node 'D:\LocalWP\dentall\.codex-tmp\day61-root\flows.cjs' 'flows-verified'
node 'D:\LocalWP\dentall\.codex-tmp\day61-root\flows.cjs' 'flows-ajax-verified'
node 'D:\LocalWP\dentall\.codex-tmp\day61-root\ajax-faults.cjs' 'ajax-faults-verified'
node 'D:\LocalWP\dentall\.codex-tmp\day61-root\states.cjs' 'A' 'state-a-verified'
node 'D:\LocalWP\dentall\.codex-tmp\day61-root\states.cjs' 'B' 'state-b-verified'
node 'D:\LocalWP\dentall\.codex-tmp\day61-root\states.cjs' 'C' 'state-c-verified'
node 'D:\LocalWP\dentall\.codex-tmp\day61-root\ajax-error-post.cjs'
git diff --check
~~~

重新运行需准备同一快照、5文件哈希和相应场景，并使用新的证据目录名；脚本拒绝覆盖现有目录。所有截图、SQL、完整数据库、Cookie、凭据和私有日志只允许本机受限内部留存，不进入Git或外发。实际设备、生产缓存/CWV、公开SEO和Variation多图仍另验。

## 学习收尾与下一步

- 已基于真实源码、事件时序、图片候选、恢复缓存和交易结果生成当日WordPress学习笔记，登记索引并建立前后及同主题双向链接；用户尚未费曼自测，不代填掌握度或实际工时。
- 对照总计划D61“可变商品可购买”验收；D62已按零扩展范围提前完成，因此下一顺序缺口为D63。D63先只读梳理参数/认证/下载展示与原生字段承载，任何新增字段、PDF权限、公开资料或定制展示仍须单独确认。
- D66承担RSK-035错误体验复审；正式内容、非Local代码/Sticky配置、公司Git治理及支付物流等后续门槛不被本日技术验证替代。

## 可复用核心思想

### 跨平台不变量

服务端决定交易是否有效，浏览器展示当前状态；语义、视觉和服务端校验需要分别验证。一个初始HTML输出通过，不能证明后续动态数据也经过同一条扩展链。

### WordPress/WooCommerce当前实现

Woo经典Variation可使用初始JSON或get_variation请求；两条路径都生成available variation数据。主题在共同Filter上调整图片提示，并监听Woo事件同步语义，避免复制平台的匹配和库存规则。

### Shopify或其他平台的对应机制

可迁移的是“商品选项、展示状态、服务端交易校验分层”和动态媒体证据；具体事件、变体接口、模板与购物车规则须重新核验，Shopify对应实现待验证。
