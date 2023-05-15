export default [
    {
        path: "/user/expert",
        component: () => import("../../../pages/user/expert/Index.vue"),
        name: "User Expert",
        meta: {
            requiresAuth: true,
        },
    },
    {
        path: "/user/expert/create",
        component: () => import("../../../pages/user/expert/Create.vue"),
        name: "User Expert Create",
        meta: {
            requiresAuth: true,
        },
    },
    {
        path: "/user/expert/:id",
        component: () => import("../../../pages/user/expert/Show.vue"),
        name: "User Expert Show",
        meta: {
            requiresAuth: true,
        },
        props: true,
    },
    {
        path: "/user/expert/:id/edit",
        component: () => import("../../../pages/user/expert/Edit.vue"),
        name: "User Expert Edit",
        meta: {
            requiresAuth: true,
        },
        props: true,
    },
];
