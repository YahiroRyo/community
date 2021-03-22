//ルーターを設定する

import ***REMOVED*** createRouter, createWebHistory***REMOVED***    from 'vue-router'
import GlobalPost                           from './components/contents/GlobalPost.vue'
import Login                                from './components/contents/Login.vue'
import Logout                               from './components/contents/Logout.vue'
import Register                             from './components/contents/Register.vue'
import Profile                              from './components/contents/Profile.vue'
import Communities                          from './components/contents/Communities.vue'
import Community                            from './components/contents/Community.vue'
import Contact                              from './components/contents/Contact.vue'
import ProfileEdit                          from './components/contents/ProfileEdit.vue'

export const router = createRouter(***REMOVED***
    history: createWebHistory(),
    routes: [
        ***REMOVED***
            path: '/',
            name: 'global-post',
            component: GlobalPost,
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
    ]
***REMOVED***)