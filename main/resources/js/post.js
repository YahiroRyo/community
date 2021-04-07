/* ---------------postクラスについて--------------- */
// ・変数の説明
// name:        投稿をした主の名前
// userName:    投稿をした主のユーザーネーム
// content:     投稿をした主が投稿した文章
// isGood:      投稿に対して自分がいいねを押したか
// goodNum:     投稿のいいね数
// responceNum: 投稿への返信数
// postId:      投稿のID
import { createAlert, alert, notNormalTokenAlert }  from './alert.js'
import { getUidAndToken }                           from './supportFirebase.js'
import { displayWindow }                            from './window.js'
import { store }                                    from './store.js'
import axios                                        from 'axios' 

class post {
    constructor(name, userName, content, isGood, goodNum, responceNum, postId, communityId, imageName, imageNames, isMainPost) {
        this.name           = name
        this.userName       = userName
        this.content        = content
        this.isGood         = isGood
        this.goodNum        = goodNum
        this.responceNum    = responceNum
        this.postId         = postId
        this.communityId    = communityId
        this.imageName      = imageName
        this.imageNames     = imageNames
        this.isMainPost     = isMainPost
    }
}
const sendGood = async(postObj) => {
    if (store.state.user.isLogin) {
        const user = await getUidAndToken()
        const greatPostInfos = {
            postId: postObj.postId,
            token:  user.token,
            uid:    user.uid,
        }
        axios.post('/api/post/great-post', greatPostInfos)
        .then((responce) => {
            if (responce.data.isNormalToken) {
                if (responce.data.isGreat) {
                    postObj.isGood = !postObj.isGood
                    postObj.isGood ? postObj.goodNum++ : postObj.goodNum--
                } else {
                    createAlert(new alert('いいねすることができませんでした。', 2))
                }
            } else {
                notNormalTokenAlert()
            }
        })
    } else {
        displayWindow(5)
    }
}

export { post, sendGood }