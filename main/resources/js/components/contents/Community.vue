<template>
    <div>
        <link rel="stylesheet" href="/css/components/community/community.css">
        <!-- コミュニティで投稿する -->
        <div class="community-create-post-wapper">
            <h1 class="community-create-post__title">コミュニティに投稿する</h1>
            <textarea v-model="data.post.content" class="form form_dont-resize"></textarea>
            <!-- 文字数カウント -->
            <p :class="{'form-label-create-post': true, 'form-label_danger': data.post.content.length >= 200 ? true : false, }">{{data.post.content.length}} | 200</p>
            <!-- 画像プレビュー -->
            <div class="create-post-display-img-wapper" v-show="data.post.images.length > 0">
                <transition-group name="create-post-input-img-anim">
                    <div class="create-post-input-img" v-for="(image, key) in data.post.images" :key="key" :style="`background-image: url(${image})`">
                        <button @click="deleteMedia(key)" class="create-post-input-img__btn"><img class="create-post-input-img__btn__img" src="/images/materials/close.svg"></button>
                    </div>
                </transition-group>
            </div>
            <button @click="selectMedia" class="create-post-btn"><img src="/images/materials/media.svg" class="create-post-btn__media-img"></button>
            <input ref="inputFileElement" @change="displayMedia" style="display: none;" type="file" accept="image/*" />
            <button disabled @click="createPost" class="form form_btn">投稿する</button>
        </div>
        <!-- コミュニティの投稿一覧 -->
        <div v-for="(post, key) in data.post.objects" :key="key">
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
        </div>
    </div>
</template>

<script>
    import { reactive, onMounted, ref } from 'vue'
    import { useStore } from 'vuex'
    import { post } from '../../post.js'
    import { createAlert, alert } from '../../alert.js'
    import Post from '../Post.vue'

    export default {
        components: {
            Post,
        },
        setup() {
            const data = reactive({
                store: useStore(),
                post: {
                    objects: [],
                    content: '',
                    images: [],
                },
            })
            const inputFileElement = ref(null)
            const sendGood = (key) => {
                data.post.objects[key].isGood = !data.post.objects[key].isGood
                data.post.objects[key].isGood ? data.post.objects[key].goodNum++ : data.post.objects[key].goodNum--
            }
            const selectMedia = () => {
                // 閉じた際に、clickするとnullをクリックした判定になるため、nullじゃないかチェック
                if (inputFileElement.value.click !== null) { inputFileElement.value.click() }
            }
            const displayMedia = () => {
                if (inputFileElement.value.files.length > 0) {
                    if (data.post.images.length < 4 && inputFileElement.value.files[0].type.match("image.*")) {
                        const fileReader    = new FileReader()
                        fileReader.onload   = (() => { data.post.images.push(fileReader.result) })
                        fileReader.readAsDataURL(inputFileElement.value.files[0])
                    } else {
                        createAlert(new alert('4枚以上の画像を設定できません', 1))
                    }
                }
            }
            const deleteMedia = (key) => { data.post.images.splice(key, 1) }
            onMounted(() => {
                /* ---------------TODO: サーバーからコミュニティの投稿内容を取得するajax処理を実装--------------- */

                // 仮で表示するために値を格納
                for (let i = 0; i < 100; i++)
                    data.post.objects.push(new post('name', 'userName', 'content', true, 1, 5))
            })
            return { data, sendGood, inputFileElement, selectMedia, displayMedia, deleteMedia }
        }
    }
</script>