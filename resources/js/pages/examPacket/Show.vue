<script>
import dayjs from "dayjs";
import "dayjs/locale/id";

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
    },
};
</script>
<template>
    <div class="row">
        <div class="col-sm-12">
            <div class="card bg-primary">
                <div class="card-body profile-user-box">
                    <div class="row">
                        <div class="col-sm-8">
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

                        <div class="col-sm-4">
                            <div class="text-center mt-sm-0 mt-3 text-sm-end">
                                <router-link
                                    class="btn btn-sm btn-light me-2"
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
                                    class="btn btn-sm btn-light"
                                    v-if="
                                        $can('update', 'Exampacket')
                                        edit
                                    "
                                >
                                    Edit Paket
                                </router-link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
