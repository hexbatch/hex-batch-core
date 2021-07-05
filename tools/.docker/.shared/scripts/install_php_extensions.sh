#!/bin/sh


# download helper script
# @see https://github.com/mlocati/docker-php-extension-installer/


# install extensions
chmod uga+x /usr/local/bin/install-php-extensions && sync && install-php-extensions \
    mysqli \
    pdo_mysql \
    xdebug \
    curl \
    zip \
    bcmath \
    intl \
;

