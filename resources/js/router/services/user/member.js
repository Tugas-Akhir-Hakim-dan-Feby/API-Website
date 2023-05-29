import { checkRoles } from "../../../store/utils/middleware";
import store from "../../../store";

export default [
    {
        path: "/user/member",
        component: () => import("../../../pages/user/member/Index.vue"),
        name: "User Member",
        meta: {
            requiresAuth: true,
        },
        beforeEnter: checkRoles([store.state.ADMIN_APP, store.state.ADMIN_HUB]),
    },
    {
        path: "/user/member/:id",
        component: () => import("../../../pages/user/member/Show.vue"),
        name: "User Member Show",
        meta: {
            requiresAuth: true,
        },
        props: true,
        beforeEnter: checkRoles([store.state.ADMIN_APP, store.state.ADMIN_HUB]),
    },
];
