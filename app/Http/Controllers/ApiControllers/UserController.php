<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

use \App\Models\User;
use Auth;
use Illuminate\Auth\Events\Registered;

class UserController extends Controller
{
    //get all user
    public function index() {
        try { 
            $user = User::with('childrens')->paginate(100);
            return response()->json(["status" => true, "msg" => $user]);
        } catch (\Throwable $th) {
            throw ValidationException::withMessages([
                "status" => false,
                'msg' => $th,
            ]);
        }
    }

    //register user
    public function store(Request $request) {
        $request->validate([
            "name" => "required|string|max:255",
            "email" => "email|required|max:255|unique:users,email",
            "password" => "required|string|min:8|confirmed|regex:/^(?=.*?[A-Z])(?=.*?[0-9])/",
            "nik" => "required|numeric|min:16|unique:users,nik",
            "phone_number" => "required|numeric|min:10",
            "address" => "required|max:500"
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => password_hash($request->password, PASSWORD_BCRYPT, ['cost' => 10]),
                'nik' => $request->nik,
                'address' => $request->address,
                'phone_number' => $request->phone_number
            ]);
            event(new Registered($user));
            Auth::login($user);
            return response()->json(["status" => true, "msg" => $user]);
        } catch (\Throwable $th) {
            throw ValidationException::withMessages([
                "status" => false,
                'msg' => $th,
            ]);
        }
    }

    //update user
    public function update(Request $request, $id) {

        try {
            $user = User::find($id);
            if($user && Auth::user()->id == $user->id) {
                $user->name = $request->name;
                $user->address = $request->address;
                $user->phone_number = $request->phone_number;
                $user->save();
                return response()->json(["status" => true, "msg" => $user]);
            }else {
                throw ValidationException::withMessages([
                    "status" => false,
                    'msg' => "Data not found",
                ]);
            }
        } catch (\Throwable $th) {
            throw ValidationException::withMessages([
                "status" => false,
                'msg' => $th,
            ]);
        }
    }
    
    public function delete(Request $request, $id) {

        try {
            $user = User::find($id);
            if($user && Auth::user()->id == $user->id) {
                $user->delete();
                return response()->json(["status" => true, "msg" => "Data has been deleted"]);
            }else {
                throw ValidationException::withMessages([
                    "status" => false,
                    'msg' => "Data not found",
                ]);
            }
        } catch (\Throwable $th) {
            throw ValidationException::withMessages([
                "status" => false,
                'msg' => $th,
            ]);
        }
    }

    //get user by id
    public function show(Request $request, $id) {
        try {
            $user = User::with(['childrens', 'orders', 'bookmarks'])->find($id);
            return response()->json(["status" => true, "msg" => $user]);
        } catch (\Throwable $th) {
            throw ValidationException::withMessages([
                "status" => false,
                'msg' => 'Invalid user id',
            ]);
        }
    }

    
}
