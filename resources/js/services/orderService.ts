import apiClient from "./apiClient";
import type { OrderDto, CreateOrderDto } from "./types";

interface PaginatedOrdersResponse {
  data: OrderDto[];
}

interface SingleOrderResponse {
  data: OrderDto;
}

export const orderService = {
  /**
   * Fetches the order history for the logged-in user.
   */
  getMyOrders: (): Promise<PaginatedOrdersResponse> => {
    return apiClient<PaginatedOrdersResponse>("/api/orders");
  },

  /**
   * Fetches a single order by its ID.
   */
  getOrderById: (id: number): Promise<SingleOrderResponse> => {
    return apiClient<SingleOrderResponse>(`/api/orders/${id}`);
  },

  /**
   * Creates a new order.
   */
  createOrder: (data: CreateOrderDto): Promise<OrderDto> => {
    return apiClient<OrderDto>("/api/orders", {
      method: "POST",
      body: JSON.stringify(data),
    });
  },
};