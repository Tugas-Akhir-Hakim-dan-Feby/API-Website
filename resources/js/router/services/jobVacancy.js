export default [
    {
        path: "/job-vacancy",
        component: () => import("../../pages/jobVacancy/Index.vue"),
        name: "Job Vacancy",
        meta: {
            requiresAuth: true,
        },
    },
    {
        path: "/job-vacancy/create",
        component: () => import("../../pages/jobVacancy/Create.vue"),
        name: "Job Vacancy Create",
        meta: {
            requiresAuth: true,
        },
    },
    {
        path: "/job-vacancy/:slug",
        component: () => import("../../pages/jobVacancy/Detail.vue"),
        name: "Job Vacancy Detail",
        meta: {
            requiresAuth: true,
        },
        props: true,
    },
    {
        path: "/job-vacancy/:id/edit",
        component: () => import("../../pages/jobVacancy/Edit.vue"),
        name: "Job Vacancy Edit",
        meta: {
            requiresAuth: true,
        },
        props: true,
    },
];