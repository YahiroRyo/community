<template>
    <div>
        <link rel="stylesheet" href="/css/components/bell/bell.css">
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

    import { reactive, onMounted } from 'vue'
    import { useRouter } from 'vue-router'
    import { setOpenFunction, setCloseFunction, createWindow, closeWindow } from '../../window'
    import firebase from 'firebase'
    import axios from 'axios'

    export default {
        setup() {
            const data = reactive({
                bell: {
                    objects: [],
                    take: 50,
                    gotNum: 0,
                },
                router: useRouter(),
            })
            const getBells = async() => {
                await firebase.auth().onAuthStateChanged(async(user) => {
                    if (user) {
                        const bellsInfos = {
                            params: {
                                uid: user.uid,
                                take: data.bell.take,
                                gotNum: data.bell.gotNum,
                            },
                        }
                        await axios.get('/api/get/bells', bellsInfos)
                        .then((responce) => {
                            console.log(responce)
                            responce.data.forEach((obj) => {
                                data.bell.objects.push(new bell(obj.type, obj.id, obj.dataForType))
                            })
                        })
                    }
                })
            }
            const joinApp = (communityId, userId, bellId) => {
                // 申請を通す
                // bellは消去
                
            }
            const joinDisallow = (userId, bellId) => {
                // 申請削除
                // bellは消去

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