import { checkPermission } from "../../store/utils/middleware";

export default [
    {
        path: "/job-vacancy",
        component: () => import("../../pages/jobVacancy/Index.vue"),
        name: "Job Vacancy",
        meta: {
            requiresAuth: true,
        },
        beforeEnter: checkPermission("jobvacancy", "index"),
    },
    {
        path: "/job-vacancy/create",
        component: () => import("../../pages/jobVacancy/Create.vue"),
        name: "Job Vacancy Create",
        meta: {
            requiresAuth: true,
        },
        beforeEnter: checkPermission("jobvacancy", "create"),
    },
    {
        path: "/job-vacancy/:slug/apply",
        component: () => import("../../pages/jobVacancy/Apply.vue"),
        name: "Job Vacancy Apply",
        meta: {
            requiresAuth: true,
        },
        props: true,
        beforeEnter: checkPermission("jobvacancy", "show"),
    },
    {
        path: "/job-vacancy/:uuid/participant",
        component: () => import("../../pages/jobVacancy/Participant.vue"),
        name: "Job Vacancy Participant",
        meta: {
            requiresAuth: true,
        },
        props: true,
        beforeEnter: checkPermission("jobvacancy", "show"),
    },
    {
        path: "/job-vacancy/:slug",
        component: () => import("../../pages/jobVacancy/Detail.vue"),
        name: "Job Vacancy Detail",
        meta: {
            requiresAuth: true,
        },
        props: true,
        beforeEnter: checkPermission("jobvacancy", "show"),
    },
    {
        path: "/job-vacancy/:id/edit",
        component: () => import("../../pages/jobVacancy/Edit.vue"),
        name: "Job Vacancy Edit",
        meta: {
            requiresAuth: true,
        },
        props: true,
        beforeEnter: checkPermission("jobvacancy", "update"),
    },
];
