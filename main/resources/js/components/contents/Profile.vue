<template>
    <div>
        <link rel="stylesheet" href="/css/components/profile/profile.css">
        <h1 class="profile__name">***REMOVED******REMOVED***data.user.name***REMOVED******REMOVED***</h1>
        <div class="profile__flex">
            <p class="profile__user-name">@***REMOVED******REMOVED***data.user.userName***REMOVED******REMOVED***</p>
            <router-link to="/profile-edit" v-if="$store.state.user.userName === data.user.userName" class="profile__btn">プロフィールを編集する</router-link>
        </div>
        <p class="profile__content">***REMOVED******REMOVED***data.user.intro***REMOVED******REMOVED***</p>
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
    import ***REMOVED*** useRouter, useRoute, onBeforeRouteUpdate ***REMOVED*** from 'vue-router'
    import ***REMOVED*** reactive, onMounted ***REMOVED***                      from 'vue'
    import ***REMOVED*** getUidAndToken ***REMOVED***                           from '../../supportFirebase.js'
    import ***REMOVED*** displayWindow ***REMOVED***                            from '../../window.js'
    import ***REMOVED*** ruseStore ***REMOVED***                                from 'vuex'
    import ***REMOVED*** useStore ***REMOVED***                                 from 'vuex'
    import ***REMOVED*** post ***REMOVED***                                     from '../../post.js'
    import axios                                        from 'axios'
    import Post                                         from '../Post.vue'

    export default ***REMOVED***
        components: ***REMOVED***
            Post
        ***REMOVED***,
        setup() ***REMOVED***
            const data = reactive(***REMOVED***
                store: useStore(),
                route: useRoute(),
                user: ***REMOVED***
                    name:       '',
                    userName:   '',
                    intro:      '',
                ***REMOVED***,
                post: ***REMOVED***
                    cantGetPosts:   false,
                    objects:        [],
                    gotNum:         0,
                    take:           50,
                ***REMOVED***
            ***REMOVED***)
            const sendGood = async(key) => ***REMOVED*** 
                if (data.store.state.user.isLogin) ***REMOVED***
                    const user = await getUidAndToken()
                    const greatPostInfos = ***REMOVED***
                        postId: data.post.objects[key].postId,
                        token:  user.token,
                        uid:    user.uid,
                    ***REMOVED***
                    axios.post('/api/post/great-post', greatPostInfos)
                    .then((responce) => ***REMOVED***
                        if (responce.data.isNormalToken) ***REMOVED***
                            if (responce.data.isGreat) ***REMOVED***
                                data.post.objects[key].isGood = !data.post.objects[key].isGood
                                data.post.objects[key].isGood ? data.post.objects[key].goodNum++ : data.post.objects[key].goodNum--
                            ***REMOVED*** else ***REMOVED***
                                createAlert(new alert('いいねすることができませんでした。', 2))
                            ***REMOVED***
                        ***REMOVED*** else ***REMOVED***
                            notNormalTokenAlert()
                        ***REMOVED***
                    ***REMOVED***)
                ***REMOVED*** else ***REMOVED***
                    displayWindow(5)
                ***REMOVED***
            ***REMOVED***
            const getUserData = () => ***REMOVED***
                const userProfileInfos = ***REMOVED***
                    params: ***REMOVED***
                        'userName': data.route.params.userName,
                    ***REMOVED***,
                ***REMOVED***
                axios.get('/api/get/user-profile', userProfileInfos)
                .then((responce) => ***REMOVED***
                    data.user.name      = responce.data.name
                    data.user.userName  = responce.data.user_name
                    data.user.intro     = responce.data.intro
                ***REMOVED***)
                .catch(() => ***REMOVED***
                   createAlert(new alert('ユーザーデータの取得に失敗しました。', 2))
                ***REMOVED***)
            ***REMOVED***
            const getUsersPosts = async(userName) => ***REMOVED***
                if (!data.post.cantGetPosts) ***REMOVED***
                    let user = ***REMOVED******REMOVED***
                    if (data.store.state.user.isLogin) ***REMOVED***
                        user = await getUidAndToken()
                    ***REMOVED*** else ***REMOVED***
                        user.uid = ''
                    ***REMOVED***
                    const usersPostsInfos = ***REMOVED***
                        params: ***REMOVED***
                            userName:   userName,
                            gotNum:     data.post.gotNum,
                            take:       data.post.take,
                            uid:        user.uid,
                        ***REMOVED***
                    ***REMOVED***
                    axios.get('/api/get/users-posts', usersPostsInfos)
                    .then((responce) => ***REMOVED***
                        console.log(responce)
                        data.post.gotNum += data.post.take
                        if (data.post.take > responce.data.length)
                            data.post.cantGetPosts = true
                        responce.data.forEach((obj) => ***REMOVED***
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
                        ***REMOVED***)
                    ***REMOVED***)
                ***REMOVED***
            ***REMOVED***
            onBeforeRouteUpdate((to, from) => ***REMOVED***
                data.post.cantGetPosts = false
                data.post.objects = []
                data.post.gotNum = 0
                getUserData()
                getUsersPosts(to.params.userName)
            ***REMOVED***)
            onMounted(() => ***REMOVED***
                getUserData()
                getUsersPosts(data.route.params.userName)
            ***REMOVED***)
            return ***REMOVED*** data, sendGood ***REMOVED***
        ***REMOVED***
    ***REMOVED***
</script>