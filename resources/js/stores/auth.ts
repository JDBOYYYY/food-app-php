import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import { authService, type LoginDto, type RegisterDto, type UserDto } from '../services';

export const useAuthStore = defineStore('auth', () => {
  // --- STATE ---
  const token = ref<string | null>(localStorage.getItem('userToken'));
  // The user object will now be stored directly
  const user = ref<UserDto | null>(JSON.parse(localStorage.getItem('user') || 'null'));
  const loading = ref(true);

  // --- GETTERS ---
  const isAuthenticated = computed(() => !!token.value && !!user.value);
  const isAdmin = computed(() => user.value?.role === 'admin');

  // --- ACTIONS ---
  function setUserAndToken(newToken: string | null, newUser: UserDto | null) {
    token.value = newToken;
    user.value = newUser;
    if (newToken) {
      localStorage.setItem('userToken', newToken);
    } else {
      localStorage.removeItem('userToken');
    }
    if (newUser) {
      localStorage.setItem('user', JSON.stringify(newUser));
    } else {
      localStorage.removeItem('user');
    }
  }

    function updateUser(newUserData: UserDto) {
        if (user.value) {
            user.value = { ...user.value, ...newUserData };
        }
    }

  async function signIn(loginData: LoginDto) {
    const response = await authService.login(loginData);
    if (response.token && response.user) {
      setUserAndToken(response.token, response.user);
    }
  }

  async function register(registerData: RegisterDto) {
    const response = await authService.register(registerData);
    if (response.token && response.user) {
      setUserAndToken(response.token, response.user);
    }
  }

  async function signOut() {
    setUserAndToken(null, null);
    // The router guard will handle redirection
  }

  function checkAuthStatus() {
    const storedToken = localStorage.getItem('userToken');
    const storedUser = localStorage.getItem('user');
    if (storedToken && storedUser) {
      token.value = storedToken;
      user.value = JSON.parse(storedUser);
    } else {
      setUserAndToken(null, null);
    }
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
      updateUser,
    checkAuthStatus,
  };
});
