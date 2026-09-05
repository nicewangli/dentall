=== DentAll Core ===
Contributors: dentall
Requires at least: 7.0
Requires PHP: 8.2
Stable tag: 0.2.7
License: GPL-2.0-or-later

DentAll 商城跨主题的最小业务能力。

== Description ==

当前版本提供内容试录员与Website Manager角色，以及业务内容、商城运营、媒体、系统权限和网站级SEO兼容边界。

== Changelog ==

= 0.2.7 =
* 将商品筛选参数页标记为noindex, follow，同时保留Yoast基础归档Canonical。

= 0.2.6 =
* 为Website Manager授予WooCommerce原生商品导入所需的WordPress全局import能力。
* 商品导出继续限制在WooCommerce原生商品导出请求；不加载自定义商品导入模块。

= 0.2.3 =
* 修复Yoast启用时WordPress核心仍重复输出文档Title的问题；Yoast停用时保留核心Title。

= 0.2.2 =
* 按角色、媒体、商品治理和后台访问职责拆分内部模块，不改变现有功能与权限边界。

= 0.2.1 =
* 隐藏并拒绝Website Manager访问无业务功能的WordPress工具入口。

= 0.2.0 =
* 新增Website Manager商城运营权限白名单与版本化角色同步。
* 开放文章、页面、媒体元数据、分类、标签、评论、商品评价与内容级SEO能力。
* 开放商品、属性、订单、优惠券、报表、客户创建与WooCommerce管理能力。
* Website Manager沿用第一阶段安全位图和5MB上传边界。
* 让5MB限制覆盖REST原始媒体上传。

= 0.1.2 =
* 隐藏并拒绝内容试录员访问评论和工具后台页面。

= 0.1.1 =
* 统一媒体界面与服务端的5MB上传限制。
* 阻止内容试录员创建商品标签。

= 0.1.0 =
* 建立内容试录员最小权限和媒体上传边界。
