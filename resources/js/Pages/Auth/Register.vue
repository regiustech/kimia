<script setup>
    import FrontendLayout from "@/Layouts/FrontendLayout.vue";
    import {Head,useForm} from "@inertiajs/vue3";
    const form = useForm({first_name:"",last_name:"",email:"",phone:"",password:"",password_confirmation:""});
    const submit = () => {
        form.post(route("register"),{
            onFinish: () => form.reset("password","password_confirmation")
        });
    }
</script>
<template>
    <FrontendLayout>
        <Head title="Register"/>
        <section class="sign-up-section pad-100-15" style="background-image: url('/assets/images/background-image.jpg');background-size:cover;">
            <div class="container flex mob-flex-rev">
                <div class="col-40">
                    <div class="sing-up-image">
                        <img  src="/assets/images/chamistry-lab.svg">
                    </div>
                </div>
                <div class="col-60">
                    <form class="form-wrapper form-pad" @submit.prevent="submit">
                        <h2 class="h2 mb-3 mt-0">Create New Account</h2>
                        <div class="flex gap-20">
                            <div class="form-half-field form-field">
                                <label for="first_name">First Name<sup>*</sup></label>
                                <input type="text" id="first_name" v-model="form.first_name" placeholder="First Name"/>
                                <label class="rt-cust-error" v-if="hasValidateError('first_name')">{{ validateError('first_name') }}</label>
                            </div>
                            <div class="form-half-field form-field">
                                <label for="last_name">Last Name<sup>*</sup></label>
                                <input type="text" id="last_name" v-model="form.last_name" placeholder="Last Name"/>
                                <label class="rt-cust-error" v-if="hasValidateError('last_name')">{{ validateError('last_name') }}</label>
                            </div>
                            <div class="form-half-field form-field">
                                <label for="email">Email<sup>*</sup></label>
                                <input type="email" id="email" v-model="form.email" placeholder="Enter Email"/>
                                <label class="rt-cust-error" v-if="hasValidateError('email')">{{ validateError('email') }}</label>
                            </div>
                            <div class="form-half-field form-field">
                                <label for="phone">Phone</label>
                                <input type="tel" id="phone" v-model="form.phone" placeholder="Enter phone number"/>
                                <label class="rt-cust-error" v-if="hasValidateError('phone')">{{ validateError('phone') }}</label>
                            </div>
                            <div class="form-half-field form-field">
                                <label for="password">Password</label>
                                <input type="password" id="password" v-model="form.password" placeholder="Enter password"/>
                                <label class="rt-cust-error" v-if="hasValidateError('password')">{{ validateError('password') }}</label>
                            </div>
                            <div class="form-half-field form-field">
                                <label for="password_confirmation">Confirm Password</label>
                                <input type="password" id="password_confirmation" v-model="form.password_confirmation" placeholder="Confirm password"/>
                                <label class="rt-cust-error" v-if="hasValidateError('password_confirmation')">{{ validateError('password_confirmation') }}</label>
                            </div>
                            <div class="form-full-field form-button">
                                <button class="btn secondary-btn" type="submit">Register</button>
                            </div>
                            <p class="mb-0">Allready have an  Account ? <a :href="route('login')">Login</a></p>
                        </div>
                    </form>
                </div>
                
            </div>
        </section>
    </FrontendLayout>
</template>
