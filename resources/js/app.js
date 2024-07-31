import "./bootstrap";
import { createApp } from "vue";
import App from "./App.vue";

import axios from "@/axios";
import VueAxios from "vue-axios";
import router from "./router";
import vuetify from "./vuetify";

const app = createApp(App);

// import ExampleComponent from "./components/ExampleComponent.vue";
// app.component("example-component", ExampleComponent);

app.use(VueAxios, axios).use(vuetify).use(router).mount("#app");
