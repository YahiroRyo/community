<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use Kreait\Firebase\Auth;

use App\Models\UserInfo;
use App\Models\User;

class UserController extends Controller
***REMOVED***
    private $auth;

    public function __construct(Auth $auth)
    ***REMOVED***
        $this->auth = $auth;
    ***REMOVED***
    // トークンが正常なものか確認
    public function isNormalToken($token): bool ***REMOVED***
        try ***REMOVED***
            $isCorrectToken = $this->auth->verifyIdToken($token);
            return true;
        ***REMOVED*** catch (\Exception $e) ***REMOVED***
            return false;
        ***REMOVED***
    ***REMOVED***

    /* ---------------アカウント登録について--------------- */
    // ・変数の説明
    // $request->token:     トークン
    // $request->uid:       登録するuid
    // $request->name:      登録する名前
    // $request->userName:  登録するユーザー名
    // isNormalToken:       正常なTokenだったか
    // isCreateAccount:     アカウントの作成に成功したか

    // ・エラーが出た場合
    // エラーをキャッチし、ロールバックをする
    // その時点で、ユーザーの作成は不可能なためisCreateAccountをfalseにしてreturnする

    public function registerUser(Request $request): array ***REMOVED***
        if (!$this->isNormalToken($request->token)) ***REMOVED***
            return [
                'isCreateAccount'   => false,
                'isNormalToken'     => false,
            ];
        ***REMOVED*** else ***REMOVED***
            $lastInsertId = 0;
            // ロールバックをできるようにバックアップポイント作成
            DB::beginTransaction();
            try ***REMOVED***
                // user登録
                $user = new User;
                $user->fill([ 'uid' => $request->uid, ]);
                $user->save();
                // userで登録したidを保持しておく
                $lastInsertId = $user->id;
            ***REMOVED*** catch(\Exception $e) ***REMOVED***
                DB::rollBack();
                DB::commit();
                return [
                    'isCreateAccount'   => false,
                    'isNormalToken'     => true,
                ];
            ***REMOVED***
            try ***REMOVED***
                // userの情報を登録
                $userInfo = new UserInfo;
                $userInfo->fill([
                    'user_name' => $request->userName,
                    'user_id'   => $lastInsertId,
                    'name'      => $request->name,
                ]);
                $userInfo->save();
            ***REMOVED*** catch(\Exception $e) ***REMOVED***
                DB::rollBack();
                DB::commit();
                if ($lastInsertId !== 0) ***REMOVED*** DB::statement("ALTER TABLE users AUTO_INCREMENT=$lastInsertId"); ***REMOVED***
                return [
                    'isCreateAccount'   => false,
                    'isNormalToken'     => true,
                ];
            ***REMOVED***
            // すべてのデータを正常に保存できたらcommit
            DB::commit();
            return [
                'isCreateAccount'   => true,
                'isNormalToken'     => true,
            ];
        ***REMOVED***
    ***REMOVED***
    // ユーザーの情報を取得
    public function getUserProfile(Request $request) ***REMOVED***
        return UserInfo::where('user_name', $request->userName)
                        ->first(['name', 'user_name', 'intro', 'image_name']);
    ***REMOVED***
    // 自分のユーザー情報を取得
    public function getMyUserData(Request $request) ***REMOVED***
        try ***REMOVED***
            $userId = User::where('uid', $request->uid)
                            ->first()['id'];
            $userInfo = UserInfo::where('user_id', $userId)
                            ->first(['name', 'user_name', 'intro', 'image_name']);
            return [
                'isGetMyUserData' => true,
                'userData' => $userInfo,
            ];
        ***REMOVED*** catch(\Exception $e) ***REMOVED***
            return [
                'isGetMyUserData' => false,
                'userData' => null,
            ];
        ***REMOVED***
        
    ***REMOVED***
    public function refreshUserProfile(Request $request) ***REMOVED***
        /* ---------------取得する変数一覧--------------- */
        // $request->uid:       更新する対象のuid
        // $request->name:      更新する名前
        // $request->userName:  更新するユーザーネーム
        // $request->intro:     更新する自己紹介

        // ・エラーが出た場合
        // エラーをキャッチし、isRefreshAccountをfalseにして返す

        if ($this->isNormalToken($request->token)) ***REMOVED***
            try ***REMOVED***
                // ユーザー情報更新
                $userId = User::where('uid', $request->uid)
                                ->first()['id'];
                $userInfo = UserInfo::where('user_id', $userId)
                                ->first();
                $userInfo->fill([
                    'user_name' => $request->userName,
                    'intro'     => $request->intro,
                    'name'      => $request->name,
                ]);
                $userInfo->save();
            ***REMOVED*** catch(\Exception $e) ***REMOVED***
                return [
                    'isRefreshAccount'  => false,
                    'isNormalToken'     => true,
                ];
            ***REMOVED***
            return [
                'isRefreshAccount'  => true,
                'isNormalToken'     => true,
            ];
        ***REMOVED*** else ***REMOVED***
            return [
                'isRefreshAccount'  => false,
                'isNormalToken'     => false,
            ];
        ***REMOVED***
    ***REMOVED***
***REMOVED***
