<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Http\Resources\ProductResource; // To return list of favorite products
use Illuminate\Http\Response;

class UserFavoriteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * List the authenticated user's favorite products.
     * GET /api/favorites
     */
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'User not authenticated.'], Response::HTTP_UNAUTHORIZED);
        }


        // Eager load what ProductResource might need (category, restaurant)
        $favoriteProducts = $user->favoriteProducts()->with(['category', 'restaurant'])->paginate(15);
        return ProductResource::collection($favoriteProducts);
    }

    /**
     * Add a product to the authenticated user's favorites.
     * POST /api/products/{product}/favorite
     */
    public function store(Request $request, Product $product) // Product via route-model binding
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'User not authenticated.'], Response::HTTP_UNAUTHORIZED);
        }

        // Attach if not already attached. syncWithoutDetaching adds if not present.
        // The second argument to attach/sync is for extra pivot data.
        $user->favoriteProducts()->syncWithoutDetaching([$product->Id => ['DateAdded' => now()]]);

        return response()->json(['message' => 'Product added to favorites.'], Response::HTTP_CREATED);
    }

    /**
     * Remove a product from the authenticated user's favorites.
     * DELETE /api/products/{product}/unfavorite  (or /api/favorites/{product})
     */
    public function destroy(Request $request, Product $product) // Product via route-model binding
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'User not authenticated.'], Response::HTTP_UNAUTHORIZED);
        }

        $user->favoriteProducts()->detach($product->Id);

        return response()->noContent();
    }
}