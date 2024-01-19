import { checkPermission, checkRoles } from "../../store/utils/middleware";
import store from "../../store";

export default [
    {
        path: "/branch",
        component: () => import("../../pages/branch/Index.vue"),
        name: "Branch",
        meta: {
            requiresAuth: true,
        },
        beforeEnter: checkPermission("branch", "index"),
    },
    {
        path: "/branch-dashboard",
        component: () => import("../../pages/branch/Dashboard.vue"),
        name: "Branch Dashboard",
        meta: {
            requiresAuth: true,
        },
        beforeEnter: checkPermission("branch", "dashboard"),
    },
];
