<template>
    <div>
        <p style="color: gray;" class="t-c">ログインの必要な機能です</p>
        <button @click="goToLogin()" class="btn btn_normal" style="margin-top: 20px !important;">ログイン</button>
    </div>
</template>

<script>
    import { setOpenFunction, setCloseFunction, createWindow, closeWindow }     from '../../window'
    import { reactive, onMounted }                                              from 'vue'
    import { useRouter }                                                        from 'vue-router'

    export default {
        setup() {
            const data = reactive({
                router: useRouter(),
            })
            const goToLogin = () => {
                closeWindow()
                data.router.push('/login')
            }
            onMounted(() => {
                createWindow('ログインしてください', 500, 250)
            })
            return { goToLogin }
        }
    }
</script>