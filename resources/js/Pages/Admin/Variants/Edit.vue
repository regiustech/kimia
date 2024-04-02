<script>
    export default {
        props: ['errors','variant','flash'],
        data(){
            return {
                form: {
                    id: this.variant.id || '',
                    name: this.variant.name || ''
                }
            }
        },
        methods: {
            updateVariant: function(){
                document.getElementById("rt-custom-loader").style.display = "block";
                if(this.variant.id){
                    var data = this.createUpdateRecordFormData(this.form);
                    this.$inertia.post(this.route('admin.variants.update',{variant: this.variant.id}),data,{
                        onFinish: () => document.getElementById("rt-custom-loader").style.display = "none"
                    });
                }else{
                    this.$inertia.post(this.route('admin.variants.store'),this.form,{
                        onFinish: () => document.getElementById("rt-custom-loader").style.display = "none"
                    });
                }
            }
        }
    }
</script>
<script setup>
    import BackendLayout from '@/Layouts/BackendLayout.vue';
    import {Head} from '@inertiajs/vue3';
</script>
<template>
    <Head :title="variant.id ? 'Update Variant' : 'Create Variant'"/>
    <BackendLayout>
        <div class="rt-head-wrap">
            <div class="heading">{{variant.id ? 'Update' : 'Create'}} Variant</div>
        </div>
        <form @submit.prevent="updateVariant" class="rt-cust-form">
            <div class="rt-cust-box-inner">
                <div class="rt-cust-left">
                    <div class="form-group">
                        <label>Name:</label>
                        <input type="text" v-model="form.name" oninput="this.value = this.value.substring(0,100);"/>
                        <label class="rt-cust-error" v-if="hasValidateError('name')">{{ validateError('name') }}</label>
                    </div>
                </div>
            </div>
            <div class="form-btn-wrap">
                <button type="submit" class="form-btn">{{variant.id ? 'Update' : 'Save'}}</button>
                <a :href="route('admin.variants.index')" class="form-btn">Cancel</a>
            </div>
        </form>
    </BackendLayout>
</template>