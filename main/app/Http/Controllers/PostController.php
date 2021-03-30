<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Kreait\Firebase\Auth;

use App\Models\Post;
use App\Models\User;
use App\Models\Great;
use App\Models\UserInfo;
use App\Http\Controllers\CommunityController;

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

        if ($this->isNormalToken($request->token)) ***REMOVED***
            if (strlen($request->content) <= 280) ***REMOVED***
                try ***REMOVED***
                    $userId = User::where('uid', $request->uid)->first()['id'];
                    $post = new Post;
                    $post->fill([
                        'user_id' => $userId,
                        'content' => $request->content,
                    ]);
                    $post->save();
                ***REMOVED*** catch(\Exception $e) ***REMOVED***
                    return [
                        'isNormalToken' => true,
                        'isCreatePost'  => false,
                    ];
                ***REMOVED***
                return [
                    'isNormalToken' => true,
                    'isCreatePost'  => true,
                ];
            ***REMOVED*** else ***REMOVED***
                return [
                    'isNormalToken' => true,
                    'isCreatePost'  => false,
                ];
            ***REMOVED***
        ***REMOVED*** else ***REMOVED***
            return [
                'isNormalToken' => false,
                'isCreatePost'  => false,
            ];
        ***REMOVED***
    ***REMOVED***
    // コミュニティ内で投稿を作成
    public function createCommunityPost(Request $request) ***REMOVED***
        // $request->uid
        // $request->token
        // $request->content
        // $request->communityId
        if ($this->isNormalToken($request->token)) ***REMOVED***
            if (strlen($request->content) <= 280) ***REMOVED***
                try ***REMOVED***
                    $userId = User::where('uid', $request->uid)->first()['id'];
                    $post = new Post;
                    $post->fill([
                        'community_id'  => $request->communityId,
                        'user_id'       => $userId,
                        'content'       => $request->content,
                    ]);
                    $post->save();
                ***REMOVED*** catch(\Exception $e) ***REMOVED***
                    return [
                        'isNormalToken'         => true,
                        'isCreateCommunityPost' => false,
                    ];
                ***REMOVED***
                return [
                    'isNormalToken'         => true,
                    'isCreateCommunityPost' => true,
                ];
            ***REMOVED***
        ***REMOVED*** else ***REMOVED***
            return [
                'isNormalToken'         => false,
                'isCreateCommunityPost' => false,
            ];
        ***REMOVED***
    ***REMOVED***
    // 返信を作成
    public function createResponcePost(Request $request) ***REMOVED***
        // $request->uid
        // $request->token
        // $request->postId
        // $request->content
        // $request->communityId
        if ($this->isNormalToken($request->token)) ***REMOVED***
            if (strlen($request->content) <= 280) ***REMOVED***
                try ***REMOVED***
                    $userId = User::where('uid', $request->uid)->first()['id'];
                    $post = new Post;
                    if (isset($request->communityId)) ***REMOVED***
                        $post->fill([
                            'user_id'       => $userId,
                            'post_id'       => $request->postId,
                            'content'       => $request->content,
                            'community_id'  => $request->communityId,
                        ]);
                    ***REMOVED*** else ***REMOVED***
                        $post->fill([
                            'user_id' => $userId,
                            'post_id' => $request->postId,
                            'content' => $request->content,
                        ]);
                    ***REMOVED***
                    $post->save();
                ***REMOVED*** catch(\Exception $e) ***REMOVED***
                    return [
                        'isNormalToken' => true,
                        'isCreateResponcePost' => false,
                    ];
                ***REMOVED***
                return [
                    'isNormalToken' => true,
                    'isCreateResponcePost' => true,
                ];
            ***REMOVED*** else ***REMOVED***
                return [
                    'isNormalToken' => true,
                    'isCreateResponcePost' => false,
                ];
            ***REMOVED***
        ***REMOVED*** else ***REMOVED***
            return [
                'isNormalToken' => false,
                'isCreateResponcePost' => false,
            ];
        ***REMOVED***
    ***REMOVED***
    // グローバルな投稿を取得
    public function getGlobalPosts(Request $request): array ***REMOVED***
        // $request->take
        // $request->gotNum
        // $request->uid
        if (ctype_digit(strval($request->take)) && (ctype_digit(strval($request->gotNum)) || !$request->gotNum)) ***REMOVED***
            $result = [];
            
            $take   = intval($request->take);
            $gotNum = intval($request->gotNum);
            $userId;
            try ***REMOVED***
                $userId = User::where('uid', $request->uid)->first()['id'];
            ***REMOVED*** catch(\Exception $e) ***REMOVED***
                $userId = 0;
            ***REMOVED***
            $posts  = Post::select(['content', 'id', 'user_id', 'post_id'])
                            ->take($take + $gotNum)
                            ->whereNull('post_id')
                            ->whereNull('community_id')
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
    // ユーザーの投稿を取得
    public function getUsersPosts(Request $request): array ***REMOVED***
        // $request->uid
        // $request->take
        // $request->gotNum
        // $request->userName
        if (ctype_digit(strval($request->take)) && (ctype_digit(strval($request->gotNum)) || !$request->gotNum)) ***REMOVED***
            $result = [];

            $take   = intval($request->take);
            $gotNum = intval($request->gotNum);

            $userId;
            try ***REMOVED***
                $userId = User::where('uid', $request->uid)->first()['id'];
            ***REMOVED*** catch(\Exception $e) ***REMOVED***
                $userId = 0;
            ***REMOVED***
            $userInfos = UserInfo::where('user_name', $request->userName)
                    ->first(['name', 'user_name', 'user_id']);
            $posts = Post::select(['content', 'id', 'user_id', 'post_id'])
                        ->where('user_id', $userInfos['user_id'])
                        ->whereNull('post_id')
                        ->whereNull('community_id')
                        ->take($take + $gotNum)
                        ->with([
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
                $posts[$i]['user_info'] = $userInfos;
                array_push($result, $posts[$i]);
            ***REMOVED***
            return $result;
        ***REMOVED***
    ***REMOVED***
    // コミュニティの投稿を取得
    public function getCommunityPosts(Request $request) ***REMOVED***
        // $request->communityId
        // $request->gotNum
        // $request->take
        // $request->uid
        if (ctype_digit(strval($request->take)) && (ctype_digit(strval($request->gotNum)) || !$request->gotNum)) ***REMOVED***
            $result = [];
            
            $take   = intval($request->take);
            $gotNum = intval($request->gotNum);
            $userId;
            try ***REMOVED***
                $userId = User::where('uid', $request->uid)->first()['id'];
            ***REMOVED*** catch(\Exception $e) ***REMOVED***
                $userId = 0;
            ***REMOVED***
            $posts  = Post::select(['content', 'id', 'user_id', 'post_id'])
                            ->take($take + $gotNum)
                            ->where('community_id', $request->communityId)
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
    // 投稿に対しての返信を取得
    public function getResponcePosts(Request $request): array ***REMOVED***
        // $request->uid
        // $request->take
        // $request->postId
        // $request->gotNum
        if (ctype_digit(strval($request->take)) && (ctype_digit(strval($request->gotNum)) || !$request->gotNum)) ***REMOVED***
            $result = [];
                        
            $take   = intval($request->take);
            $gotNum = intval($request->gotNum);

            $userId;
            try ***REMOVED***
                $userId = User::where('uid', $request->uid)->first()['id'];
            ***REMOVED*** catch(\Exception $e) ***REMOVED***
                $userId = 0;
            ***REMOVED***
            if ($gotNum === 0) ***REMOVED***
                $parentPost = Post::select(['content', 'id', 'user_id', 'post_id', 'community_id'])
                            ->where('id', $request->postId)
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
                            ->first();
                if (CommunityController::canJoinCommunity($parentPost['community_id'], $userId) || !isset($parentPost['community_id'])) ***REMOVED***
                    array_push($result, $parentPost);
                ***REMOVED*** else ***REMOVED***
                    return $result;
                ***REMOVED***
            ***REMOVED***       
            $posts = Post::select(['content', 'id', 'user_id', 'post_id'])
                        ->whereNotNull('post_id')
                        ->where('post_id', $request->postId)
                        ->take($take + $gotNum)
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
            try ***REMOVED***
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
            ***REMOVED*** catch(\Exception $e) ***REMOVED***
                return [
                    'isNormalToken' => true,
                    'isGreat' => false,
                ];
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
