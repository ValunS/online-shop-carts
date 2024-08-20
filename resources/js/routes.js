import { createWebHistory, createRouter } from "vue-router";

import PurchaseForm from "@/components/PurchaseForm.vue";
import PurchasesList from "@/components/PurchasesList.vue";
// import Login from "@/components/Auth/Login.vue";
// import Register from "@/components/Auth/Register.vue";
// import TaskList from "@/components/TaskList.vue";
// import TaskShow from "@/components/TaskShow.vue";
// import TaskEdit from "@/components/TaskEdit.vue";
// import TaskCreate from "@/components/TaskCreate.vue";

//use store by VueX

const routes = [
    {
        path: "/purchases",
        name: "PurchasesList",
        component: PurchasesList,
    },
    {
        path: "/purchases/{id}",
        name: "PurchaseForm",
        component: PurchaseForm,
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
