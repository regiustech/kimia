<script>
    import axios from "axios";
    export default {
        data(){
            return {
                url: route('customer.myOrders.all'),
                length: 10,
                headers: [
                    {title: 'Order Date', key: 'created_at',sortable: false},
                    {title: 'Billing Name',key: 'billing_name',sortable: false},
                    {title: 'Billing Email', key: 'billing_email',sortable: false},
                    {title: 'Shipping Address', key: 'shipping_street_address',sortable: false},
                    {title: 'Payment Method', key: 'send_invoice_me',sortable: false},
                    {title: 'Order Status', key: 'order_status',sortable: false},
                    {title: 'Subtotal', key: 'subtotal',sortable: false},
                    {title: 'Tax', key: 'tax',sortable: false},
                    {title: 'Total', key: 'total',sortable: false},
                    {title: 'Manage',key: '',sortable: false}
                ],
                search: '',
                tableRecords: [],
                isLoading: true,
                totalItems: 0,
            }
        },
        methods: {
            loadItems({page,itemsPerPage,sortBy}){
                let $vm = this;
                try{
                    $vm.isLoading = true
                    axios.get(this.url,{params:{page,length: itemsPerPage,sortBy}}).then(({data}) => {
                        $vm.isLoading = false;
                        $vm.tableRecords = data.data;
                        $vm.totalItems = data.total;
                    });
                }catch(e){
                }
            }
        }
    }
</script>
<script setup>
    import FrontendLayout from '@/Layouts/FrontendLayout.vue';
    import {Head} from '@inertiajs/vue3';
    import 'vuetify/styles';
</script>
<template>
    <Head title="My Orders &#8211; Kimia Corp."/>
    <FrontendLayout>
        <section class="inner-banner pad-60-15" style="background-image: url('/assets/images/about-banner.jpg');">
            <div class="container flex text-center">
                <h1 class="mt-0 mb-0 row text-white">My Orders</h1>
            </div>
        </section>
        <section class="pad-100-15 product-section">
            <div class="container">
                <v-data-table-server
                    v-model:items-per-page="length"
                    :headers="headers"
                    :items="tableRecords"
                    :items-length="totalItems"
                    :loading="isLoading"
                    :search="search"
                    item-value="name"
                    @update:options="loadItems"
                >
                    <template v-slot:item="{item}">
                        <tr>
                            <td>{{item.created_at}}</td>
                            <td>{{item.billing_name}}</td>
                            <td>{{item.billing_email}}</td>
                            <td>
                                <span>
                                    <template v-if="item.shipping_street_address">{{ `${item.shipping_street_address}` }}</template>
                                    <template v-if="item.shipping_city">{{ `, ${item.shipping_city}` }}</template>
                                    <template v-if="item.shipping_state">{{ `, ${item.shipping_state}` }}</template>
                                    <template v-if="item.shipping_zipcode">{{ `, ${item.shipping_zipcode}` }}</template>
                                </span>
                            </td>
                            <td>{{((item.send_invoice_me == 1) ? "Pay Later" : "Stripe/Credit Card")}}</td>
                            <td>{{ucwords(item.order_status)}}</td>
                            <td>{{(item.subtotal).toLocaleString('en-US',{style:'currency',currency:'USD'})}}</td>
                            <td>{{(item.tax).toLocaleString('en-US',{style:'currency',currency:'USD'})}}</td>
                            <td>{{(item.total).toLocaleString('en-US',{style:'currency',currency:'USD'})}}</td>
                            <td>
                                <div className="rt-datatable-actions">
                                    <a className="box-button" :href="route('customer.myOrders.view',{slug: item.slug})">
                                        <svg viewBox="0 0 24 24" width="18" height="18"><path d="M23.821,11.181v0C22.943,9.261,19.5,3,12,3S1.057,9.261.179,11.181a1.969,1.969,0,0,0,0,1.64C1.057,14.739,4.5,21,12,21s10.943-6.261,11.821-8.181A1.968,1.968,0,0,0,23.821,11.181ZM12,18a6,6,0,1,1,6-6A6.006,6.006,0,0,1,12,18Z"/><circle cx="12" cy="12" r="4"/></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </template>
                </v-data-table-server>
            </div>
        </section>
    </FrontendLayout>
</template>
<style scoped>
    .pad-60-15{padding:60px 15px;}
</style>