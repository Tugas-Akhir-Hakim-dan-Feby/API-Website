import { AbilityBuilder, Ability, defineAbility } from "@casl/ability";
import Cookie from "js-cookie";
import { defaultsDeep } from "lodash";

export default defineAbility((can) => {
    if (Cookie.get("token") !== undefined) {
        var permission = "";
        for (var prop in permission) {
            if (permission.hasOwnProperty(prop)) {
                can(
                    permission[prop],
                    prop.charAt(0).toUpperCase() + prop.slice(1)
                );
            }
        }
    }
});
