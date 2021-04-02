<template>
    <div>
        <link rel="stylesheet" href="/css/components/community/community.css">
        <!-- コミュニティで投稿する -->
        <div class="community-create-post-wapper" v-show="data.post.isOpen">
            <img v-if="$store.state.windowSize.width <= 414" @click="data.post.isOpen = false" style="width: 16px; height: 16px;" src="/images/materials/close.svg">
            <Form class="form" :useTextArea="true" v-model:inputContent="data.post.content" label="コミュニティに投稿する" uniqueClassKey="1" />
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
            <button @click="createPost" class="form__btn">投稿する</button>
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
            .form__textarea-wapper ***REMOVED***height: 30px !important;***REMOVED***
            .form__textarea:focus ~ .form__label,
            .form__textarea:valid ~ .form__label ***REMOVED***
                transform: translateY(-25px);
            ***REMOVED***
            .form ***REMOVED*** margin-bottom: 20px !important; ***REMOVED***
        </component>
    </div>
</template>

<script>
    import ***REMOVED*** reactive, onMounted, onBeforeMount, ref ***REMOVED***  from 'vue'
    import ***REMOVED*** createAlert, alert, notNormalTokenAlert ***REMOVED***  from '../../alert.js'
    import ***REMOVED*** antiLoginUser, antiNotLoginUser ***REMOVED***          from '../../router.js'
    import ***REMOVED*** addPageEvent, removeAtAllFunc ***REMOVED***            from '../../page.js'
    import ***REMOVED*** useRoute, useRouter ***REMOVED***                      from 'vue-router'
    import ***REMOVED*** post, sendGood ***REMOVED***                           from '../../post.js'
    import ***REMOVED*** getUidAndToken ***REMOVED***                           from '../../supportFirebase.js'
    import ***REMOVED*** useStore ***REMOVED***                                 from 'vuex'
    import axios                                        from 'axios' 
    
    /* ---------------コンポーネントをインポート--------------- */
    import Post                                 from '../Post.vue'
    import Form                                 from '../Form.vue'

    export default ***REMOVED***
        components: ***REMOVED*** 
            Post,
            Form,
        ***REMOVED***,
        setup() ***REMOVED***
            const data = reactive(***REMOVED***
                router: useRouter(),
                route:  useRoute(),
                store:  useStore(),
                post: ***REMOVED***
                    cantGetPosts:   false,
                    objects:        [],
                    content:        '',
                    isOpen:         false,
                    images:         [],
                    gotNum:         0,
                    files:          [],
                    take:           50,
                ***REMOVED***,
            ***REMOVED***)
            const inputFileElement = ref(null)
            const selectMedia = () => ***REMOVED***
                // 閉じた際に、clickするとnullをクリックした判定になるため、nullじゃないかチェック
                if (inputFileElement.value.click !== null) ***REMOVED*** inputFileElement.value.click() ***REMOVED***
            ***REMOVED***
            const displayMedia = () => ***REMOVED***
                if (inputFileElement.value.files.length > 0) ***REMOVED***
                    if (data.post.images.length < 4 && inputFileElement.value.files[0].type.match("image.*")) ***REMOVED***
                        data.post.files.push(inputFileElement.value.files[0])
                        const fileReader    = new FileReader()
                        fileReader.onload   = (() => ***REMOVED*** data.post.images.push(fileReader.result) ***REMOVED***)
                        fileReader.readAsDataURL(inputFileElement.value.files[0])
                    ***REMOVED*** else ***REMOVED***
                        createAlert(new alert('4枚以上の画像を設定できません', 1))
                    ***REMOVED***
                ***REMOVED***
            ***REMOVED***
            const deleteMedia = (key) => ***REMOVED***
                data.post.images.splice(key, 1)
                data.post.files.splice(key, 1)
            ***REMOVED***
            const createPost = async() => ***REMOVED***
                const user = await getUidAndToken()
                const createCommunityPostInfos = new FormData()
                createCommunityPostInfos.append('communityId', data.route.params.id)
                createCommunityPostInfos.append('content', data.post.content)
                createCommunityPostInfos.append('token', user.token)
                createCommunityPostInfos.append('uid', user.uid)
                for (let i = 0; i < data.post.files.length; i++) ***REMOVED***
                    createCommunityPostInfos.append(`file[$***REMOVED***i***REMOVED***]`, data.post.files[i])
                ***REMOVED***
                axios.post('/api/post/create-community-post', createCommunityPostInfos)
                .then((responce) => ***REMOVED***
                    if (responce.data.isNormalToken) ***REMOVED***
                        if (responce.data.isCreateCommunityPost) ***REMOVED***
                            createAlert(new alert('投稿しました。', 0))
                        ***REMOVED*** else ***REMOVED***
                            createAlert(new alert('投稿に失敗しました。', 2))
                        ***REMOVED***
                    ***REMOVED*** else ***REMOVED***
                        notNormalTokenAlert()
                    ***REMOVED***
                ***REMOVED***)
            ***REMOVED***
            const getPosts = async() => ***REMOVED***
                if (!data.post.cantGetPosts) ***REMOVED***
                    const user = await getUidAndToken()
                    const communityPostsInfos = ***REMOVED***
                        params: ***REMOVED***
                            communityId:    data.route.params.id,
                            gotNum:         data.post.gotNum,
                            take:           data.post.take,
                            uid:            user.uid,
                        ***REMOVED***
                    ***REMOVED***
                    axios.get('/api/get/community-posts', communityPostsInfos)
                    .then((responce) => ***REMOVED***
                        data.post.gotNum += data.post.take
                        if (data.post.take > responce.data.length)
                            data.post.cantGetPosts = true
                        responce.data.forEach((obj) => ***REMOVED***
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
                        ***REMOVED***)
                    ***REMOVED***)
                ***REMOVED***
            ***REMOVED***
            const bytes = (str) => ***REMOVED***
                return(encodeURIComponent(str).replace(/%../g,"x").length)
            ***REMOVED***
            const checkCanJoinCommunity = async() => ***REMOVED***
                const user = await getUidAndToken()
                const canJoinCommunity = ***REMOVED***
                    params: ***REMOVED***
                        uid: user.uid,
                        token: user.token,
                        communityId: data.route.params.id,
                    ***REMOVED***
                ***REMOVED***
                axios.get('/api/get/can-join-community', canJoinCommunity)
                .then((responce) => ***REMOVED***
                    if (responce.data.isNormalToken) ***REMOVED***
                        if (!responce.data.canJoinCommunity) ***REMOVED***
                            createAlert(new alert('コミュニティに入る権利がないです。'), 2)
                            data.router.push('/communities/0')   
                        ***REMOVED***
                    ***REMOVED*** else ***REMOVED***
                        notNormalTokenAlert()
                    ***REMOVED***
                ***REMOVED***)
                .catch(() => ***REMOVED***
                    createAlert(new alert('エラーが発生しました。'), 2)
                    data.router.push('/communities/0')   
                ***REMOVED***)
            ***REMOVED***
            onBeforeMount(() => ***REMOVED***
                antiNotLoginUser()
                checkCanJoinCommunity()
            ***REMOVED***)
            onMounted(() => ***REMOVED***
                getPosts()
                addPageEvent('pageMostBottom', () => ***REMOVED***getPosts()***REMOVED***)
                if (data.store.state.windowSize.width > 414) ***REMOVED***
                    data.post.isOpen = true
                ***REMOVED***
            ***REMOVED***)
            return ***REMOVED*** data, sendGood, inputFileElement, selectMedia, displayMedia, deleteMedia, createPost, bytes ***REMOVED***
        ***REMOVED***
    ***REMOVED***
</script>