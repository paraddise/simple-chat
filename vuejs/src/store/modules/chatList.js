import httpClient from "@/services/http.service";

export default {
    state: {
        chats: {},
    },
    actions: {
        async fetchChats(context, limit = 10) {
            try {
                let {status, data} = await httpClient.get('/chat', {params: {limit}});
                if (status === 200) {
                    context.commit('updateChats', data)
                    return {status, data}
                } else {
                    context.commit('updateChats', [])
                    return Promise.reject({status, data})
                }
            } catch (e) {
                context.commit('updateChats', [])
                return Promise.reject({status: e.response.statusCode, data: e.response.data})
            }
        },

        async createChat(context, form) {
            try {
                let {status, data} = await httpClient.post('/chat/create', form);
                if (status === 200) {
                    context.commit('pushChat', data)
                    return {status, data}
                } else {
                    return Promise.reject({status, data})
                }
            } catch (e) {
                return Promise.reject({status: e.response.statusCode, data: e.response.data})
            }
        },

    },
    mutations: {
        updateChats(state, chats) {
            for(let chat of chats) {
                state.chats[chat.id] = chat;
            }
        },
        pushChat(state, chat) {
            state.chats[chat.id] = chat
        },
    },
    getters: {
        allChats(state) {
            return state.chats
        },
        allChatsEntries(state) {
            return Object.entries(state.chats)
        },
    }
}