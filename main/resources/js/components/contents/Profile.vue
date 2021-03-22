<template>
    <div>
        <link rel="stylesheet" href="/css/components/profile/profile.css">
        <h1 class="profile__name">***REMOVED******REMOVED***data.user.name***REMOVED******REMOVED***</h1>
        <div class="profile__flex">
            <p class="profile__user-name">@***REMOVED******REMOVED***data.user.userName***REMOVED******REMOVED***</p>
            <router-link to="/profile-edit" v-if="data.myUserName === data.user.userName" class="profile__btn">プロフィールを編集する</router-link>
        </div>
        <p class="profile__content">***REMOVED******REMOVED***data.user.intro***REMOVED******REMOVED***</p>
        <div class="profile__posts-wapper">
            <template v-for="(post, key) in data.post.objects" :key="key">
                <Post
                    :name="post.name"
                    :userName="post.userName" 
                    :content="post.content"
                    :goodNum="post.goodNum"
                    :responceNum="post.responceNum"
                    :sendGood="sendGood"
                    :sendGoodKey="key"
                    :isGood="post.isGood"
                />
            </template>
        </div>
    </div>
</template>

<script>
    import ***REMOVED*** reactive, onMounted, onUpdated ***REMOVED*** from 'vue'
    import ***REMOVED*** useStore ***REMOVED*** from 'vuex'
    import ***REMOVED*** useRouter, useRoute ***REMOVED*** from 'vue-router'
    import ***REMOVED*** post ***REMOVED*** from '../../post.js'
    import axios from 'axios'
    import Post from '../Post.vue'

    export default ***REMOVED***
        components: ***REMOVED***
            Post
        ***REMOVED***,
        setup() ***REMOVED***
            const data = reactive(***REMOVED***
                route: useRoute(),
                user: ***REMOVED***
                    name: '',
                    userName: '',
                    intro: '',
                ***REMOVED***,
                myUserName: localStorage.getItem('myUserName'),
                post: ***REMOVED***
                    objects: [],
                ***REMOVED***
            ***REMOVED***)
            const sendGood = (key) => ***REMOVED***
                data.post.objects[key].isGood = !data.post.objects[key].isGood
                data.post.objects[key].isGood ? data.post.objects[key].goodNum++ : data.post.objects[key].goodNum--
            ***REMOVED***
            const getUserData = () => ***REMOVED***
                const userProfileInfos = ***REMOVED***
                    params: ***REMOVED***
                        'userName': data.route.params.userName,
                    ***REMOVED***,
                ***REMOVED***
                axios.get('/api/get/user-profile', userProfileInfos)
                .then((responce) => ***REMOVED***
                    data.user.name = responce.data.name
                    data.user.userName = responce.data.user_name
                    data.user.intro = responce.data.intro
                ***REMOVED***)
                .catch(() => ***REMOVED***
                   createAlert(new alert('ユーザーデータの取得に失敗しました。', 2))
                ***REMOVED***)
            ***REMOVED***
            onUpdated(() => ***REMOVED***
                getUserData()
            ***REMOVED***)
            onMounted(() => ***REMOVED***
                getUserData()

                /* ---------------TODO: サーバーから投稿内容を取得するajax処理を実装--------------- */
                
                // 仮で表示するために値を格納
                for (let i = 0; i < 100; i++)
                    data.post.objects.push(new post('name', 'userName', 'content', true, 1, 5))
            ***REMOVED***)
            return ***REMOVED*** data, sendGood ***REMOVED***
        ***REMOVED***
    ***REMOVED***
</script>