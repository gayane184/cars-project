export default {
    state: {
        token: null,
        expired_at: null,
        is_logged: false
    },
    mutations: {
        setToken(state, payload) {
            state.token = payload;
        },
        setExpiredAt(state, payload) {
            state.expired_at = payload;
        },
        setIsLogged(state, payload) {
            state.is_logged = payload;
        }
    },
    getters: {
        getToken(state) {
            return state.token;
        },
        getExpiredAt(state) {
            return state.expired_at;
        },
        getIsLogged(state) {
            return state.is_logged;
        }
    }
};
