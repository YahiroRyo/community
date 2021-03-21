<template>
    <div>
        <link rel="stylesheet" href="/css/components/profile/profile.css">
        <h1 class="profile__name">{{data.user.name}}</h1>
        <div class="profile__flex">
            <p class="profile__user-name">@{{data.user.userName}}</p>
            <router-link to="/profile-edit" class="profile__btn">プロフィールを編集する</router-link>
        </div>
        <p class="profile__content">{{data.user.intro}}</p>
        <div class="profile__posts-wapper">
            <template v-for="(post, key) in data.post.objects" :key="key">
                <Post
                    :name="post.name"
                    :userName="post.userName" 
                    :content="post.content"
                    :goodNum="post.goodNum"
                    :responceNum="post.responceNum"
                    :sendGood="sendGood"
                    :sendGoodKey="key"
                    :isGood="post.isGood"
                />
            </template>
        </div>
    </div>
</template>

<script>
    import { reactive, onMounted } from 'vue'
    import { useStore } from 'vuex'
    import { useRouter } from 'vue-router'
    import { post } from '../../post.js'
    import axios from 'axios'
    import Post from '../Post.vue'

    export default {
        components: {
            Post
        },
        setup() {
            const data = reactive({
                user: {
                    name: '',
                    userName: '',
                    intro: '',
                },
                post: {
                    objects: [],
                }
            })
            const sendGood = (key) => {
                data.post.objects[key].isGood = !data.post.objects[key].isGood
                data.post.objects[key].isGood ? data.post.objects[key].goodNum++ : data.post.objects[key].goodNum--
            }
            const getUserData = () => {
                const userProfileInfos = {
                    params: {
                        'uid': localStorage.getItem('uid'),
                    },
                }
                axios.get('/api/get/user-profile', userProfileInfos)
                .then((responce) => {
                    data.user.name = responce.data.name
                    data.user.userName = responce.data.user_name
                    data.user.intro = responce.data.intro
                })
                .catch(() => {
                   createAlert(new alert('ユーザーデータの取得に失敗しました。', 2))
                })
            }
            onMounted(() => {
                getUserData()

                /* ---------------TODO: サーバーから投稿内容を取得するajax処理を実装--------------- */

                // 仮で表示するために値を格納
                for (let i = 0; i < 100; i++)
                    data.post.objects.push(new post('name', 'userName', 'content', true, 1, 5))
            })
            return { data, sendGood }
        }
    }
</script>