<template>
    <section class="default-section">
        <div class="section-title">
            <h1>Marks</h1>
            <el-button @click="toggleCrud('create')" size="large" type="primary" round>Create</el-button>
        </div>
        <div class="default-content">
            <div class="table-parent scroll-blue">
                <el-table border empty-text="No marks" :data="marks">
                    <el-table-column label="Name" sortable prop="name">
                        <template #default="scope">
                            <span>{{ scope.row.name }}</span>
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
                                title="Are you sure to delete this mark ?"
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
                :model="crud.model"
                :action="crud.action"
                @toggleCrud="toggleCrud"
                @fetchMarks="fetchMarks"
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
            this.fetchMarks();
        },
        data() {
            return {
                marks: [],
                crud: {
                    popup: false,
                    action: null,
                    model: {}
                }
            }
        },
        methods: {
            fetchMarks() {
                $api().get(api_url + 'mark').then(({data}) => {
                    this.marks = data.marks;
                });
            },
            update(mark){
                let model = {
                    id: mark.id,
                    name: mark.name
                };

                this.toggleCrud('update', model);
            },
            destroy(mark){
                $api().delete(api_url + 'mark/' + mark.id).then(({data}) => {
                    if (data.success){
                        this.marks = this.marks.filter(({id}) => id !== mark.id);

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
                        name: null
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
