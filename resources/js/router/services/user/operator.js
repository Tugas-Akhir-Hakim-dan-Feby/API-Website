import { checkPermission, checkRoles } from "../../../store/utils/middleware";
import store from "../../../store";

export default [
    {
        path: "/user/operator",
        component: () => import("../../../pages/user/operator/Index.vue"),
        name: "User Operator",
        meta: {
            requiresAuth: true,
        },
        beforeEnter: checkPermission("weldermember", "index"),
    },
    {
        path: "/user/operator/:id",
        component: () => import("../../../pages/user/operator/Show.vue"),
        name: "User Operator Show",
        meta: {
            requiresAuth: true,
        },
        props: true,
        beforeEnter: checkPermission("weldermember", "show"),
    },
];
