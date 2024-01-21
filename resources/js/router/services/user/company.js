import { checkPermission, checkRoles } from "../../../store/utils/middleware";
import store from "../../../store";

export default [
    {
        path: "/user/company",
        component: () => import("../../../pages/user/company/Index.vue"),
        name: "User Company",
        meta: {
            requiresAuth: true,
        },
        beforeEnter: checkPermission("companymember", "index"),
    },
    // {
    //     path: "/user/company/create",
    //     component: import("../../../pages/user/company/Test.vue"),
    //     name: "User Company Create",
    //     meta: {
    //         // requiresAuth: true,
    //     },
    // },
    {
        path: "/user/company/:id",
        component: () => import("../../../pages/user/company/Show.vue"),
        name: "User Company Show",
        meta: {
            requiresAuth: true,
        },
        props: true,
        beforeEnter: checkPermission("companymember", "show"),
    },
];
