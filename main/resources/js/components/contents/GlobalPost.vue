<template>
    <div>
        <template v-for="(post, key) in data.post.objects" :key="key">
            <Post
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
    </div>
</template>

<script>
    import ***REMOVED*** alert, createAlert, notNormalTokenAlert ***REMOVED***  from '../../alert'
    import ***REMOVED*** addPageEvent, removeAtAllFunc ***REMOVED***            from '../../page.js'
    import ***REMOVED*** reactive, onMounted ***REMOVED***                      from 'vue'
    import ***REMOVED*** post, sendGood ***REMOVED***                           from '../../post.js'
    import ***REMOVED*** getUidAndToken ***REMOVED***                           from '../../supportFirebase.js'
    import ***REMOVED*** displayWindow ***REMOVED***                            from '../../window.js'
    import ***REMOVED*** useStore ***REMOVED***                                 from 'vuex'
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
                                    obj.community_id,
                                    obj.image_name,
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