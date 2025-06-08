import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '../stores/auth';

// Import Layouts
import MainLayout from '../layouts/MainLayout.vue';
import AdminLayout from '../layouts/AdminLayout.vue';

// Define the application routes
const routes= [
  // =========================================
  // Main User-Facing Application Layout
  // =========================================
  {
    path: '/',
    component: MainLayout,
    children: [
      {
        path: '',
        name: 'Home',
        component: () => import('../views/HomeView.vue'),
      },
      {
        path: 'restaurant/:id',
        name: 'RestaurantDetail',
        component: () => import('../views/RestaurantDetailView.vue'),
        props: true, // This allows the :id to be passed as a prop
      },
      {
        path: 'cart',
        name: 'Cart',
        component: () => import('../views/CartView.vue'),
        // meta: { requiresAuth: true }, // Requires user to be logged in
      },
      {
        path: 'checkout',
        name: 'Checkout',
        component: () => import('../views/CheckoutView.vue'),
        meta: { requiresAuth: true },
      },
      {
        path: 'favorites',
        name: 'Favorites',
        component: () => import('../views/FavoritesView.vue'),
        meta: { requiresAuth: true },
      },
      {
        path: 'profile',
        name: 'profile',
        component: () => import('../views/ProfileView.vue'),
        meta: { requiresAuth: true },
      },
    ],
  },

  // =========================================
  // Admin Panel Layout
  // =========================================
  {
    path: '/admin',
    component: AdminLayout,
    meta: { requiresAuth: true, requiresAdmin: true }, // Protects all child routes
    children: [
      {
        path: '',
        name: 'AdminDashboard',
        component: () => import('../views/admin/DashboardView.vue'),
      },
      {
        path: 'categories',
        name: 'AdminCategories',
        component: () => import('../views/admin/CategoriesView.vue'),
      },
      {
        path: 'products',
        name: 'AdminProducts',
        component: () => import('../views/admin/ProductsView.vue'),
      },
      {
        path: 'restaurants',
        name: 'AdminRestaurants',
        component: () => import('../views/admin/RestaurantsView.vue'),
      },
      {
        path: 'orders',
        name: 'AdminOrders',
        component: () => import('../views/admin/OrdersView.vue'),
      },
      {
        path: 'users',
        name: 'AdminUsers',
        component: () => import('../views/admin/UsersView.vue'),
      },
    ],
  },

  // =========================================
  // Auth Routes (No Layout)
  // =========================================
  {
    path: '/login',
    name: 'Login',
    component: () => import('../views/auth/LoginView.vue'),
    meta: { guestOnly: true }, // For logged-in users, redirect away
  },
  {
    path: '/register',
    name: 'Register',
    component: () => import('../views/auth/RegisterView.vue'),
    meta: { guestOnly: true },
  },

  // =========================================
  // Catch-all 404 Page
  // =========================================
  {
    path: '/:pathMatch(.*)*',
    name: 'NotFound',
    component: () => import('../views/NotFoundView.vue'),
  },
];

// Create the router instance
const router = createRouter({
  history: createWebHistory(),
  routes,
});

// --- Navigation Guard ---
// This function runs before every route change to protect pages.
router.beforeEach((to, from, next) => {
  const auth = useAuthStore();

  const requiresAuth = to.matched.some((record) => record.meta.requiresAuth);
  const requiresAdmin = to.matched.some((record) => record.meta.requiresAdmin);
  const guestOnly = to.matched.some((record) => record.meta.guestOnly);

  // If user is not logged in and tries to access a protected route
  if (requiresAuth && !auth.isAuthenticated) {
    next({ name: 'Login' });
  }
  // If user is not an admin and tries to access an admin route
  else if (requiresAdmin && !auth.isAdmin) {
    next({ name: 'Home' }); // Redirect to home page
  }
  // If a logged-in user tries to access Login or Register page
  else if (guestOnly && auth.isAuthenticated) {
    next({ name: 'Home' });
  }
  // Otherwise, allow navigation
  else {
    next();
  }
});

export default router;
