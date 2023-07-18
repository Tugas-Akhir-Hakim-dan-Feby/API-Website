import { checkPermission, checkRoles } from "../../../store/utils/middleware";
import store from "../../../store";

export default [
    {
        path: "/user/member",
        component: () => import("../../../pages/user/member/Index.vue"),
        name: "User Member",
        meta: {
            requiresAuth: true,
        },
        beforeEnter: checkPermission("weldermember", "index"),
    },
    {
        path: "/user/member/:id",
        component: () => import("../../../pages/user/member/Show.vue"),
        name: "User Member Show",
        meta: {
            requiresAuth: true,
        },
        props: true,
        beforeEnter: checkPermission("weldermember", "show"),
    },
];
