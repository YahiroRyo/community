//ルーターを設定する

import { createRouter, createWebHistory}    from 'vue-router'
import GlobalPost                           from './components/contents/GlobalPost.vue'
import Profile                              from './components/contents/Profile.vue'
import Communities                          from './components/contents/Communities.vue'
import Contact                              from './components/contents/Contact.vue'
import ProfileEdit                              from './components/contents/ProfileEdit.vue'

export const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            name: 'global-post',
            component: GlobalPost,
        },
        {
            path: '/profile',
            name: 'profile',
            component: Profile,
        },
        {
            path: '/profile-edit',
            name: 'profile-edit',
            component: ProfileEdit,
        },
        {
            path: '/communities',
            name: 'communities',
            component: Communities,
        },
        {
            path: '/contact',
            name: 'contact',
            component: Contact,
        },
    ]
})