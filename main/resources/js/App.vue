<template>
    <link rel="stylesheet" href="/css/base.css">
    <header id="header">
    </header>
    <main style="height: 100vh">
        <!-- テストしているモジュールのフラグをtrueにするボタン -->
        <div style="width: 300px; height: 300px; background-color: #eee;" @click="data.testTrigger = true"><p>Test btn trigger</p></div>
        
        <!-- 背景を暗くする -->
        <transition name="dark-background-anim"><div v-show="$store.state.window.use" class="dark-background"></div></transition>
        
        <!-- window module -->
        <transition name="window-anim">
            <!-- onMounted時に表示するコンポーネントがwindowの情報を指定できるようにするため、v-ifを使用 -->
            <div v-if="$store.state.window.use" :style="`width: $***REMOVED***$store.state.window.width***REMOVED***px; height: $***REMOVED***$store.state.window.height***REMOVED***px;`" class="window">
                <img @click="$store.state.window.use = false" class="window__close-btn" src="/images/materials/close.svg">
                <h1 class="window__title">***REMOVED******REMOVED***$store.state.window.title***REMOVED******REMOVED***</h1>
                <!-- window__content内のコンポーネントは予め用意しておき、if文で切り替える -->
                <div class="window__content" :style="`height: $***REMOVED***data.window.contentHeight***REMOVED***px;`">
                    <!-- 使いたいコンポーネントに番号を定義し、if文で表示させる -->
                    <WindowExample v-if="$store.state.window.currentComponent === 0" />
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
    </main>
    <footer id="footer">
    </footer>
</template>

<script>
    import ***REMOVED*** reactive, watch ***REMOVED*** from 'vue'
    import ***REMOVED*** useStore ***REMOVED*** from 'vuex'
    import ***REMOVED*** useRouter, useRoute ***REMOVED*** from 'vue-router'
    import ***REMOVED*** displayWindow ***REMOVED*** from './window';
    import ***REMOVED*** alert, createAlert ***REMOVED*** from './alert';

    /* ---------------コンポーネントインポート--------------- */
    import WindowExample from './components/WindowExampleComponent.vue'

    export default ***REMOVED***
        components:***REMOVED***



            /* ---------------Window用コンポーネント--------------- */
            WindowExample
        ***REMOVED***,
        setup() ***REMOVED***
            const data = reactive(***REMOVED***
                store: useStore(),
                router: useRouter(),
                route: useRoute(),
                window: ***REMOVED*** contentHeight: 0, ***REMOVED***,
                testTrigger: false,
                u: 0,
            ***REMOVED***)
            watch(() => data.store.state.window.use, () => ***REMOVED***
                if (data.store.state.window.use) ***REMOVED***
                    // 他のコンポーネントでopenにメソッドを代入するため、遅延が発生してしまう。
                    // その遅延に合わせるため、setTimeoutを使用
                    setTimeout(() => ***REMOVED***
                        if (data.store.state.window.functions.open) ***REMOVED*** data.store.state.window.functions.open() ***REMOVED***
                    ***REMOVED***, 100)
                    /* ---------------window.height -120計算方法--------------- */
                    // windowのpadding                          -20px
                    // close.svgのheight                        -24px
                    // window__titleのheight                    -36px
                    // window__titleのmargin-top                -10px
                    // window__contentのmargin-top              -10px
                    // window__contentを不自然に表示しないため  -20px
                    // 計 -120px
                    data.window.contentHeight = data.store.state.window.height - 120;
                ***REMOVED*** else ***REMOVED***
                    if (data.store.state.window.functions.close) ***REMOVED*** data.store.state.window.functions.close() ***REMOVED***
                ***REMOVED***
            ***REMOVED***)
            watch(() => data.testTrigger, () => ***REMOVED***
                displayWindow(0)
                if (data.testTrigger) ***REMOVED***
                    createAlert(
                        new alert(
                            'test Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alert'
                            , 0
                        )
                    )
                    data.testTrigger = false
                ***REMOVED***
            ***REMOVED***)
            return ***REMOVED*** data ***REMOVED***
        ***REMOVED***
    ***REMOVED***
</script>