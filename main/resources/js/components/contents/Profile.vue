<template>
    <div>
        <link rel="stylesheet" href="/css/components/profile/profile.css">
        <h1 class="profile__name">name</h1>
        <div class="profile__flex">
            <p class="profile__user-name">@user-name</p>
            <router-link to="/profile-edit" class="profile__btn">プロフィールを編集する</router-link>
        </div>
        <p class="profile__content">content</p>
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
    import { post } from '../../post.js'
    import Post from '../Post.vue'

    export default {
        components: {
            Post
        },
        setup() {
            const data = reactive({
                post: {
                    objects: [],
                }
            })
            const sendGood = (key) => {
                data.post.objects[key].isGood = !data.post.objects[key].isGood
                data.post.objects[key].isGood ? data.post.objects[key].goodNum++ : data.post.objects[key].goodNum--
            }
            onMounted(() => {
                /* ---------------TODO: サーバーから投稿内容を取得するajax処理を実装--------------- */

                // 仮で表示するために値を格納
                for (let i = 0; i < 100; i++)
                    data.post.objects.push(new post('name', 'userName', 'content', true, 1, 5))
            })
            return { data, sendGood }
        }
    }
</script>