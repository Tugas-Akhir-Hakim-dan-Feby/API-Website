import auth from "./services/auth";
import skill from "./services/skill";
import branch from "./services/branch";
import question from "./services/question";
import userHub from "./services/user/hub";
import userBranch from "./services/user/branch";
import userExpert from "./services/user/expert";
import userCompany from "./services/user/company";
import userMember from "./services/user/member";
import article from "./services/article";

const routes = [
    ...auth,
    ...skill,
    ...branch,
    ...question,
    ...userHub,
    ...userBranch,
    ...userExpert,
    ...userCompany,
    ...userMember,
    ...article,
    {
        path: "/",
        name: "Dashboard",
        component: () => import("../pages/home/Index.vue"),
        meta: {
            // requiresAuth: true,
        },
    },
    {
        path: "/my-profile",
        name: "My Profile",
        component: () => import("../pages/user/MyProfile.vue"),
        meta: {
            // requiresAuth: true,
        },
    },
];

export default routes;
