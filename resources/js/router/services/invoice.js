export default [
    {
        path: "/invoice/:externalId/:costId",
        component: () => import("../../pages/invoice/Index.vue"),
        name: "Invoice",
        meta: {
            requiresAuth: true,
        },
        props: true,
    },
];
