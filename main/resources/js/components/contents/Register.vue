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
    import ***REMOVED*** alert, createAlert, notNormalTokenAlert ***REMOVED***  from '../../alert'
    import ***REMOVED*** reactive, onMounted, onBeforeMount ***REMOVED***       from 'vue'
    import ***REMOVED*** antiLoginUser, antiNotLoginUser ***REMOVED***          from '../../router.js'
    import ***REMOVED*** getUidAndToken ***REMOVED***                           from '../../supportFirebase.js'
    import ***REMOVED*** useRouter ***REMOVED***                                from 'vue-router'
    import ***REMOVED*** useStore ***REMOVED***                                 from 'vuex'
    import firebase                                     from 'firebase'
    import axios                                        from 'axios'

    /* ---------------コンポーネントをインポート--------------- */
    import Form                                         from '../Form.vue'

    export default ***REMOVED***
        components: ***REMOVED***
            Form,
        ***REMOVED***,
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
                            data.router.go('/')
                        ***REMOVED***
                    ***REMOVED***)
                ***REMOVED***
            ***REMOVED***
            onBeforeMount(() => ***REMOVED***
                antiLoginUser()
            ***REMOVED***)
            return ***REMOVED*** data, register ***REMOVED***
        ***REMOVED***
    ***REMOVED***
</script>