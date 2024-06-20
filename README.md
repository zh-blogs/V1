# zhblogs-back-end（已弃用）
中文博客列表导航后台。本版本根据 [https://github.com/zh-blogs/blog-daohang/issues/52](https://github.com/zh-blogs/V2/issues/52) 进行弃用。

# 部署

## 环境

> 仅在 centos 7 下测试, 理论上 linux 下均可

1. php 8+ (最好安装event扩展)
2. mysql 5.7+
3. redis

## 安装

1. git clone git@github.com:zh-blogs/zhblogs-back-end.git
2. composer install
3. 参考 .env.example 配置数据库等信息并重命名为 .env

## 启动
```shell
php start.php start # 以debug 模式启动
php start.php start -d # 以守护模式启动

php start.php status -d # 查看运行状态
php start.php restart   # 重启服务
php start.php reload    # 重载服务
```
