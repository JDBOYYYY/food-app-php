<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Restaurant;
use Illuminate\Http\Response;
use App\Http\Resources\ProductResource; // Import ProductResource
use App\Http\Resources\RestaurantResource; // Import RestaurantResource

class UserFavoriteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function storeProduct(Request $request, Product $product)
    {
        $request->user()->favoriteProducts()->syncWithoutDetaching($product->Id);
        return response()->json(['message' => 'Product added to favorites.'], Response::HTTP_CREATED);
    }

    public function destroyProduct(Request $request, Product $product)
    {
        $request->user()->favoriteProducts()->detach($product->Id);
        return response()->noContent();
    }

    public function storeRestaurant(Request $request, Restaurant $restaurant)
    {
        $request->user()->favoriteRestaurants()->syncWithoutDetaching($restaurant->Id);
        return response()->json(['message' => 'Restaurant added to favorites.'], Response::HTTP_CREATED);
    }

    public function destroyRestaurant(Request $request, Restaurant $restaurant)
    {
        $request->user()->favoriteRestaurants()->detach($restaurant->Id);
        return response()->noContent();
    }

    public function index(Request $request)
    {
        $user = $request->user();

        // Eager load relationships to avoid N+1 query problems
        $favoriteProducts = $user->favoriteProducts()->with(['category', 'restaurant'])->get();
        $favoriteRestaurants = $user->favoriteRestaurants()->with('categories')->get();

        return response()->json([
            'products' => ProductResource::collection($favoriteProducts),
            'restaurants' => RestaurantResource::collection($favoriteRestaurants),
        ]);
    }
}