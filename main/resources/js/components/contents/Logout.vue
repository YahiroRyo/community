<template>
</template>

<script>
    import ***REMOVED*** reactive, onMounted ***REMOVED***  from 'vue'
    import ***REMOVED*** useRouter ***REMOVED***            from 'vue-router'
    import firebase                 from 'firebase'

    export default ***REMOVED***
        setup() ***REMOVED***
            const data = reactive(***REMOVED*** router: useRouter(), ***REMOVED***)
            onMounted(async() => ***REMOVED***
                await firebase.auth().signOut()
                data.router.push('/')
            ***REMOVED***)
        ***REMOVED***
    ***REMOVED***
</script>