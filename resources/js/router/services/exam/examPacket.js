import { checkPermission } from "../../../store/utils/middleware";

export default [
    {
        path: "/exam-packet",
        component: () => import("../../../pages/examPacket/Index.vue"),
        name: "Exam Packet",
        meta: {
            requiresAuth: true,
        },
        beforeEnter: checkPermission("exampacket", "index"),
    },
    {
        path: "/exam-packet/create",
        component: () => import("../../../pages/examPacket/Create.vue"),
        name: "Exam Packet Create",
        meta: {
            requiresAuth: true,
        },
        beforeEnter: checkPermission("exampacket", "create"),
    },
    {
        path: "/exam-packet/register",
        component: () => import("../../../pages/examPacket/Register.vue"),
        name: "Exam Packet Register",
        meta: {
            requiresAuth: true,
        },
        beforeEnter: checkPermission("exampacket", "show"),
    },
    {
        path: "/exam-packet/:examPacketId/success",
        component: () => import("../../../pages/examPacket/Success.vue"),
        name: "Exam Packet Success",
        meta: {
            requiresAuth: true,
        },
        props: true,
        beforeEnter: checkPermission("exampacket", "show"),
    },
    {
        path: "/exam-packet/:id/detail/participant",
        component: () => import("../../../pages/examPacket/Participant.vue"),
        name: "Exam Packet Participant",
        meta: {
            requiresAuth: true,
        },
        props: true,
        beforeEnter: checkPermission("exampacket", "show"),
    },
    {
        path: "/exam-packet/:id/edit",
        component: () => import("../../../pages/examPacket/Edit.vue"),
        name: "Exam Packet Edit",
        meta: {
            requiresAuth: true,
        },
        props: true,
        beforeEnter: checkPermission("exampacket", "update"),
    },
    {
        path: "/exam-packet/:id/detail",
        component: () => import("../../../pages/examPacket/Detail.vue"),
        name: "Exam Packet Detail",
        meta: {
            requiresAuth: true,
        },
        props: true,
        beforeEnter: checkPermission("exampacket", "show"),
    },
];
