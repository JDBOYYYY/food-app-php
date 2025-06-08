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

export interface AddressDto {
  Id: number;
  Street: string;
  Apartment: string | null;
  City: string;
  PostalCode: string;
  Country: string;
}

// ... (other types like AddressDto, CreateOrderDto)

export interface OrderItemDto {
  Id: number;
  Name: string;
  Quantity: number;
  Price: number;
}

export interface AddressDto {
  Id: number;
  Street: string;
  Apartment: string | null;
  City: string;
  PostalCode: string;
  Country: string;
}

// --- ADD THIS INTERFACE ---
export interface CreateAddressDto {
  Street: string;
  Apartment?: string | null;
  City: string;
  PostalCode: string;
  Country: string;
}

// ... (keep all other DTOs like AddressDto, ProductDto, etc.)

// --- Order & OrderItem DTOs ---

// Describes a Product object when it's nested inside an OrderItem
export interface NestedProductInOrderDto {
  Id: number;
  Name: string;
  Description: string | null;
  Price: string; // API sends price as a string here
  ImageUrl: string | null;
}

// Describes an OrderItem object as returned by the API inside an Order
export interface OrderItemDto {
  Id: number;
  OrderId: string;
  ProductId: string;
  Quantity: number;
  UnitPrice: string; // API sends price as a string here
  product: NestedProductInOrderDto;
}

// Describes the full Order object returned by the API
export interface OrderDto {
  Id: number;
  UserId: number;
  UserName: string;
  OrderDate: string; // ISO 8601 date string
  TotalAmount: number;
  Status: string;
  ShippingAddressId: number;
  ShippingAddress: AddressDto;
  BillingAddressId: number;
  BillingAddress: AddressDto;
  OrderItems: OrderItemDto[];
  created_at: string;
  updated_at: string;
}

// This remains the same for creating an order
export interface CreateOrderItemDto {
  ProductId: number;
  Quantity: number;
}

export interface CreateOrderDto {
  ShippingAddressId: number;
  BillingAddressId?: number;
  items: CreateOrderItemDto[];
}
//
// -------------------------

// ... (the rest of your types)

export interface FavoriteItemDto {
  Id: number;
  UserId: number;
  ProductId: number;
  DateAdded: string;
  // The API might return the full product object nested
  product?: ProductDto;
}