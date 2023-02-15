import routes from "./routes";
import { createRouter, createWebHistory } from "vue-router";

const router = createRouter({
    history: createWebHistory(),
    routes,
    linkActiveClass: "menuitem-active",
});

router.beforeEach((to, from, next) => {
    document.title =
        to.name + " - Asosiasi Pengelasan Indonesia" ||
        "Asosiasi Pengelasan Indonesia";
    next();
});

export default router;
