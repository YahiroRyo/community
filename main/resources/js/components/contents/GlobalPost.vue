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
    import { alert, createAlert, notNormalTokenAlert }  from '../../alert'
    import { addPageEvent, removeAtAllFunc }            from '../../page.js'
    import { reactive, onMounted }                      from 'vue'
    import { post, sendGood }                           from '../../post.js'
    import { getUidAndToken }                           from '../../supportFirebase.js'
    import { displayWindow }                            from '../../window.js'
    import { useStore }                                 from 'vuex'
    import axios                                        from 'axios'
    import Post                                         from '../Post.vue'

    export default {
        components: { Post, },
        setup() {
            const data = reactive({
                store: useStore(),
                post: {
                    cantGetPosts:   false,
                    objects:        [],
                    gotNum:         0,
                    take:           50,
                }
            })
            const getPosts = async() => {
                if (!data.post.cantGetPosts) {
                    let user = {}
                    if (data.store.state.user.isLogin) {
                        user = await getUidAndToken()
                    } else {
                        user.uid = ''
                    }
                    const globalPostsInfos = {
                        params: {
                            gotNum: data.post.gotNum,
                            take:   data.post.take,
                            uid:    user.uid,
                        }
                    }
                    axios.get('/api/get/global-posts', globalPostsInfos)
                    .then((responce) => {
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
                                    obj.community_id,
                                    obj.image_name,
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