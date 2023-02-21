import routes from "./routes";
import Cookies from "js-cookie";
import { createRouter, createWebHistory } from "vue-router";

const router = createRouter({
    history: createWebHistory(),
    routes,
    linkActiveClass: "menuitem-active",
});

router.beforeEach((to, from, next) => {
    const token = Cookies.get("token");
    if (to.meta.requiresAuth == true) {
        if (!token) {
            window.location.href = "/auth/login";
        } else {
            next();
        }
    }

    if (to.meta.requiresAuth == false) {
        if (token) {
            window.location.href = "/";
        } else {
            next();
        }
    }

    document.title =
        to.name + " - Asosiasi Pengelasan Indonesia" ||
        "Asosiasi Pengelasan Indonesia";
    next();
});

export default router;
