<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Models\ServiceHeader;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ServiceHeadersController extends Controller
{
    public function index() {
        try {
            $service_headers = ServiceHeader::get();
            return response()->json(["status" => true, "data" => $service_headers]);
        } catch (\Throwable $th) {
            throw ValidationException::withMessages([
                'status' => false,
                'msg' => [$th],
            ]);
        }
    }

    public function show($id) {
        try {
            $service_header = ServiceHeader::with('services')->find($id);
            return response()->json(["status" => true, "data" => $service_header]);
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
        ]);
        
        try {
            $service_header = ServiceHeader::create([
                'name' => $request->name,
                'desc' => $request->desc,
            ]);
            return response()->json(["status" => true, "data" => $service_header]);
        } catch (\Throwable $th) {
            throw ValidationException::withMessages([
                "status" => false,
                'msg' => [$th],
            ]);
        }
    }

    public function update(Request $request, $id) {

        try {
            $service_header = ServiceHeader::find($id);
            if($service_header) {
                $service_header->name = $request->name;
                $service_header->desc = $request->desc;
                $service_header->save();
            }else{
                throw ValidationException::withMessages([
                    "status" => false,
                    'msg' => "Data not found",
                ]);
            }
            return response()->json(["status" => true, "data" => $service_header]);
        } catch (\Throwable $th) {
            throw ValidationException::withMessages([
                "status" => false,
                'msg' => [$th],
            ]);
        }
    }
    
    public function delete(Request $request, $id) {

        try {
            $service_header = ServiceHeader::find($id);
            if($service_header) {
                $service_header->delete();
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
