FROM nginx:alpine

RUN apk add --no-cache openssl

COPY entrypoint.sh /usr/local/bin/entrypoint.sh
COPY https.conf.template /etc/nginx/https.conf.template
COPY http.conf.template /etc/nginx/http.conf.template

RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]