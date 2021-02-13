<template>
  <div class="chat-page">
    <h3 v-if="!chat">Choose chat and begin the conversation</h3>
    <Chat v-else v-bind:chat="chat"/>
  </div>
</template>

<script>


import Chat from "@/components/Chat";
import { mapActions, mapGetters} from 'vuex';


export default {
  name: "Chats",
  components: {Chat},
  data() {
    return {

    }
  },
  methods: {
    ...mapActions(['fetchChat', 'fetchMessages']),
  },
  mounted() {
    if (this.$route.query.id) {
      this.fetchChat(this.$route.query.id)
    }
  },
  computed: {
    ...mapGetters(['chat'])
  },
  watch: {
    async $route(to) {
      this.fetchChat(to.query.id).then(this.fetchMessages);
    },
  }


}
</script>

<style lang="scss" scoped>
.chat-page {
  width: 100%;
  height: 99%;
}
</style>