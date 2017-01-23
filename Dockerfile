FROM ubuntu:16.04
MAINTAINER wild0522 <wild0522@gmail.com>

USER root

RUN locale-gen en_US.UTF-8

ENV LANG=en_US.UTF-8
ENV LANGUAGE=en_US.UTF-8
ENV LC_ALL=en_US.UTF-8
ENV LC_CTYPE=UTF-8
ENV TERM xterm

#ENV MYSQL_ROOT_PASSWORD '123456'

RUN apt-get update && apt-get install -y --force-yes apt-utils

#RUN sed -i "s/^exit 101$/exit 0/" /usr/sbin/policy-rc.d

#install wget, nano editor, git 1.9.1, nginx 1.4.6
RUN DEBIAN_FRONTEND=noninteractive apt-get install -y --force-yes wget curl nano git && \
DEBIAN_FRONTEND=noninteractive apt-get install -y --force-yes nginx

#install php7.0
RUN DEBIAN_FRONTEND=noninteractive apt-get install -y --force-yes software-properties-common && \
LC_ALL=C.UTF-8 DEBIAN_FRONTEND=noninteractive add-apt-repository -y ppa:ondrej/php && \
DEBIAN_FRONTEND=noninteractive apt-get update && \
DEBIAN_FRONTEND=noninteractive apt-get install -y --force-yes php7.0 php7.0-fpm php7.0-mysql php7.0-curl php7.0-gd php7.0-json php7.0-mcrypt php7.0-opcache php7.0-xml php7.0-mbstring php7.0-zip;

#install mysql 5.7.14
RUN \
LC_ALL=C.UTF-8 DEBIAN_FRONTEND=noninteractive add-apt-repository -y ppa:ondrej/mysql-5.7 && \
apt-get update && \
#echo mysql-community-server	mysql-community-server/root-pass password $MYSQL_ROOT_PASSWORD | debconf-set-selections && \
#echo mysql-community-server	mysql-community-server/re-root-pass	password $MYSQL_ROOT_PASSWORD | debconf-set-selections && \
DEBIAN_FRONTEND=noninteractive apt-get install -y --force-yes mysql-server;

#run and set mysql user
RUN service mysql restart && mysql -u root -e "CREATE USER 'wild'@'%' IDENTIFIED BY '123456'" && \
mysql -u root -e "GRANT ALL ON mysql.* TO 'wild'@'%'" && \
mysql -u root -e "ALTER USER 'root'@'localhost' IDENTIFIED BY 'root'";

#install nodejs 7.4.0, npm 4.0.5
RUN \
curl -sL https://deb.nodesource.com/setup_7.x -o /tmp/nodesource_setup.sh && \
bash /tmp/nodesource_setup.sh && \
DEBIAN_FRONTEND=noninteractive apt-get install -y --force-yes nodejs;

#install ssh
RUN DEBIAN_FRONTEND=noninteractive apt-get install -y --force-yes openssh-server && \
mkdir -p /var/run/sshd && \
echo 'root:root' |chpasswd && \ 
sed -ri 's/^PermitRootLogin\s+.*/PermitRootLogin yes/' /etc/ssh/sshd_config && \ 
sed -ri 's/UsePAM yes/#UsePAM yes/g' /etc/ssh/sshd_config && \ 
echo 'export LANGUAGE="en_US.UTF-8"' >> /etc/bash.bashrc && \ 
echo 'export LC_ALL="en_US.UTF-8"' >> /etc/bash.bashrc && \ 
echo 'export LANG="en_US.UTF-8"' >> /etc/bash.bashrc

#install mongoDB 3.4.1
RUN apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv 0C49F3730359A14518585931BC711F9BA15703C6 && \
echo "deb [ arch=amd64 ] http://repo.mongodb.org/apt/ubuntu trusty/mongodb-org/3.4 multiverse" | tee /etc/apt/sources.list.d/mongodb-org-3.4.list && \
apt-get update && DEBIAN_FRONTEND=noninteractive apt-get install -y --force-yes mongodb-org
RUN mkdir -p /data/db

#menual add mongo.sevice & init.d (stop not working)
RUN wget https://raw.githubusercontent.com/mongodb/mongo/master/debian/mongod.service -O /lib/systemd/system/mongod.service && chmod +x /lib/systemd/system/mongod.service && \
wget https://raw.githubusercontent.com/mongodb/mongo/master/debian/init.d -O /etc/init.d/mongod && chmod +x /etc/init.d/mongod

#####################################
# Non-Root User:

ARG PUID=1000
ARG PGID=1000
RUN groupadd -g $PGID dock && \
    useradd -u $PUID -g dock -m dock
####################################

#install composer
RUN \
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
php -r "if (hash_file('SHA384', 'composer-setup.php') === '55d6ead61b29c7bdee5cccfb50076874187bd9f21f65d8991d46ec5cc90518f447387fb9f76ebae1fbbacf329e583e30') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" && \
php composer-setup.php  && \
php -r "unlink('composer-setup.php');" && \
mv composer.phar /usr/local/bin/composer;

ARG TZ=UTC
ENV TZ ${TZ}
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

#php 100 connection
RUN sed -i 's/pm.max_children = 5/pm.max_children = 100/g' /etc/php/7.0/fpm/pool.d/www.conf

USER dock

#add laravel install tool
RUN composer global require "laravel/installer"
ENV PATH /home/dock/.composer/vendor/bin/:$PATH

USER root

#RUN if [[ -z ${1} ]]; then sed -e "s/^bind-address\(.*\)=.*/bind-address = 0.0.0.0/" -i /etc/mysql/my.cnf; else exec "$@"; fi


#mount folder: nginx config, nginx html, www, log
VOLUME ["/etc/nginx/sites-enabled","/usr/share/nginx/html","/var/www","/var/lib/mongodb"]

#auto start nginx, php7, mysql, mongodb, sshd
ENTRYPOINT \
service nginx start && \
service php7.0-fpm start && \
service mysql restart && \
service mongod start && \
/usr/sbin/sshd -D && \
/bin/bash

EXPOSE 80 8000 22 3306 27017

#WORKDIR /usr/share/nginx/html/
#WORKDIR /var/www/

