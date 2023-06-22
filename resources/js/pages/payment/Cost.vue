<script>
import PageTitle from "../../components/PageTitle.vue";
export default { components: { PageTitle, Pagination } };
import Pagination from "../../components/Pagination.vue";
</script>
<template>
    <PageTitle :title="'Data Harga'" />

    <div class="card">
        <div class="card-body">
            <div
                class="d-md-flex d-block justify-content-between align-items-center mb-2"
            >
                <div class="text-center">
                    <button
                        class="btn btn-sm btn-primary mb-2"
                        data-bs-toggle="modal"
                        data-bs-target="#addPaymentCost"
                    >
                        Tambah Harga
                    </button>
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
                            <th>No.</th>
                            <th>Deskripsi</th>
                            <th>Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th v-html="'iteration(index)'"></th>
                            <td v-html="'skill.skillName'"></td>
                            <td v-html="'skill.skillDescription'"></td>
                            <td>
                                <button
                                    class="btn btn-warning text-white btn-sm me-2"
                                >
                                    Edit
                                </button>
                                <button class="btn btn-danger btn-sm">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div
        class="modal fade"
        id="addPaymentCost"
        tabindex="-1"
        aria-labelledby="addPaymentCostLabel"
        aria-hidden="true"
        data-bs-backdrop="static"
        data-bs-keyboard="false"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPaymentCostLabel">
                        Tambah Data Harga
                    </h5>
                </div>
                <form @submit.prevent="handleSubmit" method="post">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Deskripsi</label>
                            <input type="text" class="form-control" />
                        </div>
                        <div class="mb-3">
                            <label>Harga</label>
                            <input type="text" class="form-control" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-sm btn-secondary me-2"
                            data-bs-dismiss="modal"
                            :disabled="isLoading"
                        >
                            Batal
                        </button>
                        <button
                            class="btn btn-sm btn-primary"
                            v-if="!isLoading"
                        >
                            Kirim
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
                </form>
            </div>
        </div>
    </div>
</template>
