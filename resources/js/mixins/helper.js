import store from '../store';

export default {
    methods: {
        $User() {
            return store.getters.getUser;
        },
        $HasError(errors, field) {
            return errors[field] !== undefined;
        },
        $GetError(errors, field) {
            return errors[field][0];
        }
    }
};
