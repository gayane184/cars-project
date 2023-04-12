import {createWebHistory, createRouter} from "vue-router";
import {api_url} from "../constants";
import moment from "moment";
import store from "../store";
import {$api} from "../api";

const routes = [
    {
        path: "/",
        name: "sign-in",
        component: () => import("@/views/auth/SignIn.vue"),
        meta: {auth: 'guest'}
    },
    {
        path: "/admin/mark",
        name: "admin-mark",
        component: () => import("@/views/admin/mark/Index.vue"),
        meta: {auth: 'auth', role: 'admin'}
    },
    {
        path: "/user/car",
        name: "user-car",
        component: () => import("@/views/user/car/Index.vue"),
        meta: {auth: 'auth', role: 'user'}
    },
    {
        path: "/:catchAll(.*)",
        name: 'not-found',
        component: import("@/views/others/NotFound.vue"),
        meta: {auth: 'each'}
    }
];

const router = createRouter({
    history: createWebHistory(),
    mode: 'hash',
    routes
});

router.beforeEach(async (to, from, next) => {
    let user = null;

    let reset = () => {
        store.commit("setToken", null);
        store.commit("setExpiredAt", null);
        store.commit("setIsLogged", false);
        store.commit("setUser", null);
        next('/');
    };

    if (store.getters.getIsLogged) {
        let now = moment().format('YYYY-MM-DD HH:mm:ss');
        let expired_at = moment(store.getters.getExpiredAt);
        let is_expired = expired_at.diff(now) <= 0;

        if (is_expired) {
            reset();
        } else {
            await $api().post(api_url + "me").then(({data}) => {
                if (data.success) {
                    user = data.user;
                    store.commit('setUser', user);
                } else {
                    reset();
                }
            }).catch(() => {
                reset();
            });
        }
    }

    if (to.meta.auth === 'guest') {
        if (user) {
            if (user.role === 'admin') {
                next('/admin/mark');
            } else {
                next('/user/car');
            }
        } else {
            next();
        }
    } else if (to.meta.auth === 'auth') {
        if (user) {
            if (to.meta.role === user.role) {
                next();
            } else {
                if (user.role === 'admin') {
                    next('/admin/mark');
                } else {
                    next('/user/car');
                }
            }
        } else {
            next('/');
        }
    } else {
        next();
    }
});

export default router;
