FROM ubuntu:16.04
MAINTAINER Nandan Desai <nkdesai409@gmail.com>
ENV DEBIAN_FRONTEND noninteractive
RUN apt-get update
RUN apt-get install -y apache2 php-xdebug libapache2-mod-php php php-mysql pwgen php-mcrypt php-gd php-xml php-mbstring php-gettext zip unzip php-zip curl php-curl
RUN rm /var/www/html/index.html
ADD ./setup_files/index.php /var/www/html/
RUN chmod 777 -R /var/www/html
CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]
EXPOSE 80
