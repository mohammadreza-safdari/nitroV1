<?php

namespace App\Http\Controllers\Api\Admin\Catalog;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Catalog\Category\StoreCategoryRequest;
use App\Http\Requests\Admin\Catalog\Category\UpdateCategoryRequest;
use App\Models\Catalog\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index(): JsonResponse
    {
        $categories = Category::query()
            ->with('children')
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();

        return response()->json([
            'categories' => $categories,
        ]);
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $category = Category::create($request->validated());

        return response()->json([
            'message' => 'Category created successfully.',
            'category' => $category,
        ], 201);
    }

    public function show(Category $category): JsonResponse
    {
        $category->load([
            'parent',
            'children',
            'vehicleModels',
        ]);

        return response()->json([
            'category' => $category,
        ]);
    }

    public function update(
        UpdateCategoryRequest $request,
        Category              $category
    ): JsonResponse
    {
        $category->update($request->validated());

        return response()->json([
            'message' => 'Category updated successfully.',
            'category' => $category->fresh(),
        ]);
    }

    public function destroy(Category $category): JsonResponse
    {
        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully.',
        ]);
    }
}
