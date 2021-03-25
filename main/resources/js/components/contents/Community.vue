<template>
    <div>
        <link rel="stylesheet" href="/css/components/community/community.css">
        <!-- コミュニティで投稿する -->
        <div class="community-create-post-wapper">
            <h1 class="community-create-post__title">コミュニティに投稿する</h1>
            <textarea v-model="data.post.content" class="form form_dont-resize"></textarea>
            <!-- 文字数カウント -->
            <p :class="***REMOVED***'form-label-create-post': true, 'form-label_danger': data.post.content.length >= 200 ? true : false, ***REMOVED***">***REMOVED******REMOVED***data.post.content.length***REMOVED******REMOVED*** | 200</p>
            <!-- 画像プレビュー -->
            <div class="create-post-display-img-wapper" v-show="data.post.images.length > 0">
                <transition-group name="create-post-input-img-anim">
                    <div class="create-post-input-img" v-for="(image, key) in data.post.images" :key="key" :style="`background-image: url($***REMOVED***image***REMOVED***)`">
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
    import ***REMOVED*** reactive, onMounted, ref ***REMOVED*** from 'vue'
    import ***REMOVED*** createAlert, alert ***REMOVED***       from '../../alert.js'
    import ***REMOVED*** useStore ***REMOVED***                 from 'vuex'
    import ***REMOVED*** post ***REMOVED***                     from '../../post.js'
    import Post                         from '../Post.vue'

    export default ***REMOVED***
        components: ***REMOVED*** Post ***REMOVED***,
        setup() ***REMOVED***
            const data = reactive(***REMOVED***
                store: useStore(),
                post: ***REMOVED***
                    objects:    [],
                    content:    '',
                    images:     [],
                ***REMOVED***,
            ***REMOVED***)
            const inputFileElement = ref(null)
            const sendGood = (key) => ***REMOVED***
                data.post.objects[key].isGood = !data.post.objects[key].isGood
                data.post.objects[key].isGood ? data.post.objects[key].goodNum++ : data.post.objects[key].goodNum--
            ***REMOVED***
            const selectMedia = () => ***REMOVED***
                // 閉じた際に、clickするとnullをクリックした判定になるため、nullじゃないかチェック
                if (inputFileElement.value.click !== null) ***REMOVED*** inputFileElement.value.click() ***REMOVED***
            ***REMOVED***
            const displayMedia = () => ***REMOVED***
                if (inputFileElement.value.files.length > 0) ***REMOVED***
                    if (data.post.images.length < 4 && inputFileElement.value.files[0].type.match("image.*")) ***REMOVED***
                        const fileReader    = new FileReader()
                        fileReader.onload   = (() => ***REMOVED*** data.post.images.push(fileReader.result) ***REMOVED***)
                        fileReader.readAsDataURL(inputFileElement.value.files[0])
                    ***REMOVED*** else ***REMOVED***
                        createAlert(new alert('4枚以上の画像を設定できません', 1))
                    ***REMOVED***
                ***REMOVED***
            ***REMOVED***
            const deleteMedia = (key) => ***REMOVED*** data.post.images.splice(key, 1) ***REMOVED***
            onMounted(() => ***REMOVED***
                /* ---------------TODO: サーバーからコミュニティの投稿内容を取得するajax処理を実装--------------- */

                // 仮で表示するために値を格納
                for (let i = 0; i < 100; i++)
                    data.post.objects.push(new post('name', 'userName', 'content', true, 1, 5))
            ***REMOVED***)
            return ***REMOVED*** data, sendGood, inputFileElement, selectMedia, displayMedia, deleteMedia ***REMOVED***
        ***REMOVED***
    ***REMOVED***
</script>