<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Models\ContactInfo;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AdvertisementController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 5);
        $sortBy = $request->input('sortBy', 'updated_at');
        $sortDirection = $request->input('sortDirection', 'desc');

        $advertisements = Advertisement::with(['post', 'contactInfo'])
            ->OrderBy($sortBy, $sortDirection)
            ->paginate($perPage);

        if($advertisements->isEmpty()){
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'No advertisements found',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => Response::HTTP_OK,
            'data' => $advertisements,
        ],Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        Log::info('Request data:', $request->all());

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'name' => 'required|string|max:255',
            'category_id' => 'required|integer|exists:categories,id',
            'subcategory_id' => 'required|integer|exists:categories,id',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'conditions' => 'nullable|string|max:255',
            'authenticity' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'negotiable' => 'required|boolean',
            'tags' => 'nullable|array|min:0|max:10',
            'tags.*' => 'string|distinct',
            'phone_number' => 'required|string|max:15',
            'backup_phone' => 'nullable|string|max:15',
            'email' => 'required|email|max:255',
            'website_link' => 'nullable|url|max:255',
            'country' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'feature' => 'nullable|string',
            'images' => 'nullable|array|min:0|max:5',
            'images.*' => 'url',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'message' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        DB::beginTransaction();

        try {
            Log::info('Creating advertisement.');

            $advertisement = Advertisement::create([
                'user_id' => $request->user_id,  // Используем user_id из запроса
                'name' => $request->name,
                'category_id' => $request->category_id,
                'subcategory_id' => $request->subcategory_id,
                'brand' => $request->brand,
                'model' => $request->model,
                'conditions' => $request->conditions,
                'authenticity' => $request->authenticity,
                'price' => $request->price,
                'negotiable' => $request->negotiable,
                'tags' => json_encode($request->tags),
            ]);

            Log::info('Advertisement created:', ['advertisement' => $advertisement]);

            $contactInfo = ContactInfo::create([
                'advertisement_id' => $advertisement->id,
                'phone_number' => $request->phone_number,
                'backup_phone' => $request->backup_phone,
                'email' => $request->email,
                'website_link' => $request->website_link,
                'country' => $request->country,
                'location' => $request->location,
            ]);

            Log::info('ContactInfo created:', ['contact_info' => $contactInfo]);

            $post = Post::create([
                'advertisement_id' => $advertisement->id,
                'description' => $request->description,
                'feature' => $request->feature,
                'images' => json_encode($request->images),
            ]);

            Log::info('Post created:', ['post' => $post]);

            DB::commit();

            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Advertisement created successfully',
                'advertisement' => $advertisement,
                'contact_info' => $contactInfo,
                'post' => $post
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating advertisement: ' . $e->getMessage());

            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Failed to create advertisement'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id){
        $advertisement = Advertisement::with(['post', 'contactInfo'])->find($id);

        if(is_null($advertisement)){
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'No advertisement found',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => Response::HTTP_OK,
            'data' => $advertisement,
        ], Response::HTTP_OK);
    }

    public function update(Request $request, $id){
        Log::info('Request data:', $request->all());
        $advertisement = Advertisement::with(['post', 'contactInfo'])->find($id);

        if(is_null($advertisement)){
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'No advertisement found',
            ], Response::HTTP_NOT_FOUND);
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'name' => 'required|string|max:255',
            'category_id' => 'required|integer|exists:categories,id',
            'subcategory_id' => 'required|integer|exists:categories,id',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'conditions' => 'nullable|string|max:255',
            'authenticity' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'negotiable' => 'required|boolean',
            'tags' => 'nullable|array|min:0|max:10',
            'tags.*' => 'string|distinct',
            'phone_number' => 'required|string|max:15',
            'backup_phone' => 'nullable|string|max:15',
            'email' => 'required|email|max:255',
            'website_link' => 'nullable|url|max:255',
            'country' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'feature' => 'nullable|string',
            'images' => 'nullable|array|min:0|max:5',
            'images.*' => 'url',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'message' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        DB::beginTransaction();
        try {
            $advertisement->update([
                'user_id' => $request->user_id,
                'name' => $request->name,
                'category_id' => $request->category_id,
                'subcategory_id' => $request->subcategory_id,
                'brand' => $request->brand,
                'model' => $request->model,
                'conditions' => $request->conditions,
                'authenticity' => $request->authenticity,
                'price' => $request->price,
                'negotiable' => $request->negotiable,
                'tags' => json_encode($request->tags),
            ]);
            Log::info('Advertisement updated:', ['advertisement' => $advertisement]);

            $advertisement->contactInfo->update([
                'phone_number' => $request->phone_number,
                'backup_phone' => $request->backup_phone,
                'email' => $request->email,
                'website_link' => $request->website_link,
                'country' => $request->country,
                'location' => $request->location,
            ]);
            Log::info('ContactInfo updated:', ['contact_info' => $advertisement->contactInfo]);

            $advertisement->post->update([
                'description' => $request->description,
                'feature' => $request->feature,
                'images' => json_encode($request->images),
            ]);
            Log::info('Post updated:', ['post' => $advertisement->post]);

            DB::commit();

            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Advertisement updated successfully',
                'advertisement' => $advertisement,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
           DB::rollBack();
           Log::error('Error updating advertisement: ' . $e->getMessage());

           return response()->json([
               'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
               'message' => 'Failed to update advertisement'
           ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

}
