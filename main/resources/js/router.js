//ルーターを設定する

import ***REMOVED*** createRouter, createWebHistory***REMOVED***    from 'vue-router'
import ***REMOVED*** alert, createAlert ***REMOVED***               from './alert'
import ***REMOVED*** closeWindow ***REMOVED***                      from './window'
import ***REMOVED*** store ***REMOVED***                            from './store'
import GlobalPost                           from './components/contents/GlobalPost.vue'
import ShowResponce                         from './components/contents/ShowResponce.vue'
import Login                                from './components/contents/Login.vue'
import Logout                               from './components/contents/Logout.vue'
import Register                             from './components/contents/Register.vue'
import Profile                              from './components/contents/Profile.vue'
import Communities                          from './components/contents/Communities.vue'
import Community                            from './components/contents/Community.vue'
import Contact                              from './components/contents/Contact.vue'
import ProfileEdit                          from './components/contents/ProfileEdit.vue'
import Test                                 from './components/contents/Test.vue'

const router = createRouter(***REMOVED***
    history: createWebHistory(),
    routes: [
        ***REMOVED***
            path: '/',
            name: 'global-post',
            component: GlobalPost,
        ***REMOVED***,
        ***REMOVED***
            path: '/responce/:postId',
            name: 'responce',
            component: ShowResponce,
        ***REMOVED***,
        ***REMOVED***
            path: '/login',
            name: 'login',
            component: Login,
        ***REMOVED***,
        ***REMOVED***
            path: '/logout',
            name: 'logout',
            component: Logout,
        ***REMOVED***,
        ***REMOVED***
            path: '/register',
            name: 'register',
            component: Register,
        ***REMOVED***,
        ***REMOVED***
            path: '/profile/:userName',
            name: 'profile',
            component: Profile,
        ***REMOVED***,
        ***REMOVED***
            path: '/profile-edit',
            name: 'profile-edit',
            component: ProfileEdit,
        ***REMOVED***,
        ***REMOVED***
            path: '/communities',
            name: 'communities',
            component: Communities,
        ***REMOVED***,
        ***REMOVED***
            path: '/communities/community/:id',
            name: 'communities-community',
            component: Community,
        ***REMOVED***,
        ***REMOVED***
            path: '/contact',
            name: 'contact',
            component: Contact,
        ***REMOVED***,
        ***REMOVED***
            path: '/.test',
            name: 'test',
            component: Test,
        ***REMOVED***,
    ]
***REMOVED***)

const antiLoginUser = () => ***REMOVED***
    if (store.state.user.isLogin) ***REMOVED***
        closeWindow()
        createAlert(new alert('ログインしているためホームに戻ります。'))
        router.push('/')
    ***REMOVED***
***REMOVED***
const antiNotLoginUser = () => ***REMOVED***
    if (!store.state.user.isLogin) ***REMOVED***
        closeWindow()
        createAlert(new alert('ログインしていないためホームに戻ります。'))
        router.push('/')
    ***REMOVED***
***REMOVED***

export ***REMOVED*** antiLoginUser, antiNotLoginUser, router ***REMOVED***