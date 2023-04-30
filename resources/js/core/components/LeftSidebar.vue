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
                <li class="side-nav-item">
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
                    v-if="checkRole($store.state.ADMIN_APP)"
                >
                    Data Master
                </li>

                <li
                    class="side-nav-item"
                    v-if="checkRole($store.state.ADMIN_APP)"
                >
                    <router-link :to="{ name: 'Branch' }" class="side-nav-link">
                        <i class="mdi mdi-family-tree"></i>
                        <span> Data Cabang </span>
                    </router-link>
                </li>

                <li
                    class="side-nav-item"
                    v-if="checkRole($store.state.ADMIN_APP)"
                >
                    <a
                        data-bs-toggle="collapse"
                        href="#user"
                        aria-expanded="false"
                        aria-controls="user"
                        class="side-nav-link"
                    >
                        <i class="uil-users-alt"></i>
                        <span> Pengguna </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="user">
                        <ul class="side-nav-second-level">
                            <li>
                                <router-link :to="{ name: 'User Hub' }"
                                    >API Pusat</router-link
                                >
                            </li>
                            <li>
                                <router-link :to="{ name: 'User Branch' }"
                                    >API Cabang</router-link
                                >
                            </li>
                            <li>
                                <router-link :to="{ name: 'User Expert' }"
                                    >Pakar</router-link
                                >
                            </li>
                            <li>
                                <router-link :to="{ name: 'User Company' }"
                                    >Perusahaan Member</router-link
                                >
                            </li>
                            <li>
                                <router-link :to="{ name: 'User Member' }"
                                    >Welder Member</router-link
                                >
                            </li>
                        </ul>
                    </div>
                </li>

                <li
                    class="side-nav-item"
                    v-if="checkRole($store.state.ADMIN_APP)"
                >
                    <router-link
                        :to="{ name: 'Welder Skill' }"
                        class="side-nav-link"
                    >
                        <i class="mdi mdi-head-alert-outline"></i>
                        <span> Keahlian Welder </span>
                    </router-link>
                </li>

                <li class="side-nav-item">
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
                    v-if="
                        checkRole($store.state.ADMIN_APP) ||
                        checkRole($store.state.MEMBER_WELDER)
                    "
                >
                    <router-link
                        :to="{ name: 'Exam Packet' }"
                        class="side-nav-link"
                    >
                        <i class="mdi mdi-family-tree"></i>
                        <span> Uji Kompetensi </span>
                    </router-link>
                </li>
            </ul>
        </div>
    </div>
</template>
