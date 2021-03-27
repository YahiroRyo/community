<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\CanIJoinCommunity;
use App\Models\Bell;
use App\Models\User;

class BellController extends Controller
***REMOVED***
    public function getBells(Request $request): array ***REMOVED***
        // $request->uid
        // $request->take
        // $request->gotNum
        if (ctype_digit(strval($request->take)) && (ctype_digit(strval($request->gotNum)) || !$request->gotNum)) ***REMOVED***
            $result = [];

            $take   = intval($request->take);
            $gotNum = intval($request->gotNum);
            try ***REMOVED***
                $userId = User::where('uid', $request->uid)
                                ->first()['id'];
                $bells  = Bell::where('user_id', $userId)
                                ->take($take + $gotNum)
                                ->get(['id', 'type', 'user_id']);
                for ($i = $gotNum; $i < count($bells); $i++) ***REMOVED***
                    if ($bells[$i]['type'] === 1) ***REMOVED***
                        $bells[$i]['dataForType'] = CanIJoinCommunity::where('bell_id', $bells[$i]['id'])
                                                        ->with(['community' => function($query) ***REMOVED***
                                                            $query->select(['id', 'name']);
                                                        ***REMOVED***, 'userInfo' => function($query) ***REMOVED***
                                                            $query->select(['name', 'user_name', 'user_id']);
                                                        ***REMOVED***])->first();
                    ***REMOVED***
                    array_push($result, $bells[$i]);
                ***REMOVED***
            ***REMOVED*** catch(\Exception $e) ***REMOVED***
                return [
                    'isGetBells' => false,
                    'bells' => null,
                ];
            ***REMOVED***
            return [
                'isGetBells' => true,
                'bells' => $result,
            ];
        ***REMOVED***
    ***REMOVED***
***REMOVED***
