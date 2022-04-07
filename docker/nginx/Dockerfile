FROM nginx:1.13.5-alpine
COPY symfony.conf /etc/nginx/conf.d/default.conf
RUN mkdir -p /var/www/html/public/ \
    && echo "@edge http://nl.alpinelinux.org/alpine/edge/main" >> /etc/apk/repositories \
    && echo "@edge http://nl.alpinelinux.org/alpine/edge/community" >> /etc/apk/repositories \
    && echo "@edge http://nl.alpinelinux.org/alpine/edge/testing" >> /etc/apk/repositories \
    && apk update && apk add shadow@edge sudo
WORKDIR /var/www/html
COPY nginx-entrypoint.sh /usr/bin/nginx-entrypoint.sh

RUN chmod a+x /usr/bin/nginx-entrypoint.sh

ENTRYPOINT ["nginx-entrypoint.sh"]
CMD ["nginx", "-g", "daemon off;"]