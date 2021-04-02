<template>
    <div class="show-image-wapper">
        <div class="show-image-background" @click="closeWindow()"></div>
        <button v-if="$store.state.post.imageKey !== 0" @click="$store.state.post.imageKey !== 0 ? $store.state.post.imageKey-- : null" class="to-left-btn">
            <p class="btn__allow">***REMOVED******REMOVED***'<'***REMOVED******REMOVED***</p>
        </button>
        <img    ref="showImage"
                :class="***REMOVED***'show-image': true, 'show-image_more-width': data.width > data.height && !data.changeImage, 'show-image_more-height': data.width < data.height && !data.changeImage, ***REMOVED***"
                :src="`/storage/$***REMOVED***$store.state.post.image[$store.state.post.imageKey].image_name***REMOVED***`">
        <button v-if="$store.state.post.imageKey !== $store.state.post.image.length - 1" @click="$store.state.post.imageKey !== $store.state.post.image.length - 1 ? $store.state.post.imageKey++ : null" class="to-right-btn">
            <p class="btn__allow">***REMOVED******REMOVED***'>'***REMOVED******REMOVED***</p>
        </button>
        <component is="style">
            .window ***REMOVED***
                background-color: transparent !important;
                box-shadow: 0 0 !important;
                width: 100vw !important;
                height: 100vh !important;
            ***REMOVED***
            .window__close-btn ***REMOVED***
                background-color: rgba(0, 0, 0, 0.7);
                border-radius: 50%;
                width: 35px !important;
                height: 35px !important;
                transition: .3s;
                padding: 10px;
            ***REMOVED***
            .window__content ***REMOVED*** height: 90vh !important; ***REMOVED***
            .window__close-btn:hover ***REMOVED*** background-color: rgba(0, 0, 0, 0.9); ***REMOVED***
            .window__title ***REMOVED*** display: none; ***REMOVED***
            .show-image-wapper ***REMOVED*** display: flex; ***REMOVED***
            .show-image ***REMOVED***
                display: block;
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                margin: 0 auto;
            ***REMOVED***
            .show-image_more-width ***REMOVED*** width: 90vw ***REMOVED***
            .show-image_more-height ***REMOVED*** height: 90vh ***REMOVED***
            .show-image-background ***REMOVED***
                position: absolute;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
            ***REMOVED***
            .to-left-btn,
            .to-right-btn ***REMOVED***
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
            ***REMOVED***
            .to-left-btn ***REMOVED*** left: 20px; ***REMOVED***
            .to-right-btn ***REMOVED*** right: 20px; ***REMOVED***
            .btn__allow ***REMOVED***
                color: white;
                font-size: 20px;
                font-weight: bold;
                display: block;
                text-align: center;
                margin: 0 auto;
            ***REMOVED***
            /*
            =====================
            スマホ レスポンシブ
            =====================
            */
            @media screen and (max-width: 414px) ***REMOVED***
                .show-image_more-height ***REMOVED*** height: 50vh ***REMOVED***
            ***REMOVED***
        </component>
    </div>
</template>

<script>
    import ***REMOVED*** setOpenFunction, setCloseFunction, createWindow, closeWindow ***REMOVED*** from '../../window'
    import ***REMOVED*** createAlert, alert, notNormalTokenAlert ***REMOVED***                      from '../../alert'
    import ***REMOVED*** reactive, onMounted, ref, watch ***REMOVED***                              from 'vue'
    import ***REMOVED*** useStore ***REMOVED***                                                     from 'vuex'

    export default ***REMOVED***
        setup() ***REMOVED***
            const data = reactive(***REMOVED***
                store: useStore(),
                width: 0,
                height: 0,
                changeImage: false,
            ***REMOVED***)
            const showImage = ref(null)
            watch(() => data.store.state.post.imageKey, () => ***REMOVED***
                data.changeImage = true
                setTimeout(() => ***REMOVED***
                    data.width = showImage.value.clientWidth
                    data.height = showImage.value.clientHeight
                    data.changeImage = false
                ***REMOVED***, 1)
            ***REMOVED***)
            onMounted(() => ***REMOVED***
                createWindow('投稿画像', 0, 0)
                data.width = showImage.value.clientWidth
                data.height = showImage.value.clientHeight
                createWindow(() => ***REMOVED***
                    data.store.state.post.image = []
                    data.store.state.post.imageKey = null
                ***REMOVED***)
            ***REMOVED***)
            return ***REMOVED*** data, showImage, closeWindow ***REMOVED***
        ***REMOVED***
    ***REMOVED***
</script>