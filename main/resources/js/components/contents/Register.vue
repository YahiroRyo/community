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
    import { reactive, onMounted }  from 'vue'
    import { useStore }             from 'vuex'
    import { useRouter }            from 'vue-router'
    import { alert, createAlert, notNormalTokenAlert }   from '../../alert'
    import firebase                 from 'firebase'
    import axios                    from 'axios'

    export default {
        setup() {
            const data = reactive({
                store: useStore(),
                router: useRouter(),
                form: {
                    email: {
                        content: '',
                    },
                    name: {
                        content: '',
                    },
                    userName: {
                        content: '',
                    },
                    password: {
                        content: '',
                    },
                    confirmationPassword: {
                        content: '',
                    },
                },
            })
            const register = async() => {
                let isError = false
                // firebaseアカウントを作成
                await firebase.auth().createUserWithEmailAndPassword(data.form.email.content, data.form.password.content)
                .then(async(responce) => {
                    // uidをローカルストレージに保存
                    localStorage.setItem('uid', responce.user.uid)
                    // idTokenを取得
                    await firebase.auth().currentUser.getIdTokenResult()
                    .then((idTokenResult) => {
                        // idTokenをローカルストレージに保存
                        localStorage.setItem('token', idTokenResult.token)
                        data.router.push('/')
                    })
                    .catch(async() => {
                        // アクセストークンの取得に失敗した場合はログアウト
                        notNormalTokenAlert()
                    })
                })
                .catch(() => {
                    isError = true
                    for (key in data.form) { data.form[key].content = '' }
                    createAlert(new alert('アカウントの作成に失敗しました。', 1))
                })
                if (!isError) {
                    const registerUserInfos = {
                        token: localStorage.getItem('token'),
                        uid: localStorage.getItem('uid'),
                        name: data.form.name.content,
                        userName: data.form.userName.content,
                    }
                    axios.post('/api/post/register-user', registerUserInfos)
                    .then((responce) => {
                        if (!responce.data.isNormalToken) {
                            createAlert(new alert('無効なアクセストークンです。', 2))
                        } else if (!responce.data.isCreateAccount) {
                            createAlert(new alert('アカウントの作成に失敗しました。', 2))
                        } else {
                            // ログイン
                            firebase.auth().onAuthStateChanged(async(user) => {
                                if (user) {
                                    data.store.state.user.isLogin = true
                                    data.router.push('/')
                                } else {
                                    data.store.state.user.isLogin = false
                                    data.router.push('/login')
                                }
                            })
                        }
                    })
                }
            }
            return { data, register }
        }
    }
</script>