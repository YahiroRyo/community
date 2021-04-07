<template>
    <div>
        <p style="color: gray;" class="t-c">本当に消去しますか</p>
        <button @click="deletePost" class="btn btn_normal" style="margin-top: 20px !important;">消去</button>
    </div>
</template>

<script>
    import { setCloseFunction, createWindow, closeWindow }  from '../../window'
    import { createAlert, alert, notNormalTokenAlert }      from '../../alert.js'
    import { onMounted, reactive }                          from 'vue'
    import { getUidAndToken }                               from '../../supportFirebase.js'
    import { useRouter }                                    from 'vue-router'
    import { useStore }                                     from 'vuex'
    import axios                                            from 'axios'

    export default {
        setup() {
            const data = reactive({
                store:  useStore(),
                router: useRouter(),
            })
            const deletePost = async() => {
                // 投稿を消去する処理
                const user = await getUidAndToken()
                const deletePostInfos = {
                    postId: data.store.state.post.deletePostId,
                    token:  user.token,
                    uid:    user.uid,
                }
                axios.post('/api/post/delete-post', deletePostInfos)
                .then((responce) => {
                    if (responce.isNormalToken) {
                        if (responce.isDeletePost) {
                            createAlert(new alert('投稿を消去しました。', 0))
                        } else {
                            createAlert(new alert('投稿の消去に失敗しました。', 2))
                        }
                    } else {
                        notNormalTokenAlert()
                    }
                })
                closeWindow()
            }
            onMounted(() => {
                if (data.store.state.windowSize.width <= 414) {
                    createWindow('ツイートの消去確認', data.store.state.windowSize.width - 10, 250)
                } else {
                    createWindow('ツイートの消去確認', 500, 250)
                }
                setCloseFunction(() => {
                    data.store.state.post.deletePostId = 0
                    data.router.go('/')
                })
            })
            return { data, deletePost }
        }        
    }
</script>