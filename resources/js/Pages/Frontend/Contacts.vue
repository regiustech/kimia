<script>
    import axios from "axios";
    export default {
        data(){
            return {
                form: {
                    name: "",
                    company: "",
                    email: "",
                    phone: "",
                    subject: "",
                    msg: ""
                },
                errors: []
            }
        },
        methods: {
            submitContacts: function(){
                if(this.submitting){
                    return;
                }
                if(!this.validate()){
                    return;
                }
                this.sendContacts();
            },
            validate: function(){
                const newError = {};
                let positionFocus = "";
                const emailRE = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                if(!this.form.name || !this.form.name.trim()){
                    newError["name"] = "Required";
                    positionFocus = positionFocus || "name";
                }
                if(!this.form.company || !this.form.company.trim()){
                    newError["company"] = "Required";
                    positionFocus = positionFocus || "company";
                }
                if(!this.form.email || !this.form.email.trim()){
                    newError["email"] = "Required";
                    positionFocus = positionFocus || "email";
                }else if(this.form.email && !emailRE.test(this.form.email)){
                    newError["email"] = "Enter a valid email";
                    positionFocus = positionFocus || "email";
                }
                if(!this.form.phone || !this.form.phone.trim()){
                    newError["phone"] = "Required";
                    positionFocus = positionFocus || "phone";
                }
                if(!this.form.subject || !this.form.subject.trim()){
                    newError["subject"] = "Required";
                    positionFocus = positionFocus || "subject";
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
            sendContacts: function(){
                let $vm = this;
                document.getElementById("rt-custom-loader").style.display = "block";
                try{
                    axios.post($vm.route('contacts.send'),$vm.form).then(({data}) => {
                        document.getElementById("rt-custom-loader").style.display = "none";
                        toast(data.message,{"type": "success","autoClose": 3000,"transition": "slide"});
                        $vm.form = {name: "",company: "",email: "",phone: "",subject: "",msg: ""}
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
            <title>Contact</title>
            <meta name="description" content="Kimia description">
        </Head>
        <section class="inner-banner" style="background-image: url('/assets/images/about-banner.jpg');">
            <div class="container  flex text-center">
                <span class="banner-subheading mb-2 row"> Keep In Touch </span>
                <h1 class="mt-0 mb-0 row text-white"> Contact Us</h1>       
            </div>
        </section>
        <section class="pad-100-15 services-section">
            <div class="container flex gap-70">
                <div class="col-50 information-col">
                    <h2 class="h2 mb-2 mt-0"> Ask Us Anything. Anytime. </h2>
                    <div class="info flex gap-20 mt-2">
                        <a class="flex gap-10 items-verticaly-center" href="tel:4087481046" target="_blank"><span class="icon icon-kimia-phone"></span> (408) 748-1046</a>
                        <a class="flex gap-10 items-verticaly-center" href="mailto:info@kimiacorp.com" target="_blank"><span class="icon icon-kimia-envelope"></span>info@kimiacorp.com</a>
                        <a class="flex gap-10 items-verticaly-center" href="https://maps.app.goo.gl/vpReVCVFYtTLE2ds5" target="_blank"><span class="icon icon-kimia-location"></span>3048 Scott Blvd. Santa Clara, CA 95054</a>
                    </div>
                </div>
                <div class="col-50 form-col">
                    <div class="row mb-3">
                        <h4 class="h4 mt-0 ">Send  A Message</h4>
                    </div>
                    <div class="form-wrapper">
                        <form @submit.prevent="submitContacts">
                            <div class="flex gap-20">
                                <div class="form-half-field form-field">
                                    <label for="name">Name</label>
                                    <input type="text" id="name" v-model="form.name" placeholder="Enter your name" oninput="this.value = this.value.substring(0,50);"/>
                                    <label class="rt-cust-error" v-if="hasValidationError(errors,'name')">{{ validationError(errors,'name') }}</label>
                                </div>
                                <div class="form-half-field form-field">
                                    <label for="company">Company/Institution</label>
                                    <input type="text" id="company" v-model="form.company" placeholder="Company/Institution" oninput="this.value = this.value.substring(0,50);"/>
                                    <label class="rt-cust-error" v-if="hasValidationError(errors,'company')">{{ validationError(errors,'company') }}</label>
                                </div>
                                <div class="form-half-field form-field">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" v-model="form.email" placeholder="Enter email" oninput="this.value = this.value.substring(0,50);"/>
                                    <label class="rt-cust-error" v-if="hasValidationError(errors,'email')">{{ validationError(errors,'email') }}</label>
                                </div>
                                <div class="form-half-field form-field">
                                    <label for="phone">Phone</label>
                                    <input type="tel" id="phone" v-model="form.phone" placeholder="Enter phone number" @keypress="isNumber($event)" oninput="this.value = (this.value.replace(/[^0-9]/g,'')).substring(0,10);"/>
                                    <label class="rt-cust-error" v-if="hasValidationError(errors,'phone')">{{ validationError(errors,'phone') }}</label>
                                </div>
                                <div class="form-full-field form-field">
                                    <label for="subject">Subject</label>
                                    <input type="text" id="subject" v-model="form.subject" placeholder="Enter Subject" oninput="this.value = this.value.substring(0,100);"/>
                                    <label class="rt-cust-error" v-if="hasValidationError(errors,'subject')">{{ validationError(errors,'subject') }}</label>
                                </div>
                                <div class="form-full-field form-field">
                                    <label for="msg">Message</label>
                                    <textarea id="msg" v-model="form.msg" oninput="this.value = this.value.substring(0,200);" cols="50" placeholder="Tell Us More"></textarea>
                                    <label class="rt-cust-error" v-if="hasValidationError(errors,'msg')">{{ validationError(errors,'msg') }}</label>
                                </div>
                                <div class="form-full-field form-button text-center">
                                    <button class="btn secondary-btn" type="submit">Submit</button>
                                </div> 
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </FrontendLayout>
</template>