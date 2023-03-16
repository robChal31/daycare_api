<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Models\Employe;
use App\Models\Merchant;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
     //login user
     public function login(Request $request) {

        $param_auth = $request->email ?? $request->username;
       
        $tokenExists = DB::table('personal_access_tokens')
                ->where('name', 'like', '%' . $param_auth . '%')
                ->exists();

        if($tokenExists) throw ValidationException::withMessages([
            'violation' => ["status" => false, "msg" => "User is logged in"],
        ]);

        if($request->email) {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);
        }
        if($request->username) {
            $request->validate([
                'username' => 'required',
                'password' => 'required',
            ]);
        }
 
        if ($request->type === 'user') {
          
            if (Auth::guard('web')->attempt($request->only('email', 'password'))) {
                // Authentication passed

                // Generate a token for the authenticated user
                try {
                    $user = User::where('email', $request['email'])->first();
                    $token = $user->createToken($request['email'], ['user'])->plainTextToken;
                } catch (\Throwable $th) {
                    throw ValidationException::withMessages([
                        "status" => false, 
                        "msg" => "Login failed",
                    ]);
                }
                // Return token as response
                return response()->json(["status" => true, 'data' => ["token" => $token]]);
            } else {
                // Authentication failed
                return response()->json(['status' => false, 'message' => 'Invalid login credentials'], 401);
            }
        }

        if ($request->type === 'merchant') {

            if (Auth::guard('merchant')->attempt($request->only('email', 'password'))) {
                // Authentication passed

                // Generate a token for the authenticated user
                try {
                    $merchant = Merchant::where('email', $request['email'])->first();
                    $token = $merchant->createToken($request['email'], ['merchant'])->plainTextToken;
                } catch (\Throwable $th) {
                    throw ValidationException::withMessages([
                        "status" => false, 
                        "msg" => "Login failed",
                    ]);
                }
                // Return token as response
                return response()->json(["status" => true, 'data' => ["token" => $token]]);
            } else {
                // Authentication failed
                return response()->json(['status' => false, 'message' => 'Invalid login credentials'], 401);
            }
        }

        if ($request->type === 'employe') {
            if (Auth::guard('employe')->attempt($request->only('username', 'password'))) {
                // Authentication passed

                // Generate a token for the authenticated user
                try {
                    $employe = Employe::where('username', $request['username'])->first();
                    $token = $employe->createToken($request['username'], ['employe'])->plainTextToken;
                } catch (\Throwable $th) {
                    throw ValidationException::withMessages([
                        "status" => false, 
                        "msg" => "Login failed",
                    ]);
                }
                // Return token as response
                return response()->json(["status" => true, 'data' => ["token" => $token]]);
            } else {
                // Authentication failed
                return response()->json(['status' => false, 'message' => 'Invalid login credentials'], 401);
            }
        }
        
    }

    //logout user
    public function logout(Request $request) {
        try {
            $request->user()->currentAccessToken()->delete();
            return response()->json(["status" => true, "msg" => "Logout success"]);
        } catch (\Throwable $th) {
            throw ValidationException::withMessages([
                "status" => false, 
                "msg" => "Logout failed",
            ]);
        }
    }

    public function loginView() {
        return 'Email is verified now';
    }
}
