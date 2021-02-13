<template>
  <NavBar />
  <div class="app-content">
    <router-view name="sidebar"></router-view>
    <router-view></router-view>
  </div>
</template>

<style>
html,
body {
  margin: 0;
  height: 100vh;
}
.app-content {
  display: flex;
  height: calc(98vh - 15px);
}

#app {
  height: 100%;
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}

#nav {
  height: 2vh;
  padding: 15px;
  background-color: Gainsboro;
  display: flex;
  justify-content: flex-end;
  align-items: center;
}

#nav a {
  font-weight: bold;
  color: #2c3e50;
  text-decoration: none;
}
#nav a.logo-text {
  color: Grey;
  font-family: Inconsolata;
  margin-right: auto;
  font-size: 1.4em;
}
#nav a.router-link-exact-active {
  color: #668ad8;
}

#nav a {
  font-size: 1.2em;
  margin-left: 10px;
}
#nav a:hover {
  font-size: 1.4em;
}
</style>
<script>
import NavBar from "@/components/NavBar";
import {mapActions, mapGetters} from 'vuex';
import authService from "@/services/auth.service";

export default {
  components: {NavBar},
  methods: {
    ...mapActions(['fetchUserInfo'])
  },
  computed: {
    ...mapGetters(['user']),
  },
  mounted() {
    if (authService.isLoggedIn()) {
      this.fetchUserInfo().then(() => { alert('Hello ' + this.user.username)} )
    }
  }
}
</script>