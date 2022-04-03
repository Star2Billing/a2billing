FROM php:7.4-apache
#FROM php:8.0-apache

COPY ./composer.json /app/package.json
COPY ./composer.json /app/package-lock.json
COPY customer /var/www
RUN chown -R www-data:www-data /var/www
COPY ./addons/asterisk-conf/extentions_a2billing_1_6.conf  /etc/apache2/sites-available/extentions_a2billing_1_6.conf
COPY ./addons/asterisk-conf/extentions_a2billing_1_8.conf  /etc/apache2/sites-available/extentions_a2billing_1_8.conf
COPY ./addons/asterisk-conf/extentions_a2billing.conf  /etc/apache2/sites-available/extentions_a2billing.conf

COPY ./addons/apach2/a2billing_admin.conf  /etc/apache2/sites-available/a2billing_admin.conf
COPY ./addons/apach2/a2billing_agent.conf  /etc/apache2/sites-available/a2billing_agent.conf
COPY ./addons/apach2/a2billing_customer.conf  /etc/apache2/sites-available/a2billing_customer.conf
COPY ./addons/apach2/a2billing_inex.conf  /etc/apache2/sites-available/a2billing_index.conf
COPY ./addons/apach2/a2billing_webservice.conf  /etc/apache2/sites-available/a2billing_webservice.conf



#CMD ["start-apache"]
#WORKDIR /app
#FROM nginx:alpine
#COPY ./a2billing.conf /etc/nginx/nginx.conf
#RUN docker-php-ext-install mysqli

