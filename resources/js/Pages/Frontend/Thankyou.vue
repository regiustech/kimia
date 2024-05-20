<script setup>
    import FrontendLayout from '@/Layouts/FrontendLayout.vue';
    import {Head} from '@inertiajs/vue3';
</script>
<template>
    <FrontendLayout>
        <Head>
            <title>Thank You &#8211; Kimia Corp.</title>
            <meta name="description" content="">
        </Head>
        <section class="pad-100-15 thankyou-page-template text-center" style="background-image: url('/assets/images/background-image.jpg');background-size:cover;">
            <div class="container text-center ">
                <div class="icon">
                    <i class="icon-badge-check"></i>
                </div>
                <h1 class="mb-2">Thank You</h1>
                <p>Your  message have been recieved. <br> We will update you shortly</p>
                <a class="btn primary-btn mt-0 mb-0" :href="route('home')">Go back to home</a>
            </div>
        </section>
    </FrontendLayout>
</template>