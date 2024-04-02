import { createApp } from "vue";
import app from "./layouts/proton.vue";
import { createRouter, createWebHistory } from "vue-router";
import routes from "./routes";
import vuetify from "./vuetify";
import "../styles/custom.scss";

const router = createRouter({
    history: createWebHistory(),
    routes,
});

createApp(app).use(router).use(vuetify).mount("#proton");
