<script>
import PageTitle from "../../../components/PageTitle.vue";
import Approve from "./Approve.vue";
import NotApprove from "./NotApprove.vue";

export default {
    data() {
        return {
            counNotApprove: 0,
            isNotApproved: false,
        };
    },
    mounted() {
        this.getUsers();
    },
    methods: {
        getUsers() {
            let params = [`approved=false`].join("&");

            this.$store
                .dispatch("getData", ["user/expert", params])
                .then((response) => {
                    this.counNotApprove = response.data.length;
                })
                .catch((err) => {});
        },
        onSuccess(e) {
            this.getUsers();
        },
    },
    components: { PageTitle, Approve, NotApprove },
};
</script>
<template>
    <PageTitle :title="'Daftar Pengguna Pakar'" />

    <div class="card">
        <ul class="nav nav-tabs justify-content-around pt-2">
            <li class="nav-item">
                <a
                    href="#expertApprove"
                    data-bs-toggle="tab"
                    aria-expanded="false"
                    class="nav-link text-primary active"
                    @click="isNotApproved = false"
                >
                    <span>Data Pakar yang Di Setujui</span>
                </a>
            </li>
            <li class="nav-item">
                <a
                    href="#expertNotApprove"
                    data-bs-toggle="tab"
                    aria-expanded="false"
                    class="nav-link text-primary position-relative"
                    @click="isNotApproved = true"
                >
                    <span>Data Pakar yang Belum Di Setujui</span>
                    <span
                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                    >
                        {{ counNotApprove < 99 ? counNotApprove : "99+" }}
                        <span class="visually-hidden">unread messages</span>
                    </span>
                </a>
            </li>
        </ul>
        <div class="card-body position-relative">
            <div class="tab-content">
                <div class="tab-pane show active" id="expertApprove">
                    <Approve v-if="!isNotApproved" />
                </div>
                <div class="tab-pane" id="expertNotApprove">
                    <NotApprove
                        v-if="isNotApproved"
                        @onSuccess="onSuccess($event)"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
