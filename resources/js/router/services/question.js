export default [
    {
        path: "/question",
        component: () => import("../../pages/question/Index.vue"),
        name: "Question",
        meta: {
            // requiresAuth: true,
        },
    },
    {
        path: "/question/create",
        component: () => import("../../pages/question/Create.vue"),
        name: "Question Create",
        meta: {
            // requiresAuth: true,
        },
    },
    {
        path: "/question/:id/edit",
        component: () => import("../../pages/question/Edit.vue"),
        name: "Question Edit",
        meta: {
            // requiresAuth: true,
        },
        props: true,
    },
];
