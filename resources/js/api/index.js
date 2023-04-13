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
        const messages = {
            '401': 'Unauthenticated.',
            '404': 'Record not found.',
            '405': 'Access denied.',
            '500': 'Server error.'
        };

        const status = error?.response?.status;

        ElMessage({
            message: messages[status] ? messages[status] : 'Something went wrong.',
            showClose: true,
            type: 'warning'
        });

        return error;
    });

    return axios;
};
