import { checkRoles } from "../../../store/utils/middleware";
import store from "../../../store";

export default [
    {
        path: "/user/expert",
        component: () => import("../../../pages/user/expert/Index.vue"),
        name: "User Expert",
        meta: {
            requiresAuth: true,
        },
        beforeEnter: checkRoles([store.state.ADMIN_APP, store.state.ADMIN_HUB]),
    },
    {
        path: "/user/expert/create",
        component: () => import("../../../pages/user/expert/Create.vue"),
        name: "User Expert Create",
        meta: {
            requiresAuth: true,
        },
        beforeEnter: checkRoles([store.state.ADMIN_APP, store.state.ADMIN_HUB]),
    },
    {
        path: "/user/expert/:id",
        component: () => import("../../../pages/user/expert/Show.vue"),
        name: "User Expert Show",
        meta: {
            requiresAuth: true,
        },
        props: true,
        beforeEnter: checkRoles([store.state.ADMIN_APP, store.state.ADMIN_HUB]),
    },
    {
        path: "/user/expert/:id/edit",
        component: () => import("../../../pages/user/expert/Edit.vue"),
        name: "User Expert Edit",
        meta: {
            requiresAuth: true,
        },
        props: true,
        beforeEnter: checkRoles([store.state.ADMIN_APP, store.state.ADMIN_HUB]),
    },
];
