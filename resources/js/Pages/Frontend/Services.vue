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
                        <span><i class="flat-icon icon-kimia-custom-synthesis​"></i></span>
                        <h4 class="h4 mt-3 mb-2">Custom Synthesis​</h4>
                        <p>Kimia is committed to executing the synthesis and delivering the desired compound(s) without being bound by previous literature precedence.</p>
                    </div>
                </div>
                <div class="col-one-third">
                    <div class="services-inner-card">
                        <span><i class="flat-icon  icon-kimia-design-synthesis"></i></span>
                        <h4 class="h4 mt-3 mb-2">Protocol Write up</h4>
                        <p>Kimia offers the option to provide written protocols for compounds synthesized in-house upon request.</p>
                    </div>
                </div>
                <div class="col-one-third">
                    <div class="services-inner-card">
                        <span><i class="flat-icon icon-kimia-design-and-synthesis​"></i></span>
                        <h4 class="h4 mt-3 mb-2">Consultation​</h4>
                        <p>Consult with Kimia's team of expert synthetic chemists for insightful advice on any aspect related to organic synthesis.</p>
                    </div>
                </div>
                <div class="col-one-third">
                    <div class="services-inner-card">
                        <span><i class="flat-icon icon-kimia-synthetic-method"></i></span>
                        <h4 class="h4 mt-3 mb-2">Databases for AI/ML models​</h4>
                        <p>Kimia is pioneering the development of an industry-leading standardized database aimed at empowering the training of AI/ML models for chemical property prediction.</p>
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