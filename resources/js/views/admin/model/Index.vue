<template>
    <section class="default-section">
        <div class="section-title">
            <h1>Models</h1>
            <el-button @click="toggleCrud('create')" size="large" type="primary" round>Create</el-button>
        </div>
        <div class="default-content">
            <div class="table-parent scroll-blue">
                <el-table border empty-text="No models" :data="models">
                    <el-table-column label="Name" sortable prop="name">
                        <template #default="scope">
                            <span>{{ scope.row.name }}</span>
                        </template>
                    </el-table-column>
                    <el-table-column label="Mark" sortable prop="mark">
                        <template #default="scope">
                            <span>{{ scope.row.mark.name }}</span>
                        </template>
                    </el-table-column>
                    <el-table-column label="Actions" class-name="column-actions">
                        <template #default="scope">
                            <el-button @click="update(scope.row)" size="large" type="primary" icon="El-Icon-Edit" circle></el-button>
                            <el-popconfirm
                                width="max-content"
                                confirm-button-text="Yes"
                                cancel-button-text="No"
                                :hide-icon="true"
                                title="Are you sure to delete this model ?"
                                @confirm="destroy(scope.row)"
                            >
                                <template #reference>
                                    <el-button type="danger" size="large" icon="El-Icon-Delete" circle></el-button>
                                </template>
                            </el-popconfirm>
                        </template>
                    </el-table-column>
                </el-table>
            </div>
        </div>
        <transition name="fade">
            <Crud
                v-if="crud.popup"
                :marks="marks"
                :model="crud.model"
                :action="crud.action"
                @toggleCrud="toggleCrud"
                @fetchData="fetchData"
            ></Crud>
        </transition>
    </section>
</template>

<script>
    import Crud from "./Crud.vue";
    import {$api} from "../../../api";
    import {ElMessage} from 'element-plus';
    import {api_url} from "../../../constants";

    export default {
        components: {Crud},
        beforeMount() {
            this.fetchData();
        },
        data() {
            return {
                models: [],
                marks: [],
                crud: {
                    popup: false,
                    action: null,
                    model: {}
                }
            }
        },
        methods: {
            fetchData() {
                $api().get(api_url + 'model').then(({data}) => {
                    this.models = data.models;
                    this.marks = data.marks;
                });
            },
            update(info){
                let model = {
                    id: info.id,
                    name: info.name,
                    mark_id: info.mark_id
                };

                this.toggleCrud('update', model);
            },
            destroy(model){
                $api().delete(api_url + 'model/' + model.id).then(({data}) => {
                    if (data.success){
                        this.models = this.models.filter(({id}) => id !== model.id);

                        ElMessage({
                            message: data.message,
                            showClose: true,
                            type: 'success'
                        });
                    }
                });
            },
            toggleCrud(action, model = {}) {
                if (action === 'create') {
                    model = {
                        name: null,
                        mark_id: null
                    };
                }

                if (this.crud.popup) {
                    this.crud = {
                        popup: false,
                        action: null,
                        model: {}
                    };
                } else {
                    this.crud = {
                        popup: true,
                        action: action,
                        model: model
                    };
                }
            }
        }
    }
</script>
