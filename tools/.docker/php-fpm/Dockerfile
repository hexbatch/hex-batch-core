ARG TARGET_PHP_VERSION=7.4
FROM php:${TARGET_PHP_VERSION}-fpm

COPY --from=composer /usr/bin/composer /usr/bin/composer

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

ARG SERVICE_DIR="./php-fpm"
COPY ./.shared/scripts/ /tmp/scripts/
RUN chmod +x -R /tmp/scripts/

# set timezone
ARG TZ=UTC
RUN /tmp/scripts/set_timezone.sh ${TZ}

# add users
ARG APP_USER=www-data
ARG APP_GROUP=www-data
ARG APP_USER_ID=1000
ARG APP_GROUP_ID=1000

RUN /tmp/scripts/create_user.sh ${APP_USER} ${APP_GROUP} ${APP_USER_ID} ${APP_GROUP_ID}

RUN /tmp/scripts/install_software.sh

RUN /tmp/scripts/install_php_extensions.sh




# php-fpm pool config
COPY ${SERVICE_DIR}/php-fpm.d/* /usr/local/etc/php-fpm.d


RUN /tmp/scripts/modify_config.sh /usr/local/etc/php-fpm.d/zz-app.conf \
    "__APP_USER" \
    "${APP_USER}" \
 && /tmp/scripts/modify_config.sh /usr/local/etc/php-fpm.d/zz-app.conf \
    "__APP_GROUP" \
    "${APP_GROUP}" \
;

RUN mkdir -p /home/www-data/.ssh/ \
 && chown ${APP_USER}:${APP_USER} -R /home/www-data/ \
 && chmod go-rwx -R /home/www-data/ \
;

RUN (host=github.com; ssh-keyscan -H $host; for ip in $(dig @8.8.8.8 github.com +short); do ssh-keyscan -H $host,$ip; ssh-keyscan -H $ip; done) 2> /dev/null >> /home/www-data/.ssh/known_hosts;

RUN chown ${APP_USER}:${APP_USER}  /home/www-data/.ssh/known_hosts \
 && chmod go+r  /home/www-data/.ssh/known_hosts \
;

# workdir
ARG APP_CODE_PATH="/var/www/current"
WORKDIR "$APP_CODE_PATH"

# entrypoint
RUN mkdir -p /bin/docker-entrypoint/ \
 && cp /tmp/scripts/docker-entrypoint/* /bin/docker-entrypoint/ \
 && chmod +x -R /bin/docker-entrypoint/ \
;

RUN /tmp/scripts/cleanup.sh
ENTRYPOINT ["/bin/docker-entrypoint/resolve-docker-host-ip.sh","php-fpm"]
EXPOSE 9000

