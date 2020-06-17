<template>
  <nav class="navbar">
    <RoterLink class="navbar__brand" to="/">
      VueSplash
    </RoterLink>
    <div class="navbar__menu">
      <div v-if="isLogin" class="navbar__item">
        <button class="button" @click="showForm = ! showForm">
          <i class="icon ion-md-add"></i>
          Submit a photo
        </button>
      </div>
      <span v-if="isLogin" class="navbar__item">
        {{ username }}
      </span>
      <div v-else class="navbar__item">
        <router-link class="button button--link" to="/login">
          Login / Register
        </router-link>
      </div>
    </div>
    <PhotoForm v-model="showForm"/>
  </nav>
</template>

<script>
import PhotoForm from './PhotoForm';

export default {
  components:{
    PhotoForm
  },
  data() {
    return {
      showForm: false
    }
  },
  computed: {
    isLogin(){
      return this.$store.getters['auth/check']
    },
    username(){
      return this.$store.getters['auth/username']
    }
  },

}
</script>