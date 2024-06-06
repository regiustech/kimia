<script>
    import axios from "axios";
    export default {
        props: ['order','orderItems'],
        data(){
            return {
                form: {
                    order_id: this.order.id,
                    order_status: this.order.order_status
                },
                disabledStatus:["completed","cancelled"]
            }
        },
        methods: {
            handleChangeStatus: function(){
                this.$swal.fire({text: "Are you sure you want to change the order status?",icon: 'warning',showCancelButton: true,confirmButtonText: 'Yes',cancelButtonText: 'No'}).then((result) => {
                    if(result.isConfirmed){
                        this.changeStatus();
                    }else{
                        this.form.order_status = this.order.order_status;
                    }
                });
            },
            changeStatus: function(){
                let $vm = this;
                document.getElementById("rt-custom-loader").style.display = "block";
                try{
                    axios.post($vm.route('admin.orders.status'),$vm.form).then(({data}) => {
                        document.getElementById("rt-custom-loader").style.display = "none";
                        if(data.status == 200){
                            toast(data.message,{"type": "success","autoClose": 3000,"transition": "slide"});
                        }else{
                            toast(data.message,{"type": "error","autoClose": 3000,"transition": "slide"});
                        }
                    });
                }catch(e){
                    document.getElementById("rt-custom-loader").style.display = "none";
                }
            }
        }
    }
</script>
<script setup>
    import BackendLayout from '@/Layouts/BackendLayout.vue';
    import {Head} from '@inertiajs/vue3';
    import {toast} from "vue3-toastify";
    import "vue3-toastify/dist/index.css";
</script>
<template>
    <Head title="View Order"/>
    <BackendLayout>
        <div class="rt-head-wrap">
            <div class="heading">Order #{{ order.id }} Details</div>
        </div>
        <div class="rt-order-wrap">
            <div class="rt-box">
                <div class="rt-heading">General</div>
                <div class="rt-box-inner">
                    <div class="order-info-item" v-if="order.created_at">
                        <label><b>Order Date: </b></label>
                        <span>{{ order.created_at }}</span>
                    </div>
                    <div class="order-info-item align-center mtb6" v-if="order.order_status">
                        <label><b>Order Status: </b></label>
                        <select v-model="form.order_status" @change="handleChangeStatus" :disabled="disabledStatus.includes(form.order_status)">
                            <option value="pending" disabled>Pending</option>
                            <option value="processing">Processing</option>
                            <option value="hold">Hold</option>
                            <option value="unholed">Unholed</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="rt-box">
                <div class="rt-heading">Billing Detail</div>
                <div class="rt-box-inner">
                    <div class="order-info-item" v-if="order.billing_name">
                        <label><b>Name: </b></label>
                        <span>{{ order.billing_name }}</span>
                    </div>
                    <div class="order-info-item" v-if="order.billing_email">
                        <label><b>Email: </b></label>
                        <span>{{ order.billing_email }}</span>
                    </div>
                    <div class="order-info-item" v-if="order.billing_phone">
                        <label><b>Phone: </b></label>
                        <span>{{ order.billing_phone }}</span>
                    </div>
                    <div class="order-info-item" v-if="order.billing_company">
                        <label><b>Company: </b></label>
                        <span>{{ order.billing_company }}</span>
                    </div>
                    <div class="order-info-item"  v-if="order.billing_street_address || order.billing_city || order.billing_state || order.billing_zipcode">
                        <label><b>Address: </b></label>
                        <span>
                            <template v-if="order.billing_street_address">{{ `${order.billing_street_address}` }}</template>
                            <template v-if="order.billing_city">{{ `, ${order.billing_city}` }}</template>
                            <template v-if="order.billing_state">{{ `, ${order.billing_state}` }}</template>
                            <template v-if="order.billing_zipcode">{{ `, ${order.billing_zipcode}` }}</template>
                        </span>
                    </div>
                </div>
            </div>
            <div class="rt-box">
                <div class="rt-heading">Shipping Detail</div>
                <div class="rt-box-inner">
                    <div class="order-info-item" v-if="order.shipping_name">
                        <label><b>Name: </b></label>
                        <span>{{ order.shipping_name }}</span>
                    </div>
                    <div class="order-info-item" v-if="order.shipping_company">
                        <label><b>Company: </b></label>
                        <span>{{ order.shipping_company }}</span>
                    </div>
                    <div class="order-info-item"  v-if="order.shipping_street_address || order.shipping_city || order.shipping_state || order.shipping_zipcode">
                        <label><b>Address: </b></label>
                        <span>
                            <template v-if="order.shipping_street_address">{{ `${order.shipping_street_address}` }}</template>
                            <template v-if="order.shipping_city">{{ `, ${order.shipping_city}` }}</template>
                            <template v-if="order.shipping_state">{{ `, ${order.shipping_state}` }}</template>
                            <template v-if="order.shipping_zipcode">{{ `, ${order.shipping_zipcode}` }}</template>
                        </span>
                    </div>
                </div>
            </div>
            </div>
            <div class="rt-additional" v-if="order.additional_notes">
                <div class="rt-heading">Additional Notes:</div>
                <div class="rt-content">{{ order.additional_notes }}</div>
            </div>
            <div class="rt-table-wrap">
                <div class="rt-heading">Items</div>
                <div class="rt-table-wrapper">
                    <table class="rt-table">
                        <thead class="rt-thead">
                            <tr class="rt-head-row">
                                <th class="rt-head">Product</th>
                                <th class="rt-head">Category</th>
                                <th class="rt-head">Price</th>
                                <th class="rt-head">Quantity</th>
                                <th class="rt-head">Total</th>
                            </tr>
                        </thead>
                        <tbody class="rt-tbody">
                            <tr v-for="item in orderItems" :key="item.id" class="rt-body-row">
                                <td class="rt-data">
                                    <div class="produst-wrap">
                                        <img :src="item.product.image" :alt="item.product.name" class="product-img"/>
                                        <div class="product-info">
                                            <div class="rt-table-text">{{ item.product.name }}</div>
                                            <div class="rt-table-text"><strong>Catalog: </strong>{{ item.product.catalog_number }}</div>
                                            <div class="rt-table-text" v-if="item.product.product_type == 'variant'"><strong>Unit: </strong>{{ item.variant_detail ? item.variant_detail.name : null }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="rt-data">{{ ucwords(item.product.category) }}</td>
                                <td class="rt-data">{{ (item.price).toLocaleString('en-US',{style:'currency',currency:'USD'}) }}</td>
                                <td class="rt-data">{{ item.quantity }}</td>
                                <td class="rt-data">{{ (item.total).toLocaleString('en-US',{style:'currency',currency:'USD'}) }}</td>
                            </tr>
                            <tr class="rt-body-row">
                                <td class="rt-data" colspan="3"></td>
                                <td class="rt-data" style="font-weight:600 !important;">Subtotal</td>
                                <td class="rt-data" style="font-weight:600 !important;">{{ (order.subtotal).toLocaleString('en-US',{style:'currency',currency:'USD'}) }}</td>
                            </tr>
                            <tr class="rt-body-row" v-if="order.shipping_amount">
                                <td class="rt-data" colspan="3"></td>
                                <td class="rt-data" style="font-weight:600 !important;">Shipping</td>
                                <td class="rt-data" style="font-weight:600 !important;">{{ (order.shipping_amount).toLocaleString('en-US',{style:'currency',currency:'USD'}) }}</td>
                            </tr>
                            <tr class="rt-body-row">
                                <td class="rt-data" colspan="3"></td>
                                <td class="rt-data" style="font-weight:600 !important;">Tax{{ (order.tax_percent ? " ("+order.tax_percent+"%)" : "") }}</td>
                                <td class="rt-data" style="font-weight:600 !important;">{{ (order.tax).toLocaleString('en-US',{style:'currency',currency:'USD'}) }}</td>
                            </tr>
                            <tr class="rt-body-row">
                                <td class="rt-data" colspan="3"></td>
                                <td class="rt-data" style="font-size:18px;font-weight:600 !important;">Total</td>
                                <td class="rt-data" style="font-size:18px;font-weight:600 !important;">{{ (order.total).toLocaleString('en-US',{style:'currency',currency:'USD'}) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
    </BackendLayout>
</template>