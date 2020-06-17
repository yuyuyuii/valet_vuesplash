
//import エイリアス名 from '元のプラグイン'
import Vue from 'vue'
import VueRouter from 'vue-router'

//pages直下のコンポーネントのルーティングを設定する為、インポートする
import PhotoList from './pages/PhotoList.vue';
import Login from './pages/Login.vue';
import store from "./store"; 
import SystemError from "./pages/errors/System.vue";

//明示的にVueRoterプラグインを使用すると宣言
//これで<RouterView />コンポーネントを使用できるようになる
Vue.use(VueRouter);

//パスとコンポーネントのマッピング
const routes = [
  {
    path: "/", // "/" にアクセスするとPhotoListコンポーネントを描写する
    component: PhotoList
  },
  {
    path: "/login",
    component: Login,
    beforeEnter(to, from, next) {
      if (store.getters['auth/check']) {
        next('/')
      } else {
        next()
      }
    },
  },
  {
    path: '/500',
    component: SystemError
  }
]

//VueRouterインスタンスを作成する
const router = new VueRouter({
  mode: "history", //追加する事でURLに # がつかないようになる
  routes //上で定義したroutes
})

//app.jsでインポートするために、VueRoterをエクスポートする
export default router
