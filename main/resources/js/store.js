import { createStore } from 'vuex'

export const store = createStore({
    state() {
      return {
        alert: {
          object: null,
          waitIntervals: [],
        },
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