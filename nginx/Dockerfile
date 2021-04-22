FROM nginx

RUN apt-get update && apt update

RUN apt-get -y install software-properties-common

RUN LC_ALL=C.UTF-8 add-apt-repository ppa:ondrej/php

# php install
RUN apt install php8.0 php8.0-intl php8.0-mysql php8.0-sqlite3 php8.0-gd

# zip unzip install
RUN apt-get -y install zip unzip

# nodejs install
RUN apt-get -y install nodejs npm \
&& npm install -g n \
&& n stable

# composer install
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
&& php -r "if (hash_file('sha384', 'composer-setup.php') === '756890a4488ce9024fc62c56153228907f1545c228516cbf63f885e036d37e9a59d27d63f46af1d4d07ee0f76181c7d3') ***REMOVED*** echo 'Installer verified'; ***REMOVED*** else ***REMOVED*** echo 'Installer corrupt'; unlink('composer-setup.php'); ***REMOVED*** echo PHP_EOL;" \
&& php composer-setup.php \
&& mv composer.phar /usr/local/bin/composer

RUN apt-get -y install git-all

RUN cd /var/www/html \
&& git clone https://github.com/YahiroRyo/community 

# setting init
RUN composer self-update \
&& composer update --lock \
&& composer install \
&& composer update \
&& npm i vue-router@next \
&& npm install axios \
&& npm install --save firebase \
&& composer require kreait/firebase-php \
&& composer require kreait/laravel-firebase \
&& npm install @vuelidate/core@2.0.0-alpha.4 --legacy-peer-deps \
&& npm install @vuelidate/validators@2.0.0-alpha.2 --legacy-peer-deps \
&& php artisan storage:link \
&& mkdir public/storage/profileIcons \
&& cp public/images/default.jpg public/storage/profileIcons/default.jpg
