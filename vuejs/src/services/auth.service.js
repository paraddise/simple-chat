import axios from "axios";

const authService = {
    user: null,
    baseUrl: 'http://localhost:8080/api',
    async login(formData) {
        try {
            const {status, data} = await axios.post(this.baseUrl + '/user/login', formData);
            if (status === 200) {
                this.setUser(data);
                return {status: true};
            }
            return {status: false, errors: data}
        } catch(e) {
            return {
                success: false,
                errors: e.response.data.errors
            }
        }
    },
    setUser(user) {
        this.user = user;
        localStorage.setItem('ACCESS_TOKEN', user.access_token);
        localStorage.setItem('USER', JSON.stringify(user))
    },
    isLoggedIn() {
        return !!localStorage.getItem('ACCESS_TOKEN');
    },
    logout() {
        localStorage.removeItem('ACCESS_TOKEN');
    },
    getToken() {
        return localStorage.getItem('ACCESS_TOKEN');
    },
    getUser() {
        return JSON.parse(localStorage.getItem('USER'))
    }

};
export default authService