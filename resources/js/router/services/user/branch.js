export default [
    {
        path: "/user/branch",
        component: () => import("../../../pages/user/apiBranch/Index.vue"),
        name: "User Branch",
        meta: {
            requiresAuth: true,
        },
    },
    {
        path: "/user/branch/create",
        component: () => import("../../../pages/user/apiBranch/Create.vue"),
        name: "User Branch Create",
        meta: {
            requiresAuth: true,
        },
    },
    {
        path: "/user/branch/:id/edit",
        component: () => import("../../../pages/user/apiBranch/Edit.vue"),
        name: "User Branch Edit",
        meta: {
            requiresAuth: true,
        },
        props: true,
    },
];
