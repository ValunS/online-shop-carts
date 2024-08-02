import { createWebHistory, createRouter } from "vue-router";

import PurshaseForm from "@/components/PurshaseForm.vue";
import PurshasesList from "@/components/PurshasesList.vue";
// import Login from "@/components/Auth/Login.vue";
// import Register from "@/components/Auth/Register.vue";
// import TaskList from "@/components/TaskList.vue";
// import TaskShow from "@/components/TaskShow.vue";
// import TaskEdit from "@/components/TaskEdit.vue";
// import TaskCreate from "@/components/TaskCreate.vue";

//use store by VueX

const routes = [
    {
        path: "/purshases",
        name: "PurshasesList",
        component: PurshasesList,
    },
    {
        path: "/purshases/{id}",
        name: "PurshaseForm",
        component: PurshaseForm,
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
