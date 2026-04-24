FROM nginx:alpine

# Устанавливаем openssl для генерации DH параметров
RUN apk add --no-cache openssl

COPY entrypoint.sh /usr/local/bin/entrypoint.sh
COPY ./dev.conf.template /etc/nginx/conf.d/default.conf.template
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80 443
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
