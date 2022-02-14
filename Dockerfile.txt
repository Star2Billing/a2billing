FROM php:7.4-apache

#RUN apt-get install libapache2-mod-php5 php5 php5-common
##RUN apt-get install php5-cli php5-mysql mysql-server apache2 php5-gd
#RUN apt-get install openssh-server subversion
#RUN apt-get install php5-mcrypt
#RUN apt-get install asterisk

#RUN docker-php-ext-install mysqli
#WORKDIR /app
# Copy some overview page that links to the next devices
#COPY ./composer.json /app/package.json
#COPY ./composer.json /app/package-lock.json

#COPY . .
#FROM nginx
#COPY ./a2billing.conf /etc/nginx/conf.d/default.conf
#COPY ./a2billing/admin/index.php /var/www/html/admin
#COPY ./a2billing/customer/index.php /var/www/html/customere

