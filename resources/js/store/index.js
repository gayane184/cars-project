import createPersistedState from "vuex-persistedstate";
import {createStore} from 'vuex';
import Cookies from "js-cookie";

// Modules
import auth from "./auth";
import user from "./user";

createPersistedState({storage: window.sessionStorage});

export default new createStore({
    modules: {auth, user},
    plugins: [
        createPersistedState({
            storage: {
                getItem: (key) => Cookies.get(key),
                setItem: (key, value) => Cookies.set(key, value, {
                    expires: 3,
                    secure: false
                }),
                removeItem: (key) => Cookies.remove(key)
            },
            paths: ['auth']
        })
    ]
});






