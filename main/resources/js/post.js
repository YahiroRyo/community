/* ---------------postクラスについて--------------- */
// ・変数の説明
// name:        投稿をした主の名前
// userName:    投稿をした主のユーザーネーム
// content:     投稿をした主が投稿した文章
// isGood:      投稿に対して自分がいいねを押したか
// goodNum:     投稿のいいね数
// responceNum: 投稿への返信数
// postId:      投稿のID
import ***REMOVED*** createAlert, alert, notNormalTokenAlert ***REMOVED***  from './alert.js'
import ***REMOVED*** getUidAndToken ***REMOVED***                           from './supportFirebase.js'
import ***REMOVED*** displayWindow ***REMOVED***                            from './window.js'
import ***REMOVED*** store ***REMOVED***                                    from './store.js'
import axios                                        from 'axios' 

class post ***REMOVED***
    constructor(name, userName, content, isGood, goodNum, responceNum, postId, communityId) ***REMOVED***
        this.name           = name
        this.userName       = userName
        this.content        = content
        this.isGood         = isGood
        this.goodNum        = goodNum
        this.responceNum    = responceNum
        this.postId         = postId
        this.communityId    = communityId
    ***REMOVED***
***REMOVED***
const sendGood = async(postObj) => ***REMOVED***
    if (store.state.user.isLogin) ***REMOVED***
        const user = await getUidAndToken()
        const greatPostInfos = ***REMOVED***
            postId: postObj.postId,
            token:  user.token,
            uid:    user.uid,
        ***REMOVED***
        axios.post('/api/post/great-post', greatPostInfos)
        .then((responce) => ***REMOVED***
            if (responce.data.isNormalToken) ***REMOVED***
                if (responce.data.isGreat) ***REMOVED***
                    postObj.isGood = !postObj.isGood
                    postObj.isGood ? postObj.goodNum++ : postObj.goodNum--
                ***REMOVED*** else ***REMOVED***
                    createAlert(new alert('いいねすることができませんでした。', 2))
                ***REMOVED***
            ***REMOVED*** else ***REMOVED***
                notNormalTokenAlert()
            ***REMOVED***
        ***REMOVED***)
    ***REMOVED*** else ***REMOVED***
        displayWindow(5)
    ***REMOVED***
***REMOVED***

export ***REMOVED*** post, sendGood ***REMOVED***