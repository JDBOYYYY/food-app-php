<template>
  <div class="min-h-screen bg-gray-100">
    <div v-if="isLoading" class="flex justify-center items-center h-[80vh]">
      <div class="text-center">
        <div
          class="animate-spin rounded-full h-12 w-12 border-b-2 border-orange-600 mx-auto mb-4"
        ></div>
        <p class="text-gray-600">Loading Delivery Tracker...</p>
      </div>
    </div>

    <div
      v-else-if="error"
      class="flex flex-col justify-center items-center h-[80vh] text-center p-4"
    >
      <h2 class="text-2xl font-bold text-red-600 mb-2">
        Could not load tracker
      </h2>
      <p class="text-gray-600 mb-6">{{ error }}</p>
      <button
        @click="router.push('/')"
        class="bg-orange-500 text-white px-6 py-2 rounded-lg hover:bg-orange-600"
      >
        Back to Home
      </button>
    </div>

    <div
      v-else-if="order"
      class="container mx-auto px-4 sm:px-6 lg:px-8 py-8"
    >
      <div class="flex items-center justify-between mb-6">
        <div>
          <h1 class="text-3xl font-bold text-gray-900">
            Order #{{ order.Id }}
          </h1>
          <p class="text-gray-600">
            From
            <span class="font-medium">{{
              order.OrderItems[0]?.product.Restaurant?.Name
            }}</span>
          </p>
        </div>
        <div
          class="bg-green-100 text-green-800 px-3 py-1.5 rounded-full text-sm font-medium flex items-center"
        >
          <CheckCircle class="w-4 h-4 mr-2" />
          {{ orderStatus }}
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        <div class="lg:col-span-2">
          <div
            id="map"
            class="w-full h-[600px] rounded-lg shadow-md z-10"
          ></div>
        </div>

        <div class="lg:col-span-1 space-y-6">
          <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="font-bold text-lg mb-4">Your Driver</h3>
            <div class="flex items-center space-x-4">
              <div
                class="w-14 h-14 bg-gray-200 rounded-full flex items-center justify-center"
              >
                <span class="text-xl font-semibold text-gray-600">MR</span>
              </div>
              <div>
                <h4 class="font-semibold text-lg">Mike Rodriguez</h4>
                <div class="flex items-center space-x-1 text-sm">
                  <Star class="w-4 h-4 fill-yellow-400 text-yellow-400" />
                  <span class="font-medium">4.9</span>
                  <span class="text-gray-500">(247)</span>
                </div>
              </div>
            </div>
          </div>

          <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="font-bold text-lg mb-4">Live Status</h3>
            <div class="space-y-4">
              <div
                v-for="status in orderTimeline"
                :key="status.name"
                class="flex items-center space-x-4"
              >
                <div
                  class="w-3 h-3 rounded-full ring-4 ring-offset-1"
                  :class="status.ringClass"
                ></div>
                <div class="text-sm">
                  <p class="font-medium" :class="status.textClass">
                    {{ status.name }}
                  </p>
                  <p class="text-gray-500">{{ status.time }}</p>
                </div>
              </div>
            </div>
          </div>

          <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="font-bold text-lg mb-4">Order Summary</h3>
            <div class="space-y-2 text-sm">
              <div
                v-for="item in order.OrderItems"
                :key="item.Id"
                class="flex justify-between"
              >
                <span>{{ item.Quantity }}x {{ item.product.Name }}</span>
                <span
                  >{{ (item.Quantity * item.UnitPrice).toFixed(2) }} zł</span
                >
              </div>
              <hr class="border-gray-200 !my-3" />
              <div class="flex justify-between font-semibold">
                <span>Total</span>
                <span>{{ order.TotalAmount.toFixed(2) }} zł</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import { orderService } from "@/services";
import { Star, CheckCircle } from "lucide-vue-next";


const route = useRoute();
const router = useRouter();

const isLoading = ref(true);
const error = ref(null);
const order = ref(null);

const estimatedTime = ref(12);
const orderStatus = ref("On the way");
const map = ref(null);
const orderTimeline = ref([]);

let timeInterval = null;
let animationFrameId = null;

const initializeMap = async () => {
  try {
    const L = await import("leaflet");
    await import("leaflet/dist/leaflet.css");

    window.L = L;

    await import("leaflet-routing-machine");
    await import("leaflet-routing-machine/dist/leaflet-routing-machine.css");

    delete L.Icon.Default.prototype._getIconUrl;
    L.Icon.Default.mergeOptions({
      iconRetinaUrl:
        "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon-2x.png",
      iconUrl:
        "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon.png",
      shadowUrl:
        "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png",
    });

    const restaurantIcon = new L.Icon({
      iconUrl:
        "data:image/svg+xml;base64," +
        btoa(
          `<svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="16" cy="16" r="16" fill="#f97316"/><path d="M12 8v16M16 8v16M20 8v4c0 2-1 3-2 3s-2-1-2-3V8" stroke="white" strokeWidth="2" strokeLinecap="round"/></svg>`,
        ),
      iconSize: [32, 32],
      iconAnchor: [16, 32],
    });

    const customerIcon = new L.Icon({
      iconUrl:
        "data:image/svg+xml;base64," +
        btoa(
          `<svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="16" cy="16" r="16" fill="#ef4444"/><path d="M16 8c-4 0-7 3-7 7 0 7 7 13 7 13s7-6 7-13c0-4-3-7-7-7z" fill="white"/><circle cx="16" cy="15" r="3" fill="#ef4444"/></svg>`,
        ),
      iconSize: [32, 32],
      iconAnchor: [16, 32],
    });

    const driverIcon = new L.Icon({
      iconUrl:
        "data:image/svg+xml;base64," +
        btoa(
          `<svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="20" cy="20" r="20" fill="#3b82f6" stroke="white" stroke-width="2"/><path d="M20 12 L28 28 H12 Z" fill="white" transform="rotate(0 20 20)"/></svg>`,
        ),
      iconSize: [40, 40],
      iconAnchor: [20, 20],
    });

    if (map.value) map.value.remove();
    const mapInstance = L.map("map", { zoomControl: false }).setView(
      [52.23, 21.01],
      14,
    );
    map.value = mapInstance;
    L.control.zoom({ position: "bottomright" }).addTo(mapInstance);

    L.tileLayer(
      "https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png",
      {
        attribution:
          '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
      },
    ).addTo(mapInstance);

    const restaurantLocation = L.latLng(52.237, 21.017);
    const customerLocation = L.latLng(52.229, 21.012);

    const driverMarker = L.marker(restaurantLocation, {
      icon: driverIcon,
      zIndexOffset: 1000,
    }).addTo(mapInstance);

    const routingControl = L.Routing.control({
      waypoints: [restaurantLocation, customerLocation],
      routeWhileDragging: false,
      show: false,
      addWaypoints: false,
      createMarker: function (i, waypoint, n) {
        const icon = i === 0 ? restaurantIcon : customerIcon;
        return L.marker(waypoint.latLng, { icon });
      },
      lineOptions: {
        styles: [{ color: "#3b82f6", opacity: 0.8, weight: 6 }],
      },
    }).addTo(mapInstance);

    routingControl.on("routesfound", function (e) {
      const route = e.routes[0];
      const coordinates = route.coordinates;
      animateDriver(driverMarker, coordinates);
    });
  } catch (e) {
    console.error("Error initializing map:", e);
  }
};

const animateDriver = (marker, route) => {
  let step = 0;
  const move = () => {
    if (step >= route.length - 1) return;

    const start = route[step];
    const end = route[step + 1];
    let progress = 0;
    const duration = 2000;
    let startTime = null;

    const animationStep = (timestamp) => {
      if (!startTime) startTime = timestamp;
      const elapsed = timestamp - startTime;
      progress = elapsed / duration;

      if (progress < 1) {
        const lat = start.lat + (end.lat - start.lat) * progress;
        const lng = start.lng + (end.lng - start.lng) * progress;
        marker.setLatLng([lat, lng]);
        animationFrameId = requestAnimationFrame(animationStep);
      } else {
        marker.setLatLng(end);
        step++;
        setTimeout(move, 500);
      }
    };
    animationFrameId = requestAnimationFrame(animationStep);
  };
  setTimeout(move, 1000);
};

const fetchOrderDetails = async () => {
  const orderId = route.params.orderId;
  try {
    const response = await orderService.getOrderById(orderId);
    order.value = response.data;

    const orderDate = new Date(order.value.OrderDate);
    orderTimeline.value = [
      {
        name: "Order Confirmed",
        time: orderDate.toLocaleTimeString([], {
          hour: "2-digit",
          minute: "2-digit",
        }),
        ringClass: "ring-green-500",
        textClass: "",
      },
      {
        name: "Preparing Order",
        time: new Date(orderDate.getTime() + 5 * 60000).toLocaleTimeString(
          [],
          { hour: "2-digit", minute: "2-digit" },
        ),
        ringClass: "ring-green-500",
        textClass: "",
      },
      {
        name: "Out for Delivery",
        time: new Date(orderDate.getTime() + 15 * 60000).toLocaleTimeString(
          [],
          { hour: "2-digit", minute: "2-digit" },
        ),
        ringClass: "ring-green-500",
        textClass: "",
      },
      {
        name: "On the Way",
        time: `ETA: ${Math.ceil(estimatedTime.value)} mins`,
        ringClass: "ring-orange-500 animate-pulse",
        textClass: "text-orange-600",
      },
    ];
  } catch (e) {
    error.value = e.message || "Could not find the specified order.";
  } finally {
    isLoading.value = false;
  }
};

onMounted(async () => {
  await fetchOrderDetails();
  if (!error.value) {
    await initializeMap();
    timeInterval = setInterval(() => {
      estimatedTime.value = Math.max(estimatedTime.value - 0.3, 2);
      const onTheWayStatus = orderTimeline.value.find(
        (s) => s.name === "On the Way",
      );
      if (onTheWayStatus) {
        onTheWayStatus.time = `ETA: ${Math.ceil(estimatedTime.value)} mins`;
      }
    }, 2000);
  }
});

onUnmounted(() => {
  if (timeInterval) clearInterval(timeInterval);
  if (map.value) map.value.remove();
  if (animationFrameId) cancelAnimationFrame(animationFrameId);
});
</script>

<style scoped>
:deep(.leaflet-routing-container) {
  display: none !important;
}
</style>