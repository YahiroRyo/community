<template>
</template>

<script>
    import ***REMOVED*** reactive, onMounted, onBeforeMount ***REMOVED***   from 'vue'
    import ***REMOVED*** antiLoginUser, antiNotLoginUser ***REMOVED***      from '../../router.js'
    import ***REMOVED*** useRouter ***REMOVED***                            from 'vue-router'
    import firebase                                 from 'firebase'

    export default ***REMOVED***
        setup() ***REMOVED***
            const data = reactive(***REMOVED*** router: useRouter(), ***REMOVED***)
            onBeforeMount(() => ***REMOVED***
                antiNotLoginUser()
            ***REMOVED***)
            onMounted(async() => ***REMOVED***
                await firebase.auth().signOut()
                data.router.push('/')
            ***REMOVED***)
        ***REMOVED***
    ***REMOVED***
</script>