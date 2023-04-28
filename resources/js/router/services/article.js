export default [
    {
        path: "/article",
        component: () => import("../../pages/article/Index.vue"),
        name: "Article",
        meta: {
            requiresAuth: true,
        },
    },
    {
        path: "/article/create",
        component: () => import("../../pages/article/Create.vue"),
        name: "Article Create",
        meta: {
            requiresAuth: true,
        },
    },
    {
        path: "/article/:slug",
        component: () => import("../../pages/article/Detail.vue"),
        name: "Article Detail",
        meta: {
            requiresAuth: true,
        },
        props: true,
    },
    {
        path: "/article/:id/edit",
        component: () => import("../../pages/article/Edit.vue"),
        name: "Article Edit",
        meta: {
            requiresAuth: true,
        },
        props: true,
    },
];
