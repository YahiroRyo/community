<template>
    <div>
        <link rel="stylesheet" href="/css/components/communities/communities.css">
        <!-- タブ -->
        <div class="tabs-wapper" style="display: flex;">
            <div @click="data.page = false" :class="{'tab': true, 'tab_selecting': !data.page }">
                <p class="communities-tab__title">コミュニティを選択</p>
            </div>
            <div @click="data.page = true" :class="{'tab': true, 'tab_selecting': data.page}">
                <p class="communities-tab__title">コミュニティを作成</p>
            </div>
        </div>
        <!-- タブの選択に対しての画面 0 -->
        <transition name="router-view-anim">
            <!-- コミュニティを一覧表示 -->
            <div v-show="!data.page" class="communities-page" key="communities-page-0">
                <div class="post" v-for="(community, key) in data.community.objects" :key="key">
                    <div class="post__flex">
                        <p class="post__name">{{community.name}}</p>
                    </div>
                    <div class="post__flex">
                        <p class="post__font">{{community.description}}</p>
                        <button @click="goToCommunity(key)" class="communitiest__btn">入る</button>
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
                <button @click="createCommunity()" class="form form_btn">作成</button>
            </div>
        </transition>
    </div>
</template>

<script>
    import { reactive, watch, onMounted } from 'vue'
    import { createAlert, alert } from '../../alert.js'
    import { addPageEvent, removeAtAllFunc } from '../../page.js'
    import { useRouter } from 'vue-router'
    import axios from 'axios'

    export default {
        setup() {
            const data = reactive({
                page: false,
                router: useRouter(),
                community: {
                    getNum: 0,
                    take: 50,
                    cantTake: false,
                    objects: [],
                },
                createCommunity: {
                    name: localStorage.getItem('name') ? localStorage.getItem('name') : '',
                    description: localStorage.getItem('description') ? localStorage.getItem('description') : '',
                },
            })
            const getCommunities = () => {
                if (!data.cantTake) {
                    const getCommunitiesInfos = {
                        params: {
                            take: data.community.take,
                            gotNum: data.community.gotNum,
                        }
                    }
                    axios.get('/api/get/communities', getCommunitiesInfos)
                    .then((responce) => {
                        if (responce.data.isGetCommunities) {
                            data.community.gotNum += data.community.take
                            // 取得しようとしていた数よりも少なかった場合は、それ以上のデータがない。
                            // 従って、これ以上取得できないように設定
                            if (data.community.gotNum > responce.data.communities.length)
                                data.community.cantTake = true
                            responce.data.communities.forEach((community) => {
                                data.community.objects.push({
                                    name: community.name,
                                    description: community.description,
                                    id: community.id,
                                })
                            })
                        } else {
                            createAlert(new alert('コミュニティの取得に失敗しました。', 2))
                            // 失敗した場合は、ホームに飛ぶ
                            setTimeout(() => {
                                data.router.push('/')
                            }, 50)
                        }
                    })
                }
            }
            const createCommunity = () => {
                /* ---------------TODO: サーバーへコミュニティを作成するajax処理を実装--------------- */
                const createCommunityInfos = {
                    uid: localStorage.getItem('uid'),
                    token: localStorage.getItem('token'),
                    name: data.createCommunity.name,
                    description: data.createCommunity.description,
                }
                axios.post('/api/post/create-community', createCommunityInfos)
                .then((responce) => {
                    if (responce.data.isCreateCommunity) {
                        createAlert(new alert('コミュニティを作成しました。', 0))
                        // v-modelの内容とlocalStorageの内容を初期化
                        data.createCommunity.name = ''
                        data.createCommunity.description = ''
                        localStorage.removeItem('name')
                        localStorage.removeItem('description')
                    } else if (!responce.data.isNormalToken) {
                        createAlert(new alert('無効なアクセストークンのためログアウトします。', 2))
                        // data.router.pushをそのまま実行すると何故か実行されないため、setTimeoutを用いる
                        setTimeout(() => {
                            data.router.push('/logout')
                        }, 50)
                    } else {
                        createAlert(new alert('コミュニティの作成に失敗しました。', 2))
                    }
                })
            }
            const goToCommunity = (key) => {
                /* ---------------TODO: コミュニティに入る作業--------------- */
                // 仮に入るとする
                data.router.push(`/communities/community/${data.community.objects[key].id}`)
            }
            
            /* ---------------createCommunity変数について--------------- */
            // 他のURLに飛ぶと値が消滅してしまうため、ローカルストレージに入力した値を保存
            watch(() => data.createCommunity.name, () => {
                localStorage.setItem('name', data.createCommunity.name) 
            })
            watch(() => data.createCommunity.description, () => {
                localStorage.setItem('description', data.createCommunity.description) 
            })

            onMounted(() => {
                /* ---------------TODO: サーバーからコミュニティデータを取得するajax処理を実装--------------- */
                getCommunities()
                addPageEvent('pageMostBottom', () => {getCommunities()})
            })
            return { data, createCommunity, goToCommunity }
        },
        beforeRouteLeave (to, from, next) {
            removeAtAllFunc()
            next()
        }
    }
</script>