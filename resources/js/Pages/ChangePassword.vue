<script>
    import axios from "axios";
    export default {
        data(){
            return {
                form: {
                    old_password: "",
                    password: "",
                    confirm_password: ""
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
                    axios.post($vm.route('change-password.update'),$vm.form).then(({data}) => {
                        document.getElementById("rt-custom-loader").style.display = "none";
                        if(data.status == 200){
                            $vm.form = {old_password: "",password: "",confirm_password: ""}
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
                const passRegix = /^(?=.*\d)(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z]).{8,}$/;
                if(!this.form.old_password || !this.form.old_password.trim()){
                    newError["old_password"] = "Required";
                    positionFocus = positionFocus || "old_password";
                }
                if(!this.form.password || !this.form.password.trim()){
                    newError["password"] = "Required";
                    positionFocus = positionFocus || "password";
                }
                if(!this.form.confirm_password || !this.form.confirm_password.trim()){
                    newError["confirm_password"] = "Required";
                    positionFocus = positionFocus || "confirm_password";
                }
                if(this.form.old_password && this.form.password){
                    if(this.form.old_password == this.form.password){
                        newError["password"] = "Old and New Password should not be same.";
                        positionFocus = positionFocus || "password";
                    }else if(this.form.password && this.form.password.length > 20){
                        newError["password"] = "Maximum 20 characters allowed";
                        positionFocus = positionFocus || "password";
                    }else if(!passRegix.test(this.form.password)){
                        newError["password"] = "Password must have at least 8 character and contain at least one of each: uppercase letter, one lowercase letter, number, and symbol.";
                        positionFocus = positionFocus || "password";
                    }else if(this.form.confirm_password && this.form.password != this.form.confirm_password){
                        newError["confirm_password"] = "Password does not match.";
                        positionFocus = positionFocus || "confirm_password";
                    }
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
    import {Head} from '@inertiajs/vue3';
    import {toast} from "vue3-toastify";
    import "vue3-toastify/dist/index.css";
</script>
<template>
    <Head title="Change Password &#8211; Kimia Corp."/>
    <FrontendLayout>
        <section class="sign-up-section pad-100-15" style="background-image: url('/assets/images/background-image.jpg'); background-size:cover;">
            <div class="container flex mob-flex-rev">
                <div class="col-40">
                    <div class="sing-up-image">
                        <img src="/assets/images/chamistry-lab.svg">
                    </div>
                </div>
                <div class="col-60">
                    <form @submit.prevent="submitForm" class="form-wrapper form-pad">
                        <h2 class="h2 mb-3 mt-0">Change Password</h2>
                        <div class="flex gap-20">
                            <div class="form-full-field form-field">
                                <label for="old_password">Old Password<sup>*</sup></label>
                                <input type="password" id="old_password" v-model="form.old_password"/>
                                <label class="rt-cust-error" v-if="hasValidationError(errors,'old_password')">{{ validationError(errors,'old_password') }}</label>
                            </div>
                            <div class="form-full-field form-field">
                                <label for="password">New Password<sup>*</sup></label>
                                <input type="password" id="password" v-model="form.password"/>
                                <label class="rt-cust-error" v-if="hasValidationError(errors,'password')">{{ validationError(errors,'password') }}</label>
                            </div>
                            <div class="form-full-field form-field">
                                <label for="confirm_password">Confirm Password<sup>*</sup></label>
                                <input type="password" id="confirm_password" v-model="form.confirm_password"/>
                                <label class="rt-cust-error" v-if="hasValidationError(errors,'confirm_password')">{{ validationError(errors,'confirm_password') }}</label>
                            </div>
                            <div class="form-full-field form-button">
                                <button class="btn secondary-btn" type="submit">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </FrontendLayout>
</template>