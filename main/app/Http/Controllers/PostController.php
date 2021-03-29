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
            try {
                $userId = User::where('uid', $request->uid)->first()['id'];
                $post = new Post;
                $post->fill([
                    'user_id' => $userId,
                    'content' => $request->content,
                ]);
                $post->save();
            } catch(\Exception $e) {
                return [
                    'isNormalToken' => true,
                    'isCreatePost'  => false,
                ];
            }
            return [
                'isNormalToken' => true,
                'isCreatePost'  => true,
            ];
        } else {
            return [
                'isNormalToken' => false,
                'isCreatePost'  => false,
            ];
        }
    }
    // コミュニティ内で投稿を作成
    public function createCommunityPost(Request $request) {
        // $request->uid
        // $request->token
        // $request->content
        // $request->communityId
        if ($this->isNormalToken($request->token) && strlen($request->content) <= 200) {
            try {
                $userId = User::where('uid', $request->uid)->first()['id'];
                $post = new Post;
                $post->fill([
                    'community_id'  => $request->communityId,
                    'user_id'       => $userId,
                    'content'       => $request->content,
                ]);
                $post->save();
            } catch(\Exception $e) {
                return [
                    'isNormalToken'         => true,
                    'isCreateCommunityPost' => false,
                ];
            }
            return [
                'isNormalToken'         => true,
                'isCreateCommunityPost' => true,
            ];
        } else {
            return [
                'isNormalToken'         => false,
                'isCreateCommunityPost' => false,
            ];
        }
    }
    // 返信を作成
    public function createResponcePost(Request $request) {
        // $request->uid
        // $request->token
        // $request->postId
        // $request->content
        // $request->communityId
        if ($this->isNormalToken($request->token) && strlen($request->content) <= 200) {
            try {
                $userId = User::where('uid', $request->uid)->first()['id'];
                $post = new Post;
                if (isset($request->communityId)) {
                    $post->fill([
                        'user_id'       => $userId,
                        'post_id'       => $request->postId,
                        'content'       => $request->content,
                        'community_id'  => $request->communityId,
                    ]);
                } else {
                    $post->fill([
                        'user_id' => $userId,
                        'post_id' => $request->postId,
                        'content' => $request->content,
                    ]);
                }
                $post->save();
            } catch(\Exception $e) {
                return [
                    'isNormalToken' => true,
                    'isCreateResponcePost' => false,
                ];
            }
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
    // グローバルな投稿を取得
    public function getGlobalPosts(Request $request): array {
        // $request->take
        // $request->gotNum
        // $request->uid
        if (ctype_digit(strval($request->take)) && (ctype_digit(strval($request->gotNum)) || !$request->gotNum)) {
            $result = [];
            
            $take   = intval($request->take);
            $gotNum = intval($request->gotNum);
            $userId;
            try {
                $userId = User::where('uid', $request->uid)->first()['id'];
            } catch(\Exception $e) {
                $userId = 0;
            }
            $posts  = Post::select(['content', 'id', 'user_id', 'post_id'])
                            ->take($take + $gotNum)
                            ->whereNull('post_id')
                            ->whereNull('community_id')
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
    // ユーザーの投稿を取得
    public function getUsersPosts(Request $request): array {
        // $request->uid
        // $request->take
        // $request->gotNum
        // $request->userName
        if (ctype_digit(strval($request->take)) && (ctype_digit(strval($request->gotNum)) || !$request->gotNum)) {
            $result = [];

            $take   = intval($request->take);
            $gotNum = intval($request->gotNum);

            $userId;
            try {
                $userId = User::where('uid', $request->uid)->first()['id'];
            } catch(\Exception $e) {
                $userId = 0;
            }
            $userInfos = UserInfo::where('user_name', $request->userName)
                    ->first(['name', 'user_name', 'user_id']);
            $posts = Post::select(['content', 'id', 'user_id', 'post_id'])
                        ->where('user_id', $userInfos['user_id'])
                        ->whereNull('post_id')
                        ->whereNull('community_id')
                        ->take($take + $gotNum)
                        ->with([
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
                $posts[$i]['user_info'] = $userInfos;
                array_push($result, $posts[$i]);
            }
            return $result;
        }
    }
    // コミュニティの投稿を取得
    public function getCommunityPosts(Request $request) {
        // $request->communityId
        // $request->gotNum
        // $request->take
        // $request->uid
        if (ctype_digit(strval($request->take)) && (ctype_digit(strval($request->gotNum)) || !$request->gotNum)) {
            $result = [];
            
            $take   = intval($request->take);
            $gotNum = intval($request->gotNum);
            $userId;
            try {
                $userId = User::where('uid', $request->uid)->first()['id'];
            } catch(\Exception $e) {
                $userId = 0;
            }
            $posts  = Post::select(['content', 'id', 'user_id', 'post_id'])
                            ->take($take + $gotNum)
                            ->where('community_id', $request->communityId)
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
    // 投稿に対しての返信を取得
    public function getResponcePosts(Request $request): array {
        // $request->uid
        // $request->take
        // $request->postId
        // $request->gotNum
        if (ctype_digit(strval($request->take)) && (ctype_digit(strval($request->gotNum)) || !$request->gotNum)) {
            $result = [];
                        
            $take   = intval($request->take);
            $gotNum = intval($request->gotNum);

            $userId;
            try {
                $userId = User::where('uid', $request->uid)->first()['id'];
            } catch(\Exception $e) {
                $userId = 0;
            }
            if ($gotNum === 0) {
                $parentPost = Post::select(['content', 'id', 'user_id', 'post_id', 'community_id'])
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
                            ->first();
                if (CommunityController::canJoinCommunity($parentPost['community_id'], $userId) || !isset($parentPost['community_id'])) {
                    array_push($result, $parentPost);
                } else {
                    return $result;
                }
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
    }
    // いいねをする
    public function greatPost(Request $request) {
        // $request->uid
        // $request->token
        // $request->postId
        if ($this->isNormalToken($request->token)) {
            try {
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
            } catch(\Exception $e) {
                return [
                    'isNormalToken' => true,
                    'isGreat' => false,
                ];
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
