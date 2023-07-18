export default [
    {
        path: "/member",
        name: "Member",
        component: () => import("../../pages/member/Index.vue"),
        meta: {
            requiresAuth: true,
        },
    },
    {
        path: "/member/welder",
        name: "Member Welder",
        component: () => import("../../pages/member/Welder.vue"),
        meta: {
            requiresAuth: true,
        },
    },
    {
        path: "/member/company",
        name: "Member Company",
        component: () => import("../../pages/member/Company.vue"),
        meta: {
            requiresAuth: true,
        },
    },
    {
        path: "/member/tuk",
        name: "Member Operator",
        component: () => import("../../pages/member/Operator.vue"),
        meta: {
            requiresAuth: true,
        },
    },
];
