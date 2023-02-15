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
                            src="https://ui-avatars.com/api/?background=random&size=25&rounded=true&length=2&name=Soeng Souy"
                            alt="user-image"
                            class="rounded-circle"
                        />
                    </span>
                    <span>
                        <span class="account-user-name">Soeng Souy</span>
                        <span class="account-position">Founder</span>
                    </span>
                </a>
                <div
                    class="dropdown-menu dropdown-menu-end dropdown-menu-animated topbar-dropdown-menu profile-dropdown"
                >
                    <div class="dropdown-header noti-title">
                        <h6 class="text-overflow m-0">Welcome !</h6>
                    </div>

                    <a
                        href="javascript:void(0);"
                        class="dropdown-item notify-item"
                    >
                        <i class="mdi mdi-account-circle me-1"></i>
                        <span>My Account</span>
                    </a>

                    <a
                        href="javascript:void(0);"
                        class="dropdown-item notify-item"
                    >
                        <i class="mdi mdi-account-edit me-1"></i>
                        <span>Settings</span>
                    </a>

                    <a
                        href="javascript:void(0);"
                        class="dropdown-item notify-item"
                    >
                        <i class="mdi mdi-lifebuoy me-1"></i>
                        <span>Support</span>
                    </a>

                    <a
                        href="javascript:void(0);"
                        class="dropdown-item notify-item"
                    >
                        <i class="mdi mdi-lock-outline me-1"></i>
                        <span>Lock Screen</span>
                    </a>

                    <a
                        href="javascript:void(0);"
                        class="dropdown-item notify-item"
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
