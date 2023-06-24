<script>
export default {
    data() {
        return {
            timer: 10,
        };
    },
    watch: {
        timer: {
            handler(value) {
                if (value > 0) {
                    setTimeout(() => {
                        this.timer--;
                    }, 1000);
                }

                if (value == 0) {
                    localStorage.removeItem("isPayment");
                    this.$router.push({ name: "Dashboard" });
                }
            },
            immediate: true,
        },
    },
    methods: {
        redirect() {
            localStorage.removeItem("isPayment");
            this.$router.push({ name: "Dashboard" });
        },
    },
};
</script>
<template>
    <div class="d-flex justify-content-center">
        <div class="card" style="width: 25rem; margin-top: 5rem">
            <div class="card-body">
                <div class="text-center pt-3 pb-3" id="sent">
                    <span class="dots">
                        <i class="mdi mdi-check-underline"></i>
                    </span>
                    <h4 class="mt-2">Pembayaran Berhasil</h4>
                </div>
                <div class="text-center mb-2">
                    {{ timer }} detik lagi, kembali ke Dashboard
                </div>
                <div class="text-center">
                    <a href="#" @click="redirect()">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.form {
    display: all;
    transition: all 2s;
}
.invoice-btn {
    height: 52px;
}
.input-text {
    height: 48px;
}
.text-area {
    resize: none;
}
.display-none {
    display: none;
    transition: height 1s, width 1s, padding 1s, visibility 1s,
        opacity 0.5s ease-out;
}
.dots {
    height: 50px;
    width: 50px;
    background-color: blue;
    border-radius: 50%;
    color: #fff;
    font-size: 29px;
    display: inline-block;
}
.btn:focus,
.form-check-input:focus,
.input-text:focus,
.text-area:focus {
    box-shadow: none;
}
</style>
