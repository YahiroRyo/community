<template>
    <div>
        <link rel="stylesheet" href="/css/components/profile/profile.css">
        <div class="profile__flex">
            <div class="profile__icon-wapper">
                <img class="profile__icon-img" :src="`/storage/profileIcons/${data.user.imageName}`">
                <div v-if="$store.state.user.userName === data.user.userName" @click="selectProfileImage" class="profile__icon-change"><p class="profile__icon-change__text">画像を変更</p></div>
                <input v-if="$store.state.user.userName === data.user.userName" ref="inputFileElement" @change="changeProfileImage" style="display: none;" type="file" accept="image/,.jpg,.jpeg,.png" />
            </div>
            <div class="width-full">
                <h1 class="profile__name">{{data.user.name}}</h1>
                <div class="profile__other__flex">
                    <p class="profile__user-name">@{{data.user.userName}}</p>
                    <router-link to="/profile-edit" v-if="$store.state.user.userName === data.user.userName" class="profile__btn">プロフィールを編集する</router-link>
                </div>
            </div>
        </div>
        <p class="profile__content">{{data.user.intro}}</p>
        <div class="profile__posts-wapper">
            <template v-if="data.post.objects.length > 0" v-for="(post, key) in data.post.objects" :key="key">
                <Post
                    :postImageNames="post.imageNames"
                    :sendArg="data.post.objects[key]"
                    :responceNum="post.responceNum"
                    :imageName="post.imageName"
                    :userName="post.userName" 
                    :content="post.content"
                    :goodNum="post.goodNum"
                    :postId="post.postId"
                    :isGood="post.isGood"
                    :sendGood="sendGood"
                    :name="post.name"
                />
            </template>
            <h2 v-else-if="!data.user.isFound">このユーザーは存在しません。</h2>
            <h2 v-else>このユーザーのツイートは存在しません。</h2>
        </div>
    </div>
</template>

<script>
    import { useRouter, useRoute, onBeforeRouteUpdate, }    from 'vue-router'
    import { reactive, onMounted, onBeforeMount, ref }      from 'vue'
    import { alert, createAlert, notNormalTokenAlert }      from '../../alert'
    import { post, sendGood }                               from '../../post.js'
    import { getUidAndToken }                               from '../../supportFirebase.js'
    import { displayWindow }                                from '../../window.js'
    import { ruseStore }                                    from 'vuex'
    import { useStore }                                     from 'vuex'
    import axios                                            from 'axios'
    import Post                                             from '../Post.vue'

    export default {
        components: {
            Post
        },
        setup() {
            const data = reactive({
                store: useStore(),
                route: useRoute(),
                user: {
                    name:       '',
                    userName:   '',
                    intro:      '',
                    imageName:  '',
                    isFound:    false,
                },
                post: {
                    cantGetPosts:   false,
                    objects:        [],
                    gotNum:         0,
                    take:           50,
                }
            })
            const inputFileElement = ref(null)
            const getUserData = async() => {
                const userProfileInfos = {
                    params: {
                        'userName': data.route.params.userName,
                    },
                }
                await axios.get('/api/get/user-profile', userProfileInfos)
                .then((responce) => {
                    if (responce.data !== null) {
                        data.user.name      = responce.data.name
                        data.user.userName  = responce.data.user_name
                        data.user.intro     = responce.data.intro
                        data.user.imageName = responce.data.image_name
                        data.user.isFound   = true
                    }
                })
                .catch(() => {
                   createAlert(new alert('ユーザーデータの取得に失敗しました。', 2))
                })
            }
            const getUsersPosts = async(userName) => {
                if (!data.post.cantGetPosts) {
                    let user = {}
                    if (data.store.state.user.isLogin) {
                        user = await getUidAndToken()
                    } else {
                        user.uid = ''
                    }
                    const usersPostsInfos = {
                        params: {
                            userName:   userName,
                            gotNum:     data.post.gotNum,
                            take:       data.post.take,
                            uid:        user.uid,
                        }
                    }
                    axios.get('/api/get/users-posts', usersPostsInfos)
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
            const selectProfileImage = () => {
                if (inputFileElement.value.click !== null) { inputFileElement.value.click() }
            }
            const changeProfileImage = async() => {
                if (inputFileElement.value.files.length > 0 && (
                        inputFileElement.value.files[0].type.match("image.png") ||
                        inputFileElement.value.files[0].type.match("image.jpg") ||
                        inputFileElement.value.files[0].type.match("image.jpeg")
                    )) {
                    const user = await getUidAndToken()
                    const refreshUserPostImageInfos = new FormData()
                    refreshUserPostImageInfos.append('file', inputFileElement.value.files[0])
                    refreshUserPostImageInfos.append('uid', user.uid)
                    refreshUserPostImageInfos.append('token', user.token)
                    axios.post('/api/post/refresh-user-profile-image', refreshUserPostImageInfos)
                    .then((responce) => {
                        if (responce.data.isNormalToken) {
                            if (responce.data.isRefreshImage) {
                                createAlert(new alert('画像を設定しました。', 0))
                                data.post.cantGetPosts = false
                                data.post.objects = []
                                data.post.gotNum = 0
                                getUserData()
                                if (data.user.isFound)
                                    getUsersPosts(data.route.params.userName)
                            } else {
                                createAlert(new alert('画像の更新に失敗しました。', 2))
                            }
                        } else {
                            notNormalTokenAlert()
                        }
                    })
                }
            }
            onBeforeRouteUpdate((to, from) => {
                data.post.cantGetPosts = false
                data.post.objects = []
                data.post.gotNum = 0
                getUserData()
                if (data.user.isFound)
                    getUsersPosts(to.params.userName)
            })
            
            onMounted(async() => {
                await getUserData()
                if (data.user.isFound)
                    getUsersPosts(data.route.params.userName)
            })
            return { data, sendGood, changeProfileImage, inputFileElement, selectProfileImage }
        }
    }
</script>