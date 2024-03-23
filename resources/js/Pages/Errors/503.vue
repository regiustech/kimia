<script setup>
    import {Head} from '@inertiajs/vue3';
</script>
<template>
    <Head title="Server Error"/>
    <section class="pad-100-15 error-page text-center ">
        <div class="container text-center ">
            <h1 class="mb-2">503</h1>
            <p class="text-404">Sorry, we are doing some maintenance. Please check back soon.</p>
            <a class="btn primary-btn mt-0 mb-0" :href="route('home')">Go back to home</a>
        </div>
    </section>
</template>