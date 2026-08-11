# DentAll WooCommerce 项目入口

本目录是DentAll商城的LocalWP项目根目录、Codex工作区和未来Git仓库根目录。WordPress已经由LocalWP创建，正式开发采用单休20周编辑先行版：120个工作日，自然周期约4.6个月。

## 开始工作

1. 阅读`00-项目总档案.md`和`AGENTS.md`。
2. 阅读`project-docs/PROJECT_STATE.md`。
3. 按`DentAll商城项目总计划-单休版.md`选择当天任务。
4. 只在`app/public/wp-content`下开发主题、项目插件和mu-plugin。
5. 收工使用`Obsidian每日复盘模板.md`，将每日笔记保存到`project-docs/笔记/`并更新项目状态。

## 目录

```text
D:\LocalWP\dentall\
├─ 00-项目总档案.md                项目总入口和关键事实
├─ AGENTS.md                       Codex长期项目规范
├─ DentAll商城项目总计划-单休版.md  当前有效排期
├─ project-docs/                   需求、架构、变更、测试和发布文档
├─ design-assets/                  素材规范；原始图片暂存D:\new-project
├─ backups/                        归档模板和受控备份，不进入Git
├─ app/public/                     WordPress根目录
│  └─ wp-content/                  主题、插件、媒体和语言包
├─ conf/                           LocalWP生成配置，不进入Git
└─ logs/                           LocalWP日志，不进入Git
```

## 当前事实

- 当前有效排期：单休20周编辑先行版，对外按4.5～5个月管理。
- 当前首要里程碑：D6安全试录、D18商品模型候选冻结、D24内容样本完成、D25开放批量录入。
- 当前状态入口：`project-docs/PROJECT_STATE.md`。
- 当前设计参考原文件暂存于`D:\new-project`，路径记录在素材登记中。
- 商品模型：WooCommerce原生Product；属性、变体和ACF按数据字典选择。
- 第一周目标：本地和受保护Staging可用，不是公开正式上线。

## 重要规则

- 不将数据库、uploads、密钥和大型设计文件提交到源码Git。
- 项目管理文档放项目根目录或`project-docs/`，不得放进`wp-content`。
- 不修改WordPress、WooCommerce、父主题或第三方插件核心文件。
- 新增、修改和删除需求先登记`project-docs/CHANGE_REQUESTS.md`。
- 发布前执行`project-docs/RELEASE_CHECKLIST.md`。
- 生产故障和恢复按`project-docs/RUNBOOK.md`处理。
