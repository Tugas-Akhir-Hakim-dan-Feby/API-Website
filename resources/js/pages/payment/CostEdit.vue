<script>
import PageTitle from "../../components/PageTitle.vue";
import Loader from "../../components/Loader.vue";
import Pagination from "../../components/Pagination.vue";
import util from "../../store/utils/util";
import Success from "../../components/notifications/Success.vue";
import Confirm from "../../components/notifications/Confirm.vue";

export default {
    props: ["id"],
    data() {
        return {
            benefitId: null,
            msg: "",
            cost: {},
            cost: {},
            errors: {},
            form: {
                nominalPrice: "",
            },
            description: "",
            isLoading: false,
            isLoadingBenefit: false,
        };
    },
    mounted() {
        this.getCost();
    },
    methods: {
        getCost() {
            this.isLoading = true;
            this.$store
                .dispatch("showData", ["cost", this.id])
                .then((response) => {
                    this.isLoading = false;
                    this.cost = response.data;
                })
                .catch((error) => {
                    this.isLoading = false;
                });
        },
        getRupiah(amount) {
            return util.getRupiah(amount);
        },
        handleSubmit() {
            this.errors = {};
            this.isLoading = true;
            this.$store
                .dispatch("updateData", ["cost", this.cost.uuid, this.cost])
                .then((response) => {
                    this.msg = "data harga berhasil diperbaharui.";
                    this.isLoading = false;
                    this.getCost();
                    $("#addBenefit").modal("hide");
                    $("#successModal").modal("show");
                })
                .catch((error) => {
                    this.isLoading = false;
                    this.errors = error.response.data.messages;
                });
        },
        handleAddBenefit() {
            this.errors = {};
            this.isLoadingBenefit = true;

            let form = {
                description: this.description,
                costId: this.id,
            };

            this.$store
                .dispatch("postData", ["cost-benefit", form])
                .then((response) => {
                    this.msg = "data benefit berhasil disimpan.";
                    this.isLoadingBenefit = false;
                    this.getCost();
                    $("#addBenefit").modal("hide");
                    $("#successModal").modal("show");
                })
                .catch((error) => {
                    this.isLoadingBenefit = false;
                    this.errors = error.response.data.message;
                });
        },
        handleDelete(id) {
            this.benefitId = id;
            $("#confirmModal").modal("show");
        },
        onBack() {
            this.$router.push({ name: "Payment Cost" });
        },
        onDelete() {
            this.isLoading = true;
            this.isLoadingBenefit = true;
            $("#confirmModal").modal("hide");
            this.$store
                .dispatch("deleteData", ["cost-benefit", this.benefitId])
                .then((response) => {
                    this.isLoading = false;
                    this.isLoadingBenefit = false;
                    this.msg = `data berhasil dihapus.`;
                    this.getCost();
                    $("#successModal").modal("show");
                })
                .catch((error) => {
                    this.isLoading = false;
                    this.isLoadingBenefit = false;

                    if (
                        error.response.data.status == "ERROR" &&
                        error.response.data.statusCode == 500
                    ) {
                        this.isError = true;
                        this.msg = error.response.data.message;
                    }
                });
        },
    },
    components: { PageTitle, Pagination, Loader, Success, Confirm },
};
</script>
<template>
    <PageTitle :title="'Edit Harga'" :isBack="true" @onBack="onBack">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item">
                <router-link :to="{ name: 'Dashboard' }">Dashboard</router-link>
            </li>
            <li class="breadcrumb-item">
                <router-link :to="{ name: 'Payment Cost' }"
                    >Data Harga</router-link
                >
            </li>
            <li class="breadcrumb-item active">Edit Harga</li>
        </ol>
    </PageTitle>

    <div class="row position-relative">
        <Loader v-if="isLoading" />
        <div class="col-lg-6 col-md-6">
            <form @submit.prevent="handleSubmit">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label>Jenis Harga</label>
                            <input
                                type="text"
                                class="form-control"
                                disabled
                                :value="cost.typePrice"
                            />
                        </div>
                        <div class="mb-3">
                            <label>Nominal Harga</label>
                            <input
                                type="number"
                                class="form-control"
                                v-model="cost.nominalPrice"
                                :disabled="isLoading"
                                :class="{ 'is-invalid': errors.nominalPrice }"
                            />
                            <div
                                class="invalid-feedback"
                                v-if="errors.nominalPrice"
                                v-for="(error, index) in errors.nominalPrice"
                                :key="index"
                            >
                                {{ error }}
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button
                            class="btn btn-sm btn-primary"
                            v-if="!isLoading"
                        >
                            Simpan
                        </button>
                        <button
                            class="btn btn-sm btn-primary"
                            type="button"
                            disabled
                            v-if="isLoading"
                        >
                            <span
                                class="spinner-border spinner-border-sm me-1"
                                role="status"
                                aria-hidden="true"
                            ></span>
                            Harap Tunggu...
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="col">
            <div class="card">
                <div
                    class="card-header d-flex justify-content-between align-items-center"
                >
                    <h5 class="card-title m-0">Benefit</h5>
                    <button
                        class="btn btn-sm btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#addBenefit"
                    >
                        Tambah Benefit
                    </button>
                </div>
                <div class="card-body">
                    <ul>
                        <li
                            v-for="(benefit, index) in cost.benefits"
                            class="mb-2"
                        >
                            <div
                                class="d-flex justify-content-between align-items-center"
                            >
                                <span>{{ benefit.description }}</span>
                                <button
                                    class="btn btn-sm btn-danger"
                                    @click="handleDelete(benefit.id)"
                                >
                                    <i class="mdi mdi-trash-can-outline"></i>
                                </button>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div
        class="modal fade"
        id="addBenefit"
        tabindex="-1"
        aria-labelledby="addBenefitLabel"
        aria-hidden="true"
        data-bs-backdrop="static"
        data-bs-keyboard="false"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addBenefitLabel">
                        Tambah Benefit Baru
                    </h5>
                </div>
                <form @submit.prevent="handleAddBenefit" method="post">
                    <div class="modal-body">
                        <label>Deskripsi</label>
                        <input
                            type="text"
                            class="form-control"
                            :disabled="isLoadingBenefit"
                            :class="{ 'is-invalid': errors.description }"
                            v-model="description"
                        />
                        <div
                            class="invalid-feedback"
                            v-if="errors.description"
                            v-for="(error, index) in errors.description"
                            :key="index"
                        >
                            {{ error }}
                        </div>
                    </div>
                    <div
                        class="modal-footer d-flex justify-content-between align-items-center"
                    >
                        <button
                            type="button"
                            class="btn btn-sm btn-secondary"
                            data-bs-dismiss="modal"
                        >
                            Batal
                        </button>
                        <button
                            class="btn btn-sm btn-primary"
                            v-if="!isLoadingBenefit"
                        >
                            Simpan
                        </button>
                        <button
                            class="btn btn-sm btn-primary"
                            type="button"
                            disabled
                            v-if="isLoadingBenefit"
                        >
                            <span
                                class="spinner-border spinner-border-sm me-1"
                                role="status"
                                aria-hidden="true"
                            ></span>
                            Harap Tunggu...
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <Success :msg="msg" />
    <Confirm @onDelete="onDelete" />
</template>
