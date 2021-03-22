<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Kreait\Firebase\Auth;

use App\Models\User;
use App\Models\Community;
use App\Models\Bell;
use App\Models\CanIJoinCommunity;

class CommunityController extends Controller
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
    // コミュニティを作成
    public function createCommunity(Request $request) {
        // $request->uid
        // $request->token
        // $request->name
        // $request->description
        if ($this->isNormalToken($request->token)) {
            $userId = User::where('uid', $request->uid)->first()['id'];
            try {
                $community = new Community;
                $community->fill([
                    'user_id' => $userId,
                    'name' => $request->name,
                    'description' => $request->description,
                ]);
                $community->save();
            } catch (\Exception $e) {
                return [
                    'isCreateCommunity' => false,
                    'isNormalToken' => true,
                ];
            }
            return [
                'isCreateCommunity' => true,
                'isNormalToken' => true,
            ];
        }
        return [
            'isCreateCommunity' => false,
            'isNormalToken' => false,
        ];
    }
    // コミュニティを取得
    public function getCommunities(Request $request) {
        // $request->take
        // $request->gotNum
        if (ctype_digit(strval($request->take)) && (ctype_digit(strval($request->gotNum)) || !$request->gotNum)) {
            $result = [];
            
            $take = intval($request->take);
            $gotNum = intval($request->gotNum);
            $communities = Community::select(['id', 'name', 'description'])
                                    ->with([
                                        'isJoiningCommunity',
                                        'canIJoinCommunity' => function ($query) {
                                            $query->select(['community_id']);
                                        }
                                    ])->take($take + $gotNum)->get();
            for ($i = $gotNum; $i < count($communities); $i++) {
                array_push($result, $communities[$i]);
            }
            return [ 'isGetCommunities' => true, 'communities' => $result, ];
        } else {
            return [
                'isGetCommunities' => false,
            ];
        }
    }
    // 加入申請
    public function canIJoinCommunity(Request $request) {
        // $request->uid
        // $request->token
        // $request->communityId
        if ($this->isNormalToken($request->token)) {
            $userId = User::where('uid', $request->uid)->first()['id'];
            // すでに加入申請がされていた場合
            $canIJoinCommunity = CanIJoinCommunity::where('user_id', $userId)
                                                    ->where('community_id', $request->communityId)
                                                    ->first();
            if ($canIJoinCommunity === null) {
                $bellId;    // bellIdをcanIJoinCommunityで使用するため定義
                DB::beginTransaction();
                try {
                    $bell = new Bell;
                    $bell->fill(['type' => 1,]);
                    $bell->save();
                    $bellId = $bell->id;
                } catch(\Exception $e) {
                    DB::rollBack();
                    DB::commit();
                    return [
                        'isNormalToken' => true,
                        'isCanIJoinCommunity' => false,
                    ];
                }
                try {
                    $canIJoinCommunity = new CanIJoinCommunity;
                    $canIJoinCommunity->fill([
                        'bell_id' => $bellId,
                        'user_id' => $userId,
                        'community_id' => $request->communityId,
                    ]);
                    $canIJoinCommunity->save();
                } catch(\Exception $e) {
                    DB::rollBack();
                    DB::commit();
                    return [
                        'isNormalToken' => true,
                        'isCanIJoinCommunity' => false,
                    ];
                }
                DB::commit();
                return [
                    'isNormalToken' => true,
                    'isCanIJoinCommunity' => true,
                ];
            } else {
                return [
                    'isNormalToken' => true,
                    'isCanIJoinCommunity' => true,
                ];
            }
        } else {
            return [
                'isNormalToken' => false,
                'isCanIJoinCommunity' => false,
            ];
        }
    }
}
