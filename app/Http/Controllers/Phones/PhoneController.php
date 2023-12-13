<?php

namespace App\Http\Controllers\Phones;

use Exception;
use App\Models\Phones\Phone;
use Illuminate\Http\Request;
use App\Models\Phones\PhoneBrand;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Phones\AdminEmployee;
use App\Models\Phones\PhoneContact;
use App\Models\Phones\PhonePlan;
use Illuminate\Support\Facades\Validator;

class PhoneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            //Query
            $phones = Phone::with([
                'employee',
                'plan',
                'contract',
                'model',
                'incidents'
            ])->withCount(['incidents'])->get();
            return response()->json($phones, 200);

        } catch (Exception $e) {
            Log::error($e->getMessage() . ' | En Línea - ' . $e->getLine());
            return response()->json(['message' => 'Ha ocurrido un error al procesar la solicitud.', 'errors' => $e->getMessage()], 500);
        }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            //Query
            //Getting active employees
            $admEmployees = AdminEmployee::all();
            /* $admEmployees = AdminEmployee::where('active', true)
            ->whereHas('requestType', function ($query) {
                $query->where('active', true);
            })->get(); */

            //Getting active plans
            $admEmployees = PhonePlan::all();

            //Getting active contracts
            $phoneContracts = PhoneContact::all();

            //Getting Brands and its models
            $phoneBrands = PhoneBrand::with([
                'models'
            ])->withCount('models')
            ->get();

            return response()->json([
                $admEmployees,
                $phoneBrands,
                $phoneContracts,
            ], 200);

        } catch (Exception $e) {
            Log::error($e->getMessage() . ' | En Línea - ' . $e->getLine());
            return response()->json(['message' => 'Ha ocurrido un error al procesar la solicitud.', 'errors' => $e->getMessage()], 500);
        }
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
    public function edit(int $id)
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
