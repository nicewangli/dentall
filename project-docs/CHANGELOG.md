# 版本变更记录

采用语义化版本思路：重大不兼容变化为主版本，新增兼容功能为次版本，缺陷修复为修订版本。每个正式发布绑定Git标签、数据库备份和发布记录。

## Unreleased

### 新增

- 项目专属Codex Skill和`AGENTS.md`。
- 双休100日项目计划和Obsidian复盘模板。
- 四端首页效果图及响应式素材包。
- 项目背景、需求、架构、数据、SEO、变更、测试和发布文档。
- 单休编辑先行版96日计划及D6/D12/D18三阶段编辑验收门槛。
- 单休20周、120日项目计划及D1～D25编辑第一阶段验收体系。
- `DentAll Website Manager`角色版本5，覆盖文章、页面、媒体、评论、商品、术语、订单、优惠券、客户创建和WooCommerce报表等业务能力。
- D12 Local简单/可变商品原型、自动审计脚本及Staging双环境权限验收记录。
- D17 Staging五个代表商品样本矩阵，覆盖Simple、Variable、缺货Variation、多图及Yoast字段保存/输出；作为D18商品模型候选冻结输入。
- D18 M2商品模型候选冻结结论与W3周验收：冻结Simple/Variable、父子SKU、合法组合、库存真相源、物流继承/覆盖、图片、SEO及Website Manager职责边界，不把TEST值升级为正式业务事实。

### 修改

- 项目排期从单休基线调整为20周双休基线。
- 当前有效排期由20周双休调整为16周单休，并将商品、文章和页面编辑流程前置到W1-W3。
- 当前有效排期由16周单休调整为20周单休，对外周期为4.5～5个月；编辑第一阶段延长到D25。
- 当前两名网站人员统一使用独立Website Manager账号；低权限Content Editor保留为未来可选角色，不纳入D12当前人员验收。
- D12 TEST对象保留为D13及下周回归夹具，D25前再次复核归档或清理。
- DentAll Core 0.2.2按角色、媒体、商品治理和后台访问拆分内部模块，保持既有函数、Hook、权限和运行行为不变；Local验证完成，尚未部署Staging。
- DentAll Core 0.2.3新增独立SEO兼容模块，修复Yoast启用时WordPress Block Template重复输出Title；Yoast停用时保留WordPress核心Title回退。已完成Local验证并部署Staging，五页矩阵与D17代表商品SEO输出通过受保护环境边界检查。
- DentAll Core 0.2.4将角色定义升级为版本6，重新同步Website Manager既有高级SEO元数据能力，并在商品编辑页隐藏WordPress原始自定义字段面板；Local与Staging均已复测通过。
- DentAll Core 0.2.5允许Website Manager使用WooCommerce原生商品CSV导出；`export`只在商品列表、商品导出页面、对应AJAX与下载请求中临时生效，不写入角色数据库，也不开放WordPress全站内容导出。Local 5行与Staging 10行商品CSV均已验证；Staging通过`e9e21c4`部署并完成D18 C6关键路径复测。

### 删除

- 无。

### 安全

- 规定生产密钥不进入Git；Staging必须禁止索引并使用支付沙盒。
- Website Manager继续禁止WordPress用户、插件、主题、代码和系统设置；Site Kit未来使用只读Dashboard Sharing，GTM在Google平台单独授权。
- Website Manager的`wpseo_edit_advanced_metadata`属于Yoast整组高级元数据能力，除Canonical和robots外还可能包含advanced robots、Breadcrumbs Title等字段；高影响修改继续执行旧值、新值、原因、受影响URL、复核人与页面回归记录。
- 商品CSV包含价格、库存、描述与素材URL，按业务数据文件管理；0.2.5仍拒绝Website Manager访问WordPress全站导出。中文WooCommerce CSV存在Upsells/Cross-sells均显示为“交叉销售”的重复表头，D25无损回导前必须规范化或在隔离环境验证。
- 角色能力会持久化到WordPress数据库；若需撤销Website Manager高级SEO能力，必须从角色白名单移除并提升新的单调递增角色版本。普通代码降级不能替代撤权，紧急角色对象撤权也必须在同一发布窗口补上版本化修复。

### 修复

- DentAll Core 0.2.1隐藏并拦截Website Manager和Content Editor无业务内容的Tools入口。
- DentAll Core 0.2.4修复Local数据库角色版本未同步`wpseo_edit_advanced_metadata`的问题，并对Website Manager隐藏商品原始自定义字段面板，降低误改`total_sales`等技术元数据的风险；该界面防护不替代服务端capability和WooCommerce CRUD边界。

## 发布模板

## [版本号] - YYYY-MM-DD

### 新增

### 修改

### 修复

### 删除

### 安全

### 数据库/迁移

### 已知问题

### 发布证据

- Git标签：
- 数据库备份：
- uploads快照：
- 测试报告：
- 回滚说明：
