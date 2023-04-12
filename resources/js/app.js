import {createApp} from 'vue';
import App from './App.vue';
import store from './store';
import router from './router';
import 'element-plus/dist/index.css';
import ElementPlus from 'element-plus';
import uiEn from 'element-plus/lib/locale/lang/en';
import '@fortawesome/fontawesome-free/css/all.css';
import * as ElementPlusIconsVue from '@element-plus/icons-vue';

const app = createApp(App)
    .use(store)
    .use(router)
    .use(ElementPlus, {locale: uiEn});

for (const [key, component] of Object.entries(ElementPlusIconsVue)) {
    app.component(`El-Icon-${key}`, component);
}

app.mount('#app');
