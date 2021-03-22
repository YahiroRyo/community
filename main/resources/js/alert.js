/* ---------------alertの使い方--------------- */
// ・alertを表示したい場合
// createAlertを用いて引数にalertクラスをインスタンス化した物を代入
// alertの引数は表示したい文章、背景の色となっている

// ・alertクラスのtypeの番号に対する背景色一覧
// 0: success   緑色
// 1: attention 黄色
// 2: danger    赤色

// ・alertを複数に表示する場合
// alertが沢山表示されると操作範囲が非常に狭まるため、仕様上できないよう実装
// ※ alertが表示され終わったら、次のalertが表示される。 ※

// ・storeにあるalertのグローバル変数の説明
// object:          alertのオブジェクトを代入
// waitIntervals:   alertを監視しているIntervalの戻り値を格納

import ***REMOVED*** store ***REMOVED*** from './store'
import ***REMOVED*** router ***REMOVED*** from './router'

class alert ***REMOVED***
    constructor(content, type) ***REMOVED***
        this.content    = content
        this.type       = type
    ***REMOVED***
***REMOVED***

const addAlert = (alertObj) => ***REMOVED***
    store.state.alert.object = alertObj
    setTimeout(() => ***REMOVED*** store.state.alert.object = null ***REMOVED***, 5000)
***REMOVED***
const createAlert = (alertObj) => ***REMOVED***
    if (store.state.alert.object === 0) ***REMOVED***
        addAlert(alertObj)
    ***REMOVED*** else ***REMOVED***
        const interval = setInterval((alertObj) => ***REMOVED***
            if (store.state.alert.object === null) ***REMOVED***
                clearInterval(store.state.alert.waitIntervals[0])
                store.state.alert.waitIntervals.shift()
                addAlert(alertObj)
            ***REMOVED***
        ***REMOVED***, 200, alertObj)
        store.state.alert.waitIntervals.push(interval)
    ***REMOVED***
***REMOVED***
const notNormalTokenAlert = () => ***REMOVED***
    createAlert(new alert('無効なアクセストークンのためログアウトします。', 2))
    // router.pushをそのまま実行すると何故か実行されないため、setTimeoutを用いる
    setTimeout(() => ***REMOVED***
        router.push('/logout')
    ***REMOVED***, 50)
***REMOVED***

export ***REMOVED***
    alert,
    createAlert,
    notNormalTokenAlert,
***REMOVED***