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
                        <button @click="goToCommunity(key)" class="communitiest__btn">***REMOVED******REMOVED***community.type === 0 ? '加入申請' : community.type === 1 ? '加入申請中' : '入る'***REMOVED******REMOVED***</button>
                    </div>
                </div>
            </div>
        </transition>
        <!-- タブの選択に対しての画面 1 -->
        <transition name="router-view-anim">
            <!-- コミュニティを作成 -->
            <div v-show="data.page" class="form__wapper m-t-3" appear>
                <Form class="form" v-model:inputContent="data.createCommunity.name" label="コミュニティ名" uniqueClassKey="1" />
                <Form class="form" :useTextArea="true" v-model:inputContent="data.createCommunity.description" label="コミュニティの説明" uniqueClassKey="1" />
                <button @click="createCommunity()" class="form__btn">作成</button>
            </div>
        </transition>
    </div>
</template>

<script>
    import ***REMOVED*** createAlert, alert, notNormalTokenAlert ***REMOVED***  from '../../alert.js'
    import ***REMOVED*** addPageEvent, removeAtAllFunc ***REMOVED***            from '../../page.js'
    import ***REMOVED*** reactive, watch, onMounted ***REMOVED***               from 'vue'
    import ***REMOVED*** getUidAndToken ***REMOVED***                           from '../../supportFirebase.js'
    import ***REMOVED*** useRouter ***REMOVED***                                from 'vue-router'
    import firebase                                     from 'firebase'
    import axios                                        from 'axios'
    /* ---------------コンポーネントをインポート--------------- */
    import Form                                         from '../Form.vue'

    export default ***REMOVED***
        components: ***REMOVED***
            Form
        ***REMOVED***,
        setup() ***REMOVED***
            const data = reactive(***REMOVED***
                page:   false,
                router: useRouter(),
                community: ***REMOVED***
                    take:       50,
                    gotNum:     0,
                    objects:    [],
                    cantTake:   false,
                ***REMOVED***,
                createCommunity: ***REMOVED***
                    name: localStorage.getItem('name') ? localStorage.getItem('name') : '',
                    description: localStorage.getItem('description') ? localStorage.getItem('description') : '',
                ***REMOVED***,
            ***REMOVED***)
            const getCommunities = async() => ***REMOVED***
                if (!data.community.cantTake) ***REMOVED***
                    const user = await getUidAndToken()
                    if (!user.isError) ***REMOVED***
                        const getCommunitiesInfos = ***REMOVED***
                            params: ***REMOVED***
                                uid: user.uid,
                                take: data.community.take,
                                gotNum: data.community.gotNum,
                            ***REMOVED***
                        ***REMOVED***
                        await axios.get('/api/get/communities', getCommunitiesInfos)
                        .then((responce) => ***REMOVED***
                            if (responce.data.isGetCommunities) ***REMOVED***
                                data.community.gotNum += data.community.take
                                // 取得しようとしていた数よりも少なかった場合は、それ以上のデータがない。
                                // 従って、これ以上取得できないように設定
                                if (data.community.gotNum > responce.data.communities.length)
                                    data.community.cantTake = true
                                responce.data.communities.forEach((community) => ***REMOVED***
                                    data.community.objects.push(***REMOVED***
                                        name: community.name,
                                        description: community.description,
                                        id: community.id,
                                        type: community.is_joining_community !== null ? 2 : community.can_i_join_community !== null ? 1 : 0,
                                    ***REMOVED***)
                                ***REMOVED***)
                            ***REMOVED*** else ***REMOVED***
                                createAlert(new alert('コミュニティの取得に失敗しました。', 2))
                                // 失敗した場合は、ホームに飛ぶ
                                setTimeout(() => ***REMOVED***
                                    data.router.push('/')
                                ***REMOVED***, 50)
                            ***REMOVED***
                        ***REMOVED***)
                    ***REMOVED*** else ***REMOVED***
                        createAlert(new alert('ユーザー情報を取得することに失敗しました。', 2))
                        // 失敗した場合は、ホームに飛ぶ
                        setTimeout(() => ***REMOVED***
                            data.router.push('/')
                        ***REMOVED***, 50)
                    ***REMOVED***
                ***REMOVED***
            ***REMOVED***
            const createCommunity = async() => ***REMOVED***
                /* ---------------TODO: サーバーへコミュニティを作成するajax処理を実装--------------- */
                const user = await getUidAndToken()
                if (!user.isError) ***REMOVED***
                    const createCommunityInfos = ***REMOVED***
                        uid: user.uid,
                        token: user.token,
                        name: data.createCommunity.name,
                        description: data.createCommunity.description,
                    ***REMOVED***
                    axios.post('/api/post/create-community', createCommunityInfos)
                    .then((responce) => ***REMOVED***
                        if (responce.data.isCreateCommunity) ***REMOVED***
                            createAlert(new alert('コミュニティを作成しました。', 0))
                            // v-modelの内容とlocalStorageの内容を初期化
                            data.createCommunity.name = ''
                            data.createCommunity.description = ''
                            localStorage.removeItem('name')
                            localStorage.removeItem('description')
                            data.router.go(data.router.path)
                        ***REMOVED*** else if (!responce.data.isNormalToken) ***REMOVED***
                            notNormalTokenAlert()
                        ***REMOVED*** else ***REMOVED***
                            createAlert(new alert('コミュニティの作成に失敗しました。', 2))
                        ***REMOVED***
                    ***REMOVED***)
                ***REMOVED*** else ***REMOVED***
                    createAlert(new alert('コミュニティの作成に失敗しました。', 2))
                ***REMOVED***
            ***REMOVED***
            const goToCommunity = async(key) => ***REMOVED***
                /* ---------------TODO: コミュニティに入る作業--------------- */
                const user = await getUidAndToken()
                if (!user.isError) ***REMOVED***
                    if (data.community.objects[key].type === 0) ***REMOVED***
                        // 加入申請を送信
                        const canIJoinCommunityInfos = ***REMOVED***
                            uid: user.uid,
                            token: user.token,
                            communityId: data.community.objects[key].id,
                        ***REMOVED***
                        axios.post('/api/post/can-i-join-community', canIJoinCommunityInfos)
                        .then((responce) => ***REMOVED***
                            if (responce.data.isNormalToken) ***REMOVED***
                                if (responce.data.isCanIJoinCommunity) ***REMOVED***
                                    data.community.objects[key].type = 1
                                    createAlert(new alert('加入申請をしました。', 0))
                                ***REMOVED*** else ***REMOVED***
                                    createAlert(new alert('加入申請に失敗しました。', 2))
                                ***REMOVED***
                            ***REMOVED*** else ***REMOVED***
                                notNormalTokenAlert()
                            ***REMOVED***
                        ***REMOVED***)
                    ***REMOVED*** else if (data.community.objects[key].type === 1) ***REMOVED***
                        // 加入申請を取り消し
                        const cancelJoinCommunityInfos = ***REMOVED***
                            uid: user.uid,
                            token: usersToken,
                            communityId: data.community.objects[key].id,
                        ***REMOVED***
                        axios.post('/api/post/cancel-join-community', cancelJoinCommunityInfos)
                        .then((responce) => ***REMOVED***
                            if (responce.data.isNormalToken) ***REMOVED***
                                if (responce.data.isCancelJoinCommunity) ***REMOVED***
                                    data.community.objects[key].type = 0
                                    createAlert(new alert('加入申請を取り消しました。', 0))
                                ***REMOVED*** else ***REMOVED***
                                    createAlert(new alert('加入申請の取り消しに失敗しました', 2))
                                ***REMOVED***
                            ***REMOVED*** else ***REMOVED***
                                notNormalTokenAlert()
                            ***REMOVED***
                        ***REMOVED***)
                    ***REMOVED*** else ***REMOVED***
                        // ルームへ入る
                        data.router.push(`/communities/community/$***REMOVED***data.community.objects[key].id***REMOVED***`)
                    ***REMOVED***
                ***REMOVED*** else ***REMOVED***
                    createAlert(new alert('ユーザー情報を取得することに失敗しました。', 2))
                ***REMOVED***
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
                getCommunities()
                addPageEvent('pageMostBottom', () => ***REMOVED***getCommunities()***REMOVED***)
            ***REMOVED***)
            return ***REMOVED*** data, createCommunity, goToCommunity ***REMOVED***
        ***REMOVED***,
        beforeRouteLeave (to, from, next) ***REMOVED***
            removeAtAllFunc()
            next()
        ***REMOVED***
    ***REMOVED***
</script>