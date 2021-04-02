<template>
    <div>
        <link rel="stylesheet" href="/css/components/community/community.css">
        <!-- コミュニティで投稿する -->
        <div class="community-create-post-wapper" v-show="data.post.isOpen">
            <img v-if="$store.state.windowSize.width <= 414" @click="data.post.isOpen = false" style="width: 16px; height: 16px;" src="/images/materials/close.svg">
            <Form class="form" :useTextArea="true" v-model:inputContent="data.post.content" label="コミュニティに投稿する" uniqueClassKey="1" />
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
            <button :disabled="data.post.content.length === 0 || bytes(data.post.content) >= 280" @click="createPost" class="form__btn">投稿する</button>
        </div>
        <button class="community-create-post-wapper-trigger" v-show="!data.post.isOpen" @click="data.post.isOpen = true">投稿する</button>
        <!-- コミュニティの投稿一覧 -->
        <div v-for="(post, key) in data.post.objects" :key="key">
            <Post
                :postImageNames="post.imageNames"
                :communityId="Number(data.route.params.id)"
                :responceNum="post.responceNum"
                :imageName="post.imageName"
                :userName="post.userName" 
                :sendGood="sendGood"
                :sendArg="data.post.objects[key]"
                :content="post.content"
                :goodNum="post.goodNum"
                :postId="post.postId"
                :isGood="post.isGood"
                :name="post.name"
            />
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
    </div>
</template>

<script>
    import { reactive, onMounted, onBeforeMount, ref }  from 'vue'
    import { createAlert, alert, notNormalTokenAlert }  from '../../alert.js'
    import { antiLoginUser, antiNotLoginUser }          from '../../router.js'
    import { addPageEvent, removeAtAllFunc }            from '../../page.js'
    import { useRoute, useRouter }                      from 'vue-router'
    import { post, sendGood }                           from '../../post.js'
    import { getUidAndToken }                           from '../../supportFirebase.js'
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
                router: useRouter(),
                route:  useRoute(),
                store:  useStore(),
                post: {
                    cantGetPosts:   false,
                    objects:        [],
                    content:        '',
                    isOpen:         false,
                    images:         [],
                    gotNum:         0,
                    files:          [],
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
                        data.post.files.push(inputFileElement.value.files[0])
                        const fileReader    = new FileReader()
                        fileReader.onload   = (() => { data.post.images.push(fileReader.result) })
                        fileReader.readAsDataURL(inputFileElement.value.files[0])
                    } else {
                        createAlert(new alert('4枚以上の画像を設定できません', 1))
                    }
                }
            }
            const deleteMedia = (key) => {
                data.post.images.splice(key, 1)
                data.post.files.splice(key, 1)
            }
            const createPost = async() => {
                const user = await getUidAndToken()
                const createCommunityPostInfos = new FormData()
                createCommunityPostInfos.append('communityId', data.route.params.id)
                createCommunityPostInfos.append('content', data.post.content)
                createCommunityPostInfos.append('token', user.token)
                createCommunityPostInfos.append('uid', user.uid)
                for (let i = 0; i < data.post.files.length; i++) {
                    createCommunityPostInfos.append(`file[${i}]`, data.post.files[i])
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
                                    obj.user_info.image_name,
                                    obj.post_image_name,
                                )
                            )
                        })
                    })
                }
            }
            const bytes = (str) => {
                return(encodeURIComponent(str).replace(/%../g,"x").length)
            }
            const checkCanJoinCommunity = async() => {
                const user = await getUidAndToken()
                const canJoinCommunity = {
                    params: {
                        uid: user.uid,
                        token: user.token,
                        communityId: data.route.params.id,
                    }
                }
                axios.get('/api/get/can-join-community', canJoinCommunity)
                .then((responce) => {
                    if (responce.data.isNormalToken) {
                        if (!responce.data.canJoinCommunity) {
                            createAlert(new alert('コミュニティに入る権利がないです。'), 2)
                            data.router.push('/communities/0')   
                        }
                    } else {
                        notNormalTokenAlert()
                    }
                })
                .catch(() => {
                    createAlert(new alert('エラーが発生しました。'), 2)
                    data.router.push('/communities/0')   
                })
            }
            onBeforeMount(() => {
                antiNotLoginUser()
                checkCanJoinCommunity()
            })
            onMounted(() => {
                getPosts()
                addPageEvent('pageMostBottom', () => {getPosts()})
                if (data.store.state.windowSize.width > 414) {
                    data.post.isOpen = true
                }
            })
            return { data, sendGood, inputFileElement, selectMedia, displayMedia, deleteMedia, createPost, bytes }
        }
    }
</script>