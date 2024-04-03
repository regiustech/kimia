<script>
    import axios from "axios";
    export default {
        props: ['user'],
        data(){
            return {
                url: route('admin.orders.byUser'),
                length: 10,
                headers: [
                    {title: 'Order Id',key: 'id',align: 'start',sortable: false},
                    {title: 'Order Date', key: 'created_at',sortable: false},
                    {title: 'Billing Name',key: 'billing_name',align: 'start',sortable: false},
                    {title: 'Billing Email', key: 'billing_email',sortable: false},
                    {title: 'Billing Address', key: 'billing_street_address',sortable: false},
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
                    axios.get(this.url,{params:{user_id: $vm.user.id,page,length: itemsPerPage,sortBy}}).then(({data}) => {
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
    import BackendLayout from '@/Layouts/BackendLayout.vue';
    import {Head} from '@inertiajs/vue3';
    import 'vuetify/styles';
</script>
<template>
    <Head title="View Customer"/>
    <BackendLayout>
        <div class="rt-head-wrap">
            <div class="heading">View Customer</div>
        </div>
        <div class="user-info">
            <div v-if="user.first_name || user.last_name" class="user-info-item">
                <label><b>Name: </b></label>
                <span>{{ user.first_name }} {{ user.last_name }}</span>
            </div>
            <div v-if="user.email" class="user-info-item">
                <label><b>Email: </b></label>
                <span>{{ user.email }}</span>
            </div>
            <div v-if="user.phone" class="user-info-item">
                <label><b>Phone: </b></label>
                <span>{{ user.phone }}</span>
            </div>
            <div v-if="user.street_address || user.city || user.state || user.zipcode" class="user-info-item">
                <label><b>Address: </b></label>
                <span>
                    <template v-if="user.street_address">{{ `${user.street_address}` }}</template>
                    <template v-if="user.city">{{ `, ${user.city}` }}</template>
                    <template v-if="user.state">{{ `, ${user.state}` }}</template>
                    <template v-if="user.zipcode">{{ `, ${user.zipcode}` }}</template>
                </span>
            </div>
        </div>
        <div class="rt-head-wrap">
            <div class="heading">Past Orders</div>
        </div>
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
                    <td><a :href="route('admin.orders.show',{order: item.id})">#{{item.id}}</a></td>
                    <td>{{item.created_at}}</td>
                    <td>{{item.billing_name}}</td>
                    <td>{{item.billing_email}}</td>
                    <td>
                        <span>
                            <template v-if="item.billing_street_address">{{ `${item.billing_street_address}` }}</template>
                            <template v-if="item.billing_city">{{ `, ${item.billing_city}` }}</template>
                            <template v-if="item.billing_state">{{ `, ${item.billing_state}` }}</template>
                            <template v-if="item.billing_zipcode">{{ `, ${item.billing_zipcode}` }}</template>
                        </span>
                    </td>
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
    </BackendLayout>
</template>