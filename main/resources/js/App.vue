<template>
    <link rel="stylesheet" href="/css/base.css">
    <header id="header">
        <router-link class="header__icon-wapper" to="/"><img class="header__icon" src="/images/icon.svg"></router-link>
        <ul class="header__tab-wapper">
            <li @mouseover="data.menu.home.isHover = true" @mouseleave="data.menu.home.isHover = false" :class="{'tab tab_circle': true, 'tab_selecting': data.route.path === '/',}">
                <router-link class="tab__img" to="/">
                    <img class="tab__img-icon" src="/images/materials/home.svg">
                </router-link>
                <transition name="pop-up-discription-anim">
                    <div v-show="data.menu.home.isHover" class="pop-up-discription"><p class="pop-up-discription__content">ホーム</p></div>
                </transition>
            </li>
            <transition name="router-view-anim">
                <li v-if="$store.state.user.isLogin" @mouseover="data.menu.profile.isHover = true" @mouseleave="data.menu.profile.isHover = false" :class="{'tab tab_circle': true, 'tab_selecting': data.route.path === '/profile',}">
                    <router-link class="tab__img" :to="`/profile/${data.menu.profile.userName}`">
                        <img class="tab__img-icon" src="/images/materials/profile.svg">
                    </router-link>
                    <transition name="pop-up-discription-anim">
                        <div v-show="data.menu.profile.isHover" class="pop-up-discription"><p class="pop-up-discription__content">プロフィール</p></div>
                    </transition>
                </li>
            </transition>
            <transition name="router-view-anim">
                <li v-if="$store.state.user.isLogin" @mouseover="data.menu.communities.isHover = true" @mouseleave="data.menu.communities.isHover = false" :class="{'tab tab_circle': true, 'tab_selecting': data.route.path === '/communities',}">
                    <router-link class="tab__img" to="/communities">
                        <img class="tab__img-icon" src="/images/materials/community.svg">
                    </router-link>
                    <transition name="pop-up-discription-anim">
                        <div v-show="data.menu.communities.isHover" class="pop-up-discription"><p class="pop-up-discription__content">コミュニティ</p></div>
                    </transition>
                </li>
            </transition>
            <li @mouseover="data.menu.contact.isHover = true" @mouseleave="data.menu.contact.isHover = false" :class="{'tab tab_circle': true, 'tab_selecting': data.route.path === '/contact',}">
                <router-link class="tab__img" to="/contact">
                    <img class="tab__img-icon" src="/images/materials/info.svg">
                </router-link>
                <transition name="pop-up-discription-anim">
                    <div v-show="data.menu.contact.isHover" class="pop-up-discription"><p class="pop-up-discription__content">お問い合わせ</p></div>
                </transition>
            </li>
        </ul>
        <div class="header__other">
            <ul class="header__other__menu">
                <li @click="displayWindow(3)" class="header__other__menu__item"><img class="header__other__menu__item__btn" src="/images/materials/bell.svg"></li>
            </ul>
        </div>
    </header>
    <div id="container">
        <nav style="" class="menu">
            <ul class="menu__items-wapper">
                <li>
                    <router-link to="/" class="menu__item"><img class="menu__item-icon" src="/images/materials/home.svg">グローバルな投稿を閲覧</router-link>
                </li>
                <transition name="router-view-anim">
                    <li v-if="$store.state.user.isLogin">
                        <router-link :to="`/profile/${data.menu.profile.userName}`" class="menu__item"><img class="menu__item-icon" src="/images/materials/profile.svg">プロフィール</router-link>
                    </li>
                </transition>
                <transition name="router-view-anim">
                    <li v-if="$store.state.user.isLogin">
                        <router-link to="/communities" class="menu__item"><img class="menu__item-icon" src="/images/materials/community.svg">コミュニティ</router-link>
                    </li>
                </transition>
                <li>
                    <router-link to="/contact" class="menu__item"><img class="menu__item-icon" src="/images/materials/info.svg">お問い合わせ</router-link>
                </li>
                <li>
                    <button @click="displayWindow(1)" class="menu__item">モジュール</button>
                </li>
                <li>
                    <!-- テストしているモジュールのフラグをtrueにするボタン -->
                    <button class="menu__item" style="color: red;" @click="data.testTrigger = true">Test Trigger</button>
                </li>
            </ul>
        </nav>
        <main id="main">
            <!-- 背景を暗くする -->
            <transition name="dark-background-anim"><div v-show="$store.state.window.use" @click="data.window.isClickOutSize = true" class="dark-background"></div></transition>
            <!-- window module -->
            <transition name="window-anim">
                <!-- onMounted時に表示するコンポーネントがwindowの情報を指定できるようにするため、v-ifを使用 -->
                <div v-if="$store.state.window.use" :style="`width: ${$store.state.window.width}px; height: ${$store.state.window.height}px;`" :class="{'window': true, 'window_shake-anim': data.window.isClickOutSize, }">
                    <img @click="$store.state.window.use = false" class="window__close-btn" src="/images/materials/close.svg">
                    <h1 class="window__title">{{$store.state.window.title}}</h1>
                    <!-- window__content内のコンポーネントは予め用意しておき、if文で切り替える -->
                    <div class="window__content" :style="`height: ${data.window.contentHeight}px;`">
                        <!-- 使いたいコンポーネントに番号を定義し、if文で表示させる -->
                        <WindowExample v-if="$store.state.window.currentComponent === 0" />
                        <Modules v-else-if="$store.state.window.currentComponent === 1" />
                        <CreatePost v-else-if="$store.state.window.currentComponent === 2" />
                        <Bell v-else-if="$store.state.window.currentComponent === 3" />
                    </div>
                </div>
            </transition>
            <!-- alert module -->
            <transition name="alerts-anim">
                <div v-if="$store.state.alert.object" :class="
                    ($store.state.alert.object.type === 0) ?    'alert alert_success'       :
                    ($store.state.alert.object.type === 1) ?    'alert alert_attention'     : 'alert alert_danger'"
                    >
                    <p class="alert__content">{{$store.state.alert.object.content}}</p>
                </div>
            </transition>
            <!-- ルーターに対してのコンポーネント表示 -->
            <router-view v-slot="{ Component }">
                <transition name="router-view-anim">
                    <component :is="Component"></component>
                </transition>
            </router-view>
        </main>
        <div class="other">
            <ul class="other__item-wapper">
                <transition name="router-view-anim">
                    <li v-if="$store.state.user.isLogin" @click="displayWindow(2)" class="other__item"><img class="other__item-icon" src="/images/materials/post.svg">投稿する</li>
                </transition>
                <transition name="router-view-anim">
                    <li v-if="$store.state.user.isLogin" ><router-link to="/logout" class="other__item">ログアウト</router-link></li>
                </transition>
                <transition name="router-view-anim">
                    <li v-if="!$store.state.user.isLogin" ><router-link to="/login" class="other__item">ログイン</router-link></li>
                </transition>
                <transition name="router-view-anim">
                    <li v-if="!$store.state.user.isLogin" ><router-link to="/register" class="other__item">アカウント登録</router-link></li>
                </transition>
            </ul>
        </div>
    </div>
</template>

<script>
    import { reactive, watch, onMounted }      from 'vue'
    import { useStore }             from 'vuex'
    import { useRouter, useRoute }  from 'vue-router'
    import { displayWindow }        from './window'
    import { alert, createAlert }   from './alert'
    import axios from 'axios'

    /* ---------------コンポーネントインポート--------------- */
    import WindowExample from './components/window/WindowExampleComponent.vue'
    import Modules from './components/window/Modules.vue'
    import CreatePost from './components/window/CreatePost.vue'
    import Bell from './components/window/Bell.vue'

    export default {
        components:{



            /* ---------------Window用コンポーネント--------------- */
            WindowExample,
            Modules,
            CreatePost,
            Bell,
        },
        setup() {
            const data = reactive({
                store: useStore(),
                router: useRouter(),
                route: useRoute(),
                window: {
                    contentHeight: 0,
                    isClickOutSize: false,
                },
                menu: {
                    home: { isHover: false, },
                    profile: { isHover: false, userName: '', },
                    communities: { isHover: false, },
                    contact: { isHover: false, },
                },
                testTrigger: false,
            })
            watch(() => data.store.state.window.use, () => {
                if (data.store.state.window.use) {
                    // 他のコンポーネントでwindowのプロパティを設定するため、遅延が発生してしまう。
                    // その遅延に合わせるため、setTimeoutを使用
                    setTimeout(() => {
                        if (data.store.state.window.functions.open) { data.store.state.window.functions.open() }
                        
                        /* ---------------window.height -120計算方法--------------- */
                        // windowのpadding                          -25px
                        // close.svgのheight                        -24px
                        // window__titleのheight                    -36px
                        // window__titleのmargin-top                -10px
                        // window__contentのmargin-top              -10px
                        // window__contentを不自然に表示しないため  -20px
                        // 計 -120px
                        data.window.contentHeight = data.store.state.window.height - 125;
                    }, 100)
                } else {
                    if (data.store.state.window.functions.close) { data.store.state.window.functions.close() }
                }
            })
            watch(() => data.window.isClickOutSize, () => {
                if (data.window.isClickOutSize) {
                    setTimeout(() => { data.window.isClickOutSize = false }, 1000)
                }
            })
            watch(() => localStorage.getItem('isLogin'), () => {
                console.log("t")
            })
            // 挙動を確かめるためのtestTriggerフラグをwatchしている
            watch(() => data.testTrigger, () => {
                if (data.testTrigger) {
                    //window
                    displayWindow(0)
                    
                    //alert
                    createAlert(new alert('alert', 0))
                    data.testTrigger = false
                }
            })
            watch(() => data.store.state.user.profileUpdate, () => {
                if (data.store.state.user.profileUpdate) {
                    data.store.state.user.profileUpdate = false
                    data.menu.profile.userName = localStorage.getItem('myUserName')
                }
            })
            onMounted(() => {
                const myUserDataInfos = {
                    params: {
                        uid: localStorage.getItem('uid'),
                    }
                }
                axios.get('/api/get/my-user-data', myUserDataInfos)
                .then((responce) => {
                    data.menu.profile.userName = responce.data.user_name
                    localStorage.setItem('myUserName', responce.data.user_name)
                })
            })
            return { displayWindow, data }
        }
    }
</script>