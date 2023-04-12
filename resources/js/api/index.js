import axios from "axios";
import store from "../store";
import {ElMessage} from 'element-plus';

export const $api = () => {
    axios.defaults.headers.common['Accept'] = 'application/json';
    axios.defaults.headers.common['Authorization'] = 'Bearer ' + store.getters.getToken;
    axios.defaults.headers.common['Content-Type'] = 'multipart/form-data';

    axios.interceptors.response.use(response => {
        return response;
    }, error => {
        ElMessage({
            message: error.response.data.message,
            showClose: true,
            type: 'warning'
        });

        return error;
    });

    return axios;
};
