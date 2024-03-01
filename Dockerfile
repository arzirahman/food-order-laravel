FROM composer:2.6.6

WORKDIR /app

COPY . /app

RUN apk update && apk add postgresql-dev && docker-php-ext-install pdo pdo_pgsql bcmath

RUN composer install --no-interaction --prefer-dist

EXPOSE 8080

CMD [ "php", "artisan", "serve", "--host=0.0.0.0", "--port=8080" ]