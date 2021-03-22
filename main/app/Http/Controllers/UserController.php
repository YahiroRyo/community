<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use Kreait\Firebase\Auth;

use App\Models\User;
use App\Models\UserInfo;

class UserController extends Controller
{
    private $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }
    // トークンが正常なものか確認
    public function isNormalToken($token): bool {
        try {
            $isCorrectToken = $this->auth->verifyIdToken($token);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

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

    public function registerUser(Request $request) {
        if (!$this->isNormalToken($request->token)) {
            return [
                'isNormalToken' => false,
                'isCreateAccount' => false,
            ];
        } else {
            $lastInsertId = 0;
            // ロールバックをできるようにバックアップポイント作成
            DB::beginTransaction();
            try {
                // user登録
                $user = new User;
                $user->fill([ 'uid' => $request->uid, ]);
                $user->save();
                // userで登録したidを保持しておく
                $lastInsertId = $user->id;
            } catch(\Exception $e) {
                DB::rollBack();
                DB::commit();
                return [
                    'isNormalToken' => true,
                    'isCreateAccount' => false,
                ];
            }
            try {
                // userの情報を登録
                $userInfo = new UserInfo;
                $userInfo->fill([
                    'name' => $request->name,
                    'user_id' => $lastInsertId,
                    'user_name' => $request->userName,
                ]);
                $userInfo->save();
            } catch(\Exception $e) {
                DB::rollBack();
                DB::commit();
                if ($lastInsertId !== 0) { DB::statement("ALTER TABLE users AUTO_INCREMENT=$lastInsertId"); }
                return [
                    'isNormalToken' => true,
                    'isCreateAccount' => false,
                ];
            }
            // すべてのデータを正常に保存できたらcommit
            DB::commit();
            return [
                'isNormalToken' => true,
                'isCreateAccount' => true,
            ];
        }
    }
    // ユーザーの情報を取得
    public function getUserProfile(Request $request) {
        return UserInfo::where('user_name', $request->userName)->first(['name', 'user_name', 'intro']);
    }
    // 自分のユーザー情報を取得
    public function getMyUserData(Request $request) {
        $userId = User::where('uid', $request->uid)->first()['id'];
        return UserInfo::where('user_id', $userId)->first(['name', 'user_name', 'intro']);
    }
    public function refreshUserProfile(Request $request) {
        /* ---------------取得する変数一覧--------------- */
        // $request->uid:       更新する対象のuid
        // $request->name:      更新する名前
        // $request->userName:  更新するユーザーネーム
        // $request->intro:     更新する自己紹介

        // ・エラーが出た場合
        // エラーをキャッチし、isRefreshAccountをfalseにして返す

        if ($this->isNormalToken($request->token)) {
            try {
                // ユーザー情報更新
                $userId = User::where('uid', $request->uid)->first()['id'];
                $userInfo = UserInfo::where('user_id', $userId)->first();
                $userInfo->fill([
                    'name' => $request->name,
                    'user_name' => $request->userName,
                    'intro' => $request->intro,
                ]);
                $userInfo->save();
            } catch(\Exception $e) {
                return [
                    'isRefreshAccount' => false,
                    'isNormalToken' => true,
                ];
            }
            return [
                'isRefreshAccount' => true,
                'isNormalToken' => true,
            ];
        } else {
            return [
                'isRefreshAccount' => false,
                'isNormalToken' => false,
            ];
        }
    }
}
