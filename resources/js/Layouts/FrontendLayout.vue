<script setup>
    import Header from '@/Layouts/Header.vue';
    import Footer from '@/Layouts/Footer.vue';
    import {Link} from '@inertiajs/vue3';
</script>
<template>
    <Header/>
    <slot/>
    <Footer/>
</template>
