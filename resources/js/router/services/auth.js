export default [
    {
        path: "/auth/login",
        name: "Login",
        component: () => import("../../pages/auth/Login.vue"),
        meta: {
            // requiresAuth: false,
        },
    },
    {
        path: "/auth/register",
        name: "Register",
        component: () => import("../../pages/auth/Register.vue"),
        meta: {
            // requiresAuth: false,
        },
    },
    {
        path: "/auth/forgot-password",
        name: "Forgot Password",
        component: () => import("../../pages/auth/ForgotPassword.vue"),
        meta: {
            // requiresAuth: false,
        },
    },
];
