import store from "../../store";

export function checkRoles(allowRoles) {
    return function (to, from, next) {
        const user = store.state.USER;

        if (!user) {
            store
                .dispatch("showData", ["user", "me"])
                .then((response) => {
                    response.user.roles.forEach((role) => {
                        if (!allowRoles.includes(role.name)) {
                            next({ name: "Dashboard" });
                        }
                        next();
                    });
                })
                .catch((err) => {});
        } else {
            user.roles.forEach((role) => {
                if (!allowRoles.includes(role.name)) {
                    next({ name: "Dashboard" });
                }
            });
            next();
        }
    };
}
