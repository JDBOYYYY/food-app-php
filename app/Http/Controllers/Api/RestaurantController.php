<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Http\Requests\CreateRestaurantRequest;
use App\Http\Requests\UpdateRestaurantRequest;
use App\Http\Resources\RestaurantResource;
use App\Http\Resources\CategoryResource; // For listing categories of a restaurant
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RestaurantController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')
            ->except(['index', 'show', 'getRestaurantCategories', 'getRestaurantProducts']);
    }

    /**
     * Display a listing of the resource.
     * GET /api/restaurants
     */
    public function index(Request $request)
    {
        // Eager load categories for each restaurant
        // Add pagination
        $restaurants = Restaurant::query()
                            ->with('categories') // Eager load categories
                            ->paginate(15);

        return RestaurantResource::collection($restaurants);
    }

    /**
     * Store a newly created resource in storage.
     * POST /api/restaurants
     */
    public function store(CreateRestaurantRequest $request)
    {
        // Validation handled by CreateRestaurantRequest
        $validatedData = $request->validated();

        // Separate category_ids from other restaurant data
        $categoryIds = null;
        if (isset($validatedData['CategoryIds'])) {
            $categoryIds = $validatedData['CategoryIds'];
            unset($validatedData['CategoryIds']); // Remove it before creating restaurant
        }

        $restaurant = Restaurant::create($validatedData);

        // Attach categories if provided
        if ($categoryIds) {
            $restaurant->categories()->sync($categoryIds); // sync is good for create/update
        }

        $restaurant->load('categories'); // Reload categories to include in the response

        return (new RestaurantResource($restaurant))
                ->response()
                ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     * GET /api/restaurants/{restaurant}
     */
    public function show(Restaurant $restaurant) // Route-model binding
    {
        $restaurant->load('categories', 'products.category'); // Eager load categories and products with their categories
        return new RestaurantResource($restaurant);
    }

    /**
     * Update the specified resource in storage.
     * PUT/PATCH /api/restaurants/{restaurant}
     */
    public function update(UpdateRestaurantRequest $request, Restaurant $restaurant)
    {
        $validatedData = $request->validated();

        $categoryIds = null;
        if (isset($validatedData['CategoryIds'])) {
            $categoryIds = $validatedData['CategoryIds'];
            unset($validatedData['CategoryIds']);
        }

        $restaurant->update($validatedData);

        if ($request->has('CategoryIds')) { // Check if CategoryIds was explicitly sent
            $restaurant->categories()->sync($categoryIds ?? []); // Sync, pass empty array if null to detach all
        }

        $restaurant->load('categories');
        return new RestaurantResource($restaurant);
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /api/restaurants/{restaurant}
     */
    public function destroy(Restaurant $restaurant)
    {
        // The ON DELETE CASCADE on Products table for RestaurantId
        // should handle deleting associated products.
        // The ON DELETE CASCADE on RestaurantCategories for RestaurantId
        // should handle deleting pivot table entries.
        $restaurant->delete();

        return response()->noContent(); // 204 No Content
    }

    /**
     * Get categories for a specific restaurant.
     * GET /api/restaurants/{restaurant}/categories
     */
    public function getRestaurantCategories(Restaurant $restaurant)
    {
        return CategoryResource::collection($restaurant->categories()->get());
    }

    /**
     * Get products for a specific restaurant.
     * GET /api/restaurants/{restaurant}/products
     */
    public function getRestaurantProducts(Restaurant $restaurant)
    {
        // You might want to use ProductResource here and paginate
        // For now, just returning the products directly
        $products = $restaurant->products()->with('category')->paginate(10);
        return \App\Http\Resources\ProductResource::collection($products);
    }
}