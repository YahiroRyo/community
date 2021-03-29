<template>
    <div>
        <transition-group name="show-responce-anim">
            <template v-for="(post, key) in data.post.objects" :key="key">
                <Post
                    :responceNum="post.responceNum"
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
    import { post, sendGood }                           from '../../post.js'
    import { getUidAndToken }                           from '../../supportFirebase.js'
    import { useStore }                                 from 'vuex'
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
                        if (responce.data.length === 0) {
                            data.post.cantGetPosts = true
                            if (data.post.gotNum === 0) {
                                createAlert(new alert('データが見つからなかったため、ホームへ戻ります。', 2))
                                data.router.push('/')
                            }
                        }
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
                                    obj.community_id
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