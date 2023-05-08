import auth from "./services/auth";
import skill from "./services/skill";
import branch from "./services/branch";
import userHub from "./services/user/hub";
import userBranch from "./services/user/branch";
import userExpert from "./services/user/expert";
import userCompany from "./services/user/company";
import userMember from "./services/user/member";
import article from "./services/article";
import examPacket from "./services/exam/examPacket";
import exam from "./services/exam/exam";
import member from "./services/member";
import invoice from "./services/invoice";

const routes = [
    ...auth,
    ...skill,
    ...branch,
    ...userHub,
    ...userBranch,
    ...userExpert,
    ...userCompany,
    ...userMember,
    ...article,
    ...examPacket,
    ...exam,
    ...member,
    ...invoice,
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
