<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use App\Models\Service;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ServiceController extends Controller
{
    public function index() {
        try {
            $services = Service::paginate(50);
            return response()->json(["status" => true, "data" => $services]);
        } catch (\Throwable $th) {
            throw ValidationException::withMessages([
                'status' => false,
                'msg' => [$th],
            ]);
        }
    }

    public function show($id) {
        try {
            $service = Service::find($id);
            return response()->json(["status" => true, "data" => $service]);
        } catch (\Throwable $th) {
            throw ValidationException::withMessages([
                'status' => false,
                'msg' => [$th],
            ]);
        }
    }

    public function store(Request $request) {
        $request->validate([
            "name" => "required|string|max:255",
            "desc" => "required|string",
            "price" => "required|numeric",
            "service_header_id" => "required|uuid",
            "image_path" => "required|string|max:255",
        ]);

        $user = Auth::user();
        if (!$user instanceof Merchant) {
            // If the authenticated user is not a Merchant, throw an exception
            throw ValidationException::withMessages([
                "status" => false,
                'msg' => 'Only merchants can add services'
            ]);
        }
        
        try {
            $service = Service::create([
                "merchant_id" => $user->id,
                "service_header_id" => $request->service_header_id,
                "name" => $request->name,
                "desc" => $request->desc,
                "price" => $request->price,
                "image_path" => $request->image_path,
            ]);
            return response()->json(["status" => true, "data" => $service]);
        } catch (\Throwable $th) {
            throw ValidationException::withMessages([
                "status" => false,
                'msg' => 'Add service failed'
            ]);
        }
    }

    public function update(Request $request, $id) {
        $merchant = Auth::user();
        if (!$merchant instanceof Merchant) {
            // If the authenticated user is not a Merchant, throw an exception
            throw ValidationException::withMessages([
                "status" => false,
                'msg' => 'Only user can add ratings'
            ]);
        }

        try {
            $service = Service::find($id);
            if($service && $merchant->id == $service->merchant_id) {
                $service->name = $request->name;
                $service->desc = $request->desc;
                $service->service_header_id = $request->service_header_id;
                $service->price = $request->price;
                $service->image_path = $request->image_path;
                $service->save();
            }else{
                throw ValidationException::withMessages([
                    "status" => false,
                    'msg' => "Data not found",
                ]);
            }
            return response()->json(["status" => true, "data" => $service]);
        } catch (\Throwable $th) {
            throw ValidationException::withMessages([
                "status" => false,
                'msg' => [$th],
            ]);
        }
    }
    
    public function delete(Request $request, $id) {
        try {
            $service = Service::find($id);
            if($service && Auth::user()->id = $service->id) {
                $service->delete();
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
                'msg' => [$th],
            ]);
        }
    }
}
