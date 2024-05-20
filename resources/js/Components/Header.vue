<script>
    export default {
        data(){
            return {
                showMenu: false,
                showSubMenu: false,
            }
        },
        methods: {
            handleMenu(){
                this.showMenu = !this.showMenu;
                this.showSubMenu = false;
            },
            handleSubMenu(){
                this.showSubMenu = !this.showSubMenu;
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
    <header>
        <div class="container flex gap-100 items-verticaly-center">
            <div class="logo">
                <a :href="route('home')" class="site-logo">
                    <img src="/assets/images/site-logo.svg" alt="Kimia"/>
                </a>
            </div>
            <div class="nav flex-1">
                <nav class="navbar" :class="showMenu ? 'open' : ''">
                    <ul class="primary-menu flex gap-30 items-verticaly-center">
                        <li class="nav-item"><a class="nav-link" :href="route('home')">Home</a></li>
                        <li class="nav-item list-has-child" :class="showSubMenu ? 'list-open' : ''">
                            <a class="nav-link" :href="route('products')">Products</a>
                            <span class="drop-drown-wrap" @click="handleSubMenu"><span class="nav-dropdown-icon"></span></span>
                            <ul class="sub-menu">
                                <li class="nav-item"><a class="nav-link" :href="route('products')">All Products</a></li>
                                <li class="nav-item"><a class="nav-link" :href="route('productDetail','linkers')">Linkers</a></li>
                                <li class="nav-item"><a class="nav-link" :href="route('productDetail','new')">New From Kimia</a></li>
                                <li class="nav-item"><a class="nav-link" :href="route('productDetail','pas')">PAS</a></li>
                            </ul>
                        </li>
                        <li class="nav-item"><a class="nav-link" :href="route('services')">Services</a></li>
                        <li class="nav-item"><a class="nav-link" :href="route('about')">About</a></li>
                        <li class="nav-item"><a class="nav-link" :href="route('contacts')">Contact</a></li>
                    </ul>
                </nav>
                <div class="mobile-hamburger" :class="showMenu ? 'active' : ''" @click="handleMenu" style="display:none">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
            <div class="header-info">
                <ul class="flex items-verticaly-center gap-20">
                    <li class="header-phone"><a href="tel:4087481046" target="_blank" class="flex items-verticaly-center gap-10 phone-num"><span class="round-icon"> <i class="icon-kimia-phone"></i></span>(408) 748-1046</a></li>
                    <div v-if="$page.props.auth.user" class="bi-user-details dropdown">
                        <div class='img-wrap'>
                            <img class="user" src="/assets/images/user.png" alt="">
                        </div>
                        <div class="name-wrap">
                            <span class="name">{{$page.props.auth.user.first_name}}</span>
                            <div class="dropdown-content">
                                <a v-if="$page.props.auth.user.role == 'admin'" :href="route('admin.dashboard')">Dashboard</a>
                                <a v-if="$page.props.auth.user.role != 'admin'" :href="route('profile.edit')">My Profile</a>
                                <a v-if="$page.props.auth.user.role != 'admin'" :href="route('customer.myOrders')">My Orders</a>
                                <a :href="route('change-password')">Change Password</a>
                                <a @click="logoutUser">Logout</a>
                            </div>
                        </div>
                    </div>
                    <li v-else class="login"><a class="btn secondary-btn" :href="route('login')">Sign in</a></li>
                    <li><a :href="route('cart.index')" class="cart-icon"><i class="flat-icon icon-kimia-cart"></i> <span class="qty flex items-verticaly-center rtCartCount">{{$page.props.cartCount}}</span></a></li>
                </ul>
            </div>
        </div>
    </header>
</template>
