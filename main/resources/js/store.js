import { createStore } from 'vuex'

export const store = createStore({
    state() {
      return {
        // 変数の説明についてはalert.jsの一行目から参照
        alert: {
          object: null,
          waitIntervals: [],
        },
        // 変数の説明についてはwindow.jsの一行目から参照
        window: {
          currentComponent: 0,
          title: 'タイトルが設定されていません',
          width: 500,
          height: 500,
          use: false,
          functions: {
            close: null,
            open: null,
          }
        },
      }
    },
  })