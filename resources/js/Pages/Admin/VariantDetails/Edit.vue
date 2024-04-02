<script>
    export default {
        props: ["errors","variants","variantDetail","flash"],
        data(){
            return {
                form: {
                    id: this.variantDetail.id || "",
                    variant_id: this.variantDetail.variant_id || "",
                    name: this.variantDetail.name || "",
                    order_no: this.variantDetail.order_no || ""
                }
            }
        },
        methods: {
            updateVariantDetail: function(){
                document.getElementById("rt-custom-loader").style.display = "block";
                if(this.variantDetail.id){
                    var data = this.createUpdateRecordFormData(this.form);
                    this.$inertia.post(this.route('admin.variant-details.update',{variant_detail: this.variantDetail.id}),data,{
                        onFinish: () => document.getElementById("rt-custom-loader").style.display = "none"
                    });
                }else{
                    this.$inertia.post(this.route('admin.variant-details.store'),this.form,{
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
    <Head :title="variantDetail.id ? 'Update Variant Detail' : 'Create Variant Detail'"/>
    <BackendLayout>
        <div class="rt-head-wrap">
            <div class="heading">{{variantDetail.id ? 'Update' : 'Create'}} Variant Detail</div>
        </div>
        <form @submit.prevent="updateVariantDetail" class="rt-cust-form">
            <div class="rt-cust-box-inner">
                <div class="rt-cust-left">
                    <div class="form-group">
                        <label>Variant:</label>
                        <select v-model="form.variant_id">
                            <option value="">Select</option>
                            <option v-for="variant in variants" :key="variant.id" :value="variant.id">{{variant.name}}</option>
                        </select>
                        <label class="rt-cust-error" v-if="hasValidateError('variant_id')">{{ validateError('variant_id') }}</label>
                    </div>
                    <div class="form-group">
                        <label>Name:</label>
                        <input type="text" v-model="form.name" oninput="this.value = this.value.substring(0,100);"/>
                        <label class="rt-cust-error" v-if="hasValidateError('name')">{{ validateError('name') }}</label>
                    </div>
                    <div class="form-group">
                        <label>Display Order:</label>
                        <input type="text" v-model="form.order_no" oninput="this.value = (this.value.replace(/[^0-9]/g,'')).substring(0,4);"/>
                        <label class="rt-cust-error" v-if="hasValidateError('order_no')">{{ validateError('order_no') }}</label>
                    </div>
                </div>
            </div>
            <div class="form-btn-wrap">
                <button type="submit" class="form-btn">{{variantDetail.id ? 'Update' : 'Save'}}</button>
                <a :href="route('admin.variant-details.index')" class="form-btn">Cancel</a>
            </div>
        </form>
    </BackendLayout>
</template>