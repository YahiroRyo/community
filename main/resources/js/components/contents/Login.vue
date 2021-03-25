<template>
    <div @keydown.enter="login">
        <link rel="stylesheet" href="/css/components/login/login.css">
        <h1 class="login__title">ログイン</h1>
        <label class="form-label">メールアドレス</label>
        <input class="form" v-model="data.form.email.content">
        <label class="form-label">パスワード</label>
        <input type="password" class="form" v-model="data.form.password.content">
        <button @click="login" class="form form_btn">ログイン</button>
    </div>
</template>

<script>
    import ***REMOVED*** createAlert, alert, notNormalTokenAlert ***REMOVED***  from '../../alert.js'
    import ***REMOVED*** reactive, onMounted ***REMOVED***                      from 'vue'
    import ***REMOVED*** useStore ***REMOVED***                                 from 'vuex'
    import ***REMOVED*** useRouter ***REMOVED***                                from 'vue-router'
    import firebase                                     from 'firebase'
    
    export default ***REMOVED***
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
            return ***REMOVED*** data, login ***REMOVED***
        ***REMOVED***
    ***REMOVED***
</script>