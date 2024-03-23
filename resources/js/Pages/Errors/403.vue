<script setup>
    import {Head} from '@inertiajs/vue3';
</script>
<template>
    <Head title="Forbidden"/>
    <section class="pad-100-15 error-page text-center ">
        <div class="container text-center ">
            <h1 class="mb-2">403</h1>
            <p class="text-404">Sorry, you are forbidden from accessing this page.</p>
            <a class="btn primary-btn mt-0 mb-0" :href="route('home')">Go back to home</a>
        </div>
    </section>
</template>