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
        <input class="form" v-model="data.form.password.content">
        <label class="form-label">パスワード確認</label>
        <input class="form" v-model="data.form.confirmationPassword.content">
        <button @click="register" class="form form_btn">登録</button>
    </div>
</template>

<script>
    import { reactive, onMounted } from 'vue'

    export default {
        setup() {
            const data = reactive({
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
            const register = () => {
                /* ---------------TODO: サーバーに登録する情報を投げる--------------- */
                
            }
            return { data, register }
        }
    }
</script>