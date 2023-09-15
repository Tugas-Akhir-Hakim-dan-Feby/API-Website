import "./bootstrap";

import "@vueup/vue-quill/dist/vue-quill.snow.css";
import "izitoast/dist/css/iziToast.min.css";
import "vue-multiselect/dist/vue-multiselect.min.css";
import IconTitle from "../images/api-iws.png";

import { createApp } from "vue";
import { QuillEditor } from "@vueup/vue-quill";
import { abilitiesPlugin } from "@casl/vue";
import iziToast from "izitoast";
import router from "./router";
import store from "./store";
import ability from "./store/services/ability";

import VueChartkick from "vue-chartkick";
import "chartkick/chart.js";

import App from "./core/App.vue";
import Auth from "./core/Auth.vue";
import Attempt from "./core/Attempt.vue";

createApp(Auth).use(router).use(store).mount("#auth");
createApp(Attempt).use(router).use(store).mount("#test");
createApp(App)
    .use(router)
    .use(store)
    .use(iziToast)
    .use(abilitiesPlugin, ability, {
        useGlobalProperties: true,
    })
    .use(VueChartkick)
    .component("QuillEditor", QuillEditor)
    .mount("#app");

document.getElementById("icon-title").href = IconTitle;
