// This file will hold all the Data Transfer Object (DTO) types
// that align with your Laravel backend.

// --- Auth ---
export interface LoginDto {
  email: string;
  password: string;
  device_name: string;
}

export interface RegisterDto {
  name: string;
  email: string;
  password: string;
  password_confirmation: string;
  device_name: string;
}

export interface LoginResponseDto {
  token: string;
  user: UserDto;
}

// --- User ---
export interface UserDto {
  id: number;
  name: string;
  email: string;
  role: 'admin' | 'user';
  created_at: string;
  updated_at: string;
}

// --- Category ---
export interface CategoryDto {
  Id: number;
  Name: string;
}

// --- Restaurant ---
export interface RestaurantDto {
  Id: number;
  Name: string;
  ImageUrl: string | null;
  AverageRating: number | null;
  DeliveryTime: string | null;
  Distance: string | null;
  PriceRange: string | null;
  Categories?: CategoryDto[];
}

export interface RestaurantDetailDto extends RestaurantDto {
  Products: ProductDto[];
  // Add these new properties
  isOpen?: boolean;
  reviewCount?: number;
  description?: string;
  isFavorite?: boolean;
}

// --- Product ---
export interface ProductDto {
  Id: number;
  Name: string;
  Description: string | null;
  Price: number;
  ImageUrl: string | null;
  RestaurantId: number;
  CategoryId: number;
  Category?: CategoryDto;
  Restaurant?: RestaurantDto;
}