// require('./bootstrap');
import Vue from "vue";

//router.jsで定義したルーティングをインポートする
import router from './router';

//ルートコンポーネントをインポートする
import App from './App.vue';

new Vue({
  el: "#app",
  router, //ルーティングの定義を読み込む
  components: { App }, //ルートコンポーネントの使用を宣言する
  template: '<App />' //ルートコンポーネントを描画する
    
});
