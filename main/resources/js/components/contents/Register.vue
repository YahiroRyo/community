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
        <input class="form" v-model="data.form.password.content">
        <label class="form-label">パスワード確認</label>
        <input class="form" v-model="data.form.confirmationPassword.content">
        <button @click="register" class="form form_btn">登録</button>
    </div>
</template>

<script>
    import ***REMOVED*** reactive, onMounted ***REMOVED*** from 'vue'
    import ***REMOVED*** alert, createAlert ***REMOVED***   from '../../alert';
    import firebase from 'firebase'
    import axios from 'axios'

    export default ***REMOVED***
        setup() ***REMOVED***
            const data = reactive(***REMOVED***
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
                .then(async(responce) => ***REMOVED***
                    // uidをローカルストレージに保存
                    localStorage.setItem('uid', responce.user.uid)
                    // idTokenを取得
                    await firebase.auth().currentUser.getIdTokenResult()
                    .then((idTokenResult) => ***REMOVED***
                        // idTokenをローカルストレージに保存
                        localStorage.setItem('token', idTokenResult.token)
                    ***REMOVED***)
                    .catch(async() => ***REMOVED***
                        // アクセストークンの取得に失敗した場合はログアウト
                        createAlert(new alert('アクセストークンの取得に失敗しました。', 2))
                        await firebase.auth().signOut()
                    ***REMOVED***)
                ***REMOVED***)
                .catch(() => ***REMOVED***
                    isError = true
                    for (key in data.form) ***REMOVED*** data.form[key].content = '' ***REMOVED***
                    createAlert(new alert('アカウントの作成に失敗しました。', 1))
                ***REMOVED***)
                if (!isError) ***REMOVED***
                    const registerUserInfos = ***REMOVED***
                        token: localStorage.getItem('token'),
                        uid: localStorage.getItem('uid'),
                        name: data.form.name.content,
                        userName: data.form.userName.content,
                    ***REMOVED***
                    axios.post('/api/post/register-user', registerUserInfos)
                    .then((responce) => ***REMOVED***
                        if (!responce.data.isNormalToken) ***REMOVED***
                            createAlert(new alert('無効なアクセストークンです。', 2))
                        ***REMOVED*** else if (!responce.data.isCreateAccount) ***REMOVED***
                            createAlert(new alert('アカウントの作成に失敗しました。', 2))
                        ***REMOVED*** else ***REMOVED***
                            // ログイン
                            firebase.auth().onAuthStateChanged(async(user) => ***REMOVED***
                                if (user) ***REMOVED***
                                    data.store.state.user.isLogin = true
                                    data.router.push('/')
                                ***REMOVED*** else ***REMOVED***
                                    data.store.state.user.isLogin = false
                                    data.router.push('/login')
                                ***REMOVED***
                            ***REMOVED***)
                        ***REMOVED***
                    ***REMOVED***)
                ***REMOVED***
            ***REMOVED***
            return ***REMOVED*** data, register ***REMOVED***
        ***REMOVED***
    ***REMOVED***
</script>