<script>
    export default {
        props: ['product','relatedProducts'],
        data(){
            return {
                product_variant_id: "",
                quantity: 1,
                showPopup: false,
                form: {
                    product_id: this.product.id,
                    product_name: this.product.name,
                    name: "",
                    email: "",
                    msg: ""
                },
                errors: []
            }
        },
        methods: {
            handleOrderPopup(status){
                this.showPopup = status;
                this.form.name = "";
                this.form.email = "";
                this.form.msg = "";
            },
            submitForm: function(){
                if(!this.validate()){
                    return;
                }
                this.submitCustomOrder();
            },
            validate: function(){
                const newError = {};
                let positionFocus = "";
                const emailRE = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                if(!this.form.name || !this.form.name.trim()){
                    newError["name"] = "Required";
                    positionFocus = positionFocus || "name";
                }
                if(!this.form.email || !this.form.email.trim()){
                    newError["email"] = "Required";
                    positionFocus = positionFocus || "email";
                }else if(this.form.email && !emailRE.test(this.form.email)){
                    newError["email"] = "Enter a valid email";
                    positionFocus = positionFocus || "email";
                }
                if(!this.form.msg || !this.form.msg.trim()){
                    newError["msg"] = "Required";
                    positionFocus = positionFocus || "msg";
                }
                this.errors = newError;
                if(positionFocus){
                    if(document.getElementById(positionFocus)){
                        let textbox = document.getElementById(positionFocus);
                        if(textbox){
                            textbox.focus();
                        }
                    }
                    return false;
                }
                return true;
            },
            getPriceByUnit(prd){
                let pVariant = prd.productVariants.find((v) => v.id == this.product_variant_id);
                if(pVariant){
                    return pVariant.price ? (pVariant.price).toLocaleString('en-US',{style:'currency',currency:'USD'}) : null;
                }
                return;
            },
            quantityChange(qty,type){
                if(type == 'increament' && qty < 99){
                    this.quantity = (parseInt(qty) + parseInt(1));
                }else if(type == 'decreament' && qty > 1){
                    this.quantity = (parseInt(qty) - parseInt(1));
                }
            },
            addToCart(prdId,quantity = 1,prdType,variantId){
                if(prdType == "variant" && !variantId){
                    toast("Please select Unit",{"type": "error","autoClose": 3000,"transition": "slide"});
                    return;
                }
                this.quantity = 1;
                this.product_variant_id = "";
                document.getElementById("rt-custom-loader").style.display = "block";
                try{
                    axios.post(this.route('cart.add'),{product_id: prdId,quantity,product_variant_id: variantId}).then(({data}) => {
                        document.getElementById("rt-custom-loader").style.display = "none";
                        toast(data.message,{"type": "success","autoClose": 3000,"transition": "slide"});
                        document.querySelector(".rtCartCount").innerHTML = data.itemCount;
                    });
                }catch(e){
                    document.getElementById("rt-custom-loader").style.display = "none";
                }
            },
            submitCustomOrder(){
                let $vm = this;
                document.getElementById("rt-custom-loader").style.display = "block";
                try{
                    axios.post($vm.route('custom-order'),$vm.form).then(({data}) => {
                        document.getElementById("rt-custom-loader").style.display = "none";
                        $vm.handleOrderPopup(false);
                        toast(data.message,{"type": "success","autoClose": 3000,"transition": "slide"});
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
</script>
<template>
    <div class="popup-wrapper" v-if="showPopup">
        <div class="custom-order">
            <span class="close-btn" @click="handleOrderPopup(false)"><i class="icon-plus"></i></span>
            <div class="form-wrapper">
                <form @submit.prevent="submitForm">
                    <div class="flex gap-20">
                        <div class="form-full-field form-field">
                            <label for="product_name">Product Name</label>               
                            <input type="text" id="product_name" placeholder="Enter your name" :value="product.name" disabled/>
                        </div>
                        <div class="form-half-field form-field">
                            <label for="name"> Name</label>               
                            <input type="text" id="name" v-model="form.name" placeholder="Enter your name" oninput="this.value = this.value.substring(0,50);"/>
                            <label class="rt-cust-error" v-if="hasValidationError(errors,'name')">{{ validationError(errors,'name') }}</label>
                        </div>
                        <div class="form-half-field form-field">
                            <label for="email">Email</label>
                            <input type="email" id="email" v-model="form.email" placeholder="Enter email" oninput="this.value = this.value.substring(0,50);"/>
                            <label class="rt-cust-error" v-if="hasValidationError(errors,'email')">{{ validationError(errors,'email') }}</label>
                        </div>
                        <div class="form-full-field form-field">
                            <label for="msg">Message</label>
                            <textarea id="msg" v-model="form.msg" oninput="this.value = this.value.substring(0,200);" cols="50" placeholder="Tell Us More"></textarea>
                            <label class="rt-cust-error" v-if="hasValidationError(errors,'msg')">{{ validationError(errors,'msg') }}</label>
                        </div>
                        <div class="form-full-field form-button text-center">
                            <button class="btn secondary-btn row" type="submit">Send</button>
                        </div> 
                    </div>
                </form>
            </div>
        </div>
    </div>
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
                            <li><strong>Category:</strong>{{ ucwords(product.category) }}</li>
                            <li v-if="product.product_type == 'regular'"><strong>Price:</strong>{{ (product.price).toLocaleString('en-US',{style:'currency',currency:'USD'}) }}</li>
                            <li v-if="product.product_type == 'variant' && product_variant_id"><strong>Price:</strong>{{ getPriceByUnit(product)}}</li>
                            <li><strong>Catalog Number:</strong>{{ product.catalog_number }}</li>
                            <li><strong>CAS Number:</strong>{{ product.cas_number }}</li>
                        </ul>
                        <div class="single-product-add-cart flex gap-20 mt-3">
                            <div class="unit-col" v-if="product.product_type == 'variant'">
                                <label>Unit</label>
                                <select v-model="product_variant_id">
                                    <option value="">Select</option>
                                    <option v-for="(pVariant,index) in product.productVariants" :key="index" :value="pVariant.id">{{pVariant.variant_detail.name}}</option>
                                </select>
                            </div>
                            <div class="quantity-col">
                                <label>Quantity</label>  
                                <span class="quantity-field flex mt-1">
                                    <span class="decreament flex items-verticaly-center" @click="quantityChange(quantity,'decreament')"><i class="icon-minus"></i></span>
                                    <input id="quantity" type="text" :value="quantity" disabled>
                                    <span class="increament flex items-verticaly-center" @click="quantityChange(quantity,'increament')"><i class="icon-plus"></i></span>
                                </span>
                            </div>
                            <div class="button-cols flex gap-10">
                                <a class ="btn primary-btn" @click="addToCart(product.id,quantity,product.product_type,product_variant_id)">Add to cart</a>
                                <a @click="handleOrderPopup(true)" class ="btn secondary-btn custom-order-btn">Add custom order</a>
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
                    <div v-for="item in relatedProducts" :key="item.id" class="card product-card">
                        <div class="product-feature-image flex text-center">
                            <a :href="route('productDetail',item.slug)"><img :src="item.image" alt="KMA0202"/></a>
                        </div>
                        <div class="product-content text-center">
                            <div class="sku mt-0 mb-1">{{ item.catalog_number }}</div>
                            <h4 class="product-title mt-0 mb-1"><a :href="route('productDetail',item.slug)">{{ item.name }}</a></h4>
                            <div class="btn-wrap">
                                <a class="btn tertory-btn" :href="route('productDetail',item.slug)"><span class="btn-text">View Detail</span><span class="btn-gradient"></span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </FrontendLayout>
</template>
<style>
    .unit-col{display:flex;flex-direction:column;}
    .unit-col select{width:100px;padding:8px 15px;border-radius:5px;outline:0 !important;border:1px solid #d09cea;}
    .quantity-field{gap:0 !important;}
    .quantity-field input{max-width:45px;padding:8px;text-align:center;border:0px;outline:0px !important;border-left:1px solid #d09cea;border-right:1px solid #d09cea;}
    .quantity-field{border:1px solid #d09cea;border-radius:5px;gap:5px;}
    .increament,
    .decreament{color:var(--secondary-color);width:44px !important;justify-content:center;border-radius:5px;cursor:pointer;transition:0.3s all;}
    .button-cols .btn{line-height:1;padding:0 20px;height:46px;display:flex;align-items:center;justify-content:center;}
    .product-image-col{width:350px;border:1px solid #81489C1C;height:350px;padding:30px;border-radius:10px;}
    .product-detail-section{border-top:1px solid #81489C1C;}
    .product-image-col img{width:100%;height:100%;object-fit:contain;}
    .product-content-col{width:calc(100% - 420px);}
    .product-title{font-size:32px;color:var(--secondary-color);line-height:42px;}
    .list-style{margin:0px;padding:0px;}
    .list-style li strong{font-weight:500;color:var(--secondary-color);margin-right:10px;}
    .list-style li{margin-bottom:10px;}
    .single-product-add-cart{align-items:flex-end;}
    .single-product-add-cart .increament,
    .single-product-add-cart .decreament{width:50px;}
    .full-specifications.list-style{background:#fafafa;}
    .full-specifications li{padding:11px 20px;margin:0px;}
    .full-specifications li:nth-child(2n +1 ){background:#f1ebf47d;}
    .full-specifications li strong{min-width:180px;display:inline-block;color:var(--dark-color);}
    .related-product-section .product-card.flex-1{background:#fffbf2;}
    @media(max-width:1310px){
        .single-product-add-cart.gap-20{gap:10px;row-gap:30px;}
        .product-detail-section .gap-70{gap:40px;}
        .product-image-col{height:320px;width:320px}
    }
    @media(max-width:1024px){
        .product-image-col{height:220px;width:220px;padding:10px;}
        .single-product-add-cart .btn{padding:14px 15px 10px;font-size:16px;line-height:normal;}
        .single-product-add-cart .increament,
        .single-product-add-cart .decreament{width:34px;}
        .single-product-add-cart .quantity-field input{min-height:45px;font-size:16px;min-width:50px;padding:5px;}
        .card.product-card.flex-1{width:calc(100% / 2 - 10px);flex:unset;}
    }
    @media(max-width:767px){
        .quantity-field.flex{width:max-content;display:inline-flex;}
        .product-content-col{width:100%;}
        .product-title{font-size:26px;line-height:35px;}
    }
    @media (max-width:480px){
        .full-specifications li strong{display:block;margin:0px;}
    }
    @media(max-width:380px){
        .card.product-card.flex-1{width:100%;}
    }
</style>