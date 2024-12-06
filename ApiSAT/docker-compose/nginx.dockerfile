FROM nginx:1.27.2

ADD docker-compose/nginx/default.conf /etc/nginx/conf.d/default.conf

RUN apt-get update && apt-get install -y
# Copy public web files
COPY ./public /var/www/public
