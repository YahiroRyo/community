<template>
    <div>
        <link rel="stylesheet" href="/css/components/globalPost/globalPost.css">
        <div class="post" v-for="(post, key) in data.post.objects" :key="key">
            <div class="post__flex">
                <p class="post__name">{{post.name}}</p>
                <p class="post__font">@{{post.userName}}</p>
            </div>
            <p class="post__font">{{post.content}}</p>
            <div class="post__flex">
                <button @click="sendGood(key)" class="post__btn"><img class="post__btn__img" :src="'/images/materials/' + (post.isGood ? 'clickedHeart.svg' : 'heart.svg')"></button>
                <p class="post__status">{{post.goodNum}}</p>
                <button class="post__btn"><img class="post__btn__img" src="/images/materials/responce.svg"></button>
                <p class="post__status">{{post.responceNum}}</p>
                <button @click="" class="post__btn-display-responce">返信を表示する</button>
            </div>
        </div>
    </div>
</template>

<script>
    /* ---------------postクラスについて--------------- */
    // ・変数の説明
    // name:        投稿をした主の名前
    // userName:    投稿をした主のユーザーネーム
    // content:     投稿をした主が投稿した文章
    // isGood:      投稿に対して自分がいいねを押したか
    // goodNum:     投稿のいいね数
    // responceNum: 投稿への返信数
    
    class post {
        constructor(name, userName, content, isGood, goodNum, responceNum) {
            this.name           = name
            this.userName       = userName
            this.content        = content
            this.isGood         = isGood
            this.goodNum        = goodNum
            this.responceNum    = responceNum
        }
    }

    import { reactive, onMounted } from 'vue'

    export default {
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