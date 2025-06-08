<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index(Request $request)
    {
        $query = Product::query()->with(['category', 'restaurant']);

        if ($request->has('categoryId')) {
            $query->where('CategoryId', $request->input('categoryId'));
        }

        if ($request->has('restaurantId')) {
            $query->where('RestaurantId', $request->input('restaurantId'));
        }

        if ($request->has('name')) {
            $query->where('Name', 'like', '%' . $request->input('name') . '%');
        }

        $products = $query->paginate(15);

        return ProductResource::collection($products);
    }

    /**
     * Store a newly created product.
     */
    public function store(CreateProductRequest $request)
    {
        $product = Product::create($request->validated());

        $product->load(['category', 'restaurant']);

        return (new ProductResource($product))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        $product->load(['category', 'restaurant']);
        return new ProductResource($product);
    }

    /**
     * Update the specified product.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        $product->load(['category', 'restaurant']);
        return new ProductResource($product);
    }

    /**
     * Remove the specified product.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->noContent();
    }
}
