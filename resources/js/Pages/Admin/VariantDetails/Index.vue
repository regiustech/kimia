<script>
    import axios from "axios";
    export default {
        props: ['errors','flash'],
        data(){
            return {
                url: route('admin.variant-details.all'),
                length: 10,
                headers: [
                    {title: 'Name',key: 'name',align: 'start',sortable: false},
                    {title: 'Variant',key: 'variant',align: 'start',sortable: false},
                    {title: 'Display Order',key: 'order_no',align: 'start',sortable: false},
                    {title: 'Manage',key: '',sortable: false,width: "100px"},
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
            },
            actionHandler(item,type){
                if(type == "edit"){
                    this.$inertia.visit(this.route('admin.variant-details.edit',item.id));
                }else if(type == "delete"){
                    this.confirmBoxForRecordDeletion(this.route('admin.variant-details.destroy',{variant_detail: item.id}));
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
    <Head title="Variant Details"/>
    <BackendLayout :flash="flash">
        <div class="rt-head-wrap">
            <div class="heading">Variant Details</div>
            <a :href="route('admin.variant-details.create')" class="btn-add">Add Variant Detail</a>
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
                    <td>{{ item.name }}</td>
                    <td>{{ item.variant.name }}</td>
                    <td>{{ item.order_no }}</td>
                    <td style="width:100px;">
                        <div className="rt-datatable-actions">
                            <button className="box-button" @click="actionHandler(item,'edit')">
                                <svg viewBox="0 0 18 17" width="18" height="18"><path d="M8.54408 1.99901V4.07401H8.36008C6.97908 4.07401 5.60608 4.09101 4.22508 4.06601C4.07226 4.06004 3.91986 4.08586 3.77753 4.14184C3.6352 4.19781 3.50604 4.28272 3.39823 4.3912C3.29041 4.49967 3.20629 4.62934 3.15118 4.77201C3.09607 4.91467 3.07118 5.06723 3.07808 5.22001C3.08675 8.28668 3.08941 11.3533 3.08608 14.42C3.0793 14.564 3.10255 14.7077 3.15433 14.8422C3.20612 14.9767 3.28531 15.0989 3.38688 15.2012C3.48845 15.3034 3.61017 15.3834 3.74432 15.436C3.87846 15.4887 4.02209 15.5129 4.16608 15.507H13.4411C13.5879 15.5162 13.7351 15.4941 13.8727 15.4421C14.0104 15.3901 14.1353 15.3094 14.2394 15.2053C14.3434 15.1013 14.4241 14.9763 14.4762 14.8386C14.5282 14.701 14.5503 14.5539 14.5411 14.407V10.247C14.5411 10.047 14.5411 10.047 14.7501 10.047H16.6081C16.6173 10.0881 16.623 10.1299 16.6251 10.172C16.6251 11.644 16.6421 13.116 16.6171 14.589C16.594 15.3914 16.2594 16.1532 15.6843 16.7132C15.1092 17.2731 14.3388 17.5873 13.5361 17.589C10.3887 17.5977 7.24108 17.5977 4.09308 17.589C3.27473 17.5829 2.49198 17.2535 1.91555 16.6726C1.33912 16.0917 1.01579 15.3064 1.01608 14.488C0.991081 12.338 0.999081 10.197 0.999081 8.05501C0.999081 7.11001 1.00708 6.17301 0.999081 5.22801C0.979295 4.7082 1.09 4.19171 1.3211 3.72567C1.5522 3.25963 1.89632 2.85889 2.32208 2.56001C2.81259 2.19204 3.40889 1.99245 4.02208 1.99101C5.50508 1.98201 6.98708 1.98201 8.46808 1.98201C8.49408 1.99001 8.51008 1.99001 8.54408 1.99901Z"/><path d="M5.84019 12.764V12.204C5.84019 11.573 5.82419 10.943 5.84819 10.304C5.86411 9.08239 6.35935 7.91593 7.22718 7.056C8.95318 5.314 10.6792 3.572 12.4372 1.856C12.8131 1.46919 13.2912 1.19716 13.8158 1.07164C14.3404 0.94611 14.8899 0.972248 15.4002 1.147C15.9254 1.28456 16.4011 1.56757 16.7726 1.96348C17.1441 2.35939 17.3963 2.85212 17.5002 3.385C17.6323 3.88514 17.6278 4.41158 17.4871 4.90937C17.3464 5.40717 17.0746 5.85806 16.7002 6.215C14.9742 7.965 13.2349 9.70434 11.4822 11.433C10.6025 12.2992 9.41675 12.7836 8.18218 12.781C7.42518 12.797 6.67718 12.781 5.92018 12.781C5.89467 12.7709 5.86762 12.7651 5.84019 12.764V12.764ZM7.81018 10.801C8.53415 10.8883 9.26367 10.6903 9.84418 10.249C10.2297 9.92532 10.5969 9.58045 10.9442 9.21601C12.3942 7.76601 13.8372 6.316 15.2872 4.873C15.4786 4.68537 15.5912 4.43181 15.6022 4.164C15.6142 3.93885 15.5568 3.71545 15.4376 3.52403C15.3185 3.33261 15.1434 3.18243 14.936 3.09383C14.7287 3.00522 14.4991 2.98246 14.2785 3.02864C14.0578 3.07482 13.8566 3.1877 13.7022 3.352L8.66918 8.39C8.35266 8.70073 8.11087 9.0793 7.96207 9.49714C7.81326 9.91497 7.76132 10.3612 7.81018 10.802V10.801Z"/></svg>
                            </button>
                            <button className="box-button" @click="actionHandler(item,'delete')">
                                <svg viewBox="0 0 18 17" width="18" height="18"><path d="M2.53804 4.137H0.915039V2.069H6.99104V0H11.426V2.069H17.515V4.137H15.88C15.827 4.627 15.78 5.092 15.729 5.566C15.605 6.766 15.472 7.959 15.348 9.155L14.975 12.694C14.895 13.433 14.842 14.173 14.718 14.904C14.6162 15.3879 14.3487 15.821 13.9617 16.1288C13.5747 16.4366 13.0924 16.5997 12.598 16.59C10.336 16.598 8.07404 16.598 5.81204 16.59C5.30517 16.5934 4.81362 16.4164 4.42524 16.0907C4.03687 15.7649 3.77698 15.3117 3.69204 14.812C3.55004 13.612 3.43504 12.419 3.30204 11.223C3.16004 9.923 3.01804 8.631 2.88504 7.335C2.77804 6.3 2.67104 5.259 2.56504 4.212C2.55194 4.18867 2.54281 4.16333 2.53804 4.137V4.137ZM5.88204 14.513C5.94404 14.513 5.98204 14.521 6.01504 14.521H12.366C12.508 14.521 12.535 14.471 12.543 14.355C12.605 13.732 12.667 13.117 12.743 12.494C12.894 11.094 13.043 9.686 13.195 8.294C13.31 7.194 13.435 6.094 13.55 4.994C13.577 4.72 13.603 4.437 13.63 4.155H4.77404C5.14671 7.60233 5.51637 11.0553 5.88304 14.514L5.88204 14.513Z"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>
            </template>
        </v-data-table-server>
    </BackendLayout>
</template>