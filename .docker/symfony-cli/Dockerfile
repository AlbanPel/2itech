FROM php:8.1.5-fpm-bullseye

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN echo 'deb [trusted=yes] https://repo.symfony.com/apt/ /' | tee /etc/apt/sources.list.d/symfony-cli.list
RUN apt update && \
    apt install -y  symfony-cli  vim wget zip unzip

RUN curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.1/install.sh | bash

RUN usermod -u 1000 www-data

USER $USERNAME

COPY entrypoint.sh /usr/bin/
RUN chmod +x /usr/bin/entrypoint.sh
ENTRYPOINT ["entrypoint.sh"]
CMD ["php-fpm"]
