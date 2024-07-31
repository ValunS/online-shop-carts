import { createWebHistory, createRouter } from "vue-router";

import Auth from "@/components/Auth/Auth.vue";
import Login from "@/components/Auth/Login.vue";
import Register from "@/components/Auth/Register.vue";
import Dashboard from "@/components/Dashboard.vue";
import TaskList from "@/components/TaskList.vue";
import TaskShow from "@/components/TaskShow.vue";
import TaskEdit from "@/components/TaskEdit.vue";
import TaskCreate from "@/components/TaskCreate.vue";

//use store by VueX

const routes = [
    {
        path: "/auth",
        name: "Auth",
        component: Auth,
        children: [
            {
                path: "",
                name: "Login",
                component: Login,
            },
            {
                path: "register",
                name: "Register",
                component: Register,
            },
        ],
        meta: {
            requiresGuest: true,
        },
    },
    {
        path: "/dashboard",
        name: "Dashboard",
        component: Dashboard,
        meta: { requiresAuth: true },
    },
    {
        path: "/tasks",
        name: "Tasks",
        component: TaskList,
        meta: { requiresAuth: true },
    },
    {
        path: "/tasks/:id",
        name: "TaskShow",
        component: TaskShow,
        meta: { requiresAuth: true },
    },
    {
        path: "/tasks/:id/edit",
        name: "TaskEdit",
        component: TaskEdit,
        meta: { requiresAuth: true },
    },
    {
        path: "/tasks/create",
        name: "TaskCreate",
        component: TaskCreate,
        meta: { requiresAuth: true },
    },
    {
        path: "/",
        redirect: (to) => {
            return store.getters["auth/isTokenValid"] ? "/tasks" : "/auth"; // Проверяем токен в Vuex
        },
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

// import { mapActions, mapGetters } from "vuex";

router.beforeEach(async (to, from, next) => {
    if (to.meta.requiresAuth && !store.getters["auth/isTokenValid"]) {
        try {
            await store.dispatch("auth/checkToken");

            if (store.getters["auth/isTokenValid"]) {
                return next(); //  Доступ разрешен
            }
        } catch (error) {
            console.error("Ошибка при обновлении токена:", error);
        }

        let redirectPath = "/auth";
        if (to.fullPath !== "/") {
            redirectPath = `/auth?redirect=${encodeURIComponent(to.fullPath)}`;
        }
        return next(redirectPath);
    }
    next();
});

export { router, store };
