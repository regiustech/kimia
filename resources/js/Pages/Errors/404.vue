<script setup>
    import FrontendLayout from '@/Layouts/FrontendLayout.vue';
    import {Head} from '@inertiajs/vue3';
</script>
<template>
    <FrontendLayout>
        <Head title="Page Not Found"/>
        <section class="pad-100-15 error-page text-center ">
            <div class="container text-center ">
                <h1 class="mb-2">404</h1>
                <p class="text-404">Oops! It seems you’ve taken a wrong turn. The page you’re looking for may have been moved or doesn’t exist. Double-check the URL or navigate back to our homepage.</p>
                <a class="btn primary-btn mt-0 mb-0" :href="route('home')">Go back to home</a>
            </div>
        </section>
    </FrontendLayout>
</template>