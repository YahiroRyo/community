<template>
    <div>
        <link rel="stylesheet" href="/css/components/communities/communities.css">
        <!-- タブ -->
        <div class="tabs-wapper" style="display: flex;">
            <div @click="data.router.push('/communities/0')" :class="{'tab': true, 'tab_selecting': data.route.params.page == 0 }">
                <p class="communities-tab__title">コミュニティを選択</p>
            </div>
            <div @click="data.router.push('/communities/1')" :class="{'tab': true, 'tab_selecting': data.route.params.page == 1}">
                <p class="communities-tab__title">コミュニティを作成</p>
            </div>
        </div>
        <!-- タブの選択に対しての画面 0 -->
        <transition name="router-view-anim">
            <!-- コミュニティを一覧表示 -->
            <div v-show="data.route.params.page == 0" class="communities-page" key="communities-page-0">
                <div class="post" v-for="(community, key) in data.community.objects" :key="key">
                    <div class="post__flex">
                        <p class="post__name">{{community.name}}</p>
                    </div>
                    <div class="post__flex">
                        <p class="post__font">{{community.description}}</p>
                        <button @click="goToCommunity(key)" class="communitiest__btn">{{community.type === 0 ? '加入申請' : community.type === 1 ? '加入申請中' : '入る'}}</button>
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
    import { reactive, watch, onMounted, onBeforeMount, toRef } from 'vue'
    import { createAlert, alert, notNormalTokenAlert }          from '../../alert.js'
    import { antiLoginUser, antiNotLoginUser }                  from '../../router.js'
    import { addPageEvent, removeAtAllFunc }                    from '../../page.js'
    import { useRouter, useRoute }                              from 'vue-router'
    import { getUidAndToken }                                   from '../../supportFirebase.js'
    import firebase                                             from 'firebase'
    import axios                                                from 'axios'
    /* ---------------validation関係--------------- */
    import { required, minLength, maxLength  } from "@vuelidate/validators"
    import { useVuelidate } from "@vuelidate/core"

    /* ---------------コンポーネントをインポート--------------- */
    import Form                                         from '../Form.vue'

    export default {
        components: {
            Form
        },
        setup() {
            const data = reactive({
                router: useRouter(),
                route:  useRoute(),
                community: {
                    take:       50,
                    gotNum:     0,
                    objects:    [],
                    cantTake:   false,
                },
                createCommunity: {
                    name: {
                        content: localStorage.getItem('name') ? localStorage.getItem('name') : '',
                        isClick: false,
                    },
                    description: {
                        content: localStorage.getItem('description') ? localStorage.getItem('description') : '',
                        isClick: false,
                    },
                },
            })
            const getCommunities = async() => {
                if (!data.community.cantTake) {
                    const user = await getUidAndToken()
                    if (!user.isError) {
                        const getCommunitiesInfos = {
                            params: {
                                uid: user.uid,
                                take: data.community.take,
                                gotNum: data.community.gotNum,
                            }
                        }
                        await axios.get('/api/get/communities', getCommunitiesInfos)
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
                                        type: community.is_joining_community !== null ? 2 : community.can_i_join_community !== null ? 1 : 0,
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
                    } else {
                        createAlert(new alert('ユーザー情報を取得することに失敗しました。', 2))
                        // 失敗した場合は、ホームに飛ぶ
                        setTimeout(() => {
                            data.router.push('/')
                        }, 50)
                    }
                }
            }
            const createCommunity = async() => {
                if (validate.value.$invalid) {
                    createAlert(new alert('不正な値です。', 2))
                    return
                }
                const user = await getUidAndToken()
                if (!user.isError) {
                    const createCommunityInfos = {
                        uid: user.uid,
                        token: user.token,
                        name: data.createCommunity.name.content,
                        description: data.createCommunity.description.content,
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
                            data.router.go('communities/1')
                        } else if (!responce.data.isNormalToken) {
                            notNormalTokenAlert()
                        } else {
                            createAlert(new alert('コミュニティの作成に失敗しました。', 2))
                        }
                    })
                } else {
                    createAlert(new alert('コミュニティの作成に失敗しました。', 2))
                }
            }
            const goToCommunity = async(key) => {
                /* ---------------TODO: コミュニティに入る作業--------------- */
                const user = await getUidAndToken()
                if (!user.isError) {
                    if (data.community.objects[key].type === 0) {
                        // 加入申請を送信
                        const canIJoinCommunityInfos = {
                            uid: user.uid,
                            token: user.token,
                            communityId: data.community.objects[key].id,
                        }
                        axios.post('/api/post/can-i-join-community', canIJoinCommunityInfos)
                        .then((responce) => {
                            if (responce.data.isNormalToken) {
                                if (responce.data.isCanIJoinCommunity) {
                                    data.community.objects[key].type = 1
                                    createAlert(new alert('加入申請をしました。', 0))
                                } else {
                                    createAlert(new alert('加入申請に失敗しました。', 2))
                                }
                            } else {
                                notNormalTokenAlert()
                            }
                        })
                    } else if (data.community.objects[key].type === 1) {
                        // 加入申請を取り消し
                        const cancelJoinCommunityInfos = {
                            uid: user.uid,
                            token: usersToken,
                            communityId: data.community.objects[key].id,
                        }
                        axios.post('/api/post/cancel-join-community', cancelJoinCommunityInfos)
                        .then((responce) => {
                            if (responce.data.isNormalToken) {
                                if (responce.data.isCancelJoinCommunity) {
                                    data.community.objects[key].type = 0
                                    createAlert(new alert('加入申請を取り消しました。', 0))
                                } else {
                                    createAlert(new alert('加入申請の取り消しに失敗しました', 2))
                                }
                            } else {
                                notNormalTokenAlert()
                            }
                        })
                    } else {
                        // ルームへ入る
                        data.router.push(`/community/${data.community.objects[key].id}`)
                    }
                } else {
                    createAlert(new alert('ユーザー情報を取得することに失敗しました。', 2))
                }
            }
            const rules = {
                name:                   { required, maxLength: maxLength(50), },
                description:            { required, maxLength: maxLength(500), },
            }
            const validate = useVuelidate(rules, {
                name:                   toRef(data.createCommunity.name, 'content'),
                description:            toRef(data.createCommunity.description, 'content'),
            })
            
            /* ---------------createCommunity変数について--------------- */
            // 他のURLに飛ぶと値が消滅してしまうため、ローカルストレージに入力した値を保存
            watch(() => data.createCommunity.name, () => {
                localStorage.setItem('name', data.createCommunity.name) 
            })
            watch(() => data.createCommunity.description, () => {
                localStorage.setItem('description', data.createCommunity.description) 
            })
            onBeforeMount(() => {
                antiNotLoginUser()
            })
            onMounted(() => {
                getCommunities()
                addPageEvent('pageMostBottom', () => {getCommunities()})
                validate.value.$touch()
            })
            return { data, createCommunity, goToCommunity, validate }
        },
        beforeRouteLeave (to, from, next) {
            removeAtAllFunc()
            next()
        }
    }
</script>