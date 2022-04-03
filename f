[33mcommit b5fecc5aa657ba1897cc9ba4c93df2a601a5a641[m[33m ([m[1;36mHEAD -> [m[1;32mmain[m[33m, [m[1;31morigin/main[m[33m, [m[1;31morigin/develop[m[33m, [m[1;31morigin/HEAD[m[33m)[m
Author: Nima Fakhari <nima.fakhari@gmail.com>
Date:   Mon Feb 14 18:21:44 2022 +0330

    Update Dockerfile

[1mdiff --git a/Dockerfile b/Dockerfile[m
[1mindex f784db7d..ce4a87a8 100644[m
[1m--- a/Dockerfile[m
[1m+++ b/Dockerfile[m
[36m@@ -5,7 +5,7 @@[m [mFROM php:7.4-apache[m
 #COPY my-apache-site.conf /etc/apache2/sites-available/my-apache-site.conf[m
 #RUN apt-get install libapache2-mod-php5 php5 php5-common[m
 #RUN apt-get install php5-cli php5-mysql mysql-server apache2 php5-gd[m
[31m-RUN apt-get install openssh-server subversion[m
[32m+[m[32m#RUN apt-get install openssh-server subversion[m[41m[m
 #RUN apt-get install php5-mcrypt[m
 RUN apt-get install asterisk[m
 [m
