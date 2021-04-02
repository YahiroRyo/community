<template>
    <div>
        <link rel="stylesheet" href="/css/components/register/register.css">
        <div class="form__wapper">
            <h1 class="register__title">アカウント作成</h1>
            <Form @click="data.form.email.isClick = true" v-model:inputContent="validate.email.$model" class="form" label="メールアドレス" uniqueClassKey="1" 
                :validate="validate.email.$errors.length > 0 && data.form.email.isClick"
                :error="validate.email.required.$invalid ? 'メールアドレスは必須入力です。' :
                        validate.email.email.$invalid ? '正常なメールアドレスではありません。' : null"
            />

            <Form @click="data.form.name.isClick = true" v-model:inputContent="validate.name.$model" class="form" label="名前" uniqueClassKey="2" 
                :validate="validate.name.$errors.length > 0 && data.form.name.isClick"
                :error="validate.name.required.$invalid ? '名前は必須入力です。' :
                        validate.name.maxLength.$invalid ? '30文字を超過しています。' : null"
            />

            <Form @click="data.form.userName.isClick = true" v-model:inputContent="validate.userName.$model" class="form" label="ユーザー名" uniqueClassKey="3"
                :validate="validate.userName.$errors.length > 0 && data.form.userName.isClick"
                :error="validate.userName.required.$invalid ? 'ユーザー名は必須入力です。' :
                        validate.userName.alphaNum.$invalid ? 'アルファベットと数字のみを入力ください。' :
                        validate.userName.maxLength.$invalid ? '30文字を超過しています。' : null"
            />

            <Form @click="data.form.password.isClick = true" v-model:inputContent="validate.password.$model" class="form" label="パスワード" uniqueClassKey="4" type="password"
                :validate="validate.password.$errors.length > 0 && data.form.name.isClick"
                :error="validate.password.required.$invalid ? 'パスワードは必須入力です。' :
                        validate.password.minLength.$invalid ? '4文字以上のパスワードを設定下さい。' : null"
            />

            <Form @click="data.form.confirmationPassword.isClick = true" v-model:inputContent="validate.confirmationPassword.$model" class="form" label="パスワード確認" uniqueClassKey="5" type="password"
                :validate="validate.confirmationPassword.$errors.length > 0 && data.form.confirmationPassword.isClick"
                :error="validate.confirmationPassword.required.$invalid ? 'パスワード確認は必須入力です。' :
                        validate.confirmationPassword.confirmation.$invalid ? 'パスワードと一致しません' : null"
            />
            <button :disabled="validate.$invalid" @click="register" class="form__btn">登録</button>
        </div>
    </div>
</template>

<script>
    import ***REMOVED*** reactive, onMounted, onBeforeMount, toRef, watch ***REMOVED***from 'vue'
    import ***REMOVED*** alert, createAlert, notNormalTokenAlert ***REMOVED***  from '../../alert'
    import ***REMOVED*** antiLoginUser, antiNotLoginUser ***REMOVED***          from '../../router.js'
    import ***REMOVED*** getUidAndToken ***REMOVED***                           from '../../supportFirebase.js'
    import ***REMOVED*** useRouter ***REMOVED***                                from 'vue-router'
    import ***REMOVED*** useStore ***REMOVED***                                 from 'vuex'
    import firebase                                     from 'firebase'
    import axios                                        from 'axios'

    /* ---------------validation関係--------------- */
    import ***REMOVED*** required, email, minLength, maxLength, alphaNum,  ***REMOVED*** from "@vuelidate/validators"
    import ***REMOVED*** useVuelidate ***REMOVED*** from "@vuelidate/core"

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
                        isClick: false,
                        content: '',
                    ***REMOVED***,
                    name: ***REMOVED***
                        isClick: false,
                        content: '',
                    ***REMOVED***,
                    userName: ***REMOVED***
                        isClick: false,
                        content: '',
                    ***REMOVED***,
                    password: ***REMOVED***
                        isClick: false,
                        content: '',
                    ***REMOVED***,
                    confirmationPassword: ***REMOVED***
                        isClick: false,
                        content: '',
                    ***REMOVED***,
                ***REMOVED***,
            ***REMOVED***)
            const rules = ***REMOVED***
                email:                  ***REMOVED*** required, email, ***REMOVED***,
                name:                   ***REMOVED*** required, maxLength: maxLength(30), ***REMOVED***,
                userName:               ***REMOVED*** required, alphaNum, maxLength: maxLength(30), ***REMOVED***,
                password:               ***REMOVED*** required, minLength: minLength(4), ***REMOVED***,
                confirmationPassword:   ***REMOVED*** required, confirmation: () => ***REMOVED*** return data.form.confirmationPassword.content === data.form.password.content ***REMOVED***, ***REMOVED***,
            ***REMOVED***
            const validate = useVuelidate(rules, ***REMOVED***
                email:                  toRef(data.form.email, 'content'),
                name:                   toRef(data.form.name, 'content'),
                userName:               toRef(data.form.userName, 'content'),
                password:               toRef(data.form.password, 'content'),
                confirmationPassword:   toRef(data.form.confirmationPassword, 'content'),
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
                validate.value.$touch()
            ***REMOVED***)
            return ***REMOVED*** data, register, validate ***REMOVED***
        ***REMOVED***
    ***REMOVED***
</script>