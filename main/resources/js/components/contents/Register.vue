<template>
    <div>
        <link rel="stylesheet" href="/css/components/register/register.css">
        <h1 class="register__title">アカウント作成</h1>
        <label class="form-label">メールアドレス</label>
        <input class="form" v-model="data.form.email.content" placeholder="例: info@example.com">
        <label class="form-label">名前</label>
        <input class="form" v-model="data.form.name.content">
        <label class="form-label">ユーザー名</label>
        <input class="form" v-model="data.form.userName.content">
        <label class="form-label">パスワード</label>
        <input type="password" class="form" v-model="data.form.password.content">
        <label class="form-label">パスワード確認</label>
        <input type="password" class="form" v-model="data.form.confirmationPassword.content">
        <button @click="register" class="form form_btn">登録</button>
    </div>
</template>

<script>
    import ***REMOVED*** alert, createAlert, notNormalTokenAlert ***REMOVED***  from '../../alert'
    import ***REMOVED*** reactive, onMounted ***REMOVED***                      from 'vue'
    import ***REMOVED*** getUidAndToken ***REMOVED***                           from '../../supportFirebase.js'
    import ***REMOVED*** useRouter ***REMOVED***                                from 'vue-router'
    import ***REMOVED*** useStore ***REMOVED***                                 from 'vuex'
    import firebase                                     from 'firebase'
    import axios                                        from 'axios'

    export default ***REMOVED***
        setup() ***REMOVED***
            const data = reactive(***REMOVED***
                store: useStore(),
                router: useRouter(),
                form: ***REMOVED***
                    email: ***REMOVED***
                        content: '',
                    ***REMOVED***,
                    name: ***REMOVED***
                        content: '',
                    ***REMOVED***,
                    userName: ***REMOVED***
                        content: '',
                    ***REMOVED***,
                    password: ***REMOVED***
                        content: '',
                    ***REMOVED***,
                    confirmationPassword: ***REMOVED***
                        content: '',
                    ***REMOVED***,
                ***REMOVED***,
            ***REMOVED***)
            const register = async() => ***REMOVED***
                let isError = false
                // firebaseアカウントを作成
                await firebase.auth().createUserWithEmailAndPassword(data.form.email.content, data.form.password.content)
                .then((responce) => ***REMOVED***
                ***REMOVED***)
                .catch(() => ***REMOVED***
                    isError = true
                    for (key in data.form) ***REMOVED*** data.form[key].content = '' ***REMOVED***
                    createAlert(new alert('アカウントの作成に失敗しました。', 1))
                ***REMOVED***)
                if (!isError) ***REMOVED***
                    const user = await getUidAndToken()
                    const registerUserInfos = ***REMOVED***
                        userName:   data.form.userName.content,
                        token:      user.token,
                        name:       data.form.name.content,
                        uid:        user.uid,
                    ***REMOVED***
                    await axios.post('/api/post/register-user', registerUserInfos)
                    .then(async(responce) => ***REMOVED***
                        if (!responce.data.isNormalToken) ***REMOVED***
                            createAlert(new alert('無効なアクセストークンです。', 2))
                        ***REMOVED*** else if (!responce.data.isCreateAccount) ***REMOVED***
                            createAlert(new alert('アカウントの作成に失敗しました。', 2))
                        ***REMOVED*** else ***REMOVED***
                            // TODO: router.goが動かない
                            data.router.push('/')
                        ***REMOVED***
                    ***REMOVED***)
                ***REMOVED***
            ***REMOVED***
            return ***REMOVED*** data, register ***REMOVED***
        ***REMOVED***
    ***REMOVED***
</script>