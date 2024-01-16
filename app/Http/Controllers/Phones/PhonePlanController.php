<?php

namespace App\Http\Controllers\Phones;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Phones\PhonePlan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PhonePlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $requestContract = PhonePlan::with(
                [
                    'contract',
                    'typePhone'
                ]
            )->withCount(
                [
                    'phones'
                ]
            )->get();
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
        /*   try {
            $phoneContracts = PhoneContract::where('active', true)->get();
            return response()->json([
                $phoneContracts,
            ], 200);

        } catch (Exception $e) {
            Log::error($e->getMessage() . ' | En Línea ' . $e->getFile() . '-' . $e->getLine());
            return response()->json(['message' => 'Ha ocurrido un error al procesar la solicitud.', 'errors' => $e->getMessage()], 500);
        } */
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $rules = [
                'name' => [
                    'required',
                    'string',
                    'max:250',
                    Rule::unique('pho_phone_plans', 'name')
                        ->where('pho_phone_contract_id', $request->pho_phone_contract_id)
                        ->whereNull('deleted_at')
                ],
                'mobile_data' => [
                    'nullable',
                    'integer',
                    'min:0'
                ],
                'roaming_data' => [
                    'nullable',
                    'integer',
                    'min:0'
                ],
                'minutes' => [
                    'nullable',
                    'integer',
                    'min:0'
                ],
                'roaming_minutes' => [
                    'nullable',
                    'integer',
                    'min:0'
                ],
                'active' => [
                    'nullable',

                ],
                'pho_phone_type_phone_id' => [
                    'required',
                    'integer',
                    Rule::exists(
                        'pho_phone_type_phones',
                        'id'
                    )->where(
                        'active',
                        true
                    )->whereNull(
                        'deleted_at'
                    )
                ],
                'pho_phone_contract_id' => [
                    'required',
                    'integer',
                    Rule::exists(
                        'pho_phone_contracts',
                        'id'
                    )->where(
                        'active',
                        true
                    )->whereNull(
                        'deleted_at'
                    )
                ],
            ];

            $messages = [
                'required' => 'Falta :attribute.',
                'string' => 'El formato de :attribute es irreconocible.',
                'min' => ':attributes ingresado debe ser mayor o igual a 0',
                'integer' => 'El formato de :attribute es diferente al que se espera',
                'boolean' => 'El formato de :attribute es diferente al esperado',
                'name.unique' => ':attribute ya existe en el contrato.',
                'exists' => ':attribute no existe o esta inactivo',
                'max' => ':attribute excede los caracteres máximos',
            ];

            $attributes = [
                'name' => 'El Nombre del Plan',
                'mobile_data' => ' Datos Móviles',
                'roaming_data' => ' Datos Roaming',
                'minutes' => ' Minutos de LLamada',
                'roaming_minutes' => ' Minutos de Llamada Roaming',
                'active' => 'el Estado del Plan',
                'pho_phone_type_phone_id' => 'el Tipo del Teléfono',
                'pho_phone_contract_id' => 'el Identificador del Contrato'
            ];

            $request->validate($rules, $messages, $attributes);


            $requestPlantData = [
                'name' => $request->name,
                'mobile_data' => $request->mobile_data,
                'roaming_data' => $request->roaming_data,
                'minutes' => $request->minutes,
                'roaming_minutes' => $request->roaming_minutes,
                'active' => $request->active == 'true' || $request->active == 1 || $request->active === null ? true : false,
                'pho_phone_type_phone_id' => $request->pho_phone_type_phone_id,
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
    public function show(int $id)
    {
        try {
            $validatedData = Validator::make(
                ['id' => $id],
                ['id' => ['required', 'integer', 'exists:pho_phone_plans,id']],
                [
                    'id.required' => 'Falta :attribute.',
                    'id.integer' => ':attribute irreconocible.',
                    'id.exists' => ':attribute solicitado sin coincidencia.',
                ],
                ['id' => 'Identificador de Planes'],
            )->validate();

            $plan = PhonePlan::with([
                'contract',
                'phones',
                'typePhone'
            ])->withCount(['phones'])->findOrFail($validatedData['id']);



            return response()->json($plan, 200);
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' | En Línea ' . $e->getFile() . '-' . $e->getLine() . '. Información enviada: ' . json_encode($id));
            return response()->json(['message' => 'Ha ocurrido un error al procesar la solicitud.', 'errors' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        /*  try {
            $validatedData = Validator::make(
                ['id' => $id],
                ['id' => ['required', 'integer', 'exists:pho_phone_plans,id']],
                [
                 'id.required' => 'Falta :attribute.',
                 'id.integer' => ':attribute irreconocible.',
                 'id.exists' => ':attribute solicitado sin coincidencia.',
                ],
                ['id' => 'Identificador de Plan'],
            )->validate();

            $plan = PhonePlan::with([
                'contract',
                'phones'
            ])->withCount(['contract','phones'])->findOrFail($validatedData['id']);
            $phoneContract = PhoneContract::where('active', true)->get();
            return response()->json([$plan, $phoneContract], 200);

        } catch (Exception $e) {
            Log::error($e->getMessage() . ' | En Línea ' . $e->getFile() . '-' . $e->getLine() . '. Información enviada: ' . json_encode($id));
            return response()->json(['message' => 'Ha ocurrido un error al procesar la solicitud.', 'errors' => $e->getMessage()], 500);
        } */
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        try {
            $requestPlan = PhonePlan::findOrFail($id);

            $rules = [
                'id' => [
                    'required',
                    'integer',
                    'exists:pho_phone_plans,id',
                    Rule::in([$id])
                ],
                'name' => [
                    'required',
                    'string',
                    'max:250',
                    Rule::unique('pho_phone_plans', 'name')
                        ->where('pho_phone_contract_id', $request->pho_phone_contract_id)
                        ->whereNull('deleted_at')
                        ->ignore($request->id),
                ],
                'mobile_data' => [
                    'nullable',
                    'integer',
                    'min:0'
                ],
                'roaming_data' => [
                    'nullable',
                    'integer',
                    'min:0'
                ],
                'minutes' => [
                    'nullable',
                    'integer',
                    'min:0'
                ],
                'roaming_minutes' => [
                    'nullable',
                    'integer',
                    'min:0'
                ],
                'active' => [
                    'nullable'
                ],
                'pho_phone_type_phone_id' => [
                    'required',
                    'integer',
                    Rule::exists(
                        'pho_phone_type_phones',
                        'id'
                    )->where(
                        'active',
                        true
                    )->whereNull(
                        'deleted_at'
                    )
                ],
                'pho_phone_contract_id' => [
                    'required',
                    'integer',
                    Rule::exists('pho_phone_contracts', 'id')
                        ->where('active', true)
                        ->whereNull('deleted_at')
                        ->where(function ($query) use ($requestPlan) {
                            $query->where('id', $requestPlan->pho_phone_contract_id);
                        })
                ],
            ];

            $messages = [
                'required' => 'Falta :attribute.',
                'string' => 'El formato de :attribute es irreconocible.',
                'min' => ':attributes ingresado debe ser mayor o igual a 0.',
                'integer' => 'El formato de :attribute es diferente al que se espera.',
                'boolean' => 'El formato de :attribute es diferente al esperado.',
                'name.unique' => ':attribute ya existe en el contrato.',
                'id.in' => 'El contrato no se puede modificar.',
                'exists' => ':attribute no existe o esta inactivo',
                'max' => ':attribute excede los caracteres máximos.',
                'pho_phone_contract_id.exists' => 'El campo Contrato no puede ser modificado.',
            ];

            $attributes = [
                'name' => 'El Nombre del Plan',
                'mobile_data' => ' Datos Móviles',
                'roaming_data' => ' Datos Roaming',
                'minutes' => ' Minutos de LLamada',
                'roaming_minutes' => ' Minutos de Llamada Roaming',
                'active' => 'el Estado del Plan',
                'pho_phone_type_phone_id' => 'el Tipo del Teléfono',
                'pho_phone_contract_id' => 'el contrato',
            ];

            $request->validate($rules, $messages, $attributes);

            $requestPlan = PhonePlan::findOrFail($request->id);

            $requestPlanData = [
                'name' => $request->name,
                'mobile_data' => $request->mobile_data,
                'roaming_data' => $request->roaming_data,
                'minutes' => $request->minutes,
                'roaming_minutes' => $request->roaming_minutes,
                'active' => $request->active == 'true' ? true : false,
                'pho_phone_type_phone_id' => $request->pho_phone_type_phone_id,
                'pho_phone_contract_id' => $request->pho_phone_contract_id
            ];

            $requestPlan->update($requestPlanData);
            return response()->json($requestPlan, 200);
        } catch (ValidationException $e) {
            Log::error(json_encode($e->validator->errors()->getMessages()) . ' Información enviada: ' . json_encode($request->all()));
            return response()->json(['message' => $e->validator->errors()->getMessages()], 422);
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' | En línea ' . $e->getFile() . '-' . $e->getLine() . '  Información enviada: ' . json_encode($request->all()));
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $validatedData = Validator::make(
                ['id' => $id],
                ['id' => ['required', 'integer', 'exists:pho_phone_plans,id']],
                [
                    'id.required' => 'Falta el :attribute.',
                    'id.integer' => 'El :attribute es irreconocible.',
                    'id.exists' => 'El :attribute enviado, sin coincidencia.',
                ],
                ['id' => 'Identificador del Plan',]
            )->validate();

            $contract = NULL;

            DB::transaction(function () use ($validatedData, &$contract) {
                $contract = PhonePlan::findOrFail($validatedData['id']);
                $contract->delete();
                $contract['status'] = 'deleted';
            });

            return response()->json($contract, 200);
        } catch (ValidationException $e) {
            Log::error(json_encode($e->validator->errors()->getMessages()) . '. Información enviada: ' . json_encode($id));

            return response()->json(['message' => $e->validator->errors()->getMessages()], 422);
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' | ' . $e->getFile() . ' - ' . $e->getLine() . '. Información enviada: ' . json_encode($id));

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function activePlans(int $id = null)
    {
        try {
            $commonQuery = PhonePlan::where('active', true);
            if ($id !== null) {
                $validatedData = Validator::make(
                    ['id' => $id],
                    ['id' => ['required', 'integer', 'exists:pho_phone_plans,id']],
                    [
                        'id.required' => 'Falta el :attribute.',
                        'id.integer' => 'El :attribute es irreconocible.',
                        'id.exists' => 'El :attribute enviado, sin coincidencia.',
                    ],
                    ['id' => 'Identificador del Plan',]
                )->validate();

                $requestContracts = $commonQuery->with(['contract', 'phones'])->findOrFail($validatedData['id']);
            } else {
                $requestContracts = $commonQuery->with(['contract'])->withCount('phones')->get();
            }
            return response()->json($requestContracts, 200);
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' | ' . $e->getFile() . ' - ' . $e->getLine() . '. Información enviada: ' . json_encode($id));
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
