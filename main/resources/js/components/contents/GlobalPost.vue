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
    import { alert, createAlert, notNormalTokenAlert }  from '../../alert'
    import { addPageEvent, removeAtAllFunc }            from '../../page.js'
    import { reactive, onMounted }                      from 'vue'
    import { getUidAndToken }                           from '../../supportFirebase.js'
    import { post }                                     from '../../post.js'
    import axios                                        from 'axios'
    import Post                                         from '../Post.vue'

    export default {
        components: { Post, },
        setup() {
            const data = reactive({
                post: {
                    cantGetPosts:   false,
                    objects:        [],
                    gotNum:         0,
                    take:           50,
                }
            })
            const sendGood = async(key) => {
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
            }
            const getPosts = async() => {
                if (!data.post.cantGetPosts) {
                    const user = await getUidAndToken()
                    const globalPostsInfos = {
                        params: {
                            gotNum: data.post.gotNum,
                            take:   data.post.take,
                            uid:    user.uid,
                        }
                    }
                    axios.get('/api/get/global-posts', globalPostsInfos)
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
                                    0,
                                    obj.id,
                                )
                            )
                        })
                    })
                }
            }
            onMounted(() => {
                getPosts()
                addPageEvent('pageMostBottom', () => {getPosts()})
            })
            return { data, sendGood }
        },
        beforeRouteLeave (to, from, next) {
            removeAtAllFunc()
            next()
        }
    }
</script>