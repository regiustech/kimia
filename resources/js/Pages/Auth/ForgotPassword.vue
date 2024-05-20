<script setup>
    import FrontendLayout from "@/Layouts/FrontendLayout.vue";
    import {Head,useForm} from "@inertiajs/vue3";
    const form = useForm({email: ""});
    const submit = () => {
        form.post(route("password.email"));
    }
</script>
<template>
    <FrontendLayout>
        <Head title="Forgot Password &#8211; Kimia Corp."/>
        <section class="sign-up-section pad-100-15" style="background-image: url('/assets/images/background-image.jpg');background-size:cover;">
            <div class="container flex mob-flex-rev">
                <div class="col-40">
                    <div class="sing-up-image">
                        <img  src="/assets/images/chamistry-lab.svg">
                    </div>
                </div>
                <div class="col-60">
                    <form class="form-wrapper form-pad" @submit.prevent="submit">
                        <h2 class="h2 mb-3 mt-0">Forgot Your Password?</h2>
                        <p class="mb-5 mt-0">Please enter your email address below to receive a password reset link.</p>
                        <div class="flex gap-20">
                            <div class="form-full-field form-field">
                                <label for="email">Email<sup>*</sup></label>
                                <input type="email" id="email" v-model="form.email" placeholder="Enter Email" required/>
                                <label class="rt-cust-error" v-if="hasValidateError('email')">{{ validateError('email') }}</label>
                            </div>
                            <div class="form-full-field form-button">
                                <button class="btn secondary-btn" type="submit">Continue</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </FrontendLayout>
</template>
