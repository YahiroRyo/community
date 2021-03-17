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
            <div v-if="$store.state.window.use" :style="`width: ${$store.state.window.width}px; height: ${$store.state.window.height}px;`" class="window">
                <img @click="$store.state.window.use = false" class="window__close-btn" src="/images/materials/close.svg">
                <h1 class="window__title">{{$store.state.window.title}}</h1>
                <!-- window__content内のコンポーネントは予め用意しておき、if文で切り替える -->
                <div class="window__content" :style="`height: ${data.window.contentHeight}px;`">
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
                <p class="alert__content">{{$store.state.alert.object.content}}</p>
            </div>
        </transition>
    </main>
    <footer id="footer">
    </footer>
</template>

<script>
    import { reactive, watch } from 'vue'
    import { useStore } from 'vuex'
    import { useRouter, useRoute } from 'vue-router'
    import { window, createWindow } from './window';
    import { alert, createAlert } from './alert';

    /* ---------------コンポーネントインポート--------------- */
    import WindowExample from './components/WindowExampleComponent.vue'

    export default {
        components:{



            /* ---------------Window用コンポーネント--------------- */
            WindowExample
        },
        setup() {
            const data = reactive({
                store: useStore(),
                router: useRouter(),
                route: useRoute(),
                window: { contentHeight: 0, },
                testTrigger: false,
                u: 0,
            })
            watch(() => data.store.state.window.use, () => {
                if (data.store.state.window.use) {
                    // 他のコンポーネントでopenにメソッドを代入するため、遅延が発生してしまう。
                    // その遅延に合わせるため、setTimeoutを使用
                    setTimeout(() => {
                        if (data.store.state.window.functions.open) { data.store.state.window.functions.open() }
                    }, 100)
                    /* ---------------window.height -120計算方法--------------- */
                    // windowのpadding                          -20px
                    // close.svgのheight                        -24px
                    // window__titleのheight                    -36px
                    // window__titleのmargin-top                -10px
                    // window__contentのmargin-top              -10px
                    // window__contentを不自然に表示しないため  -20px
                    // 計 -120px
                    data.window.contentHeight = data.store.state.window.height - 120;
                } else {
                    if (data.store.state.window.functions.close) { data.store.state.window.functions.close() }
                }
            })
            watch(() => data.testTrigger, () => {
                if (data.testTrigger) {
                    createAlert(
                        new alert(
                            'test Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alerttest Alert'
                            , 0
                        )
                    )
                    data.testTrigger = false
                }
            })
            return { data }
        }
    }
</script>