<template>
    <section class="default-section">
        <div class="section-title">
            <h1>Cars</h1>
            <el-button @click="toggleCrud('create')" size="large" type="primary" round>Create</el-button>
        </div>
        <div class="default-content">
            <div class="table-parent scroll-blue">
                <el-table border empty-text="No cars" :data="cars">
                    <el-table-column label="Thumbnail" sortable prop="thumbnail" class-name="column-image">
                        <template #default="scope">
                            <img :src="scope.row.thumbnail.fullPath" alt="">
                        </template>
                    </el-table-column>
                    <el-table-column label="Mark" sortable prop="mark">
                        <template #default="scope">
                            <span>{{ scope.row.model.mark.name }}</span>
                        </template>
                    </el-table-column>
                    <el-table-column label="Model" sortable prop="model">
                        <template #default="scope">
                            <span>{{ scope.row.model.name }}</span>
                        </template>
                    </el-table-column>
                    <el-table-column label="Description" sortable prop="description" class-name="column-ellipse">
                        <template #default="scope">
                            <span>{{ scope.row.description }}</span>
                        </template>
                    </el-table-column>
                    <el-table-column label="Actions" class-name="column-actions">
                        <template #default="scope">
                            <el-button @click="update(scope.row)" size="large" type="primary" icon="El-Icon-Edit" circle></el-button>
                            <el-button v-if="scope.row.published" @click="toggleStatus(scope.row)" size="large" type="warning" round>Hide</el-button>
                            <el-button v-else @click="toggleStatus(scope.row)" size="large" type="success" round>Publish</el-button>
                            <el-popconfirm
                                width="max-content"
                                confirm-button-text="Yes"
                                cancel-button-text="No"
                                :hide-icon="true"
                                title="Are you sure to delete this car ?"
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
                cars: [],
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
                $api().get(api_url + 'car').then(({data}) => {
                    this.cars = data.cars;
                    this.marks = data.marks;
                });
            },
            update(car){
                let model = {
                    id: car.id,
                    model_id: car.model_id,
                    thumbnail: car.thumbnail.fullPath,
                    description: car.description
                };

                this.toggleCrud('update', model);
            },
            destroy(car){
                $api().delete(api_url + 'car/' + car.id).then(({data}) => {
                    if (data.success){
                        this.cars = this.cars.filter(({id}) => id !== car.id);

                        ElMessage({
                            message: data.message,
                            showClose: true,
                            type: 'success'
                        });
                    }
                });
            },
            toggleStatus(car) {
                $api().post(api_url + 'update-car-status/' + car.id).then(({data}) => {
                    if (data.success){
                        this.fetchData();

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
                        model_id: null,
                        thumbnail: null,
                        description: null
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
