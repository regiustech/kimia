<script>
    import axios from "axios";
    export default {
        data(){
            return {
                url: route('admin.users.all'),
                length: 10,
                headers: [
                    {title: 'First Name',key: 'first_name',align: 'start',sortable: false},
                    {title: 'Last Name', key: 'last_name',sortable: false},
                    {title: 'Email Address', key: 'email',sortable: false},
                    {title: 'Phone Number', key: 'phone',sortable: false},
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
</script>
<template>
    <Head title="Customers"/>
    <BackendLayout>
        <div class="rt-head-wrap">
            <div class="heading">Customers</div>
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
                    <td><a :href="route('admin.users.show',{user: item.id})">{{item.first_name}}</a></td>
                    <td>{{item.last_name}}</td>
                    <td>{{item.email}}</td>
                    <td>{{item.phone}}</td>
                    <td>{{item.created_at}}</td>
                </tr>
            </template>
        </v-data-table-server>
    </BackendLayout>
</template>