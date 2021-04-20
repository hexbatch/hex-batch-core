FROM composer/composer
LABEL maintainer="Will<development@hexbatch.com>" \
      description="For sql migrations"


# PDO Extension
RUN docker-php-ext-install pdo_mysql

RUN composer global require 'robmorgan/phinx'
RUN composer global require 'symfony/yaml'

ENTRYPOINT ["/tmp/vendor/bin/phinx"]
CMD ["--help"]
