import { AbilityBuilder, Ability, defineAbility } from "@casl/ability";
import Cookie from "js-cookie";
import { defaultsDeep } from "lodash";

export default defineAbility((can) => {
    var roles = "";
    for (var prop in roles) {
        if (roles.hasOwnProperty(prop)) {
            can(roles[prop], "role");
        }
    }
});
