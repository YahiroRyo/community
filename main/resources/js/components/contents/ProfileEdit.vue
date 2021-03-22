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

    import { reactive, onMounted } from 'vue'
    import { useRouter } from 'vue-router'
    import { alert, createAlert, notNormalTokenAlert }   from '../../alert'
    import axios from 'axios'

    export default {
        setup() {
            const data = reactive({
                router: useRouter(),
                user: {
                    name: '',
                    userName: '',
                    intro: '',
                },
            })
            // ユーザーデータの取得
            // uidを投げたらユーザー情報が返ってくる
            const getUserData = () => {
                const userProfileInfos = {
                    params: {
                        'uid': localStorage.getItem('uid'),
                    },
                }
                axios.get('/api/get/user-profile', userProfileInfos)
                .then((responce) => {
                    data.user.name = responce.data.name
                    data.user.userName = responce.data.user_name
                    data.user.intro = responce.data.intro
                })
                .catch(() => {
                    createAlert(new alert('ユーザーデータの取得に失敗しました。', 2))
                    data.router.push('/profile')
                })
            }
            // ユーザー情報を更新する
            const refreshUserData = () => {
                const refreshUserProfileInfos = {
                    token: localStorage.getItem('token'),
                    uid: localStorage.getItem('uid'),
                    name: data.user.name,
                    userName: data.user.userName,
                    intro: data.user.intro,
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
                    data.router.push('/profile')
                })
            }
            onMounted(() => { getUserData() })
            return { data, refreshUserData }
        }
    }
</script>