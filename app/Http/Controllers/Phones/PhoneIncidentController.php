<?php

namespace App\Http\Controllers\Phones;

use Exception;
use App\Http\Controllers\Controller;
use App\Models\Phones\Phone;
use App\Models\Phones\PhoneIncident;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


class PhoneIncidentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            //Query to get phones list
            $phoneIncidents = PhoneIncident::with([
                'phone'
            ])->get();
            return response()->json($phoneIncidents, 200);

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
            //Getting active phones
            $phones = Phone::where('active', true)->get();

            return response()->json([
                $phones
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
        //
        try {
            $rules = [
                'file_name' => ['nullable','string'],
                'file_name_original' => ['nullable','string'],
                'file_mimetype' => ['nullable','string'],
                'file_size' => ['nullable', 'numeric','between:0,9999.99'],
                'file_path' => ['nullable', 'string'],
                'percentage' => ['required', 'numeric','between:0,100'],

                'active' => ['nullable','boolean'],

                'pho_phone_id' => ['integer', 'exists:pho_phones,id'],

            ];

            $messages = [
                'required' => 'Falta :attribute.',
                'string' => 'El formato d:attribute es irreconocible.',

                'boolean' => 'El formato de :attribute es diferente al esperado',

                'numeric' => 'El formato d:attribute debe ser numérico.',
                'between' => 'El formato d:attribute debe ser entre 0 y 100.',
                'integer' => 'El formato d:attribute es irreconocible.',
            ];

            $attributes = [
                'file_name' => 'el Nombre del Incidente',
                'file_name_original' => 'el Nombre Original del Incidente',
                'file_mimetype' => 'el Mimetype del Incidente',
                'file_size' => 'el Size del Incidente',
                'file_path' => 'el Path del Incidente',
                'percentage' => 'el Porcentaje del Incidente',
                'active' => 'el Estado del Incidente',

                'pho_phone_id' => 'el Identificador del Teléfono',
            ];

            $request->validate($rules, $messages, $attributes);


                $requestPhoneIncidentData = [
                    'file_name' => $request->file_name,
                    'file_name_original' => $request->file_name_original,
                    'file_mimetype' => $request->file_mimetype,
                    'file_size' => $request->file_size,
                    'file_path' => $request->file_path,
                    'percentage' => $request->percentage,
                    'price' => $request->price,

                    'active' => $request->active == 'true' ? true : false,

                    'pho_phone_id' => $request->adm_employee_id,

                ];

               PhoneIncident::create($requestPhoneIncidentData);

            return response()->json($requestPhoneIncidentData, 200);
        } catch (ValidationException $e) {
            Log::error(json_encode($e->validator->errors()->getMessages()) .' Información enviada: ' . json_encode($request->all()));

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
                ['id' => ['required', 'integer', 'exists:pho_phone_incidents,id']],
                [
                    'id.required' => 'Falta :attribute.',
                    'id.integer' => ':attribute irreconocible.',
                    'id.exists' => ':attribute solicitado sin coincidencia.',
                ],
                ['id' => 'Identificador de Incidencia de Teléfono de Solicitud.'],
            )->validate();

            $phoneIncident =PhoneIncident::with([
                'phone'
            ])->findOrFail($validatedData['id']);

            return response()->json($phoneIncident, 200);

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
        //
        try {
            //Validate id
            $validatedData = Validator::make(
                ['id' => $id],
                ['id' => ['required', 'integer', 'exists:pho_phones,id']],
                [
                    'id.required' => 'Falta :attribute.',
                    'id.integer' => ':attribute irreconocible.',
                    'id.exists' => ':attribute solicitado sin coincidencia.',
                ],
                ['id' => 'Identificador de Teléfono de Solicitud.'],
            )->validate();

            //Getting the phone incident to edit
            $phoneIncident =PhoneIncident::with([
                'phone'
            ])->findOrFail($validatedData['id']);

            //Getting active phones
            $phones = Phone::where('active', true)->get();

            return response()->json([
                $phoneIncident,
                $phones,
            ], 200);

        } catch (Exception $e) {
            Log::error($e->getMessage() . ' | En Línea - ' . $e->getLine());
            return response()->json(['message' => 'Ha ocurrido un error al procesar la solicitud.', 'errors' => $e->getMessage()], 500);
        }
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
        //
        try {
            $validatedData = Validator::make(
                ['id' => $id],
                ['id' => ['required', 'integer', 'exists:pho_phones,id']],
                [
                    'id.required' => 'Falta el :attribute.',
                    'id.integer' => 'El :attribute es irreconocible.',
                    'id.exists' => 'El :attribute enviado, sin coincidencia.',
                ],
                [
                    'id' => 'Identificador de Incidencia del Teléfono de Solicitud',
                ]
            )->validate();

            $phone = NULL;

            DB::transaction(function() use ($validatedData, &$phone) {
                $phone = PhoneIncident::findOrFail($validatedData['id']);
                $phone->delete();
                $phone['status'] = 'deleted';
            });

            return response()->json($phone, 200);
        } catch (ValidationException $e) {
            Log::error(json_encode($e->validator->errors()->getMessages()) . '. Información enviada: ' . json_encode($id));

            return response()->json(['message' => $e->validator->errors()->getMessages()], 422);
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' | ' . $e->getFile() . ' - ' . $e->getLine() . '. Información enviada: ' . json_encode($id));

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


}
