<script>
export default {
    props: ["user"],
};
</script>
<template>
    <div class="card">
        <div class="card-header">
            <h4 class="fw-bold card-title py-2 m-0 text-dark">
                Selamat Datang...
            </h4>
        </div>
        <div class="card-body text-center">
            <div>
                <img
                    src="../../../images/certificate.png"
                    height="150"
                    width="150"
                    class="mb-3"
                />
            </div>
            <h3 class="text-dark">Sistem Manajemen Sertifikasi</h3>
            <p class="text-center mt-4 px-5">
                Sistem ini akan membantu anda dalam memanajemen sertifikat
                khususnya bidang pengelasan, mulai dari pencarian, ujian hingga
                mendapatkan sertifikat. Selain itu sistem ini dapat membantu
                dalam pencarian Lowongan Pekerjaan dalam bidang Pengelasan.
            </p>
            <p class="text-center mt-4 fw-bold text-dark">
                Asosiasi Pengelasan Indonesia
            </p>
        </div>
    </div>
</template>

<style scoped>
p {
    font-size: 15px;
}
</style>
