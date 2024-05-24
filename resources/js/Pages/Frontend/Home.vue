<script>
    export default {
        data(){
            return {
                activeTab: "catalog",
                searchTxt: "",
                sliderOptions: {
                    autoplay: true,
                    interval: 3000,
                    pauseOnHover: true,
                    type: 'loop',
                    perPage: 4,
                    gap: 20,
                    pagination: false,
                    perMove: 1,
                    breakpoints: {
                        1199: {perPage: 3},
                        767: {perPage: 2},
                        479: {perPage: 1}
                    }
                }
            }
        },
        methods: {
            changeTab(tab){
                if(this.activeTab != tab){
                    this.activeTab = tab;
                }
            },
            searchProducts(){
                this.$inertia.visit(this.route('products',{search: this.searchTxt}));
            },
            onKeyPress(evt){
                if(evt.key == "Enter"){
                    this.$inertia.visit(this.route('products',{search: this.searchTxt}));
                }
            }
        }
    }
</script>
<script setup>
    import FrontendLayout from '@/Layouts/FrontendLayout.vue';
    import {Head} from '@inertiajs/vue3';
    import '@splidejs/vue-splide/css';
    import '@splidejs/vue-splide/css/core';
    defineProps({
        allProducts: {type: Array},
        linkersProducts: {type: Array}
    });
</script>
<template>
    <FrontendLayout>
        <Head>
            <title>Kimia Corp. &#8211; Custom Synthesis</title>
            <meta name="description" content="Contact Kimia Corp. today for more information!">
        </Head>
        <section class="hero">
            <div class="container flex">
                <div class="col-50 banner-text">
                    <div class="col-50-wrapper">
                        <!-- <h1 class="text-white mb-3">Global Supplier of Chemistry Services</h1> -->
                        <p class="text-white"><strong class="text-primary1">Kimia Corp.</strong> is a contract research organization based in Santa Clara, California that focuses on custom synthesis for several industries such as drug discovery, diagnostics, etc.</p>
                        <a class="btn secondary-btn mt-3" :href="route('products')"><span class="btn-text">View all products</span></a>
                        <div class="input search-input mt-10">
                            <input type="text" v-model="searchTxt" @keypress="onKeyPress($event)" placeholder="Search by Name, CAS Number, Catalog Number or Molecular Formula"/>
                            <button type="submit" class="submit-icon" @click="searchProducts"><i class="icon-kimia-search"></i></button>
                        </div>
                    </div>
                </div>
                <div class="col-50">
                    <div class="col-50-wrapper banner-image-wrapper">
                        <img src="/assets/images/banner-image.jpg" alt="Global Supplier of  Chemistry Services"/>
                    </div>
                </div>
            </div>
        </section>
        <section class="pad-100-15 product-section">
            <div class="container flex">
                <div class="row mb-5 text-center">
                    <h5 class="sub-heading mt-0 mb-2">Products</h5>
                    <h2 class="h2 mb-0 mt-0">Our Products</h2>
                </div>
                <div class="row">
                    <ul class="product-nav flex items-verticaly-center gap-50 mt-0 text-center mb-5">
                        <li><a :class="activeTab == 'catalog' ? 'active' : ''" @click="changeTab('catalog')">Catalog</a></li>
                        <li><a :class="activeTab == 'linkers' ? 'active' : ''" @click="changeTab('linkers')">Linkers</a></li>
                        <li><a :class="activeTab == 'new-prd' ? 'active' : ''" @click="changeTab('new-prd')">New from Kimia</a></li>
                        <li><a :class="activeTab == 'pas' ? 'active' : ''" @click="changeTab('pas')">PAS </a></li>
                    </ul>
                    <div :class="activeTab == 'catalog' ? 'tab-content active' : 'tab-content'">
                        <div class="row flex gap-20" v-if="allProducts.length">
                            <div class="card product-card" v-for="item in allProducts" :key="item.id">
                                <div class="product-feature-image flex text-center">
                                    <a :href="route('productDetail',item.slug)"><img :src="item.image" :alt="item.catalog_number"/></a>
                                </div>
                                <div class="product-content text-center">
                                    <div class="sku mt-0 mb-1">{{item.catalog_number}}</div>
                                    <h4 class="product-title mt-0 mb-1"><a :href="route('productDetail',item.slug)">{{item.name}}</a></h4>
                                    <div class="btn-wrap">
                                        <a class="btn secondary-btn" :href="route('productDetail',item.slug)"><span class="btn-text">View Detail</span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row text-center mt-5">
                            <a class="btn primary-btn" :href="route('products')"><span class="btn-text">View All Products</span></a>
                        </div>
                    </div>
                    <div :class="activeTab == 'linkers' ? 'tab-content active' : 'tab-content'">
                        <div class="row flex gap-20" v-if="linkersProducts.length">
                            <div class="card product-card" v-for="item in linkersProducts" :key="item.id">
                                <div class="product-feature-image flex text-center">
                                    <a :href="route('productDetail',item.slug)"><img :src="item.image" :alt="item.catalog_number"/></a>
                                </div>
                                <div class="product-content text-center">
                                    <div class="sku mt-0 mb-1">{{item.catalog_number}}</div>
                                    <h4 class="product-title mt-0 mb-1"><a :href="route('productDetail',item.slug)">{{item.name}}</a></h4>
                                    <div class="btn-wrap">
                                        <a class="btn secondary-btn" :href="route('productDetail',item.slug)"><span class="btn-text">View Detail</span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row text-center mt-5">
                            <a class="btn primary-btn" :href="route('products')"><span class="btn-text">View All Products</span></a>
                        </div>
                    </div>
                    <div :class="activeTab == 'new-prd' ? 'tab-content active' : 'tab-content'">
                        <p class="text-center">Please visit here frequently to see new additions to our catalog</p>
                    </div>
                    <div :class="activeTab == 'pas' ? 'tab-content active' : 'tab-content'">
                        <p class="text-center">The product line PAS is meticulously designed to expedite synthesis processes by eliminating preparatory steps, thereby saving valuable time. Beyond time efficiency, our products offer a host of advantages:</p>
                        <div class="pas-content">
                            <ol>
                                <li>
                                    <span class="large-icon"><img src="/assets/images/cost-effective-icon.png" width="35"/></span>
                                    <strong>Cost-effectiveness</strong> Our products provide affordable access to a wide range of compounds, ensuring that cutting-edge research remains within budgetary constraints.
                                </li>
                                <li>   <span class="large-icon"><img src="/assets/images/eco-friendly-icon.png" width="30"/></span>
                                    <strong>Eco-friendly</strong> With minimal solvent usage and waste generation, our products promote sustainable laboratory practices, contributing to a greener environment.
                                </li>
                                <li>  <span class="large-icon"><img src="/assets/images/space-efficiency-icon.png" width="30"/></span>
                                    <strong>Space efficiency</strong> By requiring minimal storage space, our products optimize laboratory organization and resource management.
                                </li>
                                <li>  
                                    <span class="large-icon"><img src="/assets/images/zero-wastage-icon.png" width="35"/></span>
                                    <strong>Zero wastage</strong> Our formulations ensure that no compounds are left unused, maximizing efficiency and minimizing resource depletion.
                                </li>
                            </ol>
                        </div>
                        <p class="text-center">Browse through our catalogue to explore our diverse range of compounds, each available in a convenient 100 μmole scale, ready to catalyze your next breakthrough reaction.</p>
                    </div>
                </div>
            </div>
        </section>
        <section class="pad-100-15 slider-section bg-dark ">
            <div class="container">
                <div class="row flex gap-50 mb-5 wrap-unset">
                    <div class="col-40">
                        <h5 class="sub-heading mt-0 mb-1">We Are Good At What We Do</h5>
                        <h2 class="h2 text-white mb-0">We Are Here To Assist</h2>
                    </div>
                    <div class="col-60">
                        <p class="text-white mb-0">Kimia Corp. offers a wide variety of services for your synthetic organic chemistry needs. Whether you need a complex compound or route scouting Kimia’s experienced scientists can help.</p>
                    </div>
                </div>
                <Splide :options="sliderOptions">
                    <SplideSlide>
                        <a :href="route('services')" class="services-card">
                            <div class="services-image mb-1">
                                <img src="/assets/images/design-of-synthesis-img.jpg" alt="Design of Synthesis"/>
                            </div>
                            <h3 class="mb-1">Design of Synthesis</h3>
                            <p class="mb-0 text-white">Kimia designs the synthesis of the desired organic compound(s) with or without a reference protocol.</p>
                        </a>
                    </SplideSlide>
                    <SplideSlide>
                        <a :href="route('services')" class="services-card">
                            <div class="services-image mb-1">
                                <img src="/assets/images/custom-synthesis-img.jpg" alt="Custom Synthesis​"/>
                            </div>
                            <h3 class="mb-1">Custom Synthesis​</h3>
                            <p class="mb-0 text-white">Kimia carries out the custom synthesis of the desired compound(s) using a reference provided by the customer</p>
                        </a>
                    </SplideSlide>
                    <SplideSlide>
                        <a :href="route('services')" class="services-card">
                            <div class="services-image mb-1">
                                <img src="/assets/images/design-and-synthesis.jpg" alt="Design and Synthesis​"/>
                            </div>
                            <h3 class="mb-1">Design and Synthesis​</h3>
                            <p class="mb-0 text-white"> Kimia handles route design and the custom synthesis of the desired compound(s).</p>
                        </a>
                    </SplideSlide>
                    <SplideSlide>
                        <a :href="route('services')" class="services-card">
                            <div class="services-image mb-1">
                                <img src="/assets/images/provide-synthetic-method-img.jpg" alt="Provide Synthetic Method"/>
                            </div>
                            <h3 class="mb-1">Provide Synthetic Method</h3>
                            <p class="mb-0 text-white">Kimia can provide the synthetic method of internally synthesized compounds.</p>
                        </a>
                    </SplideSlide>
                    <SplideSlide>
                        <a :href="route('services')" class="services-card">
                            <div class="services-image mb-1">
                                <img src="/assets/images/interpretation-of-analytical-data-img.jpg" alt="Interpretation of Analytical data"/>
                            </div>
                            <h3 class="mb-1">Interpretation of Analytical data</h3>
                            <p class="mb-0 text-white">Kimia provides interpretation of analytical data such as LCMS, NMR, FTIR, etc. of organic compounds.</p>
                        </a>
                    </SplideSlide>
                </Splide>
            </div>
        </section>
        <section class="about-section">
            <div class="container flex">
                <div class="col-40 flex items-verticaly-center">
                    <div class="dual-image-wrapper">
                        <img class="dual-img-1" src="/assets/images/about-image-1.jpg" alt="About Kimia Corp"/>
                        <img class="dual-img-2" src="/assets/images/about-image-2.jpg" alt="About Kimia Corp"/>
                    </div>
                </div>
                <div class="col-60">
                    <div class="abot-text-wrapper">
                        <div class="about-text">
                            <img class="bg-1" src="/assets/images/about-bg-1.svg" alt="About Kimia Corp"/>
                            <img class="bg-2" src="/assets/images/about-bg-2.svg" alt="About Kimia Corp"/>
                            <h5 class="sub-heading mb-2">About Us</h5>
                            <h2 class="h2 mb-3 mt-0">About Kimia Corp.</h2>
                            <p>Kimia Corporation is a contract research organization based in Santa Clara, California that focuses on custom synthesis for several industries such as drug discovery, diagnostics, etc. Kimia also has a catalog of small molecules that continues to expand.</p>
                            <a class="btn primary-btn mt-2" :href="route('about')"><span class="btn-text">Read More</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </FrontendLayout>
</template>
<style>
    .splide__arrows{position:absolute;bottom:-30px;}
    .splide__arrows .splide__arrow--prev{left:0;}
    .splide__arrows .splide__arrow--next{left:45px;}
</style>