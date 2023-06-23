import { AnswerQuestion } from "./constants";

export default {
    convertStringToBoolean(data) {
        switch (data) {
            case "true":
            case "yes":
            case "1":
                return true;

            case "false":
            case "no":
            case "0":
            case null:
            case undefined:
                return false;

            default:
                return JSON.parse(data);
        }
    },
    getGender(gender) {
        if (gender == "L") {
            return "Laki-laki";
        }

        return "Perempuan";
    },
    getRupiah(amount) {
        const formatter = new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR",
            minimumFractionDigits: 0,
        });

        return formatter.format(amount ?? 0);
    },
    getAnswerQuestionData(type) {
        if (type == AnswerQuestion.MULTIPLE_CHOICE) {
            return "Pilihan Ganda";
        }

        return "Benar atau Salah";
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

    randomString(length) {
        let result = "";
        const characters =
            "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        const charactersLength = characters.length;
        let counter = 0;
        while (counter < length) {
            result += characters.charAt(
                Math.floor(Math.random() * charactersLength)
            );
            counter += 1;
        }
        return result;
    },
};
