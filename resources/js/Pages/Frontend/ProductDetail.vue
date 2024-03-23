<script>
    export default {
        data(){
            return {
                quantity: 1
            }
        },
        methods: {
            quantityChange(qty,type){
                if(type == 'increament' && qty < 99){
                    this.quantity = (parseInt(qty) + parseInt(1));
                }else if(type == 'decreament' && qty > 1){
                    this.quantity = (parseInt(qty) - parseInt(1));
                }
            },
            addToCart(product_id,quantity = 1,reset = false){
                if(reset){
                    this.quantity = 1;
                }
                document.getElementById("rt-custom-loader").style.display = "block";
                try{
                    axios.post(this.route('cart.add'),{product_id,quantity}).then(({data}) => {
                        document.getElementById("rt-custom-loader").style.display = "none";
                        toast(data.message,{"type": "success","autoClose": 3000,"transition": "slide"});
                        document.querySelector(".rtCartCount").innerHTML = data.itemCount;
                    });
                }catch(e){
                    document.getElementById("rt-custom-loader").style.display = "none";
                }
            }
        }
    }
</script>
<script setup>
    import axios from "axios";
    import FrontendLayout from '@/Layouts/FrontendLayout.vue';
    import {Head} from '@inertiajs/vue3';
    import {toast} from "vue3-toastify";
    import "vue3-toastify/dist/index.css";
    defineProps({
        product: {type: Object},
        relatedProducts: {type: Array}
    });
</script>
<template>
    <FrontendLayout>
        <Head>
            <title>{{product.name}}</title>
            <meta name="description" content="Kimia Product description">
        </Head>
        <section class="product-detail-section pad-100-15">
            <div class="container">
                <div class="row flex gap-70">
                    <div class="product-image-col">
                        <img :src="product.image" :alt="product.name"/>
                    </div>
                    <div class="product-content-col flex-1">
                        <h1 class="product-title mb-3">{{ product.name }}</h1>
                        <ul class="product-short-info list-style">
                            <li><strong>Price: </strong>{{(product.price).toLocaleString('en-US',{style:'currency',currency:'USD'})}}</li>
                            <li><strong>Catalog Number: </strong>{{ product.catalog_number }}</li>
                            <li><strong>CAS Number: </strong>{{ product.cas_number }}</li>
                        </ul>
                        <div class="single-product-add-cart flex gap-20 mt-3">
                            <div class="quantity-col">
                                <label>Quantity</label>  
                                <span class="quantity-field flex mt-1">
                                    <span class="decreament flex items-verticaly-center" @click="quantityChange(quantity,'decreament')"><i class="icon-minus"></i></span>
                                    <input id="quantity" type="text" :value="quantity" disabled>
                                    <span class="increament flex items-verticaly-center" @click="quantityChange(quantity,'increament')"><i class="icon-plus"></i></span>
                                </span>
                            </div>
                            <div class="button-cols flex gap-10">
                                <a class ="btn primary-btn" @click="addToCart(product.id,quantity,true)">Add to cart</a>
                                <a class ="btn secondary-btn custom-order-btn">Add custom order</a>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <h2 class="h2 mb-3">Full Specifications</h2>
                        <ul class="full-specifications list-style">
                            <li><strong>Catalog Number </strong>{{ product.catalog_number }}</li>
                            <li><strong>CAS Number: </strong>{{ product.cas_number }}</li>
                            <template v-if="product.specifications && product.specifications.length > 0">
                                <li v-for="(specification,index) in JSON.parse(product.specifications)" :key="index"><strong>{{specification.label}}: </strong>{{specification.value}}</li>
                            </template>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
        <section v-if="relatedProducts.length > 0" class="pad-100-15 related-product-section" style="background-image: url('/assets/images/background-image.jpg');background-size:cover;">
            <div class="container flex">
                <div class="row mb-5 text-center">
                    <h2 class="h2 mb-0 mt-0">Related Products</h2>
                </div>
                <div class="row flex gap-20">
                    <div v-for="item in relatedProducts" :key="item.id" class="card product-card flex-1">
                        <div class="product-feature-image flex text-center">
                            <a :href="route('productDetail',item.slug)"><img :src="item.image" alt="KMA0202"/></a>
                        </div>
                        <div class="product-content text-center">
                            <div class="sku mt-0 mb-1">{{ item.catalog_number }}</div>
                            <h4 class="product-title mt-0 mb-1"><a :href="route('productDetail',item.slug)">{{ item.name }}</a></h4>
                            <div class="btn-wrap">
                                <a class="btn tertory-btn" @click="addToCart(item.id,1)"><span class="btn-text">Add to cart </span><span class="btn-gradient"></span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </FrontendLayout>
</template>
<style>
    th{font-weight:600;text-transform:capitalize;letter-spacing:0.5px;
    color:var(--secondary-color)}
    table{border-spacing:0;border-collapse:collapse;}
    th, td{font-size:16px;line-height:normal;padding: 10px;}
    thead{background:#81489C1C;}
    thead tr{border-top:1px solid #81489C1C;}
    tbody tr{border-bottom:1px solid #81489C1C;}
    tr{ border-left:1px solid #81489C1C;border-right:1px solid #81489C1C;}
    .cart-thumbnail img{width:auto;height:65px;}
    .quantity-field input{max-width:45px;padding:8px;text-align:center;border:0px;outline:0px !important;border-left:1px solid #d09cea;border-right:1px solid #d09cea; }
    .quantity-field{border:1px solid #d09cea;border-radius:5px;gap:5px;}
    .increament, .decreament{color:var(--secondary-color);width:30px;justify-content:center;border-radius:5px;cursor:pointer;transition:0.3s all;}
    .delete-product{font-size:20px;cursor:pointer;color:var(--secondary-color);transition:0.3s all;}
    .delete-product:hover{color:var(--primary-color)}
    table .table-footer .input input{background:#f1ebf4 !important;border:0px !important;padding:14px;border-radius:6px;}
    .update-cart{text-align:right;}
    .cart-table .table-footer .btn{padding:14px 25px 12px;font-size:16px;border:0px !important;}
    .cart_total_col{max-width:350px;}
    .cart_total_table th{text-align:left;}
    .cart_total_col .h2{font-size:24px;line-height:30px;background:var(--secondary-color);color:#fff;padding:10px 20px;font-weight:500;letter-spacing:1px;}
    .checkout_btn .btn{width:100%;}
    .inner-banner.pad-60-15{padding:60px 15px;}
    .product-image-col{width:350px;border:1px solid #81489C1C;height:350px;padding:30px;border-radius:10px;}
    .product-detail-section{border-top:1px solid #81489C1C;}
    .product-image-col img{width:100%;height:100%;object-fit:contain;}
    .product-content-col{width:calc(100% - 420px);}
    .product-title{font-size:32px;color:var(--secondary-color);line-height:42px;}
    .list-style{margin:0px;padding:0px;}
    .list-style li strong{font-weight:500;color:var(--secondary-color);margin-right:10px;}
    .list-style li{margin-bottom:10px;}
    .single-product-add-cart{align-items:flex-end;}
    .single-product-add-cart .quantity-field input{min-height:52px;font-size:17px;min-width:60px;}
    .single-product-add-cart .increament, .single-product-add-cart .decreament{width:50px;}
    .full-specifications.list-style{background:#fafafa;}
    .full-specifications li{padding:11px 20px;margin:0px;}
    .full-specifications li:nth-child(2n +1 ){background:#f1ebf47d;}
    .full-specifications li strong{min-width:180px;display:inline-block;color:var(--dark-color);}
    .checkbox-wrap input{width:auto !important;margin:0px;}
    .flex.order-quantity{margin-top:5px;text-align:right;display:flex;justify-content:flex-end;gap:10px;}
    .order-col h2{font-size:26px;line-height:normal;}
    .col-30{width:30%;}
    .order-col{margin-top:-220px;position:relative;z-index:1;}
    .order-col .card{padding:30px;box-shadow:0px 0px 10px rgba(211, 173, 103, 0.19);margin:20px 0px;background-color:#fff;}
    .checkbox_field.privacy-policy-text, .checkbox_field.privacy-policy-text a{font-size:14px;line-height:normal;}
    .privacy-policy-text{align-items:flex-start;}
    .privacy-policy-text input{width:auto !important;margin:0px;margin-top:6px;}
    #order-detail tr{border-left:0px !important;border-right:0px !important;}
    .product-row a{display:block;font-size:16px !important;line-height:normal !important;font-weight:600 !important;color:var(--secondary-color);}
    #order-detail{width:100%;}
    .paypal-button a{padding:16px;border-radius:10px;display:flex;justify-content:center;background:#ffd140;}
    .paypal-button a img{height:20px;}
    .payment-methode-card .btn{width:100%;}
    .payment-option input{margin:0px !important;}
    .divider{position:relative;z-index:1;}
    .divider span{background:#fff;font-size:14px;padding:10px;}
    .divider::after{content:"";position:absolute;width:60%;height:1px;background:#9a9a9a;left:0;right:0;bottom:0;top:0;margin:auto;z-index:-1;}
    .product-row span{color:var(--text-color);}
    .product-row span.cross{margin-left:5px;}
    #order-detail tr:last-child{border:0px;}
    .related-product-section .product-card.flex-1{background:#fffbf2;}
    @media(max-width:1310px){
        .order-col{min-width:350px;}
        .single-product-add-cart.gap-20{gap:10px;row-gap:30px;}
        .product-detail-section .gap-70{gap:40px;}
        .product-image-col{height:320px;width:320px}
    }
    @media(max-width:1024px){
        .cart-table{width:100%;}
        .order-col{min-width:100%;margin:0px;width:100%;}
        .order-col .card{padding:20px 15px;}
        .order-col .card td, .order-col .card th{padding-left:0;padding-right:0px;}
        .product-image-col{height:220px;width:220px;padding:10px;}
        .single-product-add-cart .btn{padding:14px 15px 10px;font-size:16px;line-height:normal;}
        .single-product-add-cart .increament, .single-product-add-cart .decreament{width:34px;}
        .single-product-add-cart .quantity-field input{min-height:45px;font-size:16px;min-width:50px;padding:5px;}
        .card.product-card.flex-1{width:calc(100% / 2 - 10px);flex:unset;}
    }
    @media(max-width:767px){
        .cart-table thead{display:none;}
        .cart-table tr{border:1px solid #81489C1C;display:flex;flex-wrap:wrap;flex-direction:column;position:relative;margin-bottom:10px;border-radius:10px;}
        .cart-table tr td::before{content:attr(data-title);font-weight:600;color:var(--secondary-color);margin-right:10px;}
        .delete-product{font-size:17px;position:absolute;top:12px;right:10px;background:#81489C4F;border-radius:100%;}
        .quantity-field.flex{width:max-content;display:inline-flex;}
        .cart-table .table-footer{border:0px !important;text-align:left !important;padding-left:0px !important;}
        .cart-table  .table-footer .update-cart{text-align:left;padding-left:0px !important;}
        .cart-table  .table-footer .update-cart::before{display:none;}
        .product-content-col{width:100%;}
        .product-title{font-size:26px;line-height:35px;}
    }
    @media(max-width:600px){
        .column-w-600{flex-direction:column;}
    }
    @media (max-width:480px){
        .cart_total_col{max-width:100%;width:100%;}
        .cart_total_table{width:100%;}
        .full-specifications li strong{display:block;margin:0px;}
    }
    @media(max-width:380px){
        .card.product-card.flex-1{width:100%;}
    }
</style>