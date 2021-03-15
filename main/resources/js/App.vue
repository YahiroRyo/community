<template>
    <link rel="stylesheet" href="/css/base.css">
    <header id="header">
    </header>
    <main>
    </main>
    <footer id="footer">
    </footer>
</template>

<script>
    import { reactive } from 'vue'
    import { useStore } from 'vuex'
    import { useRouter, useRoute } from 'vue-router'

    export default {
        setup() {
            const data = reactive({
                store: useStore(),
                router: useRouter(),
                route: useRoute(),
            })
            return { data }
        }
    }
</script>