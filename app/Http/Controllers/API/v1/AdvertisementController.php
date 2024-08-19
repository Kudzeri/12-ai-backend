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

        $advertisements = Advertisement::with(['post', 'contactInfo'])->paginate($perPage);

        return response()->json($advertisements);
    }

}
