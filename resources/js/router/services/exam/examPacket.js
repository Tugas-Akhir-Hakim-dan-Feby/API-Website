export default [
    {
        path: "/exam-packet",
        component: () => import("../../../pages/examPacket/Index.vue"),
        name: "Exam Packet",
        meta: {
            requiresAuth: true,
        },
    },
    {
        path: "/exam-packet/create",
        component: () => import("../../../pages/examPacket/Create.vue"),
        name: "Exam Packet Create",
        meta: {
            requiresAuth: true,
        },
    },
    {
        path: "/exam-packet/register",
        component: () => import("../../../pages/examPacket/Register.vue"),
        name: "Exam Packet Register",
        meta: {
            requiresAuth: true,
        },
    },
    {
        path: "/exam-packet/:examPacketId/success",
        component: () => import("../../../pages/examPacket/Success.vue"),
        name: "Exam Packet Success",
        meta: {
            requiresAuth: true,
        },
        props: true,
    },
    {
        path: "/exam-packet/:id/detail",
        component: () => import("../../../pages/examPacket/Detail.vue"),
        name: "Exam Packet Detail",
        meta: {
            requiresAuth: true,
        },
        props: true,
    },
];
