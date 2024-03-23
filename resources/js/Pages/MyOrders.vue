<script>
    import axios from "axios";
    export default {
        data(){
            return {
                url: route('customer.myOrders.all'),
                length: 10,
                headers: [
                    {title: 'Order Id',key: 'id',align: 'start',sortable: false},
                    {title: 'Order Date', key: 'created_at',sortable: false},
                    {title: 'Billing Name',key: 'billing_name',align: 'start',sortable: false},
                    {title: 'Billing Email', key: 'billing_email',sortable: false},
                    {title: 'Shipping Address', key: 'shipping_street_address',sortable: false},
                    {title: 'Order Status', key: 'order_status',sortable: false},
                    {title: 'Subtotal', key: 'subtotal',sortable: false},
                    {title: 'Tax', key: 'tax',sortable: false},
                    {title: 'Total', key: 'total',sortable: false}
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
</script>
<template>
    <Head title="My Orders"/>
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
                            <td><a :href="route('customer.myOrders.view',{slug: item.slug})">#{{item.id}}</a></td>
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
                            <td>{{ucwords(item.order_status)}}</td>
                            <td>{{(item.subtotal).toLocaleString('en-US',{style:'currency',currency:'USD'})}}</td>
                            <td>{{(item.tax).toLocaleString('en-US',{style:'currency',currency:'USD'})}}</td>
                            <td>{{(item.total).toLocaleString('en-US',{style:'currency',currency:'USD'})}}</td>
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