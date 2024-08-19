<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request){
        $perPage = $request->input('per_page', 5);
        $sortBy = $request->input('sortBy', 'updated_at');
        $sortDirection = $request->input('sortDirection', 'desc');

        $categories = Category::OrderBy($sortBy, $sortDirection)
            ->paginate($perPage);

        return response()->json($categories);
    }
}
