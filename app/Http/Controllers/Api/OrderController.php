<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Http\Requests\CreateOrderRequest;
use App\Http\Resources\OrderResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of the authenticated user's orders.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $orders = $user->orders()
                       ->with(['shippingAddress', 'billingAddress', 'orderItems.product'])
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
        $user = $request->user();

        $validatedData = $request->validated();
        $orderItemsData = $validatedData['items'];
        $totalAmount = 0;
        $orderItemsToCreate = [];
        try {
            DB::beginTransaction();
            foreach ($orderItemsData as $itemData) {
                $product = Product::find($itemData['ProductId']);
                if (!$product) {
                    throw new \Exception("Product with ID {$itemData['ProductId']} not found.");
                }
                $unitPrice = $product->Price; 
                $quantity = $itemData['Quantity'];
                $subTotal = $unitPrice * $quantity;
                $totalAmount += $subTotal;

                $orderItemsToCreate[] = [
                    'ProductId' => $product->Id,
                    'Quantity' => $quantity,
                    'UnitPrice' => $unitPrice,
                ];
            }
            $order = $user->orders()->create([ 
                'OrderDate' => now(),
                'TotalAmount' => $totalAmount,
                'Status' => Order::STATUS_PENDING, 
                'ShippingAddressId' => $validatedData['ShippingAddressId'],
                'BillingAddressId' => $validatedData['BillingAddressId'] ?? $validatedData['ShippingAddressId'], 
            ]);
            foreach ($orderItemsToCreate as &$itemToCreate) { 
                $itemToCreate['OrderId'] = $order->Id;
            }
            DB::table('OrderItems')->insert($orderItemsToCreate); 

            DB::commit();
            $order->load(["user", "shippingAddress", "billingAddress", "orderItems.product"]);
            return (new OrderResource($order))
                ->response()
                ->setStatusCode(Response::HTTP_CREATED);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Order creation failed. Please try again.',
                'error' => $e->getMessage() 
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     * GET /api/orders/{order}
     */
    public function show(Order $order)
    {

        $order->load(['user', 'shippingAddress', 'billingAddress', 'orderItems.product.category', 'orderItems.product.restaurant']);
        return new OrderResource($order);
    }

    /**
     * Update the specified resource in storage. (e.g., update order status by admin)
     * PUT/PATCH /api/orders/{order}
     * For now, let's assume only status can be updated by an admin.
     */
    public function update(Request $request, Order $order) 
    {

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
        if ($order->Status === Order::STATUS_PENDING || $order->Status === Order::STATUS_CANCELLED || $order->Status === Order::STATUS_FAILED) {
            $order->delete();
            return response()->noContent();
        }

        return response()->json(['message' => 'Order cannot be deleted in its current status.'], Response::HTTP_FORBIDDEN);
    }
}