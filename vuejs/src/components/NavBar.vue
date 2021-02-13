<template>
<nav id="nav">
  <router-link to="/" class="logo-text">Simple Chat</router-link>
  <router-link to="/chats">Chats</router-link>

  <router-link v-if="!isLoggedIn" to="/auth/login">Login</router-link>
  <a v-else @click="logout" href="#">Logout</a>

</nav>
</template>

<script>
import authService from "@/services/auth.service";
import router from "@/router";

export default {
name: "NavBar",
  methods: {
    logout() {
      authService.logout()
      router.push({name: 'login'});
    },
    isLoggedIn() {
      return authService.isLoggedIn();
    }
  },
  computed: {

  }


}
</script>

<style scoped>

</style>