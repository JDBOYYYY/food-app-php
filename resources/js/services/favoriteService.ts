import apiClient from './apiClient';
import type { ProductDto, RestaurantDto } from './types';

export interface AllFavoritesResponse {
  products: ProductDto[];
  restaurants: RestaurantDto[];
}

export const favoriteService = {
  /**
   * Fetches all of the user's favorite products and restaurants.
   */
  getUserFavorites: (): Promise<AllFavoritesResponse> => {
    return apiClient<AllFavoritesResponse>('/api/favorites');
  },

  /**
   * Adds a PRODUCT to the user's favorites.
   */
  addProductFavorite: (productId: number): Promise<void> => {
    return apiClient(`/api/products/${productId}/favorite`, { method: 'POST' });
  },

  /**
   * Removes a PRODUCT from the user's favorites.
   */
  removeProductFavorite: (productId: number): Promise<void> => {
    return apiClient(`/api/products/${productId}/unfavorite`, {
      method: 'DELETE',
    });
  },

  /**
   * Adds a RESTAURANT to the user's favorites.
   */
  addRestaurantFavorite: (restaurantId: number): Promise<void> => {
    return apiClient(`/api/restaurants/${restaurantId}/favorite`, {
      method: 'POST',
    });
  },

  /**
   * Removes a RESTAURANT from the user's favorites.
   */
  removeRestaurantFavorite: (restaurantId: number): Promise<void> => {
    return apiClient(`/api/restaurants/${restaurantId}/unfavorite`, {
      method: 'DELETE',
    });
  },
};