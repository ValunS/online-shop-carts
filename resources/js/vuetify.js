import { createApp } from "vue";
import App from "./App.vue";
import vuetify from "./vuetify"; // Добавьте эту строку

const app = createApp(App);
app.use(vuetify); // Инициализируйте Vuetify
app.mount("#app");
