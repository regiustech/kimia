<script>
    export default {
        props: ['errors','product','flash'],
        data(){
            return {
                form: {
                    id: this.product.id || '',
                    name: this.product.name || '',
                    category: this.product.category || '',
                    catalog_number: this.product.catalog_number || '',                
                    cas_number: this.product.cas_number || '',
                    price: this.product.price || '',
                    image: this.product.image || '',
                    specifications: (this.product.specifications && this.product.specifications.length) ? JSON.parse(this.product.specifications) : []
                },
                imagePreview: null,
                categories: ['acid','aldehyde','amine','halide']
            }
        },
        methods: {
            updateProduct: function(){
                document.getElementById("rt-custom-loader").style.display = "block";
                if(this.product.id){
                    var data = this.createUpdateRecordFormData(this.form);
                    if(this.$refs.image){
                        data.append('image',this.$refs.image.files[0]);
                    }
                    data.append('specifications',JSON.stringify(this.form.specifications));
                    this.$inertia.post(this.route('admin.products.update',{product: this.product.id}),data,{
                        onFinish: () => document.getElementById("rt-custom-loader").style.display = "none"
                    });
                }else{
                    if(this.$refs.image){
                        this.form.image = this.$refs.image.files[0]
                    }
                    this.$inertia.post(this.route('admin.products.store'),this.form,{
                        onFinish: () => document.getElementById("rt-custom-loader").style.display = "none"
                    });
                }
            },
            selectNewImage(){
                this.$refs.image.click();
            },
            updateImagePreview(){
                var $vm = this;
                const reader = new FileReader();
                reader.onload = (e) => {
                    $vm.imagePreview = e.target.result;
                };
                reader.readAsDataURL($vm.$refs.image.files[0]);
            },
            addOption(){
                let specifications = Object.assign([],this.form.specifications);
                specifications.push({label: "",value: ""});
                this.form.specifications = specifications;
            },
            deleteOption(index){
                this.$swal.fire({text: "Are you sure you want to delete this?",icon: 'warning',showCancelButton: true,confirmButtonText: 'Yes',cancelButtonText: 'No'}).then((result) => {
                    if(result.isConfirmed){
                        let specifications = Object.assign([],this.form.specifications);
                        specifications.splice(index,1);
                        this.form.specifications = specifications;
                    }
                });
            }
        }
    }
</script>
<script setup>
    import BackendLayout from '@/Layouts/BackendLayout.vue';
    import SwitchButton from '@/Components/SwitchButton.vue';
    import {Head} from '@inertiajs/vue3';
</script>
<template>
    <Head :title="product.id ? 'Update Product' : 'Create Product'"/>
    <BackendLayout>
        <div class="rt-head-wrap">
            <div class="heading">{{product.id ? 'Update' : 'Create'}} Products</div>
        </div>
        <form @submit.prevent="updateProduct" class="rt-cust-form">
            <div class="rt-cust-box-inner">
                <div class="rt-cust-left">
                    <div class="form-group">
                        <label>Name:</label>
                        <input type="text" v-model="form.name" oninput="this.value = this.value.substring(0,100);"/>
                        <label class="rt-cust-error" v-if="hasValidateError('name')">{{ validateError('name') }}</label>
                    </div>
                    <div class="form-group">
                        <label>Category:</label>
                        <select v-model="form.category">
                            <option value="">Select Category</option>
                            <option :value="category" v-for="category in categories" :key="category">{{ ucwords(category) }}</option>
                        </select>
                        <label class="rt-cust-error" v-if="hasValidateError('category')">{{ validateError('category') }}</label>
                    </div>
                    <div class="form-group">
                        <label>Catalog Number:</label>
                        <input type="text" v-model="form.catalog_number" oninput="this.value = this.value.substring(0,16);"/>
                        <label class="rt-cust-error" v-if="hasValidateError('catalog_number')">{{ validateError('catalog_number') }}</label>
                    </div>
                    <div class="form-group">
                        <label>CAS Number:</label>
                        <input type="text" v-model="form.cas_number" oninput="this.value = this.value.substring(0,16);"/>
                        <label class="rt-cust-error" v-if="hasValidateError('cas_number')">{{ validateError('cas_number') }}</label>
                    </div>
                    <div class="form-group">
                        <label>Price:</label>
                        <input type="text" v-model="form.price" oninput="this.value = (this.value.replace(/[^0-9]/g,'')).substring(0,4);"/>
                        <label class="rt-cust-error" v-if="hasValidateError('price')">{{ validateError('price') }}</label>
                    </div>
                    <div class="form-group full">
                        <div class='rt-cust-options'>
                            <div class='top'>
                                <label class="label">Specifications</label>
                                <button class="rt-add" type="button" @click="addOption">Add</button>
                            </div>
                            <div class="rt-field-options" v-if="form.specifications.length">
                                <div class="rt-field-option" v-for="(specification,index) in form.specifications" :key="index">
                                    <div class="left-section">
                                        <div class="row-group">
                                            <div class="form-group">
                                                <label class="label">Label</label>
                                                <input type="text" v-model="specification.label" oninput="this.value = this.value.substring(0,50);"/>
                                            </div>
                                            <div class="form-group">
                                                <label class="label">Value</label>
                                                <input type="text" v-model="specification.value" oninput="this.value = this.value.substring(0,50);"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="action-section">
                                        <button class="rt-delete" type="button" @click="deleteOption(index)">
                                            <svg viewBox="0 0 24 24" width="22" height="22" fill="#FFF"><path d="M12.0007 10.5865L16.9504 5.63672L18.3646 7.05093L13.4149 12.0007L18.3646 16.9504L16.9504 18.3646L12.0007 13.4149L7.05093 18.3646L5.63672 16.9504L10.5865 12.0007L5.63672 7.05093L7.05093 5.63672L12.0007 10.5865Z"/></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="rt-cust-right">
                    <div class="form-group">
                        <label>Image:</label>
                        <input type="file" accept="image/png,image/jpg,image/jpeg,image/svg+xml" class="hidden" ref="image" @change="updateImagePreview">
                        <div v-if="imagePreview" class="product-img-wrap" @click="selectNewImage" >
                            <span :style="'background-image:url(\''+imagePreview+'\');'"></span>
                        </div>
                        <div v-else class="product-img-wrap" @click="selectNewImage">
                            <img :src="form.image ? form.image : '/assets/images/default.png'" class="product-img"/>
                        </div>
                        <label class="rt-cust-error" v-if="hasValidateError('image')">{{ validateError('image') }}</label>
                    </div>
                </div>
            </div>
            <div class="form-btn-wrap">
                <button type="submit" class="form-btn">{{product.id ? 'Update' : 'Save'}}</button>
                <a :href="route('admin.products.index')" class="form-btn">Cancel</a>
            </div>
        </form>
    </BackendLayout>
</template>