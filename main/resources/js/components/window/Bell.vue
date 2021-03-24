<template>
    <div>
        <link rel="stylesheet" href="/css/components/bell/bell.css">
        <template v-if="data.bell.objects.length > 0">
            <div class="post" v-for="(bell, key) in data.bell.objects" :key="key">
                <div class="post__flex">
                    <p class="post__font" v-if="bell.type === 1">
                        <p class="bell__go-to-profile" @click="goToProfile(bell.dataForType.user_info.user_name)">***REMOVED******REMOVED***bell.dataForType.user_info.name***REMOVED******REMOVED***</p>
                        が***REMOVED******REMOVED***bell.dataForType.community.name***REMOVED******REMOVED***へ入りたいと加入申請をしています。
                    </p>
                    <button v-if="bell.type === 1" @click="joinApp(bell.dataForType.community_id, bell.dataForType.user_id, bell.id)" class="bell__btn-app">申請を許可する</button>
                    <button v-if="bell.type === 1" @click="joinDisallow(bell.dataForType.user_id, bell.id)" class="bell__btn-disallow">申請を許可しない</button>
                </div>
            </div>
        </template>
        <template v-else>
            <h1 class="bell__not-exist">通知がないです。</h1>
        </template>
    </div>
</template>

<script>
    class bell ***REMOVED***
        constructor(type, id, dataForType) ***REMOVED***
            this.type = type
            this.id = id
            this.dataForType = dataForType
        ***REMOVED***
    ***REMOVED***

    import ***REMOVED*** reactive, onMounted ***REMOVED*** from 'vue'
    import ***REMOVED*** useRouter ***REMOVED*** from 'vue-router'
    import ***REMOVED*** setOpenFunction, setCloseFunction, createWindow, closeWindow ***REMOVED*** from '../../window'
    import ***REMOVED*** createAlert, alert, notNormalTokenAlert ***REMOVED*** from '../../alert'
    import firebase from 'firebase'
    import axios from 'axios'

    export default ***REMOVED***
        setup() ***REMOVED***
            const data = reactive(***REMOVED***
                bell: ***REMOVED***
                    objects: [],
                    take: 50,
                    gotNum: 0,
                    isCantTake: false,
                ***REMOVED***,
                router: useRouter(),
            ***REMOVED***)
            const getBells = async() => ***REMOVED***
                if (!data.bell.cantTake) ***REMOVED***
                    await firebase.auth().onAuthStateChanged(async(user) => ***REMOVED***
                        if (user) ***REMOVED***
                            const bellsInfos = ***REMOVED***
                                params: ***REMOVED***
                                    uid: user.uid,
                                    take: data.bell.take,
                                    gotNum: data.bell.gotNum,
                                ***REMOVED***,
                            ***REMOVED***
                            await axios.get('/api/get/bells', bellsInfos)
                            .then((responce) => ***REMOVED***
                                data.bell.gotNum += data.bell.take
                                if (responce.data.length < data.bell.take)
                                    data.bell.cantTake = true
                                responce.data = responce.data.filter((obj) => obj.dataForType !== null)
                                responce.data.forEach((obj) => ***REMOVED***
                                    data.bell.objects.push(new bell(obj.type, obj.id, obj.dataForType))
                                ***REMOVED***)
                            ***REMOVED***)
                        ***REMOVED***
                    ***REMOVED***)
                ***REMOVED***
            ***REMOVED***
            const joinApp = async(communityId, userId, bellId) => ***REMOVED***
                // 申請を通す
                // bellは消去
                await firebase.auth().onAuthStateChanged(async(user) => ***REMOVED***
                    if (user) ***REMOVED***
                        await user.getIdTokenResult().then((responce) => ***REMOVED***
                            const joinCommunityInfos = ***REMOVED***
                                token: responce.token,
                                communityId: communityId,
                                userId: userId,
                                bellId: bellId,
                            ***REMOVED***
                            axios.post('/api/post/join-community', joinCommunityInfos)
                            .then((responce) => ***REMOVED***
                                if (responce.data.isNormalToken) ***REMOVED***
                                    if (responce.data.isJoinCommunity) ***REMOVED***
                                        createAlert(new alert('加入申請を許可しました。', 0))
                                        data.bell.objects = []
                                        data.bell.gotNum = 0
                                        data.bell.cantTake = false
                                        getBells()
                                    ***REMOVED*** else ***REMOVED***
                                        createAlert(new alert('加入申請を許可することができませんでした。', 2))
                                    ***REMOVED***
                                ***REMOVED*** else ***REMOVED***
                                    notNormalTokenAlert()
                                ***REMOVED***
                            ***REMOVED***)
                        ***REMOVED***)
                    ***REMOVED***
                ***REMOVED***)
            ***REMOVED***
            const joinDisallow = async(userId, bellId) => ***REMOVED***
                // 申請削除
                // bellは消去
                await firebase.auth().onAuthStateChanged(async(user) => ***REMOVED***
                    if (user) ***REMOVED***
                        await user.getIdTokenResult().then((responce) => ***REMOVED***
                            const dontJoinCommunityInfos = ***REMOVED***
                                token: responce.token,
                                userId: userId,
                                bellId: bellId,
                            ***REMOVED***
                            axios.post('/api/post/dont-join-community', dontJoinCommunityInfos)
                            .then((responce) => ***REMOVED***
                                if (responce.data.isNormalToken) ***REMOVED***
                                    if (responce.data.isDontJoinCommunity) ***REMOVED***
                                        createAlert(new alert('加入申請を拒否しました。', 0))
                                        data.bell.objects = []
                                        data.bell.gotNum = 0
                                        data.bell.cantTake = false
                                        getBells()
                                    ***REMOVED*** else ***REMOVED***
                                        createAlert(new alert('加入申請の拒否に失敗しました。', 2))
                                    ***REMOVED***
                                ***REMOVED*** else ***REMOVED***
                                    notNormalTokenAlert()
                                ***REMOVED***
                            ***REMOVED***)
                        ***REMOVED***)
                    ***REMOVED***
                ***REMOVED***)
            ***REMOVED***
            const goToProfile = (userName) => ***REMOVED***
                closeWindow()
                data.router.push(`/profile/$***REMOVED***userName***REMOVED***`)
            ***REMOVED***
            onMounted(() => ***REMOVED***
                createWindow('通知', 1000, 750)
                getBells()
            ***REMOVED***)
            return ***REMOVED*** data, goToProfile, joinApp, joinDisallow ***REMOVED***
        ***REMOVED***
    ***REMOVED***
</script>