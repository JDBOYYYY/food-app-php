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
        // All actions here require an authenticated user
        // $this->middleware('auth:sanctum'); // Add when Sanctum is ready
    }

    /**
     * List the authenticated user's favorite products.
     * GET /api/favorites
     */
    public function index()
    {
        $user = Auth::user();
        // If testing without auth:
        if (!$user) { $user = \App\Models\User::where('email', 'user@example.com')->first(); } // Temporary
        if (!$user) { return response()->json(['message' => 'User not found for favorites list.'], 404); }


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
        // If testing without auth:
        if (!$user) { $user = \App\Models\User::where('email', 'user@example.com')->first(); } // Temporary
        if (!$user) { return response()->json(['message' => 'User not found to add favorite.'], 404); }

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
        // If testing without auth:
        if (!$user) { $user = \App\Models\User::where('email', 'user@example.com')->first(); } // Temporary
        if (!$user) { return response()->json(['message' => 'User not found to remove favorite.'], 404); }

        $user->favoriteProducts()->detach($product->Id);

        return response()->noContent();
    }
}