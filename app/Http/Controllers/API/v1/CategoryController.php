<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index(Request $request){
        $perPage = $request->input('perPage', 5);
        $sortBy = $request->input('sortBy', 'updated_at');
        $sortDirection = $request->input('sortDirection', 'desc');

        $categories = Category::OrderBy($sortBy, $sortDirection)
            ->paginate($perPage);

        if($categories->isEmpty()){
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'No categories found',
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json([
            'status' => Response::HTTP_OK,
            'data' => $categories,
        ],Response::HTTP_OK);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'parent_id' => 'nullable|integer|exists:categories,id'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => $validator->errors()
                ]
                , Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $category = Category::create([
                'name' => $request->name,
                'slug' => $request->slug,
                'parent_id' => $request->parent_id
                ]);

            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Category created successfully',
                'category' => $category
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Error creating category: '.$e->getMessage());

            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Failed to create category'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id){
        $category = Category::with('children')->where('id', $id)->first();

        if(is_null($category)){
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'No category found',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => Response::HTTP_OK,
            'data' => $category
        ],Response::HTTP_OK);
    }

    public function update(Request $request, $id){
        Log::info('Request data:', $request->all());

        $category = Category::find($id);
        if(is_null($category)){
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'No category found',
            ],Response::HTTP_NOT_FOUND);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'parent_id' => 'nullable|integer|exists:categories,id'
        ]);
        if($validator->fails()){
            return response()->json([
                    'status' => Response::HTTP_BAD_REQUEST,
                    'message' => $validator->errors()
                ]
                , Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            Log::info('Updating category: '.$category->name);
            $category->update([
                    'name' => $request->name,
                    'slug' => $request->slug,
                    'parent_id' => $request->parent_id,
                ]);
            Log::info('Updated category: '.$category->name);

            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Category updated successfully',
                'category' => $category
            ], Response::HTTP_OK);
        } catch (\Exception $e){
            Log::error('Error updating category: '.$e->getMessage());
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Failed to update category'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id){
        $category = Category::find($id);

        if(is_null($category)){
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'No category found',
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            Log::info('Deleting category: {name}:{id}',$category->name, $id);
            $category->delete();

            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Category deleted successfully',
            ], Response::HTTP_OK);
        } catch (\Exception $e){
            Log::error('Error deleting category: '.$e->getMessage());
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Failed to delete category'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
