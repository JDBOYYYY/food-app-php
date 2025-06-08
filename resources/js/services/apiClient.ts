import { API_URL } from "../constants";
import { useAuthStore } from "../stores/auth";

export class ApiError extends Error {
  response: any;

  constructor(message: string, response: any) {
    super(message);
    this.name = "ApiError";
    this.response = response;
  }
}

interface ApiClientOptions extends RequestInit {
}

async function apiClient<T>(
  endpoint: string,
  options: ApiClientOptions = {},
): Promise<T> {
  const auth = useAuthStore();
  const headers = new Headers(options.headers || {});
  headers.append("Content-Type", "application/json");
  headers.append("Accept", "application/json");

  if (auth.token) {
    headers.append("Authorization", `Bearer ${auth.token}`);
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
    throw new ApiError(message, data);
  }

  return data as T;
}

export default apiClient;