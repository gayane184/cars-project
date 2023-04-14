<template>
    <div class="sidebar">
        <div v-if="$User()?.role === 'admin'" class="sidebar-items">
            <router-link :to="{name: 'admin-mark'}" class="sidebar-item" active-class="active" tag="div">
                <div class="sidebar-item-name">Marks</div>
            </router-link>
            <router-link :to="{name: 'admin-model'}" class="sidebar-item" active-class="active" tag="div">
                <div class="sidebar-item-name">Models</div>
            </router-link>
            <router-link :to="{name: 'admin-car'}" class="sidebar-item" active-class="active" tag="div">
                <div class="sidebar-item-name">Cars</div>
            </router-link>
        </div>
    </div>
</template>

<script>
    import helper from "../mixins/helper";

    export default {
        mixins: [helper]
    }
</script>
