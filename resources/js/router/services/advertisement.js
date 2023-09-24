import { checkPermission } from "../../store/utils/middleware";

export default [
    {
        path: "/advertisement",
        component: () => import("../../pages/advertisement/Index.vue"),
        name: "Advertisement",
        meta: {
            requiresAuth: true,
        },
        beforeEnter: checkPermission("advertisement", "show"),
    },
    {
        path: "/advertisement/history",
        component: () => import("../../pages/advertisement/History.vue"),
        name: "Advertisement History",
        meta: {
            requiresAuth: true,
        },
        beforeEnter: checkPermission("advertisement", "show"),
    },
    {
        path: "/advertisement/create",
        component: () => import("../../pages/advertisement/Create.vue"),
        name: "Advertisement Create",
        meta: {
            requiresAuth: true,
        },
        beforeEnter: checkPermission("advertisement", "create"),
    },
    {
        path: "/advertisement/:id/edit",
        component: () => import("../../pages/advertisement/Edit.vue"),
        name: "Advertisement Edit",
        meta: {
            requiresAuth: true,
        },
        props: true,
        beforeEnter: checkPermission("advertisement", "update"),
    },
];
