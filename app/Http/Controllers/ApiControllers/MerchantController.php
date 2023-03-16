<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Models\Employe;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

use \App\Models\Merchant;
use App\Models\Service;
use Auth;

class MerchantController extends Controller
{
    //register merchant
    public function store(Request $request) {
        $request->validate([
            "name" => "required|string|max:255",
            "email" => "email|required|max:255|unique:users,email",
            "password" => "required|string|min:8|confirmed|regex:/^(?=.*?[A-Z])(?=.*?[0-9])/",
            "address1" => "required|max:500",
            "address2" => "required|max:500",
            "phone_number" => "required|numeric|min:10",
        ]);

        try {
            $merchant = Merchant::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => password_hash($request->password, PASSWORD_BCRYPT, ['cost' => 10]),
                'address1' => $request->address1,
                'address2' => $request->address2,
                'phone_number' => $request->phone_number,
            ]);
            return response()->json(["status" => true, "data" => $merchant]);
        } catch (\Throwable $th) {
            throw ValidationException::withMessages([
                "status" => false,
                'msg' => 'Register merchant failed',
            ]);
        }

        
    }

    //update merchant
    public function update(Request $request, $id) {
        try {
            $merchant = Merchant::find($id);
            if($merchant && Auth::user()->id == $merchant->id) {
                $merchant->name = $request->name;
                $merchant->address1 = $request->address1;
                $merchant->address2 = $request->address2;
                $merchant->phone_number = $request->phone_number;
                $merchant->save();
                return response()->json(["status" => true, "data" => $merchant]);
            }else {
                throw ValidationException::withMessages([
                    "status" => false,
                    'msg' => 'Data not found',
                ]);
            }
        } catch (\Throwable $th) {
            throw ValidationException::withMessages([
                "status" => false,
                'msg' => 'Update merchant failed',
            ]);
        }
    }
    
    //delete
    public function delete(Request $request, $id) {
        //should or should not
        try {
            $merchant = Merchant::find($id);
            if($merchant) $merchant->delete();
        } catch (\Throwable $th) {
            throw ValidationException::withMessages([
                "status" => false, 
                "msg" => "delete failed",
            ]);
        }

        return response()->json(["status" => true, "msg" => "Data has been deleted"]);
    }

     //get merchant by id
     public function show(Request $request, $id) {
        try {
            $merchant = Merchant::with(['employes', 'services'])->find($id);
            return response()->json(["status" => true, "data" => $merchant]);
        } catch (\Throwable $th) {
            throw ValidationException::withMessages([
                "status" => false,
                'msg' => 'Invalid merchant id',
            ]);
        }
    }

}
