<template>
    <div>
        <link rel="stylesheet" href="/css/components/post/post.css">
        <label class="form-label">投稿内容を入力</label>
        <textarea v-model="data.post.content" class="form form_dont-resize height-between-small-and-middle"></textarea>
        <!-- 文字数カウント -->
        <p :class="{'form-label-post': true, 'form-label_danger': data.post.content.length >= 200 ? true : false, }">{{data.post.content.length}} | 200</p>
        <!-- 画像プレビュー -->
        <div class="post-display-img-wapper" v-show="data.post.images.length > 0">
            <transition-group name="post-input-img-anim">
                <div class="post-input-img" v-for="(image, key) in data.post.images" :key="key" :style="`background-image: url(${image})`">
                    <button @click="deleteMedia(key)" class="post-input-img__btn"><img class="post-input-img__btn__img" src="/images/materials/close.svg"></button>
                </div>
            </transition-group>
        </div>
        <button @click="selectMedia" class="post-btn"><img src="/images/materials/media.svg" class="post-btn__media-img"></button>
        <input ref="inputFileElement" @change="displayMedia" style="display: none;" type="file" accept="image/*" />
        <button @click="postSend" class="form form_btn">投稿する</button>
    </div>
</template>

<script>
    /* ---------------post(投稿)をする制約-------------- */
    // ・文字数は200字まで
    // ・画像を投稿できるのは4枚まで

    import { reactive, onMounted, ref }         from 'vue'
    import { createWindow, closeWindow }        from '../../window.js'
    import { createAlert, alert }               from '../../alert.js'
    import { useStore }                         from 'vuex';

    export default {
        setup() {
            // ボタンを用いてファイルを選択させたいため、ファイル選択にrefを用いた。
            const inputFileElement = ref(null)
            const data = reactive({
                store: useStore(),
                post: {
                    content: '',
                    images: [],
                },
            })
            const postSend = () => {
                /* ---------------TODO: サーバーに投稿内容を投げるajax処理を実装--------------- */
                closeWindow()
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
            onMounted(() => { createWindow('投稿する', 500, 650) })
            return { data, postSend, selectMedia, inputFileElement, displayMedia, deleteMedia, }
        }
    }
</script>