<?php

namespace App\Http\Controllers\Phones;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Phones\PhonePlan;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Phones\PhoneContract;
use Illuminate\Validation\ValidationException;

class PhonePlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $requestContract = PhonePlan::with([
                'contract',
                'phones'
            ])->withCount(['contract','phones'])->get();
            return response()->json($requestContract, 200);

        } catch (Exception $e) {
            Log::error($e->getMessage() . ' | En Línea ' . $e->getFile() . '-' . $e->getLine());
            return response()->json(['message' => 'Ha ocurrido un error al procesar la solicitud.', 'errors' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $phoneContracts = PhoneContract::where('active', true)->get();
            return response()->json([
                $phoneContracts,
            ], 200);

        } catch (Exception $e) {
            Log::error($e->getMessage() . ' | En Línea ' . $e->getFile() . '-' . $e->getLine());
            return response()->json(['message' => 'Ha ocurrido un error al procesar la solicitud.', 'errors' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $rules = [
                'name' => ['required', 'string', Rule::unique('pho_phone_plans', 'name')->whereNull('deleted_at')],'max:250',
                'mobile_data' => ['nullable', 'integer', 'min:0'],
                'roaming_data' => ['nullable', 'integer', 'min:0'],
                'minutes' => ['nullable', 'integer', 'min:0'],
                'roaming_minutes' => ['nullable', 'integer', 'min:0'],
                'active' => ['nullable', 'boolean'],
                'type' => ['required', 'string', 'max:250'],
                'pho_phone_contract_id' => ['required', 'integer', 'exists:dir_contacts,id'],
            ];

            $messages = [
                'required' => 'Falta :attribute.',
                'string' => 'El formato d:attribute es irreconocible.',
                'integer' => 'El formato d:attribute es diferente al que se espera',
                'boolean' => 'El formato d:attribute es diferente al esperado',
                'name.unique' => ':attribute ya existe',
                'exists' => ':attribute no existe'
            ];

            $attributes = [
                'name' => 'el Nombre del Plan',
                'mobile_data' => ' Datos Moviles',
                'roaming_data' => ' Datos Roaming',
                'minutes' => ' Minutos de LLamada',
                'roaming_minutes' => ' Minutos de LLamada Roaming',
                'active' => 'el Estado del Plan',
                'type' => 'el Tipo del Telefono',
                'pho_phone_contract_id' => 'el Identificador del Contrato'
            ];

            $request->validate($rules, $messages, $attributes);


            $requestPlantData = [
                'name' => $request->name,
                'mobile_data' => $request->mobile_data,
                'roaming_data' => $request->roaming_data,
                'minutes' => $request->minutes,
                'roaming_minutes' => $request->roaming_minutes,
                'active' => $request->active,
                'type' => $request->type,
                'pho_phone_contract_id' => $request->pho_phone_contract_id
            ];

            PhonePlan::create($requestPlantData);
            return response()->json($requestPlantData, 200);

        } catch (ValidationException $e) {
            Log::error(json_encode($e->validator->errors()->getMessages()) . ' Información enviada: ' . json_encode($request->all()));
            return response()->json(['message' => $e->validator->errors()->getMessages()], 422);

        } catch (Exception $e) {
            Log::error($e->getMessage() . ' | En línea ' . $e->getFile() . '-' . $e->getLine() . '  Información enviada: ' . json_encode($request->all()));
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function destroy(string $id)
    {
        //
    }
}
