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
    // コミュニティを作成
    public function createCommunity(Request $request) ***REMOVED***
        // $request->uid
        // $request->token
        // $request->name
        // $request->description
        if ($this->isNormalToken($request->token)) ***REMOVED***
            $userId = User::where('uid', $request->uid)->first()['id'];
            try ***REMOVED***
                $community = new Community;
                $community->fill([
                    'user_id' => $userId,
                    'name' => $request->name,
                    'description' => $request->description,
                ]);
                $community->save();
            ***REMOVED*** catch (\Exception $e) ***REMOVED***
                return [
                    'isCreateCommunity' => false,
                    'isNormalToken' => true,
                ];
            ***REMOVED***
            return [
                'isCreateCommunity' => true,
                'isNormalToken' => true,
            ];
        ***REMOVED***
        return [
            'isCreateCommunity' => false,
            'isNormalToken' => false,
        ];
    ***REMOVED***
    // コミュニティを取得
    public function getCommunities(Request $request) ***REMOVED***
        // $request->take
        // $request->gotNum
        if (ctype_digit(strval($request->take)) && (ctype_digit(strval($request->gotNum)) || !$request->gotNum)) ***REMOVED***
            $result = [];
            
            $take = intval($request->take);
            $gotNum = intval($request->gotNum);
            $communities = Community::select(['id', 'name', 'description'])
                                    ->with([
                                        'isJoiningCommunity',
                                        'canIJoinCommunity' => function ($query) ***REMOVED***
                                            $query->select(['community_id']);
                                        ***REMOVED***
                                    ])->take($take + $gotNum)->get();
            for ($i = $gotNum; $i < count($communities); $i++) ***REMOVED***
                array_push($result, $communities[$i]);
            ***REMOVED***
            return [ 'isGetCommunities' => true, 'communities' => $result, ];
        ***REMOVED*** else ***REMOVED***
            return [
                'isGetCommunities' => false,
            ];
        ***REMOVED***
    ***REMOVED***
    // 加入申請
    public function canIJoinCommunity(Request $request) ***REMOVED***
        // $request->uid
        // $request->token
        // $request->communityId
        if ($this->isNormalToken($request->token)) ***REMOVED***
            $userId = User::where('uid', $request->uid)->first()['id'];
            // すでに加入申請がされていた場合
            $canIJoinCommunity = CanIJoinCommunity::where('user_id', $userId)
                                                    ->where('community_id', $request->communityId)
                                                    ->first();
            if ($canIJoinCommunity === null) ***REMOVED***
                $bellId;    // bellIdをcanIJoinCommunityで使用するため定義
                DB::beginTransaction();
                try ***REMOVED***
                    $bell = new Bell;
                    $bell->fill(['type' => 1,]);
                    $bell->save();
                    $bellId = $bell->id;
                ***REMOVED*** catch(\Exception $e) ***REMOVED***
                    DB::rollBack();
                    DB::commit();
                    return [
                        'isNormalToken' => true,
                        'isCanIJoinCommunity' => false,
                    ];
                ***REMOVED***
                try ***REMOVED***
                    $canIJoinCommunity = new CanIJoinCommunity;
                    $canIJoinCommunity->fill([
                        'bell_id' => $bellId,
                        'user_id' => $userId,
                        'community_id' => $request->communityId,
                    ]);
                    $canIJoinCommunity->save();
                ***REMOVED*** catch(\Exception $e) ***REMOVED***
                    DB::rollBack();
                    DB::commit();
                    return [
                        'isNormalToken' => true,
                        'isCanIJoinCommunity' => false,
                    ];
                ***REMOVED***
                DB::commit();
                return [
                    'isNormalToken' => true,
                    'isCanIJoinCommunity' => true,
                ];
            ***REMOVED*** else ***REMOVED***
                return [
                    'isNormalToken' => true,
                    'isCanIJoinCommunity' => true,
                ];
            ***REMOVED***
        ***REMOVED*** else ***REMOVED***
            return [
                'isNormalToken' => false,
                'isCanIJoinCommunity' => false,
            ];
        ***REMOVED***
    ***REMOVED***
***REMOVED***
