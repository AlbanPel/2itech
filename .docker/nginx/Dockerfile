FROM nginx:1.21.6-alpine

LABEL author="Khalid Sookia"

COPY ./app.conf /etc/nginx/conf.d/app.conf

EXPOSE 80 443

ENTRYPOINT ["nginx", "-g", "daemon off;"]
