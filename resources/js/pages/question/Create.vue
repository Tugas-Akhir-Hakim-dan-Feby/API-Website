<script>
import Success from "../../components/notifications/Success.vue";
import PageTitle from "../../components/PageTitle.vue";
import MultipleChoice from "./components/MultipleChoice.vue";
import TrueFalse from "./components/TrueFalse.vue";

export default {
    data() {
        return {
            msg: "",
            questionType: "",
        };
    },
    methods: {
        handleSubmit() {
            $("#successModal").modal("show");
            this.msg = "data berhasil ditambahkan.";
        },
    },
    components: { PageTitle, MultipleChoice, TrueFalse, Success },
};
</script>

<template>
    <div class="container-fluid">
        <PageTitle :title="'Kumpulan Pertanyaan'" />

        <form @submit.prevent="handleSubmit">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <label for="">Pertanyaan</label>
                        <QuillEditor
                            theme="snow"
                            toolbar="full"
                            style="height: 300px"
                        />
                    </div>
                    <div class="mb-3">
                        <label for="">Tipe Pertanyaan</label>
                        <select
                            name=""
                            id=""
                            class="form-select"
                            v-model="questionType"
                        >
                            <option value=""></option>
                            <option value="1">Pilihan Ganda</option>
                            <option value="2">Pilihan Benar Salah</option>
                        </select>
                    </div>
                    <MultipleChoice v-if="questionType == 1" />
                    <TrueFalse v-if="questionType == 2" />
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <router-link
                        :to="{ name: 'Question' }"
                        class="btn btn-secondary"
                        >Batal</router-link
                    >
                    <button class="btn btn-success">Simpan</button>
                </div>
            </div>
        </form>
    </div>

    <Success :url="{ name: 'Question' }" :msg="msg" />
</template>
