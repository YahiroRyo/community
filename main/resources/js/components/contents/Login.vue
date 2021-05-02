<template>
    <div @keydown.enter="login">
        <link rel="stylesheet" href="/css/components/login/login.css">
        <div class="form__wapper">
            <h1 class="login__title">ログイン</h1>
            <Form class="form" v-model:inputContent="data.form.email.content" label="メールアドレス" uniqueClassKey="1" />
            <Form class="form" v-model:inputContent="data.form.password.content" type="password" label="パスワード" uniqueClassKey="2" />
            <button @click="login" class="form__btn">ログイン</button>
        </div>
    </div>
</template>

<script>
    import ***REMOVED*** createAlert, alert, notNormalTokenAlert ***REMOVED***  from '../../alert.js'
    import ***REMOVED*** antiLoginUser, antiNotLoginUser ***REMOVED***          from '../../router.js'
    import ***REMOVED*** reactive, onBeforeMount, ref ***REMOVED***             from 'vue'
    import ***REMOVED*** useRouter ***REMOVED***                                from 'vue-router'
    import ***REMOVED*** useStore ***REMOVED***                                 from 'vuex'
    import firebase                                     from 'firebase'

    /* ---------------コンポーネントをインポート--------------- */
    import Form                                         from '../Form.vue'
    
    export default ***REMOVED***
        components: ***REMOVED***
            Form
        ***REMOVED***,
        setup() ***REMOVED***
            const data = reactive(***REMOVED***
                store: useStore(),
                router: useRouter(),
                form: ***REMOVED***
                    email: ***REMOVED***
                        content: '',
                    ***REMOVED***,
                    password: ***REMOVED***
                        content: '',
                    ***REMOVED***,
                ***REMOVED***,
            ***REMOVED***)
            const login = async() => ***REMOVED***
                await firebase.auth().signInWithEmailAndPassword(data.form.email.content, data.form.password.content)
                .then(() => ***REMOVED***
                    createAlert(new alert('ログインしました。', 0))
                    data.router.push('/')
                ***REMOVED***)
                .catch(() => ***REMOVED***
                    createAlert(new alert('ログインに失敗しました。', 2))
                    data.form.password.content = ''
                ***REMOVED***)
            ***REMOVED***
            onBeforeMount(() => ***REMOVED***
                antiLoginUser()
            ***REMOVED***)
            return ***REMOVED*** data, login ***REMOVED***
        ***REMOVED***
    ***REMOVED***
</script>