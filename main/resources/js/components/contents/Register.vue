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
    import { alert, createAlert, notNormalTokenAlert }  from '../../alert'
    import { reactive, onMounted }                      from 'vue'
    import { getUidAndToken }                           from '../../supportFirebase.js'
    import { useRouter }                                from 'vue-router'
    import { useStore }                                 from 'vuex'
    import firebase                                     from 'firebase'
    import axios                                        from 'axios'

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
                .then((responce) => {
                })
                .catch(() => {
                    isError = true
                    for (key in data.form) { data.form[key].content = '' }
                    createAlert(new alert('アカウントの作成に失敗しました。', 1))
                })
                if (!isError) {
                    const user = await getUidAndToken()
                    const registerUserInfos = {
                        userName:   data.form.userName.content,
                        token:      user.token,
                        name:       data.form.name.content,
                        uid:        user.uid,
                    }
                    await axios.post('/api/post/register-user', registerUserInfos)
                    .then(async(responce) => {
                        if (!responce.data.isNormalToken) {
                            createAlert(new alert('無効なアクセストークンです。', 2))
                        } else if (!responce.data.isCreateAccount) {
                            createAlert(new alert('アカウントの作成に失敗しました。', 2))
                        } else {
                            // TODO: router.goが動かない
                            data.router.push('/')
                        }
                    })
                }
            }
            return { data, register }
        }
    }
</script>