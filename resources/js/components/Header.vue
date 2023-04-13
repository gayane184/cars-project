<template>
    <header>
        <h1>Logo</h1>
        <el-button @click="logout()">Log Out</el-button>
    </header>
</template>

<script>
    import {$api} from "../api";
    import {api_url} from "../constants";

    export default {
        methods: {
            logout() {
                $api().post(api_url + "logout").then(() => {
                    this.$store.commit("setToken", null);
                    this.$store.commit("setExpiredAt", null);
                    this.$store.commit("setIsLogged", false);
                    this.$store.commit("setUser", null);
                    this.$router.push({name: "sign-in"});
                });
            }
        }
    }
</script>
