import axios from 'axios';
import authService from './auth.service';
import router from '../router';


/** Default config for axios client */
let config = {
    baseURL: `http://localhost:8080/api/`,
};
/** Creating instance of axios */
const httpClient = axios.create(config);

/** Auth interceptors */
const authInterceptor = config => {
    config.headers.Authorization = `Bearer ${authService.getToken()}`;
    return config;
};

/** Adding the request interceptors */
httpClient.interceptors.request.use(authInterceptor);

/** Adding the response interceptors */
httpClient.interceptors.response.use(
    response => {
        return response;
    },
    error => {
            if (error.response.statusCode === 401) {
                router.push({name: 'login'});
            }
            return Promise.reject(error);
    }
);


export default httpClient;