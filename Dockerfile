# 使用内置 Apache 的 PHP 官方镜像
FROM php:8.2-apache

# 1. 安装必要的 PHP 扩展（如果你的 AI 逻辑需要用到 curl 或其他工具）
RUN apt-get update && apt-get install -y \
    libcurl4-openssl-dev \
    pkg-config \
    libssl-dev \
    && docker-php-ext-install curl \
    && rm -rf /var/lib/apt/lists/*

# 2. 启用 Apache 的 rewrite 模块（对很多 PHP 框架和路由很有用）
RUN a2enmod rewrite

# 3. 将你的项目文件复制到 Apache 的默认 Web 目录
COPY . /var/www/html/

# 4. 设置权限，确保 Apache 可以读取文件
RUN chown -R www-data:www-data /var/www/html/

# 5. 暴露 80 端口
EXPOSE 80

# Apache 会自动启动，不需要额外的 CMD
