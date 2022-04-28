<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use League\CommonMark\Extension\CommonMark\Node\Inline\Image;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{

    protected function create(Request $request)
    {
        $user = auth('sanctum')->user()->id;
        $validator = Validator::make($request->all(), [
            'image' => ['required', 'image']
        ]);
        $path = $request->file('image')->store('users');
        User::where('id', $user)->update([
            'avatar' => 'storage/app/' . $path,
        ]);
        return response()->json([
            'data' => [
                'code' => 201,
                'message' => "Фото успешно обновлено"
            ]
        ], 201);
    }
    public function donwload(Request $request)
    {
        $pathToFile = storage_path() . "/app/public/to/file/" . $request->file_name;
        return response()->download($pathToFile);
    }
}
