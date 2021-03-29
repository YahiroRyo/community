<template>
    <div>
        <link rel="stylesheet" href="/css/components/community/community.css">
        <!-- コミュニティで投稿する -->
        <div class="community-create-post-wapper">
            <Form class="form" :useTextArea="true" v-model:inputContent="data.post.content" label="コミュニティに投稿する" uniqueClassKey="1" />
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
            <button @click="createPost" class="form__btn">投稿する</button>
        </div>
        <!-- コミュニティの投稿一覧 -->
        <div v-for="(post, key) in data.post.objects" :key="key">
            <Post
                :sendArg="data.post.objects[key]"
                :responceNum="post.responceNum"
                :communityId="Number(data.route.params.id)"
                :userName="post.userName" 
                :content="post.content"
                :goodNum="post.goodNum"
                :postId="post.postId"
                :isGood="post.isGood"
                :sendGood="sendGood"
                :name="post.name"
            />
        </div>
    </div>
    <component is="style">
        .form__textarea,
        .form__textarea-wapper {height: 30px !important;}
        .form__textarea:focus ~ .form__label,
        .form__textarea:valid ~ .form__label {
            transform: translateY(-25px);
        }
        .form { margin-bottom: 20px !important; }
    </component>
</template>

<script>
    import { createAlert, alert, notNormalTokenAlert }  from '../../alert.js'
    import { antiLoginUser, antiNotLoginUser }          from '../../router.js'
    import { addPageEvent, removeAtAllFunc }            from '../../page.js'
    import { reactive, onMounted, ref }                 from 'vue'
    import { post, sendGood }                           from '../../post.js'
    import { getUidAndToken }                           from '../../supportFirebase.js'
    import { useRoute }                                 from 'vue-router'
    import { useStore }                                 from 'vuex'
    import axios                                        from 'axios' 
    
    /* ---------------コンポーネントをインポート--------------- */
    import Post                                 from '../Post.vue'
    import Form                                 from '../Form.vue'

    export default {
        components: { 
            Post,
            Form,
        },
        setup() {
            const data = reactive({
                route: useRoute(),
                store: useStore(),
                post: {
                    cantGetPosts:   false,
                    objects:        [],
                    content:        '',
                    images:         [],
                    gotNum:         0,
                    take:           50,
                },
            })
            const inputFileElement = ref(null)
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
            const deleteMedia = (key) => { data.post.images.splice(key, 1) }
            const createPost = async() => {
                const user = await getUidAndToken()
                const createCommunityPostInfos = {
                    communityId:    data.route.params.id,
                    content:        data.post.content,
                    token:          user.token,
                    uid:            user.uid,
                }
                axios.post('/api/post/create-community-post', createCommunityPostInfos)
                .then((responce) => {
                    if (responce.data.isNormalToken) {
                        if (responce.data.isCreateCommunityPost) {
                            createAlert(new alert('投稿しました。', 0))
                        } else {
                            createAlert(new alert('投稿に失敗しました。', 2))
                        }
                    } else {
                        notNormalTokenAlert()
                    }
                })
            }
            const getPosts = async() => {
                if (!data.post.cantGetPosts) {
                    const user = await getUidAndToken()
                    const communityPostsInfos = {
                        params: {
                            communityId:    data.route.params.id,
                            gotNum:         data.post.gotNum,
                            take:           data.post.take,
                            uid:            user.uid,
                        }
                    }
                    axios.get('/api/get/community-posts', communityPostsInfos)
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
                                )
                            )
                        })
                    })
                }
            }
            onMounted(() => {
                antiNotLoginUser()
                getPosts()
                addPageEvent('pageMostBottom', () => {getPosts()})
            })
            return { data, sendGood, inputFileElement, selectMedia, displayMedia, deleteMedia, createPost }
        }
    }
</script>