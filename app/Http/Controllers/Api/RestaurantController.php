<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Http\Requests\CreateRestaurantRequest;
use App\Http\Requests\UpdateRestaurantRequest;
use App\Http\Resources\RestaurantResource;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RestaurantController extends Controller
{
    public function index(Request $request)
    {
        $restaurants = Restaurant::query()->with('categories')->paginate(15);

        return RestaurantResource::collection($restaurants);
    }

    public function store(CreateRestaurantRequest $request)
    {
        $validatedData = $request->validated();

        $categoryIds = null;
        if (isset($validatedData['CategoryIds'])) {
            $categoryIds = $validatedData['CategoryIds'];
            unset($validatedData['CategoryIds']);
        }

        $restaurant = Restaurant::create($validatedData);

        if ($categoryIds) {
            $restaurant->categories()->sync($categoryIds);
        }

        $restaurant->load('categories');

        return (new RestaurantResource($restaurant))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Restaurant $restaurant)
    {
        $restaurant->load(['categories', 'products.category']);

        return new RestaurantResource($restaurant);
    }

    public function update(UpdateRestaurantRequest $request, Restaurant $restaurant)
    {
        $validatedData = $request->validated();

        $categoryIds = null;
        if (isset($validatedData['CategoryIds'])) {
            $categoryIds = $validatedData['CategoryIds'];
            unset($validatedData['CategoryIds']);
        }

        $restaurant->update($validatedData);

        if ($request->has('CategoryIds')) {
            $restaurant->categories()->sync($categoryIds ?? []);
        }

        $restaurant->load('categories');
        return new RestaurantResource($restaurant);
    }

    public function destroy(Restaurant $restaurant)
    {
        $restaurant->delete();

        return response()->noContent();
    }

    public function getRestaurantCategories(Restaurant $restaurant)
    {
        return CategoryResource::collection($restaurant->categories()->get());
    }

    public function getRestaurantProducts(Restaurant $restaurant)
    {
        $products = $restaurant->products()->with('category')->paginate(10);
        return \App\Http\Resources\ProductResource::collection($products);
    }
}