import * as API from "./axios.js";

export default {
    login(payload) {
        return API.apiClient.post("/auth/login", payload);
    },
    logout() {
        return API.apiClient.post("/auth/logout");
    },
    getUser() {
        return API.apiClient.get('/user')
    }
};
