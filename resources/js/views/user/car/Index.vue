<template>
    <section>
        <div v-if="cars.length" class="cars-full">
            <div class="cars-filters">
                <div class="cars-filter">
                    <div class="field-label">Mark</div>
                    <el-select
                        @change="changeMark"
                        v-model="filters.mark"
                        reserve-keyword
                        size="large"
                        filterable
                        remote>
                        <el-option value="all" label="All"></el-option>
                        <el-option
                            v-for="mark in marks"
                            :key="'mark-' + mark.id"
                            :label="mark.name"
                            :value="mark.id"
                        ></el-option>
                    </el-select>
                </div>
                <div class="cars-filter">
                    <div class="field-label">Model</div>
                    <el-select
                        :disabled="filters.mark === 'all'"
                        v-model="filters.model"
                        reserve-keyword
                        size="large"
                        filterable
                        remote>
                        <el-option
                            value="all"
                            :label="filters.mark === 'all' ? ' ' : 'All'"
                        ></el-option>
                        <el-option
                            v-for="model in marks.find(({id}) => id === filters.mark)?.models || []"
                            :key="'model-' + model.id"
                            :label="model.name"
                            :value="model.id"
                        ></el-option>
                    </el-select>
                </div>
            </div>
            <div class="cars-items">
                <el-card class="cars-item" v-for="car in data">
                    <img :src="car.thumbnail.fullPath" class="image" alt="car"/>
                    <div class="card-content">
                        <p>{{ `${car.model.mark.name} ${car.model.name}` }}</p>
                        <p>{{ car.description }}</p>
                    </div>
                </el-card>
            </div>
        </div>
        <div v-else class="cars-empty">
            <p>There is no published cars.</p>
        </div>
    </section>
</template>

<script>
    import {$api} from "../../../api";
    import {api_url} from "../../../constants";

    export default {
        beforeMount() {
            this.fetchCars();
        },
        data() {
            return {
                cars: [],
                marks: [],
                filters: {
                    mark: 'all',
                    model: 'all'
                }
            }
        },
        methods: {
            fetchCars() {
                $api().get(api_url + 'cars').then(({data}) => {
                    this.cars = data.cars;
                    this.marks = data.marks;
                });
            },
            changeMark(){
                this.filters.model = 'all';
            }
        },
        computed: {
            data: function () {
                return this.cars.filter(car => {
                    let found = true;

                    if (this.filters.mark !== 'all') {
                        found = car.model.mark.id === this.filters.mark;
                    }

                    if (this.filters.model !== 'all') {
                        found = car.model.id === this.filters.model;
                    }

                    return found;
                });
            }
        }
    }
</script>
