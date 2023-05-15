export default [
    {
        path: "/expert",
        name: "Register Expert",
        component: () => import("../../pages/expert/Create.vue"),
        meta: {
            requiresAuth: true,
        },
    },
];
