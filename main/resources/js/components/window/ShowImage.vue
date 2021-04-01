<template>
    <div class="show-image-wapper">
        <div class="show-image-background" @click="closeWindow()"></div>
        <button v-if="$store.state.post.imageKey !== 0" @click="$store.state.post.imageKey !== 0 ? $store.state.post.imageKey-- : null" class="to-left-btn">
            <p class="btn__allow">{{'<'}}</p>
        </button>
        <img    ref="showImage"
                :class="{'show-image': true, 'show-image_more-width': data.width > data.height && !data.changeImage, 'show-image_more-height': data.width < data.height && !data.changeImage, }"
                :src="`/storage/${$store.state.post.image[$store.state.post.imageKey].image_name}`">
        <button v-if="$store.state.post.imageKey !== $store.state.post.image.length - 1" @click="$store.state.post.imageKey !== $store.state.post.image.length - 1 ? $store.state.post.imageKey++ : null" class="to-right-btn">
            <p class="btn__allow">{{'>'}}</p>
        </button>
        <component is="style">
            .window {
                background-color: transparent !important;
                box-shadow: 0 0 !important;
                width: 100vw !important;
                height: 100vh !important;
            }
            .window__close-btn {
                background-color: rgba(0, 0, 0, 0.7);
                border-radius: 50%;
                width: 35px !important;
                height: 35px !important;
                transition: .3s;
                padding: 10px;
            }
            .window__content { height: 90vh !important; }
            .window__close-btn:hover { background-color: rgba(0, 0, 0, 0.9); }
            .window__title { display: none; }
            .show-image-wapper { display: flex; }
            .show-image {
                display: block;
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                margin: 0 auto;
            }
            .show-image_more-width { width: 90vw }
            .show-image_more-height { height: 90vh }
            .show-image-background {
                position: absolute;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
            }
            .to-left-btn,
            .to-right-btn {
                align-items: center;
                background-color: rgba(0, 0, 0, 0.2);
                border-radius: 50%;
                display: flex;
                height: 50px;
                width: 50px;
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                user-select: none;
                z-index: 3;
            }
            .to-left-btn { left: 20px; }
            .to-right-btn { right: 20px; }
            .btn__allow {
                color: white;
                font-size: 20px;
                font-weight: bold;
                display: block;
                text-align: center;
                margin: 0 auto;
            }
        </component>
    </div>
</template>

<script>
    import { setOpenFunction, setCloseFunction, createWindow, closeWindow } from '../../window'
    import { createAlert, alert, notNormalTokenAlert }                      from '../../alert'
    import { reactive, onMounted, ref, watch }                              from 'vue'
    import { useStore }                                                     from 'vuex'

    export default {
        setup() {
            const data = reactive({
                store: useStore(),
                width: 0,
                height: 0,
                changeImage: false,
            })
            const showImage = ref(null)
            watch(() => data.store.state.post.imageKey, () => {
                data.changeImage = true
                setTimeout(() => {
                    data.width = showImage.value.clientWidth
                    data.height = showImage.value.clientHeight
                    data.changeImage = false
                }, 1)
            })
            onMounted(() => {
                createWindow('投稿画像', 0, 0)
                data.width = showImage.value.clientWidth
                data.height = showImage.value.clientHeight
                createWindow(() => {
                    data.store.state.post.image = []
                    data.store.state.post.imageKey = null
                })
            })
            return { data, showImage, closeWindow }
        }
    }
</script>