FROM php:7.4-apache
#FROM php:8.0-apache
#RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
#RUN apt-get update && apt-get upgrade -y
#COPY my-apache-site.conf /etc/apache2/sites-available/my-apache-site.conf
#RUN apt-get install libapache2-mod-php5 php5 php5-common
#RUN apt-get install php5-cli php5-mysql mysql-server apache2 php5-gd
#RUN apt-get install openssh-server subversion
#RUN apt-get install php5-mcrypt
RUN apt-get install asterisk

#COPY start-apache /usr/local/bin
#RUN a2enmod rewrite
#COPY src /var/www/
COPY ./composer.json /app/package.json
COPY ./composer.json /app/package-lock.json
COPY src /var/www/
#RUN chown -R www-data:www-data /var/www

#CMD ["start-apache"]
#WORKDIR /app
#FROM nginx:alpine
#COPY ./a2billing.conf /etc/nginx/nginx.conf
#RUN docker-php-ext-install mysqli
