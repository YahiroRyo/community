<template>
    <div>
        <link rel="stylesheet" href="/css/components/bell/bell.css">
        <template v-if="data.bell.objects.length > 0">
            <div class="post" v-for="(bell, key) in data.bell.objects" :key="key">
                <div class="post__flex">
                    <p class="post__font" v-if="bell.type === 1">
                        <p class="bell__go-to-profile" @click="goToProfile(bell.dataForType.user_info.user_name)">{{bell.dataForType.user_info.name}}</p>
                        が{{bell.dataForType.community.name}}へ入りたいと加入申請をしています。
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
    class bell {
        constructor(type, id, dataForType) {
            this.type = type
            this.id = id
            this.dataForType = dataForType
        }
    }

    import { setOpenFunction, setCloseFunction, createWindow, closeWindow } from '../../window'
    import { createAlert, alert, notNormalTokenAlert }                      from '../../alert'
    import { reactive, onMounted }                                          from 'vue'
    import { getUidAndToken }                                               from '../../supportFirebase.js'
    import { useRouter }                                                    from 'vue-router'
    import firebase                                                         from 'firebase'
    import axios                                                            from 'axios'

    export default {
        setup() {
            const data = reactive({
                bell: {
                    objects: [],
                    take: 50,
                    gotNum: 0,
                    isCantTake: false,
                },
                router: useRouter(),
            })
            const getBells = async() => {
                if (!data.bell.cantTake) {
                    await firebase.auth().onAuthStateChanged(async(user) => {
                        if (user) {
                            const bellsInfos = {
                                params: {
                                    gotNum: data.bell.gotNum,
                                    take:   data.bell.take,
                                    uid:    user.uid,
                                },
                            }
                            await axios.get('/api/get/bells', bellsInfos)
                            .then((responce) => {
                                if (responce.data.isGetBells) {
                                    data.bell.gotNum += data.bell.take
                                    if (responce.data.length < data.bell.take)
                                        data.bell.cantTake = true
                                    responce.data = responce.data.bells.filter((obj) => obj.dataForType !== null)
                                    responce.data.forEach((obj) => {
                                        data.bell.objects.push(new bell(obj.type, obj.id, obj.dataForType))
                                    })
                                } else {
                                    createAlert(new alert('通知を取得することができませんでした。', 2))
                                }
                            })
                        }
                    })
                }
            }
            const joinApp = async(communityId, userId, bellId) => {
                // 申請を通す
                // bellは消去
                const user = await getUidAndToken()
                const joinCommunityInfos = {
                    communityId:    communityId,
                    userId:         userId,
                    bellId:         bellId,
                    token:          user.token,
                }
                axios.post('/api/post/join-community', joinCommunityInfos)
                .then((responce) => {
                    if (responce.data.isNormalToken) {
                        if (responce.data.isJoinCommunity) {
                            createAlert(new alert('加入申請を許可しました。', 0))
                            data.bell.objects = []
                            data.bell.gotNum = 0
                            data.bell.cantTake = false
                            getBells()
                        } else {
                            createAlert(new alert('加入申請を許可することができませんでした。', 2))
                        }
                    } else {
                        notNormalTokenAlert()
                    }
                })
            }
            const joinDisallow = async(userId, bellId) => {
                // 申請削除
                // bellは消去
                await firebase.auth().onAuthStateChanged(async(user) => {
                    if (user) {
                        await user.getIdTokenResult().then((responce) => {
                            const dontJoinCommunityInfos = {
                                userId: userId,
                                bellId: bellId,
                                token:  responce.token,
                            }
                            axios.post('/api/post/dont-join-community', dontJoinCommunityInfos)
                            .then((responce) => {
                                if (responce.data.isNormalToken) {
                                    if (responce.data.isDontJoinCommunity) {
                                        createAlert(new alert('加入申請を拒否しました。', 0))
                                        data.bell.cantTake  = false
                                        data.bell.objects   = []
                                        data.bell.gotNum    = 0
                                        getBells()
                                    } else {
                                        createAlert(new alert('加入申請の拒否に失敗しました。', 2))
                                    }
                                } else {
                                    notNormalTokenAlert()
                                }
                            })
                        })
                    }
                })
            }
            const goToProfile = (userName) => {
                closeWindow()
                data.router.push(`/profile/${userName}`)
            }
            onMounted(() => {
                createWindow('通知', 1000, 750)
                getBells()
            })
            return { data, goToProfile, joinApp, joinDisallow }
        }
    }
</script>