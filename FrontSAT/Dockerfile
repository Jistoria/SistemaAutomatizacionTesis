FROM node:18-bullseye

RUN apt-get update && apt-get install -y nginx && apt-get clean

WORKDIR /app

COPY package*.json ./

RUN npm install

COPY . .

COPY ./nginx.conf /etc/nginx/conf.d/default.conf

RUN rm /etc/nginx/sites-enabled/default

RUN chmod -R 775 /app && chown -R www-data:www-data /app

EXPOSE 80

CMD ["sh", "-c", "node .output/server/index.mjs & nginx -g 'daemon off;'"]