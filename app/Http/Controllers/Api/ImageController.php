<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Http\Request;


class ImageController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $images = Image::orderBy('uploaded_at', 'desc')->paginate(env('DEFAULT_PAGINATION_COUNT', 10));
        $responseData = [
            'data' => $images->items(),
            'current_page' => $images->currentPage(),
            'per_page' => $images->perPage(),
            'total' => $images->total(),
        ];
        return response()->json($responseData);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        $image = Image::find($id);

        if (!$image) {
            return response()->json(['error' => 'File not found'], 404);
        }

        return response()->json($image);
    }
}
