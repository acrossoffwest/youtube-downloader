FROM acrossoffwest/domda-php-fpm:8.0.8.0

MAINTAINER Yurij Karpov <acrossoffwest@gmail.com>

RUN install-php-extensions pdo_mysql mysqli pcntl swoole

RUN apk --no-cache update \
    && apk --no-cache upgrade \
    && apk add --no-cache \
            mysql-client \
            freetype \
            libpng \
            freetype-dev \
            libpng-dev \
            jpeg-dev \
            libjpeg \
            libjpeg-turbo-dev \
            wget \
            zlib-dev \
            ttf-freefont \
            fontconfig \
            xvfb \
            libxrender-dev \
            gettext \
            gettext-dev \
            libxml2-dev \
            gnu-libiconv-dev \
            autoconf \
            g++ \
            git \
            bash \
            wkhtmltopdf \
            icu-dev

RUN apk add --no-cache ffmpeg

CMD ["crond"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]
