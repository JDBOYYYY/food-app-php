// src/services/orderService.ts

import apiClient from "./apiClient";
import type { OrderDto, CreateOrderDto } from "./types";

// Odpowiedź z API dla listy zamówień będzie paginowana
interface PaginatedOrdersResponse {
    data: OrderDto[];
    // Możesz dodać 'links' i 'meta', jeśli będziesz ich potrzebować
}

export const orderService = {
    /**
     * Pobiera historię zamówień zalogowanego użytkownika.
     * Odpowiada trasie: GET /api/orders
     */
    getMyOrders: (): Promise<PaginatedOrdersResponse> => {
        return apiClient<PaginatedOrdersResponse>("/api/orders");
    },

    /**
     * Tworzy nowe zamówienie.
     * Odpowiada trasie: POST /api/orders
     */
    createOrder: (data: CreateOrderDto): Promise<OrderDto> => {
        return apiClient<OrderDto>("/api/orders", {
            method: "POST",
            body: JSON.stringify(data),
        });
    },
};
