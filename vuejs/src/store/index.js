import { createStore } from "vuex";
import chatList from './modules/chatList.js';
import chat from './modules/chat.js';
import user from './modules/user.js';


export default createStore({
    state() {
        return {}
    },
    mutations: {},
    actions: {},
    getters: {},
    modules: {
        chat,
        chatList,
        user
    }
})