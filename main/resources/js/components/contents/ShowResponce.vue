<template>
    <div>
        <transition-group name="show-responce-anim">
            <template v-for="(post, key) in data.post.objects" :key="key">
                <Post
                    :responceNum="post.responceNum"
                    :imageName="post.imageName"
                    :communityId="!data.post.objects[0].communityId ? 0 : data.post.objects[0].communityId"
                    :userName="post.userName" 
                    :sendGood="sendGood"
                    :content="post.content"
                    :goodNum="post.goodNum"
                    :sendArg="data.post.objects[key]"
                    :postId="post.postId"
                    :isGood="post.isGood"
                    :name="post.name"
                />
            </template>
        </transition-group>
    </div>
</template>

<style>
    /* ---------------show-responce-animのtransition--------------- */
    .show-responce-anim-enter-active,
    .show-responce-anim-leave-active ***REMOVED*** transition: opacity .5s ease-in-out; ***REMOVED***
    .show-responce-anim-enter-active ***REMOVED*** transition-delay: .5s; ***REMOVED***
    .show-responce-anim-enter-active,
    .show-responce-anim-leave-to ***REMOVED*** opacity: 0; ***REMOVED***
    .show-responce-anim-enter-to,
    .show-responce-anim-leave ***REMOVED*** opacity: 1; ***REMOVED***

</style>

<script>
    import ***REMOVED*** useRouter, useRoute, onBeforeRouteUpdate ***REMOVED*** from 'vue-router'
    import ***REMOVED*** alert, createAlert, notNormalTokenAlert ***REMOVED***  from '../../alert'
    import ***REMOVED*** addPageEvent, removeAtAllFunc ***REMOVED***            from '../../page.js'
    import ***REMOVED*** reactive, onMounted ***REMOVED***                      from 'vue'
    import ***REMOVED*** post, sendGood ***REMOVED***                           from '../../post.js'
    import ***REMOVED*** getUidAndToken ***REMOVED***                           from '../../supportFirebase.js'
    import ***REMOVED*** useStore ***REMOVED***                                 from 'vuex'
    import axios                                        from 'axios'
    import Post                                         from '../Post.vue'

    export default ***REMOVED***
        components: ***REMOVED*** Post ***REMOVED***,
        setup() ***REMOVED***
            const data = reactive(***REMOVED***
                router: useRouter(),
                route:  useRoute(),
                store:  useStore(),
                post: ***REMOVED***
                    cantGetPosts:   false,
                    objects:        [],
                    gotNum:         0,
                    take:           50,
                ***REMOVED***,
            ***REMOVED***)
            const getResponcePosts = async(responceFromPostId) => ***REMOVED***
                if (!data.post.cantGetPosts) ***REMOVED***
                    let user = ***REMOVED******REMOVED***
                    if (data.store.state.user.isLogin) ***REMOVED***
                        user = await getUidAndToken()
                    ***REMOVED*** else ***REMOVED***
                        user.uid = ''
                    ***REMOVED***
                    const responcePostsInfos = ***REMOVED***
                        params: ***REMOVED***
                            postId: responceFromPostId,
                            gotNum: data.post.gotNum,
                            take:   data.post.take,
                            uid:    user.uid,
                        ***REMOVED***
                    ***REMOVED***
                    axios.get('/api/get/responce-posts', responcePostsInfos)
                    .then((responce) => ***REMOVED***
                        if (responce.data.length === 0) ***REMOVED***
                            data.post.cantGetPosts = true
                            if (data.post.gotNum === 0) ***REMOVED***
                                createAlert(new alert('データが見つからなかったため、ホームへ戻ります。', 2))
                                data.router.push('/')
                            ***REMOVED***
                        ***REMOVED***
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
            onBeforeRouteUpdate((to, from) => ***REMOVED***
                data.post.cantGetPosts = false
                data.post.objects = []
                data.post.gotNum = 0
                getResponcePosts(to.params.postId)
            ***REMOVED***)
            onMounted(() => ***REMOVED***
                getResponcePosts(data.route.params.postId)
                addPageEvent('pageMostBottom', () => ***REMOVED***getResponcePosts()***REMOVED***)
            ***REMOVED***)
            return ***REMOVED*** data, sendGood, getResponcePosts ***REMOVED***
        ***REMOVED***,
    ***REMOVED***
</script>