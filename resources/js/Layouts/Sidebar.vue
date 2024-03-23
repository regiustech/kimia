<script>
    export default {
        data(){
            return {
                isActiveSidebar: false
            }
        },
        methods: {
            changeSidebarToggle(status){
                this.isActiveSidebar = status;
            },
            logoutUser(){
                this.$swal.fire({
                    text: "Are you sure you want to logout?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if(result.isConfirmed){
                        this.$inertia.post(this.route('logout'),{});
                    }
                });
            }
        }
    }
</script>
<template>
    <div v-if="isActiveSidebar" @click="changeSidebarToggle(!isActiveSidebar)" class="overlay"></div>
    <div :class="(isActiveSidebar ? 'admin-sidebar admin-overlay-menu' : 'admin-sidebar')">
        <a class="logo-wrap" :href="route('home')">
            <img class="logo" src="/assets/images/site-logo.svg" alt="Kimia">
        </a>
        <button :class="(isActiveSidebar ? 'btn-arrow' : 'btn-arrow open')" @click="changeSidebarToggle(!isActiveSidebar)">
            <svg viewBox="0 0 24 24" width="20" height="20" fill="#fff"><path d="M13.775,18.707,8.482,13.414a2,2,0,0,1,0-2.828l5.293-5.293,1.414,1.414L9.9,12l5.293,5.293Z"/></svg>
        </button>
        <ul class="menu-wrap">
            <li :class="isRoute('admin.dashboard') ? 'menu-item active' : 'menu-item'">
                <a :href="route('admin.dashboard')" class="menu-link">
                    <svg viewBox="0 0 24 24" width="26" height="26" fill="#81499d"><path d="M23.9,11.437A12,12,0,0,0,0,13a11.878,11.878,0,0,0,3.759,8.712A4.84,4.84,0,0,0,7.113,23H16.88a4.994,4.994,0,0,0,3.509-1.429A11.944,11.944,0,0,0,23.9,11.437Zm-4.909,8.7A3,3,0,0,1,16.88,21H7.113a2.862,2.862,0,0,1-1.981-.741A9.9,9.9,0,0,1,2,13,10.014,10.014,0,0,1,5.338,5.543,9.881,9.881,0,0,1,11.986,3a10.553,10.553,0,0,1,1.174.066,9.994,9.994,0,0,1,5.831,17.076ZM7.807,17.285a1,1,0,0,1-1.4,1.43A8,8,0,0,1,12,5a8.072,8.072,0,0,1,1.143.081,1,1,0,0,1,.847,1.133.989.989,0,0,1-1.133.848,6,6,0,0,0-5.05,10.223Zm12.112-5.428A8.072,8.072,0,0,1,20,13a7.931,7.931,0,0,1-2.408,5.716,1,1,0,0,1-1.4-1.432,5.98,5.98,0,0,0,1.744-5.141,1,1,0,0,1,1.981-.286Zm-5.993.631a2.033,2.033,0,1,1-1.414-1.414l3.781-3.781a1,1,0,1,1,1.414,1.414Z"/></svg>
                    <span>Dashboard</span>
                </a>
            </li>
            <li :class="isRoute('admin.users.index','admin.users.edit','admin.users.show') ? 'menu-item active' : 'menu-item'">
                <a :href="route('admin.users.index')" class="menu-link">
                    <svg width="26" height="26" fill="#81499d"><g><path d="M7.058 4.962c.839 0 1.595.346 2.139.901a4.998 4.998 0 0 0-2.198 4.138c0 .328.036.647.097.958-.013 0-.025.004-.038.004a3 3 0 1 1 0-6ZM17 5c-.841 0-1.599.349-2.144.906A4.991 4.991 0 0 1 16.9 10.99c.034.001.066.01.1.01a3 3 0 1 0 0-6Zm-5 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm0 2c-1.754 0-3.335 1.063-3.935 2.646a1 1 0 1 0 1.871.709c.307-.811 1.137-1.354 2.065-1.354s1.758.544 2.065 1.354a1.001 1.001 0 0 0 1.289.582c.516-.196.776-.773.581-1.29-.6-1.582-2.181-2.646-3.935-2.646Zm3.7-1.803a1 1 0 1 0 .6 1.908c.223-.07.458-.105.7-.105.928 0 1.758.544 2.065 1.354a1.001 1.001 0 0 0 1.289.582c.516-.196.776-.773.581-1.29C20.335 14.064 18.754 13 17 13a4.33 4.33 0 0 0-1.3.197ZM7 13c-1.754 0-3.335 1.063-3.935 2.646a1 1 0 1 0 1.87.708C5.242 15.543 6.072 15 7 15c.242 0 .478.035.7.105a1 1 0 1 0 .6-1.908A4.33 4.33 0 0 0 7 13Zm0 9H5c-1.654 0-3-1.346-3-3v-2a1 1 0 1 0-2 0v2c0 2.757 2.243 5 5 5h2a1 1 0 1 0 0-2Zm16-6a1 1 0 0 0-1 1v2c0 1.654-1.346 3-3 3h-2a1 1 0 1 0 0 2h2c2.757 0 5-2.243 5-5v-2a1 1 0 0 0-1-1ZM19 0h-2a1 1 0 1 0 0 2h2c1.654 0 3 1.346 3 3v2a1 1 0 1 0 2 0V5c0-2.757-2.243-5-5-5ZM1 8a1 1 0 0 0 1-1V5c0-1.654 1.346-3 3-3h2a1 1 0 1 0 0-2H5C2.243 0 0 2.243 0 5v2a1 1 0 0 0 1 1Z"></path></g></svg>
                    <span>Customers</span>
                </a>
            </li>
            <li :class="isRoute('admin.products.index','admin.products.create','admin.products.edit') ? 'menu-item active' : 'menu-item'">
                <a :href="route('admin.products.index')" class="menu-link">
                    <svg viewBox="0 0 24 24" width="26" height="26" fill="#81499d"><g><path d="M23.621 6.836 22.269 4.01a3.016 3.016 0 0 0-1.758-1.552L14.214.359a7.044 7.044 0 0 0-4.428 0L3.49 2.458a3.015 3.015 0 0 0-1.759 1.554L.445 6.719a3.01 3.01 0 0 0-.247 2.609c.309.84.964 1.49 1.802 1.796l-.005 6.314a4.993 4.993 0 0 0 3.418 4.748l4.365 1.455c.714.238 1.464.357 2.214.357s1.5-.119 2.214-.357l4.369-1.457a4.994 4.994 0 0 0 3.419-4.739l.005-6.32a2.98 2.98 0 0 0 1.819-1.79c.317-.858.228-1.799-.198-2.499ZM10.419 2.257a5.029 5.029 0 0 1 3.162 0l4.248 1.416-5.822 1.95-5.834-1.95 4.246-1.415ZM2.204 7.666l1.327-2.782c.048.025 7.057 2.373 7.057 2.373l-1.621 3.258a1.006 1.006 0 0 1-1.173.434L2.713 9.256a1.025 1.025 0 0 1-.639-.619c-.109-.294-.078-.616.129-.97Zm3.841 12.623a2.995 2.995 0 0 1-2.051-2.848l.005-5.648 3.162 1.054c1.344.448 2.792-.087 3.559-1.371l.278-.557-.005 10.981a4.827 4.827 0 0 1-.581-.155L6.046 20.29Zm11.897-.001-4.37 1.457a5.02 5.02 0 0 1-.581.155l.005-10.995.319.64a2.978 2.978 0 0 0 3.521 1.302l3.161-1.053-.005 5.651a2.997 2.997 0 0 1-2.052 2.844Zm4-11.644a.991.991 0 0 1-.619.6l-5.118 1.706c-.438.147-.934-.035-1.136-.365l-1.655-3.323s7.006-2.351 7.054-2.377l1.393 2.901a.993.993 0 0 1 .081.859Z"></path></g></svg>
                    <span>Products</span>
                </a>
            </li>
            <li :class="isRoute('admin.orders.index','admin.orders.show') ? 'menu-item active' : 'menu-item'">
                <a :href="route('admin.orders.index')" class="menu-link">
                    <svg viewBox="0 0 24 24" width="26" height="26" fill="#81499d"><g><circle cx="7" cy="22" r="2"></circle><circle cx="17" cy="22" r="2"></circle><path d="M23.685 1.336a1 1 0 0 0-1.414 0L17.112 6.5l-1.551-1.619a1 1 0 0 0-1.442 1.386l1.614 1.679a1.872 1.872 0 0 0 1.345.6h.033a1.873 1.873 0 0 0 1.335-.553l5.239-5.243a1 1 0 0 0 0-1.414Z" ></path><path d="M21.9 9.016a1 1 0 0 0-1.162.807l-.128.709A3 3 0 0 1 17.657 13H5.418l-.94-8H11a1 1 0 0 0 0-2H4.242L4.2 2.648A3 3 0 0 0 1.222 0H1a1 1 0 0 0 0 2h.222a1 1 0 0 1 .993.883l1.376 11.7A5 5 0 0 0 8.557 19H19a1 1 0 0 0 0-2H8.557a3 3 0 0 1-2.829-2h11.929a5 5 0 0 0 4.921-4.113l.128-.71a1 1 0 0 0-.806-1.161Z"></path></g></svg>
                    <span>Orders</span>
                </a>
            </li>
        </ul>
        <div :class="(!isActiveSidebar ? 'logout logout-hide' : 'logout')">
            <div class="name-wrap">
                <span class="name">kimia Admin</span>
                <span class="email">kimia@gmail.com</span>
            </div>
            <span class="logout-icon-wrap">
                <span class="logout-title">Logout</span>
                <svg width="22" height="22" x="0" y="0" viewBox="0 0 24 24" fill="#81499d" @click="logoutUser"><g><path d="M11.476 15a1 1 0 0 0-1 1v3a3 3 0 0 1-3 3H5a3 3 0 0 1-3-3V5a3 3 0 0 1 3-3h2.476a3 3 0 0 1 3 3v3a1 1 0 0 0 2 0V5a5.006 5.006 0 0 0-5-5H5a5.006 5.006 0 0 0-5 5v14a5.006 5.006 0 0 0 5 5h2.476a5.006 5.006 0 0 0 5-5v-3a1 1 0 0 0-1-1Z"></path><path d="m22.867 9.879-4.586-4.586a1 1 0 1 0-1.414 1.414l4.263 4.263L6 11a1 1 0 0 0 0 2l15.188-.03-4.323 4.323a1 1 0 1 0 1.414 1.414l4.586-4.586a3 3 0 0 0 .002-4.242Z"></path></g></svg>
            </span>
        </div>
    </div>
</template>