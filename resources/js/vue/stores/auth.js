import { reactive, toRefs } from 'vue';

const state = reactive({
  token: localStorage.getItem('token') || null,
});

export function useAuth() {
  const login = async (email, password) => {
    const res = await fetch('/api/AuthControler/login', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email, password })
    });
    if (!res.ok) throw new Error('Login failed');
    const data = await res.json();
    state.token = data.token;
    localStorage.setItem('token', data.token);
  };

  const logout = () => {
    state.token = null;
    localStorage.removeItem('token');
  };

  return { ...toRefs(state), login, logout };
}
