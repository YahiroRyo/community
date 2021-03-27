<template>
    <div>
        <link rel="stylesheet" href="/css/components/profile/profile.css">
        <h1 class="profile__name">{{data.user.name}}</h1>
        <div class="profile__flex">
            <p class="profile__user-name">@{{data.user.userName}}</p>
            <router-link to="/profile-edit" v-if="$store.state.user.userName === data.user.userName" class="profile__btn">プロフィールを編集する</router-link>
        </div>
        <p class="profile__content">{{data.user.intro}}</p>
        <div class="profile__posts-wapper">
            <template v-if="data.post.objects.length > 0" v-for="(post, key) in data.post.objects" :key="key">
                <Post
                    :name="post.name"
                    :userName="post.userName" 
                    :content="post.content"
                    :goodNum="post.goodNum"
                    :responceNum="post.responceNum"
                    :sendGood="sendGood"
                    :sendKey="key"
                    :isGood="post.isGood"
                />
            </template>
            <h2 v-else>このユーザーのツイートは存在しません。</h2>
        </div>
    </div>
</template>

<script>
    import { useRouter, useRoute, onBeforeRouteUpdate } from 'vue-router'
    import { reactive, onMounted }                      from 'vue'
    import { getUidAndToken }                           from '../../supportFirebase.js'
    import { displayWindow }                            from '../../window.js'
    import { ruseStore }                                from 'vuex'
    import { useStore }                                 from 'vuex'
    import { post }                                     from '../../post.js'
    import axios                                        from 'axios'
    import Post                                         from '../Post.vue'

    export default {
        components: {
            Post
        },
        setup() {
            const data = reactive({
                store: useStore(),
                route: useRoute(),
                user: {
                    name:       '',
                    userName:   '',
                    intro:      '',
                },
                post: {
                    cantGetPosts:   false,
                    objects:        [],
                    gotNum:         0,
                    take:           50,
                }
            })
            const sendGood = async(key) => { 
                if (data.store.state.user.isLogin) {
                    const user = await getUidAndToken()
                    const greatPostInfos = {
                        postId: data.post.objects[key].postId,
                        token:  user.token,
                        uid:    user.uid,
                    }
                    axios.post('/api/post/great-post', greatPostInfos)
                    .then((responce) => {
                        if (responce.data.isNormalToken) {
                            if (responce.data.isGreat) {
                                data.post.objects[key].isGood = !data.post.objects[key].isGood
                                data.post.objects[key].isGood ? data.post.objects[key].goodNum++ : data.post.objects[key].goodNum--
                            } else {
                                createAlert(new alert('いいねすることができませんでした。', 2))
                            }
                        } else {
                            notNormalTokenAlert()
                        }
                    })
                } else {
                    displayWindow(5)
                }
            }
            const getUserData = () => {
                const userProfileInfos = {
                    params: {
                        'userName': data.route.params.userName,
                    },
                }
                axios.get('/api/get/user-profile', userProfileInfos)
                .then((responce) => {
                    data.user.name      = responce.data.name
                    data.user.userName  = responce.data.user_name
                    data.user.intro     = responce.data.intro
                })
                .catch(() => {
                   createAlert(new alert('ユーザーデータの取得に失敗しました。', 2))
                })
            }
            const getUsersPosts = async(userName) => {
                if (!data.post.cantGetPosts) {
                    let user = {}
                    if (data.store.state.user.isLogin) {
                        user = await getUidAndToken()
                    } else {
                        user.uid = ''
                    }
                    const usersPostsInfos = {
                        params: {
                            userName:   userName,
                            gotNum:     data.post.gotNum,
                            take:       data.post.take,
                            uid:        user.uid,
                        }
                    }
                    axios.get('/api/get/users-posts', usersPostsInfos)
                    .then((responce) => {
                        console.log(responce)
                        data.post.gotNum += data.post.take
                        if (data.post.take > responce.data.length)
                            data.post.cantGetPosts = true
                        responce.data.forEach((obj) => {
                            data.post.objects.push(
                                new post(
                                    obj.user_info.name,
                                    obj.user_info.user_name,
                                    obj.content,
                                    obj.is_great_post.length > 0,
                                    obj.great_post_num.length,
                                    obj.responce_num.length,
                                    obj.id,
                                )
                            )
                        })
                    })
                }
            }
            onBeforeRouteUpdate((to, from) => {
                data.post.cantGetPosts = false
                data.post.objects = []
                data.post.gotNum = 0
                getUserData()
                getUsersPosts(to.params.userName)
            })
            onMounted(() => {
                getUserData()
                getUsersPosts(data.route.params.userName)
            })
            return { data, sendGood }
        }
    }
</script>