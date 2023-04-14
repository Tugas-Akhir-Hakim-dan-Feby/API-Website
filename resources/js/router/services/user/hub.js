export default [
    {
        path: "/user/hub",
        component: () => import("../../../pages/user/hub/Index.vue"),
        name: "User Hub",
        meta: {
            requiresAuth: true,
        },
    },
    {
        path: "/user/hub/create",
        component: () => import("../../../pages/user/hub/Create.vue"),
        name: "User Hub Create",
        meta: {
            requiresAuth: true,
        },
    },
    {
        path: "/user/hub/:id",
        component: () => import("../../../pages/user/hub/Detail.vue"),
        name: "User Hub Detail",
        meta: {
            requiresAuth: true,
        },
        props: true,
    },
    {
        path: "/user/hub/:id/edit",
        component: () => import("../../../pages/user/hub/Edit.vue"),
        name: "User Hub Edit",
        meta: {
            requiresAuth: true,
        },
        props: true,
    },
];
