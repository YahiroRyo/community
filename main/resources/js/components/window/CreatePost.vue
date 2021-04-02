<template>
    <div>
        <link rel="stylesheet" href="/css/components/createPost/createPost.css">
        <Form class="form" :useTextArea="true" v-model:inputContent="data.post.content" label="投稿内容を入力" uniqueClassKey="1" />
        <!-- 文字数カウント -->
        <p :class="{'form-label-create-post': true, 'form-label_danger': bytes(data.post.content) >= 280, }">{{bytes(data.post.content) }} | 280</p>
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
        <button :disabled="data.post.content.length === 0 || bytes(data.post.content) >= 280" @click="createPost" class="form__btn m-l-1">投稿する</button>
    </div>
</template>

<script>
    /* ---------------createPost(投稿)をする制約-------------- */
    // ・文字数は200字まで
    // ・画像を投稿できるのは4枚まで

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
                    files:      [],
                },
            })
            const createPost = async() => {
                const user = await getUidAndToken()
                const createPostInfos = new FormData()
                createPostInfos.append('content', data.post.content)
                createPostInfos.append('token', user.token)
                createPostInfos.append('uid', user.uid)
                for (let i = 0; i < data.post.files.length; i++) {
                    createPostInfos.append(`file[${i}]`, data.post.files[i])
                }
                axios.post('/api/post/create-post', createPostInfos)
                .then((responce) => {
                    if (responce.data.isNormalToken) {
                        if (responce.data.isCreatePost) {
                            createAlert(new alert('投稿をしました。', 0))
                        } else {
                            createAlert(new alert('投稿をすることができませんでした。', 2))
                        }
                    } else {
                        notNormalTokenAlert()
                    }
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
                        data.post.files.push(inputFileElement.value.files[0])
                        const fileReader    = new FileReader()
                        fileReader.onload   = (() => { data.post.images.push(fileReader.result) })
                        fileReader.readAsDataURL(inputFileElement.value.files[0])
                    } else {
                        createAlert(new alert('4枚以上の画像を設定できません', 1))
                    }
                }
            }
            const bytes = (str) => {
                return(encodeURIComponent(str).replace(/%../g,"x").length)
            }
            const deleteMedia = (key) => {
                data.post.images.splice(key, 1)
                data.post.files.splice(key, 1)
            }
            onBeforeMount(() => {
                antiNotLoginUser()
            })
            onMounted(() => {
                if (data.store.state.windowSize.width <= 414) {
                    createWindow('投稿する', data.store.state.windowSize.width - 10, data.store.state.windowSize.height - 20)
                } else {
                    createWindow('投稿する', 500, 680)
                }
            })
            return { data, createPost, selectMedia, inputFileElement, displayMedia, deleteMedia, bytes }
        }
    }
</script>