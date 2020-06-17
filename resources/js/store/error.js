const state = {
  code: null //ステータスコード
}

const getters = {}

const mutations = {
  setCode(state, code) {
    state.code = code
  },
}

const actions = {}

export default {
  namespaced: true,
  state,
  mutations
}