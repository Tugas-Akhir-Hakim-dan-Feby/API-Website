export default [
    {
        path: "/exam/:id/create",
        component: () => import("../../../pages/exam/Create.vue"),
        name: "Exam Create",
        meta: {
            requiresAuth: true,
        },
        props: true,
    },
    {
        path: "/exam/:id/edit/:examId",
        component: () => import("../../../pages/exam/Edit.vue"),
        name: "Exam Edit",
        meta: {
            requiresAuth: true,
        },
        props: true,
    },
];
