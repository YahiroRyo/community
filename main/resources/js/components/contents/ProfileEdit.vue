<template>
    <div class="form__wapper">
        <link rel="stylesheet" href="/css/components/profileEdit/profileEdit.css">
        <h1 class="profile-edit__title">プロフィールを編集</h1>
        <Form @click="data.user.name.isClick = true" v-model:inputContent="validate.name.$model" class="form" label="名前" uniqueClassKey="1" 
            :validate="validate.name.$errors.length > 0 && data.user.name.isClick"
            :error="validate.name.required.$invalid ? '名前は必須入力です。' :
                    validate.name.maxLength.$invalid ? '30文字を超過しています。' : null"
        />
        <Form @click="data.user.userName.isClick = true" v-model:inputContent="validate.userName.$model" class="form" label="ユーザー名" uniqueClassKey="2"
            :validate="validate.userName.$errors.length > 0 && data.user.userName.isClick"
            :error="validate.userName.required.$invalid ? 'ユーザー名は必須入力です。' :
                    validate.userName.alphaNum.$invalid ? 'アルファベットと数字のみを入力ください。' :
                    validate.userName.maxLength.$invalid ? '30文字を超過しています。' : null"
        />
        <Form @click="data.user.intro.isClick = true" v-model:inputContent="validate.intro.$model" :useTextArea="true" class="form" label="自己紹介" uniqueClassKey="3"
            :validate="validate.intro.$errors.length > 0 && data.user.intro.isClick"
            :error="validate.intro.maxLength.$invalid ? '200文字を超過しています。' : null"
        />
        <button :disabled="validate.$invalid" @click="refreshUserData" class="form__btn">保存</button>
    </div>
</template>

<script>
    /* ---------------ProfileEditについて--------------- */
    // ・何らかのエラーが出た場合
    // alertを表示させた後、data.router.push('/profile')を実行

    import ***REMOVED*** reactive, onMounted, onBeforeMount, toRef ***REMOVED***           from 'vue'
    import ***REMOVED*** alert, createAlert, notNormalTokenAlert ***REMOVED***      from '../../alert'
    import ***REMOVED*** antiLoginUser, antiNotLoginUser ***REMOVED***              from '../../router.js'
    import ***REMOVED*** getUidAndToken ***REMOVED***                               from '../../supportFirebase.js'
    import ***REMOVED*** useRouter ***REMOVED***                                    from 'vue-router'
    import ***REMOVED*** useStore ***REMOVED***                                     from 'vuex'
    import firebase                                         from 'firebase'
    import axios                                            from 'axios'
    /* ---------------validation関係--------------- */
    import ***REMOVED*** required, email, minLength, maxLength, alphaNum,  ***REMOVED*** from "@vuelidate/validators"
    import ***REMOVED*** useVuelidate ***REMOVED*** from "@vuelidate/core"
    
    /* ---------------コンポーネントをインポート--------------- */
    import Form                                             from '../Form.vue'

    export default ***REMOVED***
        components: ***REMOVED***
            Form
        ***REMOVED***,
        setup() ***REMOVED***
            const data = reactive(***REMOVED***
                router: useRouter(),
                store: useStore(),
                user: ***REMOVED***
                    userName: ***REMOVED***
                        content: '',
                        isClick: false,
                    ***REMOVED***,
                    name: ***REMOVED***
                        content: '',
                        isClick: false,
                    ***REMOVED***,
                    intro: ***REMOVED***
                        content: '',
                        isClick: false,
                    ***REMOVED***,
                ***REMOVED***,
            ***REMOVED***)
            // ユーザーデータの取得
            // uidを投げたらユーザー情報が返ってくる
            const getUserData = async() => ***REMOVED***
                const user = await getUidAndToken()
                if (!user.isError) ***REMOVED***
                    const userProfileInfos = ***REMOVED***
                        params: ***REMOVED*** 'uid': user.uid, ***REMOVED***,
                    ***REMOVED***
                    axios.get('/api/get/my-user-data', userProfileInfos)
                    .then((responce) => ***REMOVED***
                        if (responce.data.isGetMyUserData) ***REMOVED***
                            data.user.name.content      = responce.data.userData.name
                            data.user.userName.content  = responce.data.userData.user_name
                            data.user.intro.content     = responce.data.userData.intro
                        ***REMOVED*** else ***REMOVED***
                            createAlert(new alert('ユーザーデータの取得に失敗しました。', 2))
                        ***REMOVED***
                    ***REMOVED***)
                    .catch(() => ***REMOVED***
                        createAlert(new alert('ユーザーデータの取得に失敗しました。', 2))
                        // 失敗した場合は、プロフィールに飛ぶ
                        setTimeout(() => ***REMOVED***
                            data.router.push('/profile')
                        ***REMOVED***, 50)
                    ***REMOVED***)
                ***REMOVED*** else ***REMOVED***
                    createAlert(new alert('ユーザー情報を取得することに失敗しました。', 2))
                    // 失敗した場合は、プロフィールに飛ぶ
                    setTimeout(() => ***REMOVED***
                        data.router.push('/profile')
                    ***REMOVED***, 50)
                ***REMOVED***
            ***REMOVED***
            // ユーザー情報を更新する
            const refreshUserData = async() => ***REMOVED***
                if (validate.value.$invalid) ***REMOVED***
                    createAlert(new alert('不正な値です。', 2))
                    return
                ***REMOVED***
                const user = await getUidAndToken()
                const refreshUserProfileInfos = ***REMOVED***
                    userName:   data.user.userName.content,
                    token:      user.token,
                    intro:      data.user.intro.content,
                    name:       data.user.name.content,
                    uid:        user.uid,
                ***REMOVED***
                axios.post('/api/post/refresh-user-profile', refreshUserProfileInfos)
                .then((responce) => ***REMOVED***
                    if (responce.data.isRefreshAccount) ***REMOVED***
                        createAlert(new alert('ユーザデータを更新しました。', 0))
                    ***REMOVED*** else if (!responce.data.isNormalToken) ***REMOVED***
                        notNormalTokenAlert()
                    ***REMOVED*** else ***REMOVED***
                        createAlert(new alert('ユーザデータを更新することができませんでした。', 2))
                        data.router.push('/')
                        return
                    ***REMOVED***
                    data.store.state.user.userName = data.user.userName.content
                    data.store.state.user.profileUpdate = true
                    data.router.push(`/profile/$***REMOVED***data.user.userName.content***REMOVED***`)
                ***REMOVED***)
            ***REMOVED***
            const rules = ***REMOVED***
                name:                   ***REMOVED*** required, maxLength: maxLength(30), ***REMOVED***,
                userName:               ***REMOVED*** required, alphaNum, maxLength: maxLength(30), ***REMOVED***,
                intro:                  ***REMOVED*** maxLength: maxLength(200), ***REMOVED***
            ***REMOVED***
            const validate = useVuelidate(rules, ***REMOVED***
                name:                   toRef(data.user.name, 'content'),
                userName:               toRef(data.user.userName, 'content'),
                intro:                  toRef(data.user.intro, 'content'),
            ***REMOVED***)
            onBeforeMount(() => ***REMOVED***
                antiNotLoginUser()
                validate.value.$touch()
            ***REMOVED***)
            onMounted(() => ***REMOVED*** getUserData() ***REMOVED***)
            return ***REMOVED*** data, refreshUserData, validate ***REMOVED***
        ***REMOVED***
    ***REMOVED***
</script>