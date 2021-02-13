<template>
  <div class="chat-header" :class="{expanded: headerExpanded}" @click="headerExpanded = !headerExpanded">
    <div class="chat__title">
      <span>{{ chat.title }}</span>
      <font-awesome-icon class="icon" icon="arrow-down"/>
    </div>
    <div v-if="headerExpanded" class="chat-header-expanded">
      <p v-if="chat.description">{{ chat.description }}</p>
      <p>Created At: {{ created_at }}</p>
      <p>Chat Owner: {{ chat.created_by }} </p>
      <p>Your role in this chat: {{ chat.role }}</p>
      <router-link :to="{name: 'chat-update', query: {id: chat.id}}">Update chat</router-link>
    </div>
  </div>
  <div class="chat__body">
    <div class="chat__messages">
      <ul class="chat__messages-list">
        <li v-for="message of messages" :key="message.id" class="chat__message"
            :class="{own: isOwnMessage(message)}">
          <div>
            {{ message.text }}
          </div>
          <small>{{ transformMessageTime(message.created_at) }}</small>
        </li>
      </ul>
    </div>
    <form v-if="chat.permissions.sendMessage" class="send__form"
          @submit.prevent="submitMessageForm">
      <input v-model.trim="messageForm.text" type="text" placeholder="Type your message here" id="messageInput"/>
      <button type="submit">
        <font-awesome-icon icon="arrow-circle-right"/>
      </button>
    </form>
  </div>

</template>

<script>
import {mapActions, mapGetters} from 'vuex';

export default {
  name: "Chat",
  props: {
    chat: Object
  },
  data() {
    return {
      headerExpanded: false,

      messageForm: {
        text: ''
      }
    }
  },
  methods: {
    ...mapActions(['fetchMessages', 'sendMessage']),
    isOwnMessage(message) {
      return message.created_by === this.user.id;
    },
    transformMessageTime(timestamp) {
      const date = new Date(timestamp * 1000)
      return `${date.getFullYear()}/${date.getMonth()}/${date.getDate()} ${date.getHours()}:${date.getMinutes()}:${date.getSeconds()}`
    },
    async submitMessageForm() {
      let {status} = await this.sendMessage(this.messageForm)
      if (status === 200) {
        this.messageForm.text = '';
      }
    }
  },
  mounted() {
  },
  updated() {
    // this.$el.scrollTop = this.$el.clientHeight
  },
  computed: {
    ...mapGetters(['messages', 'user']),

    created_at() {
      const date = new Date(this.chat.created_at * 1000);
      return date.toLocaleString('ru-RU')
    },
    updated_at() {
      const date = new Date(this.chat.updated_at * 1000);
      return date.toLocaleString('ru-RU')
    }
  },
}
</script>

<style lang="scss" scoped>
.chat-header {
  --chat-header-bg: #9d92ee;
  position: fixed;
  top: calc(2vh + 15px);
  width: 100%;
  padding: 10px;
  text-align: left;
  background-color: var(--chat-header-bg);
}

.chat__body {
  padding-top: 5vh;
  height: 88%;

  & .chat__messages {
    height: 100%;

    & .chat__messages-list {
      --message-bg: #6a59de;
      --message-color: white;
      --message-own-bg: #5ac728;


      height: 100%;
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      list-style: none;
      overflow-y: auto;
      padding: 0;

      & .chat__message {
        text-align: left;
        border-radius: 10px;
        margin: 8px;
        padding: 0.6rem;
        background-color: var(--message-bg);
        color: var(--message-color);

        & small {
          text-align: right;
          font-size: 0.7em;
        }
      }

      & .chat__message.own {
        text-align: right;
        background-color: var(--message-own-bg);
        align-self: flex-end;
      }
    }
  }

  & .send__form {
    display: flex;

    #messageInput {
      width: 100%;
    }

    & button {
      margin-left: auto;
      border-radius: 2px;
      border: 1px solid black;
      width: 10%;
    }
  }
}

</style>