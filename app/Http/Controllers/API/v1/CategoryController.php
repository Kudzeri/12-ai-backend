<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request){
        $perPage = $request->input('per_page', 5);

        $categories = Category::paginate($perPage);

        return response()->json($categories);
    }
}
