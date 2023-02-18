import "./bootstrap";

import "@vueup/vue-quill/dist/vue-quill.snow.css";
import IconTitle from "../images/api-iws.png";

import { createApp } from "vue";
import { QuillEditor } from "@vueup/vue-quill";
import router from "./router";
import store from "./store";

import App from "./core/App.vue";
import Auth from "./core/Auth.vue";

createApp(Auth).use(router).use(store).mount("#auth");
createApp(App)
    .use(router)
    .use(store)
    .component("QuillEditor", QuillEditor)
    .mount("#app");

document.getElementById("icon-title").href = IconTitle;
