<template>
    <div>
        <p style="color: gray;" class="t-c">本当に消去しますか</p>
        <button @click="deletePost" class="btn btn_normal" style="margin-top: 20px !important;">消去</button>
    </div>
</template>

<script>
    import ***REMOVED*** setCloseFunction, createWindow, closeWindow ***REMOVED***  from '../../window'
    import ***REMOVED*** createAlert, alert, notNormalTokenAlert ***REMOVED***      from '../../alert.js'
    import ***REMOVED*** onMounted, reactive ***REMOVED***                          from 'vue'
    import ***REMOVED*** getUidAndToken ***REMOVED***                               from '../../supportFirebase.js'
    import ***REMOVED*** useRouter ***REMOVED***                                    from 'vue-router'
    import ***REMOVED*** useStore ***REMOVED***                                     from 'vuex'
    import axios                                            from 'axios'

    export default ***REMOVED***
        setup() ***REMOVED***
            const data = reactive(***REMOVED***
                store:  useStore(),
                router: useRouter(),
            ***REMOVED***)
            const deletePost = async() => ***REMOVED***
                // 投稿を消去する処理
                const user = await getUidAndToken()
                const deletePostInfos = ***REMOVED***
                    postId: data.store.state.post.deletePostId,
                    token:  user.token,
                    uid:    user.uid,
                ***REMOVED***
                axios.post('/api/post/delete-post', deletePostInfos)
                .then((responce) => ***REMOVED***
                    if (responce.isNormalToken) ***REMOVED***
                        if (responce.isDeletePost) ***REMOVED***
                            createAlert(new alert('投稿を消去しました。', 0))
                        ***REMOVED*** else ***REMOVED***
                            createAlert(new alert('投稿の消去に失敗しました。', 2))
                        ***REMOVED***
                    ***REMOVED*** else ***REMOVED***
                        notNormalTokenAlert()
                    ***REMOVED***
                ***REMOVED***)
                closeWindow()
            ***REMOVED***
            onMounted(() => ***REMOVED***
                if (data.store.state.windowSize.width <= 414) ***REMOVED***
                    createWindow('ツイートの消去確認', data.store.state.windowSize.width - 10, 250)
                ***REMOVED*** else ***REMOVED***
                    createWindow('ツイートの消去確認', 500, 250)
                ***REMOVED***
                setCloseFunction(() => ***REMOVED***
                    data.store.state.post.deletePostId = 0
                    data.router.go('/')
                ***REMOVED***)
            ***REMOVED***)
            return ***REMOVED*** data, deletePost ***REMOVED***
        ***REMOVED***        
    ***REMOVED***
</script>