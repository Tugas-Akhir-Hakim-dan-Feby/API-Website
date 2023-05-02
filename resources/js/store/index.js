import { createStore } from "vuex";
import action from "./services/action";
import ticket from "./modules/ticket";

const store = createStore({
    modules: {
        action,
        ticket,
    },
    state: {
        ADMIN_APP: "Admin App",
        ADMIN_HUB: "Admin Pusat",
        ADMIN_BRANCH: "Admin Cabang",
        EXPERT: "Pakar",
        MEMBER_COMPANY: "Member Company",
        MEMBER_WELDER: "Member Welder",
        GUEST: "Guest",
        PAID: "PAID",
        PENDING: "PENDING",
    },
});

export default store;
