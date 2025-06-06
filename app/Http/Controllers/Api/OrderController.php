<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product; // Needed for fetching product prices
use App\Http\Requests\CreateOrderRequest;
// use App\Http\Requests\UpdateOrderRequest; // We'll create this later if needed for updating orders
use App\Http\Resources\OrderResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // For database transactions

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of the authenticated user's orders.
     * GET /api/orders
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'User not authenticated.'], Response::HTTP_UNAUTHORIZED);
        }

        $orders = $user->orders() // Assuming 'orders' relationship exists on User model
                       ->with(['shippingAddress', 'billingAddress', 'orderItems.product']) // Eager load details
                       ->orderBy('OrderDate', 'desc')
                       ->paginate(15);

        return OrderResource::collection($orders);
    }

    /**
     * Store a newly created resource in storage.
     * POST /api/orders
     */
    public function store(CreateOrderRequest $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'User not authenticated.'], Response::HTTP_UNAUTHORIZED);
        }

        $validatedData = $request->validated();
        $orderItemsData = $validatedData['items'];
        $totalAmount = 0;
        $orderItemsToCreate = [];

        // Use a database transaction to ensure atomicity
        try {
            DB::beginTransaction();

            // 1. Calculate total amount and prepare order items
            foreach ($orderItemsData as $itemData) {
                $product = Product::find($itemData['ProductId']);
                if (!$product) {
                    // This should ideally be caught by 'exists:Products,Id' validation,
                    // but good to have a safeguard.
                    throw new \Exception("Product with ID {$itemData['ProductId']} not found.");
                }
                $unitPrice = $product->Price; // Get current price from product
                $quantity = $itemData['Quantity'];
                $subTotal = $unitPrice * $quantity;
                $totalAmount += $subTotal;

                $orderItemsToCreate[] = [
                    'ProductId' => $product->Id,
                    'Quantity' => $quantity,
                    'UnitPrice' => $unitPrice,
                    // OrderId will be set after Order is created
                ];
            }

            // 2. Create the Order
            $order = $user->orders()->create([ // Assumes 'orders' relationship on User model
                'OrderDate' => now(),
                'TotalAmount' => $totalAmount,
                'Status' => Order::STATUS_PENDING, // Default status
                'ShippingAddressId' => $validatedData['ShippingAddressId'],
                'BillingAddressId' => $validatedData['BillingAddressId'] ?? $validatedData['ShippingAddressId'], // Default billing to shipping if not provided
                // 'Notes' => $validatedData['Notes'] ?? null,
            ]);

            // 3. Create OrderItems and associate with the Order
            foreach ($orderItemsToCreate as &$itemToCreate) { // Pass by reference to add OrderId
                $itemToCreate['OrderId'] = $order->Id;
            }
            // Use insert for multiple items for efficiency
            DB::table('OrderItems')->insert($orderItemsToCreate); // Assumes OrderItems table has created_at/updated_at or they are nullable

            DB::commit();

            // Load all relationships for the response
            $order->load(['user', 'shippingAddress', 'billingAddress', 'orderItems.product']);
            return (new OrderResource($order))
                    ->response()
                    ->setStatusCode(Response::HTTP_CREATED);
            $order->payment()->create([ // Uses the hasOne relationship
                'PaymentDate' => now(),
                'Amount' => $order->TotalAmount, // Assuming payment is for the full amount
                'PaymentMethod' => 'Credit Card (Placeholder)', // This would come from payment gateway/user choice
                'TransactionId' => 'txn_' . uniqid(), // Placeholder for a real transaction ID
                'Status' => \App\Models\Payment::STATUS_SUCCEEDED, // Assume success for now
            ]);

            DB::commit();
            
            $order->load(['user', 'shippingAddress', 'billingAddress', 'orderItems.product', 'payment']);
            return (new OrderResource($order))
                ->response()
                ->setStatusCode(Response::HTTP_CREATED);

        } catch (\Exception $e) {
            DB::rollBack();
            // Log the error: Log::error('Order creation failed: ' . $e->getMessage());
            return response()->json([
                'message' => 'Order creation failed. Please try again.',
                'error' => $e->getMessage() // For debugging, remove in production
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     * GET /api/orders/{order}
     */
    public function show(Order $order)
    {
        // TODO: Authorization: User can only see their own order, or admin can see any
        // $this->authorize('view', $order); // Requires an OrderPolicy

        $order->load(['user', 'shippingAddress', 'billingAddress', 'orderItems.product.category', 'orderItems.product.restaurant']);
        return new OrderResource($order);
    }

    /**
     * Update the specified resource in storage. (e.g., update order status by admin)
     * PUT/PATCH /api/orders/{order}
     * For now, let's assume only status can be updated by an admin.
     */
    public function update(Request $request, Order $order) // Use generic Request for now for status update
    {
        // TODO: Authorization: Only admin can update status, or user can cancel if pending.
        // $this->authorize('update', $order);

        $request->validate([
            'Status' => 'required|string|in:' . implode(',', [
                Order::STATUS_PROCESSING,
                Order::STATUS_COMPLETED,
                Order::STATUS_SHIPPED,
                Order::STATUS_DELIVERED,
                Order::STATUS_CANCELLED,
                Order::STATUS_FAILED,
            ]),
        ]);

        $order->Status = $request->input('Status');
        $order->save();

        $order->load(['user', 'shippingAddress', 'billingAddress', 'orderItems.product']);
        return new OrderResource($order);
    }

    /**
     * Remove the specified resource from storage. (Generally not recommended for orders)
     * DELETE /api/orders/{order}
     * Usually, orders are cancelled or archived, not hard deleted.
     */
    public function destroy(Order $order)
    {
        // TODO: Authorization
        // $this->authorize('delete', $order);

        // Consider business logic: can all orders be deleted?
        // Maybe only if in 'Cancelled' or 'Failed' status.
        if ($order->Status === Order::STATUS_PENDING || $order->Status === Order::STATUS_CANCELLED || $order->Status === Order::STATUS_FAILED) {
            // DB::transaction(function () use ($order) {
            //     $order->orderItems()->delete(); // Delete related items if not handled by cascade
            //     $order->delete();
            // });
            // Since OrderItems has onDelete('cascade') for OrderId, just deleting order is enough.
            $order->delete();
            return response()->noContent();
        }

        return response()->json(['message' => 'Order cannot be deleted in its current status.'], Response::HTTP_FORBIDDEN);
    }
}