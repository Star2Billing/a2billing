#FROM php:7.4-apache
FROM php:8.0-apache

COPY ./composer.json /app/package.json
COPY ./composer.json /app/package-lock.json
COPY ./customer /var/www/a2billing/customer
COPY ./agent    /var/www/a2billing/agent
COPY ./admin    /var/www/a2billing/admin
COPY ./commen   /var/www/a2billing/commen
#RUN chown -R www-data:www-data /var/www
COPY ./addons/asterisk-conf/extentions_a2billing_1_6.conf  /etc/apache2/sites-available/extentions_a2billing_1_6.conf
COPY ./addons/asterisk-conf/extentions_a2billing_1_8.conf  /etc/apache2/sites-available/extentions_a2billing_1_8.conf
COPY ./addons/asterisk-conf/extentions_a2billing.conf  /etc/apache2/sites-available/extentions_a2billing.conf



 



#CMD ["start-apache"]
#WORKDIR /app
#FROM nginx:alpine
#COPY ./a2billing.conf /etc/nginx/nginx.conf
#RUN docker-php-ext-install mysqli

