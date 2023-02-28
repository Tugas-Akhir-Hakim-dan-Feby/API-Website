export default {
    iteration(index, pagination) {
        return (pagination.currentPage - 1) * pagination.perPage + index + 1;
    },
};
