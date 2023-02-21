export default [
    {
        path: "/user/member",
        component: () => import("../../../pages/user/member/Index.vue"),
        name: "User Member",
        meta: {
            requiresAuth: true,
        },
    },
    // {
    //     path: "/user/company/create",
    //     component: import("../../../pages/user/company/Create.vue"),
    //     name: "User Company Create",
    //     meta: {
    //         // requiresAuth: true,
    //     },
    // },
    // {
    //     path: "/user/company/:id/edit",
    //     component: import("../../../pages/user/company/Edit.vue"),
    //     name: "User Company Edit",
    //     meta: {
    //         // requiresAuth: true,
    //     },
    //     props: true,
    // },
];
