<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request; // For query parameters in index()
use Illuminate\Http\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     * GET /api/products
     * Can be filtered by ?categoryId=X or ?restaurantId=X
     */
    public function index(Request $request)
    {
        $query = Product::query()->with(['category', 'restaurant']); // Eager load relationships

        if ($request->has('categoryId')) {
            $query->where('CategoryId', $request->input('categoryId'));
        }

        if ($request->has('restaurantId')) {
            $query->where('RestaurantId', $request->input('restaurantId'));
        }

        // Add other filters as needed, e.g., search by name
        if ($request->has('name')) {
            $query->where('Name', 'like', '%' . $request->input('name') . '%');
        }

        // Add pagination
        $products = $query->paginate(15); // Get 15 products per page

        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     * POST /api/products
     */
    public function store(CreateProductRequest $request)
    {
        // Authorization and Validation handled by CreateProductRequest
        $product = Product::create($request->validated());

        // Load relationships for the response
        $product->load(['category', 'restaurant']);

        return (new ProductResource($product))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     * GET /api/products/{product}
     */
    public function show(Product $product) // Route-model binding
    {
        // Eager load relationships for the single resource response
        $product->load(['category', 'restaurant']);
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     * PUT/PATCH /api/products/{product}
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        // Authorization and Validation handled by UpdateProductRequest
        $product->update($request->validated());

        $product->load(['category', 'restaurant']);
        return new ProductResource($product); // Return the updated resource
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /api/products/{product}
     */
    public function destroy(Product $product)
    {
        // TODO: Add checks if product is in active orders, etc., if needed
        // For now, simple delete
        $product->delete();

        return response()->noContent(); // 204 No Content
    }
}
