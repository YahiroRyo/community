<template>
    <div>
        <template v-for="(post, key) in data.post.objects" :key="key">
            <Post
                :name="post.name"
                :userName="post.userName" 
                :content="post.content"
                :goodNum="post.goodNum"
                :responceNum="post.responceNum"
                :sendGood="sendGood"
                :sendKey="key"
                :postId="post.postId"
                :isGood="post.isGood"
            />
        </template>
    </div>
</template>

<script>
    import ***REMOVED*** alert, createAlert, notNormalTokenAlert ***REMOVED***  from '../../alert'
    import ***REMOVED*** addPageEvent, removeAtAllFunc ***REMOVED***            from '../../page.js'
    import ***REMOVED*** reactive, onMounted ***REMOVED***                      from 'vue'
    import ***REMOVED*** getUidAndToken ***REMOVED***                           from '../../supportFirebase.js'
    import ***REMOVED*** displayWindow ***REMOVED***                            from '../../window.js'
    import ***REMOVED*** useStore ***REMOVED***                                 from 'vuex'
    import ***REMOVED*** post ***REMOVED***                                     from '../../post.js'
    import axios                                        from 'axios'
    import Post                                         from '../Post.vue'

    export default ***REMOVED***
        components: ***REMOVED*** Post, ***REMOVED***,
        setup() ***REMOVED***
            const data = reactive(***REMOVED***
                store: useStore(),
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
            const getPosts = async() => ***REMOVED***
                if (!data.post.cantGetPosts) ***REMOVED***
                    let user = ***REMOVED******REMOVED***
                    if (data.store.state.user.isLogin) ***REMOVED***
                        user = await getUidAndToken()
                    ***REMOVED*** else ***REMOVED***
                        user.uid = ''
                    ***REMOVED***
                    const globalPostsInfos = ***REMOVED***
                        params: ***REMOVED***
                            gotNum: data.post.gotNum,
                            take:   data.post.take,
                            uid:    user.uid,
                        ***REMOVED***
                    ***REMOVED***
                    axios.get('/api/get/global-posts', globalPostsInfos)
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
                                )
                            )
                        ***REMOVED***)
                    ***REMOVED***)
                ***REMOVED***
            ***REMOVED***
            onMounted(() => ***REMOVED***
                getPosts()
                addPageEvent('pageMostBottom', () => ***REMOVED***getPosts()***REMOVED***)
            ***REMOVED***)
            return ***REMOVED*** data, sendGood ***REMOVED***
        ***REMOVED***,
        beforeRouteLeave (to, from, next) ***REMOVED***
            removeAtAllFunc()
            next()
        ***REMOVED***
    ***REMOVED***
</script>