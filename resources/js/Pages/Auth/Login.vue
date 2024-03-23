<script setup>
    import FrontendLayout from "@/Layouts/FrontendLayout.vue";
    import {Head,useForm} from "@inertiajs/vue3";
    defineProps({
        canResetPassword: {type: Boolean},
        canRegister: {type: Boolean},
        status: {type: String},
    });
    const form = useForm({email:"",password:"",remember: false});
    const submit = () => {
        form.post(route("login"),{
            onFinish: () => form.reset("password")
        });
    }
</script>
<template>
    <FrontendLayout>
        <Head title="Log in"/>
        <section class="sign-up-section pad-100-15" style="background-image: url('/assets/images/background-image.jpg'); background-size:cover;">
            <div class="container flex mob-flex-rev">
                <div class="col-40">
                    <div class="sing-up-image">
                        <img src="/assets/images/chamistry-lab.svg">
                    </div>
                </div>
                <div class="col-60">
                    <form @submit.prevent="submit" class="form-wrapper form-pad">
                        <h2 class="h2 mb-3 mt-0">Login to account</h2>
                        <div class="flex gap-20">
                            <div class="form-full-field form-field">
                                <label for="email">Email<sup>*</sup></label>
                                <input type="email" id="email" v-model="form.email" placeholder="Enter Email" required/>
                                <label class="rt-cust-error" v-if="hasValidateError('email')">{{ validateError('email') }}</label>
                            </div>
                            <div class="form-full-field form-field">
                                <label for="password">Password</label>
                                <input type="password" id="password" v-model="form.password" placeholder="Enter password" required/>
                                <label class="rt-cust-error" v-if="hasValidateError('password')">{{ validateError('password') }}</label>
                            </div>
                            <div class="form-full-field" v-if="canResetPassword">
                                <p class="mb-0"><a :href="route('password.request')">Forget password?</a></p>
                            </div>
                            <div class="form-full-field form-button">
                                <button class="btn secondary-btn" type="submit">Login</button>
                            </div>
                            <p v-if="canRegister" class="mb-0">Not a member ? <a :href="route('register')">Sign up </a> here</p>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </FrontendLayout>
</template>
