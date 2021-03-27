<template>
    <div>
        <transition-group name="show-responce-anim">
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
        </transition-group>
    </div>
</template>

<style>
    /* ---------------show-responce-animのtransition--------------- */
    .show-responce-anim-enter-active,
    .show-responce-anim-leave-active { transition: opacity .5s ease-in-out; }
    .show-responce-anim-enter-active { transition-delay: .5s; }
    .show-responce-anim-enter-active,
    .show-responce-anim-leave-to { opacity: 0; }
    .show-responce-anim-enter-to,
    .show-responce-anim-leave { opacity: 1; }

</style>

<script>
    import { useRouter, useRoute, onBeforeRouteUpdate } from 'vue-router'
    import { alert, createAlert, notNormalTokenAlert }  from '../../alert'
    import { addPageEvent, removeAtAllFunc }            from '../../page.js'
    import { reactive, onMounted }                      from 'vue'
    import { getUidAndToken }                           from '../../supportFirebase.js'
    import { useStore }                                 from 'vuex'
    import { post }                                     from '../../post.js'
    import axios                                        from 'axios'
    import Post                                         from '../Post.vue'

    export default {
        components: { Post },
        setup() {
            const data = reactive({
                router: useRouter(),
                route:  useRoute(),
                store:  useStore(),
                post: {
                    cantGetPosts:   false,
                    objects:        [],
                    gotNum:         0,
                    take:           50,
                },
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
            const getResponcePosts = async(responceFromPostId) => {
                if (!data.post.cantGetPosts) {
                    let user = {}
                    if (data.store.state.user.isLogin) {
                        user = await getUidAndToken()
                    } else {
                        user.uid = ''
                    }
                    const responcePostsInfos = {
                        params: {
                            postId: responceFromPostId,
                            gotNum: data.post.gotNum,
                            take:   data.post.take,
                            uid:    user.uid,
                        }
                    }
                    axios.get('/api/get/responce-posts', responcePostsInfos)
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
                getResponcePosts(to.params.postId)
            })
            onMounted(() => {
                getResponcePosts(data.route.params.postId)
                addPageEvent('pageMostBottom', () => {getResponcePosts()})
            })
            return { data, sendGood, getResponcePosts }
        },
    }
</script>