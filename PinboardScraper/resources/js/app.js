import { createApp } from 'vue';
import  LinksFilter from './components/LinksFilter.vue';

const app = createApp({});
app.component('links-filter', LinksFilter)
app.mount('#app')