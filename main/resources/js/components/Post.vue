<template>
    <div class="post">
        <div class="post__flex">
            <router-link :to="`/profile/${userName}`" class="post__name">{{name}}</router-link>
            <p class="post__font">@{{userName}}</p>
        </div>
        <p class="post__font">{{content}}</p>
        <div class="post__flex">
            <button @click="sendGood(sendGoodKey)" class="post__btn"><img class="post__btn__img" :src="'/images/materials/' + (isGood ? 'clickedHeart.svg' : 'heart.svg')"></button>
            <p class="post__status">{{goodNum}}</p>
            <button class="post__btn"><img class="post__btn__img" src="/images/materials/responce.svg"></button>
            <p class="post__status">{{responceNum}}</p>
            <button @click="" class="post__btn-display-responce">返信を表示する</button>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            name: {
                type: String,
                required: true,
            },
            userName: {
                type: String,
                required: true,
            },
            content: { type: String, },
            goodNum: { type: Number, },
            responceNum: { type: Number, },
            isGood: { type: Boolean, },
            sendGood: { type: Function, },
            sendGoodKey: { type: Number, },
        },
    }
</script>