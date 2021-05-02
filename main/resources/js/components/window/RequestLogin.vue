<template>
    <div>
        <p style="color: gray;" class="t-c">ログインの必要な機能です</p>
        <button @click="goToLogin()" class="btn btn_normal" style="margin-top: 20px !important;">ログイン</button>
    </div>
</template>

<script>
    import ***REMOVED*** setOpenFunction, setCloseFunction, createWindow, closeWindow ***REMOVED***     from '../../window'
    import ***REMOVED*** reactive, onMounted ***REMOVED***                                              from 'vue'
    import ***REMOVED*** useRouter ***REMOVED***                                                        from 'vue-router'
    import ***REMOVED*** useStore ***REMOVED***                                              from 'vuex'

    export default ***REMOVED***
        setup() ***REMOVED***
            const data = reactive(***REMOVED***
                router: useRouter(),
                store: useStore(),
            ***REMOVED***)
            const goToLogin = () => ***REMOVED***
                closeWindow()
                data.router.push('/login')
            ***REMOVED***
            onMounted(() => ***REMOVED***
                if (data.store.state.windowSize.width <= 414) ***REMOVED***
                    createWindow('ログインしてください', data.store.state.windowSize.width - 10, 250)
                ***REMOVED*** else ***REMOVED***
                    createWindow('ログインしてください', 500, 250)
                ***REMOVED***
            ***REMOVED***)
            return ***REMOVED*** goToLogin ***REMOVED***
        ***REMOVED***
    ***REMOVED***
</script>