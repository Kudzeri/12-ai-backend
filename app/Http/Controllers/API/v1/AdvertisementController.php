<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Illuminate\Http\Request;

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
                'message' => 'No advertisements found',
            ], 404);
        }

        return response()->json($advertisements,200);
    }

}
