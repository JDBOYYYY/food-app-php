import apiClient from './apiClient';
import type { OrderDto, CreateOrderDto } from './types';

export const orderService = {
  createOrder: (data: CreateOrderDto): Promise<OrderDto> => {
    return apiClient<OrderDto>('/api/orders', {
      method: 'POST',
      body: JSON.stringify(data),
    });
  },
};