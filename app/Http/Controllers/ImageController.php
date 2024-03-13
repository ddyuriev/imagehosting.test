<?php

namespace App\Http\Controllers;

use App\Helpers\StringHelper;
use App\Http\Requests\StoreImageRequest;
use App\Models\Image;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class ImageController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function index(Request $request)
    {
        $query = Image::query();

        if ($request->has('sort_by')) {
            $sortBy = $request->query('sort_by');

            match ($sortBy) {
                'name_asc' => $query->orderBy('filename', 'asc'),
                'name_desc' => $query->orderBy('filename', 'desc'),
                'datetime_asc' => $query->orderBy('uploaded_at', 'asc'),
                'datetime_desc' => $query->orderBy('uploaded_at', 'desc'),
                default => $query->orderBy('id', 'desc')
            };
        }

        $images = $query->paginate(env('DEFAULT_PAGINATION_COUNT', 10))->withQueryString();

        return view('image.index', [
            'images' => $images,
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function upload(Request $request)
    {
        return view('image.upload');
    }

    /**
     * @param StoreImageRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreImageRequest $request)
    {
        $requestFilesArr = [];
        foreach ($request->file('files') as $file) {
            $requestFilesArr[StringHelper::transliterateAndLowerCase($file->getClientOriginalName())] = $file;
        }

        $existImages = Image::whereIn('filename', array_keys($requestFilesArr))->get()->pluck('filename')->toArray();
        $imageArr = [];
        $now = Carbon::now();

        foreach ($requestFilesArr as $fileName => $file) {
            if (in_array($fileName, $existImages)) {
                $fileName = StringHelper::newFileName($file);
            }
            $file->storeAs('public/uploads', $fileName);
            $imageArr[] = [
                'filename' => $fileName,
                'uploaded_at' => $now,
            ];
        }
        Image::insert($imageArr);

        return back()->with('message', 'Your files is uploaded successfully');
    }

    /**
     * @param $name
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadZip($name)
    {
        $fileName = $name;
        $filePath = Storage::path('public/uploads/' . $fileName);

        if (!Storage::exists('public/uploads/' . $fileName)) {
            return response()->json(['error' => 'File not found'], 404);
        }
        $zip = new ZipArchive;
        $zipFileName = 'archive.zip';

        if ($zip->open(public_path($zipFileName), ZipArchive::CREATE) === TRUE) {
            $zip->addFile($filePath, $fileName);
            $zip->close();
        } else {
            return response()->json(['error' => 'Unable to create zip archive'], 500);
        }

        return response()->download(public_path($zipFileName))->deleteFileAfterSend(true);
    }

}
