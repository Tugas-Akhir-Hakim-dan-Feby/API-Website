<script>
export default {
    props: ["user"],
    methods: {
        handleLogout() {
            this.$store.dispatch("postData", ["auth/logout", {}]).then(() => {
                window.location.replace("/auth/login");
            });
        },
    },
};
</script>
<template>
    <div class="navbar-custom">
        <ul class="list-unstyled topbar-menu float-end mb-0">
            <li class="dropdown notification-list">
                <a
                    class="nav-link dropdown-toggle nav-user arrow-none me-0"
                    data-bs-toggle="dropdown"
                    href="#"
                    role="button"
                    aria-haspopup="false"
                    aria-expanded="false"
                >
                    <span class="account-user-avatar">
                        <img
                            :src="`https://ui-avatars.com/api/?background=random&size=25&rounded=true&length=2&name=${user.name}`"
                            alt="user-image"
                            class="rounded-circle"
                        />
                    </span>
                    <span>
                        <span
                            class="account-user-name"
                            v-html="user.name"
                        ></span>
                        <span
                            class="account-position"
                            v-if="user.roles"
                            v-html="user.roles[0].name"
                        ></span>
                    </span>
                </a>
                <div
                    class="dropdown-menu dropdown-menu-end dropdown-menu-animated topbar-dropdown-menu profile-dropdown"
                >
                    <div class="dropdown-header noti-title">
                        <h6 class="text-overflow m-0">Selamat Datang !</h6>
                    </div>

                    <router-link
                        :to="{ name: 'My Profile' }"
                        class="dropdown-item notify-item"
                    >
                        <i class="mdi mdi-account-circle me-1"></i>
                        <span>Profil Saya</span>
                    </router-link>

                    <a
                        @click="handleLogout"
                        class="dropdown-item notify-item"
                        style="cursor: pointer"
                    >
                        <i class="mdi mdi-logout me-1"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </li>
        </ul>
        <button class="button-menu-mobile open-left">
            <i class="mdi mdi-menu"></i>
        </button>
    </div>
</template>
