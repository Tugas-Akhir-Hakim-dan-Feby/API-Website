export default [
    {
        path: "/user/company",
        component: () => import("../../../pages/user/company/Index.vue"),
        name: "User Company",
        meta: {
            // requiresAuth: true,
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
