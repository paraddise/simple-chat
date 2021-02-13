import httpClient from "@/services/http.service";

export default {
    state() {
        return {
            chat: null,
            messages: {}
        }
    },
    mutations: {
        setChat(state, chat) {
            state.chat = chat;
        },
        setMessages(state, messages) {
            state.messages = {};
            for (const message of messages) {
                state.messages[message.id] = message
            }
        },
        pushMessage(state, message) {
            state.messages[message.id] = message;
        }
    },
    actions: {
        async fetchChat(ctx, chatId) {
          try {
              let {status, data} = await httpClient.get(`/chat/view`, {params: {id: chatId}})
              if (status === 200) {
                  ctx.commit('setChat', data)
                  return {status, data};
              } else {
                  alert("Cannot fetch chat: " + chatId)
                  return Promise.reject({status, data})
              }
          } catch (e) {
            return Promise.reject({status: e.response.statusCode, data: e.response.data})
          }
        },
        async fetchMessages(ctx, params = {limit: 20, offset: 0}) {
            const  chatId = ctx.state.chat.id;
            const {limit, offset} = params;
            try {
                let {status, data} = await httpClient.get('/chat/messages', {params: {
                        chatId,
                        limit,
                        offset
                    }});

                if (status === 200) {
                    ctx.commit('setMessages', data)
                    return {status, data}
                } else {
                    return Promise.reject({status, data})
                }
            } catch (e) {
                alert("Cannot fetch messages")
                alert(e)
                return Promise.reject({status: e.response.statusCode, data: e.response.data})
            }
        },
        async sendMessage(ctx, form) {
            try {
                let chatId = ctx.state.chat.id;
                let {status, data} = await httpClient.post(`/chat/${chatId}/send-message`, form);
                if (status === 200) {
                    ctx.commit('pushMessage', data)
                    return {status, data};
                } else {
                    alert("Cannot send message, status: " + status)
                    return Promise.reject({status, data})
                }
            } catch (e) {
                alert(e)
                return Promise.reject(e)
            }
        },

    },
    getters: {
        messages(state) {
            return state.messages;
        },
        chat(state) {
            return state.chat;
        }
    }
}