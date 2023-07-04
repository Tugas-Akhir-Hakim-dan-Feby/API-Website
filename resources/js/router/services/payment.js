import { checkRoles } from "../../store/utils/middleware";
import store from "../../store";

export default [
    {
        path: "/payment/cost",
        name: "Payment Cost",
        component: () => import("../../pages/payment/Cost.vue"),
        meta: {
            requiresAuth: true,
        },
        beforeEnter: checkRoles([store.state.ADMIN_APP, store.state.ADMIN_HUB]),
    },
    {
        path: "/payment/history",
        name: "Payment History",
        component: () => import("../../pages/payment/History.vue"),
        meta: {
            requiresAuth: true,
        },
        beforeEnter: checkRoles([
            store.state.MEMBER_WELDER,
            store.state.MEMBER_COMPANY,
        ]),
    },
    {
        path: "/payment/history/:externalId",
        name: "Payment History Detail",
        component: () => import("../../pages/payment/HistoryDetail.vue"),
        meta: {
            requiresAuth: true,
        },
        props: true,
        beforeEnter: checkRoles([
            store.state.MEMBER_WELDER,
            store.state.MEMBER_COMPANY,
        ]),
    },
    {
        path: "/payment/recapitulation-invoice",
        name: "Payment Recapitulation Invoice",
        component: () => import("../../pages/payment/RecapituationInvoice.vue"),
        meta: {
            requiresAuth: true,
        },
        beforeEnter: checkRoles([store.state.ADMIN_APP, store.state.ADMIN_HUB]),
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
        beforeEnter: checkRoles([store.state.ADMIN_APP, store.state.ADMIN_HUB]),
    },
];
