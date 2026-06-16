FROM php:8.4-apache

# 必要なPHP拡張モジュールのインストール
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql pdo_pgsql pgsql

# Apacheの設定変更（Laravelのpublicフォルダをドキュメントルートにする）
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN a2enmod rewrite

# Composerのインストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# プロジェクトファイルのコピー
WORKDIR /var/www/html
COPY . .

# 依存関係のインストール
RUN composer install --no-dev --optimize-autoloader

EXPOSE 80

# 権限設定（念のため再確認）
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# キャッシュを徹底的に削除し、migrationを実行してからApacheを起動
CMD rm -f bootstrap/cache/config.php && \
    rm -f bootstrap/cache/services.php && \
    rm -f bootstrap/cache/packages.php && \
    php artisan migrate --force && \
    apache2-foreground
