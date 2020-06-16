<template>
  <div class="container--small">
    <ul class="tab">
      <li
        class="tab__item"
        :class="{'tab__item--active': tab === 1}"
        @click="tab = 1"
      >Login</li>
      <li
        class="tab__item"
        :class="{'tab__item--active': tab === 2}"
        @click="tab = 2"
      >Register</li>
    </ul>
    <div class="panel" v-show="tab === 1">
      <form class="form" @submit.prevent="login">
        <label for="login-email">Email</label>
        <input type="text" class="form__item" id="login-email" v-model="loginForm.email">
        <label for="login-password">Password</label>
        <input type="password" class="form__item" id="login-password" v-model="loginForm.password">
        <div class="form__button">
          <button type="submit" class="button button--inverse">login</button>
        </div>
      </form>
    </div>
    <div class="panel" v-show="tab === 2">
      <form class="form" @submit.prevent="register">
        <label for="username">Name</label>
        <input type="text" class="form__item" id="username" v-model="registerForm.name">
        <label for="email">Email</label>
        <input type="text" class="form__item" id="email" v-model="registerForm.email">
        <label for="password">Password</label>
        <input type="password" class="form__item" id="password" v-model="registerForm.password">
        <label for="password-confirmation">Password (confirm)</label>
        <input type="password" class="form__item" id="password-confirmation" v-model="registerForm.password_confirmation">
        <div class="form__button">
          <button type="submit" class="button button--inverse">register</button>
        </div>
      </form>
    </div>
  </div>
</template>


<script>
export default {
  data(){
    return{
      tab:1,
      loginForm: {
        email: '',
        password: ''
      },
      registerForm: {
        name: '',
        email: '',
        password: '',
        password_confirmation: ''
      },      
    }
  },
  methods: {
    login(){
      console.log(this.loginForm)
    },
    async register(){
      //authストアのregisterアクションを呼び出す
      //this.$storeと書くことでストアを参照する事ができる。(stores/index.jsでVue.use(Vuew)と記述したから)
      console.log(this.$store)
      await this.$store.dispatch('auth/register', this.registerForm) //dispatch(アクション名, フォームの入力値), これがauth.js/actionsの第二引数となる(state, data)の所
      //awaitで非同期なアクションが完了するのを待ってからトップページへ移動(Promiseの解決を待ってから)
      //トップページへ移動
      this.$router.push('/');
    }
  }

  
}
</script>