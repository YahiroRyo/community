<template>
    <div class="post">
        <div class="post__flex">
            <router-link :to="`/profile/$***REMOVED***userName***REMOVED***`"><img class="post__icon-img" :src="`/images/$***REMOVED***imageName***REMOVED***`"></router-link>
            <div>
                <div class="post__flex">
                    <router-link :to="`/profile/$***REMOVED***userName***REMOVED***`" class="post__name">***REMOVED******REMOVED***name***REMOVED******REMOVED***</router-link>
                    <p class="post__font">@***REMOVED******REMOVED***userName***REMOVED******REMOVED***</p>
                </div>
                <p class="post__font">***REMOVED******REMOVED***content***REMOVED******REMOVED***</p>
                <div class="post__flex">
                    <button @click="sendGood(sendArg)" class="post__btn"><img class="post__btn__img" :src="'/images/materials/' + (isGood ? 'clickedHeart.svg' : 'heart.svg')"></button>
                    <p class="post__status">***REMOVED******REMOVED***goodNum***REMOVED******REMOVED***</p>
                    <button @click="responceToPost()" class="post__btn"><img class="post__btn__img" src="/images/materials/responce.svg"></button>
                    <p class="post__status">***REMOVED******REMOVED***responceNum***REMOVED******REMOVED***</p>
                    <button @click="data.router.push(`/responce/$***REMOVED***postId***REMOVED***`)" class="post__btn-display-responce">返信を表示する</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import ***REMOVED*** displayWindow ***REMOVED***    from '../window'
    import ***REMOVED*** useRouter ***REMOVED***        from 'vue-router';
    import ***REMOVED*** useStore ***REMOVED***         from 'vuex';
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
            communityId:    ***REMOVED***
                default:    0,
                type:       Number,
            ***REMOVED***,
            imageName:      ***REMOVED***
                default:    'default.jpg',
                type:       String,
            ***REMOVED***,
            responceNum:    ***REMOVED*** type: Number, ***REMOVED***,
            sendGood:       ***REMOVED*** type: Function, ***REMOVED***,
            sendArg:        ***REMOVED*** type: Object, ***REMOVED***,
            content:        ***REMOVED*** type: String, ***REMOVED***,
            goodNum:        ***REMOVED*** type: Number, ***REMOVED***,
            postId:         ***REMOVED*** type: Number, ***REMOVED***,
            isGood:         ***REMOVED*** type: Boolean, ***REMOVED***,
        ***REMOVED***,
        setup(props) ***REMOVED***
            const data = reactive(***REMOVED***
                router: useRouter(),
                store:  useStore(),
            ***REMOVED***)
            const responceToPost = () => ***REMOVED***
                if (data.store.state.user.isLogin) ***REMOVED***
                    data.store.state.post.toResponcePostId = props.postId
                    if (props.communityId !== 0) ***REMOVED***
                        data.store.state.post.toResponceCommunityId = props.communityId
                    ***REMOVED***
                    displayWindow(4)
                ***REMOVED*** else ***REMOVED***
                    displayWindow(5)
                ***REMOVED***
            ***REMOVED***
            return ***REMOVED*** data, responceToPost ***REMOVED***
        ***REMOVED***
    ***REMOVED***
</script>