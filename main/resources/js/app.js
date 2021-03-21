import ***REMOVED*** createApp ***REMOVED*** from 'vue'
import ***REMOVED*** store ***REMOVED*** from './store'
import ***REMOVED*** router ***REMOVED*** from './router'
import App from './App.vue'

const firebaseConfig = ***REMOVED***
  apiKey: "AIzaSyCEG7R9qdyrIGJSCKSY8mDtXTruotAwJNA",
  authDomain: "community-ab3f3.firebaseapp.com",
  projectId: "community-ab3f3",
  storageBucket: "community-ab3f3.appspot.com",
  messagingSenderId: "22259305849",
  appId: "1:22259305849:web:afbb7588e683ceb3e0b3a8",
  measurementId: "G-BJM2Z9STJG"
***REMOVED***
firebase.initializeApp(firebaseConfig)
firebase.analytics()

let app = createApp(App)
app.use(store)
app.use(router)
app.mount('#app')