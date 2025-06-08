
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

export interface UserDto {
  id: number;
  name: string;
  email: string;
  role: 'admin' | 'user';
  created_at: string;
  updated_at: string;
}

export interface CategoryDto {
  Id: number;
  Name: string;
}

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
  isOpen?: boolean;
  reviewCount?: number;
  description?: string;
  isFavorite?: boolean;
}

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

export interface CreateAddressDto {
  Street: string;
  Apartment?: string | null;
  City: string;
  PostalCode: string;
  Country: string;
}



export interface NestedProductInOrderDto {
  Id: number;
  Name: string;
  Description: string | null;
  Price: string;
  ImageUrl: string | null;
}

export interface OrderItemDto {
  Id: number;
  OrderId: string;
  ProductId: string;
  Quantity: number;
  UnitPrice: string;
  product: NestedProductInOrderDto;
}

export interface OrderDto {
  Id: number;
  UserId: number;
  UserName: string;
  OrderDate: string;
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

export interface CreateOrderItemDto {
  ProductId: number;
  Quantity: number;
}

export interface CreateOrderDto {
  ShippingAddressId: number;
  BillingAddressId?: number;
  items: CreateOrderItemDto[];
}


export interface FavoriteItemDto {
  Id: number;
  UserId: number;
  ProductId: number;
  DateAdded: string;
  product?: ProductDto;
}