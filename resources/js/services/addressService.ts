import apiClient from "./apiClient";
import type { AddressDto, CreateAddressDto } from "./types";

interface PaginatedResponse<T> {
  data: T[];
}

interface SingleItemResponse<T> {
  data: T;
}

export const addressService = {
  /**
   * Fetches the current user's saved addresses.
   */
  getMyAddresses: (): Promise<PaginatedResponse<AddressDto>> => {
    return apiClient<PaginatedResponse<AddressDto>>("/api/addresses");
  },

  /**
   * Creates a new address for the current user.
   */
  createAddress: (
    addressData: Omit<CreateAddressDto, "UserId">,
  ): Promise<SingleItemResponse<AddressDto>> => {
    return apiClient<SingleItemResponse<AddressDto>>("/api/addresses", {
      method: "POST",
      body: JSON.stringify(addressData),
    });
  },

  /**
   * Updates an existing address for the current user.
   */
  updateAddress: (
    id: number,
    addressData: Partial<CreateAddressDto>,
  ): Promise<SingleItemResponse<AddressDto>> => {
    return apiClient<SingleItemResponse<AddressDto>>(`/api/addresses/${id}`, {
      method: "PUT",
      body: JSON.stringify(addressData),
    });
  },

  /**
   * Deletes an address for the current user.
   */
  deleteAddress: (id: number): Promise<void> => {
    return apiClient<void>(`/api/addresses/${id}`, {
      method: "DELETE",
    });
  },
};