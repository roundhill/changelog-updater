FROM composer:2

LABEL repository="https://github.com/bloom/changelog-updater"
LABEL homepage="https://github.com/bloom/changelog-updater"
LABEL maintainer="Automattic"

RUN mkdir -p /app
WORKDIR /app
COPY . .

RUN composer install --no-dev --no-interaction --no-progress --no-scripts --optimize-autoloader

ENTRYPOINT ["php", "/app/action.php"]
