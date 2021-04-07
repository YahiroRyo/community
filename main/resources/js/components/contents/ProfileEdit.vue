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

    import { reactive, onMounted, onBeforeMount, toRef }           from 'vue'
    import { alert, createAlert, notNormalTokenAlert }      from '../../alert'
    import { antiLoginUser, antiNotLoginUser }              from '../../router.js'
    import { getUidAndToken }                               from '../../supportFirebase.js'
    import { useRouter }                                    from 'vue-router'
    import { useStore }                                     from 'vuex'
    import firebase                                         from 'firebase'
    import axios                                            from 'axios'
    /* ---------------validation関係--------------- */
    import { required, email, minLength, maxLength, alphaNum,  } from "@vuelidate/validators"
    import { useVuelidate } from "@vuelidate/core"
    
    /* ---------------コンポーネントをインポート--------------- */
    import Form                                             from '../Form.vue'

    export default {
        components: {
            Form
        },
        setup() {
            const data = reactive({
                router: useRouter(),
                store: useStore(),
                user: {
                    userName: {
                        content: '',
                        isClick: false,
                    },
                    name: {
                        content: '',
                        isClick: false,
                    },
                    intro: {
                        content: '',
                        isClick: false,
                    },
                },
            })
            // ユーザーデータの取得
            // uidを投げたらユーザー情報が返ってくる
            const getUserData = async() => {
                const user = await getUidAndToken()
                if (!user.isError) {
                    const userProfileInfos = {
                        params: { 'uid': user.uid, },
                    }
                    axios.get('/api/get/my-user-data', userProfileInfos)
                    .then((responce) => {
                        if (responce.data.isGetMyUserData) {
                            data.user.name.content      = responce.data.userData.name
                            data.user.userName.content  = responce.data.userData.user_name
                            data.user.intro.content     = responce.data.userData.intro
                        } else {
                            createAlert(new alert('ユーザーデータの取得に失敗しました。', 2))
                        }
                    })
                    .catch(() => {
                        createAlert(new alert('ユーザーデータの取得に失敗しました。', 2))
                        // 失敗した場合は、プロフィールに飛ぶ
                        setTimeout(() => {
                            data.router.push('/profile')
                        }, 50)
                    })
                } else {
                    createAlert(new alert('ユーザー情報を取得することに失敗しました。', 2))
                    // 失敗した場合は、プロフィールに飛ぶ
                    setTimeout(() => {
                        data.router.push('/profile')
                    }, 50)
                }
            }
            // ユーザー情報を更新する
            const refreshUserData = async() => {
                if (validate.value.$invalid) {
                    createAlert(new alert('不正な値です。', 2))
                    return
                }
                const user = await getUidAndToken()
                const refreshUserProfileInfos = {
                    userName:   data.user.userName.content,
                    token:      user.token,
                    intro:      data.user.intro.content,
                    name:       data.user.name.content,
                    uid:        user.uid,
                }
                axios.post('/api/post/refresh-user-profile', refreshUserProfileInfos)
                .then((responce) => {
                    if (responce.data.isRefreshAccount) {
                        createAlert(new alert('ユーザデータを更新しました。', 0))
                    } else if (!responce.data.isNormalToken) {
                        notNormalTokenAlert()
                    } else {
                        createAlert(new alert('ユーザデータを更新することができませんでした。', 2))
                        data.router.push('/')
                        return
                    }
                    data.store.state.user.userName = data.user.userName.content
                    data.store.state.user.profileUpdate = true
                    data.router.push(`/profile/${data.user.userName.content}`)
                })
            }
            const rules = {
                name:                   { required, maxLength: maxLength(30), },
                userName:               { required, alphaNum, maxLength: maxLength(30), },
                intro:                  { maxLength: maxLength(200), }
            }
            const validate = useVuelidate(rules, {
                name:                   toRef(data.user.name, 'content'),
                userName:               toRef(data.user.userName, 'content'),
                intro:                  toRef(data.user.intro, 'content'),
            })
            onBeforeMount(() => {
                antiNotLoginUser()
                validate.value.$touch()
            })
            onMounted(() => { getUserData() })
            return { data, refreshUserData, validate }
        }
    }
</script>