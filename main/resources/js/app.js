import { createApp }  from 'vue'
import { router }     from './router'
import { store }      from './store'
import firebase       from 'firebase'
import App            from './App.vue'

const firebaseConfig = {
  apiKey: "AIzaSyCEG7R9qdyrIGJSCKSY8mDtXTruotAwJNA",
  authDomain: "community-ab3f3.firebaseapp.com",
  projectId: "community-ab3f3",
  storageBucket: "community-ab3f3.appspot.com",
  messagingSenderId: "22259305849",
  appId: "1:22259305849:web:afbb7588e683ceb3e0b3a8",
  measurementId: "G-BJM2Z9STJG"
}
// firebase初期化
firebase.initializeApp(firebaseConfig)
firebase.analytics()

// ログインをしているか確認
router.beforeEach(async(to, from, next) => {
  await firebase.auth().onAuthStateChanged((user) => {
    if (user) {
      store.state.user.isLogin = true
    } else {
      if (store.state.user.isLogin) {
        store.state.user.isLogin = false
        router.push('/login')
      }
    }
  })
  next()
})
let app = createApp(App)
app.use(store)
app.use(router)
app.mount('#app')