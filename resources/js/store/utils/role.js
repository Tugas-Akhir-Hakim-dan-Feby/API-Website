export default {
    checkRoles(roles, data) {
        if (typeof data == "object") {
            return this.checkRoleObject(roles, data);
        } else {
            return this.checkRoleString(roles, data);
        }
    },
    checkRoleString(roles, data) {
        roles.filter((role) => {
            return role == data;
        });
    },
    checkRoleObject(roles, data) {
        for (const role in data) {
            if (Object.hasOwnProperty.call(data, role)) {
                return this.checkRoleString(roles, data[role]);
            }
        }
    },
};
