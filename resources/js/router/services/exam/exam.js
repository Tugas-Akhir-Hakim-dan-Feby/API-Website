import { checkPermission } from "../../../store/utils/middleware";

export default [
    {
        path: "/exam/:id/create",
        component: () => import("../../../pages/exam/Create.vue"),
        name: "Exam Create",
        meta: {
            requiresAuth: true,
        },
        props: true,
        beforeEnter: checkPermission("exam", "create"),
    },
    {
        path: "/attempt/:examPacketId/execution/:examId",
        component: () => import("../../../pages/exam/execution/Show.vue"),
        name: "Exam Execution",
        meta: {
            requiresAuth: true,
        },
        props: true,
        beforeEnter: checkPermission("exam", "show"),
    },
    {
        path: "/exam/:id/edit/:examId",
        component: () => import("../../../pages/exam/Edit.vue"),
        name: "Exam Edit",
        meta: {
            requiresAuth: true,
        },
        props: true,
        beforeEnter: checkPermission("exam", "update"),
    },
];
