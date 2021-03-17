/* ---------------windowの使い方--------------- */
// displayWindowを用いることでwindowを開くことができる

// ・タイトル、横幅、縦幅を指定したい場合
// windowに表示するコンポーネントのonMountedから、
// createWindowの引数に自身のカレントコンポーネント、タイトル、横幅、縦幅を代入する

// ・開いた時にメソッドを実行したい場合
// windowに表示するコンポーネントのonMountedから、setOpenFunctionの引数にメソッドを代入する

// ・閉じた時にメソッドを実行したい場合
// windowに表示するコンポーネントのonMountedから、setCloseFunctionの引数にメソッドを代入する

// ・storeにあるwindowのグローバル変数の説明
// currentComponent:    モジュールの番号
// title:               タイトル
// width:               横幅
// height:              縦幅
// use:                 使用しているか否かのフラグ
// functions.open:      開いたときに実行されるメソッド
// functions.close:     閉じたときに実行されるメソッド

import { store } from './store'

const displayWindow = (currentComponent) => {
    store.state.window.currentComponent = currentComponent
    store.state.window.use = true
}
const createWindow = (title = 'タイトルが設定されていません', width = 500, height = 500) => {
    store.state.window.title = title
    store.state.window.width = width
    store.state.window.height = height
}
const setOpenFunction = (func = null) => { store.state.window.functions.open = func }
const setCloseFunction = (func = null) => { store.state.window.functions.close = func }

export {
    createWindow,
    displayWindow,
    setOpenFunction,
    setCloseFunction,
}