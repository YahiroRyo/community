<template>
    <div class="post">
        <div class="post__flex">
            <router-link :to="`/profile/$***REMOVED***userName***REMOVED***`" class="post__name">***REMOVED******REMOVED***name***REMOVED******REMOVED***</router-link>
            <p class="post__font">@***REMOVED******REMOVED***userName***REMOVED******REMOVED***</p>
        </div>
        <p class="post__font">***REMOVED******REMOVED***content***REMOVED******REMOVED***</p>
        <div class="post__flex">
            <button @click="sendGood(sendKey)" class="post__btn"><img class="post__btn__img" :src="'/images/materials/' + (isGood ? 'clickedHeart.svg' : 'heart.svg')"></button>
            <p class="post__status">***REMOVED******REMOVED***goodNum***REMOVED******REMOVED***</p>
            <button @click="$store.state.post.toResponcePostId = postId; displayWindow(4);" class="post__btn"><img class="post__btn__img" src="/images/materials/responce.svg"></button>
            <p class="post__status">***REMOVED******REMOVED***responceNum***REMOVED******REMOVED***</p>
            <button @click="data.router.push(`/responce/$***REMOVED***postId***REMOVED***`)" class="post__btn-display-responce">返信を表示する</button>
        </div>
    </div>
</template>

<script>
    import ***REMOVED*** displayWindow ***REMOVED***    from '../window'
    import ***REMOVED*** useRouter ***REMOVED***        from 'vue-router';
    import ***REMOVED*** reactive ***REMOVED***         from 'vue';

    export default ***REMOVED***
        props: ***REMOVED***
            name: ***REMOVED***
                type:       String,
                required:   true,
            ***REMOVED***,
            userName: ***REMOVED***
                type:       String,
                required:   true,
            ***REMOVED***,
            responceNum:    ***REMOVED*** type: Number, ***REMOVED***,
            sendGood:       ***REMOVED*** type: Function, ***REMOVED***,
            sendKey:        ***REMOVED*** type: Number, ***REMOVED***,
            content:        ***REMOVED*** type: String, ***REMOVED***,
            goodNum:        ***REMOVED*** type: Number, ***REMOVED***,
            postId:         ***REMOVED*** type: Number, ***REMOVED***,
            isGood:         ***REMOVED*** type: Boolean, ***REMOVED***,
        ***REMOVED***,
        setup() ***REMOVED***
            const data = reactive(***REMOVED***
                router: useRouter(),
            ***REMOVED***)
            return ***REMOVED*** data, displayWindow ***REMOVED***
        ***REMOVED***
    ***REMOVED***
</script>