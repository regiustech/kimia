<script>
    export default {
        props: ['cart'],
        data(){
            let stripe = window.Stripe(import.meta.env.VITE_STRIPE_PUBLISHABLE_KEY);
            let user = this.$page.props.auth.user;
            return {
                stripe: stripe,
                cartItems: ((this.cart && this.cart.cartItems.length) ? this.cart.cartItems : []),
                subtotal: ((this.cart && this.cart.subtotal) ? this.cart.subtotal : 0),
                shipping_amount: ((this.cart && this.cart.shipping_amount) ? this.cart.shipping_amount : 0),
                tax_percent: ((this.cart && this.cart.tax_percent) ? this.cart.tax_percent : 0),
                tax: ((this.cart && this.cart.tax) ? this.cart.tax : 0),
                total: ((this.cart && this.cart.total) ? this.cart.total : 0),
                form: {
                    billing_name: ((user && user.first_name) ? user.first_name : ""),
                    billing_company: ((user && user.company) ? user.company : ""),
                    billing_email: ((user && user.email) ? user.email : ""),
                    billing_phone: ((user && user.phone) ? user.phone : ""),
                    billing_country: ((user && user.country) ? user.country : ""),
                    billing_street_address: ((user && user.street_address) ? user.street_address : ""),
                    billing_city: ((user && user.city) ? user.city : ""),
                    billing_state: ((user && user.state) ? user.state : ""),
                    billing_zipcode: ((user && user.zipcode) ? user.zipcode : ""),
                    same_as_billing: true,
                    shipping_name: "",
                    shipping_company: "",
                    shipping_country: "",
                    shipping_street_address: "",
                    shipping_city: "",
                    shipping_state: "",
                    shipping_zipcode: "",
                    additional_notes: "",
                    card_name: "",
                    policy: 0,
                    invoice: 0,
                },
                submitting: false,
                errors: []
            }
        },
        computed: {
            stripeElements(){
                return this.stripe.elements();
            }
        },
        mounted(){
            const style = {
                base: {
                    backgroundColor: "#FFF",
                    color: "#333",
                    fontFamily: 'Lato,sans-serif',
                    fontSize: "16px",
                    fontSmoothing: 'antialiased',
                    fontWeight: '400',
                    lineHeight: "45px",
                    iconColor: "#222",
                    "::placeholder": {
                        color: "#9b9b9b"
                    }
                },
                invalid: {
                    color: '#fa755a',
                    iconColor: '#fa755a'
                }
            };
            this.cardNumber = this.stripeElements.create('cardNumber',{style});
            this.cardNumber.mount('#card-number');
            this.cardExpiry = this.stripeElements.create('cardExpiry',{style});
            this.cardExpiry.mount('#card-expiry');
            this.cardCvc = this.stripeElements.create('cardCvc',{style});
            this.cardCvc.mount('#card-cvc');
        },
        beforeDestroy(){
            this.cardNumber.destroy();
            this.cardExpiry.destroy();
            this.cardCvc.destroy();
        },
        methods: {
            submitOrder: function(){
                if(this.submitting){
                    return;
                }
                if(!this.validate()){
                    return;
                }
                this.createToken();
            },
            validate: function(){
                const newError = {};
                let positionFocus = "";
                const emailRE = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                if(!this.form.billing_name || !this.form.billing_name.trim()){
                    newError["billing_name"] = "Required";
                    positionFocus = positionFocus || "billing_name";
                }else if(this.form.billing_name && this.form.billing_name.length > 100){
                    newError["billing_name"] = "Maximum 100 characters allowed";
                    positionFocus = positionFocus || "billing_name";
                }
                if(this.form.billing_company && this.form.billing_company.length > 50){
                    newError["billing_company"] = "Maximum 50 characters allowed";
                    positionFocus = positionFocus || "billing_company";
                }
                if(!this.form.billing_email || !this.form.billing_email.trim()){
                    newError["billing_email"] = "Required";
                    positionFocus = positionFocus || "billing_email";
                }else if(this.form.billing_email && this.form.billing_email.length > 50){
                    newError["billing_email"] = "Maximum 50 characters allowed";
                    positionFocus = positionFocus || "billing_email";
                }else if(this.form.billing_email && !emailRE.test(this.form.billing_email)){
                    newError["billing_email"] = "Enter a valid email";
                    positionFocus = positionFocus || "billing_email";
                }
                if(this.form.billing_phone && this.form.billing_phone.length > 10){
                    newError["billing_phone"] = "Maximum 10 characters allowed";
                    positionFocus = positionFocus || "billing_phone";
                }
                if(!this.form.billing_country || !this.form.billing_country.trim()){
                    newError["billing_country"] = "Required";
                    positionFocus = positionFocus || "billing_country";
                }else if(this.form.billing_country && this.form.billing_country.length > 100){
                    newError["billing_country"] = "Maximum 100 characters allowed";
                    positionFocus = positionFocus || "billing_country";
                }
                if(!this.form.billing_street_address || !this.form.billing_street_address.trim()){
                    newError["billing_street_address"] = "Required";
                    positionFocus = positionFocus || "billing_street_address";
                }else if(this.form.billing_street_address && this.form.billing_street_address.length > 250){
                    newError["billing_street_address"] = "Maximum 250 characters allowed";
                    positionFocus = positionFocus || "billing_street_address";
                }
                if(!this.form.billing_city || !this.form.billing_city.trim()){
                    newError["billing_city"] = "Required";
                    positionFocus = positionFocus || "billing_city";
                }else if(this.form.billing_city && this.form.billing_city.length > 100){
                    newError["billing_city"] = "Maximum 100 characters allowed";
                    positionFocus = positionFocus || "billing_city";
                }
                if(!this.form.billing_state || !this.form.billing_state.trim()){
                    newError["billing_state"] = "Required";
                    positionFocus = positionFocus || "billing_state";
                }else if(this.form.billing_state && this.form.billing_state.length > 100){
                    newError["billing_state"] = "Maximum 100 characters allowed";
                    positionFocus = positionFocus || "billing_state";
                }
                if(!this.form.billing_zipcode || !this.form.billing_zipcode.trim()){
                    newError["billing_zipcode"] = "Required";
                    positionFocus = positionFocus || "billing_zipcode";
                }else if(this.form.billing_zipcode && this.form.billing_zipcode.length > 10){
                    newError["billing_zipcode"] = "Maximum 10 characters allowed";
                    positionFocus = positionFocus || "billing_zipcode";
                }
                if(!this.form.same_as_billing){
                    if(!this.form.shipping_name || !this.form.shipping_name.trim()){
                        newError["shipping_name"] = "Required";
                        positionFocus = positionFocus || "shipping_name";
                    }else if(this.form.shipping_name && this.form.shipping_name.length > 100){
                        newError["shipping_name"] = "Maximum 100 characters allowed";
                        positionFocus = positionFocus || "shipping_name";
                    }
                    if(this.form.shipping_company && this.form.shipping_company.length > 50){
                        newError["shipping_company"] = "Maximum 50 characters allowed";
                        positionFocus = positionFocus || "shipping_company";
                    }
                    if(!this.form.shipping_country || !this.form.shipping_country.trim()){
                        newError["shipping_country"] = "Required";
                        positionFocus = positionFocus || "shipping_country";
                    }else if(this.form.shipping_country && this.form.shipping_country.length > 100){
                        newError["shipping_country"] = "Maximum 100 characters allowed";
                        positionFocus = positionFocus || "shipping_country";
                    }
                    if(!this.form.shipping_street_address || !this.form.shipping_street_address.trim()){
                        newError["shipping_street_address"] = "Required";
                        positionFocus = positionFocus || "shipping_street_address";
                    }else if(this.form.shipping_street_address && this.form.shipping_street_address.length > 250){
                        newError["shipping_street_address"] = "Maximum 250 characters allowed";
                        positionFocus = positionFocus || "shipping_street_address";
                    }
                    if(!this.form.shipping_city || !this.form.shipping_city.trim()){
                        newError["shipping_city"] = "Required";
                        positionFocus = positionFocus || "shipping_city";
                    }else if(this.form.shipping_city && this.form.shipping_city.length > 100){
                        newError["shipping_city"] = "Maximum 100 characters allowed";
                        positionFocus = positionFocus || "shipping_city";
                    }
                    if(!this.form.shipping_state || !this.form.shipping_state.trim()){
                        newError["shipping_state"] = "Required";
                        positionFocus = positionFocus || "shipping_state";
                    }else if(this.form.shipping_state && this.form.shipping_state.length > 100){
                        newError["shipping_state"] = "Maximum 100 characters allowed";
                        positionFocus = positionFocus || "shipping_state";
                    }
                    if(!this.form.shipping_zipcode || !this.form.shipping_zipcode.trim()){
                        newError["shipping_zipcode"] = "Required";
                        positionFocus = positionFocus || "shipping_zipcode";
                    }else if(this.form.shipping_zipcode && this.form.shipping_zipcode.length > 10){
                        newError["shipping_zipcode"] = "Maximum 10 characters allowed";
                        positionFocus = positionFocus || "shipping_zipcode";
                    }
                }
                if(this.form.additional_notes && this.form.additional_notes.length > 500){
                    newError["additional_notes"] = "Maximum 500 characters allowed";
                    positionFocus = positionFocus || "additional_notes";
                }
                if(!this.form.card_name || !this.form.card_name.trim()){
                    newError["card_name"] = "Required";
                    positionFocus = positionFocus || "card_name";
                }else if(this.form.card_name && this.form.card_name.length > 100){
                    newError["card_name"] = "Maximum 100 characters allowed";
                    positionFocus = positionFocus || "card_name";
                }
                if(this.form.policy == 0){
                    newError["policy"] = "Required";
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
            async createToken(){
                const {error,token} = await this.stripe.createToken(this.cardNumber,{
                    name: this.form.card_name,
                    address_line1: this.form.billing_street_address,
                    address_city: this.form.billing_city,
                    address_state: this.form.billing_state,
                    address_zip: this.form.billing_zipcode,
                    address_country: "US"
                });
                if(error){
                    const newError = {};
                    if(error.code.includes("number")){
                        newError["card_number"] = error.message;
                    }
                    if(error.code.includes("expiry")){
                        newError["card_expiry"] = error.message;
                    }
                    if(error.code.includes("cvc")){
                        newError["card_cvc"] = error.message;
                    }
                    this.errors = newError;
                    return;
                }
                this.makeOrder(token);
            },
            makeOrder(token){
                let $vm = this;
                $vm.form.token = token.id;
                document.getElementById("rt-custom-loader").style.display = "block";
                this.$inertia.post($vm.route('checkout.order'),$vm.form,{
                    onFinish: () => document.getElementById("rt-custom-loader").style.display = "none"
                });
            },
            onPolicyChange(){
                this.form.policy = ((this.form.policy == "1") ? "0" : "1");
            },
            onInvoiceChange(){
                this.form.invoice = ((this.form.invoice == "1") ? "0" : "1");
            }
        }
    }
</script>
<script setup>
    import FrontendLayout from '@/Layouts/FrontendLayout.vue';
    import countries from '@/countries.json';
    import {Head} from '@inertiajs/vue3';
    import {toast} from "vue3-toastify";
    import "vue3-toastify/dist/index.css";
</script>
<template>
    <FrontendLayout>
        <Head>
            <title>Checkout &#8211; Kimia Corp.</title>
            <meta name="description" content="Contact Kimia Corp. today for more information!">
        </Head>
        <section class="inner-banner pad-60-15" style="background-image: url('/assets/images/about-banner.jpg');background-size:cover;">
            <div class="container flex">
                <h1 class="mt-0 row text-white text-center-tab">Checkout</h1>
            </div>
        </section>
        <section class="pad-100-15">
            <div class="container flex-column-tab  flex flex-1 gap-70">
                <div class="flex-1 form-col">
                    <div class="row mb-3">
                        <h4 class="h4 mt-0">Billing Details</h4>
                    </div>
                    <div class="form-wrapper">
                        <div class="flex gap-20">
                            <div class="form-half-field form-field">
                                <label for="billing_name">Name</label>
                                <input type="text" id="billing_name" v-model="form.billing_name" placeholder="Enter your name" oninput="this.value = this.value.substring(0,50);"/>
                                <label class="rt-cust-error" v-if="hasValidationError(errors,'billing_name')">{{ validationError(errors,'billing_name') }}</label>
                            </div>
                            <div class="form-half-field form-field">
                                <label for="billing_company">Company/Institution</label>
                                <input type="text" id="billing_company" v-model="form.billing_company" placeholder="Company/Institution" oninput="this.value = this.value.substring(0,50);"/>
                                <label class="rt-cust-error" v-if="hasValidationError(errors,'billing_company')">{{ validationError(errors,'billing_company') }}</label>
                            </div>
                            <div class="form-half-field form-field">
                                <label for="billing_email">Email</label>
                                <input type="text" id="billing_email" v-model="form.billing_email" placeholder="Enter email" oninput="this.value = this.value.substring(0,50);"/>
                                <label class="rt-cust-error" v-if="hasValidationError(errors,'billing_email')">{{ validationError(errors,'billing_email') }}</label>
                            </div>
                            <div class="form-half-field form-field">
                                <label for="billing_phone">Phone</label>
                                <input type="text" id="billing_phone" v-model="form.billing_phone" placeholder="Enter phone number" oninput="this.value = (this.value.replace(/[^0-9]/g,'')).substring(0,10);"/>
                                <label class="rt-cust-error" v-if="hasValidationError(errors,'billing_phone')">{{ validationError(errors,'billing_phone') }}</label>
                            </div>
                            <div class="form-full-field form-field">
                                <label for="billing_street_address">Street address *</label>
                                <input type="text" id="billing_street_address" v-model="form.billing_street_address" placeholder="Street address" oninput="this.value = this.value.substring(0,150);"/>
                                <label class="rt-cust-error" v-if="hasValidationError(errors,'billing_street_address')">{{ validationError(errors,'billing_street_address') }}</label>
                            </div>
                            <div class="form-half-field form-field">
                                <label for="billing_city">Town / City *</label>
                                <input type="text" id="billing_city" v-model="form.billing_city" placeholder="Town / City" oninput="this.value = this.value.substring(0,50);"/>
                                <label class="rt-cust-error" v-if="hasValidationError(errors,'billing_city')">{{ validationError(errors,'billing_city') }}</label>
                            </div>
                            <div class="form-half-field form-field">
                                <label for="billing_state">State *</label>
                                <input type="text" id="billing_state" v-model="form.billing_state" placeholder="State" oninput="this.value = this.value.substring(0,50);"/>
                                <label class="rt-cust-error" v-if="hasValidationError(errors,'billing_state')">{{ validationError(errors,'billing_state') }}</label>
                            </div>
                            <div class="form-half-field form-field">
                                <label for="billing_zipcode">ZIP Code *</label>
                                <input type="text" id="billing_zipcode" v-model="form.billing_zipcode" placeholder="ZIP Code" oninput="this.value = this.value.substring(0,10);"/>
                                <label class="rt-cust-error" v-if="hasValidationError(errors,'billing_zipcode')">{{ validationError(errors,'billing_zipcode') }}</label>
                            </div>
                            <div class="form-half-field form-field">
                                <label for="billing_country">Country / Region *</label>
                                <select id="billing_country" v-model="form.billing_country">
                                    <option value="">Select</option>
                                    <option v-for="(country,index) in countries" :key="index" :value="country.code">{{country.name}}</option>
                                </select>
                                <label class="rt-cust-error" v-if="hasValidationError(errors,'billing_country')">{{ validationError(errors,'billing_country') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-5 mb-3">
                        <h4 class="h4 mt-0">Shipping Details</h4>
                    </div>
                    <div class="form-wrapper mb-4">
                        <div class="flex gap-20">
                            <div class="form-full-field form-field rt-checkbox-field">
                                <input type="checkbox" id="same_as_billing" v-model="form.same_as_billing">
                                <label for="same_as_billing">Same as Billing Details</label>
                            </div>
                            <div class="form-half-field form-field" v-if="!form.same_as_billing">
                                <label for="shipping_name">Name</label>
                                <input type="text" id="shipping_name" v-model="form.shipping_name" placeholder="Enter your name" oninput="this.value = this.value.substring(0,50);"/>
                                <label class="rt-cust-error" v-if="hasValidationError(errors,'shipping_name')">{{ validationError(errors,'shipping_name') }}</label>
                            </div>
                            <div class="form-half-field form-field" v-if="!form.same_as_billing">
                                <label for="shipping_company">Company/Institution</label>
                                <input type="text" id="shipping_company" v-model="form.shipping_company" placeholder="Company/Institution" oninput="this.value = this.value.substring(0,50);"/>
                                <label class="rt-cust-error" v-if="hasValidationError(errors,'shipping_company')">{{ validationError(errors,'shipping_company') }}</label>
                            </div>
                            <div class="form-full-field form-field" v-if="!form.same_as_billing">
                                <label for="shipping_street_address">Street address *</label>
                                <input type="text" id="shipping_street_address" v-model="form.shipping_street_address" placeholder="Street address" oninput="this.value = this.value.substring(0,150);"/>
                                <label class="rt-cust-error" v-if="hasValidationError(errors,'shipping_street_address')">{{ validationError(errors,'shipping_street_address') }}</label>
                            </div>
                            <div class="form-half-field form-field" v-if="!form.same_as_billing">
                                <label for="shipping_city">Town / City *</label>
                                <input type="text" id="shipping_city" v-model="form.shipping_city" placeholder="Town / City" oninput="this.value = this.value.substring(0,50);"/>
                                <label class="rt-cust-error" v-if="hasValidationError(errors,'shipping_city')">{{ validationError(errors,'shipping_city') }}</label>
                            </div>
                            <div class="form-half-field form-field" v-if="!form.same_as_billing">
                                <label for="shipping_state">State *</label>
                                <input type="text" id="shipping_state" v-model="form.shipping_state" placeholder="State" oninput="this.value = this.value.substring(0,50);"/>
                                <label class="rt-cust-error" v-if="hasValidationError(errors,'shipping_state')">{{ validationError(errors,'shipping_state') }}</label>
                            </div>
                            <div class="form-half-field form-field" v-if="!form.same_as_billing">
                                <label for="shipping_zipcode">ZIP Code *</label>
                                <input type="text" id="shipping_zipcode" v-model="form.shipping_zipcode" placeholder="ZIP Code" oninput="this.value = this.value.substring(0,10);"/>
                                <label class="rt-cust-error" v-if="hasValidationError(errors,'shipping_zipcode')">{{ validationError(errors,'shipping_zipcode') }}</label>
                            </div>
                            <div class="form-half-field form-field" v-if="!form.same_as_billing">
                                <label for="shipping_country">Country / Region *</label>
                                <select id="shipping_country" v-model="form.shipping_country">
                                    <option value="">Select</option>
                                    <option v-for="(country,index) in countries" :key="index" :value="country.code">{{country.name}}</option>
                                </select>
                                <label class="rt-cust-error" v-if="hasValidationError(errors,'shipping_country')">{{ validationError(errors,'shipping_country') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-wrapper">
                        <div class="flex gap-20">
                            <div class="form-full-field form-field">
                                <label for="additional_notes">Additional Notes</label>
                                <textarea id="additional_notes" v-model="form.additional_notes" cols="50" placeholder="Additional Notes"></textarea>
                                <label class="rt-cust-error" v-if="hasValidationError(errors,'additional_notes')">{{ validationError(errors,'additional_notes') }}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-30 order-col">
                    <div class="card flex flex-column-tab">
                        <h2 class="h3 mt-0 mb-0">Your Order</h2>
                        <table id="order-detail" class="cart_total_table">
                            <tbody>
                                <tr class="product-row" v-for="cartItem in cartItems" :key="cartItem.id">
                                    <td colspan="2">
                                        <a :href="route('productDetail',cartItem.product.slug)">{{cartItem.product.name}}<span class="cross">x</span> <span class="product-qty">{{cartItem.quantity}}</span></a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Subtotal</th>
                                    <td>{{(subtotal).toLocaleString('en-US',{style:'currency',currency:'USD'})}}</td>
                                </tr>
                                <tr>
                                    <th>Shipping</th>
                                    <td>{{(shipping_amount).toLocaleString('en-US',{style:'currency',currency:'USD'})}}</td>
                                </tr>
                                <tr>
                                    <th>Tax{{ (tax_percent ? " ("+tax_percent+"%)" : "") }}</th>
                                    <td>{{(tax).toLocaleString('en-US',{style:'currency',currency:'USD'})}}</td>
                                </tr>
                                <tr>
                                    <th>Total</th>
                                    <td><strong>{{(total).toLocaleString('en-US',{style:'currency',currency:'USD'})}}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card payment-methode-card flex flex-column-tab gap-20">
                        <h2 class="h3 mt-0 mb-0">Payment Details</h2>
                        <div class="flex gap-20 card-detail payment-type">
                            <div class="form-full-field form-field">
                                <label for="card-name">Name on card</label>
                                <input type="text" v-model="form.card_name" id="card-name" placeholder="Name on card" style="padding:0 12px;height:45px;font-size:16px;">
                                <label class="rt-cust-error" v-if="hasValidationError(errors,'card_name')">{{ validationError(errors,'card_name') }}</label>
                            </div>
                            <div class="form-full-field form-field">
                                <label for="card-number">Card number</label>
                                <div id="card-number" style="border:1px solid #4848488c;padding:0 12px;border-radius:5px;box-sizing:border-box;"></div>
                                <label class="rt-cust-error" v-if="hasValidationError(errors,'card_number')">{{ validationError(errors,'card_number') }}</label>
                            </div>
                            <div class="form-half-field form-field">
                                <label for="card-expiry">Card Expire Date</label>
                                <div id="card-expiry" style="border:1px solid #4848488c;padding:0 12px;border-radius:5px;box-sizing:border-box;"></div>
                                <label class="rt-cust-error" v-if="hasValidationError(errors,'card_expiry')">{{ validationError(errors,'card_expiry') }}</label>
                            </div>
                            <div class="form-half-field form-field">
                                <label for="card-cvc">Card CVV</label>
                                <div id="card-cvc" style="border:1px solid #4848488c;padding:0 12px;border-radius:5px;box-sizing:border-box;"></div>
                                <label class="rt-cust-error" v-if="hasValidationError(errors,'card_cvc')">{{ validationError(errors,'card_cvc') }}</label>
                            </div>
                            <div id="card-error" class="rt-cust-error"></div>
                        </div>
                        <div class="form-full-field form-field">
                            <div class="checkbox_field privacy-policy-text flex gap-10 wrap-unset">
                                <input type ="checkbox" id="agree_withi_policies" @change="onPolicyChange" :checked="(form.policy == 1)">
                                <label for="agree_withi_policies">Your personal data will be used to process your order, support your experience throughout this website, and for other purposes described in our <a href="#">privacy policy</a>.</label>
                            </div>
                            <label class="rt-cust-error" v-if="hasValidationError(errors,'policy')">{{ validationError(errors,'policy') }}</label>
                            <div class="checkbox_field privacy-policy-text flex gap-10 wrap-unset">
                                <input type ="checkbox" id="invoice" @change="onInvoiceChange" :checked="(form.invoice == 1)">
                                <label for="agree_withi_policies">Invoice me</label>
                            </div>
                            <div class="form-full-field mt-1">
                                <button class="btn secondary-btn flex-1 mt-1" @click="submitOrder"><span class="btn-text">Place your order</span></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </FrontendLayout>
</template>
<style>
    th{font-weight:600;text-transform:capitalize;letter-spacing:0.5px;color:var(--secondary-color);}
    table{border-spacing:0;border-collapse:collapse;width:100%;max-width:950px;}
    th,
    td{font-size:16px;line-height:1.2;padding:10px;}
    thead{background:#81489C1C;}
    thead tr{border-top:1px solid #81489C1C;}
    tbody tr{border-bottom:1px solid #81489C1C;}
    tr{border-left:1px solid #81489C1C;border-right:1px solid #81489C1C;}
    .cart_total_table th{text-align:left;}
    .inner-banner.pad-60-15{padding:60px 15px;}
    #order-detail{width:100%;}
    #order-detail tr{border-left:0px !important;border-right:0px !important;}
    #order-detail tr:last-child{border:0px;}
    .order-col{margin-top:-220px;position:relative;z-index:1;}
    .order-col h2{font-size:26px;line-height:normal;}
    .order-col .card{padding:30px;box-shadow:0px 0px 10px rgba(211,173,103,0.19);margin:20px 0px;background-color:#fff;}
    .col-30{width:30%;}
    .checkbox_field.privacy-policy-text,
    .checkbox_field.privacy-policy-text a{font-size:14px;line-height:normal;}
    .privacy-policy-text{align-items:flex-start;}
    .privacy-policy-text input{width:auto !important;margin:0px;margin-top:6px;}
    .product-row a{display:block;font-size:16px !important;line-height:normal !important;font-weight:600 !important;color:var(--secondary-color);}
    .product-row span{color:var(--text-color);}
    .product-row span.cross{margin-left:5px;}
    .payment-methode-card .btn{width:100%;}
    .rt-checkbox-field{display:flex;align-items:center;column-gap:10px;}
    .rt-checkbox-field input{width:20px;height:20px;}

    @media(max-width:1310px){
        .order-col{min-width:350px;}
    }
    @media(max-width:1024px){
        .order-col{min-width:100%;margin:0px;width:100%;}
        .order-col .card{padding:20px 15px;}
        .order-col .card td,
        .order-col .card th{padding-left:0;padding-right:0;}
    }
    @media (max-width:480px){
        .cart_total_table{width:100%;}
    }
</style>