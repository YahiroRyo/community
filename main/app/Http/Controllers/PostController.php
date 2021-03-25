<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Kreait\Firebase\Auth;

use App\Models\Post;
use App\Models\User;
use App\Models\Great;

class PostController extends Controller
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
    // 投稿作成
    public function createPost(Request $request) ***REMOVED***
        // $request->uid
        // $request->token
        // $request->content
        if ($this->isNormalToken($request->token) && strlen($request->content) <= 200) ***REMOVED***
            $userId = User::where('uid', $request->uid)->first()['id'];
            $post = new Post;
            $post->fill([
                'user_id' => $userId,
                'post_id' => null,
                'content' => $request->content,
            ]);
            $post->save();
            return [
                'isNormalToken' => true,
                'isCreatePost' => true,
            ];
        ***REMOVED*** else ***REMOVED***
            return [
                'isNormalToken' => false,
                'isCreatePost' => false,
            ];
        ***REMOVED***
    ***REMOVED***
    public function createResponcePost(Request $request) ***REMOVED***
        // $request->uid
        // $request->token
        // $request->postId
        // $request->content
        if ($this->isNormalToken($request->token) && strlen($request->content) <= 200) ***REMOVED***
            $userId = User::where('uid', $request->uid)->first()['id'];
            $post = new Post;
            $post->fill([
                'user_id' => $userId,
                'post_id' => $request->postId,
                'content' => $request->content,
            ]);
            $post->save();
            return [
                'isNormalToken' => true,
                'isCreateResponcePost' => true,
            ];
        ***REMOVED*** else ***REMOVED***
            return [
                'isNormalToken' => false,
                'isCreateResponcePost' => false,
            ];
        ***REMOVED***
    ***REMOVED***
    public function getGlobalPosts(Request $request): array ***REMOVED***
        // $request->take
        // $request->gotNum
        // $request->uid
        if (ctype_digit(strval($request->take)) && (ctype_digit(strval($request->gotNum)) || !$request->gotNum)) ***REMOVED***
            $result = [];
            
            $take   = intval($request->take);
            $gotNum = intval($request->gotNum);

            $userId = User::where('uid', $request->uid)->first()['id'];
            $posts  = Post::select(['content', 'id', 'user_id', 'post_id'])
                            ->take($take + $gotNum)
                            ->whereNull('post_id')
                            ->with([
                                'userInfo' => function ($query) ***REMOVED***
                                    $query->select(['name', 'user_name', 'user_id']);
                                ***REMOVED***,
                                'isGreatPost' => function ($query) use ($userId) ***REMOVED***
                                    $query->where('user_id', $userId);
                                ***REMOVED***,
                                'greatPostNum',
                                'responceNum' => function ($query) ***REMOVED***
                                    $query->select(['post_id']);
                                ***REMOVED***,
                            ])
                            ->orderBy('id', 'desc')
                            ->get();
            for ($i = $gotNum; $i < count($posts); $i++) ***REMOVED***
                array_push($result, $posts[$i]);
            ***REMOVED***
            return $result;
        ***REMOVED***
    ***REMOVED***
    // いいねをする
    public function greatPost(Request $request) ***REMOVED***
        // $request->uid
        // $request->token
        // $request->postId
        if ($this->isNormalToken($request->token)) ***REMOVED***
            $userId = User::where('uid', $request->uid)->first()['id'];
            $isGreatExists = Great::where('user_id', $userId)
                                    ->where('post_id',$request->postId)
                                    ->exists();
            if ($isGreatExists) ***REMOVED***
                Great::where('user_id', $userId)
                        ->where('post_id',$request->postId)
                        ->delete();
            ***REMOVED*** else ***REMOVED***
                $great = new Great;
                $great->fill([
                    'user_id' => $userId,
                    'post_id' => $request->postId,
                ]);
                $great->save();
            ***REMOVED***
            return [
                'isNormalToken' => true,
                'isGreat' => true,
            ];
        ***REMOVED*** else ***REMOVED***
            return [
                'isNormalToken' => false,
                'isGreat' => false,
            ];
        ***REMOVED***
    ***REMOVED***
***REMOVED***
