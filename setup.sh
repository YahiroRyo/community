cd /var/www/html \
&& git clone https://github.com/YahiroRyo/community

# setting init
cd community/main \
&& composer self-update \
&& composer update --lock \
&& composer install \
&& composer update \
&& npm i vue-router@next \
&& npm install axios \
&& npm install firebase \
&& composer require kreait/firebase-php \
&& composer require kreait/laravel-firebase \
&& npm install @vuelidate/core@2.0.0-alpha.4 --legacy-peer-deps \
&& npm install @vuelidate/validators@2.0.0-alpha.2 --legacy-peer-deps \
&& php artisan storage:link \
&& mkdir public/storage/profileIcons \
&& cp public/images/default.jpg public/storage/profileIcons/default.jpg

