<?php

namespace App\Http\Controllers\Phones;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Phones\PercentageRules;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PercentageRuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $requestPercentages = PercentageRules::get();

            return response()->json($requestPercentages, 200);
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $rules = [
                'incident_count' => [
                    'nullable',
                    'integer',
                    Rule::unique(
                        'pho_phone_percentage_rules',
                        'incident_count'
                    )->whereNull(
                        'deleted_at'
                    )
                ],
                'percentage_discount' => [
                    'required',
                    'max:100.00',
                    'decimal:0,2',
                ],
                'pho_phone_contract_id' => [
                    'required',
                    'integer',
                    Rule::exists(
                        'pho_phone_percentage_rules',
                        'id'
                    )->whereNull(
                        'deleted_at'
                    )
                ]
            ];
            $messages = [
                'required' => 'Falta :attribute.',
                'integer' => 'El formato de :attribute es irreconocible.',
                'exists' => ':attribute no existe o esta inactivo',
                'max' => ':attribute excede los caracteres máximos',
                'decimal' => ':attribute solo puede tener 2 decimales',

            ];

            $attributes = [
                
            ];

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
            $validateData = Validator::make(
                ['id' => $id],
                [
                    'id' => [
                        'required',
                        'integer',
                        'exists:pho_phone_percentage_rules'
                    ]
                ],
                [
                    'id.required' => 'Falta :attribute.',
                    'id.integer' => ':attribute irreconocible',
                    'id.exists' => ':attribute solicitado sin coincidencia.',
                ],
                [
                    'id' => 'Identificador de Reglas de Porcentaje'
                ]
            )->validate();

            $PercentageRules = PercentageRules::with(
                [
                    'contract',
                ]
            )->withCount(
                [
                    'contract',
                ]
            )->findOrFail($validateData['id']);

            return response()->json($PercentageRules, 200);
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' | En Línea ' . $e->getFile() . '-' . $e->getLine() . '. Información enviada: ' . json_encode($id));
            return response()->json(['message' => 'Ha ocurrido un error al procesar la solicitud.', 'errors' => $e->getMessage()], 500);
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
    public function destroy(string $id)
    {
        //
    }
}
