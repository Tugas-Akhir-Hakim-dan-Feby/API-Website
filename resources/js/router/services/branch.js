export default [
    {
        path: "/branch",
        component: () => import("../../pages/branch/Index.vue"),
        name: "Branch",
        meta: {
            // requiresAuth: true,
        },
    },
];
