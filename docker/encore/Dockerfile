FROM node:10-alpine

COPY encore-entrypoint.sh /usr/bin/encore-entrypoint.sh

RUN set -ex \
    && apk update \
    && apk upgrade \
    && apk add curl git

WORKDIR /home/node/app

RUN chmod a+x /usr/bin/encore-entrypoint.sh

ENTRYPOINT ["encore-entrypoint.sh"]

CMD ["./node_modules/.bin/encore", "dev",  "--watch"]