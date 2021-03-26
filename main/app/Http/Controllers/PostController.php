<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Kreait\Firebase\Auth;

use App\Models\Post;
use App\Models\User;
use App\Models\Great;

class PostController extends Controller
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
    // 投稿作成
    public function createPost(Request $request) {
        // $request->uid
        // $request->token
        // $request->content
        if ($this->isNormalToken($request->token) && strlen($request->content) <= 200) {
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
        } else {
            return [
                'isNormalToken' => false,
                'isCreatePost' => false,
            ];
        }
    }
    public function createResponcePost(Request $request) {
        // $request->uid
        // $request->token
        // $request->postId
        // $request->content
        if ($this->isNormalToken($request->token) && strlen($request->content) <= 200) {
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
        } else {
            return [
                'isNormalToken' => false,
                'isCreateResponcePost' => false,
            ];
        }
    }
    public function getGlobalPosts(Request $request): array {
        // $request->take
        // $request->gotNum
        // $request->uid
        if (ctype_digit(strval($request->take)) && (ctype_digit(strval($request->gotNum)) || !$request->gotNum)) {
            $result = [];
            
            $take   = intval($request->take);
            $gotNum = intval($request->gotNum);

            $userId = User::where('uid', $request->uid)->first()['id'];
            $posts  = Post::select(['content', 'id', 'user_id', 'post_id'])
                            ->take($take + $gotNum)
                            ->whereNull('post_id')
                            ->with([
                                'userInfo' => function ($query) {
                                    $query->select(['name', 'user_name', 'user_id']);
                                },
                                'isGreatPost' => function ($query) use ($userId) {
                                    $query->where('user_id', $userId);
                                },
                                'greatPostNum',
                                'responceNum' => function ($query) {
                                    $query->select(['post_id']);
                                },
                            ])
                            ->orderBy('id', 'desc')
                            ->get();
            for ($i = $gotNum; $i < count($posts); $i++) {
                array_push($result, $posts[$i]);
            }
            return $result;
        }
    }
    public function getResponcePosts(Request $request) {
        // $request->take
        // $request->postId
        // $request->gotNum
        $result = [];
                    
        $take   = intval($request->take);
        $gotNum = intval($request->gotNum);

        $userId = User::where('uid', $request->uid)->first()['id'];
        if ($gotNum === 0) {
            array_push($result, Post::select(['content', 'id', 'user_id', 'post_id'])
                ->where('id', $request->postId)
                ->with([
                    'userInfo' => function ($query) {
                        $query->select(['name', 'user_name', 'user_id']);
                    },
                    'isGreatPost' => function ($query) use ($userId) {
                        $query->where('user_id', $userId);
                    },
                    'greatPostNum',
                    'responceNum' => function ($query) {
                        $query->select(['post_id']);
                    },
                ])
                ->first());
        }
        $posts = Post::select(['content', 'id', 'user_id', 'post_id'])
                    ->whereNotNull('post_id')
                    ->where('post_id', $request->postId)
                    ->take($take + $gotNum)
                    ->with([
                        'userInfo' => function ($query) {
                            $query->select(['name', 'user_name', 'user_id']);
                        },
                        'isGreatPost' => function ($query) use ($userId) {
                            $query->where('user_id', $userId);
                        },
                        'greatPostNum',
                        'responceNum' => function ($query) {
                            $query->select(['post_id']);
                        },
                    ])
                    ->orderBy('id', 'desc')
                    ->get();
        for ($i = $gotNum; $i < count($posts); $i++) {
            array_push($result, $posts[$i]);
        }
        return $result;
    }
    // いいねをする
    public function greatPost(Request $request) {
        // $request->uid
        // $request->token
        // $request->postId
        if ($this->isNormalToken($request->token)) {
            $userId = User::where('uid', $request->uid)->first()['id'];
            $isGreatExists = Great::where('user_id', $userId)
                                    ->where('post_id',$request->postId)
                                    ->exists();
            if ($isGreatExists) {
                Great::where('user_id', $userId)
                        ->where('post_id',$request->postId)
                        ->delete();
            } else {
                $great = new Great;
                $great->fill([
                    'user_id' => $userId,
                    'post_id' => $request->postId,
                ]);
                $great->save();
            }
            return [
                'isNormalToken' => true,
                'isGreat' => true,
            ];
        } else {
            return [
                'isNormalToken' => false,
                'isGreat' => false,
            ];
        }
    }
}
