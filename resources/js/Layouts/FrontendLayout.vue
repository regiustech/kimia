<script setup>
    import Header from '@/Components/Header.vue';
    import Footer from '@/Components/Footer.vue';
    import {Link} from '@inertiajs/vue3';
</script>
<template>
    <Header/>
    <slot/>
    <Footer/>
</template>
