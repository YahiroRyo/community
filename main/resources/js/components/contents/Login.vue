<template>
    <div>
        <link rel="stylesheet" href="/css/components/login/login.css">
        <h1 class="login__title">ログイン</h1>
        <label class="form-label">メールアドレス</label>
        <input class="form" v-model="data.form.email.content">
        <label class="form-label">パスワード</label>
        <input class="form" v-model="data.form.password.content">
        <button @click="login" class="form form_btn">ログイン</button>
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
                    password: {
                        content: '',
                    },
                },
            })
            const login = () => {
                /* ---------------TODO: サーバーにログイン情報を投げる--------------- */

            }
            return { data, login }
        }
    }
</script>