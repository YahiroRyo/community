<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Kreait\Firebase\Auth;

use App\Models\IsJoiningCommunity;
use App\Models\CanIJoinCommunity;
use App\Models\Community;
use App\Models\User;
use App\Models\Bell;

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
            try {
                $userId = User::where('uid', $request->uid)->first()['id'];
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
        // $request->uid
        // $request->take
        // $request->gotNum
        if (ctype_digit(strval($request->take)) && (ctype_digit(strval($request->gotNum)) || !$request->gotNum)) {
            $result = [];
            
            $take   = intval($request->take);
            $gotNum = intval($request->gotNum);
            try {
                $userId = User::where('uid', $request->uid)->first()['id'];
                $communities = Community::select(['id', 'name', 'description'])
                                        ->with([
                                            'isJoiningCommunity' => function ($query) use ($userId) {
                                                $query->where('user_id', $userId)
                                                        ->get();
                                            },
                                            'canIJoinCommunity' => function ($query) use ($userId) {
                                                $query->select(['community_id'])
                                                        ->where('user_id', $userId)
                                                        ->get();
                                            }
                                        ])->take($take + $gotNum)
                                        ->orderBy('id', 'desc')
                                        ->get();
            } catch(\Exception $e) {
                return [
                    'isGetCommunities' => false,
                    'communities' => null
                ];
            }
            for ($i = $gotNum; $i < count($communities); $i++) {
                array_push($result, $communities[$i]);
            }
            return [ 'isGetCommunities' => true, 'communities' => $result, ];
        } else {
            return [
                'isGetCommunities' => false,
                'communities' => null,
            ];
        }
    }
    // 加入申請
    public function canIJoinCommunity(Request $request) {
        // $request->uid
        // $request->token
        // $request->communityId
        if ($this->isNormalToken($request->token)) {
            $founderUserId;
            $canIJoinCommunity;
            $userId;
            try {
                $founderUserId  = Community::where('id', $request->communityId)
                                            ->first()['user_id'];
                $userId         = User::where('uid', $request->uid)
                                            ->first()['id'];
                // すでに加入申請がされていた場合
                $canIJoinCommunity = CanIJoinCommunity::where('user_id', $userId)
                                                        ->where('community_id', $request->communityId)
                                                        ->exist();
            } catch(\Exception $e) {
                return [
                    'isNormalToken' => true,
                    'isCanIJoinCommunity' => false,
                ];
            }
            if (!$canIJoinCommunity) {
                $bellId;    // bellIdをcanIJoinCommunityで使用するため定義
                DB::beginTransaction();
                try {
                    $bell = new Bell;
                    $bell->fill([
                        'user_id' => $founderUserId,
                        'type' => 1,
                    ]);
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
    // コミュニティへの加入申請を取り消し
    public function cancelJoinCommunity(Request $request) {
        // $request->token
        // $request->uid
        // $request->communityId
        if ($this->isNormalToken($request->token)) {
            $userId = User::where('uid', $request->uid)->first()['id'];
            $canIJoinCommunityBellId;
            DB::beginTransaction();
            try {
                $canIJoinCommunityBellId = CanIJoinCommunity::where('user_id', $userId)
                                ->where('community_id', $request->communityId)
                                ->first()['bell_id'];
                CanIJoinCommunity::where('user_id', $userId)
                                ->where('community_id', $request->communityId)
                                ->delete();
            } catch(\Exception $e) {
                DB::rollBack();
                DB::commit();
                return [
                    'isNormalToken' => true,
                    'isCancelJoinCommunity' => false,
                ];
            }
            try {
                Bell::where('id', $canIJoinCommunityBellId)->delete();
            } catch(\Exception $e) {
                DB::rollBack();
                DB::commit();
                return [
                    'isNormalToken' => true,
                    'isCancelJoinCommunity' => false,
                ];
            }
            DB::commit();
            return [
                'isNormalToken' => true,
                'isCancelJoinCommunity' => true,
            ];
        } else {
            return [
                'isNormalToken' => false,
                'isCancelJoinCommunity' => false,
            ];
        }
    }
    // コミュニティに参加
    public function joinCommunity(Request $request) {
        // $request->token
        // $request->communityId
        // $request->userId
        // $request->bellId
        if ($this->isNormalToken($request->token)) {
            DB::beginTransaction();
            try {
                CanIJoinCommunity::where('bell_id', $request->bellId)
                                ->where('user_id', $request->userId)
                                ->delete();
            } catch (\Exception $e) {
                DB::rollBack();
                DB::commit();
                return [
                    'isNormalToken' => true,
                    'isJoinCommunity' => false,
                ];
            }
            try {
                $isJoiningCommunity = new IsJoiningCommunity;
                $isJoiningCommunity->fill([
                    'user_id' => $request->userId,
                    'community_id' => $request->communityId,
                ]);
                $isJoiningCommunity->save();
            } catch (\Exception $e) {
                DB::rollBack();
                DB::commit();
                return [
                    'isNormalToken' => true,
                    'isJoinCommunity' => false,
                ];
            }
            try {
                Bell::where('id', $request->bellId)
                    ->where('user_id', $request->userId)
                    ->delete();
            } catch (\Exception $e) {
                DB::rollBack();
                DB::commit();
                return [
                    'isNormalToken' => true,
                    'isJoinCommunity' => false,
                ];
            }
            DB::commit();
            return [
                'isNormalToken' => true,
                'isJoinCommunity' => true,
            ];
        } else {
            return [
                'isNormalToken' => false,
                'isJoinCommunity' => false,
            ];
        }
    }
    // コミュニティへの加入申請を拒否
    public function dontJoinCommunity(Request $request) {
        // $request->token
        // $request->userId
        // $request->bellId
        if ($this->isNormalToken($request->token)) {
            DB::beginTransaction();
            try {
                CanIJoinCommunity::where('bell_id', $request->bellId)
                                ->where('user_id', $request->userId)
                                ->delete();
            } catch (\Exception $e) {
                DB::rollBack();
                DB::commit();
                return [
                    'isNormalToken' => true,
                    'isDontJoinCommunity' => false,
                ];
            }
            try {
                Bell::where('id', $request->bellId)
                    ->where('user_id', $request->userId)
                    ->delete();
            } catch (\Exception $e) {
                DB::rollBack();
                DB::commit();
                return [
                    'isNormalToken' => true,
                    'isDontJoinCommunity' => false,
                ];
            }
            DB::commit();
            return [
                'isNormalToken' => true,
                'isDontJoinCommunity' => true,
            ];
        } else {
            return [
                'isNormalToken' => false,
                'isDontJoinCommunity' => false,
            ];
        }
    }
}
