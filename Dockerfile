# 1. ビルド用ステージ
FROM php:8.4-apache AS vendor
# 必要なライブラリとPHP拡張をビルドステージでもインストール
RUN apt-get update && apt-get install -y libpq-dev git zip unzip \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# Composerのインストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /app
COPY composer.json composer.lock ./
# 依存関係をインストール
RUN composer install --no-dev --optimize-autoloader --no-scripts

# 2. 実行用ステージ
FROM php:8.4-apache
RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo pdo_pgsql pgsql

# Apache設定
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN a2enmod rewrite

WORKDIR /var/www/html

# ビルドステージからvendorをコピー
COPY --from=vendor /app/vendor/ ./vendor/
# アプリのソースコードをコピー
COPY . .

# 権限設定
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 起動コマンド
EXPOSE 80
CMD php artisan config:clear && php artisan migrate --force && apache2-foreground