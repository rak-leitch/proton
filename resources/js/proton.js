import { createApp } from "vue";
import app from "./layouts/proton.vue";
import vuetify from "./vuetify";
import "../styles/custom.scss";

createApp(app).use(vuetify).mount("#proton");
