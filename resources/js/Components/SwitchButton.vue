<template id="switch-button">
    <div class="switch-button-control">
            <div class="switch-button" :class="{enabled: isEnabled}" @click="toggle" :style="{'--color': color}">
            <div class="button"></div>
        </div>
        <div class="switch-button-label"><slot></slot></div>
    </div>
</template>
<script>
    export default {
        model: {
            prop: "isEnabled",
            event: "toggle"
        },
        props: {
            isEnabled: Boolean,
            color: {type: String,required: false,default: "#81499d"}
        },
        methods: {
            toggle: function(){
                this.$emit("toggle",!this.isEnabled);
            }
        }
    };
</script>