import '../../node_modules/jquery/dist/jquery.min.js';
import './bootstrap';
import '../../node_modules/bootstrap/dist/js/bootstrap.min.js';
import TabelaJogadores from './components/TabelaJogadores.vue';
import Home from './components/Home.vue';

import { createApp } from 'vue';

const app = createApp();

app.component('tabelajogadores', TabelaJogadores)
app.component('home', Home)
app.mount('#app');