<?php

namespace App\Http\Controllers;

use App\Image;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function store(Request $request){



        $request->validate([
            'file' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);



        $image = new Image;
        $image->filename = $request->file->getClientOriginalName();
        $image->save();


        Storage::disk('')->putFileAs('', $request->file, $image->filename);


        return response()->json([
            'data' => [
                'id'=>1,
                'filename' => $image->filename
            ]
        ])->setStatusCode(Response::HTTP_CREATED);


    }
}
