<script setup>
    import FrontendLayout from '@/Layouts/FrontendLayout.vue';
    import {Head,useForm} from '@inertiajs/vue3';
    const props = defineProps({
        email: {type: String,required: true},
        token: {type: String,required: true}
    });
    const form = useForm({token: props.token,email: props.email,password: '',password_confirmation: ''});
    const submit = () => {
        form.post(route('password.store'),{
            onFinish: () => form.reset('password','password_confirmation')
        });
    }
</script>
<template>
    <FrontendLayout>
        <Head title="Reset Password" />
        <section class="sign-up-section pad-100-15" style="background-image: url('/assets/images/background-image.jpg');background-size:cover;">
            <div class="container flex mob-flex-rev">
                <div class="col-40">
                    <div class="sing-up-image">
                        <img  src="/assets/images/chamistry-lab.svg">
                    </div>
                </div>
                <div class="col-60">
                    <form class="form-wrapper form-pad" @submit.prevent="submit">
                        <h2 class="h2 mb-3 mt-0">Reset Password</h2>
                        <div class="flex gap-20">
                            <div class="form-full-field form-field">
                                <label for="email">Email<sup>*</sup></label>
                                <input type="text" id="email" v-model="form.email" placeholder="Enter Email" required/>
                                <label class="rt-cust-error" v-if="hasValidateError('email')">{{ validateError('email') }}</label>
                            </div>
                            <div class="form-full-field form-field">
                                <label for="password">Password<sup>*</sup></label>
                                <input type="text" id="password" v-model="form.password" placeholder="Enter Password" required/>
                                <label class="rt-cust-error" v-if="hasValidateError('password')">{{ validateError('password') }}</label>
                            </div>
                            <div class="form-full-field form-field">
                                <label for="password_confirmation">Confirm Password<sup>*</sup></label>
                                <input type="text" id="password_confirmation" v-model="form.password_confirmation" placeholder="Enter Password" required/>
                                <label class="rt-cust-error" v-if="hasValidateError('password_confirmation')">{{ validateError('password_confirmation') }}</label>
                            </div>
                            <div class="form-full-field form-button">
                                <button class="btn secondary-btn" type="submit">Reset Password</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </FrontendLayout>
</template>
