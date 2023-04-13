<template>
    <div class="popup crud-popup">
        <div class="popup-inner">
            <div class="popup-header">
                <h1>{{ action }}</h1>
                <El-Icon-Close @click="close()"></El-Icon-Close>
            </div>
            <div class="popup-body scroll-blue">
                <div class="crud-form">
                    <div class="form-field">
                        <div class="field-label">Mark</div>
                        <el-select
                            v-model="mark_id"
                            filterable
                            size="large"
                            remote
                            reserve-keyword>
                            <el-option
                                v-for="mark in marks"
                                :key="'mark-' + mark.id"
                                :label="mark.name"
                                :value="mark.id"
                            ></el-option>
                        </el-select>
                    </div>
                    <div :class="`form-field ${$HasError(errors, 'model_id') ? 'field-invalid' : ''}`">
                        <div class="field-label">Model</div>
                        <el-select
                            v-model="model.model_id"
                            :disabled="!mark_id"
                            reserve-keyword
                            size="large"
                            filterable
                            remote>
                            <el-option
                                v-for="model in marks.find(({id}) => id === mark_id)?.models || []"
                                :key="'model-' + model.id"
                                :label="model.name"
                                :value="model.id"
                            ></el-option>
                        </el-select>
                        <div v-if="$HasError(errors, 'model_id')" class="field-error">
                            <span>{{ $GetError(errors, 'model_id') }}</span>
                        </div>
                    </div>
                    <div :class="`form-field ${$HasError(errors, 'thumbnail') ? 'field-invalid' : ''}`">
                        <div class="field-label">Thumbnail</div>
                        <div class="form-file">
                            <div class="file-input">
                                <el-button :type="getFile().type" size="large">{{ getFile().label }}</el-button>
                                <input id="thumbnail" type="file" @change="changeFile"/>
                            </div>
                            <div class="file-action" v-if="hasFile()">
                                <el-button @click="resetFile()" type="danger" icon="El-Icon-Close" circle></el-button>
                            </div>
                            <el-link
                                v-if="model.thumbnail"
                                :href="model.thumbnail"
                                :underline="false"
                                target="_blank"
                            >
                                <el-button type="primary" icon="El-Icon-View" circle></el-button>
                            </el-link>
                        </div>
                        <div v-if="$HasError(errors, 'thumbnail')" class="field-error">
                            <span>{{ $GetError(errors, 'thumbnail') }}</span>
                        </div>
                    </div>
                    <div :class="`form-field ${$HasError(errors, 'description') ? 'field-invalid' : ''}`">
                        <div class="field-label">Description</div>
                        <el-input size="large" type="textarea" rows="4" v-model="model.description"></el-input>
                        <div v-if="$HasError(errors, 'description')" class="field-error">
                            <span>{{ $GetError(errors, 'description') }}</span>
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
    import objectToFormData from "../../../mixins/objectToFormData";

    export default {
        props: {
            marks: {
                type: Array
            },
            model: {
                type: Object
            },
            action: {
                type: String
            },
            toggleCrud: {
                type: Function
            },
            fetchData: {
                type: Function
            }
        },
        mixins: [helper],
        beforeMount() {
            if (this.action === 'update') {
                const mark = this.marks.find(({models}) => models.findIndex(({id}) => id === this.model.model_id) !== -1);

                this.mark_id = mark.id;
            }
        },
        data() {
            return {
                mark_id: null,
                thumbnail: JSON.parse(JSON.stringify(this.model.thumbnail)),
                errors: {}
            }
        },
        methods: {
            close(){
                this.$emit('toggleCrud');
            },
            hasFile(){
                return typeof this.thumbnail === 'object' && this.thumbnail !== null;
            },
            getFile() {
                if (this.thumbnail){
                    if (typeof this.thumbnail === 'string'){
                        return {
                            type: 'success',
                            label: 'Change'
                        };
                    }
                    return {
                        type: 'primary',
                        label: this.thumbnail.name
                    };
                }
                return {
                    type: 'default',
                    label: 'Choose'
                };
            },
            resetFile(){
                this.thumbnail = JSON.parse(JSON.stringify(this.model.thumbnail));
                document.getElementById('thumbnail').value = null;
            },
            changeFile(event){
                this.thumbnail = event.target.files[0];
            },
            submit(){
                if (this.action === 'create'){
                    this.create(this.model);
                } else{
                    this.update(this.model, this.model.id);
                }
            },
            create(model){
                let data = objectToFormData({
                    ...model,
                    thumbnail: this.thumbnail
                });

                $api().post(api_url + 'car', data).then(({data}) => {
                    if (data.success){
                        this.errors = {};

                        this.$emit('fetchData');

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
                let data = {
                    ...model,
                    _method: 'put'
                };

                if (this.hasFile()) {
                    data.thumbnail = this.thumbnail;
                }

                data = objectToFormData(data);

                $api().post(api_url + 'car/' + id, data).then(({data}) => {
                    if (data.success){
                        this.errors = {};

                        this.$emit('fetchData');

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
