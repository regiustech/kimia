<script>
    import axios from "axios";
    export default {
        props: ['user'],
        data(){
            return {
                form: {
                    first_name: this.user.first_name || "",
                    last_name: this.user.last_name || "",
                    email: this.user.email || "",
                    phone: this.user.phone || "",
                    company: this.user.company || "",
                    street_address: this.user.street_address || "",
                    city: this.user.city || "",
                    state: this.user.state || "",
                    zipcode: this.user.zipcode || "",
                    country: this.user.country || ""
                },
                errors: []
            }
        },
        methods: {
            submitForm: function(){
                if(!this.validate()){
                    return;
                }
                let $vm = this;
                document.getElementById("rt-custom-loader").style.display = "block";
                try{
                    axios.post($vm.route('profile.update'),$vm.form).then(({data}) => {
                        document.getElementById("rt-custom-loader").style.display = "none";
                        if(data.status == 200){
                            toast(data.message,{"type": "success","autoClose": 3000,"transition": "slide"});
                        }else{
                            toast(data.message,{"type": "error","autoClose": 3000,"transition": "slide"});
                        }
                    });
                }catch(e){
                    document.getElementById("rt-custom-loader").style.display = "none";
                }
            },
            validate: function(){
                const newError = {};
                let positionFocus = "";
                const emailRE = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                if(!this.form.first_name || !this.form.first_name.trim()){
                    newError["first_name"] = "Required";
                    positionFocus = positionFocus || "first_name";
                }
                if(!this.form.last_name || !this.form.last_name.trim()){
                    newError["last_name"] = "Required";
                    positionFocus = positionFocus || "last_name";
                }
                if(!this.form.email || !this.form.email.trim()){
                    newError["email"] = "Required";
                    positionFocus = positionFocus || "email";
                }else if(this.form.email && !emailRE.test(this.form.email)){
                    newError["email"] = "Enter a valid email";
                    positionFocus = positionFocus || "email";
                }
                if(this.form.phone && this.form.phone.length < 10){
                    newError["phone"] = "Invalid phone";
                    positionFocus = positionFocus || "phone";
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
    <Head title="Profile"/>
    <FrontendLayout>
        <section class="inner-banner pad-60-15" style="background-image: url('/assets/images/about-banner.jpg');">
            <div class="container flex text-center">
                <h1 class="mt-0 mb-0 row text-white">Profile</h1>
            </div>
        </section>
        <section class="pad-100-15 product-section">
            <div class="container">
                <form @submit.prevent="submitForm" class="rt-form">
                    <div class="rt-form-row">
                        <div class="rt-form-group">
                            <label for="first_name" class="label">First Name</label>
                            <input type="text" id="first_name" v-model="form.first_name" oninput="this.value = this.value.substring(0,50);"/>
                            <label class="rt-cust-error" v-if="hasValidationError(errors,'first_name')">{{ validationError(errors,'first_name') }}</label>
                        </div>
                        <div class="rt-form-group">
                            <label for="last_name" class="label">Last Name</label>
                            <input type="text" id="last_name" v-model="form.last_name" oninput="this.value = this.value.substring(0,50);"/>
                            <label class="rt-cust-error" v-if="hasValidationError(errors,'last_name')">{{ validationError(errors,'last_name') }}</label>
                        </div>
                        <div class="rt-form-group">
                            <label for="company" class="label">Company/Institution</label>
                            <input type="text" id="company" v-model="form.company" oninput="this.value = this.value.substring(0,50);"/>
                            <label class="rt-cust-error" v-if="hasValidationError(errors,'company')">{{ validationError(errors,'company') }}</label>
                        </div>
                        <div class="rt-form-group">
                            <label for="email" class="label">Email</label>
                            <input type="text" id="email" v-model="form.email" oninput="this.value = this.value.substring(0,50);"/>
                            <label class="rt-cust-error" v-if="hasValidationError(errors,'email')">{{ validationError(errors,'email') }}</label>
                        </div>
                        <div class="rt-form-group">
                            <label for="phone" class="label">Phone</label>
                            <input type="text" id="phone" v-model="form.phone" oninput="this.value = (this.value.replace(/[^0-9]/g,'')).substring(0,10);"/>
                            <label class="rt-cust-error" v-if="hasValidationError(errors,'phone')">{{ validationError(errors,'phone') }}</label>
                        </div>
                        <div class="rt-form-group">
                            <label for="street_address" class="label">Street address</label>
                            <input type="text" id="street_address" v-model="form.street_address" oninput="this.value = this.value.substring(0,150);"/>
                            <label class="rt-cust-error" v-if="hasValidationError(errors,'street_address')">{{ validationError(errors,'street_address') }}</label>
                        </div>
                        <div class="rt-form-group">
                            <label for="city" class="label">Town / City</label>
                            <input type="text" id="city" v-model="form.city" oninput="this.value = this.value.substring(0,50);"/>
                            <label class="rt-cust-error" v-if="hasValidationError(errors,'city')">{{ validationError(errors,'city') }}</label>
                        </div>
                        <div class="rt-form-group">
                            <label for="state" class="label">State</label>
                            <input type="text" id="state" v-model="form.state" oninput="this.value = this.value.substring(0,50);"/>
                            <label class="rt-cust-error" v-if="hasValidationError(errors,'state')">{{ validationError(errors,'state') }}</label>
                        </div>
                        <div class="rt-form-group">
                            <label for="zipcode" class="label">ZIP Code</label>
                            <input type="text" id="zipcode" v-model="form.zipcode" oninput="this.value = this.value.substring(0,50);"/>
                            <label class="rt-cust-error" v-if="hasValidationError(errors,'zipcode')">{{ validationError(errors,'zipcode') }}</label>
                        </div>
                        <div class="rt-form-group">
                            <label for="country" class="label">Country / Region</label>
                            <select id="country" v-model="form.country">
                                <option value="">Select</option>
                                <option v-for="(country,index) in countries" :key="index" :value="country.code">{{country.name}}</option>
                            </select>
                            <label class="rt-cust-error" v-if="hasValidationError(errors,'country')">{{ validationError(errors,'country') }}</label>
                        </div>
                    </div>
                    <div class="form-full-field form-button text-center">
                        <button class="btn secondary-btn" type="submit">Submit</button>
                    </div>
                </form>
            </div>
        </section>
    </FrontendLayout>
</template>
<style scoped>
    .pad-60-15{padding:60px 15px;}
    .rt-form{width:100%;display:flex;flex-direction:column;row-gap:20px;}
    .rt-form-row{width:100%;display:flex;flex:1;gap:20px;flex-wrap:wrap;}
    .rt-form-row .rt-form-group{width:100%;display:flex;flex-direction:column;max-width:calc(33.33% - 15px);}
    .rt-form-row .rt-form-group .label{font-size:16px;line-height:1;margin:0 0 5px;font-weight:600;}
    .rt-form-row .rt-form-group input{width:100%;border:1px solid #4848488c;padding:0 15px;border-radius:5px;outline:0px !important;font-size:16px;line-height:28px;font-weight:400;background:transparent;height:40px;}
    .rt-form-row .rt-form-group select{width:100%;border:1px solid #4848488c;padding:0 15px;border-radius:5px;outline:0px !important;font-size:16px;line-height:28px;font-weight:400;background:transparent;height:40px;cursor:pointer;}
    @media(max-width:767px){
        .rt-form-row .rt-form-group{max-width:calc(50% - 10px);}
    }
    @media(max-width:599px){
        .rt-form-row .rt-form-group{max-width:100%;}
    }
</style>