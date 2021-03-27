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

    export default ***REMOVED***
        setup() ***REMOVED***
            const data = reactive(***REMOVED***
                router: useRouter(),
            ***REMOVED***)
            const goToLogin = () => ***REMOVED***
                closeWindow()
                data.router.push('/login')
            ***REMOVED***
            onMounted(() => ***REMOVED***
                createWindow('ログインしてください', 500, 250)
            ***REMOVED***)
            return ***REMOVED*** goToLogin ***REMOVED***
        ***REMOVED***
    ***REMOVED***
</script>