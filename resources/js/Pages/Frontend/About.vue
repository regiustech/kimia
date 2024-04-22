<script setup>
    import FrontendLayout from '@/Layouts/FrontendLayout.vue';
    import {Head} from '@inertiajs/vue3';
</script>
<template>
    <FrontendLayout>
        <Head>
            <title>About</title>
            <meta name="description" content="About description">
        </Head>
        <section class="inner-banner" style="background-image: url('/assets/images/about-banner.jpg');">
            <div class="container flex text-center">
                <span class="banner-subheading mb-2 row">Who we area</span>
                <h1 class="mt-0 mb-0 row text-white">About Us</h1>       
            </div>
        </section>
        <section class="pad-100-15 product-section">
            <div class="container flex gap-70 items-verticaly-center">
                <div class="col-50 tab-full">
                    <h5 class="sub-heading mt-0 mb-1">About Us</h5>
                    <h2 class="h2 mb-3">About Kimia Corp.</h2>
                    <p>Kimia Corporation is a Santa Clara, California based contract research organization founded in 1994 and specialized in the synthesis of small organic molecules. Encompassing a highly qualified and diversified team of chemists, our expertise allows us to cover a wide range of organic compounds and processes. At Kimia we focus on the preparation of high purity special compounds for facilitating your research. </p>
                    <p>Whether you know the exact compounds you desire or have reached a state of frustration with your research and desperately seeking alternative specialty compounds, Kimia is standing by to serve you. In addition Kimia has developed and contributed to various technologies which have proven fundamental towards research in this field. A superior dedication to confidentiality, research and service, shall prove Kimia your dependable source for years to come.</p>
                </div>
                <div class="col-50 tab-full">
                    <div class="image-wrapper rounded-image">
                        <img src="/assets/images/about-kimia-corp-image.jpg" alt="About Kimia Corp"/>
                    </div>
                </div>
            </div>
        </section>
        <section class="pad-100-15 quality-section" style="background-image: url('/assets/images/background-image.jpg'); background-size: cover;">
            <div class="container flex gap 30px">
                <div class="row mb-2 text-center">
                    <h5 class="sub-heading mt-0 mb-2">We Have No Limits</h5>
                    <h2 class="h2 mb-2 mt-0">Why Choose Us</h2>
                    <p>Kimia Corporation is a contract research organization based in Santa Clara, California. Founded in 1994, Kimia specializes in the synthesis of small organic molecules. Encompassing a highly qualified and diversified team of chemists, our expertise allows us to cover a wide range of organic compounds and synthetic procedures. Additionally, Kimia Corp has contributed to the development of various analytical, diagnostic, and pharmaceutical technologies with a superior dedication to confidentiality, quality, and customer service. </p>
                </div>
                <div class="row mt-10 flex  gap-20 card-wrapper">
                    <div class="quality-card text-center">
                        <span class="large-icon"><i class="icon-kimia-quality"></i></span>
                        <h4 class="h4 mt-0 mb-1">Respect for Intellectual Property</h4>
                        <p>Based in the USA, Kimia upholds the highest standards of intellectual property law and encourages open communication with clients.</p>
                    </div>
                    <div class="quality-card text-center">
                        <span class="large-icon"><i class="icon-kimia-motivated"></i></span>
                        <h4 class="h4 mt-0 mb-1">Established Since 1994</h4>
                        <p>With roots dating back to 1994, Kimia has established itself as a trusted provider of custom chemistry services. Our extensive track record speaks volumes about our commitment to customer satisfaction</p>
                    </div>
                    <div class="quality-card text-center">
                        <span class="large-icon"><i class="icon-kimia-creative"></i></span>
                        <h4 class="h4 mt-0 mb-2">Flexibility</h4>
                        <p>At Kimia, we understand that every client has unique requirements. That's why we offer flexible contracting models and Statements of Work (SOWs), ensuring that our services align perfectly with your needs and preferences.</p>
                    </div>
                </div>
            </div>
        </section>
        <section class="pad-60-15 cta-section" style="background-image: url('/assets/images/cta-background-image.jpg'); background-size: cover;">
        <div class="cta-container text-center">
            <h2 class="h2 mb-3 mt-0 text-white">Kimia Corp. offers a wide variety of services for your synthetic organic chemistry needs.</h2>
            <a class="btn secondary-btn" :href="route('contacts')"><span class="btn-text">Contact us</span></a>
        </div>
        </section>
    </FrontendLayout>
</template>