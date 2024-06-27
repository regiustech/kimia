<script>
    import {QuillEditor} from '@vueup/vue-quill'
    import '@vueup/vue-quill/dist/vue-quill.snow.css';
    export default {
        props: ["errors","emailTemplate","flash"],
        components: {
            QuillEditor
        },
        data(){
            return {
                form: {
                    id: this.emailTemplate.id || '',
                    subject: this.emailTemplate.subject || '',
                    type: this.emailTemplate.type || '',
                    email_content: this.emailTemplate.email_content || ''
                },
                emailTypes: [
                    {label: "Contacts",value: "contacts",variables: ['%subject%','%name%','%company%','%email%','%phone%','%message%']},
                    {label: "Custom Order",value: "custom_order",variables: ['%product_name%','%name%','%email%','%message%']},
                    {label: "New Order",value: "new_order",variables: ['%name%','%order_id%','%order_date%','%billing_name%','%billing_address%','%shipping_name%','%shipping_address%','%order_items%','%is_send_invoice%']},
                    {label: "Change Order Status",value: "order_change_status",variables: ['%name%','%order_id%','%order_status%','%order_change_message%']}
                ],
                variables: ['%site_title%','%site_url%']
            }
        },
        methods: {
            handleSubmit: function(){
                if(this.emailTemplate.id){
                    document.getElementById("rt-custom-loader").style.display = "block";
                    var data = this.createUpdateRecordFormData(this.form);
                    this.$inertia.post(this.route('admin.email-templates.update',{email_template: this.emailTemplate.id}),data,{
                        onFinish: () => document.getElementById("rt-custom-loader").style.display = "none"
                    });
                }
            },
            getVariables: function(){
                let vars = Object.assign([],this.variables);
                if(this.form.type){
                    let typeObj = this.emailTypes.find((e) => e.value == this.form.type);
                    if(typeObj){
                        vars = [...vars,...typeObj.variables];
                    }
                }
                return '<b>Variables:</b><span class="variable">' + vars.join('</span><span class="variable">') + "</span>";
            }
        }
    }
</script>
<script setup>
    import BackendLayout from '@/Layouts/BackendLayout.vue';
    import {Head} from '@inertiajs/vue3';
</script>
<template>
    <Head :title="emailTemplate.id ? 'Update Email Template' : 'Create Email Template'"/>
    <BackendLayout>
        <div class="rt-head-wrap">
            <div class="heading">{{emailTemplate.id ? 'Update' : 'Create'}} Email Template</div>
        </div>
        <form @submit.prevent="handleSubmit" class="rt-cust-form">
            <div class="rt-cust-box-inner">
                <div class="rt-cust-left">
                    <div class="form-group full">
                        <label>Subject:</label>
                        <input type="text" v-model="form.subject"/>
                        <label class="rt-cust-error" v-if="hasValidateError('subject')">{{ validateError('subject') }}</label>
                    </div>
                    <div class="form-group full">
                        <label>Content:</label>
                        <div class="editor-wrap">
                            <quill-editor theme="snow" v-model:content="form.email_content" content-type="html"/>
                        </div>
                        <div class="variables" v-html="getVariables()"></div>
                    </div>
                </div>
            </div>
            <div class="form-btn-wrap">
                <button type="submit" class="form-btn">{{emailTemplate.id ? 'Update' : 'Save'}}</button>
                <a :href="route('admin.email-templates.index')" class="form-btn">Cancel</a>
            </div>
        </form>
    </BackendLayout>
</template>
<style>
    .variables{font-size:14px;line-height:1.3;margin-top:10px;display:flex;flex-wrap:wrap;row-gap:10px;column-gap:15px;padding:12px 15px;border:1px solid #ddd;border-radius:8px;}
    .variables .variable{font-size:14px;line-height:1.3;}
</style>