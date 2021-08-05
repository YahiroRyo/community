<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\CanIJoinCommunity;
use App\Models\Bell;
use App\Models\User;

class BellController extends Controller
{
    public function getBells(Request $request): array {
        // $request->uid
        // $request->take
        // $request->gotNum
        if (ctype_digit(strval($request->take)) && (ctype_digit(strval($request->gotNum)) || !$request->gotNum)) {
            $result = [];

            $take   = intval($request->take);
            $gotNum = intval($request->gotNum);
            try {
                $userId = User::where('uid', $request->uid)
                                ->first()['id'];
                $bells  = Bell::where('user_id', $userId)
                                ->take($take + $gotNum)
                                ->get(['id', 'type', 'user_id']);
                for ($i = $gotNum; $i < count($bells); $i++) {
                    if ($bells[$i]['type'] === 1) {
                        $bells[$i]['dataForType'] = CanIJoinCommunity::where('bell_id', $bells[$i]['id'])
                                                        ->with(['community' => function($query) {
                                                            $query->select(['id', 'name']);
                                                        }, 'userInfo' => function($query) {
                                                            $query->select(['name', 'user_name', 'user_id']);
                                                        }])->first();
                    }
                    array_push($result, $bells[$i]);
                }
            } catch(\Exception $e) {
                return [
                    'isGetBells' => false,
                    'bells' => null,
                ];
            }
            return [
                'isGetBells' => true,
                'bells' => $result,
            ];
        }
    }
}
