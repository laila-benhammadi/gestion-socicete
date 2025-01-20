import { createApp } from 'vue'
import App from './App.vue'
import './assets/main.css';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap-icons/font/bootstrap-icons.css';
import router from './routes/index'; // Assurez-vous que le chemin est correct
import 'font-awesome/css/font-awesome.min.css';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';

// Create the Vue app
const app = createApp(App);

// Use the router before mounting the app
app.use(router);

// Mount the app
app.mount('#app');
