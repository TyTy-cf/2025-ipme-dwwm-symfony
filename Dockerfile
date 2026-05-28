FROM base AS dev

# Install Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash && \
    mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

ARG USER_ID=1000
ARG GROUP_ID=1000

COPY user_entry_point.sh /user_entry_point.sh
RUN chmod +x /user_entry_point.sh
RUN /user_entry_point.sh ${USER_ID} ${GROUP_ID}

#switch to the good user
USER ${USER_ID}:${GROUP_ID}

FROM php:8.2-fpm AS php

# Add the github script to easy install-php-extensions
ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN install-php-extensions pdo_mysql zip ctype iconv xsl gd intl

# Ajouter composer depuis l'image composer officiel
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Ajouter le client symfony
COPY --link \
    --from=ghcr.io/symfony-cli/symfony-cli:latest \
    /usr/local/bin/symfony /usr/local/bin/symfony

FROM caddy:2.11 AS caddy

COPY Caddyfile /etc/caddy/Caddyfile
