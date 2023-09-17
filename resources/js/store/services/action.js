import { reject } from "lodash";
import Api from "./api";

const process = {
    actions: {
        getData(context, param) {
            return new Promise((resolve, reject) => {
                Api.init();
                Api.get(param[0] + "?" + param[1]).then(
                    (response) => {
                        resolve(response.data);
                    },
                    (error) => {
                        if (error.response.status == 500) {
                            $("#errorModal").modal("show");
                            $("#errorModal #errorModalLabel").html(
                                error.message
                            );
                            $("#errorModal .modal-body").html(
                                error.response.data.message
                            );
                        }

                        reject(error);
                    }
                );
            });
        },
        showData(context, param) {
            return new Promise((resolve, reject) => {
                Api.init();
                Api.get(param[0] + "/" + param[1]).then(
                    (response) => {
                        resolve(response.data);
                    },
                    (error) => {
                        if (error.response.status == 500) {
                            $("#errorModal").modal("show");
                            $("#errorModal #errorModalLabel").html(
                                error.message
                            );
                            $("#errorModal .modal-body").html(
                                error.response.data.message
                            );
                        }

                        reject(error);
                    }
                );
            });
        },
        showChart(context, param) {
            return new Promise((resolve, reject) => {
                Api.init();
                Api.getChart(param[0] + "?" + param[1]).then(
                    (response) => {
                        resolve(response.data);
                    },
                    (error) => {
                        if (error.response.status == 500) {
                            $("#errorModal").modal("show");
                            $("#errorModal #errorModalLabel").html(
                                error.message
                            );
                            $("#errorModal .modal-body").html(
                                error.response.data.message
                            );
                        }

                        reject(error);
                    }
                );
            });
        },
        postData(context, param) {
            return new Promise((resolve, reject) => {
                Api.init();
                Api.post(param[0], param[1]).then(
                    (response) => {
                        resolve(response.data);
                    },
                    (error) => {
                        if (error.response.status == 500) {
                            $("#errorModal").modal("show");
                            $("#errorModal #errorModalLabel").html(
                                error.message
                            );
                            $("#errorModal .modal-body").html(
                                error.response.data.message
                            );
                        }

                        reject(error);
                    }
                );
            });
        },
        postDataUpload(context, param) {
            return new Promise((resolve, reject) => {
                Api.init();
                Api.postFormData(param[0], param[1]).then(
                    (response) => {
                        resolve(response.data);
                    },
                    (error) => {
                        if (error.response.status == 500) {
                            $("#errorModal").modal("show");
                            $("#errorModal #errorModalLabel").html(
                                error.message
                            );
                            $("#errorModal .modal-body").html(
                                error.response.data.message
                            );
                        }

                        reject(error);
                    }
                );
            });
        },
        updateData(state, param) {
            return new Promise((resolve, reject) => {
                Api.init();
                Api.update(param[0], param[1], param[2]).then(
                    (response) => {
                        resolve(response.data);
                    },
                    (error) => {
                        if (error.response.status == 500) {
                            $("#errorModal").modal("show");
                            $("#errorModal #errorModalLabel").html(
                                error.message
                            );
                            $("#errorModal .modal-body").html(
                                error.response.data.message
                            );
                        }

                        reject(error);
                    }
                );
            });
        },
        putData(state, param) {
            return new Promise((resolve, reject) => {
                Api.init();
                Api.put(param[0], param[1], param[2]).then(
                    (response) => {
                        resolve(response.data.data);
                    },
                    (error) => {
                        if (error.response.status == 500) {
                            $("#errorModal").modal("show");
                            $("#errorModal #errorModalLabel").html(
                                error.message
                            );
                            $("#errorModal .modal-body").html(
                                error.response.data.message
                            );
                        }

                        reject(error);
                    }
                );
            });
        },
        putDataUpload(context, param) {
            return new Promise((resolve, reject) => {
                Api.init();
                Api.updateFormData(param[0], param[1], param[2]).then(
                    (response) => {
                        resolve(response.data);
                    },
                    (error) => {
                        if (error.response.status == 500) {
                            $("#errorModal").modal("show");
                            $("#errorModal #errorModalLabel").html(
                                error.message
                            );
                            $("#errorModal .modal-body").html(
                                error.response.data.message
                            );
                        }

                        reject(error);
                    }
                );
            });
        },
        deleteData(context, param) {
            return new Promise((resolve, reject) => {
                Api.init();
                Api.delete(param[0] + `/${param[1]}`).then(
                    (response) => {
                        resolve(response.data);
                    },
                    (error) => {
                        if (error.response.status == 500) {
                            $("#errorModal").modal("show");
                            $("#errorModal #errorModalLabel").html(
                                error.message
                            );
                            $("#errorModal .modal-body").html(
                                error.response.data.message
                            );
                        }

                        reject(error.response);
                    }
                );
            });
        },
        async getFile(context, param) {
            Api.init();
            // Api.getFile(param[0]+'?'+param[1]).then(
            const response = await Api.getFile(param[0] + "?" + param[1]);
            let blob = new Blob([response.data], {
                type: "application/vnd.ms-excel",
            });
            let link = document.createElement("a");
            link.href = window.URL.createObjectURL(blob);
            link.download = "example.xlsx";
            link.click();
        },
    },
};

export default process;
