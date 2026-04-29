FROM nginx:alpine

RUN apk add --no-cache \
    openssl \
    inotify-tools

COPY entrypoint.sh /usr/local/bin/entrypoint.sh
COPY watch-certs.sh /usr/local/bin/watch-certs.sh
COPY https.conf.template /etc/nginx/https.conf.template
COPY http.conf.template /etc/nginx/http.conf.template

RUN chmod +x /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/watch-certs.sh

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]