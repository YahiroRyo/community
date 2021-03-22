<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Kreait\Firebase\Auth;

use App\Models\User;
use App\Models\Community;

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
    public function getCommunities(Request $request) ***REMOVED***
        // $request->take
        // $request->gotNum
        if (ctype_digit(strval($request->take)) && (ctype_digit(strval($request->gotNum)) || !$request->gotNum)) ***REMOVED***
            $result = [];
            
            $take = intval($request->take);
            $gotNum = intval($request->gotNum);
            $communities = Community::take($take + $gotNum)->get();
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
***REMOVED***
