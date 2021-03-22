<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Kreait\Firebase\Auth;

use App\Models\User;
use App\Models\Community;

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
    public function getCommunities(Request $request) {
        // $request->take
        // $request->gotNum
        if (ctype_digit(strval($request->take)) && (ctype_digit(strval($request->gotNum)) || !$request->gotNum)) {
            $result = [];
            
            $take = intval($request->take);
            $gotNum = intval($request->gotNum);
            $communities = Community::take($take + $gotNum)->get();
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
}
