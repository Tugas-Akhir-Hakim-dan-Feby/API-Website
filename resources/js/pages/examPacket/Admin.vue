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
            examPackets: [],
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
        };
    },
    mounted() {
        this.getExamPackets();
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
        getExamPackets() {
            this.isLoading = true;

            let params = [
                `per_page=${this.pagination.perPage}`,
                `page=${this.pagination.page}`,
                `search=${this.filters.search}`,
            ];

            params.push([`sort_direction=desc`]);

            this.$store
                .dispatch("getData", ["exam-packet", params.join("&")])
                .then((response) => {
                    this.isLoading = false;
                    this.examPackets = response.data;
                    this.metaPagination = response.meta;
                })
                .catch((error) => {
                    this.isLoading = false;
                });
        },
        getSchedule(date) {
            return dayjs(date).locale("id").format("dddd, DD MMMM YYYY");
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
                    this.getExamPackets();
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
            this.getExamPackets();
        },
        onDelete() {
            this.$store
                .dispatch("deleteData", ["exam-packet", this.uuid])
                .then((response) => {
                    $("#confirmModal").modal("hide");
                    $("#successModal").modal("show");
                    this.msg = "data berhasil dihapus.";
                    this.getExamPackets();
                })
                .catch((error) => {});
        },
        checkRole(role) {
            if (this.roles.includes(role)) {
                return true;
            }
            return false;
        },
        checkSchedule(schedule, timing) {
            let now = dayjs();
            schedule = dayjs(schedule).locale("id");

            let nowTime = now.hour() + ":" + now.minute();

            if (
                timing[1] <= nowTime &&
                nowTime >= timing[0] &&
                now.isSame(schedule, "day")
            ) {
                return true;
            }

            return false;
        },
    },
    components: { PageTitle, Success, Pagination, Confirm, Loader },
};
</script>

<template>
    <PageTitle title="Kumpulan Uji Kompetensi">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item">
                <router-link :to="{ name: 'Dashboard' }">Dashboard</router-link>
            </li>
            <li class="breadcrumb-item active">
                Kumpulan Paket Uji Kompetensi
            </li>
        </ol>
    </PageTitle>

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
                            <th
                                v-if="
                                    checkRole($store.state.ADMIN_APP) ||
                                    checkRole($store.state.ADMIN_HUB)
                                "
                            >
                                Penyelenggara
                            </th>
                            <th>Skema Kompetensi</th>
                            <th>Jadwal Ujian</th>
                            <th>
                                Jadwal Penutupan <br />
                                Pendaftaran
                            </th>
                            <th>Tenggat Ujian</th>
                            <th>Jumlah Peserta</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="examPackets.length < 1">
                            <td
                                :colspan="
                                    checkRole($store.state.ADMIN_APP) ||
                                    checkRole($store.state.ADMIN_HUB)
                                        ? 8
                                        : 7
                                "
                                class="text-center"
                            >
                                data paket uji kompetensi tidak ada
                            </td>
                        </tr>
                        <tr
                            v-else
                            v-for="(examPacket, index) in examPackets"
                            :key="index"
                        >
                            <th v-html="iteration(index)"></th>
                            <td
                                v-if="
                                    checkRole($store.state.ADMIN_APP) ||
                                    checkRole($store.state.ADMIN_HUB)
                                "
                                v-html="examPacket.operator?.tukName"
                            ></td>
                            <td
                                v-html="examPacket.competenceSchema?.skillName"
                            ></td>
                            <td
                                v-html="getSchedule(examPacket.examSchedule)"
                            ></td>
                            <td
                                v-html="getSchedule(examPacket.closeSchedule)"
                            ></td>
                            <td
                                v-html="
                                    `${examPacket.startTime} - ${
                                        examPacket.endTime
                                    } WIB <br> (${getMinute(
                                        examPacket.startTime,
                                        examPacket.endTime
                                    )} Menit)`
                                "
                            ></td>
                            <td>
                                <router-link
                                    :to="{
                                        name: 'Exam Packet Participant',
                                        params: { id: examPacket.uuid },
                                    }"
                                    class="badge btn-success"
                                >
                                    {{ examPacket.examPacketHasWelders }}
                                    Peserta
                                </router-link>
                            </td>
                            <td>
                                <router-link
                                    :to="{
                                        name: 'Exam Packet Detail',
                                        params: { id: examPacket.uuid },
                                    }"
                                    v-if="$can('show', 'Exampacket')"
                                    class="btn btn-info btn-sm me-2 text-white"
                                >
                                    Detail
                                </router-link>
                                <button
                                    class="btn btn-danger btn-sm"
                                    @click="handleDelete(examPacket.uuid)"
                                    v-if="$can('delete', 'Exampacket')"
                                >
                                    Hapus
                                </button>
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
