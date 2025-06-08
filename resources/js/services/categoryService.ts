import apiClient from './apiClient';
import type { CategoryDto } from './types';

// This interface describes the paginated response from Laravel
interface PaginatedResponse<T> {
  data: T[];
  links: any;
  meta: any;
}

export const categoryService = {
  /**
   * Fetches all categories from the paginated API endpoint.
   * It now correctly expects a PaginatedResponse and returns the `data` array.
   */
  async getAll(): Promise<CategoryDto[]> {
    // The apiClient will fetch from '/api/categories'
    // and we expect a PaginatedResponse containing CategoryDto objects.
    const response = await apiClient<PaginatedResponse<CategoryDto>>(
      '/api/categories',
    );
    // We return only the 'data' part of the response, which is the array we need.
    return response.data;
  },
};