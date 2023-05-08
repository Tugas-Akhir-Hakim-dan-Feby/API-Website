export default [
    {
        path: "/invoice/:externalId/:costId",
        component: () => import("../../pages/invoice/Show.vue"),
        name: "Show Invoice",
        meta: {
            requiresAuth: true,
        },
        props: true,
    },
    {
        path: "/invoice/success",
        component: () => import("../../pages/invoice/Success.vue"),
        name: "Invoice Success",
        meta: {
            requiresAuth: true,
        },
    },
];
