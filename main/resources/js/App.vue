<template>
    <link rel="stylesheet" href="/css/base.css">
    <header id="header">
        <router-link to="/"><img class="header__icon" src="/images/icon.svg"></router-link>
        <ul class="header__tab-wapper">
            <li :class="***REMOVED***'tab tab_circle': true, 'tab_selecting': data.route.path === '/',***REMOVED***">
                <router-link class="tab__img" to="/">
                    <img class="tab__img-icon" src="/images/materials/home.svg">
                </router-link>
            </li>
            <li :class="***REMOVED***'tab tab_circle': true, 'tab_selecting': data.route.path === '/profile',***REMOVED***">
                <router-link class="tab__img" to="/profile">
                    <img class="tab__img-icon" src="/images/materials/profile.svg">
                </router-link>
            </li>
            <li :class="***REMOVED***'tab tab_circle': true, 'tab_selecting': data.route.path === '/communities',***REMOVED***">
                <router-link class="tab__img" to="/communities">
                    <img class="tab__img-icon" src="/images/materials/community.svg">
                </router-link>
            </li>
            <li :class="***REMOVED***'tab tab_circle': true, 'tab_selecting': data.route.path === '/contact',***REMOVED***">
                <router-link class="tab__img" to="/contact">
                    <img class="tab__img-icon" src="/images/materials/info.svg">
                </router-link>
            </li>
        </ul>
    </header>
    <div id="container">
        <nav class="menu">
            <ul class="menu__items-wapper">
                <li>
                    <router-link to="/" class="menu__item"><img class="menu__item-icon" src="/images/materials/home.svg">グローバルな投稿を閲覧</router-link>
                </li>
                <li>
                    <router-link to="/profile" class="menu__item"><img class="menu__item-icon" src="/images/materials/profile.svg">プロフィール</router-link>
                </li>
                <li>
                    <router-link to="/communities" class="menu__item"><img class="menu__item-icon" src="/images/materials/community.svg">コミュニティ</router-link>
                </li>
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
                <div v-if="$store.state.window.use" :style="`width: $***REMOVED***$store.state.window.width***REMOVED***px; height: $***REMOVED***$store.state.window.height***REMOVED***px;`" :class="***REMOVED***'window': true, 'window_shake-anim': data.window.isClickOutSize, ***REMOVED***">
                    <img @click="$store.state.window.use = false" class="window__close-btn" src="/images/materials/close.svg">
                    <h1 class="window__title">***REMOVED******REMOVED***$store.state.window.title***REMOVED******REMOVED***</h1>
                    <!-- window__content内のコンポーネントは予め用意しておき、if文で切り替える -->
                    <div class="window__content" :style="`height: $***REMOVED***data.window.contentHeight***REMOVED***px;`">
                        <!-- 使いたいコンポーネントに番号を定義し、if文で表示させる -->
                        <WindowExample v-if="$store.state.window.currentComponent === 0" />
                        <Modules v-else-if="$store.state.window.currentComponent === 1" />
                        <Post v-else-if="$store.state.window.currentComponent === 2" />
                    </div>
                </div>
            </transition>
            <!-- alert module -->
            <transition name="alerts-anim">
                <div v-if="$store.state.alert.object" :class="
                    ($store.state.alert.object.type === 0) ?    'alert alert_success'       :
                    ($store.state.alert.object.type === 1) ?    'alert alert_attention'     : 'alert alert_danger'"
                    >
                    <p class="alert__content">***REMOVED******REMOVED***$store.state.alert.object.content***REMOVED******REMOVED***</p>
                </div>
            </transition>
            <router-view></router-view>
        </main>
        <div class="other">
            <ul class="other__item-wapper">
                <li @click="displayWindow(2)" class="other__item"><img class="other__item-icon" src="/images/materials/post.svg">投稿する</li>
            </ul>
        </div>
    </div>
</template>

<script>
    import ***REMOVED*** reactive, watch ***REMOVED***      from 'vue'
    import ***REMOVED*** useStore ***REMOVED***             from 'vuex'
    import ***REMOVED*** useRouter, useRoute ***REMOVED***  from 'vue-router'
    import ***REMOVED*** displayWindow ***REMOVED***        from './window';
    import ***REMOVED*** alert, createAlert ***REMOVED***   from './alert';

    /* ---------------コンポーネントインポート--------------- */
    import WindowExample from './components/window/WindowExampleComponent.vue'
    import Modules from './components/window/Modules.vue'
    import Post from './components/window/Post.vue'

    export default ***REMOVED***
        components:***REMOVED***



            /* ---------------Window用コンポーネント--------------- */
            WindowExample,
            Modules,
            Post,
        ***REMOVED***,
        setup() ***REMOVED***
            const data = reactive(***REMOVED***
                store: useStore(),
                router: useRouter(),
                route: useRoute(),
                window: ***REMOVED***
                    contentHeight: 0,
                    isClickOutSize: false,
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
                        // windowのpadding                          -20px
                        // close.svgのheight                        -24px
                        // window__titleのheight                    -36px
                        // window__titleのmargin-top                -10px
                        // window__contentのmargin-top              -10px
                        // window__contentを不自然に表示しないため  -20px
                        // 計 -120px
                        data.window.contentHeight = data.store.state.window.height - 120;
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
                    displayWindow(0)
                    
                    //alert
                    createAlert(new alert('alert', 0))
                    data.testTrigger = false
                ***REMOVED***
            ***REMOVED***)
            return ***REMOVED*** displayWindow, data ***REMOVED***
        ***REMOVED***
    ***REMOVED***
</script>