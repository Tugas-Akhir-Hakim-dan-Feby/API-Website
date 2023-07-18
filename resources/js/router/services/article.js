import { checkPermission } from "../../store/utils/middleware";

export default [
    {
        path: "/article",
        component: () => import("../../pages/article/Index.vue"),
        name: "Article",
        meta: {
            requiresAuth: true,
        },
        beforeEnter: checkPermission("article", "index"),
    },
    {
        path: "/article/create",
        component: () => import("../../pages/article/Create.vue"),
        name: "Article Create",
        meta: {
            requiresAuth: true,
        },
        beforeEnter: checkPermission("article", "create"),
    },
    {
        path: "/article/:slug",
        component: () => import("../../pages/article/Detail.vue"),
        name: "Article Detail",
        meta: {
            requiresAuth: true,
        },
        props: true,
        beforeEnter: checkPermission("article", "show"),
    },
    {
        path: "/article/:id/edit",
        component: () => import("../../pages/article/Edit.vue"),
        name: "Article Edit",
        meta: {
            requiresAuth: true,
        },
        props: true,
        beforeEnter: checkPermission("article", "update"),
    },
];
