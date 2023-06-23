export default [
    {
        path: "/payment/cost",
        name: "Payment Cost",
        component: () => import("../../pages/payment/Cost.vue"),
        meta: {
            requiresAuth: true,
        },
    },
    {
        path: "/payment/recapitulation-invoice",
        name: "Payment Recapitulation Invoice",
        component: () => import("../../pages/payment/RecapituationInvoice.vue"),
        meta: {
            requiresAuth: true,
        },
    },
    {
        path: "/payment/recapitulation-invoice/:externalId",
        component: () =>
            import("../../pages/payment/RecapituationInvoiceDetail.vue"),
        name: "Show Payment Recapitulation",
        meta: {
            requiresAuth: true,
        },
        props: true,
    },
];
