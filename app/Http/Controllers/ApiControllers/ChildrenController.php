<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

use App\Models\Children;
use Auth;

class ChildrenController extends Controller
{

    public function index() {
        try { 
            $children = Children::paginate(100);
            return response()->json(["status" => true, "data" => $children]);
        } catch (\Throwable $th) {
            throw ValidationException::withMessages([
                "status" => false, 
                "msg" => $th,
            ]);
        }
    }

    public function getChildren(Request $request, $parent_id) {
        try {
            $children = Children::where('parent_id', $parent_id)->get();
            return response()->json(["status" => true, "data" => $children]);
        } catch (\Throwable $th) {
            throw ValidationException::withMessages([
                "status" => false, 
                "msg" => 'Data not found',
            ]);
        }
    }

    public function getChild(Request $request, $id) {
        try {
            $child = Children::find($id);
            return response()->json(["status" => true, "data" => $child]);
        } catch (\Throwable $th) {
            throw ValidationException::withMessages([
                "status" => false, 
                "msg" => "Invalid id",
            ]);
        }
    }

    public function store(Request $request) {
        $request->validate([
            "name" => "required|string|max:255",
            "gender" => "required|string",
            "age" => "required|numeric"
        ]);

        try {
            $child = Children::create([
                'name' => $request->name,
                'gender' => $request->gender,
                'parent_id' => $request->user()->id,
                'age' => $request->age
            ]);
            return response()->json(["status" => true, "data" => $child]);
        } catch (\Throwable $th) {
            throw ValidationException::withMessages([
                "status" => false, 
                "msg" => "Add child failed",
            ]);
        }
    }

    public function update(Request $request, $id) {

        try {
            $child = Children::find($id);
            if($child && Auth::user()->id == $child->parent_id) {
                if($request->user()->id == $child->parent_id) {
                    $child->name = $request->name;
                    $child->gender = $request->gender;
                    $child->age = $request->age;
                    $child->save();
                    return response()->json(["status" => true, "data" => $child]);
                }
            }else{
                throw ValidationException::withMessages([
                    "status" => false, 
                    'msg' => 'Data not found',
                ]);
            }
        } catch (\Throwable $th) {
            throw ValidationException::withMessages([
                "status" => false, 
                'msg' => 'Update data failed',
            ]);
        }
    }
    
    public function delete(Request $request, $id) {

        try {
            $child = Children::find($id);
            if($child && Auth::user()->id == $child->parent_id) {
                $child->delete();
                return response()->json(["status" => true, "msg" => "Data has been deleted"]);
            }else{
                throw ValidationException::withMessages([
                    "status" => false, 
                    'msg' => 'Data not found',
                ]);
            }
        } catch (\Throwable $th) {
            throw ValidationException::withMessages([
                "status" => false, 
                'msg' => 'delete data failed',
            ]);
        }
    }

}
