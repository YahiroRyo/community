import ***REMOVED*** createApp ***REMOVED***  from 'vue'
import ***REMOVED*** router ***REMOVED***     from './router'
import ***REMOVED*** store ***REMOVED***      from './store'
import firebase       from 'firebase'
import App            from './App.vue'

const firebaseConfig = ***REMOVED***
  apiKey: "AIzaSyCEG7R9qdyrIGJSCKSY8mDtXTruotAwJNA",
  authDomain: "community-ab3f3.firebaseapp.com",
  projectId: "community-ab3f3",
  storageBucket: "community-ab3f3.appspot.com",
  messagingSenderId: "22259305849",
  appId: "1:22259305849:web:afbb7588e683ceb3e0b3a8",
  measurementId: "G-BJM2Z9STJG"
***REMOVED***
// firebase初期化
firebase.initializeApp(firebaseConfig)
firebase.analytics()

// ログインをしているか確認
router.beforeEach((to, from, next) => ***REMOVED***
  firebase.auth().onAuthStateChanged((user) => ***REMOVED***
    if (user) ***REMOVED***
      store.state.user.isLogin = true
    ***REMOVED*** else ***REMOVED***
      if (store.state.user.isLogin) ***REMOVED***
        store.state.user.isLogin = false
        router.push('/login')
      ***REMOVED***
    ***REMOVED***
    next()
  ***REMOVED***)
***REMOVED***)
let app = createApp(App)
app.use(store)
app.use(router)
app.mount('#app')