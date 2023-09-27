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
            return dayjs(date).locale("id").format("DD MMM YYYY");
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
            $("#confirmModal").modal("hide");
            this.$store
                .dispatch("deleteData", ["exam-packet", this.uuid])
                .then((response) => {
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
            <li class="breadcrumb-item active">Kumpulan Uji Kompetensi</li>
        </ol>
    </PageTitle>

    <div class="row position-relative">
        <Loader v-if="isLoading" />
        <div
            class="col-lg-4 col-md-4 col-sm-6"
            v-if="$can('create', 'Exampacket')"
        >
            <router-link :to="{ name: 'Exam Packet Create' }">
                <div class="card shadow bg-primary">
                    <div
                        class="card-body text-white h-100 d-flex flex-column justify-content-center align-items-center"
                    >
                        <i
                            class="mdi mdi-file-plus-outline"
                            style="font-size: 5rem"
                        ></i>
                        <p class="fw-bold fs-5">Tambah Uji Kompetensi</p>
                    </div>
                </div>
            </router-link>
        </div>
        <div
            class="col-lg-4 col-md-4 col-sm-6"
            v-for="(examPacket, index) in examPackets"
            :key="index"
        >
            <div class="card sahdow">
                <img
                    :src="
                        '/print/image/' +
                        getText(examPacket.competenceSchema?.skillName)
                    "
                    class="card-img-top"
                    alt="..."
                />
                <div class="card-body">
                    <h4 class="card-title">
                        {{ examPacket.competenceSchema?.skillName }}
                    </h4>
                    <p class="small m-0">
                        <span>
                            <i class="mdi mdi-calendar"></i>
                            {{ getSchedule(examPacket.closeSchedule) }}
                        </span>
                        |
                        <span>
                            <i class="mdi mdi-clock-outline"></i>
                            {{ examPacket.startTime }} -
                            {{ examPacket.endTime }} WIB
                        </span>
                        |
                        <i class="dripicons-user-group"></i>&nbsp;
                        <span class="badge bg-info">
                            {{ examPacket.examPacketHasWelders }}
                            Peserta</span
                        >
                        <span
                            v-if="
                                checkRole($store.state.ADMIN_APP) ||
                                checkRole($store.state.ADMIN_HUB)
                            "
                        >
                            | <i class="dripicons-location"></i>
                            {{ examPacket.operator?.tukName }}</span
                        >
                    </p>
                </div>
                <div class="card-footer d-flex justify-content-between">
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
                </div>
            </div>
        </div>
    </div>

    <Success :msg="msg" />
    <Confirm @onDelete="onDelete" />
</template>

<style scoped></style>
