<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use Kreait\Firebase\Auth;

use App\Models\UserInfo;
use App\Models\User;

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

    public function registerUser(Request $request): array {
        if (!$this->isNormalToken($request->token)) {
            return [
                'isCreateAccount'   => false,
                'isNormalToken'     => false,
            ];
        } else {
            $lastInsertId = 0;
            $this->validate($request, User::rules());
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
                \Log::info($e);
                DB::rollBack();
                DB::commit();
                return [
                    'isCreateAccount'   => false,
                    'isNormalToken'     => true,
                ];
            }
            try {
                // userの情報を登録
                $userInfo = new UserInfo;
                $userInfo->fill([
                    'user_name' => $request->userName,
                    'user_id'   => $lastInsertId,
                    'name'      => $request->name,
                ]);
                $userInfo->save();
            } catch(\Exception $e) {
                \Log::info($e);
                DB::rollBack();
                DB::commit();
                if ($lastInsertId !== 0) { DB::statement("ALTER TABLE users AUTO_INCREMENT=$lastInsertId"); }
                return [
                    'isCreateAccount'   => false,
                    'isNormalToken'     => true,
                ];
            }
            // すべてのデータを正常に保存できたらcommit
            DB::commit();
            return [
                'isCreateAccount'   => true,
                'isNormalToken'     => true,
            ];
        }
    }
    // ユーザーの情報を取得
    public function getUserProfile(Request $request) {
        return UserInfo::where('user_name', $request->userName)
                        ->first(['name', 'user_name', 'intro', 'image_name']);
    }
    // 自分のユーザー情報を取得
    public function getMyUserData(Request $request) {
        try {
            $userId = User::where('uid', $request->uid)
                            ->first()['id'];
            $userInfo = UserInfo::where('user_id', $userId)
                            ->first(['name', 'user_name', 'intro', 'image_name']);
            return [
                'isGetMyUserData' => true,
                'userData' => $userInfo,
            ];
        } catch(\Exception $e) {
            return [
                'isGetMyUserData' => false,
                'userData' => null,
            ];
        }
        
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
                $this->validate($request, UserInfo::rules());
                // ユーザー情報更新
                $userId = User::where('uid', $request->uid)
                                ->first()['id'];
                $userInfo = UserInfo::where('user_id', $userId)
                                ->first();
                $canCangeUserName = (UserInfo::where('user_id', $userId)
                                            ->where('user_name', $request->userName)
                                            ->exists() ||
                                    !UserInfo::where('user_name', $request->userName)
                                        ->exists());
                if ($canCangeUserName) {
                    $userInfo->fill([
                        'user_name' => $request->userName,
                        'intro'     => $request->intro,
                        'name'      => $request->name,
                    ]);
                    $userInfo->save();
                } else {
                    throw new Exception('ユーザーネームが既に存在している。');
                }
            } catch(\Exception $e) {
                return [
                    'isRefreshAccount'  => false,
                    'isNormalToken'     => true,
                ];
            }
            return [
                'isRefreshAccount'  => true,
                'isNormalToken'     => true,
            ];
        } else {
            return [
                'isRefreshAccount'  => false,
                'isNormalToken'     => false,
            ];
        }
    }
    public function refreshUserProfileImage(Request $request) {
        if ($this->isNormalToken($request->token)) {
            if ($request->file) {
                $baseImage = null;
                $tmpFileName = $request->file->getClientOriginalName();
                // アップロードファイルを一時的に保存
                $request->file->storeAs('public/profileIcons', $tmpFileName);
                switch(exif_imagetype('./storage/profileIcons/'.$tmpFileName)) {
                    case IMAGETYPE_JPEG:
                        $baseImage = imagecreatefromjpeg('./storage/profileIcons/'.$tmpFileName);
                        break;
                    case IMAGETYPE_PNG:
                        $baseImage = imagecreatefrompng('./storage/profileIcons/'.$tmpFileName);
                        break;
                    default:
                        break;
                }
                if ($baseImage) {
                    $fileName = md5(uniqid(rand(1000, 9999), true)).'.jpg';
                    list($width, $hight) = getimagesize('./storage/profileIcons/'.$tmpFileName);
                    $image = imagecreatetruecolor(400, 400);
                    imagecopyresampled($image, $baseImage, 0, 0, 0, 0, 400, 400, $width, $hight);
                    imagejpeg($image, './storage/profileIcons/'.$fileName);
                    unlink('./storage/profileIcons/'.$tmpFileName);
                    $userId = User::where('uid', $request->uid)->first()['id'];
                    $userInfo = UserInfo::where('user_id', $userId)->update(['image_name' => $fileName,]);
                    return [
                        'isNormalToken' => true,
                        'isRefreshImage'    => true,
                    ];
                } else {
                    return [
                        'isNormalToken' => true,
                        'isRefreshImage'    => false,
                    ];
                }
            }
        } else {
            return [
                'isNormalToken' => false,
                'isRefreshImage'    => false,
            ];
        }
    }
    public function canUseUserName(Request $request) {
        // $request->userName
        return UserInfo::where('user_name', $request->userName)->exists();
    }
}
