<script>
import role from "../../store/utils/role";

export default {
    props: ["roles"],
    methods: {
        checkRole(data) {
            if (Array.isArray(this.roles)) {
                return role.checkRoles(this.roles, data);
            }
        },
    },
};
</script>
<template>
    <div class="leftside-menu">
        <router-link
            :to="{ name: 'Dashboard' }"
            class="logo text-center logo-light"
        >
            <span class="logo-lg">
                <img src="../../../images/api-iws.png" alt="" height="50" />
            </span>
            <span class="logo-sm">
                <img src="../../../images/api-iws.png" alt="" height="50" />
            </span>
        </router-link>

        <router-link
            :to="{ name: 'Dashboard' }"
            class="logo text-center logo-dark"
        >
            <span class="logo-lg">
                <img src="../../../images/api-iws.png" alt="" height="50" />
            </span>
            <span class="logo-sm">
                <img src="../../../images/api-iws.png" alt="" height="50" />
            </span>
        </router-link>

        <div class="h-100" id="leftside-menu-container" data-simplebar="">
            <ul class="side-nav">
                <li class="side-nav-title side-nav-item">Navigasi</li>
                <li class="side-nav-item" v-if="$can('index', 'Dashboard')">
                    <router-link
                        :to="{ name: 'Dashboard' }"
                        class="side-nav-link"
                    >
                        <i class="uil-home-alt"></i>
                        <span> Dashboard </span>
                    </router-link>
                </li>

                <li
                    class="side-nav-title side-nav-item"
                    v-if="$can('index', 'Adminhub')"
                >
                    Data Master
                </li>

                <li class="side-nav-item" v-if="$can('index', 'Branch')">
                    <router-link :to="{ name: 'Branch' }" class="side-nav-link">
                        <i class="mdi mdi-family-tree"></i>
                        <span> Data Cabang </span>
                    </router-link>
                </li>

                <li class="side-nav-item" v-if="$can('dashboard', 'Branch')">
                    <router-link
                        :to="{ name: 'Branch Dashboard' }"
                        class="side-nav-link"
                    >
                        <i class="mdi mdi-family-tree"></i>
                        <span> Data Cabang </span>
                    </router-link>
                </li>

                <li
                    class="side-nav-item"
                    v-if="
                        $can('index', 'Adminhub') ||
                        $can('index', 'Adminbranch') ||
                        $can('index', 'Expert') ||
                        $can('index', 'Companymember') ||
                        $can('index', 'Weldermember')
                    "
                >
                    <a
                        data-bs-toggle="collapse"
                        href="#user"
                        aria-expanded="false"
                        aria-controls="user"
                        class="side-nav-link"
                    >
                        <i class="uil-users-alt"></i>
                        <span> Data Pengguna </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="user">
                        <ul class="side-nav-second-level">
                            <li v-if="$can('index', 'Adminhub')">
                                <router-link :to="{ name: 'User Hub' }"
                                    >Admin Pusat</router-link
                                >
                            </li>
                            <li v-if="$can('index', 'Adminbranch')">
                                <router-link :to="{ name: 'User Branch' }"
                                    >Admin Cabang</router-link
                                >
                            </li>
                            <li v-if="$can('index', 'Expert')">
                                <router-link :to="{ name: 'User Expert' }"
                                    >Pakar</router-link
                                >
                            </li>
                            <li v-if="$can('index', 'Companymember')">
                                <router-link :to="{ name: 'User Company' }"
                                    >Perusahaan Member</router-link
                                >
                            </li>
                            <li v-if="$can('index', 'Weldermember')">
                                <router-link :to="{ name: 'User Member' }"
                                    >Personal Member</router-link
                                >
                            </li>
                            <li v-if="$can('index', 'Weldermember')">
                                <router-link :to="{ name: 'User Operator' }"
                                    >TUK Member</router-link
                                >
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="side-nav-item" v-if="$can('index', 'Welderskill')">
                    <router-link
                        :to="{ name: 'Welder Skill' }"
                        class="side-nav-link"
                    >
                        <i class="mdi mdi-head-alert-outline"></i>
                        <span> Keahlian Welder </span>
                    </router-link>
                </li>

                <li class="side-nav-item" v-if="$can('index', 'Article')">
                    <router-link
                        :to="{ name: 'Article' }"
                        class="side-nav-link"
                    >
                        <i
                            class="mdi mdi-newspaper-variant-multiple-outline"
                        ></i>
                        <span> Berita </span>
                    </router-link>
                </li>

                <li
                    class="side-nav-item"
                    v-if="$can('index-guest', 'Advertisement')"
                >
                    <router-link
                        :to="{
                            name: 'Advertisement',
                        }"
                        class="side-nav-link"
                    >
                        <i class="mdi mdi-google-ads"></i>
                        <span> Data Iklan </span>
                    </router-link>
                </li>

                <li class="side-nav-item" v-if="$can('cost', 'Payment')">
                    <a
                        data-bs-toggle="collapse"
                        href="#payment"
                        aria-expanded="false"
                        aria-controls="payment"
                        class="side-nav-link"
                    >
                        <i class="mdi mdi-account-cash"></i>
                        <span> Data Pembayaran </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="payment">
                        <ul class="side-nav-second-level">
                            <li>
                                <router-link :to="{ name: 'Payment Cost' }"
                                    >Data Harga</router-link
                                >
                            </li>
                            <li v-if="$can('index-admin', 'Advertisement')">
                                <router-link
                                    :to="{
                                        name: 'Advertisement',
                                    }"
                                    >Data Iklan</router-link
                                >
                            </li>
                            <li>
                                <router-link
                                    :to="{
                                        name: 'Payment Recapitulation Invoice',
                                    }"
                                    >Data Rekap Faktur</router-link
                                >
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="side-nav-item" v-if="$can('index', 'Jobvacancy')">
                    <router-link
                        :to="{ name: 'Job Vacancy' }"
                        class="side-nav-link"
                    >
                        <i class="mdi mdi-book"></i>
                        <span> Lowongan Pekerjaan </span>
                    </router-link>
                </li>

                <li class="side-nav-item" v-if="$can('history', 'Jobvacancy')">
                    <router-link
                        :to="{ name: 'Job History' }"
                        class="side-nav-link"
                    >
                        <i class="mdi mdi-bookmark"></i>
                        <span> Riwayat Lamaran </span>
                    </router-link>
                </li>

                <li class="side-nav-item" v-if="$can('history', 'Payment')">
                    <router-link
                        :to="{
                            name: 'Payment History',
                        }"
                        class="side-nav-link"
                    >
                        <i class="mdi mdi-account-cash"></i>
                        <span> Riwayat Pembayaran </span>
                    </router-link>
                </li>

                <li class="side-nav-item" v-if="$can('index', 'Exampacket')">
                    <router-link
                        :to="{ name: 'Exam Packet' }"
                        class="side-nav-link"
                    >
                        <i class="mdi mdi-family-tree"></i>
                        <span> Uji Kompetensi </span>
                    </router-link>
                </li>

                <!-- <li class="side-nav-item">
                    <router-link
                        :to="{ name: 'Check Certificate' }"
                        class="side-nav-link"
                    >
                        <i class="mdi mdi-file-search"></i>
                        <span> Cek Sertifikat </span>
                    </router-link>
                </li> -->
            </ul>
        </div>
    </div>
</template>

<style scoped>
.side-nav .side-nav-link {
    color: #cedce4 !important;
}
</style>
