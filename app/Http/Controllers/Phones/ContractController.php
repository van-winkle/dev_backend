<?php

namespace App\Http\Controllers\Phones;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Phones\PhoneContract;
use Illuminate\Support\Facades\Validator;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contract = PhoneContract::all();
        if($contract->count()>0){
            return response()->json([
                'code' => 200,
                'data' => $contract
            ],200);
        } else {
            return response()->json([
                'code' => 404,
                'data' => 'Data not found'
            ],404);
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
        $validation = Validator::make($request->all(),[
            'code' => 'required|string|unique:pho_phone_contracts',
            'start_date' => 'required|date_format:Y-m-d',
            'expiry_date' => 'required|date_format:Y-m-d',
            'active' => 'required|boolean',
            'dir_contact_id' => 'required|integer'
        ]);

        if($validation->fails()){
            return response()->json([
                'code' => 400,
                'date' => $validation->messages()
            ], 400);
        } else {
           PhoneContract::create($request->all());
            return response()->json([
                'code' => 200,
                'data' => 'data OK'
            ],200);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $contract = PhoneContract::find($id);
        $contract->contact;
         return $contract;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

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
    public function destroy(string $id)
    {
        $contract = PhoneContract::destroy($id);
        return $contract;
    }
}
