import { checkPermission } from "../../store/utils/middleware";

export default [
    {
        path: "/job-history",
        component: () => import("../../pages/jobHistory/Index.vue"),
        name: "Job History",
        meta: {
            requiresAuth: true,
        },
        beforeEnter: checkPermission("jobvacancy", "index"),
    },
];
