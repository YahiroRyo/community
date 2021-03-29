<template>
    <div>
        <link rel="stylesheet" href="/css/components/createPost/createPost.css">
        <Form class="form" :useTextArea="true" v-model:inputContent="data.post.content" label="返信内容を入力" uniqueClassKey="1" />
        <!-- 文字数カウント -->
        <p :class="{'form-label-create-post': true, 'form-label_danger': data.post.content.length >= 200 ? true : false, }">{{data.post.content.length}} | 200</p>
        <!-- 画像プレビュー -->
        <div class="create-post-display-img-wapper" v-show="data.post.images.length > 0">
            <transition-group name="create-post-input-img-anim">
                <div class="create-post-input-img" v-for="(image, key) in data.post.images" :key="key" :style="`background-image: url(${image})`">
                    <button @click="deleteMedia(key)" class="create-post-input-img__btn"><img class="create-post-input-img__btn__img" src="/images/materials/close.svg"></button>
                </div>
            </transition-group>
        </div>
        <button @click="selectMedia" class="create-post-btn"><img src="/images/materials/media.svg" class="create-post-btn__media-img"></button>
        <input ref="inputFileElement" @change="displayMedia" style="display: none;" type="file" accept="image/*" />
        <button @click="createResponcePost" class="form__btn m-l-1">返信する</button>
    </div>
</template>

<script>
    import { reactive, onMounted, ref, onBeforeMount }  from 'vue'
    import { createAlert, alert, notNormalTokenAlert }  from '../../alert.js'
    import { antiLoginUser, antiNotLoginUser }          from '../../router.js'
    import { createWindow, closeWindow }                from '../../window.js'
    import { getUidAndToken }                           from '../../supportFirebase.js'
    import { useStore }                                 from 'vuex'
    import axios                                        from 'axios'
    /* ---------------コンポーネントをインポート--------------- */
    import Form                                         from '../Form.vue'

    export default {
        components: {
            Form,
        },
        setup() {
            // ボタンを用いてファイルを選択させたいため、ファイル選択にrefを用いた。
            const inputFileElement = ref(null)
            const data = reactive({
                store: useStore(),
                post: {
                    content:    '',
                    images:     [],
                },
            })
            const createResponcePost = async() => {
                if (!data.store.state.post.toResponcePostId) {
                    createAlert(new alert('返信する対象の投稿が存在しません。', 2))
                }
                const user = await getUidAndToken()
                const createResponcePostInfos = {
                    content:    data.post.content,
                    postId:     data.store.state.post.toResponcePostId,
                    token:      user.token,
                    uid:        user.uid,
                }
                if (data.store.state.post.toResponceCommunityId) {
                    createResponcePostInfos['communityId'] = data.store.state.post.toResponceCommunityId
                }
                axios.post('/api/post/create-responce-post', createResponcePostInfos)
                .then((responce) => {
                    if (responce.data.isNormalToken) {
                        if (responce.data.isCreateResponcePost) {
                            createAlert(new alert('返信をしました。', 0))
                        } else {
                            createAlert(new alert('返信をすることができませんでした。', 2))
                        }
                    } else {
                        notNormalTokenAlert()
                    }
                    data.store.state.post.toResponcePostId      = null
                    data.store.state.post.toResponceCommunityId = null
                    closeWindow()
                })
            }
            const selectMedia = () => {
                // 閉じた際に、clickするとnullをクリックした判定になるため、nullじゃないかチェック
                if (inputFileElement.value.click !== null) { inputFileElement.value.click() }
            }
            const displayMedia = () => {
                if (inputFileElement.value.files.length > 0) {
                    if (data.post.images.length < 4 && inputFileElement.value.files[0].type.match("image.*")) {
                        const fileReader    = new FileReader()
                        fileReader.onload   = (() => { data.post.images.push(fileReader.result) })
                        fileReader.readAsDataURL(inputFileElement.value.files[0])
                    } else {
                        createAlert(new alert('4枚以上の画像を設定できません', 1))
                    }
                }
            }
            onBeforeMount(() => {
                antiNotLoginUser()
            })
            const deleteMedia = (key) => { data.post.images.splice(key, 1) }
            onMounted(() => { createWindow('投稿に返信', 500, 660) })
            return { data, createResponcePost, selectMedia, inputFileElement, displayMedia, deleteMedia, }
        }
    }
</script>