import '../../node_modules/jquery/dist/jquery.min.js';
import '../../node_modules/jquery-maskmoney/dist/jquery.maskMoney.min.js';
import '../../node_modules/moment/dist/moment.js';
import './bootstrap';
import '../../node_modules/bootstrap/dist/js/bootstrap.min.js';
import TabelaJogadores from './components/TabelaJogadores.vue';
import Home from './components/Home.vue';
import Financeiro from './components/Financeiro.vue';
import Jogos from './components/Jogos.vue';

import { createApp } from 'vue';

const app = createApp();

app.component('tabelajogadores', TabelaJogadores)
app.component('home', Home)
app.component('financeiro', Financeiro)
app.component('jogos', Jogos)
app.mount('#app');