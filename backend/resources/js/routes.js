import Vue from 'vue'
import Router from 'vue-router'
import Exercise from './components/ExerciseComponent.vue'
import Plan from './components/PlanComponent.vue'
import User from './components/UserComponent.vue'
Vue.use(Router)

export default new Router({
	routes: [
		{
			path: 'fittech/',
			name: 'exercise',
			component: Exercise
		},
		{
			path: '/fittech/planes',
			name: 'plan',
			component: Plan
		},
		{
			path: '/fittech/users',
			name: 'user',
			component: User
		}
	],
	mode: 'history',
	scrollBehavior() {
		return {x:0, y:0}
	}
})