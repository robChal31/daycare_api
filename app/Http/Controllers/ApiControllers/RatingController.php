<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Rating;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class RatingController extends Controller
{
    public function index() {
        try {
            $ratings = Rating::paginate(50);
            return response()->json(["status" => true, "data" => $ratings]);
        } catch (\Throwable $th) {
            throw ValidationException::withMessages([
                'status' => false,
                'msg' => [$th],
            ]);
        }
    }

    public function show($id) {
        try {
            $rating = Rating::find($id);
            return response()->json(["status" => true, "data" => $rating]);
        } catch (\Throwable $th) {
            throw ValidationException::withMessages([
                'status' => false,
                'msg' => [$th],
            ]);
        }
    }

    public function store(Request $request) {
        $request->validate([
            "order_id" => "required|uuid",
            "notes" => "required|string",
            "point" => "required|numeric"
        ]);

        $user = Auth::user();
        if (!$user instanceof User) {
            // If the authenticated user is not a Merchant, throw an exception
            throw ValidationException::withMessages([
                "status" => false,
                'msg' => 'Only user can add ratings'
            ]);
        }

        try {
            $check_order = Order::find($request->order_id);
            if($check_order && $user->id == $check_order->user_id) {
                $rating = Rating::create([
                    "user_id" => $user->id,
                    "order_id" => $request->order_id,
                    "notes" => $request->notes,
                    "point" => $request->point,
                    "scale" => 5
                ]);
                return response()->json(["status" => true, "data" => $rating]);
            }else{
                throw ValidationException::withMessages([
                    "status" => false,
                    'msg' => 'Data not found'
                ]); 
            }
            
        } catch (\Throwable $th) {
            throw ValidationException::withMessages([
                "status" => false,
                'msg' => 'Add rating failed'
            ]);
        }
    }

    // public function update(Request $request, $id) {

    //     try {
    //         $service = Rating::find($id);
    //         if($service) {
    //             $service->name = $request->name;
    //             $service->desc = $request->desc;
    //             $service->service_header_id = $request->service_header_id;
    //             $service->price = $request->price;
    //             $service->image_path = $request->image_path;
    //             $service->save();
    //         }else{
    //             throw ValidationException::withMessages([
    //                 "status" => false,
    //                 'msg' => "Data not found",
    //             ]);
    //         }
    //         return response()->json(["status" => true, "data" => $service]);
    //     } catch (\Throwable $th) {
    //         throw ValidationException::withMessages([
    //             "status" => false,
    //             'msg' => [$th],
    //         ]);
    //     }
    // }
    
    // public function delete(Request $request, $id) {
    //     try {
    //         $service = Rating::find($id);
    //         if($service && Auth::user()->id = $service->id) {
    //             $service->delete();
    //             return response()->json(["status" => true, "msg" => "Data has been deleted"]);
    //         }else{
    //             throw ValidationException::withMessages([
    //                 "status" => false,
    //                 'msg' => "Data not found",
    //             ]);
    //         }
    //     } catch (\Throwable $th) {
    //         throw ValidationException::withMessages([
    //             "status" => false,
    //             'msg' => [$th],
    //         ]);
    //     }
    // }

}
