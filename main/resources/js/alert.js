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

import { store } from './store'

class alert {
    constructor(content, type) {
        this.content    = content
        this.type       = type
    }
}

const addAlert = (alertObj) => {
    store.state.alert.object = alertObj
    setTimeout(() => { store.state.alert.object = null }, 5000)
}
const createAlert = (alertObj) => {
    if (store.state.alert.object === 0) {
        addAlert(alertObj)
    } else {
        const interval = setInterval((alertObj) => {
            if (store.state.alert.object === null) {
                clearInterval(store.state.alert.waitIntervals[0])
                store.state.alert.waitIntervals.shift()
                addAlert(alertObj)
            }
        }, 200, alertObj)
        store.state.alert.waitIntervals.push(interval)
    }
}

export {
    alert,
    createAlert,
}