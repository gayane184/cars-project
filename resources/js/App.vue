<template>
    <main>
        <div class="full-structure" v-if="side === 'auth'">
            <Header></Header>
            <div class="structure-body">
                <Sidebar></Sidebar>
                <div class="structure-content">
                    <router-view></router-view>
                </div>
            </div>
        </div>
        <div class="mini-structure" v-else>
            <router-view></router-view>
        </div>
    </main>
</template>

<script>
    import Header from "./components/Header.vue";
    import Sidebar from "./components/Sidebar.vue";

    export default {
        components: {Header, Sidebar},
        computed: {
            side: function () {
                return this.$route.meta.auth;
            }
        }
    }
</script>
