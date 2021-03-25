import { createStore } from 'vuex'

export const store = createStore({
    state() {
      return {
        // 変数の説明についてはalert.jsの一行目から参照
        alert: {
          waitIntervals:  [],
          object:         null,
        },
        user: {
          profileUpdate:  false,
          userName:       '',
          isLogin:        false,
        },
        // 変数の説明についてはwindow.jsの一行目から参照
        window: {
          currentComponent: 0,
          functions: {
            close:  null,
            open:   null,
          },
          title:            'タイトルが設定されていません',
          width:            500,
          height:           500,
          use:              false,
        },
      }
    },
  })