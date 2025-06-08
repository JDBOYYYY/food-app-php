import apiClient from "./apiClient";
import { categoryService } from "./categoryService";
import type {
  RestaurantDto,
  CategoryDto,
  RestaurantDetailDto,
} from "./types";
import type { RestaurantListItemProps } from "../components/RestaurantListItem.vue";
import { favoriteService } from "./favoriteService";
import { useAuthStore } from "../stores/auth"; // <-- IMPORT THE AUTH STORE

// This interface is for internal use within the service
interface CategoryWithIcon extends CategoryDto {
  icon?: string;
}

// This is the shape of the data the service will provide to the HomeView
export interface HomePageData {
  restaurants: RestaurantListItemProps[];
  categories: CategoryWithIcon[];
}

interface PaginatedResponse<T> {
  data: T[];
}

const categoryIcons: { [key: string]: string } = {
  Pizzas: "🍕",
  Burgery: "🍔",
  Przystawki: "🥗",
  "Dania główne": "🍽️",
  Sałatki: "🥬",
  Zupy: "🥣",
  Desery: "🍰",
  Napoje: "🥤",
  Śniadania: "🍳",
  Kanapki: "🥪",
  Tortilla: "🌯",
};

const mapDtoToProps = (dto: RestaurantDto): RestaurantListItemProps => ({
  id: dto.Id,
  name: dto.Name,
  imageUrl: dto.ImageUrl,
  rating: dto.AverageRating,
  deliveryTime: dto.DeliveryTime,
  distance: dto.Distance,
  priceRange: dto.PriceRange,
  cuisineType: dto.Categories?.map((c) => c.Name).join(", ") || "Various",
  categories: dto.Categories,
  isFavorite: false,
  isOpen: Math.random() > 0.2,
  promoLabel:
    dto.AverageRating && dto.AverageRating > 4.5 ? "Top Rated" : undefined,
});

export const restaurantService = {
  async getHomePageData(): Promise<HomePageData> {
    const [restaurantResponse, categoryData] = await Promise.all([
      apiClient<PaginatedResponse<RestaurantDto>>("/api/restaurants"),
      categoryService.getAll(),
    ]);

    const mappedRestaurants = restaurantResponse.data.map(mapDtoToProps);
    const mappedCategories = categoryData.map((cat) => ({
      ...cat,
      icon: categoryIcons[cat.Name] || "🍴",
    }));

    return {
      restaurants: mappedRestaurants,
      categories: mappedCategories,
    };
  },

  getById: (id: number): Promise<RestaurantDto> => {
    return apiClient<RestaurantDto>(`/api/restaurants/${id}`);
  },

  async toggleFavorite(
    restaurantId: number,
    isFavorite: boolean,
  ): Promise<void> {
    if (isFavorite) {
      await favoriteService.addRestaurantFavorite(restaurantId);
    } else {
      await favoriteService.removeRestaurantFavorite(restaurantId);
    }
  },

  // --- REFACTORED THIS ENTIRE FUNCTION ---
  async getRestaurantPageData(
    restaurantId: number,
  ): Promise<RestaurantDetailDto> {
    const auth = useAuthStore();

    // 1. Always fetch the public restaurant data
    const restaurantResponse = await apiClient<{ data: RestaurantDetailDto }>(
      `/api/restaurants/${restaurantId}`,
    );
    const restaurantData = restaurantResponse.data;

    // 2. Initialize favorite data as empty/false
    let favoriteProductIds = new Set<number>();
    let isRestaurantFavorite = false;

    // 3. If the user is logged in, fetch their private favorites data
    if (auth.isAuthenticated) {
      try {
        const favoritesResponse = await favoriteService.getUserFavorites();
        favoriteProductIds = new Set(
          favoritesResponse.products.map((favProduct) => favProduct.Id),
        );
        isRestaurantFavorite = favoritesResponse.restaurants.some(
          (r) => r.Id === restaurantId,
        );
      } catch (e) {
        console.error(
          "Could not fetch user favorites, proceeding without them.",
          e,
        );
      }
    }

    // 4. Map favorite status to products
    if (restaurantData.Products) {
      restaurantData.Products = restaurantData.Products.map((product) => ({
        ...product,
        isFavorite: favoriteProductIds.has(product.Id),
      }));
    }

    // 5. Add mock data and final favorite status
    restaurantData.isOpen = Math.random() > 0.2;
    restaurantData.reviewCount = Math.floor(Math.random() * 200) + 50;
    restaurantData.description =
      "A delightful place offering the best dishes from this region. Our chefs use only the freshest ingredients to bring you an unforgettable dining experience.";
    restaurantData.isFavorite = isRestaurantFavorite;

    return restaurantData;
  },
};