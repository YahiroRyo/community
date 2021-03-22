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

const getScrollHeight = () => ***REMOVED***  return document.body.clientHeight ***REMOVED***
const getPageMostBottom = () => ***REMOVED*** return scrollHeight - window.innerHeight ***REMOVED***
const initScroll = () => ***REMOVED***
    //ページの高さが確定するまでに、時間がかかるためsetTimeoutを用いてロードを待つ
    //TODO: ロードのアニメーションを追加するかも
    setTimeout(() => ***REMOVED***
        scrollHeight = getScrollHeight()
        pageMostBottom = getPageMostBottom()
        isUseInit = true
    ***REMOVED***, 100)
***REMOVED***

window.addEventListener('scroll', () => ***REMOVED***
    if (!isUseInit) ***REMOVED*** initScroll() ***REMOVED***
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop
    if (scrollTop >= pageMostBottom) ***REMOVED***
        if (pageBottomFunc && !isUsePageBottomFunc) ***REMOVED***
            pageBottomFunc()
            isUsePageBottomFunc = true
            setTimeout(() => ***REMOVED***
                isUsePageBottomFunc = false
            ***REMOVED***, 100)
        ***REMOVED***
    ***REMOVED***
***REMOVED***)
const addPageEvent = (eventName, func) => ***REMOVED***
    if (eventName == 'pageMostBottom') ***REMOVED*** pageBottomFunc = func ***REMOVED***
***REMOVED***

//ページが遷移する際に使わなければならないメソッド
//すべてのフックメソッドを初期化する
const removeAtAllFunc = () => ***REMOVED***
    pageBottomFunc = null
    isUseInit = false
***REMOVED***

export ***REMOVED*** addPageEvent, removeAtAllFunc ***REMOVED***