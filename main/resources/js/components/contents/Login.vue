<template>
    <div @keydown.enter="login">
        <link rel="stylesheet" href="/css/components/login/login.css">
        <div class="form__wapper">
            <h1 class="login__title">ログイン</h1>
            <Form class="form" v-model:inputContent="data.form.email.content" label="メールアドレス" uniqueClassKey="1" />
            <Form class="form" v-model:inputContent="data.form.password.content" type="password" label="パスワード" uniqueClassKey="2" />
            <button @click="login" class="form__btn">ログイン</button>
        </div>
    </div>
</template>

<script>
    import { createAlert, alert, notNormalTokenAlert }  from '../../alert.js'
    import { reactive, onMounted, ref }                 from 'vue'
    import { useRouter }                                from 'vue-router'
    import { useStore }                                 from 'vuex'
    import firebase                                     from 'firebase'

    /* ---------------コンポーネントをインポート--------------- */
    import Form                                         from '../Form.vue'
    
    export default {
        components: {
            Form
        },
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
                    createAlert(new alert('ログインしました。', 0))
                    data.router.push('/')
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