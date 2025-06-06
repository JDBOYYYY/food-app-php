<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Http\Resources\OrderItemResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderItemController extends Controller
{
    /**
     * Display a listing of order items.
     */
    public function index()
    {
        $items = OrderItem::with(['order', 'product'])->paginate(15);
        return OrderItemResource::collection($items);
    }

    /**
     * Store a newly created order item in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'OrderId' => 'required|exists:Orders,Id',
            'ProductId' => 'required|exists:Products,Id',
            'Quantity' => 'required|integer|min:1',
            'UnitPrice' => 'required|numeric',
        ]);

        $item = OrderItem::create($validated);
        $item->load(['order', 'product']);

        return (new OrderItemResource($item))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified order item.
     */
    public function show(OrderItem $orderItem)
    {
        $orderItem->load(['order', 'product']);
        return new OrderItemResource($orderItem);
    }

    /**
     * Update the specified order item in storage.
     */
    public function update(Request $request, OrderItem $orderItem)
    {
        $validated = $request->validate([
            'OrderId' => 'sometimes|exists:Orders,Id',
            'ProductId' => 'sometimes|exists:Products,Id',
            'Quantity' => 'sometimes|integer|min:1',
            'UnitPrice' => 'sometimes|numeric',
        ]);

        $orderItem->update($validated);
        $orderItem->load(['order', 'product']);

        return new OrderItemResource($orderItem);
    }

    /**
     * Remove the specified order item from storage.
     */
    public function destroy(OrderItem $orderItem)
    {
        $orderItem->delete();
        return response()->noContent();
    }
}
