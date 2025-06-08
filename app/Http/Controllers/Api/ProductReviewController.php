<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use App\Http\Requests\CreateReviewRequest;
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

    public function index(Product $product)
    {
        $reviews = $product
            ->reviews()
            ->with('user')
            ->latest('ReviewDate')
            ->paginate(10);
        return ReviewResource::collection($reviews);
    }

    public function store(CreateReviewRequest $request, Product $product)
    {
        $user = $request->user();

        $review = $product->reviews()->create(
            array_merge($request->validated(), [
                'UserId' => $user->id,
                'ReviewDate' => now(),
            ])
        );
        $review->load('user');

        return (new ReviewResource($review))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}