<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

use \App\Models\Position;

class PositionController extends Controller
{
    public function index() {
        
        try {
            $positions = Position::get();
            return response()->json(["status" => true, "data" => $positions]);
        } catch (\Throwable $th) {
            throw ValidationException::withMessages([
                "status" => false, 
                'msg' => 'Data not found',
            ]);
        }
    }

     public function store(Request $request) {
        $request->validate([
            "position" => "required|string|max:255",
        ]);

        try {
            $position = Position::create([
                'position' => $request->position,
            ]);
            return response()->json(["status" => true, "data" => $position]);
        } catch (\Throwable $th) {
            throw ValidationException::withMessages([
                "status" => false, 
                'msg' => $th,
            ]);
        }
    }

     //update 
     public function update(Request $request, $id) {

        try {
            $position = Position::find($id);
            if($position) {
                $position->position = $request->position;
                $position->save();
                return response()->json(["status" => true, "data" => $position]);
            }else{
                throw ValidationException::withMessages([
                    "status" => false,
                    'msg' => "Data not found",
                ]);
            }
        } catch (\Throwable $th) {
            throw ValidationException::withMessages([
                "status" => false,
                'msg' => [$th],
            ]);
        }
    }
    
    //delete
    public function delete(Request $request, $id) {

        try {
            $position = Position::find($id);
            if($position) {
                $position->delete();
                return response()->json(["status" => true, "msg" => "Data has been deleted"]);;
            }else{
                throw ValidationException::withMessages([
                    "status" => false,
                    'msg' => "Data not found",
                ]);
            }
        } catch (\Throwable $th) {
            throw ValidationException::withMessages([
                "status" => false,
                'msg' => [$th],
            ]);
        }
    }


}
