<script setup>
    import FrontendLayout from '@/Layouts/FrontendLayout.vue';
    import {Head,useForm} from '@inertiajs/vue3';

    const form = useForm({password: ''});
    const submit = () => {
        form.post(route('password.confirm'),{
            onFinish: () => form.reset()
        });
    }
</script>
<template>
    <FrontendLayout>
        <Head title="Confirm Password"/>
        <section class="sign-up-section pad-100-15" style="background-image: url('/assets/images/background-image.jpg');background-size:cover;">
            <div class="container flex mob-flex-rev">
                <div class="col-40">
                    <div class="sing-up-image">
                        <img  src="/assets/images/chamistry-lab.svg">
                    </div>
                </div>
                <div class="col-60">
                    <form class="form-wrapper form-pad" @submit.prevent="submit">
                        <h2 class="h2 mb-3 mt-0">Confirm Password</h2>
                        <p class="mb-5 mt-0">This is a secure area of the application. Please confirm your password before continuing.</p>
                        <div class="flex gap-20">
                            <div class="form-full-field form-field">
                                <label for="password">Password<sup>*</sup></label>
                                <input type="text" id="password" v-model="form.password" placeholder="Enter Password" required/>
                                <label class="rt-cust-error" v-if="hasValidateError('password')">{{ validateError('password') }}</label>
                            </div>
                            <div class="form-full-field form-button">
                                <button class="btn secondary-btn" type="submit">Confirm</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </FrontendLayout>
</template>
