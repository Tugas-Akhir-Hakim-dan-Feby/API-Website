<script>
import Confirm from "../../components/notifications/Confirm.vue";
import Success from "../../components/notifications/Success.vue";
import PageTitle from "../../components/PageTitle.vue";
import Pagination from "../../components/Pagination.vue";

export default {
    data() {
        return {
            msg: "",
            questionSelected: [],
            questions: [
                {
                    id: 1,
                    question: "Pertanyaan 1",
                    questionType: "Tipe Pilhan Ganda",
                    questionYear: "2021",
                    questionStatus: 1,
                },
                {
                    id: 2,
                    question: "Pertanyaan 2",
                    questionType: "Tipe Pilhan Benar Salah",
                    questionYear: "2021",
                    questionStatus: 1,
                },
                {
                    id: 3,
                    question: "Pertanyaan 3",
                    questionType: "Tipe Pilhan Ganda",
                    questionYear: "2021",
                    questionStatus: 1,
                },
            ],
        };
    },
    methods: {
        handleDelete() {
            $("#confirmModal").modal("show");
        },
        onDelete() {
            $("#confirmModal").modal("hide");
            $("#successModal").modal("show");
            this.questionSelected = [];
            this.msg = "data berhasil dihapus.";
        },
        onUpdateStatus(id, status) {
            $("#successModal").modal("show");
            this.msg = `status berhasil diperbaharui.`;
        },
    },
    components: { PageTitle, Pagination, Success, Confirm },
};
</script>

<template>
    <PageTitle :title="'Kumpulan Pertanyaan'" />

    <div class="card">
        <div class="card-body">
            <div
                class="d-md-flex d-block justify-content-between align-items-center mb-2"
            >
                <div class="text-center">
                    <router-link
                        :to="{ name: 'Question Create' }"
                        class="btn btn-primary mb-2 me-3"
                    >
                        Tambah Pertanyaan
                    </router-link>
                    <span v-show="questionSelected.length > 0">
                        <button
                            class="btn btn-danger me-3 mb-2"
                            @click="handleDelete"
                        >
                            Delete
                        </button>
                    </span>
                </div>

                <div
                    class="d-md-flex justify-content-between align-items-center"
                >
                    <div class="me-md-2 me-0">
                        <input
                            type="search"
                            class="form-control"
                            placeholder="pencarian"
                        />
                    </div>

                    <Pagination />
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Pertanyaan</th>
                            <th>Tipe Pertanyaan</th>
                            <th>Tahun Pertanyaan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(question, index) in questions" :key="index">
                            <td>
                                <input
                                    type="checkbox"
                                    class="form-check-input"
                                    style="cursor: pointer"
                                    v-model="questionSelected"
                                    :value="question.id"
                                />
                            </td>
                            <td v-html="question.question"></td>
                            <td v-html="question.questionType"></td>
                            <td v-html="question.questionYear"></td>
                            <td>
                                <div class="form-check form-switch">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        role="switch"
                                        :checked="question.questionStatus"
                                        @click="
                                            onUpdateStatus(
                                                question.id,
                                                question.questionStatus
                                            )
                                        "
                                    />
                                </div>
                            </td>
                            <td>
                                <router-link
                                    :to="{
                                        name: 'Question Edit',
                                        params: { id: question.id },
                                    }"
                                    class="btn btn-sm btn-warning"
                                >
                                    Edit
                                </router-link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <Success :url="{ name: 'Question' }" :msg="msg" />
    <Confirm @onDelete="onDelete" />
</template>
