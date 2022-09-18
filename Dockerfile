FROM php:8.1.10-buster

ENV TZ Asia/Tokyo
RUN echo "${TZ}" > /etc/timezone \
   && dpkg-reconfigure -f noninteractive tzdata

COPY ./ ./

ENV PHP_MEMORY_LIMIT 512M
ENV PHP_MAX_EXECUTION_TIME 250