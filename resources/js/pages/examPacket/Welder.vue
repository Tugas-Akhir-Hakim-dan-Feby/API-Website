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
    <PageTitle title="Uji Kompetensi" />

    <div class="card position-relative">
        <Loader v-if="isLoading" />
        <div class="card-body">
            <div
                class="d-md-flex d-block justify-content-between align-items-center mb-2"
            >
                <div class="text-center">
                    <router-link
                        :to="{ name: 'Exam Packet Create' }"
                        class="btn btn-sm btn-primary mb-2 me-3"
                        v-if="$can('create', 'Exampacket')"
                    >
                        Tambah Paket
                    </router-link>
                    <router-link
                        :to="{ name: 'Exam Packet Register' }"
                        class="btn btn-sm btn-primary mb-2 me-3"
                        v-if="$can('register-packet', 'Exampacket')"
                    >
                        Daftar Uji Kompetensi
                    </router-link>
                </div>

                <div
                    class="d-md-flex justify-content-between align-items-center"
                >
                    <Pagination
                        :pagination="metaPagination"
                        @onPageChange="onPageChange($event)"
                        v-if="$can('pagination', 'Exampacket')"
                    />
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Skema Uji Kompetensi</th>
                            <th>Jadwal Ujian</th>
                            <th>Tenggat Ujian</th>
                            <th>Status Validasi</th>
                            <th>Status</th>
                            <th v-if="isSchedule || isAfterSchedule">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(
                                welderHasExamPacket, index
                            ) in welderHasExamPackets"
                            :key="index"
                        >
                            <th v-html="iteration(index)"></th>
                            <td
                                v-html="
                                    welderHasExamPacket.examPacket
                                        ?.competenceSchema?.skillName
                                "
                            ></td>
                            <td
                                v-html="
                                    getSchedule(
                                        welderHasExamPacket.examPacket
                                            ?.examSchedule
                                    )
                                "
                            ></td>
                            <td
                                v-html="
                                    `${
                                        welderHasExamPacket.examPacket
                                            ?.startTime
                                    } - ${
                                        welderHasExamPacket.examPacket?.endTime
                                    } WIB (${getMinute(
                                        welderHasExamPacket.examPacket
                                            ?.startTime,
                                        welderHasExamPacket.examPacket?.endTime
                                    )} Menit)`
                                "
                            ></td>
                            <td>
                                <span
                                    class="badge bg-success"
                                    v-if="welderHasExamPacket.validatedAt"
                                    >VALIDASI</span
                                >
                                <span class="badge bg-warning" v-else
                                    >BELUM VALIDASI</span
                                >
                            </td>
                            <td>
                                <span
                                    class="badge bg-success"
                                    v-if="welderHasExamPacket.certificateNumber"
                                    >KOMPETEN</span
                                >
                                <span class="badge bg-warning" v-else
                                    >TIDAK KOMPETEN</span
                                >
                            </td>
                            <td
                                v-if="
                                    checkRoleWelderMember(
                                        $store.state.ADMIN_APP
                                    ) ||
                                    checkRoleWelderMember(
                                        $store.state.EXPERT
                                    ) ||
                                    checkRoleWelderMember(
                                        $store.state.ADMIN_HUB
                                    )
                                "
                            >
                                <router-link
                                    :to="{
                                        name: 'Exam Packet Detail',
                                        params: {
                                            id: welderHasExamPacket.examPacket
                                                ?.uuid,
                                        },
                                    }"
                                    v-if="$can('show', 'Exampacket')"
                                    class="btn btn-info btn-sm me-2 text-white"
                                >
                                    Detail
                                </router-link>
                                <button
                                    class="btn btn-danger btn-sm"
                                    @click="
                                        handleDelete(
                                            welderHasExamPacket.examPacket?.uuid
                                        )
                                    "
                                    v-if="$can('delete', 'Exampacket')"
                                >
                                    Hapus
                                </button>
                            </td>
                            <td v-else>
                                <a
                                    :href="`/attempt/${welderHasExamPacket.examPacket?.uuid}/execution/${welderHasExamPacket.examPacket?.exam?.uuid}`"
                                    @click.native="reloadPage"
                                    class="btn btn-primary btn-sm"
                                    v-if="
                                        checkSchedule(
                                            welderHasExamPacket.examPacket
                                                ?.examSchedule,
                                            [
                                                welderHasExamPacket.examPacket
                                                    ?.startTime,
                                                welderHasExamPacket.examPacket
                                                    ?.endTime,
                                            ]
                                        )
                                    "
                                    >Kerjakan</a
                                >
                                <router-link
                                    v-else-if="
                                        checkAfterSchedule(
                                            welderHasExamPacket.examPacket
                                                ?.examSchedule,
                                            welderHasExamPacket.examPacket
                                                ?.endTime
                                        ) ||
                                        welderHasExamPacket.status == 1 ||
                                        welderHasExamPacket.status == 3
                                    "
                                    :to="{
                                        name: 'Exam Packet Success',
                                        params: {
                                            examPacketId:
                                                welderHasExamPacket.examPacket
                                                    ?.uuid,
                                        },
                                    }"
                                    class="btn btn-sm btn-info"
                                    >Lihat Hasil</router-link
                                >
                                <span
                                    v-else
                                    class="badge bg-warning text-uppercase text-white"
                                    >Belum Mulai</span
                                >
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <Success :msg="msg" />
    <Confirm @onDelete="onDelete" />
</template>
