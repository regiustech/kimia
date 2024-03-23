<script>
    export default {
        props: {
            flash: {
                type: Object,
                default: function () {
                    return {
                        success: null,
                        error: null
                    }
                }
            }
        },
        data(){
            if(this.flash && this.flash.success){
                toast(this.flash.success,{"type": "success","autoClose": 3000,"transition": "slide"})
            }
            if(this.flash && this.flash.error){
                toast(this.flash.error,{"type": "error","autoClose": 3000,"transition": "slide"})
            }
            return {};
        }
    }
</script>
<script setup>
    import Sidebar from '@/Layouts/Sidebar.vue';
    import {toast} from "vue3-toastify";
    import "vue3-toastify/dist/index.css";
</script>
<template>
    <div class="backend">
        <Sidebar/>
        <div className="layout-content-wrap">
            <slot />
        </div>
    </div>
</template>