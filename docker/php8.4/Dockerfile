FROM public.ecr.aws/docker/library/php:8.4-fpm AS base

RUN docker-php-ext-install  \
      pdo_mysql

## use latest composer
COPY --from=public.ecr.aws/docker/library/composer:latest /usr/bin/composer /usr/bin/composer