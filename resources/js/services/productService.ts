import apiClient from './apiClient';
import type { ProductDto } from './types';

export const productService = {
  getAll: (): Promise<ProductDto[]> => {
    return apiClient<ProductDto[]>('/api/products');
  },
};