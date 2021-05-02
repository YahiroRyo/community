<template>
    <div>
        <link rel="stylesheet" href="/css/components/communities/communities.css">
        <!-- タブ -->
        <div class="tabs-wapper" style="display: flex;">
            <div @click="data.router.push('/communities/0')" :class="***REMOVED***'tab': true, 'tab_selecting': data.route.params.page == 0 ***REMOVED***">
                <p class="communities-tab__title">コミュニティを選択</p>
            </div>
            <div @click="data.router.push('/communities/1')" :class="***REMOVED***'tab': true, 'tab_selecting': data.route.params.page == 1***REMOVED***">
                <p class="communities-tab__title">コミュニティを作成</p>
            </div>
        </div>
        <!-- タブの選択に対しての画面 0 -->
        <transition name="router-view-anim">
            <!-- コミュニティを一覧表示 -->
            <div v-show="data.route.params.page == 0" class="communities-page" key="communities-page-0">
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
            <div v-show="data.route.params.page == 1" class="form__wapper m-t-3" appear>
                <Form @click="data.createCommunity.name.isClick = true" v-model:inputContent="validate.name.$model" class="form" label="コミュニティ名" uniqueClassKey="0" 
                    :validate="validate.name.$errors.length > 0 && data.createCommunity.name.isClick"
                    :error="validate.name.required.$invalid ? 'コミュニティ名は必須入力です。' :
                            validate.name.maxLength.$invalid ? '50文字を超過しています。' : null"
                />
                <Form @click="data.createCommunity.description.isClick = true" v-model:inputContent="validate.description.$model" :useTextArea="true" class="form" label="コミュニティの説明" uniqueClassKey="1"
                    :validate="validate.description.$errors.length > 0 && data.createCommunity.description.isClick"
                    :error="validate.description.required.$invalid ? 'コミュニティの説明は必須入力です。' :
                            validate.description.maxLength.$invalid ? '500文字を超過しています。' : null"
                />
                <button :disabled="validate.$invalid" @click="createCommunity()" class="form__btn">作成</button>
            </div>
        </transition>
    </div>
</template>

<script>
    import ***REMOVED*** reactive, watch, onMounted, onBeforeMount, toRef ***REMOVED*** from 'vue'
    import ***REMOVED*** createAlert, alert, notNormalTokenAlert ***REMOVED***          from '../../alert.js'
    import ***REMOVED*** antiLoginUser, antiNotLoginUser ***REMOVED***                  from '../../router.js'
    import ***REMOVED*** addPageEvent, removeAtAllFunc ***REMOVED***                    from '../../page.js'
    import ***REMOVED*** useRouter, useRoute ***REMOVED***                              from 'vue-router'
    import ***REMOVED*** getUidAndToken ***REMOVED***                                   from '../../supportFirebase.js'
    import firebase                                             from 'firebase'
    import axios                                                from 'axios'
    /* ---------------validation関係--------------- */
    import ***REMOVED*** required, minLength, maxLength  ***REMOVED*** from "@vuelidate/validators"
    import ***REMOVED*** useVuelidate ***REMOVED*** from "@vuelidate/core"

    /* ---------------コンポーネントをインポート--------------- */
    import Form                                         from '../Form.vue'

    export default ***REMOVED***
        components: ***REMOVED***
            Form
        ***REMOVED***,
        setup() ***REMOVED***
            const data = reactive(***REMOVED***
                router: useRouter(),
                route:  useRoute(),
                community: ***REMOVED***
                    take:       50,
                    gotNum:     0,
                    objects:    [],
                    cantTake:   false,
                ***REMOVED***,
                createCommunity: ***REMOVED***
                    name: ***REMOVED***
                        content: localStorage.getItem('name') ? localStorage.getItem('name') : '',
                        isClick: false,
                    ***REMOVED***,
                    description: ***REMOVED***
                        content: localStorage.getItem('description') ? localStorage.getItem('description') : '',
                        isClick: false,
                    ***REMOVED***,
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
                if (validate.value.$invalid) ***REMOVED***
                    createAlert(new alert('不正な値です。', 2))
                    return
                ***REMOVED***
                const user = await getUidAndToken()
                if (!user.isError) ***REMOVED***
                    const createCommunityInfos = ***REMOVED***
                        uid: user.uid,
                        token: user.token,
                        name: data.createCommunity.name.content,
                        description: data.createCommunity.description.content,
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
                            data.router.go('communities/1')
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
                        data.router.push(`/community/$***REMOVED***data.community.objects[key].id***REMOVED***`)
                    ***REMOVED***
                ***REMOVED*** else ***REMOVED***
                    createAlert(new alert('ユーザー情報を取得することに失敗しました。', 2))
                ***REMOVED***
            ***REMOVED***
            const rules = ***REMOVED***
                name:                   ***REMOVED*** required, maxLength: maxLength(50), ***REMOVED***,
                description:            ***REMOVED*** required, maxLength: maxLength(500), ***REMOVED***,
            ***REMOVED***
            const validate = useVuelidate(rules, ***REMOVED***
                name:                   toRef(data.createCommunity.name, 'content'),
                description:            toRef(data.createCommunity.description, 'content'),
            ***REMOVED***)
            
            /* ---------------createCommunity変数について--------------- */
            // 他のURLに飛ぶと値が消滅してしまうため、ローカルストレージに入力した値を保存
            watch(() => data.createCommunity.name, () => ***REMOVED***
                localStorage.setItem('name', data.createCommunity.name) 
            ***REMOVED***)
            watch(() => data.createCommunity.description, () => ***REMOVED***
                localStorage.setItem('description', data.createCommunity.description) 
            ***REMOVED***)
            onBeforeMount(() => ***REMOVED***
                antiNotLoginUser()
            ***REMOVED***)
            onMounted(() => ***REMOVED***
                getCommunities()
                addPageEvent('pageMostBottom', () => ***REMOVED***getCommunities()***REMOVED***)
                validate.value.$touch()
            ***REMOVED***)
            return ***REMOVED*** data, createCommunity, goToCommunity, validate ***REMOVED***
        ***REMOVED***,
        beforeRouteLeave (to, from, next) ***REMOVED***
            removeAtAllFunc()
            next()
        ***REMOVED***
    ***REMOVED***
</script>