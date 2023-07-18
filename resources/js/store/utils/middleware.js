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
                            return;
                        }
                        next();
                        return;
                    });
                })
                .catch((err) => {});
        } else {
            user.roles.forEach((role) => {
                if (!allowRoles.includes(role.name)) {
                    next({ name: "Dashboard" });
                    return;
                }
                next();
                return;
            });
        }
    };
}

export function checkPermission(resource, action) {
    return function (to, from, next) {
        const permission = store.state.PERMISSION;

        if (!permission) {
            store.dispatch("postData", ["auth/check", {}]).then((response) => {
                if (
                    response.permission[resource] &&
                    response.permission[resource].includes(action)
                ) {
                    next();
                    return;
                }
                next({ name: "Dashboard" });
                return;
            });
        } else {
            if (permission[resource] && permission[resource].includes(action)) {
                next();
                return;
            }
            next({ name: "Dashboard" });
            return;
        }
    };
}
