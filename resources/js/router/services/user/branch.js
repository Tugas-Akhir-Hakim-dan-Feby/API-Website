import { checkPermission, checkRoles } from "../../../store/utils/middleware";
import store from "../../../store";

export default [
    {
        path: "/user/branch",
        component: () => import("../../../pages/user/branch/Index.vue"),
        name: "User Branch",
        meta: {
            requiresAuth: true,
        },
        beforeEnter: checkPermission("adminbranch", "index"),
    },
    {
        path: "/user/branch/create",
        component: () => import("../../../pages/user/branch/Create.vue"),
        name: "User Branch Create",
        meta: {
            requiresAuth: true,
        },
        beforeEnter: checkPermission("adminbranch", "create"),
    },
    {
        path: "/user/branch/:id/edit",
        component: () => import("../../../pages/user/branch/Edit.vue"),
        name: "User Branch Edit",
        meta: {
            requiresAuth: true,
        },
        props: true,
        beforeEnter: checkPermission("adminbranch", "update"),
    },
];
