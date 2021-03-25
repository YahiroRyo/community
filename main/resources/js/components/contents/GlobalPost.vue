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
                :sendGoodKey="key"
                :isGood="post.isGood"
            />
        </template>
    </div>
</template>

<script>
    import ***REMOVED*** addPageEvent, removeAtAllFunc ***REMOVED***    from '../../page.js'
    import ***REMOVED*** reactive, onMounted ***REMOVED***              from 'vue'
    import ***REMOVED*** getUidAndToken ***REMOVED***                   from '../../supportFirebase.js'
    import ***REMOVED*** post ***REMOVED***                             from '../../post.js'
    import axios                                from 'axios'
    import Post                                 from '../Post.vue'

    export default ***REMOVED***
        components: ***REMOVED*** Post, ***REMOVED***,
        setup() ***REMOVED***
            const data = reactive(***REMOVED***
                post: ***REMOVED***
                    cantGetPosts:   false,
                    objects:        [],
                    gotNum:         0,
                    take:           50,
                ***REMOVED***
            ***REMOVED***)
            const sendGood = (key) => ***REMOVED***
                data.post.objects[key].isGood = !data.post.objects[key].isGood
                data.post.objects[key].isGood ? data.post.objects[key].goodNum++ : data.post.objects[key].goodNum--
            ***REMOVED***
            const getPosts = async() => ***REMOVED***
                if (!data.post.cantGetPosts) ***REMOVED***
                    const user = await getUidAndToken()
                    const globalPostsInfos = ***REMOVED***
                        params: ***REMOVED***
                            gotNum: data.post.gotNum,
                            take:   data.post.take,
                            uid:    user.uid,
                        ***REMOVED***
                    ***REMOVED***
                    axios.get('/api/get/global-posts', globalPostsInfos)
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
                                    false,
                                    0,
                                    0,
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