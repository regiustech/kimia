<script>
    import axios from "axios";
    export default {
        data(){
            return {
                url: route('admin.users.all'),
                length: 5,
                headers: [
                    {title: 'Name',key: 'first_name',align: 'start',sortable: false},
                    {title: 'Email Address', key: 'email',sortable: false},
                    {title: 'Phone Number', key: 'phone',sortable: false},
                    {title: 'Address',key: 'street_address',sortable: false},
                    {title: 'Joined At', key: 'created_at',sortable: false}
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
    import BackendLayout from '@/Layouts/BackendLayout.vue';
    import {Head} from '@inertiajs/vue3';
    import {Chart as ChartJS,CategoryScale,LinearScale,PointElement,LineElement,Title,Tooltip,Legend} from 'chart.js';
    import {Line} from 'vue-chartjs';
    import 'vuetify/styles';
    defineProps({
        customers: {type: Number},
        products: {type: Number},
        orders: {type: Number},
        totalSales: {type: Number},
        recentOrders: {type: Array},
        graphData: {type: Object}
    });
    const options = {responsive: true,maintainAspectRatio: false}
    ChartJS.register(CategoryScale,LinearScale,PointElement,LineElement,Title,Tooltip,Legend);
</script>
<template>
    <Head title="Dashboard"/>
    <BackendLayout>
        <div class="admin-dashboard">
            <div class="card-info-wrap">
                <div class="card-item">
                    <div class="icon-wrap" style="background:#d3ad67;">
                        <svg viewBox="0 0 24 24" width="26" height="26" fill="#fff"><g><path d="M23.621 6.836 22.269 4.01a3.016 3.016 0 0 0-1.758-1.552L14.214.359a7.044 7.044 0 0 0-4.428 0L3.49 2.458a3.015 3.015 0 0 0-1.759 1.554L.445 6.719a3.01 3.01 0 0 0-.247 2.609c.309.84.964 1.49 1.802 1.796l-.005 6.314a4.993 4.993 0 0 0 3.418 4.748l4.365 1.455c.714.238 1.464.357 2.214.357s1.5-.119 2.214-.357l4.369-1.457a4.994 4.994 0 0 0 3.419-4.739l.005-6.32a2.98 2.98 0 0 0 1.819-1.79c.317-.858.228-1.799-.198-2.499ZM10.419 2.257a5.029 5.029 0 0 1 3.162 0l4.248 1.416-5.822 1.95-5.834-1.95 4.246-1.415ZM2.204 7.666l1.327-2.782c.048.025 7.057 2.373 7.057 2.373l-1.621 3.258a1.006 1.006 0 0 1-1.173.434L2.713 9.256a1.025 1.025 0 0 1-.639-.619c-.109-.294-.078-.616.129-.97Zm3.841 12.623a2.995 2.995 0 0 1-2.051-2.848l.005-5.648 3.162 1.054c1.344.448 2.792-.087 3.559-1.371l.278-.557-.005 10.981a4.827 4.827 0 0 1-.581-.155L6.046 20.29Zm11.897-.001-4.37 1.457a5.02 5.02 0 0 1-.581.155l.005-10.995.319.64a2.978 2.978 0 0 0 3.521 1.302l3.161-1.053-.005 5.651a2.997 2.997 0 0 1-2.052 2.844Zm4-11.644a.991.991 0 0 1-.619.6l-5.118 1.706c-.438.147-.934-.035-1.136-.365l-1.655-3.323s7.006-2.351 7.054-2.377l1.393 2.901a.993.993 0 0 1 .081.859Z"></path></g></svg>
                    </div>
                    <div class="content-wrap">
                        <div class="heading">Total Products</div>
                        <div class="value">{{products}}</div>
                    </div>
                </div>
                <div class="card-item">
                    <div class="icon-wrap" style="background:#ff845a;">
                        <svg width="26" height="26" fill="#fff"><g><path d="M7.058 4.962c.839 0 1.595.346 2.139.901a4.998 4.998 0 0 0-2.198 4.138c0 .328.036.647.097.958-.013 0-.025.004-.038.004a3 3 0 1 1 0-6ZM17 5c-.841 0-1.599.349-2.144.906A4.991 4.991 0 0 1 16.9 10.99c.034.001.066.01.1.01a3 3 0 1 0 0-6Zm-5 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm0 2c-1.754 0-3.335 1.063-3.935 2.646a1 1 0 1 0 1.871.709c.307-.811 1.137-1.354 2.065-1.354s1.758.544 2.065 1.354a1.001 1.001 0 0 0 1.289.582c.516-.196.776-.773.581-1.29-.6-1.582-2.181-2.646-3.935-2.646Zm3.7-1.803a1 1 0 1 0 .6 1.908c.223-.07.458-.105.7-.105.928 0 1.758.544 2.065 1.354a1.001 1.001 0 0 0 1.289.582c.516-.196.776-.773.581-1.29C20.335 14.064 18.754 13 17 13a4.33 4.33 0 0 0-1.3.197ZM7 13c-1.754 0-3.335 1.063-3.935 2.646a1 1 0 1 0 1.87.708C5.242 15.543 6.072 15 7 15c.242 0 .478.035.7.105a1 1 0 1 0 .6-1.908A4.33 4.33 0 0 0 7 13Zm0 9H5c-1.654 0-3-1.346-3-3v-2a1 1 0 1 0-2 0v2c0 2.757 2.243 5 5 5h2a1 1 0 1 0 0-2Zm16-6a1 1 0 0 0-1 1v2c0 1.654-1.346 3-3 3h-2a1 1 0 1 0 0 2h2c2.757 0 5-2.243 5-5v-2a1 1 0 0 0-1-1ZM19 0h-2a1 1 0 1 0 0 2h2c1.654 0 3 1.346 3 3v2a1 1 0 1 0 2 0V5c0-2.757-2.243-5-5-5ZM1 8a1 1 0 0 0 1-1V5c0-1.654 1.346-3 3-3h2a1 1 0 1 0 0-2H5C2.243 0 0 2.243 0 5v2a1 1 0 0 0 1 1Z"></path></g></svg>
                    </div>
                    <div class="content-wrap">
                        <div class="heading">Customers</div>
                        <div class="value">{{customers}}</div>
                    </div>
                </div>
                <div class="card-item">
                    <div class="icon-wrap" style="background:#81499d;">
                        <svg width="26" height="26" fill="#fff"><g><path d="m22.576 12.52-8.727-8.67a3.892 3.892 0 0 0-3.221-1.09l-5.803.651L1.707.293A.999.999 0 1 0 .293 1.707l3.118 3.118-.696 6.088a3.865 3.865 0 0 0 1.136 3.062l8.713 8.645a4.789 4.789 0 0 0 3.4 1.404h.02a4.787 4.787 0 0 0 3.408-1.431l3.21-3.242a4.848 4.848 0 0 0-.025-6.832Zm-1.396 5.426-3.21 3.242a2.8 2.8 0 0 1-1.994.837h-.011a2.807 2.807 0 0 1-1.992-.823l-8.714-8.647a1.861 1.861 0 0 1-.554-1.449l.512-4.474 1.857 1.857A1.967 1.967 0 0 0 7 9.001a2 2 0 1 0 2-2c-.178 0-.347.031-.512.074L6.634 5.221l4.226-.474.021-.003a1.884 1.884 0 0 1 1.559.525l8.726 8.668a2.846 2.846 0 0 1 .015 4.009Zm-3.974-5.151a.999.999 0 0 0-1.414 0l-3 3a.999.999 0 0 0 0 1.414l2.5 2.5a.997.997 0 0 0 1.414 0l3-3a.999.999 0 0 0 0-1.414l-2.5-2.5Zm-1.207 4.793-1.086-1.086 1.586-1.586 1.086 1.086-1.586 1.586Z"></path></g></svg>
                    </div>
                    <div class="content-wrap">
                        <div class="heading">Total Sale</div>
                        <div class="value">{{(totalSales).toLocaleString('en-US',{style:'currency',currency:'USD'})}}</div>
                    </div>
                </div>
                <div class="card-item">
                    <div class="icon-wrap" style="background: #f2cf22;">
                        <svg viewBox="0 0 24 24" width="26" height="26" fill="#fff"><g><circle cx="7" cy="22" r="2"></circle><circle cx="17" cy="22" r="2"></circle><path d="M23.685 1.336a1 1 0 0 0-1.414 0L17.112 6.5l-1.551-1.619a1 1 0 0 0-1.442 1.386l1.614 1.679a1.872 1.872 0 0 0 1.345.6h.033a1.873 1.873 0 0 0 1.335-.553l5.239-5.243a1 1 0 0 0 0-1.414Z" ></path><path d="M21.9 9.016a1 1 0 0 0-1.162.807l-.128.709A3 3 0 0 1 17.657 13H5.418l-.94-8H11a1 1 0 0 0 0-2H4.242L4.2 2.648A3 3 0 0 0 1.222 0H1a1 1 0 0 0 0 2h.222a1 1 0 0 1 .993.883l1.376 11.7A5 5 0 0 0 8.557 19H19a1 1 0 0 0 0-2H8.557a3 3 0 0 1-2.829-2h11.929a5 5 0 0 0 4.921-4.113l.128-.71a1 1 0 0 0-.806-1.161Z"></path></g></svg>
                    </div>
                    <div class="content-wrap">
                        <div class="heading">Total Orders</div>
                        <div class="value">{{orders}}</div>
                    </div>
                </div>
            </div>
            <div class="middle-section" v-if="orders > 0">
                <div class="graph-wrap">
                    <Line :data="graphData" :options="options"/>
                </div>
                <div class="recent-order-wrap" v-if="recentOrders.length">
                    <div class="head">
                        <div class="heading">Recent Orders</div>
                        <a :href="route('admin.orders.index')" class="see-all">See All</a>
                    </div>
                    <div class="orders-body">
                        <div class="order" v-for="recentOrder in recentOrders" :key="recentOrder.id">
                            <div class="name">{{recentOrder.billing_name}}</div>
                            <div class="email">{{recentOrder.billing_email}}</div>
                            <div class="address">
                                <template v-if="recentOrder.billing_street_address">{{ `${recentOrder.billing_street_address}` }}</template>
                                <template v-if="recentOrder.billing_city">{{ `, ${recentOrder.billing_city}` }}</template>
                                <template v-if="recentOrder.billing_state">{{ `, ${recentOrder.billing_state}` }}</template>
                                <template v-if="recentOrder.billing_zipcode">{{ `, ${recentOrder.billing_zipcode}` }}</template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <v-data-table
                v-model:items-per-page="length"
                :headers="headers"
                :items="tableRecords"
                :items-length="totalItems"
                :loading="isLoading"
                @update:options="loadItems"
            >
                <template v-slot:item="{item}">
                    <tr>
                        <td><a :href="route('admin.users.show',{user: item.id})">{{item.first_name}} {{item.last_name}}</a></td>
                        <td>{{item.email}}</td>
                        <td>{{item.phone}}</td>
                        <td>
                            <span>
                                <template v-if="item.street_address">{{ `${item.street_address}` }}</template>
                                <template v-if="item.city">{{ `, ${item.city}` }}</template>
                                <template v-if="item.state">{{ `, ${item.state}` }}</template>
                                <template v-if="item.zipcode">{{ `, ${item.zipcode}` }}</template>
                            </span>
                        </td>
                        <td>{{item.created_at}}</td>
                    </tr>
                </template>
                <template #bottom></template>
            </v-data-table>
        </div>
    </BackendLayout>
</template>