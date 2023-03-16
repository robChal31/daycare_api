<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Models\Children;
use App\Models\Order;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function index() {
        try {
            $orders = Order::paginate(50);
            return response()->json(["status" => true, "data" => $orders]);
        } catch (\Throwable $th) {
            throw ValidationException::withMessages([
                'status' => false,
                'msg' => [$th],
            ]);
        }
    }

    public function show($id) {
        try {
            $order = Order::find($id);
            if($order && (Auth::user()->id == $order->user_id || Auth::user()->id == $order->service->merchant_id)) {
                return response()->json(["status" => true, "data" => $order]);
            }else{
                throw ValidationException::withMessages([
                    "status" => false,
                    'msg' => "Data not found",
                ]);
            }
        } catch (\Throwable $th) {
            throw ValidationException::withMessages([
                'status' => false,
                'msg' => [$th],
            ]);
        }
    }

    public function store(Request $request) {
        $request->validate([
            "note" => "required|string",
            "service_id" => "required|uuid",
            "children_id" => "required|uuid",
            "order_at" => "required|date",
        ]);
       
        $user = Auth::user();
        if (!$user instanceof User) {
            // If the authenticated user is not a Merchant, throw an exception
            throw ValidationException::withMessages([
                "status" => false,
                'msg' => 'Only user can add order'
            ]);
        }
       
        try {
            $children = Children::find($request->children_id);
            if($children->parent_id == $user->id) {
                $service = Order::create([
                    "user_id" => $user->id,
                    "service_id" => $request->service_id,
                    "children_id" => $request->children_id,
                    "note" => $request->note,
                    "status" => "Open",
                    "order_at" => $request->order_at,
                ]);
                return response()->json(["status" => true, "data" => $service]);
            }else{
                throw ValidationException::withMessages([
                    "status" => false,
                    'msg' => 'Data not found'
                ]);
            }
           
        } catch (\Throwable $th) {
            throw ValidationException::withMessages([
                "status" => false,
                'msg' => 'Add order failed'
            ]);
        }
    }
    
    public function getMyOrder() {
        try {
            $order = Order::where('user_id', Auth::user()->id)->get();
            return response()->json(["status" => true, "data" => $order]);
        } catch (\Throwable $th) {
            throw ValidationException::withMessages([
                'status' => false,
                'msg' => [$th],
            ]);
        }
    }

    // public function update(Request $request, $id) {
    //     $merchant = Auth::user();
    //     if (!$merchant instanceof Merchant) {
    //         // If the authenticated user is not a Merchant, throw an exception
    //         throw ValidationException::withMessages([
    //             "status" => false,
    //             'msg' => 'Only user can add ratings'
    //         ]);
    //     }

    //     try {
    //         $service = Order::find($id);
    //         if($service && $merchant->id == $service->merchant_id) {
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
    //         $service = Order::find($id);
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
