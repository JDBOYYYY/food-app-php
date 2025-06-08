import { createRouter, createWebHistory } from "vue-router";
import { useAuthStore } from "../stores/auth";

import MainLayout from "../layouts/MainLayout.vue";

const routes = [
  {
    path: "/",
    component: MainLayout,
    children: [
      {
        path: "",
        name: "Home",
        component: () => import("../views/HomeView.vue"),
      },
      {
        path: "restaurant/:id",
        name: "RestaurantDetail",
        component: () => import("../views/RestaurantDetailView.vue"),
        props: true,
      },
      {
        path: "cart",
        name: "Cart",
        component: () => import("../views/CartView.vue"),
      },
      {
        path: "checkout",
        name: "Checkout",
        component: () => import("../views/CheckoutView.vue"),
        meta: { requiresAuth: true },
      },
      {
        path: "favorites",
        name: "Favorites",
        component: () => import("../views/FavoritesView.vue"),
        meta: { requiresAuth: true },
      },
      {
        path: "profile",
        name: "profile",
        component: () => import("../views/ProfileView.vue"),
        meta: { requiresAuth: true },
      },
      {
        path: "orders/:id",
        name: "OrderDetails",
        component: () => import("../views/OrderDetailsView.vue"),
        props: true,
        meta: { requiresAuth: true },
      },
      {
        path: "track/:orderId",
        name: "DeliveryTracker",
        component: () => import("../views/DeliveryTracker.vue"),
        props: true,
        meta: { requiresAuth: true },
      },
    ],
  },

  {
    path: "/login",
    name: "Login",
    component: () => import("../views/auth/LoginView.vue"),
    meta: { guestOnly: true },
  },
  {
    path: "/register",
    name: "Register",
    component: () => import("../views/auth/RegisterView.vue"),
    meta: { guestOnly: true },
  },

  {
    path: "/:pathMatch(.*)*",
    name: "NotFound",
    component: () => import("../views/NotFoundView.vue"),
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const auth = useAuthStore();

  const requiresAuth = to.matched.some((record) => record.meta.requiresAuth);
  const requiresAdmin = to.matched.some(
    (record) => record.meta.requiresAdmin,
  );
  const guestOnly = to.matched.some((record) => record.meta.guestOnly);

  if (requiresAuth && !auth.isAuthenticated) {
    next({ name: "Login" });
  }
  else if (requiresAdmin && !auth.isAdmin) {
    next({ name: "Home" });
  }
  else if (guestOnly && auth.isAuthenticated) {
    next({ name: "Home" });
  }
  else {
    next();
  }
});

export default router;