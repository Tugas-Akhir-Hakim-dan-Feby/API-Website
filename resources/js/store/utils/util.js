export default {
    getGender(gender) {
        if (gender == "L") {
            return "Laki-laki";
        }

        return "Perempuan";
    },
    removeInvalidClass() {
        $("body").on("keyup", ".form-validation", function () {
            let className = $(this).attr("class").split(" ");
            let invalidClass = className.filter(
                (name) => name === "is-invalid"
            );
            if (invalidClass) {
                $(this).removeClass("is-invalid");
            }
        });

        $("body").on("change", ".form-validation", function () {
            let className = $(this).attr("class").split(" ");
            let invalidClass = className.filter(
                (name) => name === "is-invalid"
            );
            if (invalidClass) {
                $(this).removeClass("is-invalid");
            }
        });
    },
};
