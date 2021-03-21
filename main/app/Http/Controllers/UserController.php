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
}
