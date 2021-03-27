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

    import ***REMOVED*** alert, createAlert, notNormalTokenAlert ***REMOVED***      from '../../alert'
    import ***REMOVED*** reactive, onMounted, onBeforeMount ***REMOVED***           from 'vue'
    import ***REMOVED*** antiLoginUser, antiNotLoginUser ***REMOVED***              from '../../router.js'
    import ***REMOVED*** getUidAndToken ***REMOVED***                               from '../../supportFirebase.js'
    import ***REMOVED*** useRouter ***REMOVED***                                    from 'vue-router'
    import ***REMOVED*** useStore ***REMOVED***                                     from 'vuex'
    import firebase                                         from 'firebase'
    import axios                                            from 'axios'

    export default ***REMOVED***
        setup() ***REMOVED***
            const data = reactive(***REMOVED***
                router: useRouter(),
                store: useStore(),
                user: ***REMOVED***
                    userName:   '',
                    name:       '',
                    intro:      '',
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
                            data.user.name      = responce.data.userData.name
                            data.user.userName  = responce.data.userData.user_name
                            data.user.intro     = responce.data.userData.intro
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
                const user = await getUidAndToken()
                const refreshUserProfileInfos = ***REMOVED***
                    userName:   data.user.userName,
                    token:      user.token,
                    intro:      data.user.intro,
                    name:       data.user.name,
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
                    ***REMOVED***
                    localStorage.setItem('myUserName', data.user.userName)
                    data.store.state.user.profileUpdate = true
                    data.router.push(`/profile/$***REMOVED***data.user.userName***REMOVED***`)
                ***REMOVED***)
            ***REMOVED***
            onBeforeMount(() => ***REMOVED***
                antiNotLoginUser()
            ***REMOVED***)
            onMounted(() => ***REMOVED*** getUserData() ***REMOVED***)
            return ***REMOVED*** data, refreshUserData ***REMOVED***
        ***REMOVED***
    ***REMOVED***
</script>