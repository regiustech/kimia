<script setup>
    import FrontendLayout from '@/Layouts/FrontendLayout.vue';
    import {Head} from '@inertiajs/vue3';
</script>
<template>
    <FrontendLayout>
        <Head>
            <title>Services</title>
            <meta name="description" content="Kimia description">
        </Head>
        <section class="inner-banner" style="background-image: url('/assets/images/about-banner.jpg');">
            <div class="container  flex text-center">
                <span class="banner-subheading mb-2 row">Kimia Corporation</span>
                <h1 class="mt-0 mb-0 row text-white">Services</h1>       
            </div>
        </section>
        <section id="services" class="pad-100-15 services-section">
            <div class="container flex gap-20">
                <div class="col-one-third services-text-col">
                    <h5 class="sub-heading mt-0 mb-2">We Are Good At What We Do</h5>
                    <h2 class="h2 mb-2 mt-0">We Are Here To Assist</h2>
                    <p class="mb-0">Kimia Corp. offers a wide variety of services for your synthetic organic chemistry needs. Whether you need a complex compound or route scouting Kimia’s experienced scientists can help.</p>
                </div>
                <div class="col-one-third">
                    <div class="services-inner-card">
                        <span><i class="flat-icon  icon-kimia-design-synthesis"></i></span>
                        <h4 class="h4 mt-3 mb-2">Design of Synthesis</h4>
                        <p> Kimia designs the synthesis of the desired organic compound(s) with or without a reference protocol.</p>
                    </div>
                </div>
                <div class="col-one-third">
                    <div class="services-inner-card">
                        <span><i class="flat-icon icon-kimia-custom-synthesis​"></i></span>
                        <h4 class="h4 mt-3 mb-2">Custom Synthesis​</h4>
                        <p> Kimia carries out the custom synthesis of the desired compound(s) using a reference provided by the customer</p>
                    </div>
                </div>
                <div class="col-one-third">
                    <div class="services-inner-card">
                        <span><i class="flat-icon icon-kimia-design-and-synthesis​"></i></span>
                        <h4 class="h4 mt-3 mb-2">Design and Synthesis​​</h4>
                        <p> Kimia handles route design and the custom synthesis of the desired compound(s).</p>
                    </div>
                </div>
                <div class="col-one-third">
                    <div class="services-inner-card">
                        <span><i class="flat-icon icon-kimia-synthetic-method"></i></span>
                        <h4 class="h4 mt-3 mb-2">Provide Synthetic Method​​</h4>
                        <p>Kimia can provide the synthetic method of internally synthesized compounds. Additionally, we can provide advice on the methods used to synthesize external compounds.</p>
                    </div>
                </div>
                <div class="col-one-third">
                    <div class="services-inner-card">
                        <span><i class="flat-icon icon-kimia-analytical-data"></i></span>
                        <h4 class="h4 mt-3 mb-2">Interpretation of Analytical data​</h4>
                        <p>Kimia provides interpretation of analytical data such as LCMS, NMR, FTIR, etc. of organic compounds.</p>
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
                        <h4 class="h4 mt-0 mb-1">Driven</h4>
                        <p>Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae Fusce non nisi finibus.</p>
                    </div>
                    <div class="quality-card text-center">
                        <span class="large-icon"><i class="icon-kimia-motivated"></i></span>
                        <h4 class="h4 mt-0 mb-1">Motivated</h4>
                        <p>Morbi efficitur varius massa at vehicula. Morbi lacus lacus, ullamcorper at porta sed facilisis sed purus.</p>
                    </div>
                    <div class="quality-card text-center">
                        <span class="large-icon"><i class="icon-kimia-creative"></i></span>
                        <h4 class="h4 mt-0 mb-2">Creative</h4>
                        <p>Vestibulum sed massa tortor. Sed egestas faucibus fermentum. Vestibulum nisi quam viverra quis interdum.</p>
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