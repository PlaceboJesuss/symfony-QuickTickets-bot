FROM nginx:alpine

RUN apk add --no-cache openssl

COPY entrypoint.sh /usr/local/bin/entrypoint.sh
COPY conf.template /etc/nginx/conf.d/default.conf

RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]