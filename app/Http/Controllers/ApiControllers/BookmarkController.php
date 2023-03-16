<?php

namespace App\Http\Controllers\ApiControllers;

use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use \App\Models\Bookmark;

class BookmarkController extends Controller
{
    public function store(Request $request) {
        $request->validate([
            "service_id" => "required",
        ]);
       
        try {
            $bookmark = Bookmark::create([
                'service_id' => $request->service_id,
                'user_id' => $request->user()->id,
            ]);
            return response()->json($bookmark);
        } catch (\Throwable $th) {
            throw ValidationException::withMessages([
                "status" => false, 
                "msg" => "Add bookmark failed",
            ]);
        }
    }

    public function getBookmarks($id) {
        try {
            $bookmark = Bookmark::where('user_id', $id)->get();
            return response()->json($bookmark);
        } catch (\Throwable $th) {
            throw ValidationException::withMessages([
                "status" => false, 
                "msg" => "Invalid bookmark id",
            ]);
        }
    }
}
