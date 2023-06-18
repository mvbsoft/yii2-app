FROM redis:latest

MAINTAINER Vitalii Malinovskyi <malina.mvb@gmail.com>

COPY ./configs/redis.conf /usr/local/etc/redis/redis.conf

WORKDIR /data

CMD [ "redis-server", "/usr/local/etc/redis/redis.conf" ]