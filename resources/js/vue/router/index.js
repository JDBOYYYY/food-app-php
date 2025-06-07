import { createRouter, createWebHistory } from 'vue-router';
import Home from '../pages/Home.vue';
import Restaurants from '../pages/Restaurants.vue';
import RestaurantDetail from '../pages/RestaurantDetail.vue';

const routes = [
  { path: '/', name: 'home', component: Home },
  { path: '/restaurants', name: 'restaurants', component: Restaurants },
  { path: '/restaurants/:id', name: 'restaurant-detail', component: RestaurantDetail, props: true },
];

export default createRouter({
  history: createWebHistory(),
  routes,
});
