FROM nginx:alpine

RUN apk add --no-cache openssl

COPY entrypoint.sh /usr/local/bin/entrypoint.sh
COPY nginx.conf.template.dev /etc/nginx/conf.d/dev.template
COPY nginx.conf.template.prod /etc/nginx/conf.d/prod.template

RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]