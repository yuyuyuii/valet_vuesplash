import "./bootstrap"; //先頭に配置する必要あり
import Vue from "vue";

//router.jsで定義したルーティングをインポートする
import router from './router';

//ルートコンポーネントをインポートする
import App from './App.vue';

import store from './store'; //storeディレクトリを追加


new Vue({
  el: "#app",
  router, //ルーティングの定義を読み込む
  store, //ストアを追加
  components: { App }, //ルートコンポーネントの使用を宣言する
  template: '<App />' //ルートコンポーネントを描画する
    
});
