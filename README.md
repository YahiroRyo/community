# 【機能一覧】
## 基本機能
- ユーザー登録
- ユーザー消去
- ユーザー情報変更
  (アバター、ネーム、ユーザーネーム)
- ユーザーマイページ表示
- ログイン
## 投稿に関する機能
- 生成、読みとり、削除
- いいね
- 返信
- 画像添付
- 画像プレビュー
## コミュニティに関する機能
- 生成

# バージョン
### フレームワーク等
PHP 8.0.3 (cli)  
Laravel 8.32.1  
Vue.js 3.0.7  
### プラグイン
vuex 4.0.0  
vue-router 4.0.5  

# 環境構築コマンド等
composer create-project --prefer-dist laravel/laravel main  
composer require laravel/ui  
npm install -g npm  
main/resources/js/app.js内の`require('./bootstrap');`を削除 ------ ここまでのコマンド入力は不要  

---------- ここから環境構築のためのコマンド ----------  
cd main  
composer install 
composer update  
sudo npm install vuex@next --save  
sudo npm i vue-router@next  
sudo npm install axios  
sudo npm install --save firebase  
composer require kreait/firebase-php  
composer require kreait/laravel-firebase  
sudo npm install @vuelidate/core@2.0.0-alpha.4 --legacy-peer-deps  
sudo npm install @vuelidate/validators@2.0.0-alpha.2 --legacy-peer-deps  
php artisan storage:link  
mkdir public/storage/profileIcons  
cp public/images/default.jpg public/storage/profileIcons/default.jpg  
.envファイルをGoogleドライブからダウンロード後、それをmainファイル直下に貼り付け  
