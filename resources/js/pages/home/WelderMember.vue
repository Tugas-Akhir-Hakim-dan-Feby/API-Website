<script>
export default {
    props: ["user"],
};
</script>
<template>
    <div class="alert alert-primary">
        Selamat datang <strong>{{ user.name }}</strong
        >!
    </div>
</template>
