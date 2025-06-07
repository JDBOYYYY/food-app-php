// vite.config.js
import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";

export default defineConfig({
  plugins: [
    laravel({
      input: ["resources/css/app.css", "resources/js/app.ts"],
      refresh: true,
    }),
    vue(),
  ],
  
  // --- ADD THIS SECTION ---
  server: {
    host: "0.0.0.0", // This makes it listen on all network interfaces
    hmr: {
      host: "localhost", // This tells the browser to connect to localhost
    },
  },
  // ------------------------
});