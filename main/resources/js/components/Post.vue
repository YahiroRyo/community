<template>
    <div class="post">
        <div class="post__flex">
            <router-link :to="`/profile/${userName}`" class="post__name">{{name}}</router-link>
            <p class="post__font">@{{userName}}</p>
        </div>
        <p class="post__font">{{content}}</p>
        <div class="post__flex">
            <button @click="sendGood(sendKey)" class="post__btn"><img class="post__btn__img" :src="'/images/materials/' + (isGood ? 'clickedHeart.svg' : 'heart.svg')"></button>
            <p class="post__status">{{goodNum}}</p>
            <button @click="responceToPost()" class="post__btn"><img class="post__btn__img" src="/images/materials/responce.svg"></button>
            <p class="post__status">{{responceNum}}</p>
            <button @click="data.router.push(`/responce/${postId}`)" class="post__btn-display-responce">返信を表示する</button>
        </div>
    </div>
</template>

<script>
    import { displayWindow }    from '../window'
    import { useRouter }        from 'vue-router';
    import { useStore }         from 'vuex';
    import { reactive }         from 'vue';

    export default {
        props: {
            name: {
                type:       String,
                required:   true,
            },
            userName: {
                type:       String,
                required:   true,
            },
            responceNum:    { type: Number, },
            sendGood:       { type: Function, },
            sendKey:        { type: Number, },
            content:        { type: String, },
            goodNum:        { type: Number, },
            postId:         { type: Number, },
            isGood:         { type: Boolean, },
        },
        setup(props) {
            const data = reactive({
                router: useRouter(),
                store:  useStore(),
            })
            const responceToPost = () => {
                if (data.store.state.user.isLogin) {
                    data.store.state.post.toResponcePostId = props.postId
                    displayWindow(4)
                } else {
                    displayWindow(5)
                }
            }
            return { data, responceToPost }
        }
    }
</script>