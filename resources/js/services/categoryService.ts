import apiClient from './apiClient';
import type { CategoryDto } from './types';

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
    const response = await apiClient<PaginatedResponse<CategoryDto>>(
      '/api/categories',
    );
    return response.data;
  },
};