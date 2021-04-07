<template>
    <link rel="stylesheet" href="/css/base.css">
    <header id="header">
        <router-link class="header__icon-wapper" to="/"><img class="header__icon" src="/images/icon.svg"></router-link>
        <ul class="header__tab-wapper">
            <li @mouseover="data.menu.home.isHover = true" @mouseleave="data.menu.home.isHover = false" :class="***REMOVED***'tab tab_circle': true, 'tab_selecting': data.route.path === '/',***REMOVED***">
                <router-link class="tab__img" to="/">
                    <img class="tab__img-icon" src="/images/materials/home.svg">
                </router-link>
                <transition name="pop-up-discription-anim">
                    <div v-show="data.menu.home.isHover" class="pop-up-discription"><p class="pop-up-discription__content">ホーム</p></div>
                </transition>
            </li>
            <li v-if="$store.state.user.isLogin" @mouseover="data.menu.profile.isHover = true" @mouseleave="data.menu.profile.isHover = false" :class="***REMOVED***'tab tab_circle': true, 'tab_selecting': data.route.path === `/profile/$***REMOVED***data.menu.profile.userName***REMOVED***`,***REMOVED***">
                <router-link class="tab__img" :to="`/profile/$***REMOVED***data.menu.profile.userName***REMOVED***`">
                    <img class="tab__img-icon" src="/images/materials/profile.svg">
                </router-link>
                <transition name="pop-up-discription-anim">
                    <div v-show="data.menu.profile.isHover" class="pop-up-discription"><p class="pop-up-discription__content">プロフィール</p></div>
                </transition>
            </li>
            <li v-if="$store.state.user.isLogin" @mouseover="data.menu.communities.isHover = true" @mouseleave="data.menu.communities.isHover = false" :class="***REMOVED***'tab tab_circle': true, 'tab_selecting': data.route.path === '/communities/0' || data.route.path === '/communities/1' ,***REMOVED***">
                <router-link class="tab__img" to="/communities/0">
                    <img class="tab__img-icon" src="/images/materials/community.svg">
                </router-link>
                <transition name="pop-up-discription-anim">
                    <div v-show="data.menu.communities.isHover" class="pop-up-discription"><p class="pop-up-discription__content">コミュニティ</p></div>
                </transition>
            </li>
            <!-- <li @mouseover="data.menu.contact.isHover = true" @mouseleave="data.menu.contact.isHover = false" :class="***REMOVED***'tab tab_circle': true, 'tab_selecting': data.route.path === '/contact',***REMOVED***">
                <router-link class="tab__img" to="/contact">
                    <img class="tab__img-icon" src="/images/materials/info.svg">
                </router-link>
                <transition name="pop-up-discription-anim">
                    <div v-show="data.menu.contact.isHover" class="pop-up-discription"><p class="pop-up-discription__content">お問い合わせ</p></div>
                </transition>
            </li> -->
        </ul>
        <div class="header__other">
            <ul class="header__other__menu">
                <li v-if="$store.state.user.isLogin" @click="displayWindow(3)" class="header__other__menu__item"><img class="header__other__menu__item__btn" src="/images/materials/bell.svg"></li>
            </ul>
        </div>
    </header>
    <div id="container">
        <nav style="" class="menu">
            <ul class="menu__items-wapper">
                <li>
                    <router-link to="/" class="menu__item"><img class="menu__item-icon" src="/images/materials/home.svg">グローバルな投稿を閲覧</router-link>
                </li>
                    <li v-if="$store.state.user.isLogin">
                        <router-link :to="`/profile/$***REMOVED***data.menu.profile.userName***REMOVED***`" class="menu__item"><img class="menu__item-icon" src="/images/materials/profile.svg">プロフィール</router-link>
                    </li>
                    <li v-if="$store.state.user.isLogin">
                        <router-link to="/communities/0" class="menu__item"><img class="menu__item-icon" src="/images/materials/community.svg">コミュニティ</router-link>
                    </li>
                <!-- <li>
                    <router-link to="/contact" class="menu__item"><img class="menu__item-icon" src="/images/materials/info.svg">お問い合わせ</router-link>
                </li>
                <li>
                    <button @click="displayWindow(1)" class="menu__item">モジュール</button>
                </li>
                <li>
                    <button class="menu__item" style="color: red;" @click="data.testTrigger = true">Test Trigger</button>
                </li> -->
            </ul>
        </nav>
        <main id="main">
            <!-- 背景を暗くする -->
            <transition name="dark-background-anim"><div v-show="$store.state.window.use" @click="data.window.isClickOutSize = true" class="dark-background"></div></transition>
            <!-- window module -->
            <transition name="window-anim">
                <!-- onMounted時に表示するコンポーネントがwindowの情報を指定できるようにするため、v-ifを使用 -->
                <div v-if="$store.state.window.use" :style="`width: $***REMOVED***$store.state.window.width***REMOVED***px; height: $***REMOVED***$store.state.window.height***REMOVED***px;`" :class="***REMOVED***'window': true, 'window_shake-anim': data.window.isClickOutSize, ***REMOVED***">
                    <img @click="$store.state.window.use = false" class="window__close-btn" src="/images/materials/close.svg">
                    <h1 class="window__title">***REMOVED******REMOVED***$store.state.window.title***REMOVED******REMOVED***</h1>
                    <!-- window__content内のコンポーネントは予め用意しておき、if文で切り替える -->
                    <div class="window__content" :style="`height: $***REMOVED***data.window.contentHeight***REMOVED***px;`">
                        <!-- 使いたいコンポーネントに番号を定義し、if文で表示させる -->
                        <WindowExample          v-if="$store.state.window.currentComponent      === 0" />
                        <Modules                v-else-if="$store.state.window.currentComponent === 1" />
                        <CreatePost             v-else-if="$store.state.window.currentComponent === 2" />
                        <Bell                   v-else-if="$store.state.window.currentComponent === 3" />
                        <CreateResponcePost     v-else-if="$store.state.window.currentComponent === 4" />
                        <RequestLogin           v-else-if="$store.state.window.currentComponent === 5" />
                        <ShowImage              v-else-if="$store.state.window.currentComponent === 6" />
                        <DeletePost             v-else-if="$store.state.window.currentComponent === 7" />
                    </div>
                </div>
            </transition>
            <!-- alert module -->
            <transition name="alerts-anim">
                <div v-if="$store.state.alert.object" :class="
                    ($store.state.alert.object.type === 0) ?    'alert alert_success'       :
                    ($store.state.alert.object.type === 1) ?    'alert alert_attention'     : 'alert alert_danger'"
                    >
                    <img class="alert__icon" src="/images/materials/surprised.svg">
                    <p class="alert__content">***REMOVED******REMOVED***$store.state.alert.object.content***REMOVED******REMOVED***</p>
                </div>
            </transition>
            <!-- ルーターに対してのコンポーネント表示 -->
            <router-view v-slot="***REMOVED*** Component ***REMOVED***">
                <transition name="router-view-anim">
                    <component :is="Component"></component>
                </transition>
            </router-view>
            <ul class="mobile-phone-menu">
                <li v-if="$store.state.user.isLogin" @click="displayWindow(3)" class="mobile-phone-menu__item"><img class="mobile-phone-menu__item__img" src="/images/materials/bell.svg"></li>
                <li v-if="$store.state.user.isLogin" @click="displayWindow(2)" class="mobile-phone-menu__item"><img class="mobile-phone-menu__item__img" src="/images/materials/post.svg"></li>
                <li v-if="$store.state.user.isLogin" class="mobile-phone-menu__item" @click="$router.push('/logout')"><img class="mobile-phone-menu__item__img" src="/images/materials/logout.svg"></li>
                <li v-if="!$store.state.user.isLogin" @click="$router.push('/login')" class="mobile-phone-menu__item"><img class="mobile-phone-menu__item__img" src="/images/materials/login.svg"></li>
                <li v-if="!$store.state.user.isLogin" @click="$router.push('/register')" class="mobile-phone-menu__item"><img class="mobile-phone-menu__item__img" src="/images/materials/register.svg"></li>
            </ul>
        </main>
        <div class="other">
            <ul class="other__item-wapper">
                <li v-if="$store.state.user.isLogin" @click="displayWindow(2)" class="other__item"><img class="other__item-icon" src="/images/materials/post.svg">投稿する</li>
                <li v-if="$store.state.user.isLogin" @click="$router.push('/logout')" class="other__item"><img class="other__item-icon" src="/images/materials/logout.svg">ログアウト</li>
                <li v-if="!$store.state.user.isLogin" ><router-link to="/login" class="other__item">ログイン</router-link></li>
                <li v-if="!$store.state.user.isLogin" ><router-link to="/register" class="other__item">アカウント登録</router-link></li>
            </ul>
        </div>
    </div>
</template>

<script>
    import ***REMOVED*** reactive, watch, onMounted, onBeforeMount ***REMOVED***    from 'vue'
    import ***REMOVED*** useRouter, useRoute ***REMOVED***                          from 'vue-router'
    import ***REMOVED*** alert, createAlert ***REMOVED***                           from './alert'
    import ***REMOVED*** displayWindow ***REMOVED***                                from './window'
    import ***REMOVED*** useStore ***REMOVED***                                     from 'vuex'
    import firebase                                         from 'firebase'
    import axios                                            from 'axios'

    /* ---------------コンポーネントインポート--------------- */
    import CreateResponcePost   from './components/window/CreateResponcePost.vue'
    import WindowExample        from './components/window/WindowExampleComponent.vue'
    import RequestLogin         from './components/window/RequestLogin.vue'
    import DeletePost           from './components/window/DeletePost.vue'
    import CreatePost           from './components/window/CreatePost.vue'
    import ShowImage            from './components/window/ShowImage.vue'
    import Modules              from './components/window/Modules.vue'
    import Bell                 from './components/window/Bell.vue'
    export default ***REMOVED***
        components:***REMOVED***



            /* ---------------Window用コンポーネント--------------- */
            CreateResponcePost,
            WindowExample,
            RequestLogin,
            DeletePost,
            CreatePost,
            ShowImage,
            Modules,
            Bell,
        ***REMOVED***,
        setup() ***REMOVED***
            const data = reactive(***REMOVED***
                router: useRouter(),
                store:  useStore(),
                route:  useRoute(),
                window: ***REMOVED***
                    contentHeight:  0,
                    isClickOutSize: false,
                ***REMOVED***,
                menu: ***REMOVED***
                    communities:    ***REMOVED*** isHover: false, ***REMOVED***,
                    profile:        ***REMOVED*** isHover: false, userName: '', ***REMOVED***,
                    contact:    ***REMOVED*** isHover: false, ***REMOVED***,
                    home:           ***REMOVED*** isHover: false, ***REMOVED***,
                ***REMOVED***,
                testTrigger: false,
            ***REMOVED***)
            watch(() => data.store.state.window.use, () => ***REMOVED***
                if (data.store.state.window.use) ***REMOVED***
                    // 他のコンポーネントでwindowのプロパティを設定するため、遅延が発生してしまう。
                    // その遅延に合わせるため、setTimeoutを使用
                    setTimeout(() => ***REMOVED***
                        if (data.store.state.window.functions.open) ***REMOVED*** data.store.state.window.functions.open() ***REMOVED***
                        
                        /* ---------------window.height -120計算方法--------------- */
                        // windowのpadding                          -25px
                        // close.svgのheight                        -24px
                        // window__titleのheight                    -36px
                        // window__titleのmargin-top                -10px
                        // window__contentのmargin-top              -10px
                        // window__contentを不自然に表示しないため  -20px
                        // 計 -120px
                        data.window.contentHeight = data.store.state.window.height - 125;
                    ***REMOVED***, 100)
                ***REMOVED*** else ***REMOVED***
                    if (data.store.state.window.functions.close) ***REMOVED*** data.store.state.window.functions.close() ***REMOVED***
                ***REMOVED***
            ***REMOVED***)
            watch(() => data.window.isClickOutSize, () => ***REMOVED***
                if (data.window.isClickOutSize) ***REMOVED***
                    setTimeout(() => ***REMOVED*** data.window.isClickOutSize = false ***REMOVED***, 1000)
                ***REMOVED***
            ***REMOVED***)
            // 挙動を確かめるためのtestTriggerフラグをwatchしている
            watch(() => data.testTrigger, () => ***REMOVED***
                if (data.testTrigger) ***REMOVED***
                    //window
                    displayWindow(5)
                    
                    //alert
                    // createAlert(new alert('alert', 0))
                    data.testTrigger = false
                ***REMOVED***
            ***REMOVED***)
            watch(() => data.store.state.user.profileUpdate, () => ***REMOVED***
                if (data.store.state.user.profileUpdate) ***REMOVED***
                    data.store.state.user.profileUpdate = false
                    data.menu.profile.userName          = data.store.state.user.userName
                ***REMOVED***
            ***REMOVED***)
            onBeforeMount(() => ***REMOVED***
                window.addEventListener('resize', (e) => ***REMOVED***
                    data.store.state.windowSize.width   = window.innerWidth
                    data.store.state.windowSize.height  = window.innerHeight
                ***REMOVED***)
                data.store.state.windowSize.width   = window.innerWidth
                data.store.state.windowSize.height  = window.innerHeight
            ***REMOVED***)
            onMounted(async() => ***REMOVED***
                await firebase.auth().onAuthStateChanged(async(user) => ***REMOVED***
                    if (user) ***REMOVED***
                        const myUserDataInfos = ***REMOVED*** params: ***REMOVED*** uid: user.uid, ***REMOVED*** ***REMOVED***
                        await axios.get('/api/get/my-user-data', myUserDataInfos)
                        .then((responce) => ***REMOVED***
                            if (responce.data.isGetMyUserData) ***REMOVED***
                                data.menu.profile.userName      = responce.data.userData.user_name
                                data.store.state.user.userName  = responce.data.userData.user_name
                            ***REMOVED***
                        ***REMOVED***)
                    ***REMOVED***
                ***REMOVED***)
                
            ***REMOVED***)
            return ***REMOVED*** displayWindow, data ***REMOVED***
        ***REMOVED***
    ***REMOVED***
</script>