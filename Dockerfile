# 使用 PHP 8.3 FPM 作為基礎映像
FROM php:8.3-fpm

# 更新包列表並安裝依賴
RUN apt-get update && apt-get install -y --no-install-recommends \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    mariadb-client \
    && docker-php-ext-install pdo pdo_mysql

# 安裝 PHP 擴展
# RUN apt-get install unixodbc unixodbc-dev -y \
#  && docker-php-ext-configure pdo_odbc --with-pdo-odbc=unixODBC,/usr \
#  && docker-php-ext-install pdo_odbc

# 安裝 Redis PHP 擴展
RUN pecl install redis \
    && docker-php-ext-enable redis

# 清理安裝過程中的緩存
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# # 安裝依賴包
# RUN apt-get install -y --no-install-recommends \
#     build-essential \
#     libpng-dev \
#     libjpeg62-turbo-dev \
#     libfreetype6-dev \
#     libzip-dev \
#     zip \
#     unzip \
#     git \
#     curl \
#     redis-server \
#     && docker-php-ext-install pdo pdo_mysql mbstring zip gd \
#     && apt-get clean \
#     && rm -rf /var/lib/apt/lists/*
# 安裝 Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 設置工作目錄
WORKDIR /var/www

# 複製 Laravel 專案文件
# COPY . .

# 安裝 Laravel 依賴
# RUN composer install

# 設置權限
RUN chown -R www-data:www-data /var/www

# 曝露 PHP FPM 的端口
EXPOSE 9000

# 啟動 PHP-FPM
CMD ["php-fpm"]