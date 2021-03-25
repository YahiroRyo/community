# 機能設計
### ユーザー
- 作成
- ログイン
- ログアウト
- 閲覧
- 編集
- 退会

### 投稿
- 作成
- 閲覧
- 編集
- 退会
- いいね
- 返信

### コミュニティ
- 作成
- 閲覧
- 編集
- 消去
- 投稿と同等の機能

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
main/resources/js/app.js内の`require('./bootstrap');`を削除  
npm install vuex@next --save  
npm i vue-router@next  
npm install axios  
composer require kreait/firebase-php  
composer require kreait/laravel-firebase  