<template>
    <div class="post">
        <div style="display: flex;">
            <router-link :to="`/profile/${userName}`"><img class="post__icon-img" :src="`/storage/profileIcons/${imageName}`"></router-link>
            <div class="width-full">
                <div class="post__flex">
                    <router-link :to="`/profile/${userName}`" class="post__name">{{name}}</router-link>
                    <p class="post__font">@{{userName}}</p>
                    <button v-if="isMainPost" @click="deletePost(key)" class="post__btn-delete">消去する</button>
                </div>
                <p class="post__font">{{content}}</p>
                <div class="post__img-wapper" v-show="postImageNames.length !== 0">
                    <div class="post__img"  v-for="(postImageName, key) in postImageNames" @click="displayImage(key)" :style="`background-image: url(/storage/${postImageName.image_name})`" :key="key"></div>
                </div>
                <div class="post__flex">
                    <button @click="sendGood(sendArg)" class="post__btn"><img class="post__btn__img" :src="'/images/materials/' + (isGood ? 'clickedHeart.svg' : 'heart.svg')"></button>
                    <p class="post__status">{{goodNum}}</p>
                    <button @click="responceToPost()" class="post__btn"><img class="post__btn__img" src="/images/materials/responce.svg"></button>
                    <p class="post__status">{{responceNum}}</p>
                    <button @click="data.router.push(`/responce/${postId}`)" class="post__btn-display-responce">返信を表示</button>
                </div>
            </div>
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
            communityId:    {
                default:    0,
                type:       Number,
            },
            imageName:      {
                default:    'default.jpg',
                type:       String,
            },
            postImageNames: { type: Array, },
            responceNum:    { type: Number, },
            isMainPost:     { type: Boolean, },
            sendGood:       { type: Function, },
            sendArg:        { type: Object, },
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
                    if (props.communityId !== 0) {
                        data.store.state.post.toResponceCommunityId = props.communityId
                    }
                    displayWindow(4)
                } else {
                    displayWindow(5)
                }
            }
            const displayImage = (key) => {
                data.store.state.post.image = props.postImageNames
                data.store.state.post.imageKey = key
                displayWindow(6)
            }
            const deletePost = () => {
                data.store.state.post.deletePostId = props.postId
                displayWindow(7)
            }
            return { data, responceToPost, displayImage, deletePost }
        }
    }
</script>