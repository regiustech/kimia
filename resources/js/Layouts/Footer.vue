<script>
    import axios from "axios";
    export default {
        data(){
            return {
                form: {
                    email: ""
                },
                submitting: false,
                errors: []
            }
        },
        methods: {
            submitNewsletter: function(){
                let $vm = this;
                if($vm.submitting){
                    return;
                }
                if(!$vm.validate()){
                    return;
                }
                document.getElementById("rt-custom-loader").style.display = "block";
                try{
                    axios.post($vm.route('newsletter'),$vm.form).then(({data}) => {
                        document.getElementById("rt-custom-loader").style.display = "none";
                        if(data.status == 200){
                            $vm.form.email = "";
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
                if(!this.form.email || !this.form.email.trim()){
                    newError["newsleter"] = "Required";
                    positionFocus = positionFocus || "newsleter";
                }else if(this.form.email && !emailRE.test(this.form.email)){
                    newError["newsleter"] = "Enter a valid email";
                    positionFocus = positionFocus || "newsleter";
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
    import {toast} from "vue3-toastify";
    import "vue3-toastify/dist/index.css";
</script>
<template>
    <footer>
        <section class="pad-100-15">
            <div class="container flex gap-30">
                <div class="col-25 flex-3">
                    <div class="ft-logo">
                        <a class="ft-site-logo" :href="route('home')">
                            <img src="/assets/images/site-logo.svg" alt="Site logo"/>
                        </a>
                        <p class="text-white mt-3">Aliquam risus magna, gravida sit amet feugiat et, dignissim ut purus. Fusce lacus est, cursus ac sem facilisis, hendrerit maximus neque. </p>
                        <div class="ft-social-icon gap-10 flex items-verticaly-center">
                            <a class="icon"><i class="icon-kimia-facebook"></i></a>
                            <a class="icon"><i class="icon-kimia-linkedin"></i></a>
                            <a class="icon"><i class="icon-kimia-instagram"></i></a>
                            <a class="icon"><i class="icon-kimia-twitter"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-25 flex-1">
                    <div class="quick-links">
                        <h6 class="text-white">Quick as</h6>
                        <a :href="route('home')">Home</a>
                        <a :href="route('products')">Products</a>
                        <a :href="route('about')">About</a>
                        <a :href="route('services')">Services</a>
                        <a :href="route('contacts')">Contact</a>
                    </div>
                </div>
                <div class="col-25 flex-1">
                    <div class="quick-links">
                        <h6 class="text-white">Our Products</h6>
                        <a :href="route('productByCat','amine')">Amine</a>
                        <a :href="route('productByCat','acid')">Acid</a>
                        <a :href="route('productByCat','aldehyde')">Aldehyde</a>
                        <a :href="route('productByCat','halide')">Halide</a>
                    </div>
                </div>
                <div class="col-25 flex-2">
                    <div class="contact-info">
                        <h6 class="text-white">Get Latest Updates and Products</h6>
                        <form class="input newsleter-input" @submit.prevent="submitNewsletter">
                            <input id="newsleter" type="email" placeholder="Enter Your Email" v-model="form.email"/>
                            <button type="submit" class="submit-icon"><i class="icon-kimia-send"></i></button>
                        </form>
                        <label class="rt-cust-error" v-if="hasValidationError(errors,'newsleter')">{{ validationError(errors,'newsleter') }}</label>
                        <div class="info flex gap-10 mt-3">
                            <a class="flex gap-10 items-verticaly-center" href="tel:4087481046" target="_blank"><span class="icon icon-kimia-phone"></span> (408) 748-1046</a>
                            <a class="flex gap-10 items-verticaly-center" href="mailto:info@kimiacorp.com" target="_blank"><span class="icon icon-kimia-envelope"></span>info@kimiacorp.com</a>
                            <a class="flex gap-10 items-verticaly-center" href="https://maps.app.goo.gl/vpReVCVFYtTLE2ds5" target="_blank"><span class="icon icon-kimia-location"></span>3048 Scott Blvd. Santa Clara, CA 95054</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="copyright-section">
            <div class="container text-center">
                <p>Copyright 2024 .All Rights Reserved.</p>
            </div>
        </section>
    </footer>
</template>