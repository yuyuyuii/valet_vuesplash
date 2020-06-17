import axios from "axios";
import { OK, UNPROCESSABLE_ENTITY } from "../util";

const state = {
  user: null,
  apiStatus: null, //ステータスコードの判別に使用する
  loginErrorMessages: null
};

const getters = {
  check: state => !!state.user,
  username: state => state.user ? state.user.name : ''
};

const mutations = {
  setUser(state, user) {
    // 第一引数は必ずstate
    state.user = user;
  },
  setApiStatus(state, status) {
    state.apiStatus = status;
  },
  setLoginErrorMessages(state, messages) {
    state.loginErrorMessages = messages;
  }
    
};

const actions = {
    async register(context, data) {
      const response = await axios.post("/api/register", data)
      .catch(err => err.response || err)
    
      if (response.status === OK) {
        context.commit('setApiStatus', true)
        context.commit("setUser", response.data)
        return false
      }
      context.commit("setApiStatus", false);
      context.commit("error/setCode", response.status, { root: true });
    },
    async login(context, data) {
      context.commit("setApiStatus", false);
      if (response.status === UNPROCESSABLE_ENTITY) {
          context.commit("setLoginErrorMessages", response.data.errors);
      } else {
          context.commit("error/setCode", response.status, { root: true });
      }
    },
    async logout(context) {
        const response = await axios.post("/api/logout");
        // const response = await axios.post("/api/logout");
        context.commit("setUser", null);
    },
    async currentUser(context) {
        const response = await axios.get("/api/user");
        const user = response.data || null;
        context.commit("setUser", user);
    }
};
/**
 *  まず会員登録APIを呼び出し、返却データを渡して
 *  setUser ミューテーションを実行することで（ここで commit メソッドを使っています）user ステートを更新する
  */
// 「アクション→コミットでミューテーション呼び出し→ステート更新」というパターンはよく使う

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}