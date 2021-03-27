<template>
</template>

<script>
    import { reactive, onMounted, onBeforeMount }   from 'vue'
    import { antiLoginUser, antiNotLoginUser }      from '../../router.js'
    import { useRouter }                            from 'vue-router'
    import firebase                                 from 'firebase'

    export default {
        setup() {
            const data = reactive({ router: useRouter(), })
            onBeforeMount(() => {
                antiNotLoginUser()
            })
            onMounted(async() => {
                await firebase.auth().signOut()
                data.router.push('/')
            })
        }
    }
</script>