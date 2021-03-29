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
            \Log::info($e);
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
            try ***REMOVED***
                $userId = User::where('uid', $request->uid)->first()['id'];
                $community = new Community;
                $community->fill([
                    'user_id' => $userId,
                    'name' => $request->name,
                    'description' => $request->description,
                ]);
                $community->save();
            ***REMOVED*** catch (\Exception $e) ***REMOVED***
                \Log::info($e);
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
        // $request->uid
        // $request->take
        // $request->gotNum
        if (ctype_digit(strval($request->take)) && (ctype_digit(strval($request->gotNum)) || !$request->gotNum)) ***REMOVED***
            $result = [];
            
            $take   = intval($request->take);
            $gotNum = intval($request->gotNum);
            try ***REMOVED***
                $userId = User::where('uid', $request->uid)->first()['id'];
                $communities = Community::select(['id', 'name', 'description'])
                                        ->with([
                                            'isJoiningCommunity' => function ($query) use ($userId) ***REMOVED***
                                                $query->where('user_id', $userId)
                                                        ->get();
                                            ***REMOVED***,
                                            'canIJoinCommunity' => function ($query) use ($userId) ***REMOVED***
                                                $query->select(['community_id'])
                                                        ->where('user_id', $userId)
                                                        ->get();
                                            ***REMOVED***
                                        ])->take($take + $gotNum)
                                        ->orderBy('id', 'desc')
                                        ->get();
            ***REMOVED*** catch(\Exception $e) ***REMOVED***
                \Log::info($e);
                return [
                    'isGetCommunities' => false,
                    'communities' => null
                ];
            ***REMOVED***
            for ($i = $gotNum; $i < count($communities); $i++) ***REMOVED***
                array_push($result, $communities[$i]);
            ***REMOVED***
            return [ 'isGetCommunities' => true, 'communities' => $result, ];
        ***REMOVED*** else ***REMOVED***
            return [
                'isGetCommunities' => false,
                'communities' => null,
            ];
        ***REMOVED***
    ***REMOVED***
    // 加入申請
    public function canIJoinCommunity(Request $request) ***REMOVED***
        // $request->uid
        // $request->token
        // $request->communityId
        if ($this->isNormalToken($request->token)) ***REMOVED***
            $founderUserId;
            $canIJoinCommunity;
            $userId;
            try ***REMOVED***
                $founderUserId  = Community::where('id', $request->communityId)
                                            ->first()['user_id'];
                $userId         = User::where('uid', $request->uid)
                                            ->first()['id'];
                // すでに加入申請がされていた場合
                $canIJoinCommunity = CanIJoinCommunity::where('user_id', $userId)
                                                        ->where('community_id', $request->communityId)
                                                        ->exists();
            ***REMOVED*** catch(\Exception $e) ***REMOVED***
                \Log::info($e);
                return [
                    'isNormalToken' => true,
                    'isCanIJoinCommunity' => false,
                ];
            ***REMOVED***
            if (!$canIJoinCommunity) ***REMOVED***
                $bellId;    // bellIdをcanIJoinCommunityで使用するため定義
                DB::beginTransaction();
                try ***REMOVED***
                    $bell = new Bell;
                    $bell->fill([
                        'user_id'   => $founderUserId,
                        'type'      => 1,
                    ]);
                    $bell->save();
                    $bellId = $bell->id;
                ***REMOVED*** catch(\Exception $e) ***REMOVED***
                    \Log::info($e);
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
                    \Log::info($e);
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
    // コミュニティへの加入申請を取り消し
    public function cancelJoinCommunity(Request $request) ***REMOVED***
        // $request->token
        // $request->uid
        // $request->communityId
        if ($this->isNormalToken($request->token)) ***REMOVED***
            $userId = User::where('uid', $request->uid)->first()['id'];
            $canIJoinCommunityBellId;
            DB::beginTransaction();
            try ***REMOVED***
                $canIJoinCommunityBellId = CanIJoinCommunity::where('user_id', $userId)
                                ->where('community_id', $request->communityId)
                                ->first()['bell_id'];
                CanIJoinCommunity::where('user_id', $userId)
                                ->where('community_id', $request->communityId)
                                ->delete();
            ***REMOVED*** catch(\Exception $e) ***REMOVED***
                \Log::info($e);
                DB::rollBack();
                DB::commit();
                return [
                    'isNormalToken' => true,
                    'isCancelJoinCommunity' => false,
                ];
            ***REMOVED***
            try ***REMOVED***
                Bell::where('id', $canIJoinCommunityBellId)->delete();
            ***REMOVED*** catch(\Exception $e) ***REMOVED***
                \Log::info($e);
                DB::rollBack();
                DB::commit();
                return [
                    'isNormalToken' => true,
                    'isCancelJoinCommunity' => false,
                ];
            ***REMOVED***
            DB::commit();
            return [
                'isNormalToken' => true,
                'isCancelJoinCommunity' => true,
            ];
        ***REMOVED*** else ***REMOVED***
            return [
                'isNormalToken' => false,
                'isCancelJoinCommunity' => false,
            ];
        ***REMOVED***
    ***REMOVED***
    // コミュニティに参加
    public function joinCommunity(Request $request) ***REMOVED***
        // $request->token
        // $request->communityId
        // $request->userId
        // $request->bellId
        if ($this->isNormalToken($request->token)) ***REMOVED***
            DB::beginTransaction();
            try ***REMOVED***
                CanIJoinCommunity::where('bell_id', $request->bellId)
                                ->where('user_id', $request->userId)
                                ->delete();
            ***REMOVED*** catch (\Exception $e) ***REMOVED***
                \Log::info($e);
                DB::rollBack();
                DB::commit();
                return [
                    'isNormalToken' => true,
                    'isJoinCommunity' => false,
                ];
            ***REMOVED***
            try ***REMOVED***
                $isJoiningCommunity = new IsJoiningCommunity;
                $isJoiningCommunity->fill([
                    'user_id' => $request->userId,
                    'community_id' => $request->communityId,
                ]);
                $isJoiningCommunity->save();
            ***REMOVED*** catch (\Exception $e) ***REMOVED***
                \Log::info($e);
                DB::rollBack();
                DB::commit();
                return [
                    'isNormalToken' => true,
                    'isJoinCommunity' => false,
                ];
            ***REMOVED***
            try ***REMOVED***
                Bell::where('id', $request->bellId)
                    ->where('user_id', $request->userId)
                    ->delete();
            ***REMOVED*** catch (\Exception $e) ***REMOVED***
                \Log::info($e);
                DB::rollBack();
                DB::commit();
                return [
                    'isNormalToken' => true,
                    'isJoinCommunity' => false,
                ];
            ***REMOVED***
            DB::commit();
            return [
                'isNormalToken' => true,
                'isJoinCommunity' => true,
            ];
        ***REMOVED*** else ***REMOVED***
            return [
                'isNormalToken' => false,
                'isJoinCommunity' => false,
            ];
        ***REMOVED***
    ***REMOVED***
    // コミュニティへの加入申請を拒否
    public function dontJoinCommunity(Request $request) ***REMOVED***
        // $request->token
        // $request->userId
        // $request->bellId
        if ($this->isNormalToken($request->token)) ***REMOVED***
            DB::beginTransaction();
            try ***REMOVED***
                CanIJoinCommunity::where('bell_id', $request->bellId)
                                ->where('user_id', $request->userId)
                                ->delete();
            ***REMOVED*** catch (\Exception $e) ***REMOVED***
                \Log::info($e);
                DB::rollBack();
                DB::commit();
                return [
                    'isNormalToken' => true,
                    'isDontJoinCommunity' => false,
                ];
            ***REMOVED***
            try ***REMOVED***
                Bell::where('id', $request->bellId)
                    ->where('user_id', $request->userId)
                    ->delete();
            ***REMOVED*** catch (\Exception $e) ***REMOVED***
                \Log::info($e);
                DB::rollBack();
                DB::commit();
                return [
                    'isNormalToken' => true,
                    'isDontJoinCommunity' => false,
                ];
            ***REMOVED***
            DB::commit();
            return [
                'isNormalToken' => true,
                'isDontJoinCommunity' => true,
            ];
        ***REMOVED*** else ***REMOVED***
            return [
                'isNormalToken' => false,
                'isDontJoinCommunity' => false,
            ];
        ***REMOVED***
    ***REMOVED***
***REMOVED***
