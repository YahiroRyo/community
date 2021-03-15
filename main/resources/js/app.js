import ***REMOVED*** createApp ***REMOVED*** from 'vue'
import ***REMOVED*** store ***REMOVED*** from './store'
import ***REMOVED*** router ***REMOVED*** from './router'
import App from './App.vue'

let app = createApp(App)
app.use(store)
app.use(router)
app.mount('#app')