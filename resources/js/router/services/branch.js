import { checkRoles } from "../../store/utils/middleware";
import store from "../../store";

export default [
    {
        path: "/branch",
        component: () => import("../../pages/branch/Index.vue"),
        name: "Branch",
        meta: {
            requiresAuth: true,
        },
        beforeEnter: checkRoles([store.state.ADMIN_APP, store.state.ADMIN_HUB]),
    },
];
