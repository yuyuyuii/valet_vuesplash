
//import エイリアス名 from '元のプラグイン'
import Vue from 'vue'
import VueRouter from 'vue-router'

//pages直下のコンポーネントのルーティングを設定する為、インポートする
import PhotoList from './pages/PhotoList.vue';
import Login from './pages/Login.vue';
import store from "./store"; 
import SystemError from "./pages/errors/System.vue";
import PhotoDetail from './pages/PhotoDetail.vue';
import NotFound from './pages/errors/NotFound.vue';
//明示的にVueRoterプラグインを使用すると宣言
//これで<RouterView />コンポーネントを使用できるようになる
Vue.use(VueRouter);

//パスとコンポーネントのマッピング
const routes = [
    {
        path: "/", // "/" にアクセスするとPhotoListコンポーネントを描写する
        component: PhotoList,
        props: route => {
            const page = route.query.page;
            return { page: /^[1-9][0-9]*$/.test(page) ? page * 1 : 1 };
        }
    },
    {
        path: "/login",
        component: Login,
        beforeEnter(to, from, next) {
            if (store.getters["auth/check"]) {
                next("/");
            } else {
                next();
            }
        }
    },
    {
        path: "/500",
        component: SystemError
    },
    {
        path: "/photos/:id",
        component: PhotoDetail,
        props: true
    },
    {
        path: "*",
        component: NotFound
    }
];

//VueRouterインスタンスを作成する
const router = new VueRouter({
  mode: "history", //追加する事でURLに # がつかないようになる
  scrollBehavior() {
    return {x: 0, y: 0}
  },
  routes //上で定義したroutes
})

//app.jsでインポートするために、VueRoterをエクスポートする
export default router
