<script>
export default {
    data() {
        return {
            form: {
                name: "",
                email: "",
                skill: "",
                phone: "",
            },
            skills: [
                {
                    id: 1,
                    skillName: "Welding Engineer",
                },
                {
                    id: 2,
                    skillName: "Welding Inspector",
                },
                {
                    id: 3,
                    skillName: "Welding Specialist",
                },
                {
                    id: 4,
                    skillName: "Welding Destructive Testing",
                },
                {
                    id: 5,
                    skillName: "Welding Non-Destructive Testing",
                },
            ],
        };
    },
    methods: {
        handleSubmit() {
            this.$emit("onSuccess", true);
        },
    },
};
</script>
<template>
    <div class="mb-2">
        <label for="fullname" class="form-label">Nama Lengkap</label>
        <input class="form-control" type="text" id="fullname" />
    </div>

    <div class="mb-2">
        <label for="emailaddress" class="form-label">Email</label>
        <input class="form-control" type="email" id="emailaddress" />
    </div>

    <div class="mb-2">
        <label>Keahlian</label>
        <select name="" id="" class="form-select">
            <option value="">Pilih Keahlian</option>
            <option v-for="skill in skills" :value="skill.id">
                {{ skill.skillName }}
            </option>
        </select>
    </div>

    <div class="mb-2">
        <label for="emailaddress" class="form-label">Telepon</label>
        <input class="form-control" type="text" id="emailaddress" />
    </div>

    <div class="mb-2">
        <label for="">Alamat</label>
        <textarea name="" id="" class="form-control"></textarea>
    </div>

    <div class="mb-2 text-center">
        <button class="btn btn-primary form-control" @click="handleSubmit">
            Register
        </button>
    </div>
</template>
