<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use App\Http\Requests\CreateReviewRequest;
// use App\Http\Requests\UpdateReviewRequest;
use App\Http\Resources\ReviewResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ProductReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['store']);
    }

    /**
     * Display a listing of reviews for a specific product.
     * GET /api/products/{product}/reviews
     */
    public function index(Product $product) // Product via route-model binding
    {
        $reviews = $product->reviews()->with('user')->latest('ReviewDate')->paginate(10);
        return ReviewResource::collection($reviews);
    }

    /**
     * Store a newly created review for a specific product.
     * POST /api/products/{product}/reviews
     */
    public function store(CreateReviewRequest $request, Product $product)
    {
        $user = $request->user();

        // Check if user already reviewed this product (optional business rule)
        // if ($product->reviews()->where('UserId', $user->id)->exists()) {
        //     return response()->json(['message' => 'You have already reviewed this product.'], Response::HTTP_CONFLICT);
        // }

        $review = $product->reviews()->create(array_merge(
            $request->validated(),
            [
                'UserId' => $user->id,
                'ReviewDate' => now(),
            ]
        ));
        $review->load('user'); // Load user for the response

        return (new ReviewResource($review))
                ->response()
                ->setStatusCode(Response::HTTP_CREATED);
    }

    // We might add show(Product $product, Review $review), update(), destroy() later
    // These would require authorization (e.g., user can only update/delete their own review)
}