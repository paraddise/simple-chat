<template>
  <div class="chats-content">
    <h3>Chats</h3>
    <router-link to="/create-chat">Create
      <font-awesome-icon icon="plus-circle"/>
    </router-link>
    <ul class="chat-list">
      <li class="chat-list-item" v-for="[id, chat] of allChatsEntries" :key="id"
          @click="toChat(id)">
        <p>
          {{ chat.title }}
        </p>
        <div class="chat__last_message" v-if="chat.last_message">
          <div class="text">{{ chat.last_message.text }}</div>
          <div class="time">{{ transformLastMessageDate(chat.last_message.created_at) }}</div>
        </div>
      </li>
    </ul>
  </div>
</template>

<script>

import {mapGetters, mapActions} from 'vuex';

export default {
  name: "ChatList",
  data() {
    return {
      currentChat: null,
      loadChatInterval: null
    }
  },
  mounted() {
    this.fetchChats().then(() => {
      this.loadChatInterval = setInterval(() => {
        this.fetchChats().then((status) => {
          if (status === 403) {
            clearInterval(this.loadChatInterval)
          }
        })
      }, 10000);
    })
  },
  computed: {
    ...mapGetters(['allChatsEntries']),
  },
  methods: {
    ...mapActions(['fetchChats', 'fetchChat', 'fetchMessages']),
    toChat(chatId) {
      // this.fetchChat(chatId)
      this.$router.push({name: 'Chats', query: {id: chatId}});
    },
    transformLastMessageDate(timestamp) {
      let date = new Date(timestamp * 1000);
      return `${date.getHours()}:${date.getMinutes()}`
    }
  }
}
</script>

<style lang="scss" scoped>
$bg: #9999FF;
$chat_bg: #6666CC;
$chat_border: #CCCCFF;
.chats-content {
  display: flex;
  align-items: center;
  flex-direction: column;
  background-color: $bg;
  height: 99%;
  width: 30rem;

  & .chat-list {
    text-align: left;
    list-style: none;
    padding: 0;
    border-radius: 5px;
    margin: 0;
    width: 95%;
    overflow-y: auto;

    & .chat-list-item {
      border-radius: 5px;
      margin: 20px 0;

      &:hover {
        background-color: $chat_bg;
      }

      & .chat__last_message {
        display: flex;
        font-size: 0.8em;
        justify-content: space-between;
        align-items: center;

      }

      &:after {
        display: block;
        content: "";
        margin: 10px auto 0 auto;
        background-color: $chat_border;
        height: 1px;
        width: 80%;

      }
    }

  }

}


</style>