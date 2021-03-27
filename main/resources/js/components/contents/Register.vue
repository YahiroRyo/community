<template>
    <div>
        <link rel="stylesheet" href="/css/components/register/register.css">
        <div class="form__wapper">
            <h1 class="register__title">アカウント作成</h1>
            <Form v-model:inputContent="data.form.email.content" class="form" label="メールアドレス" uniqueClassKey="1" />
            <Form v-model:inputContent="data.form.name.content" class="form" label="名前" uniqueClassKey="2" />
            <Form v-model:inputContent="data.form.userName.content" class="form" label="ユーザー名" uniqueClassKey="3" />
            <Form v-model:inputContent="data.form.password.content" class="form" label="パスワード" uniqueClassKey="4" />
            <Form v-model:inputContent="data.form.confirmationPassword.content" class="form" label="パスワード確認" uniqueClassKey="5" />
            <button @click="register" class="form__btn">登録</button>
        </div>
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

    /* ---------------コンポーネントをインポート--------------- */
    import Form                                         from '../Form.vue'

    export default {
        components: {
            Form,
        },
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