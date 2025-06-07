import apiClient from './apiClient';
import type { AddressDto } from './types';

export const addressService = {
  getMyAddresses: (): Promise<{ data: AddressDto[] }> => {
    return apiClient<{ data: AddressDto[] }>('/api/addresses');
  },
  // Add createAddress if needed later
};