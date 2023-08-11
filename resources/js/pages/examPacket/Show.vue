<script>
import dayjs from "dayjs";
import "dayjs/locale/id";
import Success from "../../components/notifications/Success.vue";

export default {
    props: {
        examPacket: {
            default: {},
            type: Object,
        },
        edit: {
            default: false,
            type: Boolean,
        },
        isShowParticipant: {
            default: false,
            type: Boolean,
        },
    },
    data() {
        return {
            roles: [],
            errors: {},
            welderSkills: [],
            isLoading: false,
            isEdit: false,
            schedule: null,
            documentCertificate: null,
        };
    },
    mounted() {
        this.schedule = this.date;
        this.getUser();
        this.getWelderSkills();
    },
    methods: {
        getUser() {
            this.$store
                .dispatch("showData", ["user", "me"])
                .then((response) => {
                    this.roles = response.roles;
                })
                .catch((err) => {});
        },
        getWelderSkills() {
            this.$store
                .dispatch("getData", ["skill/welder", ""])
                .then((response) => {
                    this.welderSkills = response.data;
                });
        },
        getSchedule(date) {
            return dayjs(date).locale("id").format("DD MMMM YYYY");
        },
        getMinute(startTime, endTime) {
            if (startTime) {
                const [startHours, startMinutes] = startTime.split(":");
                const [endHours, endMinutes] = endTime.split(":");

                const startDate = new Date();
                startDate.setHours(startHours);
                startDate.setMinutes(startMinutes);

                const endDate = new Date();
                endDate.setHours(endHours);
                endDate.setMinutes(endMinutes);

                let diff =
                    (endDate.getTime() - startDate.getTime()) / 1000 / 60;
                if (diff < 0) {
                    diff += 1440;
                }

                return diff;
            }
            return;
        },
        checkRole(role) {
            if (this.roles.includes(role)) {
                return true;
            }
            return false;
        },
        formatDate(date) {
            return dayjs(date).locale("id").format("YYYY-MM-DD");
        },
        uploadDocumentCertificate(e) {
            this.documentCertificate = e.target.files[0];
        },
        handleSubmit() {
            this.isLoading = true;

            const formData = new FormData();

            if (this.documentCertificate) {
                formData.append(
                    "document_certificate",
                    this.documentCertificate
                );
            }
            formData.append("_method", "put");

            this.$store
                .dispatch("postDataUpload", [
                    "exam-packet/update-certificate/" + this.examPacket.uuid,
                    formData,
                ])
                .then((response) => {
                    this.isLoading = false;
                    this.documentCertificate = null;
                    $("#updateCertificate").modal("hide");
                    $("#successModal").modal("show");
                })
                .catch((error) => {
                    this.isLoading = false;
                    this.errors = error.response.data.messages;
                });
        },
    },
    components: { Success },
};
</script>
<template>
    <div class="row">
        <div class="col-sm-12">
            <div class="card bg-primary">
                <div class="card-body profile-user-box">
                    <div class="row">
                        <div class="col-sm-7">
                            <div class="row align-items-center">
                                <div class="col">
                                    <div>
                                        <h4 class="mt-1 mb-1 text-white">
                                            {{
                                                examPacket.competenceSchema
                                                    ?.skillName
                                            }}
                                        </h4>
                                        <p class="font-13 text-white-50">
                                            Skema Ujian
                                        </p>
                                        <h4 class="mt-1 mb-1 text-white">
                                            {{ examPacket.operator?.tukName }}
                                        </h4>
                                        <p class="font-13 text-white-50">
                                            Penyelenggara
                                        </p>

                                        <ul class="mb-0 list-inline text-light">
                                            <li class="list-inline-item me-3">
                                                <h5 class="mb-1 text-white">
                                                    {{
                                                        getSchedule(
                                                            examPacket.examSchedule
                                                        )
                                                    }}
                                                </h5>
                                                <p
                                                    class="mb-0 font-13 text-white-50"
                                                >
                                                    Jadwal Ujian
                                                </p>
                                            </li>
                                            <li class="list-inline-item">
                                                <h5 class="mb-1 text-white">
                                                    {{
                                                        `${
                                                            examPacket.startTime
                                                        } - ${
                                                            examPacket.endTime
                                                        } WIB (${getMinute(
                                                            examPacket.startTime,
                                                            examPacket.endTime
                                                        )} Menit)`
                                                    }}
                                                </h5>
                                                <p
                                                    class="mb-0 font-13 text-white-50"
                                                >
                                                    Tenggat Pengerjaan
                                                </p>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-5">
                            <div class="text-center mt-sm-0 mt-3 text-sm-end">
                                <router-link
                                    class="btn btn-sm btn-light me-2 mb-2"
                                    :to="{
                                        name: 'Exam Packet Participant',
                                        params: { id: examPacket.uuid },
                                    }"
                                    v-if="
                                        (examPacket.user?.name ==
                                            $store.state.USER.uuid ||
                                            checkRole($store.state.ADMIN_APP) ||
                                            checkRole($store.state.ADMIN_HUB) ||
                                            checkRole($store.state.OPERATOR)) &&
                                        isShowParticipant
                                    "
                                >
                                    Lihat Peserta
                                </router-link>
                                <router-link
                                    :to="{
                                        name: 'Exam Packet Edit',
                                        params: { id: examPacket.uuid },
                                    }"
                                    class="btn btn-sm btn-light me-2 mb-2"
                                    v-if="$can('update', 'Exampacket') && edit"
                                >
                                    Edit Paket
                                </router-link>
                                <a
                                    href="#"
                                    data-bs-toggle="modal"
                                    data-bs-target="#updateCertificate"
                                    class="btn btn-sm btn-light mb-2"
                                    v-if="$can('update', 'Exampacket') && edit"
                                >
                                    Edit Sertifikat
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div
        class="modal fade"
        id="updateCertificate"
        tabindex="-1"
        aria-labelledby="updateCertificateLabel"
        aria-hidden="true"
        data-bs-backdrop="static"
        data-bs-keyboard="false"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateCertificateLabel">
                        perbaharui sertifikat
                    </h5>
                </div>
                <form method="post" @submit.prevent="handleSubmit">
                    <div class="modal-body">
                        <label for="">Unggah Sertifikat</label>
                        <input
                            type="file"
                            class="form-control form-validation"
                            id=""
                            :class="{
                                'is-invalid': errors.documentCertificate,
                            }"
                            :disabled="isLoading"
                            @change="uploadDocumentCertificate"
                        />
                        <small
                            >unduh contoh penulisan
                            <a href="/assets/files/example-certificate.docx"
                                >sertifikat</a
                            ></small
                        >.
                        <div
                            class="invalid-feedback"
                            v-if="errors.documentCertificate"
                            v-for="(error, index) in errors.documentCertificate"
                            :key="index"
                        >
                            {{ error }}
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-sm btn-secondary"
                            data-bs-dismiss="modal"
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

    <Success :msg="'data berhasil disimpan.'" />
</template>
