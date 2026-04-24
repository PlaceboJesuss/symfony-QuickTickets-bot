FROM nginx:alpine

RUN apk add --no-cache openssl

COPY entrypoint.sh /usr/local/bin/entrypoint.sh
COPY dev.conf.template /etc/nginx/conf.d/dev.template
COPY prod.conf.template /etc/nginx/conf.d/prod.template

RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]