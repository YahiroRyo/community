//ルーターを設定する

import { createRouter, createWebHistory}    from 'vue-router'
import { alert, createAlert }               from './alert'
import { closeWindow }                      from './window'
import { store }                            from './store'
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

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            name: 'global-post',
            component: GlobalPost,
        },
        {
            path: '/responce/:postId',
            name: 'responce',
            component: ShowResponce,
        },
        {
            path: '/login',
            name: 'login',
            component: Login,
        },
        {
            path: '/logout',
            name: 'logout',
            component: Logout,
        },
        {
            path: '/register',
            name: 'register',
            component: Register,
        },
        {
            path: '/profile/:userName',
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
            path: '/communities/community/:id',
            name: 'communities-community',
            component: Community,
        },
        {
            path: '/contact',
            name: 'contact',
            component: Contact,
        },
        {
            path: '/.test',
            name: 'test',
            component: Test,
        },
    ]
})

const antiLoginUser = () => {
    if (store.state.user.isLogin) {
        closeWindow()
        createAlert(new alert('ログインしているためホームに戻ります。'))
        router.push('/')
    }
}
const antiNotLoginUser = () => {
    console.log("t")
    if (!store.state.user.isLogin) {
        closeWindow()
        createAlert(new alert('ログインしていないためホームに戻ります。'))
        router.push('/')
    }
}

export { antiLoginUser, antiNotLoginUser, router }