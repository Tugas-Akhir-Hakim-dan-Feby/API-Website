import { checkRoles } from "../../../store/utils/middleware";
import store from "../../../store";

export default [
    {
        path: "/user/company",
        component: () => import("../../../pages/user/company/Index.vue"),
        name: "User Company",
        meta: {
            requiresAuth: true,
        },
        beforeEnter: checkRoles([store.state.ADMIN_APP, store.state.ADMIN_HUB]),
    },
    // {
    //     path: "/user/company/create",
    //     component: import("../../../pages/user/company/Create.vue"),
    //     name: "User Company Create",
    //     meta: {
    //         // requiresAuth: true,
    //     },
    // },
    {
        path: "/user/company/:id",
        component: import("../../../pages/user/company/Show.vue"),
        name: "User Company Show",
        meta: {
            requiresAuth: true,
        },
        props: true,
        beforeEnter: checkRoles([store.state.ADMIN_APP, store.state.ADMIN_HUB]),
    },
];
