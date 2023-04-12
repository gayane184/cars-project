<template>
    <section class="auth-section">
        <h3 class="auth-title">Sign In</h3>
        <div class="auth-form">
            <div class="form-field">
                <div class="field-label">Email</div>
                <el-input
                    @keyup.enter.native="submit()"
                    v-model="credentials.email"
                    name="username"
                    size="large"
                ></el-input>
            </div>
            <div class="form-field">
                <div class="field-label">Password</div>
                <el-input
                    @keyup.enter.native="submit()"
                    v-model="credentials.password"
                    class="with-append"
                    name="password"
                    show-password
                    size="large"
                >
                    <template slot="append">
                        <el-icon>
                            <El-Icon-View></El-Icon-View>
                        </el-icon>
                    </template>
                </el-input>
            </div>
        </div>
        <el-button @click="submit()">Sign In</el-button>
    </section>
</template>

<script>

import {$api} from "../../api";
import {api_url} from "../../constants";
import {ElMessage} from 'element-plus';

export default {
    data() {
        return {
            credentials: {
                email: null,
                password: null
            }
        }
    },
    methods: {
        submit() {
            $api().post(api_url + 'sign-in', this.credentials).then(({data}) => {
                if (data.success) {
                    this.$store.commit("setToken", data.token);
                    this.$store.commit("setExpiredAt", data.expired_at);
                    this.$store.commit("setIsLogged", true);
                    this.$store.commit("setUser", data.user);
                    this.$router.push({name: data.user.role === 'admin' ? 'admin-mark' : 'user-car'});
                } else {
                    ElMessage({
                        message: data.message,
                        showClose: true,
                        type: 'warning'
                    });
                }
            });
        }
    }
}
</script>
