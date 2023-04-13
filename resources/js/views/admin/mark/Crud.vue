<template>
    <div class="popup crud-popup">
        <div class="popup-inner">
            <div class="popup-header">
                <h1>{{ action }}</h1>
                <El-Icon-Close @click="close()"></El-Icon-Close>
            </div>
            <div class="popup-body scroll-blue">
                <div class="crud-form">
                    <div :class="`form-field ${$HasError(errors, 'name') ? 'field-invalid' : ''}`">
                        <div class="field-label">Name <span>*</span></div>
                        <el-input size="large" v-model="model.name"></el-input>
                        <div v-if="$HasError(errors, 'name')" class="field-error">
                            <span>{{ $GetError(errors, 'name') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="popup-footer">
                <el-button @click="close()" size="large" type="danger" round>Close</el-button>
                <el-button @click="submit()" size="large" type="success" round>{{ action }}</el-button>
            </div>
        </div>
    </div>
</template>

<script>
    import {$api} from "../../../api";
    import {ElMessage} from 'element-plus';
    import helper from "../../../mixins/helper";
    import {api_url} from "../../../constants/";

    export default {
        props: {
            model: {
                type: Object
            },
            action: {
                type: String
            },
            toggleCrud: {
                type: Function
            },
            fetchMarks: {
                type: Function
            }
        },
        mixins: [helper],
        data() {
            return {
                errors: {}
            }
        },
        methods: {
            close(){
                this.$emit('toggleCrud');
            },
            submit(){
                if (this.action === 'create'){
                    this.create(this.model);
                } else{
                    this.update(this.model, this.model.id);
                }
            },
            create(model){
                $api().post(api_url + 'mark', model).then(({data}) => {
                    if (data.success){
                        this.errors = {};

                        this.$emit('fetchMarks');

                        this.close();

                        ElMessage({
                            message: data.message,
                            showClose: true,
                            type: 'success'
                        });
                    } else {
                        this.errors = data.errors;
                    }
                });
            },
            update(model, id){
                $api().put(api_url + 'mark/' + id, model).then(({data}) => {
                    if (data.success){
                        this.errors = {};

                        this.$emit('fetchMarks');

                        this.close();

                        ElMessage({
                            message: data.message,
                            showClose: true,
                            type: 'success'
                        });
                    } else {
                        this.errors = data.errors;
                    }
                });
            }
        }
    }
</script>
