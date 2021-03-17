/* ---------------windowの使い方--------------- */
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

class window {
    constructor(currentComponent = 0, title = 'タイトルが設定されていません', width = 500, height = 500) {
        this.currentComponent = currentComponent
        this.title = title
        this.width = width
        this.height = height
    }
}

const createWindow = (windowObj) => {
    store.state.window.currentComponent = windowObj.currentComponent
    store.state.window.title = windowObj.title
    store.state.window.width = windowObj.width
    store.state.window.height = windowObj.height
}
const setCurrentComponent = (currentComponent = 0) => { store.state.window.currentComponent = currentComponent }
const setTitle = (title = 'タイトルが設定されていません') => { store.state.window.title = title }
const setSize = (width = 500, height = 500) => {
    store.state.window.width = width
    store.state.window.height = height
}
const setOpenFunction = (func = null) => { store.state.window.functions.open = func }
const setCloseFunction = (func = null) => { store.state.window.functions.close = func }

export {
    window,
    createWindow,
    setCurrentComponent,
    setTitle,
    setSize,
    setOpenFunction,
    setCloseFunction,
}