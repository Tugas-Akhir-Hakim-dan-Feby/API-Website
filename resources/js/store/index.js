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
        ADMIN_APP: "Admin App",
        ADMIN_HUB: "Admin Pusat",
        ADMIN_BRANCH: "Admin Cabang",
        EXPERT: "Pakar",
        MEMBER_COMPANY: "Member Company",
        MEMBER_WELDER: "Member Welder",
        GUEST: "Guest",
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
    },
    mutations: {
        setUser(state, user) {
            state.USER = user;
        },
    },
});

export default store;
