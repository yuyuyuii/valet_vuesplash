import axios from "axios";

const state = {
  user: null
};

const getter = {};

const mutations = {
  setUser(state, user) {// 第一引数は必ずstate
    state.user = user
  }
};

const actions = {
    async register(context, data) {
    const response = await axios.post("/api/register", data)
        context.commit('setUser', response.data)
    }
}
/**
 *  まず会員登録APIを呼び出し、返却データを渡して
 *  setUser ミューテーションを実行することで（ここで commit メソッドを使っています）user ステートを更新する
  */
// 「アクション→コミットでミューテーション呼び出し→ステート更新」というパターンはよく使うので慣れれば大丈夫です

export default {
  namespaced: true,
  state,
  getter,
  mutations,
  actions
}