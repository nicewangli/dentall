---
类型: 项目Day笔记与阶段取证
项目: DentAll WooCommerce
日期: 2026-10-22
工作日: Day97（联接D98～D102）
状态: 进行中；真实Home／Shop／PDP阶段基线及受控缓存验证完成，未完成D97全范围或M8
验收层级: 源码审计＋Staging阶段传输基线
---

# Day97 性能基线与资源缓存审计

## 结论与授权

首轮已完成运行版本盘点、16次无Cookie页面请求、17项同源资源读取及首页四宽图片检查；首次临时预览13次GET复现`FG-P2-01`。后续获批单PDP两层临时排除后，**27次GET全部通过**，取得真实Shop／PDP规范URL各3次HTML基线、启用态无Cookie隔离及两阶段关闭恢复验证。两层原规则、私密预览关闭和保护状态均已恢复。又补管理员真实Shop／PDP四宽布局、字体及Zoom资源证据，发现详情侧栏／尺寸合同`FG-P2-02`，转入已授权的D98最小修复。非空Checkout、CWV和正式缓存全链路仍未完成。**D97继续进行中，D98～D102及M8未标Done。**

首轮授权来自2026-10-21总协调下发F→G工作包，以及用户2026-10-22回复“可以，我在内置窗口打开了staging了，你开始吧”，对应此前说明的约20～30分钟只读窗口。首轮未保存网站设置，已在结束后释放该窗口、关闭专用前台标签并恢复默认视口，保留用户原有后台标签。两轮均未手动清缓存、部署、升级、登录其他客户、改购物车、创建订单、访问带有效key的付款页或发送邮件。正常GET会产生访问日志、缓存填充及可能的WP Cron；不宣称服务器零写入。

2026-10-22后续用户明确回复“同意该范围，窗口另行安排”，再回复“现在由 F→G 独占”：允许临时启用Woo原生私密预览，仅测真实匿名Shop／商品详情，结束立即关闭并验证撤销；保持Coming Soon／noindex，不加购、下单或发信。**已实际保存开启、关闭各一次，终态private preview关闭、Coming Soon／仅商店页保持开启、Live未选、保存按钮禁用。** 此项授权不覆盖全站公开、用户原购物车或交易验证。因复现PDP缓存干扰，用户后续明确回复“同意此单页临时排除、必要清理和恢复验证”，授权仅代表PDP的Breeze／Varnish临时精确排除、必要清理及恢复复测；该配置范围已确认，无需再问。

## 相关笔记

- 历史Shop预览／缓存边界：[[Day86-D74与D86整合发布记录]]。
- 本轮发现后的已授权展示修复：[[Day98-图片尺寸与商品全宽布局修复]]。
- 本日阶段学习：[[WordPress实战笔记/Day97-真实正文基线与缓存撤销证据]]；项目范围仍未全部完成，不把学习记录当Done。
- 复用14组回归包：[[Day107-系统回归准备与执行包]]，以RG-11为主。
- E的D95最新源码和未提交记录位于独立`de9a`工作树；未复制、提交或修改。其最新同意／测量与Sandbox验收须在原任务收口。
- 机器证据：`project-docs/tests/day97-performance-baseline-20261022.json`；原始HTTP、图片与一次性采集脚本留在本工作树Git忽略目录`.codex-tmp/fg-20261022/`。

## 本轮三个验收结果

- [x] 建立源码、发布来源与运行环境的明确版本边界。
- [x] 取得可复算的传输／资源数据，确认最小优化优先级。
- [x] 明确D100安全验证范围、既有用例入口和需要另排窗口的动作。

以上是本轮阶段结果；真实匿名四类页面、CWV、查询耗时及完整缓存隔离仍待补齐。

## 基线与环境

| 层次 | 本轮事实 | 边界 |
|---|---|---|
| 独立工作树 | `fg-performance-cache/dentall`；分支`codex/fg-performance-cache-20261021`；起点`1d13946d648832ac22a9bfe676e22acecac82f10` | 主题0.49.2、Core0.9.3，仅源码候选，未部署 |
| C增量 | `67a8@e1ba252`的`wp-content`与该起点相同 | C最新收尾文档不等于新一轮缓存证明 |
| E增量 | `de9a@ca1e73b`新增Measurement0.2.0；`consent.js/php`另有未提交修改 | 只读核对；最终资源版本仍须由E确定 |
| Staging | WordPress7.1.3、Woo/DB11.0.0、PHP8.2.35、MySQL8.4.11、Storefront4.6.2、DentAll0.48.0、Core0.6.1 | 与隔离候选不同；PHP不是旧文档的8.2.33 |
| 发布来源线索 | `296d04852cf709038fc0889ef271a255baf8c4d6` | 实际HTTP取得的7个子主题CSS＋Logo共8文件逐字节匹配；未读服务器PHP／全树，目标完整SHA仍未核实 |
| 交易设置 | HPOS启用、OrdersTableDataStore、兼容同步关闭；USD；PayPal4.1.2启用且状态页显示Onboarded/Webhook正常 | 不等于本轮Sandbox、付款或Webhook重验 |
| 其他插件 | Yoast28.2、Breeze2.5.12、OCP1.25.5、FileBird6.5.8、FluentSMTP2.4.0启用 | 未更新；插件页面的可升级提示不作为升级授权 |
| 保护与环境 | 现场常量仍为`production`；搜索屏蔽；本轮HTML均有禁止索引头；商店Coming Soon | 按应用身份称Staging；未改环境常量或保护配置 |
| 资源／分析 | 活动插件共8项；本日普通插件列表未见Measurement、Complianz或WP Consent API；匿名样本HTML未见GTM加载 | 只限这些已读入口，不推断其他注入方式；E的GTM草稿不作为目标已部署资源，当前性能不是E最终资源后的D102结果 |

系统报告另显示WordPress内存限制40MB。这里只记录待核项：尚未核对有效PHP内存、峰值和错误日志，不能据此认定已内存不足，也不由本任务顺手改配置。

### 缓存层现场读数

| 项目 | 现场读回 | 未验范围 |
|---|---|---|
| Breeze基础 | Cache System/Gzip/Browser Cache开启；TTL1440分钟；Mobile Cache和Breeze图片懒加载关闭；全部登录角色缓存关闭 | 不代表WP原生图片懒加载关闭；不代表所有缓存层已排除登录态 |
| 文件优化 | HTML/CSS/JS压缩、CSS/JS合并、Delay Inline JS、Delay All JavaScript关闭 | 未改开关；不把这些关闭项本身算缺陷 |
| 高级排除 | Never Cache仅完整同源`/shop/`；Cache Query Strings为空 | Woo原生动态排除与Cloudways规则须另核，不能只看此列表判断交易页安全 |
| Varnish | Breeze Auto Purge Varnish开启；公开首页与受保护PDP观察到`X-Cache: HIT`／递增Age | 未进入Cloudways重读Varnish服务和完整排除表；不把界面“默认开启”说明当现场服务事实 |
| 对象缓存 | OCP1.25.5已连接、Drop-in Valid；Woo报告外部对象缓存启用 | 10月7的`prefetch`／`orders`非预取组留项需按当前有效配置重核；本轮未改，未证明已关闭旧P2 |
| CDN | Breeze Activate CDN关闭、CNAME空；本轮同源静态资源无CDN改写 | 未检查主机／DNS层其他CDN，不能写“全站所有CDN已关闭”；本轮无采购或新增缓存层依据 |

## 测量方法与实际结果

Windows curl8.9.1，`--compressed`、独立进程、顺序执行、无Cookie请求头／jar、无请求端`no-cache`／随机参数；保持TLS验证。没有手动Purge，因此第一次看到MISS也只叫首次观测，不称受控源站冷缓存。本机网络未限速；DNS／TCP／TLS／TTFB／完整下载时间均保留在私有原始JSON。

| 入口 | 次数 | 实际正文／跳转 | TTFB秒 | gzip HTML字节 | 可作结论 |
|---|---:|---|---|---|---|
| `/` | 3 | 真实Home，200 | 1.238／1.141／1.163；中位1.163 | 17,945，每次相同 | 连续HIT，Age74→76；不是CWV |
| `/shop/` | 3 | Coming Soon，200 | 3.131／1.362／2.183 | 15,426／15,295／15,441 | 保护层基线；无X-Cache/Age不能单独证明所有层不缓存 |
| 首页已输出的代表PDP | 3 | Coming Soon，200 | 1.319／1.168／1.159 | 17,721，每次相同 | MISS→HIT→HIT，Age0→1→2；不是商品内容性能 |
| `/checkout/`无Cookie | 3 | 302→`/cart/` Coming Soon200 | 工具原值2.807／1.839／1.831；完整链下载3.028／2.330／2.287秒 | 最终正文44,031／43,888／43,743 | 首跳private/no-cache；不能当真实Checkout渲染时间 |
| `/my-account/` | 2 | 真实未登录账户入口，200 | 1.242／1.304 | 15,726／15,581 | 两次private/no-cache；客户A/B隔离未验 |
| `/cart/`无Cookie | 2 | Coming Soon，200 | 1.349／1.604 | 43,743／44,178 | 保护层max-age60不能代验空／非空Cart |

另有2次首页准备读取及17次静态资源读取，合计35次curl请求链；浏览器导航与自然子资源另计。重定向的TTFB保留工具口径，`time_total`覆盖完整链；本轮没有用它计算PHP执行耗时。

### 图片与CSS/JS

- 实际首页HTML有12个外链CSS引用（其中1个Google Fonts）、13个外链JS、11个`img`；不是浏览器总请求量。11个同源首页CSS独立gzip传输合计62,893字节，子主题首页三CSS为16,414字节；字体文件、内联CSS、JS及图片另计，不能用磁盘原始总量替代网络总量。
- 现场Fallback Logo实际使用固定PNG，1024×240／144,221字节，无`srcset/sizes`；Header在390/768/1024为136 CSS px，1440为220 CSS px。Footer复用同URL，不能重复算成第二次冷下载。这是已确认资源候选，尚未实施或测得节省量。
- 后续已继续完成离线无损比较：Node内置zlib对原PNG的IDAT像素扫描流尝试5种策略，level9最佳候选仍为144,947字节，比原文件大726字节。解压扫描流完全相同，尺寸／颜色／alpha／其他PNG块保持；原运行资产SHA未变。**不采用该候选**，不为计划表新增压缩插件；后续优先解决实际显示尺寸与图源候选的对应关系。一次性脚本及结果为`.codex-tmp/fg-20261022/logo-lossless.cjs`、`logo-lossless-result.json`，未部署或修改业务素材。
- 分类代表图已有WebP、完整`srcset`和尺寸声明；324px版本6,656字节。当前`sizes`为`(max-width: 324px) 100vw, 324px`，四宽实际代表分类图约67.0／85.3／62.3／86.5 CSS px（精确值以JSON为准），观察到324px候选。四宽是同一已加载页resize，未清浏览器缓存，不能把它当四组首次选图测试；响应式`naturalWidth`还可能经过密度校正，不能一律当作文件原始像素。
- 方案图已`lazy`并由WP输出`auto, ...` sizes；当前首页没有Hero图片，不能把源码的eager/high合同冒充本轮真实Hero性能。
- 候选源码中全局子主题CSS55,272字节、首页69,134、目录71,777、详情66,006、Checkout58,789；5个JS合计23,423字节。均为候选磁盘原始字节，不能与现场gzip数据直接做优化前后比较。
- `inc/setup.php`已按目录、详情、Cart、Checkout、账户加载资源；Variable脚本只用于变体，账户邮箱脚本只用于相应端点。全站Header真实使用`WC_Widget_Cart`，不能直接卸载`wc-cart-fragments`；已有旧首页及Handheld Footer脚本清理无需重复做。
- 当前源码保留Source Sans Pro字体Token；现场存在Google Fonts CSS。字体加载耗时、实际字体文件／字重、失败回退尚未测，不先切字体或移除字体请求。

首页在390／768／1024／1440、DPR1均未出现页面横向溢出；保存390与1440截图。管理员会话原已有6件购物车，本轮未点击或修改；这些浏览器证据只用于图片和布局，不作匿名缓存、购物车或结账通过证据。只读浏览器作用域无法取得Performance对象，故LCP、INP、CLS、FCP及导航时序均未取得，未运行Lighthouse或Query Monitor。

## 临时私密预览实测与关闭（2026-10-22）

复用RG-11，使用固定Shop及首页实际输出的`/product/dental-tooth-display-box/`，`curl -q --config -`关闭默认curlrc继承，保持TLS验证；GET不跟随跳转、不带随机破缓存参数或请求端no-cache。A从原生私密Shop入口保存并重放同一`woo-share` Cookie值，各请求仍是独立curl进程；B不发Cookie。私密输入只经受限文件及curl stdin，不进入命令行、Git或截图。辅助脚本由安全、测试、Code Review专项在执行前只读复核。

| 阶段 | 实际请求／正文 | 结果与边界 |
|---|---|---|
| before，2条 | 无Cookie规范Shop、PDP均Coming Soon／200／noindex；PDP HIT／Age2550 | 预热保护层，不称受控冷缓存 |
| on，第1条 | A原生私密Shop入口，真实12商品列表；no-cache／200／noindex；gzip20,180字节，TTFB1.453332秒 | Cookie与输入匹配，仅入口样本 |
| on，第2条 | A同Cookie规范Shop，真实12商品列表；no-cache／200／noindex；gzip20,033字节，TTFB1.392328秒，总时间1.618623秒 | 真实规范Shop单样本；不是中位数、浏览器或CWV |
| on，第3条 | A同Cookie规范PDP，Coming Soon／200／noindex，HIT／Age3073 | `FG-P2-01`复现，立即停止扩展并关闭预览 |
| off，8条 | A原Cookie与B全新无Cookie分别访问Shop/PDP规范URL、旧私密参数URL，均Coming Soon／200／noindex | 关闭后实际访问恢复保护；无真实页面暴露观测 |

实际共**13次GET（2＋3＋8）**，原计划19次中的启用态B隔离2条、A重复采样4条未执行，不能报告完整矩阵通过。关闭态PDP规范URL仍HIT保护页；旧参数PDP首次MISS／Age0、随后HIT／Age3，补充了不同缓存键的负向证据，仍不能证明每条请求到PHP、Cookie删除、Key轮换或永久失效。两个专项对实际结果独立复核一致。

真实Shop的HTML包含10个CSS引用（9同源＋Google Fonts）、14个外链JS、14个图片标签／13个唯一src；12张商品图有srcset，10张图片lazy、1张high。未出现本轮检测标记下的GTM／Measurement／CMP。以上是HTML引用统计，未执行匿名浏览器资源瀑布、交互或四宽，不把Coming Soon的更多引用当成可删的商品详情资源。

`FG-P2-01`负责人F→G，处置节点为下一次真实PDP测量前：已证明有效Shop预览Cookie遇到PDP保护页缓存，尚未定位Breeze、Varnish或组合的具体责任；不能归因Woo Cookie失效、OCP或订单逻辑，也不重开历史规范Shop已接受修复。最小候选仅对该代表PDP临时精确排除，保留原Shop等规则；先读回两层有效配置，再确认保存／必要清理及恢复影响，不扩大为整个`/product/`或全站Cookie策略。[Cloudways官方说明](https://support.cloudways.com/en/articles/5126470-how-to-install-and-configure-breeze-wordpress-cache-plugin)区分Breeze内部排除与Varnish排除，因此只改插件列表不能预先保证代理层生效。

关闭后的界面截图和脱敏快照在`.codex-tmp/fg-preview-20261022/closed-ui.png`、`closed-ui.txt`，截图已目视核对；机器证据归入既有JSON的`privatePreviewTrial`，不另建测试框架。私密URL、原Cookie和原始HTML在撤销检查与脱敏归档后精确清理；受限脱敏完整报告及公开摘要保留用于复算。

### 单页临时排除复测与恢复

用户已批准上述单页配置范围。当前未见其他任务接管Staging；WordPress管理会话可用，Breeze高级设置重新读回：Never Cache仅现有完整Shop地址，Cache Query Strings为空，Disable Emoji／4个Host Files Locally／API共6个开关仍false；只读取ID、checked及两项textarea，不读取或保存API Token。OCP原生Dashboard/Tools仍Connected／Drop-in Valid，未点击Flush、Scan、Reset或Download Report；有效预取组仍未取得，不把连接状态当配置兼容已验。

Cloudways登录前曾重定向至登录页，两层均未先行修改；用户随后回复“已登录，可继续”。本轮确认`dentall-staging`应用6604195、Varnish开启、Ignore Query String关闭，完整排除表只有原Shop一行。Varnish仅新增URL `/product/dental-tooth-display-box/`，Breeze仅追加对应完整URL；结束时先关闭预览，再恢复Breeze仅原Shop地址、Varnish仅原Shop行。Query为空及6个高级开关false前后一致。两层保存后均实际读回；无部署、升级、业务数据或交易动作。

| 阶段 | 次数与实际结果 | 判定 |
|---|---|---|
| before，临时两层排除后 | 2次无Cookie规范Shop／PDP均Coming Soon、200、noindex，无旧HIT／Age | 通过 |
| on | 私密Shop入口1次；原A Cookie规范Shop／PDP各3次真实正文；B无Cookie两页仍保护，全部200／noindex | 9／9通过 |
| off，临时规则仍保留 | 原A Cookie／新B及各自旧链接共8次均保护，200／noindex | 8／8通过 |
| restored，两层原规则恢复后 | 同样8次仍保护；PDP原A规范URL首次MISS／Age0、新B HIT／Age2，旧参数亦MISS→HIT | 8／8通过 |

规范Shop三次TTFB中位**1.571077秒**（1.311172～3.200158），gzip HTML20,010～20,165字节；规范PDP中位**1.480681秒**（1.239552～1.534145），gzip23,848～24,016字节。私密Shop入口单次1.668516秒／20,189字节另计。数据为临时排除下的实际HTML阶段基线，不是正式缓存态提速前后或CWV。PDP真实HTML有13个CSS引用（12同源）、24个JS、6个img／5个唯一src，3张图库图保留srcset／sizes。

本轮Breeze高级设置保存2次，按已核2.5.12源码会触发应用HTML／资源、Varnish和对象缓存清理；未单独观测每个内部清理，未手动Purge不等于未清缓存。Varnish增删各1次、preview开启关闭保存各1次。**只证明临时两层排除＋保存清理这一组合可完成代表样本预览；无法分离各层／规则／清理的独立贡献。原配置已恢复，`FG-P2-01`永久处理未实施，不能永久关闭该P2。** 关闭态实际证据增强，但仍不证明PHP内部验证分支、Cookie删除、Key轮换、永久撤销、全部URL或交易缓存安全。

复用原helper仅增加`restored`阶段，语法及3处差分独立Review通过，无新框架。安全／缓存与资源／测试两个专项独立复核27／27及原值恢复一致；关闭预览及Varnish原表截图由root目视核对。私密URL、原Cookie及27份原始HTML共29文件在脱敏归档后精确删除，公开证据匹配私密值0，private只保留脱敏完整JSON；浏览器临时地址引用已释放。

### 实际页面补充与新发现

管理员同一浏览器逐宽重新导航Shop／PDP，390／768／1024／1440px、DPR1，共8次记录均无页面横向溢出；保留两页390／1440截图。浏览器缓存未清，不称冷访问或匿名性能；每宽加载后读取实际`currentSrc`、DOM宽度及加载状态。Shop均为12卡，实图宽约125.5／298.5／265／194px。懒加载图可使用150／300px候选；首屏部分仍324／416px。未发现A–Z品牌索引DOM，但不因此直接删全页脚本；其他内容状态与最终版本仍需核。

`FG-P2-02`：真实PDP为`right-sidebar`且缺`storefront-full-width-content`，PC截图实际输出Search／Recent Posts等博客Widget。Storefront4.6.2只在`sidebar-1`不活跃时补全宽class；子主题图库平板／PC规则依赖该class，PHP sizes却无条件按全宽计算。768／1024px实图207.6875／285.59375px，而声明704／960px；1440px实图380.25px。发布线索296d048与候选0.49.2该CSS及尺寸函数相同，不能称候选已修复。负责人F→G，D98图片验收前先修布局，再验证图源，不先缩小sizes去迎合错误侧栏；不全局清空Blog小工具。用户已明确批准两项最小代码修复和隔离验证，细节及后续状态见[[Day98-图片尺寸与商品全宽布局修复]]。

Woo Zoom初始化后另有1600px绝对定位原图及动态缩略图，不能用HTML初始6个img代表最终图库DOM。已观测原图WebP导出57,062字节；它不造成页面溢出，未移除缩放功能。该原图已进入浏览器缓存，因此后续宽度的currentSrc／density-corrected naturalWidth不能证明每宽重新下载1600图。

页面资源库存包含管理员附加资源，不作匿名请求总量或卸载清单。6个已观测字体中，同源图标WOFF2导出79,444字节；5个Google WOFF2跨源导出失败后，使用独立无Cookie、TLS校验curl读取，全部200，文件体积合计65,880字节。文件字节非浏览器传输时序，不与旧HTML gzip量直接相加。额外字体5次curl、资源bundle7项尝试及浏览器导航／自然子资源不属于27次页面矩阵。Performance仍不可用，未取得LCP／INP／CLS。专用前台标签已关闭、视口恢复默认。

D99只读审计未见真实Shop／PDP重复URL。筛选、加购依赖、Variable、图库、Mini Cart及Woo归因脚本均有已确认职责；`defer=false`不证明页头阻塞。Brands脚本仅为候选核查项，CSS仍服务筛选，未删除；无测量依据不合并／延迟现有Woo脚本。原始补充数据留在`.codex-tmp/fg-pdp-exclusion-20261022/`，脱敏摘要进入既有机器证据，不另建框架。

## 最小优化顺序与确认边界

| 优先级 | 候选与职责 | 验收／回退与维护成本 |
|---|---|---|
| 1：Logo资源 | 先复用WordPress原生Custom Logo及已有派生尺寸；正式Logo由WM提供，不让开发者推测授权。若要优化当前fallback，再单独确认同名资源替换或最小输出改动 | 比较相同视口/DPR的currentSrc、传输字节与清晰度；四宽及Header/Footer回归。先检查正式素材能否直接解决，避免给临时占位图增加长期模块 |
| 2：首页分类图sizes | 仅修正首页分类缩略图的尺寸合同，保留Woo原生分类／链接／srcset、原图及其他页面。同版Woo手工输出分类img，经`wp_calculate_image_sizes`，不经`wp_get_attachment_image_attributes`；最小候选在既有首页分类函数的try/finally中临时挂1局部回调并移除，使用现有组件上限`6rem`。约8～12行，无新文件／资源／模板覆盖，实施前说明并确认 | 新匿名浏览器四宽＋DPR1/2；确认少下载且无模糊、无缺图回归。上限为保守值，非四宽精确最小图；CSS上限变更时同步。回退对应子主题补丁与版号；不批量转换或重建全库缩略图 |
| 3：D99资源 | 只处理新基线证实的重复／无用请求；先保留既有按页加载与交易依赖 | Cart／报价／PayPal／Consent／测量联合复验。E未提交consent.js仍沿用0.2.0，正式集成时由E确认资源版号或等效浏览器失效，不能只清Breeze |

本轮没有新运行文件、函数、Hook、CSS规则或插件／依赖。以上为有证据的候选，不表示代码已落地。预计后续图片最小试验与四宽回归约1～3个专注周期，视选型与验证条件调整，属于D98既有范围；没有增加商业功能或承诺提速百分比。D99是否需要代码，待真实资源瀑布确定。D101先复用现有Breeze／OCP，暂未取得新增CDN或另一套缓存的必要性证据。

官方机制参考：[WordPress图片API](https://developer.wordpress.org/reference/functions/wp_get_attachment_image/)支持显式`sizes`、`srcset`及加载属性；[Woo缓存说明](https://github.com/woocommerce/woocommerce/blob/trunk/docs/best-practices/performance/configuring-caching-plugins.md)要求动态交易／账户页保持客户隔离；[OCP非预取组说明](https://objectcache.pro/docs/configuration-options/)用于候选配置核对，不能替代当前1.25.5有效配置和订单实测。

## D100安全缓存验证范围

复用D107 **RG-11**，关联RG-02／04／05／06／08／09／10／14；不另建测试框架。

| 范围 | 必须验证 | 当前状态／需要的窗口 |
|---|---|---|
| 全新匿名公开页 | 独立无Cookie A/B重复规范URL；正文、状态、Age/X-Cache、参数结果 | 本轮独立无Cookie样本已取；不是持久A/B会话完整矩阵 |
| 列表参数 | 分类、排序、分页、价格/属性/品牌、商品搜索、非法302交错后仍正确 | 当前商店保护层；需受控预览或隔离真实页面，保留noindex |
| 账户/订单 | A/B客户及全新匿名C；登录、退出、原退出会话与新匿名分别验；不得串姓名、地址、订单 | 本轮只完成新无Cookie登录入口头；登录令牌、旧退出会话或历史#1954不替代新C |
| Cart/Checkout | 独立A/B不同商品数量；页面、Header和Store API一致；金额权限服务端复核 | 受控交易窗口，需TEST与恢复／邮件边界；不触碰用户原购物车 |
| order-pay | 正确／错误key及邮箱、客户、有效／过期／取消／重签，实际权限金额和刷新 | 有效key GET会经`shipping-quote-lifecycle.php:550–580`取消失效报价，可能写订单及发信；必须单列交易窗口，不复用旧单反复GET |
| 自动失效 | TEST内容修改、撤稿、商品价格库存变化；自动清理与人工定向清理分开证明 | 受控写入窗口；本轮未改内容或清缓存 |
| OCP/CDN | 当前连接/有效组/跨请求订单一致性；实际CDN命中失效（若存在） | OCP已确认存在；有效预取组、Cloudways规则与其他CDN未现场重核 |

原生Woo11匿名私有HTML可能为了BFCache兼容而省略`no-store`；不能仅凭此项缺失判失败。反过来，只有private/no-cache头也不足以证明所有代理层、Cookie和客户隔离均安全。Coming Soon的max-age60必须按保护层解释。

## 专注周期与专项审查

| 周期 | 本批计划／实际推进 | 边界 |
|---|---|---|
| C1 | 读取最新规则、总计划、任务事实及工作树边界 | 根目录和其他任务只读 |
| C2 | 源码／部署来源／运行版本核对 | 未把候选合并当部署 |
| C3 | 图片与按页CSS/JS审计 | 资源Agent独立只读 |
| C4 | Staging状态、16次页面＋17资源取证 | 用户确认的只读窗口 |
| C5 | 首页四宽图片／布局、候选收敛 | 同一管理员页resize，非匿名CWV |
| C6 | 采样／缓存副作用边界独立复核 | 安全缓存Agent只读；动态隔离另验 |
| C7 | 保存可复算证据、更新本树状态与关联笔记 | 不提交别人未提交修改，不推送／部署 |

风险：本轮低风险只读取证；后续缓存会话调整属于高风险，有效报价／订单操作属于交易关键。未实施安全、权限或交易逻辑，未启动无差异的代码实施Review；资源与缓存专项仅对本轮证据和候选边界给独立结论，不声称完整站点P0/P1为0。实际有效工时未记录，不用七个周期代填实际工时。

首轮只读取证独立复核：资源Agent复算17个文件SHA、8个Git blob匹配、图片／CSS数量及页面分类通过；缓存Agent对该首轮采集边界未发现P0/P1/P2，提出1项P3“有跳转时不得把curl原始计时写成真实Checkout TTFB”，已在表格与机器证据收紧。后续私密预览新复现的`FG-P2-01`继续开放；未关闭历史缓存／OCP或其他任务风险。

## 后续启动与Day收尾

1. 本次27条受控预览及规则恢复完成，不能永久关闭`FG-P2-01`或把临时排除基线当正式缓存性能。后续共享测站须重新确认窗口现场，不能跨窗口自行测站。
2. 两项D98代码最小修复已在本树主题0.49.3完成，原生HTTP／四宽／状态／异常清理及独立审查通过，仍未部署共享Staging；见[[Day98-图片尺寸与商品全宽布局修复]]。Logo保留原生Custom Logo接入及原fallback，不新增临时资产框架。D99继续保护交易与E的同意时序。H的必要版本变更、E最终资源确定后只补受影响项。
3. D97真实四类页面及可用性能指标补齐后，才按实际完成层级收尾；D100／D101分别验缓存安全与必要性，D102最终性能继续开放。

本Day全范围尚未完成；已根据本日真实40条预览相关请求、配置恢复和排错形成阶段实战学习笔记，不把它当D97或M8完成证明。D98只生成真实授权与实施记录，不提前生成D99～D102收尾。原始截图保留在本树忽略目录，不写入业务素材库。本轮共享窗口操作已结束，后续源码验证仅用隔离Local。

本Day受控测量未改运行代码，运行代码净增0文件／0函数／0规则块／0行；D98后续源码补丁单独记录，不混作Staging已部署。共享网站累计两轮私密预览开关开启／关闭各保存2次，本次临时Breeze保存2次、Varnish增删各1，均恢复原规则。业务数据、URL、SEO配置、支付、物流、SMTP及部署未改；发生了已授权的临时缓存策略变化与保存清理，不再表述“本轮未清缓存”。

## 可复用核心思想

### 跨平台不变量

测量必须绑定版本、会话、实际正文和网络条件。保护页、重定向、冷／暖缓存和登录角色都会改变资源，先确认测的是哪一种页面，再比较数值。缓存头是证据之一，独立用户的内容与状态隔离才是安全验收目标。

### WordPress/WooCommerce当前实现

父主题、子主题、Woo和插件各有资源职责；先利用既有按页enqueue与WP图片派生尺寸，修正实际下载候选，避免合并／延迟脚本破坏依赖。对象缓存连接成功、页面缓存HIT、私有页隔离与订单实时一致性是不同检查。有效付款页的GET也可能执行业务守卫，不能用HTTP方法名称代替副作用分析。

### Shopify或其他平台对应机制

版本／会话／正文／缓存条件的可比性同样适用；本轮没有验证Shopify的主题资源、图片CDN或结账缓存机制，不能把Woo Hook、Breeze开关或`order-pay`规则照搬到其他平台。
