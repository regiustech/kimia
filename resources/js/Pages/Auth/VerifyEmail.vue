<script setup>
    import {computed} from 'vue';
    import FrontendLayout from '@/Layouts/FrontendLayout.vue';
    import {Head,Link,useForm} from '@inertiajs/vue3';
    const props = defineProps({
        status: {type: String}
    });
    const form = useForm({});
    const submit = () => {
        form.post(route('verification.send'));
    }
    const verificationLinkSent = computed(() => props.status === 'verification-link-sent');
</script>
<template>
    <FrontendLayout>
        <Head title="Email Verification &#8211; Kimia Corp."/>
        <section class="sign-up-section pad-100-15" style="background-image: url('/assets/images/background-image.jpg');background-size:cover;">
            <div class="container flex mob-flex-rev">
                <div class="col-40">
                    <div class="sing-up-image">
                        <img  src="/assets/images/chamistry-lab.svg">
                    </div>
                </div>
                <div class="col-60">
                    <form class="form-wrapper form-pad" @submit.prevent="submit">
                        <h2 class="h2 mb-3 mt-0">Email Verification</h2>
                        <p class="mb-5 mt-0">Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.</p>
                        <p class="mb-5 mt-0" v-if="verificationLinkSent">A new verification link has been sent to the email address you provided during registration.</p>
                        <div class="flex gap-20">
                            <div class="form-full-field form-button">
                                <button class="btn secondary-btn" type="submit">Resend Verification Email</button>
                            </div>
                            <p class="mb-0"><Link :href="route('logout')" method="post" as="button">Log Out</Link></p>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </FrontendLayout>
</template>
