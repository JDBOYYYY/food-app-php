import { defineStore } from "pinia";
import { ref, computed } from "vue";
import { useRouter } from "vue-router";
import { jwtDecode, type JwtPayload } from "jwt-decode";
import { authService, type LoginDto, type RegisterDto } from "../services/index";

interface CustomJwtPayload extends JwtPayload {
  role?: string | string[];
  name?: string;
  nameid?: string; // Common claim for user ID
}

export const useAuthStore = defineStore("auth", () => {
  const token = ref<string | null>(localStorage.getItem("userToken"));
  const user = ref<{ id: string | null; name: string | null; roles: string[] } | null>(null);
  const loading = ref(true);

  const isAuthenticated = computed(() => !!token.value);
  const isAdmin = computed(() => user.value?.roles.includes("Admin") ?? false);

  function decodeAndSetUser(jwt: string | null) {
    if (!jwt) {
      user.value = null;
      return;
    }
    try {
      const decoded = jwtDecode<CustomJwtPayload>(jwt);
      const roles = Array.isArray(decoded.role)
        ? decoded.role
        : decoded.role
          ? [decoded.role]
          : [];
      user.value = {
        id: decoded.sub ?? decoded.nameid ?? null,
        name: decoded.name ?? null,
        roles: roles,
      };
    } catch (e) {
      console.error("Failed to decode token:", e);
      user.value = null;
    }
  }

  async function signIn(loginData: LoginDto) {
    const response = await authService.login(loginData);
    if (response.token) {
      localStorage.setItem("userToken", response.token);
      token.value = response.token;
      decodeAndSetUser(response.token);
    }
  }

  async function register(registerData: RegisterDto) {
    await authService.register(registerData);
  }

  async function signOut() {
    localStorage.removeItem("userToken");
    token.value = null;
    user.value = null;
    // The router guard will handle redirection
  }

  function checkAuthStatus() {
    loading.value = true;
    const storedToken = localStorage.getItem("userToken");
    token.value = storedToken;
    decodeAndSetUser(storedToken);
    loading.value = false;
  }

  return {
    token,
    user,
    loading,
    isAuthenticated,
    isAdmin,
    signIn,
    register,
    signOut,
    checkAuthStatus,
  };
});