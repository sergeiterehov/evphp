FROM composer:latest

COPY ./ /app

RUN composer install

CMD ["php", "/app/vendor/codeception/codeception/codecept", "run"]