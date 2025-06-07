import apiClient from './apiClient';

export const favoriteService = {
  /**
   * Adds a restaurant to the user's favorites.
   * The backend will get the user ID from the token.
   */
  addRestaurantFavorite: (restaurantId: number): Promise<void> => {
    // The API endpoint from your mobile app was /api/favorites/restaurant/{id}
    // We assume a similar structure. Adjust if your Laravel API is different.
    // The backend should return a 201 or 204 status on success.
    return apiClient(`/api/products/${restaurantId}/favorite`, {
      method: 'POST',
    });
  },

  /**
   * Removes a restaurant from the user's favorites.
   */
  removeRestaurantFavorite: (restaurantId: number): Promise<void> => {
    return apiClient(`/api/products/${restaurantId}/unfavorite`, {
      method: 'DELETE',
    });
  },
};