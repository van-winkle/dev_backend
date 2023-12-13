<?php

namespace App\Http\Controllers\Phones;

use App\Models\Phones\Phone;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PhoneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $phones = Phone::all();
        //$phones->employee;
        return $phones;
        if($phones == []){
            return response()->json([
                'code'=>404,
                'data'=>'No data'
            ], 404);
        }
        else if ($phones->count()>0) {
            return response()->json([
                'code'=>200,
                'data'=>$phones
            ], 200);
        } else {
            return response()->json([
                'code'=>404,
                'data'=>'No data'
            ], 404);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Validation
        $validation = Validator::make($request->all(), [
            'number' => 'required|string',
            'type' => 'required|string',
            'imei' => 'required|string',
            'price' => 'required|numeric|min:0|max:9999.99',
            'active' => 'required|boolean',
            'adm_employee_id' => 'required|integer',
            'pho_phone_plan_id' => 'required|integer',
            'pho_phone_contract_id' => 'required|integer',
            'pho_phone_model_id' => 'required|integer'
        ]);
        if ($validation->fails()) {
            return response()->json([
                'code' => 400,
                //'request' =>$request->all(),
                'data' => $validation->messages()
            ], 400);
        } else {
            //Store new record
            Phone::create($request->all());
            return response()->json([
                'code' => 200,
                'data' => 'Record saved'
            ], 200);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        //Validation
        $validation = (gettype($id) === "integer") ? true : false;

        if (!$validation) {
            return response()->json([
                'code' => 400,
                'data' => 'Incorrect identifier: ' . gettype($id)
            ], 400);
        } else {
            //Get the phone record
            $phone = Phone::find($id);
            if ($phone) {

                return response()->json([
                    'code' => 200,
                    'data' => $phone
                ], 200);
            } else {
                return response()->json([
                    'code' => 404,
                    'data' => 'Record not found'
                ], 404);
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        //Validation
        $validation = (gettype($id) === "integer") ? true : false;

        if (!$validation) {
            return response()->json([
                'code' => 400,
                'data' => 'Incorrect identifier: ' . gettype($id)
            ], 400);
        } else {

            $phone = Phone::find($id);
            if ($phone) {
                $phone->delete();
                return response()->json([
                    'code' => 200,
                    'data' => 'Record removed'
                ], 200);
            } else {
                return response()->json([
                    'code' => 404,
                    'data' => 'Record not found'
                ], 404);
            }
        }
    }
}
