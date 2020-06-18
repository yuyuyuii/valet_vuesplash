import "./bootstrap"; //先頭に配置する必要あり
import Vue from "vue";

//router.jsで定義したルーティングをインポートする
import router from './router';

//ルートコンポーネントをインポートする
import App from './App.vue';

import store from './store'; //storeディレクトリを追加

const createApp = async () => {
  await store.dispatch('auth/currentUser')

  new Vue({
    el: '#app',
    router,
    store,
    components: { App },
    template: '<App />'
  })
}

createApp()