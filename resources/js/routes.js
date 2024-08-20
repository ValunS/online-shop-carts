import { createWebHistory, createRouter } from "vue-router";

import PurchasesList from "./components/PurсhasesList.vue";
import PurchaseForm from "./components/PurсhaseForm.vue";

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
