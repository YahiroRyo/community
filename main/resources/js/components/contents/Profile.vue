<template>
    <div>
        <link rel="stylesheet" href="/css/components/profile/profile.css">
        <div class="profile__flex">
            <div class="profile__icon-wapper">
                <img class="profile__icon-img" :src="`/storage/profileIcons/$***REMOVED***data.user.imageName***REMOVED***`">
                <div v-if="$store.state.user.userName === data.user.userName" @click="selectProfileImage" class="profile__icon-change"><p class="profile__icon-change__text">画像を変更</p></div>
                <input v-if="$store.state.user.userName === data.user.userName" ref="inputFileElement" @change="changeProfileImage" style="display: none;" type="file" accept="image/,.jpg,.jpeg,.png" />
            </div>
            <div class="width-full">
                <h1 class="profile__name">***REMOVED******REMOVED***data.user.name***REMOVED******REMOVED***</h1>
                <div class="profile__other__flex">
                    <p class="profile__user-name">@***REMOVED******REMOVED***data.user.userName***REMOVED******REMOVED***</p>
                    <router-link to="/profile-edit" v-if="$store.state.user.userName === data.user.userName" class="profile__btn">プロフィールを編集する</router-link>
                </div>
            </div>
        </div>
        <p class="profile__content">***REMOVED******REMOVED***data.user.intro***REMOVED******REMOVED***</p>
        <div class="profile__posts-wapper">
            <template v-if="data.post.objects.length > 0" v-for="(post, key) in data.post.objects" :key="key">
                <Post
                    :postImageNames="post.imageNames"
                    :sendArg="data.post.objects[key]"
                    :responceNum="post.responceNum"
                    :imageName="post.imageName"
                    :userName="post.userName" 
                    :content="post.content"
                    :goodNum="post.goodNum"
                    :postId="post.postId"
                    :isGood="post.isGood"
                    :sendGood="sendGood"
                    :name="post.name"
                />
            </template>
            <h2 v-else-if="!data.user.isFound">このユーザーは存在しません。</h2>
            <h2 v-else>このユーザーのツイートは存在しません。</h2>
        </div>
    </div>
</template>

<script>
    import ***REMOVED*** useRouter, useRoute, onBeforeRouteUpdate, ***REMOVED***    from 'vue-router'
    import ***REMOVED*** reactive, onMounted, onBeforeMount, ref ***REMOVED***      from 'vue'
    import ***REMOVED*** alert, createAlert, notNormalTokenAlert ***REMOVED***      from '../../alert'
    import ***REMOVED*** post, sendGood ***REMOVED***                               from '../../post.js'
    import ***REMOVED*** getUidAndToken ***REMOVED***                               from '../../supportFirebase.js'
    import ***REMOVED*** displayWindow ***REMOVED***                                from '../../window.js'
    import ***REMOVED*** ruseStore ***REMOVED***                                    from 'vuex'
    import ***REMOVED*** useStore ***REMOVED***                                     from 'vuex'
    import axios                                            from 'axios'
    import Post                                             from '../Post.vue'

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
                    imageName:  '',
                    isFound:    false,
                ***REMOVED***,
                post: ***REMOVED***
                    cantGetPosts:   false,
                    objects:        [],
                    gotNum:         0,
                    take:           50,
                ***REMOVED***
            ***REMOVED***)
            const inputFileElement = ref(null)
            const getUserData = async() => ***REMOVED***
                const userProfileInfos = ***REMOVED***
                    params: ***REMOVED***
                        'userName': data.route.params.userName,
                    ***REMOVED***,
                ***REMOVED***
                await axios.get('/api/get/user-profile', userProfileInfos)
                .then((responce) => ***REMOVED***
                    if (responce.data !== null) ***REMOVED***
                        data.user.name      = responce.data.name
                        data.user.userName  = responce.data.user_name
                        data.user.intro     = responce.data.intro
                        data.user.imageName = responce.data.image_name
                        data.user.isFound   = true
                    ***REMOVED***
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
                                    obj.community_id,
                                    obj.user_info.image_name,
                                    obj.post_image_name,
                                )
                            )
                        ***REMOVED***)
                    ***REMOVED***)
                ***REMOVED***
            ***REMOVED***
            const selectProfileImage = () => ***REMOVED***
                if (inputFileElement.value.click !== null) ***REMOVED*** inputFileElement.value.click() ***REMOVED***
            ***REMOVED***
            const changeProfileImage = async() => ***REMOVED***
                if (inputFileElement.value.files.length > 0 && (
                        inputFileElement.value.files[0].type.match("image.png") ||
                        inputFileElement.value.files[0].type.match("image.jpg") ||
                        inputFileElement.value.files[0].type.match("image.jpeg")
                    )) ***REMOVED***
                    const user = await getUidAndToken()
                    const refreshUserPostImageInfos = new FormData()
                    refreshUserPostImageInfos.append('file', inputFileElement.value.files[0])
                    refreshUserPostImageInfos.append('uid', user.uid)
                    refreshUserPostImageInfos.append('token', user.token)
                    axios.post('/api/post/refresh-user-profile-image', refreshUserPostImageInfos)
                    .then((responce) => ***REMOVED***
                        if (responce.data.isNormalToken) ***REMOVED***
                            if (responce.data.isRefreshImage) ***REMOVED***
                                createAlert(new alert('画像を設定しました。', 0))
                                data.post.cantGetPosts = false
                                data.post.objects = []
                                data.post.gotNum = 0
                                getUserData()
                                if (data.user.isFound)
                                    getUsersPosts(data.route.params.userName)
                            ***REMOVED*** else ***REMOVED***
                                createAlert(new alert('画像の更新に失敗しました。', 2))
                            ***REMOVED***
                        ***REMOVED*** else ***REMOVED***
                            notNormalTokenAlert()
                        ***REMOVED***
                    ***REMOVED***)
                ***REMOVED***
            ***REMOVED***
            onBeforeRouteUpdate((to, from) => ***REMOVED***
                data.post.cantGetPosts = false
                data.post.objects = []
                data.post.gotNum = 0
                getUserData()
                if (data.user.isFound)
                    getUsersPosts(to.params.userName)
            ***REMOVED***)
            
            onMounted(async() => ***REMOVED***
                await getUserData()
                if (data.user.isFound)
                    getUsersPosts(data.route.params.userName)
            ***REMOVED***)
            return ***REMOVED*** data, sendGood, changeProfileImage, inputFileElement, selectProfileImage ***REMOVED***
        ***REMOVED***
    ***REMOVED***
</script>