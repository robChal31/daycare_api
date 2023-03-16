<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

use \App\Models\Employe;
use App\Models\Merchant;
use Auth;

class EmployeController extends Controller
{

     //get employe by id
     public function show($id) {
        try {
            $employe = Employe::find($id);
            return response()->json(["status" => true, "data" => $employe]);
        } catch (\Throwable $th) {
            throw ValidationException::withMessages([
                "status" => false,
                'msg' => 'Data not found',
            ]);
        }
    }

    public function store(Request $request) {
        $request->validate([
            "name" => "required|string|max:255",
            "username" => "required|string|max:255|unique:employes,username",
            "password" => "required|string|min:8|confirmed|regex:/^(?=.*?[A-Z])(?=.*?[0-9])/",
            "position_id" => "required|uuid",
            "age" => "required|integer|between:18,65",
            "phone_number" => "required|numeric|min:10",
            "address" => "required|string|max:255",
            "education" => "required|string|max:255",
        ]);

        $user = Auth::user();
        if (!$user instanceof Merchant) {
            // If the authenticated user is not a Merchant, throw an exception
            throw ValidationException::withMessages([
                "status" => false,
                'msg' => 'Only merchants can add employes'
            ]);
        }

        try {
            $employe = Employe::create([
                "merchant_id" => $user->id,
                "position_id" => $request->position_id,
                "name" => $request->name,
                "username" => $request->username,
                "password" => password_hash($request->password, PASSWORD_BCRYPT, ['cost' => 10]),
                "age" => $request->age,
                "phone_number" => $request->phone_number,
                "address" => $request->address,
                "education" => $request->education,
            ]);
            return response()->json(["status" => true, "data" => $employe]);
        } catch (\Throwable $th) {
            throw ValidationException::withMessages([
                "status" => false,
                'msg' => 'Add employe failed'
            ]);
        }
    }

    public function getEmployes($id) {
        try { 
            $employes = Employe::where('merchant_id', $id)->paginate(20);
            return response()->json(["status" => false, "data" => $employes]);
        } catch (\Throwable $th) {
            throw ValidationException::withMessages([
                "status" => false, 
                "msg" => "Get employes failed"
            ]);
        }
    }

    public function update(Request $request, $id) {
        try {
            $employe = Employe::find($id);
            if($employe && Auth::user()->id == $employe->merchant_id) {
                $employe->name = $request->name;
                $employe->position_id = $request->position_id;
                $employe->username = $request->username;
                $employe->age = $request->age;
                $employe->address = $request->address;
                $employe->education = $request->education;
                $employe->phone_number = $request->phone_number;
                $employe->save();
                return response()->json(["status" => true, "data" => $employe]);
            }else{
                throw ValidationException::withMessages([
                    "status" => false,
                    'msg' => "Data not found",
                ]);
            }

        } catch (\Throwable $th) {
            throw ValidationException::withMessages([
                "status" => false, 
                "msg" => "update employe failed",
            ]);
        }
    }

    public function delete(Request $request, $id) {
        try {
            $employe = Employe::find($id);
            if($employe && Auth::user()->id == $employe->merchant_id) {
                $employe->delete();
                return response()->json(["status" => true, "msg" => "Data has been deleted"]);
            }else{
                throw ValidationException::withMessages([
                    "status" => false,
                    'msg' => "Data not found",
                ]);
            }
        } catch (\Throwable $th) {
            throw ValidationException::withMessages([
                "status" => false, 
                "msg" => "Delete employe failed",
            ]);
        }
    }

}
