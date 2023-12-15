<?php

namespace App\Http\Controllers\Phones;

use Exception;
use Illuminate\Http\Request;
use App\Models\Phones\PhoneModel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ModelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $model = PhoneModel::with([
                'brand'])
                ->withCount(['brand'])
                ->get();
            return response()->json($model, 200);

        } catch (Exception $e) {
            Log::error($e->getMessage() );
            return response()->json(['message' => 'Ha ocurrido un error al procesar la solicitud.', 'errors' => $e->getMessage()], 500);

        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $rules = [
                "name" => ['required', 'max:50', Rule::unique('pho_phone_models','name')->whereNull('deleted_at')],
                'active' => ['nullable', 'boolean'],
                'pho_phone_brand_id' => ['required', 'integer','exists:pho_phone_brands,id'],

            ];
            $messages = [
                'required' => 'El valor del :attribute es necesario',
                'boolean' => 'El formato de :attribute es diferente al esperado',
                'max' => 'La longitud máxima para :attribute es de 50 caracteres',
                'unique' => 'Ya existe un registro con el mismo nombre.',
                'integer' => 'El formato de:attribute es irreconocible.'
            ];

            $attributes = [
                'name' => 'Nombre',
                'active' => 'Activo',
                'pho_phone_brand_id' => 'Identificador marca',
            ];

            $request->validate($rules, $messages, $attributes);

            $requestModelData = [
                'name' => $request->name,
                'active' => $request->active == 'true' ? true : false,
                'pho_phone_brand_id'=> $request->pho_phone_brand_id,
            ];

            PhoneModel::create($requestModelData);
            return response()->json($requestModelData, 200);

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
                ['id' => ['required', 'integer', 'exists:pho_phones,id']],
                [
                    'id.required' => 'Falta :attribute.',
                    'id.integer' => ':attribute irreconocible.',
                    'id.exists' => ':attribute solicitado sin coincidencia.',
                ],
                ['id' => 'Identificador de Categoría de Solicitud.'],
            )->validate();

            $phoneModel = PhoneModel::with([
                'brand',
            ])->withCount(['brand'])->findOrFail($validatedData['id']);

            return response()->json($phoneModel, 200);
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
        try {
            $validatedData = Validator::make(
                ['id' => $id],
                ['id' => ['required', 'integer', 'exists:pho_phone_models,id']],
                [
                 'id.required' => 'Falta :attribute.',
                 'id.integer' => ':attribute irreconocible.',
                 'id.exists' => ':attribute solicitado sin coincidencia.',
                ],
                ['id' => 'Identificador de Categoría de Solicitud.'],
            )->validate();

            $model = PhoneModel::with([
                'brand',
                //'phones'
            ])->withCount(['plans'])->findOrFail($validatedData['id']);
            $phoneModels = PhoneModel::where('active', true)->get();
            return response()->json([$model, $phoneModels], 200);

        } catch (Exception $e) {
            Log::error($e->getMessage() . ' | En Línea ' . $e->getFile() . '-' . $e->getLine() . '. Información enviada: ' . json_encode($id));
            return response()->json(['message' => 'Ha ocurrido un error al procesar la solicitud.', 'errors' => $e->getMessage()], 500);
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)

    {
        try {
            $rules = [
                'id' => ['required', 'integer', 'exists:pho_phone_brands,id'],
                "name" => ['required', 'max:50', Rule::unique('pho_phone_models','name')->whereNull('deleted_at')],
                'active' => ['nullable', 'boolean'],
                'pho_phone_brand_id' => ['required', 'integer','exists:pho_phone_brands,id'],

            ];
            $messages = [
                'required' => 'El valor del :attribute es necesario',
                'boolean' => 'El formato de :attribute es diferente al esperado',
                'max' => 'La longitud máxima para :attribute es de 50 caracteres',
                'unique' => 'Ya existe un registro con el mismo nombre.',
                'integer' => 'El formato de:attribute es irreconocible.'
            ];

            $attributes = [
                'name' => 'Nombre',
                'active' => 'Activo',
                'pho_phone_brand_id' => 'Identificador marca',
            ];

            $request->validate($rules, $messages, $attributes);


            $requestModel = PhoneModel::findOrFail($request->id);

            $requestModelData = [
                'name' => $request->name,
                'active' => $request->active == 'true' ? true : false,
                'pho_phone_brand_id'=> $request->pho_phone_brand_id,
            ];

            $requestModel->update($requestModelData);
            return response()->json($requestModel, 200);

        } catch (ValidationException $e) {
            Log::error(json_encode($e->validator->errors()->getMessages()) . ' Información enviada: ' . json_encode($request->all()));
            return response()->json(['message' => $e->validator->errors()->getMessages()], 422);

        } catch (Exception $e) {
            Log::error($e->getMessage() . ' | En línea ' . $e->getFile() . '-' . $e->getLine() . '  Información enviada: ' . json_encode($request->all()));
            return response()->json(['message' => $e->getMessage()], 500);
        }


    /**
     * Remove the specified resource from storage.
     */
    }

    public function destroy(int $id)
    {
        try {
            $validatedData = Validator::make(
                ['id' => $id],
                ['id' => ['required', 'integer', 'exists:pho_phone_models,id']],
                [
                    'id.required' => 'Falta el :attribute.',
                    'id.integer' => 'El :attribute es irreconocible.',
                    'id.exists' => 'El :attribute enviado, sin coincidencia.',
                ],
                [
                    'id' => 'Identificador del Teléfono de Solicitud',
                ]
            )->validate();

            $phoneModel = NULL;

            DB::transaction(function() use ($validatedData, &$phoneModel) {
                $phoneModel = PhoneModel::findOrFail($validatedData['id']);
                $phoneModel->delete();
                $phoneModel['status'] = 'deleted';
            });

            return response()->json($phoneModel, 200);
        } catch (ValidationException $e) {
            Log::error(json_encode($e->validator->errors()->getMessages()) . '. Información enviada: ' . json_encode($id));

            return response()->json(['message' => $e->validator->errors()->getMessages()], 422);
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' | ' . $e->getFile() . ' - ' . $e->getLine() . '. Información enviada: ' . json_encode($id));

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function phoneModelsActive($id = null)
    {
        try {
            $commonQuery = PhoneModel::where('active', true);

            if ($id !== null) {
                $validatedData = Validator::make(
                    ['id' => $id],
                    ['id' => ['required', 'integer', 'exists:pho_phone_models,id']],
                    [
                        'id.required' => 'Falta el :attribute.',
                        'id.integer' => 'El :attribute es irreconocible.',
                        'id.exists' => 'El :attribute enviado, sin coincidencia.',
                    ],
                    [
                        'id' => 'Identificador de Contrato de Solicitud',
                    ]
                )->validate();

                $requestPhoneModel = $commonQuery->with([
                    'brand',

                ])->withCount(['brand'])->findOrFail($validatedData['id']);
            } else {
                $requestPhoneModel = $commonQuery->with([
                    'brand',
                ])->withCount(['brand'])->get();
            }

            return response()->json($requestPhoneModel, 200);
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' | ' . $e->getFile() . ' - ' . $e->getLine() .'. Información enviada: ' . json_encode($id));

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}