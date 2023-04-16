<template>
    <div class="leftside-menu">
        <a href="index.html" class="logo text-center logo-light">
            <span class="logo-lg">
                <img src="../../../images/api-iws.png" alt="" height="50" />
            </span>
            <span class="logo-sm">
                <img src="../../../images/api-iws.png" alt="" height="50" />
            </span>
        </a>

        <a href="index.html" class="logo text-center logo-dark">
            <span class="logo-lg">
                <img src="../../../images/api-iws.png" alt="" height="50" />
            </span>
            <span class="logo-sm">
                <img src="../../../images/api-iws.png" alt="" height="50" />
            </span>
        </a>

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

                <li class="side-nav-title side-nav-item">Data Master</li>

                <li class="side-nav-item">
                    <router-link :to="{ name: 'Branch' }" class="side-nav-link">
                        <i class="mdi mdi-family-tree"></i>
                        <span> Data Cabang </span>
                    </router-link>
                </li>

                <li class="side-nav-item">
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

                <li class="side-nav-item">
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

                <li class="side-nav-item">
                    <router-link
                        :to="{ name: 'Question' }"
                        class="side-nav-link"
                    >
                        <i class="mdi mdi-family-tree"></i>
                        <span> Bank Soal </span>
                    </router-link>
                </li>
            </ul>
        </div>
    </div>
</template>
