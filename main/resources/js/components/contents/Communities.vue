<template>
    <div>
        <link rel="stylesheet" href="/css/components/communities/communities.css">
        <!-- タブ -->
        <div class="tabs-wapper" style="display: flex;">
            <div @click="data.page = false" :class="***REMOVED***'tab': true, 'tab_selecting': !data.page ***REMOVED***">
                <p class="communities-tab__title">コミュニティを選択</p>
            </div>
            <div @click="data.page = true" :class="***REMOVED***'tab': true, 'tab_selecting': data.page***REMOVED***">
                <p class="communities-tab__title">コミュニティを作成</p>
            </div>
        </div>
        <!-- タブの選択に対しての画面 0 -->
        <transition name="router-view-anim">
            <!-- コミュニティを一覧表示 -->
            <div v-show="!data.page" class="communities-page" key="communities-page-0">
                <div class="post" v-for="(community, key) in data.community.objects" :key="key">
                    <div class="post__flex">
                        <p class="post__name">***REMOVED******REMOVED***community.name***REMOVED******REMOVED***</p>
                    </div>
                    <div class="post__flex">
                        <p class="post__font">***REMOVED******REMOVED***community.description***REMOVED******REMOVED***</p>
                        <button @click="goToCommunity(key)" class="btn btn_normal">入る</button>
                    </div>
                </div>
            </div>
        </transition>
        <!-- タブの選択に対しての画面 1 -->
        <transition name="router-view-anim">
            <!-- コミュニティを作成 -->
            <div v-show="data.page" class="communities-page" appear>
                <p class="communities__form-label">コミュニティ名</p>
                <input v-model="data.createCommunity.name" class="form">
                <p class="communities__form-label">コミュニティの説明</p>
                <textarea v-model="data.createCommunity.description" class="form height-middle form_dont-resize"></textarea>
                <button @click="createCommunity()" disabled class="form form_btn">作成</button>
            </div>
        </transition>
    </div>
</template>

<script>
    import ***REMOVED*** reactive, watch, onMounted ***REMOVED*** from 'vue'
    import ***REMOVED*** useRouter ***REMOVED*** from 'vue-router'

    export default ***REMOVED***
        setup() ***REMOVED***
            const data = reactive(***REMOVED***
                page: false,
                router: useRouter(),
                community: ***REMOVED***
                    objects: [],
                ***REMOVED***,
                createCommunity: ***REMOVED***
                    name: localStorage.getItem('name') ? localStorage.getItem('name') : '',
                    description: localStorage.getItem('description') ? localStorage.getItem('description') : '',
                ***REMOVED***,
            ***REMOVED***)
            const createCommunity = () => ***REMOVED***
                /* ---------------TODO: サーバーへコミュニティを作成するajax処理を実装--------------- */
                
            ***REMOVED***
            const goToCommunity = (key) => ***REMOVED***
                /* ---------------TODO: コミュニティに入る作業--------------- */
                // 仮に入るとする
                data.router.push('/communities/community/0')
            ***REMOVED***
            
            /* ---------------createCommunity変数について--------------- */
            // 他のURLに飛ぶと値が消滅してしまうため、ローカルストレージに入力した値を保存
            watch(() => data.createCommunity.name, () => ***REMOVED***
                localStorage.setItem('name', data.createCommunity.name) 
            ***REMOVED***)
            watch(() => data.createCommunity.description, () => ***REMOVED***
                localStorage.setItem('description', data.createCommunity.description) 
            ***REMOVED***)

            onMounted(() => ***REMOVED***
                /* ---------------TODO: サーバーからコミュニティデータを取得するajax処理を実装--------------- */

                // 本来はここでajax通信を行うが、まだデータがないため仮で100個データをpush
                for (let i = 0; i < 100; i++) ***REMOVED***
                    data.community.objects.push(***REMOVED***
                        name: 'コミュニティ名',
                        description: 'コミュニティの説明コミュニティの説明コミュニティの説明コミュニティの説明コミュニティの説明コミュニティの説明コミュニティの説明コミュニティの説明コミュニティの説明コミュニティの説明コミュニティの説明コミュニティの説明コミュニティの説明コミュニティの説明コミュニティの説明コミュニティの説明コミュニティの説明コミュニティの説明コミュニティの説明コミュニティの説明コミュニティの説明',
                    ***REMOVED***)
                ***REMOVED***
            ***REMOVED***)
            return ***REMOVED*** data, createCommunity, goToCommunity ***REMOVED***
        ***REMOVED***
    ***REMOVED***
</script>