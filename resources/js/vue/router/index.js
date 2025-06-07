import { createRouter, createWebHistory } from 'vue-router';
import Home from '../pages/Home.vue';
import Restaurants from '../pages/Restaurants.vue';
import RestaurantDetail from '../pages/RestaurantDetail.vue';
import Login from '../pages/Login.vue';
import Favorites from '../pages/Favorites.vue';

const routes = [
  { path: '/', name: 'home', component: Home },
  { path: '/login', name: 'login', component: Login },
  { path: '/restaurants', name: 'restaurants', component: Restaurants },
  { path: '/restaurants/:id', name: 'restaurant-detail', component: RestaurantDetail, props: true },
  { path: '/favorites', name: 'favorites', component: Favorites },
];

export default createRouter({
  history: createWebHistory(),
  routes,
});
