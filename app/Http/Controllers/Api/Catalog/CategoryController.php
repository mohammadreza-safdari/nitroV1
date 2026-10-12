<?php

namespace App\Http\Controllers\Api\Catalog;

use App\Http\Controllers\Controller;
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
}
