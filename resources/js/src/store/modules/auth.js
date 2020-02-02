import AuthService from "@/services/AuthService";

export const namespaced = true;

export const state = {
    user: null,
    token: window.localStorage.getItem("token"),
    loading: false,
    error: null
};

export const mutations = {
    SET_USER(state, user) {
        state.user = user;
        window.localStorage.setItem("user", JSON.stringify(user));
    },
    CLEAR_USER() {
        window.localStorage.clear();
        location.reload();
    },
    SET_TOKEN(state, token) {
        state.token = token;
        window.localStorage.setItem("token", token);
    },
    SET_LOADING(state, loading) {
        state.loading = loading;
    },
    SET_MESSAGE(state, message) {
        state.message = message;
    },
    SET_ERROR(state, error) {
        state.error = error;
    }
};

export const actions = {
    login({ commit }, payload) {
        commit("SET_LOADING", true);
        return new Promise((resolve, reject) => {
            AuthService.login(payload)
                .then(response => {
                    commit("SET_TOKEN", response.data.token);
                    commit("SET_LOADING", false);
                    resolve(response.data.token);
                })
                .catch(error => {
                    commit("SET_LOADING", false);
                    commit("SET_ERROR", error.data ? error.data.error : error);
                    reject(error.data);
                });
        });
    },
    logout({ commit }) {
        return AuthService.logout()
            .then(() => {
                commit("CLEAR_USER");
            })
            .catch(() => {
                commit("CLEAR_USER");
            });
    },
    getUser({ commit }) {
      return AuthService.getUser()
          .then((response) => {
              commit("SET_USER", response.data);
          })
    }
};

export const getters = {
    authUser: state => {
        return state.user;
    },
    error: state => {
        return state.error;
    },
    loading: state => {
        return state.loading;
    },
    loggedIn: state => {
        return !!state.user;
    }
};
