<script>
import PageTitle from "../../components/PageTitle.vue";
import Pagination from "../../components/Pagination.vue";
import Success from "../../components/notifications/Success.vue";
import Confirm from "../../components/notifications/Confirm.vue";
import Loader from "../../components/Loader.vue";
import PaginationUtil from "../../store/utils/pagination";
import dayjs from "dayjs";
import "dayjs/locale/id";

export default {
    data() {
        return {
            welderHasExamPackets: [],
            errors: {},
            pagination: {
                perPage: 10,
                page: 1,
            },
            filters: {
                search: "",
            },
            metaPagination: {},
            roles: [],
            msg: "",
            uuid: null,
            isLoading: false,
            isError: false,
            isSchedule: false,
            isAfterSchedule: false,
        };
    },
    mounted() {
        this.getwelderHasExamPackets();
        this.getUser();
    },
    methods: {
        iteration(index) {
            return PaginationUtil.iteration(index, this.metaPagination);
        },
        getUser() {
            this.$store
                .dispatch("showData", ["user", "me"])
                .then((response) => {
                    this.roles = response.roles;
                })
                .catch((err) => {});
        },
        getwelderHasExamPackets() {
            this.isLoading = true;

            let params = [
                `per_page=${this.pagination.perPage}`,
                `page=${this.pagination.page}`,
                `search=${this.filters.search}`,
            ];

            params.push([`sort_direction=desc`]);

            this.$store
                .dispatch("getData", ["user-exam-packet", params.join("&")])
                .then((response) => {
                    this.isLoading = false;
                    this.welderHasExamPackets = response.data;
                    this.metaPagination = response.meta;
                })
                .catch((error) => {
                    this.isLoading = false;
                });
        },
        getSchedule(date) {
            return dayjs(date).locale("id").format("DD MMMM YYYY");
        },
        getMinute(startTime, endTime) {
            const [startHours, startMinutes] = startTime.split(":");
            const [endHours, endMinutes] = endTime.split(":");

            const startDate = new Date();
            startDate.setHours(startHours);
            startDate.setMinutes(startMinutes);

            const endDate = new Date();
            endDate.setHours(endHours);
            endDate.setMinutes(endMinutes);

            let diff = (endDate.getTime() - startDate.getTime()) / 1000 / 60;
            if (diff < 0) {
                diff += 1440;
            }

            return diff.toFixed(0);
        },
        getText(text) {
            return text.replace(/\//g, " ");
        },
        handleDelete(uuid) {
            this.uuid = uuid;
            $("#confirmModal").modal("show");
        },
        onUpdateStatus(id, status) {
            this.isLoading = true;
            this.errors = {};
            this.$store
                .dispatch("updateData", ["exam-packet/update-status", id, {}])
                .then((response) => {
                    this.isLoading = false;
                    this.msg = `data berhasil diperbaharui.`;
                    this.getwelderHasExamPackets();
                    $("#successModal").modal("show");
                })
                .catch((error) => {
                    this.isLoading = false;

                    if (
                        error.response.data.status == "ERROR" &&
                        error.response.data.statusCode == 500
                    ) {
                        this.isError = true;
                        this.msg = error.response.data.message;
                    }
                });
        },
        onPageChange(e) {
            this.pagination.page = e;
            this.getwelderHasExamPackets();
        },
        onDelete() {
            this.$store
                .dispatch("deleteData", ["exam-packet", this.uuid])
                .then((response) => {
                    $("#confirmModal").modal("hide");
                    $("#successModal").modal("show");
                    this.msg = "data berhasil dihapus.";
                    this.getwelderHasExamPackets();
                })
                .catch((error) => {});
        },
        checkRoleWelderMember(role) {
            if (this.roles.includes(role)) {
                return true;
            }
            return false;
        },
        checkSchedule(schedule, timing) {
            let now = dayjs();
            schedule = dayjs(schedule).locale("id");

            let nowTime = now.format("HH:mm");
            let startTime = timing[0];
            let endTime = timing[1];

            if (
                nowTime >= startTime &&
                nowTime <= endTime &&
                now.isSame(schedule, "day")
            ) {
                this.isSchedule = true;
                return true;
            }

            this.isSchedule = false;
            return false;
        },
        checkAfterSchedule(schedule) {
            let now = dayjs();
            schedule = dayjs(schedule).locale("id");

            if (schedule.isBefore(now)) {
                this.isAfterSchedule = true;
                return true;
            }

            this.isAfterSchedule = false;
            return false;
        },
    },
    components: { PageTitle, Success, Pagination, Confirm, Loader },
};
</script>

<template>
    <PageTitle title="Uji Kompetensi">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item">
                <router-link :to="{ name: 'Dashboard' }">Dashboard</router-link>
            </li>
            <li class="breadcrumb-item active">Uji Kompetensi</li>
        </ol>
    </PageTitle>

    <div class="row position-relative">
        <Loader v-if="isLoading" />
        <div class="col-lg-4 col-md-4 col-sm-6">
            <router-link :to="{ name: 'Exam Packet Register' }">
                <div class="card shadow bg-primary">
                    <div
                        class="card-body text-white h-100 d-flex flex-column justify-content-center align-items-center"
                    >
                        <i
                            class="mdi mdi-file-plus-outline"
                            style="font-size: 5rem"
                        ></i>
                        <p class="fw-bold fs-5">Daftar Uji Kompetensi</p>
                    </div>
                </div>
            </router-link>
        </div>
        <div
            class="col-lg-4 col-md-4 col-sm-6"
            v-for="(welderHasExamPacket, index) in welderHasExamPackets"
            :key="index"
        >
            <div class="card shadow-lg position-relative">
                <div class="position-absolute top-0 end-0">
                    <span
                        class="badge bg-success"
                        v-if="welderHasExamPacket.validatedAt"
                        >VALIDASI</span
                    >
                    <span class="badge bg-warning" v-else>BELUM VALIDASI</span>
                </div>
                <img
                    :src="
                        '/print/image/' +
                        getText(
                            welderHasExamPacket.examPacket?.competenceSchema
                                ?.skillName
                        )
                    "
                    class="card-img-top"
                    :alt="
                        welderHasExamPacket.examPacket?.competenceSchema
                            ?.skillName
                    "
                />
                <div class="card-body">
                    <h4 class="card-title fw-bold" style="font-size: 18px">
                        {{
                            welderHasExamPacket.examPacket?.competenceSchema
                                ?.skillName
                        }}
                    </h4>
                    <h5 class="fw-semibold" style="font-size: 14px">
                        {{ welderHasExamPacket.examPacket?.operator?.tukName }}
                    </h5>
                    <table class="table mb-0" style="font-size: 12px">
                        <tr>
                            <td>Penutupan Pendaftaran</td>
                            <td>:</td>
                            <td>
                                {{
                                    getSchedule(
                                        welderHasExamPacket.examPacket
                                            ?.closeSchedule
                                    )
                                }}
                            </td>
                        </tr>
                        <tr>
                            <td>Jadwal Uji Kompetensi</td>
                            <td>:</td>
                            <td>
                                {{
                                    getSchedule(
                                        welderHasExamPacket.examPacket
                                            ?.examSchedule
                                    )
                                }}
                            </td>
                        </tr>
                    </table>
                    <p class="card-text"></p>
                </div>
                <div class="card-footer">
                    <a
                        :href="`/attempt/${welderHasExamPacket.examPacket?.uuid}/execution/${welderHasExamPacket.examPacket?.exam?.uuid}`"
                        @click.native="reloadPage"
                        class="btn btn-primary btn-sm"
                        v-if="
                            checkSchedule(
                                welderHasExamPacket.examPacket?.examSchedule,
                                [
                                    welderHasExamPacket.examPacket?.startTime,
                                    welderHasExamPacket.examPacket?.endTime,
                                ]
                            ) && !welderHasExamPacket.finishedAt
                        "
                        >Kerjakan</a
                    >
                    <router-link
                        v-else-if="
                            checkAfterSchedule(
                                welderHasExamPacket.examPacket?.examSchedule,
                                welderHasExamPacket.examPacket?.endTime
                            ) && welderHasExamPacket.finishedAt
                        "
                        :to="{
                            name: 'Exam Packet Success',
                            params: {
                                examPacketId:
                                    welderHasExamPacket.examPacket?.uuid,
                            },
                        }"
                        class="btn btn-sm btn-info"
                        >Lihat Hasil</router-link
                    >
                    <span
                        v-else
                        class="btn btn-sm btn-warning text-uppercase text-white"
                        style="cursor: default"
                        >Belum Mulai</span
                    >
                </div>
            </div>
        </div>
    </div>

    <Success :msg="msg" />
    <Confirm @onDelete="onDelete" />
</template>
