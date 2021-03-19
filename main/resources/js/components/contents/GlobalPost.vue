<template>
    <div>
        <link rel="stylesheet" href="/css/components/globalPost/globalPost.css">
        <div class="post" v-for="(post, key) in data.post.objects" :key="key">
            <div class="post__flex">
                <p class="post__name">***REMOVED******REMOVED***post.name***REMOVED******REMOVED***</p>
                <p class="post__font">@***REMOVED******REMOVED***post.userName***REMOVED******REMOVED***</p>
            </div>
            <p class="post__font">***REMOVED******REMOVED***post.content***REMOVED******REMOVED***</p>
            <div class="post__flex">
                <button @click="sendGood(key)" class="post__btn"><img class="post__btn__img" :src="'/images/materials/' + (post.isGood ? 'clickedHeart.svg' : 'heart.svg')"></button>
                <p class="post__status">***REMOVED******REMOVED***post.goodNum***REMOVED******REMOVED***</p>
                <button class="post__btn"><img class="post__btn__img" src="/images/materials/responce.svg"></button>
                <p class="post__status">***REMOVED******REMOVED***post.responceNum***REMOVED******REMOVED***</p>
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
    
    class post ***REMOVED***
        constructor(name, userName, content, isGood, goodNum, responceNum) ***REMOVED***
            this.name           = name
            this.userName       = userName
            this.content        = content
            this.isGood         = isGood
            this.goodNum        = goodNum
            this.responceNum    = responceNum
        ***REMOVED***
    ***REMOVED***

    import ***REMOVED*** reactive, onMounted ***REMOVED*** from 'vue'

    export default ***REMOVED***
        setup() ***REMOVED***
            const data = reactive(***REMOVED***
                post: ***REMOVED***
                    objects: [],
                ***REMOVED***
            ***REMOVED***)
            const sendGood = (key) => ***REMOVED***
                data.post.objects[key].isGood = !data.post.objects[key].isGood
                data.post.objects[key].isGood ? data.post.objects[key].goodNum++ : data.post.objects[key].goodNum--
            ***REMOVED***
            onMounted(() => ***REMOVED***
                /* ---------------TODO: サーバーから投稿内容を取得するajax処理を実装--------------- */

                // 仮で表示するために値を格納
                for (let i = 0; i < 100; i++)
                    data.post.objects.push(new post('name', 'userName', 'content', true, 1, 5))
            ***REMOVED***)
            return ***REMOVED*** data, sendGood ***REMOVED***
        ***REMOVED***
    ***REMOVED***
</script>