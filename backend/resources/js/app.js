require('./bootstrap');

window.Vue = require('vue');

Vue.component('principal', require('./components/MainComponent.vue').default);
Vue.component('exercise', require('./components/ExerciseComponent.vue').default);
Vue.component('plan', require('./components/PlanComponent.vue').default);
Vue.component('user', require('./components/UserComponent.vue').default);

import router from './routes'

const app = new Vue({
    el: '#app',
    router
});
