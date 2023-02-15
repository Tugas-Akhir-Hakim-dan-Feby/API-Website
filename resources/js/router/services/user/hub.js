export default [
    {
        path: "/user/hub",
        component: () => import("../../../pages/user/apiHub/Index.vue"),
        name: "User Hub",
        meta: {
            // requiresAuth: true,
        },
    },
    {
        path: "/user/hub/create",
        component: () => import("../../../pages/user/apiHub/Create.vue"),
        name: "User Hub Create",
        meta: {
            // requiresAuth: true,
        },
    },
    {
        path: "/user/hub/:id",
        component: () => import("../../../pages/user/apiHub/Detail.vue"),
        name: "User Hub Detail",
        meta: {
            // requiresAuth: true,
        },
        props: true,
    },
    {
        path: "/user/hub/:id/edit",
        component: () => import("../../../pages/user/apiHub/Edit.vue"),
        name: "User Hub Edit",
        meta: {
            // requiresAuth: true,
        },
        props: true,
    },
];
