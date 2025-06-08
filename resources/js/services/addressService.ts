import apiClient from './apiClient';
import type { AddressDto, CreateAddressDto } from './types';

// This interface describes the paginated response from Laravel for a list
interface PaginatedResponse<T> {
  data: T[];
}

// This interface describes the response for a single created item
interface SingleItemResponse<T> {
  data: T;
}

export const addressService = {
  /**
   * Fetches the current user's saved addresses.
   * The API returns a paginated response, so we extract the `data` array.
   */
  getMyAddresses: (): Promise<PaginatedResponse<AddressDto>> => {
    return apiClient<PaginatedResponse<AddressDto>>('/api/addresses');
  },

  /**
   * Creates a new address for the current user.
   * The API returns a single item response, so we extract the `data` object.
   */
  createAddress: (
    addressData: Omit<CreateAddressDto, 'UserId'>,
  ): Promise<SingleItemResponse<AddressDto>> => {
    return apiClient<SingleItemResponse<AddressDto>>('/api/addresses', {
      method: 'POST',
      body: JSON.stringify(addressData),
    });
  },
};