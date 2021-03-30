<template>
    <div>
        <link rel="stylesheet" href="/css/components/createPost/createPost.css">
        <Form class="form" :useTextArea="true" v-model:inputContent="data.post.content" label="投稿内容を入力" uniqueClassKey="1" />
        <!-- 文字数カウント -->
        <p :class="***REMOVED***'form-label-create-post': true, 'form-label_danger': bytes(data.post.content) >= 280 ? true : false, ***REMOVED***">***REMOVED******REMOVED***bytes(data.post.content) ***REMOVED******REMOVED*** | 280</p>
        <!-- 画像プレビュー -->
        <div class="create-post-display-img-wapper" v-show="data.post.images.length > 0">
            <transition-group name="create-post-input-img-anim">
                <div class="create-post-input-img" v-for="(image, key) in data.post.images" :key="key" :style="`background-image: url($***REMOVED***image***REMOVED***)`">
                    <button @click="deleteMedia(key)" class="create-post-input-img__btn"><img class="create-post-input-img__btn__img" src="/images/materials/close.svg"></button>
                </div>
            </transition-group>
        </div>
        <button @click="selectMedia" class="create-post-btn"><img src="/images/materials/media.svg" class="create-post-btn__media-img"></button>
        <input ref="inputFileElement" @change="displayMedia" style="display: none;" type="file" accept="image/*" />
        <button @click="createPost" class="form__btn m-l-1">投稿する</button>
    </div>
</template>

<script>
    /* ---------------createPost(投稿)をする制約-------------- */
    // ・文字数は200字まで
    // ・画像を投稿できるのは4枚まで

    import ***REMOVED*** reactive, onMounted, ref, onBeforeMount ***REMOVED***  from 'vue'
    import ***REMOVED*** createAlert, alert, notNormalTokenAlert ***REMOVED***  from '../../alert.js'
    import ***REMOVED*** antiLoginUser, antiNotLoginUser ***REMOVED***          from '../../router.js'
    import ***REMOVED*** createWindow, closeWindow ***REMOVED***                from '../../window.js'
    import ***REMOVED*** getUidAndToken ***REMOVED***                           from '../../supportFirebase.js'
    import ***REMOVED*** useStore ***REMOVED***                                 from 'vuex'
    import axios                                        from 'axios'
    /* ---------------コンポーネントをインポート--------------- */
    import Form                                         from '../Form.vue'

    export default ***REMOVED***
        components: ***REMOVED***
            Form,
        ***REMOVED***,
        setup() ***REMOVED***
            // ボタンを用いてファイルを選択させたいため、ファイル選択にrefを用いた。
            const inputFileElement = ref(null)
            const data = reactive(***REMOVED***
                store: useStore(),
                post: ***REMOVED***
                    content:    '',
                    images:     [],
                ***REMOVED***,
            ***REMOVED***)
            const createPost = async() => ***REMOVED***
                const user = await getUidAndToken()
                const createPostInfos = ***REMOVED***
                    content:    data.post.content,
                    token:      user.token,
                    uid:        user.uid,
                ***REMOVED***
                axios.post('/api/post/create-post', createPostInfos)
                .then((responce) => ***REMOVED***
                    if (responce.data.isNormalToken) ***REMOVED***
                        if (responce.data.isCreatePost) ***REMOVED***
                            createAlert(new alert('投稿をしました。', 0))
                        ***REMOVED*** else ***REMOVED***
                            createAlert(new alert('投稿をすることができませんでした。', 2))
                        ***REMOVED***
                    ***REMOVED*** else ***REMOVED***
                        notNormalTokenAlert()
                    ***REMOVED***
                    closeWindow()
                ***REMOVED***)
            ***REMOVED***
            const selectMedia = () => ***REMOVED***
                // 閉じた際に、clickするとnullをクリックした判定になるため、nullじゃないかチェック
                if (inputFileElement.value.click !== null) ***REMOVED*** inputFileElement.value.click() ***REMOVED***
            ***REMOVED***
            const displayMedia = () => ***REMOVED***
                if (inputFileElement.value.files.length > 0) ***REMOVED***
                    if (data.post.images.length < 4 && inputFileElement.value.files[0].type.match("image.*")) ***REMOVED***
                        const fileReader    = new FileReader()
                        fileReader.onload   = (() => ***REMOVED*** data.post.images.push(fileReader.result) ***REMOVED***)
                        fileReader.readAsDataURL(inputFileElement.value.files[0])
                    ***REMOVED*** else ***REMOVED***
                        createAlert(new alert('4枚以上の画像を設定できません', 1))
                    ***REMOVED***
                ***REMOVED***
            ***REMOVED***
            const bytes = (str) => ***REMOVED***
                return(encodeURIComponent(str).replace(/%../g,"x").length)
            ***REMOVED***
            const deleteMedia = (key) => ***REMOVED*** data.post.images.splice(key, 1) ***REMOVED***
            onBeforeMount(() => ***REMOVED***
                antiNotLoginUser()
            ***REMOVED***)
            onMounted(() => ***REMOVED*** createWindow('投稿する', 500, 660) ***REMOVED***)
            return ***REMOVED*** data, createPost, selectMedia, inputFileElement, displayMedia, deleteMedia, bytes ***REMOVED***
        ***REMOVED***
    ***REMOVED***
</script>