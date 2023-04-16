<script>
import Error from "../../components/alerts/Error.vue";
import Success from "../../components/notifications/Success.vue";
import PageTitle from "../../components/PageTitle.vue";

export default {
    data() {
        return {
            msg: "",
            isError: false,
            isLoading: false,
            errors: {},
            form: {
                articleTitle: "",
                articleContent: "",
                document: null,
            },
        };
    },
    computed: {
        formData() {
            let formData = new FormData();

            formData.append("article_title", this.form.articleTitle);
            formData.append("article_content", this.form.articleContent);
            formData.append("document", this.form.document);

            return formData;
        },
    },
    methods: {
        handleSubmit() {
            this.isLoading = true;
            this.errors = {};
            this.$store
                .dispatch("postDataUpload", ["article", this.formData])
                .then((response) => {
                    this.isLoading = false;
                    $("#successModal").modal("show");
                    this.msg = "data berhasil ditambahkan.";
                })
                .catch((error) => {
                    this.isLoading = false;
                    this.errors = error.response.data.messages;

                    if (
                        error.response.data.status == "ERROR" &&
                        error.response.data.statusCode == 500
                    ) {
                        this.isError = true;
                        this.msg = error.response.data.message;
                    }
                });
        },
        uploadThumbnail(e) {
            this.form.document = e.target.files[0];
        },
    },
    components: { PageTitle, Success, Error },
};
</script>

<template>
    <div class="container-fluid">
        <PageTitle :title="'Tambah Berita'" />

        <Error v-if="isError" :message="msg" />

        <form @submit.prevent="handleSubmit">
            <div class="card">
                <div class="card-body">
                    <div class="mb-2">
                        <label>Judul Berita</label>
                        <input
                            type="text"
                            class="form-control"
                            :class="{ 'is-invalid': errors.articleTitle }"
                            v-model="form.articleTitle"
                            :disabled="isLoading"
                        />
                        <div
                            class="invalid-feedback"
                            v-if="errors.articleTitle"
                            v-for="(error, index) in errors.articleTitle"
                            :key="index"
                        >
                            {{ error }}.
                        </div>
                    </div>
                    <div class="mb-2">
                        <label>Konten Berita</label>
                        <QuillEditor
                            theme="snow"
                            toolbar="full"
                            style="height: 300px"
                            :style="{
                                borderColor: errors.articleContent
                                    ? '#fa5c7c'
                                    : '',
                            }"
                            v-model:content="form.articleContent"
                            contentType="html"
                        />
                        <div
                            style="
                                width: 100%;
                                margin-top: 0.25rem;
                                font-size: 0.75rem;
                                color: #fa5c7c;
                            "
                            v-if="errors.articleContent"
                            v-for="(error, index) in errors.articleContent"
                            :key="index"
                        >
                            {{ error }}.
                        </div>
                    </div>
                    <div class="mb-2">
                        <label>Upload Gambar</label>
                        <input
                            type="file"
                            class="form-control"
                            @change="uploadThumbnail"
                            :class="{ 'is-invalid': errors.document }"
                            :disabled="isLoading"
                        />
                        <div
                            class="invalid-feedback"
                            v-if="errors.document"
                            v-for="(error, index) in errors.document"
                            :key="index"
                        >
                            {{ error }}.
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <router-link
                        :to="{ name: 'Article' }"
                        class="btn btn-secondary"
                        >Batal</router-link
                    >
                    <button class="btn btn-success">Simpan</button>
                </div>
            </div>
        </form>
    </div>

    <Success :url="{ name: 'Article' }" :msg="msg" />
</template>
