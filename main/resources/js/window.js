/* ---------------windowの使い方--------------- */
// ・タイトル、横幅、縦幅を指定したい場合
// windowに表示するコンポーネントのonMountedから、
// createWindowの引数に自身のカレントコンポーネント、タイトル、横幅、縦幅を代入する

// ・開いた時にメソッドを実行したい場合
// windowに表示するコンポーネントのonMountedから、setOpenFunctionの引数にメソッドを代入する

// ・閉じた時にメソッドを実行したい場合
// windowに表示するコンポーネントのonMountedから、setCloseFunctionの引数にメソッドを代入する

import ***REMOVED*** store ***REMOVED*** from './store'

class window ***REMOVED***
    constructor(currentComponent = 0, title = 'タイトルが設定されていません', width = 500, height = 500) ***REMOVED***
        this.currentComponent = currentComponent
        this.title = title
        this.width = width
        this.height = height
    ***REMOVED***
***REMOVED***

const createWindow = (windowObj) => ***REMOVED***
    store.state.window.currentComponent = windowObj.currentComponent
    store.state.window.title = windowObj.title
    store.state.window.width = windowObj.width
    store.state.window.height = windowObj.height
***REMOVED***
const setCurrentComponent = (currentComponent = 0) => ***REMOVED*** store.state.window.currentComponent = currentComponent ***REMOVED***
const setTitle = (title = 'タイトルが設定されていません') => ***REMOVED*** store.state.window.title = title ***REMOVED***
const setSize = (width = 500, height = 500) => ***REMOVED***
    store.state.window.width = width
    store.state.window.height = height
***REMOVED***
const setOpenFunction = (func = null) => ***REMOVED*** store.state.window.functions.open = func ***REMOVED***
const setCloseFunction = (func = null) => ***REMOVED*** store.state.window.functions.close = func ***REMOVED***

export ***REMOVED***
    window,
    createWindow,
    setCurrentComponent,
    setTitle,
    setSize,
    setOpenFunction,
    setCloseFunction,
***REMOVED***