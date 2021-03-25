<template>
</template>

<script>
    import { reactive, onMounted }  from 'vue'
    import { useRouter }            from 'vue-router'
    import firebase                 from 'firebase'

    export default {
        setup() {
            const data = reactive({ router: useRouter(), })
            onMounted(async() => {
                await firebase.auth().signOut()
                data.router.push('/')
            })
        }
    }
</script>