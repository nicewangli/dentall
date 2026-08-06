# 备份与项目归档目录规则

本目录只用于备份清单和本地受控副本，不进入源码Git，也不能作为唯一备份位置。

## 每个可恢复版本包含

```text
release-YYYYMMDD-HHMM-vX.Y.Z/
├─ RELEASE-MANIFEST.md
├─ database.sql.gz
├─ uploads-manifest.txt
├─ plugins-and-environment.md
└─ checksums.txt
```

uploads体积较大时可存储在独立备份系统，归档目录只保留快照ID、时间、大小和校验信息。

## 发布清单至少记录

- 版本号和Git标签。
- 数据库备份路径、时间、大小和校验值。
- uploads快照ID或存储路径。
- WordPress、WooCommerce、主题、插件、PHP和数据库版本。
- 生产部署时间、负责人和验证结果。
- 回滚步骤和上一个稳定版本。

## 安全

- 数据库和备份可能包含客户个人信息，必须加密并限制访问。
- 不将生产数据库上传给公共AI、公共网盘或不受控设备。
- 本地开发需要真实结构时使用脱敏副本。
- 定期测试恢复，不能只检查“备份任务显示成功”。
