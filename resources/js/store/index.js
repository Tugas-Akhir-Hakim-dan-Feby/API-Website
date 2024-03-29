import { createStore } from "vuex";
import action from "./services/action";
import ticket from "./modules/ticket";

const store = createStore({
    modules: {
        action,
        ticket,
    },
    state: {
        USER: null,
        PERMISSION: null,
        ADMIN_APP: "Admin App",
        ADMIN_HUB: "Admin Pusat",
        ADMIN_BRANCH: "Admin Cabang",
        EXPERT: "Pakar",
        MEMBER_COMPANY: "Member Company",
        MEMBER_WELDER: "Member Individu",
        GUEST: "Member Aplikasi",
        OPERATOR: "Operator",
        PAID: "PAID",
        PENDING: "PENDING",
        BASE_URL: window.origin,
        BASE_URL_REGION: "https://www.emsifa.com/api-wilayah-indonesia/api/",
        COST: {
            MEMBER_WELDER: 1,
            MEMBER_COMPANY: 2,
            ADVERTISEMENT: 3,
            EXAM_INSTITUTE: 4,
        },
        QUESTION_TYPE: {
            TRUE_FALSE: "TrueFalse",
            MULTIPLE_CHOICE: "MultipleChoice",
        },
    },
    mutations: {
        setUser(state, user) {
            state.USER = user;
        },
        setPermission(state, permission) {
            state.PERMISSION = permission;
        },
    },
});

export default store;
