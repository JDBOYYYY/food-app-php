import { defineStore } from "pinia";
import { ref, computed, watch } from "vue";

export interface CartItem {
  id: string;
  name: string;
  price: number;
  quantity: number;
  image?: any;
  restaurantId: number;
  restaurantName: string; // <-- ADDED
}

export const useCartStore = defineStore("cart", () => {
  const items = ref<CartItem[]>(
    JSON.parse(localStorage.getItem("cartItems") || "[]"),
  );
  const isFlyoutOpen = ref(false);

  watch(
    items,
    (cartItems) => {
      localStorage.setItem("cartItems", JSON.stringify(cartItems));
    },
    { deep: true },
  );

  const itemCount = computed(() =>
    items.value.reduce((sum, item) => sum + item.quantity, 0),
  );
  const totalPrice = computed(() =>
    items.value.reduce((sum, item) => sum + item.price * item.quantity, 0),
  );
  const getCartRestaurantId = computed(() =>
    items.value.length > 0 ? items.value[0].restaurantId : null,
  );
  // NEW COMPUTED PROPERTY
  const getCartRestaurantName = computed(() =>
    items.value.length > 0 ? items.value[0].restaurantName : null,
  );

  function openFlyout() {
    isFlyoutOpen.value = true;
  }

  function closeFlyout() {
    isFlyoutOpen.value = false;
  }

  function canAddToCart(productRestaurantId: number): boolean {
    if (items.value.length === 0) return true;
    return items.value[0].restaurantId === productRestaurantId;
  }

  function addToCart(product: Omit<CartItem, "quantity">, quantity = 1) {
    if (!canAddToCart(product.restaurantId)) {
      if (
        confirm(
          "You can only order from one restaurant at a time. Clear your cart to add this item?",
        )
      ) {
        items.value = [{ ...product, quantity }];
      }
      return;
    }

    const existingItem = items.value.find((item) => item.id === product.id);
    if (existingItem) {
      existingItem.quantity += quantity;
    } else {
      items.value.push({ ...product, quantity });
    }
  }

  function updateQuantity(productId: string, quantity: number) {
    if (quantity <= 0) {
      removeFromCart(productId);
    } else {
      const item = items.value.find((i) => i.id === productId);
      if (item) {
        item.quantity = quantity;
      }
    }
  }

  function removeFromCart(productId: string) {
    items.value = items.value.filter((item) => item.id !== productId);
  }

  function clearCart() {
    items.value = [];
  }

  return {
    items,
    isFlyoutOpen,
    itemCount,
    totalPrice,
    getCartRestaurantId,
    getCartRestaurantName, // <-- EXPORTED
    openFlyout,
    closeFlyout,
    canAddToCart,
    addToCart,
    updateQuantity,
    removeFromCart,
    clearCart,
  };
});