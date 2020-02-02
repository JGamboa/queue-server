import AuthService from "@/services/AuthService";

export const namespaced = true;

export const state = {
    user: JSON.parse(window.localStorage.getItem('user')),
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
    SET_TOKEN(state, data) {
        try {
            state.token = data.access_token;
            window.localStorage.setItem("token", data.access_token);
            window.localStorage.setItem("refresh_token", data.refresh_token);
        }
        catch(error) {
            console.error(error);
            // expected output: ReferenceError: nonExistentFunction is not defined
            // Note - error messages will vary depending on browser
        }
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
                    commit("SET_TOKEN", response.data);
                    commit("SET_LOADING", false);
                    resolve(response.data.access_token);
                })
                .catch(error => {
                    commit("SET_LOADING", false);
                    commit("SET_ERROR", error.data ? error.data : error);
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
      commit("SET_LOADING", true);
      return AuthService.getUser()
          .then((response) => {
              commit("SET_USER", response.data);
              commit("SET_LOADING", false);
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
