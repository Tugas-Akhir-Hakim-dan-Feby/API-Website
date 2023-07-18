import { checkPermission, checkRoles } from "../../store/utils/middleware";
import store from "../../store";

export default [
    {
        path: "/skill/expert",
        component: () => import("../../pages/skill/expert/Index.vue"),
        name: "Expert Skill",
        meta: {
            requiresAuth: true,
        },
    },
    {
        path: "/skill/welder",
        component: () => import("../../pages/skill/welder/Index.vue"),
        name: "Welder Skill",
        meta: {
            requiresAuth: true,
        },
        beforeEnter: checkPermission("welderskill", "index"),
    },
];
