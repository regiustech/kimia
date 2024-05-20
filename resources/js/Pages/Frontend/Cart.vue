<script>
    import axios from "axios";
    export default {
        props: ['cart'],
        data(){
            return {
                cartItems: ((this.cart && this.cart.cartItems.length) ? this.cart.cartItems : []),
                subtotal: ((this.cart && this.cart.subtotal) ? this.cart.subtotal : 0),
                tax: ((this.cart && this.cart.tax) ? this.cart.tax : 0),
                total: ((this.cart && this.cart.total) ? this.cart.total : 0)
            }
        },
        methods: {
            quantityChange(quantity,type,index){
                if(type == 'increament' && quantity < 99){
                    this.cartItems[index].quantity = (parseInt(quantity) + parseInt(1));
                }else if(type == 'decreament' && quantity > 1){
                    this.cartItems[index].quantity = (parseInt(quantity) - parseInt(1));
                }
            },
            deleteItem(item_id){
                let $vm = this;
                document.getElementById("rt-custom-loader").style.display = "block";
                try{
                    axios.post(route('cart.remove'),{item_id}).then(({data}) => {
                        document.getElementById("rt-custom-loader").style.display = "none";
                        toast(data.message,{"type": "success","autoClose": 3000,"transition": "slide"});
                        document.querySelector(".rtCartCount").innerHTML = data.itemCount;
                        if(data.cart){
                            $vm.cartItems = data.cart.cartItems;
                            $vm.subtotal = data.cart.subtotal;
                            $vm.tax = data.cart.tax;
                            $vm.total = data.cart.total;
                        }else{
                            $vm.cartItems = [];
                            $vm.subtotal = 0;
                            $vm.tax = 0;
                            $vm.total = 0;
                        }
                    });
                }catch(e){
                    document.getElementById("rt-custom-loader").style.display = "none";
                }
            },
            updateCart(){
                let $vm = this;
                document.getElementById("rt-custom-loader").style.display = "block";
                try{
                    var items = [];
                    for(let cartItem of this.cartItems){
                        items.push({product_id: cartItem.product_id,product_variant_id: cartItem.product_variant_id,quantity: cartItem.quantity});
                    }
                    axios.post(route('cart.update'),{cart_id: this.cart.id,items: JSON.stringify(items)}).then(({data}) => {
                        document.getElementById("rt-custom-loader").style.display = "none";
                        toast(data.message,{"type": "success","autoClose": 3000,"transition": "slide"});
                        document.querySelector(".rtCartCount").innerHTML = data.itemCount;
                        if(data.cart){
                            $vm.cartItems = data.cart.cartItems;
                            $vm.subtotal = data.cart.subtotal;
                            $vm.tax = data.cart.tax;
                            $vm.total = data.cart.total;
                        }else{
                            $vm.cartItems = [];
                            $vm.subtotal = 0;
                            $vm.tax = 0;
                            $vm.total = 0;
                        }
                    });
                }catch(e){
                    document.getElementById("rt-custom-loader").style.display = "none";
                }
            }
        }
    }
</script>
<script setup>
    import FrontendLayout from '@/Layouts/FrontendLayout.vue';
    import {Head} from '@inertiajs/vue3';
    import {toast} from "vue3-toastify";
    import "vue3-toastify/dist/index.css";
</script>
<template>
    <FrontendLayout>
        <Head>
            <title>Cart &#8211; Kimia Corp.</title>
            <meta name="description" content="">
        </Head>
        <section class="inner-banner pad-60-15" style="background-image: url('/assets/images/about-banner.jpg');background-size:cover;">
            <div class="container flex text-center">
                <h1 class="mt-0 mb-0 row text-white">Cart</h1>
            </div>
        </section>
        <section class="pad-100-15">
            <div class="container column-w-600 flex gap-30" v-if="cartItems.length">
                <div class="col flex-1">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Product</th>
                                <th>Price</th>
                                <th class="qunitity-hd">Quantity</th>
                                <th>Subtotal</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(cartItem,cartItemIndex) in cartItems" :key="cartItem.id">
                                <td>
                                    <a :href="route('productDetail',cartItem.product.slug)" class="cart-thumbnail">
                                        <img :src="cartItem.product.image" :alt="cartItem.product.catalog_number">
                                    </a>
                                </td>
                                <td data-title="Product">{{cartItem.product.name}}</td>
                                <td data-title="Price">
                                    <span class="price" v-if="cartItem.product.product_type == 'regular'">{{(cartItem.product.price).toLocaleString('en-US',{style:'currency',currency:'USD'})}}</span>
                                    <span class="price" v-else-if="cartItem.product.product_type == 'variant'">{{(cartItem.product_variant.price).toLocaleString('en-US',{style:'currency',currency:'USD'})}}</span>
                                </td>
                                <td data-title="Quantity">
                                    <span class="rt-quantity-field">
                                        <span class="decreament" @click="quantityChange(cartItem.quantity,'decreament',cartItemIndex)"><i class="icon-minus"></i></span>
                                        <input type="text" :value="cartItem.quantity" disabled/>
                                        <span class="increament" @click="quantityChange(cartItem.quantity,'increament',cartItemIndex)"><i class="icon-plus"></i></span>
                                    </span>
                                </td>
                                <td data-title="Subtotal">
                                    <span class="subtotal" v-if="cartItem.product.product_type == 'regular'">{{(cartItem.product.price * cartItem.quantity).toLocaleString('en-US',{style:'currency',currency:'USD'})}}</span>
                                    <span class="subtotal" v-else-if="cartItem.product.product_type == 'variant'">{{(cartItem.product_variant.price * cartItem.quantity).toLocaleString('en-US',{style:'currency',currency:'USD'})}}</span>
                                </td>
                                <td class="text-center">
                                    <span class="delete-product" @click="deleteItem(cartItem.id)"><i class="icon-trash-empty"></i></span>
                                </td>
                            </tr>
                            <tr class="table-footer">
                                <td class="update-cart" colspan="6">
                                    <a class="btn tertory-btn" @click="updateCart"><span class="btn-text">Update Cart</span><span class="btn-gradient"></span></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="cart_total_col">
                    <div class="cart_total">
                        <h2 class="h2 mt-0  mb-0 text-white text-center">Cart Totals</h2>
                        <table class="cart_total_table">
                            <tbody>
                                <tr>
                                    <th>Subtotal</th>
                                    <td>{{(subtotal).toLocaleString('en-US',{style:'currency',currency:'USD'})}}</td>
                                </tr>
                                <tr>
                                    <th>Tax</th>
                                    <td>{{(tax).toLocaleString('en-US',{style:'currency',currency:'USD'})}}</td>
                                </tr>
                                <tr>
                                    <th>Total</th>
                                    <td><strong>{{(total).toLocaleString('en-US',{style:'currency',currency:'USD'})}}</strong></td>
                                </tr>
                                <tr class="table-footer">
                                    <td class="checkout_btn" colspan="2">
                                        <a class="btn secondary-btn  mt-1" href="/checkout"><span class="btn-text">Proceed to checkout</span></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="container rt-empty-cart" v-else>
                <div class="rt-icon">
                    <svg viewBox="0 0 511.728 511.728" width="200" height="200"><path d="M147.925 379.116c-22.357-1.142-21.936-32.588-.001-33.68 62.135.216 226.021.058 290.132.103 17.535 0 32.537-11.933 36.481-29.017l36.404-157.641c2.085-9.026-.019-18.368-5.771-25.629s-14.363-11.484-23.626-11.484c-25.791 0-244.716-.991-356.849-1.438L106.92 54.377c-4.267-15.761-18.65-26.768-34.978-26.768H15c-8.284 0-15 6.716-15 15s6.716 15 15 15h56.942a6.246 6.246 0 0 1 6.017 4.592l68.265 253.276c-12.003.436-23.183 5.318-31.661 13.92-8.908 9.04-13.692 21.006-13.471 33.695.442 25.377 21.451 46.023 46.833 46.023h21.872a52.18 52.18 0 0 0-5.076 22.501c0 28.95 23.552 52.502 52.502 52.502s52.502-23.552 52.502-52.502a52.177 52.177 0 0 0-5.077-22.501h94.716a52.185 52.185 0 0 0-5.073 22.493c0 28.95 23.553 52.502 52.502 52.502 28.95 0 52.503-23.553 52.503-52.502a52.174 52.174 0 0 0-5.464-23.285c5.936-1.999 10.216-7.598 10.216-14.207 0-8.284-6.716-15-15-15zm91.799 52.501c0 12.408-10.094 22.502-22.502 22.502s-22.502-10.094-22.502-22.502c0-12.401 10.084-22.491 22.483-22.501h.038c12.399.01 22.483 10.1 22.483 22.501zm167.07 22.494c-12.407 0-22.502-10.095-22.502-22.502 0-12.285 9.898-22.296 22.137-22.493h.731c12.24.197 22.138 10.208 22.138 22.493-.001 12.407-10.096 22.502-22.504 22.502zm74.86-302.233c.089.112.076.165.057.251l-15.339 66.425H414.43l8.845-67.023 58.149.234c.089.002.142.002.23.113zm-154.645 163.66v-66.984h53.202l-8.84 66.984zm-74.382 0-8.912-66.984h53.294v66.984zm-69.053 0h-.047c-3.656-.001-6.877-2.467-7.828-5.98l-16.442-61.004h54.193l8.912 66.984zm56.149-96.983-9.021-67.799 66.306.267v67.532zm87.286 0v-67.411l66.022.266-8.861 67.145zm-126.588-67.922 9.037 67.921h-58.287l-18.38-68.194zm237.635 164.905H401.63l8.84-66.984h48.973l-14.137 61.217a7.406 7.406 0 0 1-7.25 5.767z"/></svg>
                </div>
                <div class="rt-heading">Your Cart is empty</div>
                <div class="rt-para">Before proceed to checkout, you must add some products to your cart.</div>
                <a class="btn primary-btn" :href="route('home')">Go back to home</a>
            </div>
        </section>
    </FrontendLayout>
</template>
<style>
    th{font-weight:600;text-transform:capitalize;letter-spacing:0.5px;color:var(--secondary-color);}
    table{border-spacing:0;border-collapse:collapse;width:100%;max-width:950px;}
    th,
    td{font-size:16px;line-height:normal;padding:10px;}
    thead{background:#81489C1C;}
    thead tr{border-top:1px solid #81489C1C;}
    tbody tr{border-bottom:1px solid #81489C1C;}
    tr{border-left:1px solid #81489C1C;border-right:1px solid #81489C1C;}
    .cart-thumbnail img{width:auto;height:65px;max-width:150px;}
    .rt-quantity-field{border:1px solid #d09cea;border-radius:5px;display:flex;width:122px;box-sizing:border-box;}
    .rt-quantity-field input{max-width:50px;padding:6px;text-align:center;border:0px;outline:0px !important;border-left:1px solid #d09cea;border-right:1px solid #d09cea;}
    .rt-quantity-field .increament,
    .rt-quantity-field .decreament{color:var(--secondary-color);width:35px;border-radius:5px;cursor:pointer;transition:0.3s all;display:flex;align-items:center;justify-content:center;}
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
        .card.product-card.flex-1{width:calc(100% / 2 - 10px);flex:unset;}
    }
    @media(max-width:767px){
        .cart-table thead{display:none;}
        .cart-table tr{border:1px solid #81489C1C;display:flex;flex-wrap:wrap;flex-direction:column;position:relative;margin-bottom:10px;border-radius:10px;}
        .cart-table tr td::before{content:attr(data-title);font-weight:600;color:var(--secondary-color);margin-right:10px;}
        .delete-product{font-size:17px;position:absolute;top:12px;right:10px;background:#81489C4F;border-radius:100%;}
        .cart-table .table-footer{border:0px !important;text-align:left !important;padding-left:0px !important;}
        .cart-table .table-footer .update-cart{text-align:left;padding-left:0px !important;}
        .cart-table .table-footer .update-cart::before{display:none;}
        .product-content-col{width:100%;}
        .product-title{font-size:26px;line-height:35px;}
    }
    @media(max-width:600px){
        .column-w-600{flex-direction:column;}
    }
    @media (max-width:480px){
        .cart_total_col{max-width:100%;width:100%;}
        .cart_total_table{width:100%;}
    }
    @media(max-width:380px){
        .card.product-card.flex-1{width:100%;}
    }
</style>