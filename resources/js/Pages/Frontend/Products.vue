<script>
    export default {
        props: ['products','search','category'],
        data(){
            return {
                searchTxt: this.search
            }
        },
        methods: {
            handleCategory(cat){
                let params = {}
                if(cat){
                    params.category = cat;
                }
                if(this.searchTxt){
                    params.search = this.searchTxt;
                }
                this.$inertia.visit(this.route('products',params));
            },
            searchProducts(searchTxt,cat){
                let params = {}
                if(cat){
                    params.category = cat;
                }
                if(searchTxt){
                    params.search = searchTxt;
                }
                this.$inertia.visit(this.route('products',params));
            },
            onKeyPress(evt){
                if(evt.key == "Enter"){
                    this.searchProducts(this.searchTxt,this.category);
                }
            }
        }
    }
</script>
<script setup>
    import FrontendLayout from '@/Layouts/FrontendLayout.vue';
    import {Head} from '@inertiajs/vue3';
    const pageRange = (currentPage,lastPage) => {
        const ranges = [];
        const pages = [];
        if((currentPage - 4) > 0 && currentPage == lastPage){
            ranges.push((currentPage - 3));
            ranges.push((currentPage - 2));
            ranges.push((currentPage - 1));
        }
        if((currentPage - 3) > 0 && (currentPage + 1) == lastPage){
            ranges.push((currentPage - 2));
            ranges.push((currentPage - 1));
        }
        if((currentPage - 2) > 0 && (currentPage + 2) == lastPage){
            ranges.push((currentPage - 1));
        }
        for(let i = currentPage;i <= lastPage;i++){
            ranges.push(i);
        }
        if(ranges.length > 4){
            pages.push(ranges[0]);
            pages.push(ranges[1]);
            pages.push('...');
            pages.push(ranges[ranges.length - 2]);
            pages.push(ranges[ranges.length - 1]);
        }else{
            for(let k = 0;k<ranges.length;k++){
                pages.push(ranges[k]);
            }
        }
        return pages;
    }
</script>
<template>
    <FrontendLayout>
        <Head>
            <title>Product &#8211; Kimia Corp.</title>
            <meta name="description" content="Contact Kimia Corp. today for more information!">
        </Head>
        <section class="pad-100-15 product-cat-section">
            <div class="products-bar mb-5">
                <div class="container gap-100 flex items-verticaly-center">
                    <div class="search-by-category">
                        <a class="flex gap-10 items-verticaly-center search-link">
                            <span class="category-icon">
                                <span class="line-one"></span>
                                <span class="line-two"></span>
                                <span class="line-three"></span>
                            </span>
                            <span class="dropdown-text">Shop by category</span>
                            <span class="dropdown-icon"></span> 
                        </a>
                        <ul class="search-list">
                            <li class="search-item all" :class="(category != 'linkers' && category != 'new' && category != 'pas') ? 'active' : ''" @click="handleCategory('')">All Products</li>
                            <li class="search-item" :class="category == 'linkers' ? 'active' : ''" @click="handleCategory('linkers')">Linkers</li>
                            <li class="search-item" :class="category == 'new' ? 'active' : ''" @click="handleCategory('new')">New From Kimia</li>
                            <li class="search-item" :class="category == 'pas' ? 'active' : ''" @click="handleCategory('pas')">PAS</li>
                        </ul>
                    </div>
                    <div class="input search-input">
                        <input type="text" v-model="searchTxt" @keypress="onKeyPress($event)" placeholder="Search by Name, CAS Number, Catalog Number or Molecular Formula">
                        <button type="submit" class="submit-icon" @click="searchProducts(searchTxt,category)"><i class="icon-kimia-search"></i></button>
                    </div>
                </div>
            </div>
            <div class="row mb-5 text-center">
                <h2 class="h2 mb-0 mt-0">Products</h2>
            </div>
            <div v-if="products && products.data && products.data.length > 0" class="container flex gap-20">
                <div class="card product-card" v-for="product in products.data" :key="product.id">
                    <div class="product-feature-image flex text-center">
                        <a :href="route('productDetail',product.slug)"><img :src="product.image" :alt="product.catalog_number"></a>
                    </div>
                    <div class="product-content text-center">
                        <div class="category">{{product.category}}</div>
                        <div class="sku mt-0 mb-1">{{product.catalog_number}}</div>
                        <h4 class="product-title mt-0 mb-1"><a :href="route('productDetail',product.slug)">{{product.name}}</a></h4>
                        <div class="btn-wrap">
                            <a class="btn secondary-btn" :href="route('productDetail',product.slug)"><span class="btn-text">View Detail</span></a>
                        </div>
                    </div>
                </div>
                <div class="row mt-5 text-center" v-if="((products.current_page > 1) || (products.current_page < products.last_page))">
                    <ul class="pagination flex gap-10 mt-0 mb-0 text-center">
                        <li>
                            <a v-if="products.current_page > 1" class="prev page-icon" :href="route('products',{search: searchTxt,category,page: (products.current_page - 1)})"><i class="icon-left-open-big"></i></a>
                            <a v-else class="prev page-icon"><i class="icon-left-open-big"></i></a>
                        </li>
                        <li v-for="page in pageRange(products.current_page,products.last_page)" :key="page">
                            <a v-if="page == products.current_page" :class="((page == products.current_page) ? 'prev page-icon active' : 'prev page-icon')">{{page}}</a>
                            <a v-else-if="page != '...'" :class="((page == products.current_page) ? 'prev page-icon active' : 'prev page-icon')" :href="route('products',{search: searchTxt,category,page})">{{page}}</a>
                            <a v-else class="prev page-icon">{{page}}</a>
                        </li>
                        <li>
                            <a v-if="products.current_page < products.last_page" class="next page-icon" :href="route('products',{search: searchTxt,category,page: (products.current_page + 1)})"><i class="icon-right-open-big"></i></a>
                            <a v-else class="next page-icon"><i class="icon-right-open-big"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
            <div v-else class="container flex gap-20">
                <p class="mt-6">No product found.</p>
            </div>
        </section>
    </FrontendLayout>
</template>