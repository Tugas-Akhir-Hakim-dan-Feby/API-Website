import "./bootstrap";

import "@vueup/vue-quill/dist/vue-quill.snow.css";
import "izitoast/dist/css/iziToast.min.css";
import IconTitle from "../images/api-iws.png";

import { createApp } from "vue";
import iziToast from "izitoast";
import { QuillEditor } from "@vueup/vue-quill";
import router from "./router";
import store from "./store";

import App from "./core/App.vue";
import Auth from "./core/Auth.vue";
import Attempt from "./core/Attempt.vue";

createApp(Auth).use(router).use(store).mount("#auth");
createApp(Attempt).use(router).use(store).mount("#test");
createApp(App)
    .use(router)
    .use(store)
    .use(iziToast)
    .component("QuillEditor", QuillEditor)
    .mount("#app");

document.getElementById("icon-title").href = IconTitle;
