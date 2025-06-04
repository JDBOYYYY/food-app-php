<?php

namespace App\Http\Controllers\Api; // Ensure namespace is correct

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Requests\CreateCategoryRequest; // Our Form Request for creation
use App\Http\Requests\UpdateCategoryRequest; // Our Form Request for updates
use App\Http\Resources\CategoryResource;     // Our API Resource for responses
use Illuminate\Http\Request;
use Illuminate\Http\Response; // For HTTP status codes

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * Corresponds to: GET api/categories (GetCategories in .NET)
     */
    public function index()
    {
        // Your .NET: _context.Categories.Select(c => new CategoryDto {...}).ToListAsync();
        $categories = Category::all();
        return CategoryResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     * Corresponds to: POST api/categories (CreateCategory in .NET)
     */
    public function store(CreateCategoryRequest $request) // Type-hint our Form Request
    {
        // Validation is handled by CreateCategoryRequest

        // Your .NET: var category = new Category { Name = createCategoryDto.Name };
        //           _context.Categories.Add(category);
        //           await _context.SaveChangesAsync();
        $category = Category::create($request->validated()); // validated() gets validated data

        // Your .NET: return CreatedAtAction(nameof(GetCategory), new { id = category.Id }, categoryDto);
        return (new CategoryResource($category))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED); // 201 Created
    }

    /**
     * Display the specified resource.
     * Corresponds to: GET api/categories/{id} (GetCategory in .NET)
     */
    public function show(Category $category) // Route-model binding: Laravel finds Category by ID
    {
        // Your .NET: var category = await _context.Categories.Select(...).FirstOrDefaultAsync(c => c.Id == id);
        //           if (category == null) return NotFound();
        // Laravel's route-model binding handles the NotFound automatically if ID doesn't exist.
        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     * Corresponds to: PUT api/categories/{id} (UpdateCategory in .NET)
     */
    public function update(UpdateCategoryRequest $request, Category $category) // Form Request & Route-model binding
    {
        // Validation is handled by UpdateCategoryRequest

        // Your .NET: categoryToUpdate.Name = updateCategoryDto.Name;
        //           await _context.SaveChangesAsync();
        $category->update($request->validated());

        // Your .NET: return NoContent();
        return response()->noContent(); // 204 No Content
    }

    /**
     * Remove the specified resource from storage.
     * Corresponds to: DELETE api/categories/{id} (DeleteCategory in .NET)
     */
    public function destroy(Category $category) // Route-model binding
    {
        // Your .NET: _context.Categories.Remove(category);
        //           await _context.SaveChangesAsync();

        // TODO: Add checks like in your .NET controller before deleting:
        // - productsInCategory = await _context.Products.AnyAsync(p => p.CategoryId == id);
        // - restaurantsWithCategory = await _context.RestaurantCategories.AnyAsync(rc => rc.CategoryId == id);
        // We'll need Product and RestaurantCategory models and relationships for this.
        // For now, a simple delete:
        try {
            $category->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            // Check for foreign key constraint violation (e.g., SQLSTATE[23000])
            // This is a basic way to catch if it's in use.
            // A more robust check would be to query related tables first.
            if (str_contains($e->getMessage(), 'constraint violation')) {
                return response()->json([
                    'message' => 'Cannot delete category. It is currently in use by other records (e.g., products or restaurants).',
                    'error_details' => $e->getMessage() // Optional: for debugging
                ], Response::HTTP_CONFLICT); // 409 Conflict or 400 Bad Request
            }
            // Re-throw if it's a different DB error
            throw $e;
        }


        // Your .NET: return NoContent();
        return response()->noContent(); // 204 No Content
    }
}
