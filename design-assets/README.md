# DentAll 设计参考库

本目录保存已经筛选、可用于后续前端开发的本地设计参考。素材于2026-08-24从`D:\new-project`整理而来；原始目录仍保留，不移动、不删除。

## 先读结论

- 这些文件是AI辅助生成的扁平截图，只能约束布局、层级、密度和视觉方向，不能替代WordPress、WooCommerce需求、真实业务数据或交互规范。
- 根目录三张“最终效果图”及三端合图用于冻结首页视觉意图；响应式包的高清分图用于放大观察和映射CSS视口。商城与账户单页图主要约束各自的内容区域。发生冲突时，视觉意图高于AI增强细节，共用Header、Footer、颜色和组件风格高于单页中的随机差异。
- 图中的商品名、价格、库存、订单号、日期、Logo副标题、支付图标和安全标识都不是正式业务事实。
- `placeholders/`内素材只用于开发和布局验证，上线前必须替换并补齐授权记录。
- `design-assets/*`已被项目`.gitignore`忽略，属于当前机器上的本地参考库；需要团队共享时应另用资产仓库或Git LFS。

## 目录结构

```text
design-assets/
├─ README.md
├─ references/
│  ├─ home/                       首页视觉源图与四端高清实现参考
│  ├─ commerce/
│  │  ├─ desktop/                9类页面
│  │  ├─ tablet-portrait/        前6类页面
│  │  ├─ tablet-landscape/       登录、注册、搜索无结果
│  │  └─ mobile/                 9类页面
│  └─ overviews/                 四张contact sheet快速总览
├─ placeholders/
│  ├─ hero/
│  ├─ categories/
│  ├─ products/
│  ├─ solutions/
│  └─ svg/
└─ guides/                       原素材包说明、组件树、断点和候选Token
```

## 参考优先级

| 优先级 | 文件 | 用法 | 可信边界 |
|---|---|---|---|
| A | `references/home/source-home-desktop-final.png`、`source-home-tablet-portrait-final.png`、`source-home-mobile-final.png` | 首页及全站共用壳层的视觉意图 | 最接近原始终稿；只约束宏观结构、顺序、响应式重排和颜色方向 |
| A | `references/home/source-home-three-device-reference.png` | 三端交叉核对 | 用于发现AI增强稿新增、删减或错配的内容，不用于直接测CSS |
| B | `references/home/home-desktop-1440.png`、`home-tablet-portrait-768.png`、`home-mobile-390.png` | 对应1440、768、390px的高清实现参考 | 经分离、增强和纵向补全；适合看分区，不可反向覆盖A层视觉意图 |
| B | `references/home/home-tablet-landscape-1024.png` | 1024px密度与布局方向 | 该状态为补生成/推导稿，低于另外三端的可信度 |
| B | `references/commerce/**` | 商城、交易、账户页面的内容区参考 | 只覆盖部分端别和正常态，不能定义WooCommerce流程 |
| C | `references/overviews/**` | 快速浏览页面集合 | 文件名中的contact sheet是“联系总览图”，不是Contact页面 |
| P | `placeholders/**` | 开发占位 | 非正式品牌或商品素材，禁止直接上线 |

`D:\new-project\平板横屏最终效果图.png`与主参考中的1024横屏图哈希一致，因此没有重复复制。旧`DentAll页面效果图-分类高清版`已被“逐页高清最终版”取代，也没有重复复制。

## 已有页面覆盖

| 页面 | PC | 平板竖屏 | 平板横屏 | 手机 | 当前可参考内容 |
|---|---:|---:|---:|---:|---|
| 首页 | ✓ | ✓ | ✓ | ✓ | 完整正常态；1024为推导稿 |
| 商品列表/分类归档 | ✓ | ✓ | — | ✓ | 商品网格、排序、筛选入口、分页 |
| 商品详情 | ✓ | ✓ | — | ✓ | Variable商品已选规格、有库存的正常态 |
| 购物车 | ✓ | ✓ | — | ✓ | 非空购物车与金额摘要 |
| 结账 | ✓ | ✓ | — | ✓ | 主要是地址步骤，支付与最终确认不完整 |
| 我的账户 | ✓ | ✓ | — | ✓ | 已登录且有历史订单的Dashboard |
| 账户订单 | ✓ | ✓ | — | ✓ | 非空订单列表 |
| 登录 | ✓ | — | ✓ | ✓ | 空白表单正常态 |
| 注册 | ✓ | — | ✓ | ✓ | 空白表单正常态 |
| 搜索无结果 | ✓ | — | ✓ | ✓ | 单一无结果状态 |

前6类商城页缺平板横屏，后三类账户/状态页缺平板竖屏。它们仍须在390、768、1024、1440px真实页面中验证，不能因为没有对应截图就跳过适配。

## 哪些较规范，哪些只可辅助

### 可以采用

1. `references/home/source-home-*.png`：用于确定首页的宏观结构、模块顺序、蓝白视觉和三端重排，是视觉意图的主依据。
2. `references/commerce/`中的逐页单图：适合拆解商品列表、商品详情、购物车、结账和账户内容区。
3. `references/home/home-*.png`：用于放大观察并映射1440、1024、768、390px视口；其中1024横屏为补生成稿。
4. `guides/component-map.md`、`responsive-breakpoints.md`和`design-tokens.css`：可作为D27～D30的候选输入，但必须经浏览器验证后才能冻结。

### 不应直接采用

- 旧“分类高清版”的重复图片不作为实现基准；根目录最终图保留视觉意图，但其压缩画布不用于直接测量页面高度或间距。
- 每张截图里不同的Logo副标题、公告文案、商品/订单数据和错拼文字。
- 非官方支付、社交平台、安全认证图标；应从对应品牌官方资源或正式插件输出获取。
- 截图里的浏览器外框、假地址栏和设备外壳。
- 只存在于截图、尚未进入已确认需求的功能：多语言/多币种切换、Wishlist、Buy Now、Google/Apple登录、Wallet、Notifications、固定手机底栏、商品视频、高级筛选、自定义订单状态Tab。
- AI稿中的“三步式结账”不能覆盖WooCommerce真实结账、支付插件和错误处理流程。

### 已确认的AI不一致

- 首页平板竖屏重复了`Equipment`分类；手机热卖区出现商品图片与`Zirconia Disc`名称错配。
- Logo副标题在`Dental Solutions`、`Dental Supplies`、`Dental Marketplace`等版本间变化，并出现错拼或乱码。
- 免邮门槛、退货天数、商品价格、评价数、购物车角标、订单号与日期跨图不一致。
- 商品列表存在异常价格文字、无意义字段和“分类数量/结果数量”矛盾。
- 商品详情的规格值、数量控件和购买区在不同设备间缺失或变化。
- PC与手机结账步骤名称和顺序冲突，部分电话、邮编等字段错位或重复，手机稿也没有覆盖完整支付与提交区。
- 账户订单中出现年份、状态和编号冲突；导航、搜索和固定手机底栏在相邻页面随机出现或消失。

这些差异统一按“忽略AI伪影，使用WooCommerce真实状态与已确认需求”处理，不要求逐张修图后才开始开发。

## 仍缺的页面与关键状态

### 第一版需要补齐的独立页面或模板

- 有结果的搜索页；它应与Shop和Product Category复用归档模板。
- Order received/Thank you，以及支付失败或取消后的结果状态。
- 账户订单详情、地址列表/编辑、Account Details。
- 忘记密码、重置密码，以及链接无效或过期状态。
- About和政策类通用内容页模板。
- Blog列表/分类/分页和文章详情。
- Solutions最小内容页。
- Contact表单、FAQ、真实HTTP 404页面。

### 不必逐张画整页，但必须由组件覆盖的状态

- Header下拉导航、手机菜单、搜索建议、Mini Cart的打开/空/更新中/错误。
- Newsletter和所有表单的校验、提交中、成功、失败、键盘焦点。
- 商品列表的0/1/少量/大量、筛选已选/重置/无结果、分页、缺图、长标题、促销、缺货、不可购买。
- Simple商品；Variable未选择、非法组合、某Variation缺货、全部不可购买；单图/多图/缺图；加购成功和失败。
- `display_only`商品只显示参考价与Contact入口，不能进入购物车或结账。
- 空购物车、优惠券成功/失败/过期/重复、数量与库存冲突、金额更新中或失败。
- 结账必填错误、配送无可用方式、支付处理中/失败/取消/超时。
- 账户无订单、登录/注册错误、越权访问订单的安全拒绝。

## 建议的可维护模块边界

```text
SiteShell
├─ AnnouncementBar
├─ Header
│  ├─ Logo
│  ├─ Search
│  ├─ AccountLink
│  ├─ MiniCart
│  └─ Navigation / MobileMenu
├─ Main
│  ├─ HomeSections
│  ├─ ArchiveShell + FilterPanel + ProductGrid
│  ├─ ProductDetail
│  ├─ Cart / Checkout / OrderResult
│  ├─ AccountShell
│  └─ ContentShell / Blog / Contact / FAQ / 404
├─ Newsletter
└─ Footer

Shared UI
├─ ProductCard
├─ Button / FormField / FormNotice
├─ Price / Badge / QuantityControl
└─ Loading / Empty / Error / Success / MissingImage
```

不要按“一个截图一个大模板”开发。首页热卖、商品归档和相关商品复用`ProductCard`；Shop、分类和搜索结果复用`ArchiveShell`；登录、注册、地址、结账和Contact复用表单基础组件；Simple、Variable、缺货和Display Only是`ProductDetail`的状态分支。

## 与Day26～D30的关系

- D26：只建立Storefront父主题＋DentAll子主题的可运行骨架和资源加载边界，不开始逐页像素还原。
- D27：从主参考提取并验证颜色、字体、容器、间距、圆角和层级Token。
- D28：按钮、链接、输入框、选择器、数量控件和通知。
- D29：商品卡、内容卡、价格、徽标和图片比例。
- D30：栅格及loading、empty、error、缺图、长文本、售罄/不可购买状态。
- D31以后：先共用Header/Footer，再进入首页和WooCommerce页面。

## 素材与授权缺口

目前仍没有可直接上线的官方Logo、品牌字体及许可证、真实Hero/分类/Solutions/商品图片、正式支付与社交图标、最终英文文案和已确认业务数据。开发时可以继续使用本目录固定比例占位素材，但验收时必须明确标注TEST/placeholder，不能把AI推测值发布为正式内容。

## 原始来源

- 首页原图与旧版辅助图：`D:\new-project`
- 首页响应式包：`D:\new-project\dentall-responsive-package`
- 商城与账户逐页终版：`D:\new-project\DentAll页面效果图-逐页高清最终版`
- 本地资产登记：`project-docs/CONTENT_ASSET_REGISTER.md`
