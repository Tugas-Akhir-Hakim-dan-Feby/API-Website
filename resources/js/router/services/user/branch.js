import { checkRoles } from "../../../store/utils/middleware";
import store from "../../../store";

export default [
    {
        path: "/user/branch",
        component: () => import("../../../pages/user/branch/Index.vue"),
        name: "User Branch",
        meta: {
            requiresAuth: true,
        },
        beforeEnter: checkRoles([store.state.ADMIN_APP, store.state.ADMIN_HUB]),
    },
    {
        path: "/user/branch/create",
        component: () => import("../../../pages/user/branch/Create.vue"),
        name: "User Branch Create",
        meta: {
            requiresAuth: true,
        },
        beforeEnter: checkRoles([store.state.ADMIN_APP, store.state.ADMIN_HUB]),
    },
    {
        path: "/user/branch/:id/edit",
        component: () => import("../../../pages/user/branch/Edit.vue"),
        name: "User Branch Edit",
        meta: {
            requiresAuth: true,
        },
        props: true,
        beforeEnter: checkRoles([store.state.ADMIN_APP, store.state.ADMIN_HUB]),
    },
];
