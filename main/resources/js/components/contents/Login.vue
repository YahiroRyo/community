<template>
    <div @keydown.enter="login">
        <link rel="stylesheet" href="/css/components/login/login.css">
        <h1 class="login__title">ログイン</h1>
        <label class="form-label">メールアドレス</label>
        <input class="form" v-model="data.form.email.content">
        <label class="form-label">パスワード</label>
        <input type="password" class="form" v-model="data.form.password.content">
        <button @click="login" class="form form_btn">ログイン</button>
    </div>
</template>

<script>
    import { reactive, onMounted }  from 'vue'
    import { useStore }             from 'vuex'
    import { useRouter }            from 'vue-router'
    import { notNormalTokenAlert }  from '../../alert.js'
    import firebase                 from 'firebase'
    
    export default {
        setup() {
            const data = reactive({
                store: useStore(),
                router: useRouter(),
                form: {
                    email: {
                        content: '',
                    },
                    password: {
                        content: '',
                    },
                },
            })
            const login = async() => {
                await firebase.auth().signInWithEmailAndPassword(data.form.email.content, data.form.password.content)
                .then(() => {
                })
                .catch(() => {
                    createAlert(new alert('ログインに失敗しました。', 2))
                    data.form.password.content = ''
                })
            }
            return { data, login }
        }
    }
</script>