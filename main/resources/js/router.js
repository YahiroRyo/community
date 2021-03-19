//ルーターを設定する

import ***REMOVED*** createRouter, createWebHistory***REMOVED***    from 'vue-router'
import GlobalPost                           from './components/contents/GlobalPost.vue'
import Profile                              from './components/contents/Profile.vue'
import Communities                          from './components/contents/Communities.vue'
import Contact                              from './components/contents/Contact.vue'

export const router = createRouter(***REMOVED***
    history: createWebHistory(),
    routes: [
        ***REMOVED***
            path: '/',
            name: 'global-post',
            component: GlobalPost,
        ***REMOVED***,
        ***REMOVED***
            path: '/profile',
            name: 'profile',
            component: Profile,
        ***REMOVED***,
        ***REMOVED***
            path: '/communities',
            name: 'communities',
            component: Communities,
        ***REMOVED***,
        ***REMOVED***
            path: '/contact',
            name: 'contact',
            component: Contact,
        ***REMOVED***,
    ]
***REMOVED***)