<template>
    <div>
        <link rel="stylesheet" href="/css/components/profileEdit/profileEdit.css">
        <h1 class="profile-edit__title">プロフィールを編集</h1>
        <p class="profile-edit__form-label">名前</p>
        <input v-model="data.user.name" class="form">
        <p class="profile-edit__form-label">ユーザー名</p>
        <input v-model="data.user.userName" class="form">
        <p class="profile-edit__form-label">自己紹介</p>
        <textarea v-model="data.user.intro" class="form form_dont-resize"></textarea>
        <button @click="refreshUserData" class="form form_btn">保存</button>
    </div>
</template>

<script>
    /* ---------------ProfileEditについて--------------- */
    // ・何らかのエラーが出た場合
    // alertを表示させた後、data.router.push('/profile')を実行

    import { alert, createAlert, notNormalTokenAlert }      from '../../alert'
    import { reactive, onMounted }                          from 'vue'
    import { getUidAndToken }                               from '../../supportFirebase.js'
    import { useRouter }                                    from 'vue-router'
    import { useStore }                                     from 'vuex'
    import firebase                                         from 'firebase'
    import axios                                            from 'axios'

    export default {
        setup() {
            const data = reactive({
                router: useRouter(),
                store: useStore(),
                user: {
                    userName:   '',
                    name:       '',
                    intro:      '',
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
                        data.user.name      = responce.data.name
                        data.user.userName  = responce.data.user_name
                        data.user.intro     = responce.data.intro
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
                const user = await getUidAndToken()
                const refreshUserProfileInfos = {
                    userName:   data.user.userName,
                    token:      user.token,
                    intro:      data.user.intro,
                    name:       data.user.name,
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
                    }
                    localStorage.setItem('myUserName', data.user.userName)
                    data.store.state.user.profileUpdate = true
                    data.router.push(`/profile/${data.user.userName}`)
                })
            }
            onMounted(() => { getUserData() })
            return { data, refreshUserData }
        }
    }
</script>