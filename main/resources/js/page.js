//ページに特定の変化が起きた場合に、特定のメソッドを実行する。

/*
・使用方法・仕組み
ページに特定の変化が起きた場合に、特定のメソッドを実行する。
特定の変化には命名がなされており、例えばページの最下部までスクロールしたときに実行されるというイベントだった場合、
'pageMostBottom'という命名がされている。
これとメソッドをaddPageEventの引数で実行することで外部のファイルからメソッドをフックすることで使用することができる。
*/
//TODO: イベントを実行してから100秒のクールタイムを入れることを許容すべきか検討

let pageBottomFunc = null   //pageが最下部に来たときに実行されるメソッド
let isUsePageBottomFunc = false
let pageMostBottom = 0  //pageの最下部
let scrollHeight = 0    //bodyの高さ
let isUseInit = false   //初期化フラグ

const getScrollHeight = () => {  return document.body.clientHeight }
const getPageMostBottom = () => { return scrollHeight - window.innerHeight }
const initScroll = () => {
    //ページの高さが確定するまでに、時間がかかるためsetTimeoutを用いてロードを待つ
    //TODO: ロードのアニメーションを追加するかも
    setTimeout(() => {
        scrollHeight = getScrollHeight()
        pageMostBottom = getPageMostBottom()
        isUseInit = true
    }, 100)
}

window.addEventListener('scroll', () => {
    if (!isUseInit) { initScroll() }
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop
    if (scrollTop >= pageMostBottom) {
        if (pageBottomFunc && !isUsePageBottomFunc) {
            pageBottomFunc()
            isUsePageBottomFunc = true
            setTimeout(() => {
                isUsePageBottomFunc = false
            }, 100)
        }
    }
})
const addPageEvent = (eventName, func) => {
    if (eventName == 'pageMostBottom') { pageBottomFunc = func }
}

//ページが遷移する際に使わなければならないメソッド
//すべてのフックメソッドを初期化する
const removeAtAllFunc = () => {
    pageBottomFunc = null
    isUseInit = false
}

export { addPageEvent, removeAtAllFunc }