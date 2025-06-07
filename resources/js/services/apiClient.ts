import { API_URL } from '../constants';
import { useAuthStore } from '../stores/auth';

interface ApiClientOptions extends RequestInit {
  // No need for authToken here, we'll get it from the store
}

async function apiClient<T>(
  endpoint: string,
  options: ApiClientOptions = {},
): Promise<T> {
  const auth = useAuthStore();
  const headers = new Headers(options.headers || {});
  headers.append('Content-Type', 'application/json');
  headers.append('Accept', 'application/json');

  if (auth.token) {
    headers.append('Authorization', `Bearer ${auth.token}`);
  }

  const response = await fetch(`${API_URL}${endpoint}`, {
    ...options,
    headers,
  });

  if (response.status === 204) {
    return undefined as T;
  }

  const data = await response.json();

  if (!response.ok) {
    const message = data.message || `API Error (${response.status})`;
    throw new Error(message);
  }

  // Handle Laravel's pagination wrapper if present
  if (data && typeof data === 'object' && 'data' in data && 'links' in data) {
    return data.data as T;
  }

  return data as T;
}

export default apiClient;