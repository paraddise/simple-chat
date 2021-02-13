import httpClient from "@/services/http.service";
import authService from "@/services/auth.service";

export default {
    state() {
        return {
            user: null,
        }
    },
    actions: {
        async fetchUserInfo(ctx) {
            let user = ctx.getters.user;
            if (ctx.state.user === null) {
                user = authService.getUser()
            }

            try {
                let {status, data} = await httpClient.post('/user/info?id=' + user.id)
                if (status === 200) {
                    ctx.commit('updateUser', data);
                    return {status, data}
                } else {
                    alert("Cannot fetch user, status " + status)
                    return Promise.reject({status, data})
                }
            } catch(e) {
                alert("Cannot fetch user")
                return Promise.reject(e)
            }
        }
    },
    mutations: {
        updateUser(state, user) {
            authService.setUser(user)
            state.user = user
        },
    },
    getters: {
        user(state) {
            return state.user
        }
    }
}