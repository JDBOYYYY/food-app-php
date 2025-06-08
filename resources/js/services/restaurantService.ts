import apiClient from './apiClient';
import { categoryService } from './categoryService';
import type { RestaurantDto, CategoryDto, RestaurantDetailDto } from './types';
import type { RestaurantListItemProps } from '../components/RestaurantListItem.vue';
import {favoriteService} from './favoriteService'

// This interface is for internal use within the service
interface CategoryWithIcon extends CategoryDto {
  icon?: string;
}

// This is the shape of the data the service will provide to the HomeView
export interface HomePageData {
  restaurants: RestaurantListItemProps[];
  categories: CategoryWithIcon[];
}

const categoryIcons: { [key: string]: string } = {
  Pizzas: '🍕',
  Burgery: '🍔',
  Przystawki: '🥗',
  'Dania główne': '🍽️',
  Sałatki: '🥬',
  Zupy: '🥣',
  Desery: '🍰',
  Napoje: '🥤',
  Śniadania: '🍳',
  Kanapki: '🥪',
  Tortilla: '🌯',
};

const mapDtoToProps = (dto: RestaurantDto): RestaurantListItemProps => ({
  id: dto.Id,
  name: dto.Name,
  imageUrl: dto.ImageUrl,
  rating: dto.AverageRating,
  deliveryTime: dto.DeliveryTime,
  distance: dto.Distance,
  priceRange: dto.PriceRange,
  cuisineType: dto.Categories?.map((c) => c.Name).join(', ') || 'Various',
  categories: dto.Categories,
  isFavorite: false, // This will be handled by a dedicated favorites store later
  isOpen: Math.random() > 0.2, // Mock data, as not present in API DTO
  promoLabel:
    dto.AverageRating && dto.AverageRating > 4.5 ? 'Top Rated' : undefined,
});

export const restaurantService = {
  /**
   * Fetches all data needed for the home page (restaurants and categories)
   * and returns it in a format ready for the view.
   */
  async getHomePageData(): Promise<HomePageData> {
    // Fetch both data types in parallel for efficiency
    const [restaurantData, categoryData] = await Promise.all([
      apiClient<RestaurantDto[]>('/api/restaurants'),
      categoryService.getAll(), // Use the existing category service
    ]);

    const mappedRestaurants = restaurantData.map(mapDtoToProps);
    const mappedCategories = categoryData.map((cat) => ({
      ...cat,
      icon: categoryIcons[cat.Name] || '🍴',
    }));

    return {
      restaurants: mappedRestaurants,
      categories: mappedCategories,
    };
  },

  /**
   * Fetches a single restaurant by its ID.
   * (This function remains from before)
   */
  getById: (id: number): Promise<RestaurantDto> => {
    // Note: The original return type was RestaurantDetailDto, but your API
    // likely returns a paginated RestaurantDto. Adjust if needed.
    return apiClient<RestaurantDto>(`/api/restaurants/${id}`);
  },

  /**
   * Toggles the favorite status of a restaurant.
   * (This is where the API call will go in the future)
   */
  async toggleFavorite(
    restaurantId: number,
    isFavorite: boolean,
  ): Promise<void> {
    console.log(
      `API CALL (mock): Setting restaurant ${restaurantId} favorite status to ${isFavorite}`,
    );
    // In the future, this will be:
    // if (isFavorite) {
    //   await apiClient(`/api/favorites/restaurant/${restaurantId}`, { method: 'POST' });
    // } else {
    //   await apiClient(`/api/favorites/restaurant/${restaurantId}`, { method: 'DELETE' });
    // }
    return Promise.resolve();
  },

  async getRestaurantPageData(restaurantId: number): Promise<RestaurantDetailDto> {
    const [restaurantResponse, favoritesResponse] = await Promise.all([
      apiClient<{ data: RestaurantDetailDto }>(`/api/restaurants/${restaurantId}`),
      favoriteService.getUserFavorites(), // This now returns AllFavoritesResponse directly
    ]);

    const restaurantData = restaurantResponse.data;
    
    // --- THIS IS THE FIX ---
    // We access the 'products' array directly on the favoritesResponse object
    const favoriteProductIds = new Set(
      favoritesResponse.products.map((favProduct) => favProduct.Id),
    );

    if (restaurantData.Products) {
      restaurantData.Products = restaurantData.Products.map((product) => ({
        ...product,
        isFavorite: favoriteProductIds.has(product.Id),
      }));
    }

    // Add mock data that is missing from the API response
    restaurantData.isOpen = Math.random() > 0.2;
    restaurantData.reviewCount = Math.floor(Math.random() * 200) + 50;
    restaurantData.description =
      'A delightful place offering the best dishes from this region. Our chefs use only the freshest ingredients to bring you an unforgettable dining experience.';
    
    // We can also fetch user's favorite status for this restaurant here in the future
    restaurantData.isFavorite = false; // Placeholder

    return restaurantData;
  },
};