<?php

namespace App\Http\Controllers\Phones;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Phones\PhoneBrand;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $phoneBrands = PhoneBrand::withCount(
                'models'
            )->get();

            return response()->json($phoneBrands, 200);
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' | En Línea - ' . $e->getLine());

            return response()->json([
                'message' => 'Ha ocurrido un error al procesar la solicitud.',
                'error' => $e->getMessage()
            ], 500);
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
                'name' => [
                    'required',
                    'string',
                    'max:50',
                    Rule::unique('pho_phone_brands', 'name')->whereNull('deleted_at')
                ],
                'active' => [
                    'nullable',

                ],
            ];

            $messages = [
                'required' => 'El campo :attribute es requerido',
                'string' => 'El campo :attribute se espera que sea texto.',
                'boolean' => 'El formato de :attribute es diferente al esperado',
                'name.unique' => 'El nombre ya existe!',
            ];

            $attributes = [
                'name' => 'Nombre',
                'active' => 'Estado',
            ];

            $request->validate(
                $rules,
                $messages,
                $attributes
            );

            // $requestBrandData = [
            //     'name' => $request->name,
            //     'active' => $request->active == 'true' ? true : false
            // ];

            $requestBrandData = [
                'name' => $request->name,
                'active' => $request->active === 'true' || $request->active === null ? true : false,
                // 'active' => is_null($request->active) ? null : ($request->active == 'true' ? true : false)
            ];

            $newBrand = PhoneBrand::create($requestBrandData);

            return response()->json($newBrand, 200);
        } catch (ValidationException $e) {

            Log::error(json_encode($e->validator->errors()->getMessages()) . ' Información enviada: ' . json_encode($request->all()));

            return response()->json(['errors' => $e->errors()], 400);
        } catch (Exception $e) {

            Log::error($e->getMessage() . ' | En línea ' . $e->getFile() . '-' . $e->getLine() . '  Información enviada: ' . json_encode($request->all()));

            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try {
            $validatedData = Validator::make(
                [
                    'id' => $id
                ],
                [
                    'id' => [
                        'required',
                        'integer',
                        Rule::exists('pho_phone_brands', 'id')
                            ->whereNull('deleted_at')
                    ],
                ],
                [
                    'id.required' => 'Falta :attribute.',
                    'id.integer' => ':attribute irreconocible.',
                    'id.exists' => ':attribute no coincide con los registros.',
                ],
                [
                    'id' => 'Identificador de Marca de Teléfono'
                ]
            )->validate();

            $phoneBrand = PhoneBrand::with(
                [
                    'models'
                ]
            )->withCount(
                [
                    'models'
                ]
            )->findOrFail($validatedData['id']);

            return response()->json($phoneBrand, 200);
        } catch (ValidationException $e) {

            return response()->json(['errors' => $e->errors()], 400);
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
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        try {
            $rules = [
                'id' => [
                    'required',
                    'integer',
                    Rule::exists('pho_phone_brands','id')->whereNull('deleted_at'),
                    Rule::in([$id])
                ],
                'name' => [
                    Rule::unique('pho_phone_brands', 'name')->whereNull('deleted_at'),
                    'required',
                    'string',
                    'max:50',
                    Rule::unique('pho_phone_brands', 'name')->ignore($request->id)->whereNull('deleted_at')
                ],
                'active' => [
                    'nullable',

                ],
            ];

            $messages = [
                'id.in' => 'El :attribute no coincide con el registro a modificar.',
                'required' => 'El campo :attribute es requerido.',
                'integer' => 'El formato de :attribute es irreconocible.',
                'exists' => 'Ningún registro actual, coincide con :attribute enviado.',
                'string' => 'El formato de :attribute es irreconocible.',
                'max' => 'La longitud de :attribute ha excedido la cantidad máxima.',
                'boolean' => 'El formato de :attribute es diferente al esperado.',
                'name.unique' => 'El nombre ya existe!',
            ];

            $attributes = [
                'id' => 'Identificador',
                'name' => 'Nombre',
                'active' => 'Estado',
            ];

            $request->validate($rules, $messages, $attributes);

            $updateBrand = PhoneBrand::findOrFail($request->id);

            $data = [
                'name' => $request->name,
                // 'active' => $request->active == 'true' ? true : false
                'active' => $request->active === 'true' || $request->active === null ? true : false,
            ];

            $updateBrand->update($data);

            return response()->json($updateBrand, 200);
        } catch (ValidationException $e) {
            Log::error(json_encode($e->validator->errors()->getMessages()) . ' Información enviada: ' . json_encode($request->all()));

            return response()->json(['errors' => $e->errors()], 400);
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' | En línea ' . $e->getFile() . '-' . $e->getLine() . '  Información enviada: ' . json_encode($request->all()));

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {
            $validatedData = Validator::make(
                [
                    'id' => $id
                ],
                [
                    'id' => ['required', 'integer', 'exists:pho_phone_brands,id']
                ],
                [
                    'id.required' => 'Falta ingresar el :attribute.',
                    'id.integer' => 'El :attribute no es reconocible',
                    'id.exists' => 'El :attribute ingresado no se encontró.',
                ],
                [
                    'id' => 'Identificador de la Marca',
                ]
            )->validate();

            $phoneBrand = [];

            DB::transaction(function () use ($validatedData, &$phoneBrand) {
                $phoneBrand = PhoneBrand::findOrFail($validatedData['id']);
                $phoneBrand->delete();
                $phoneBrand['status'] = 'deleted';
            });

            return response()->json([$phoneBrand, 'message' => 'Marca Eliminada con exito.'], 200);
        } catch (ValidationException $e) {
            Log::error(json_encode($e->validator->errors()->getMessages()) . '. Información enviada: ' . json_encode($id));

            return response()->json(['message' => $e->validator->errors()->getMessages()], 422);
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' | ' . $e->getFile() . ' - ' . $e->getLine() . '. Información enviada: ' . json_encode($id));

            return response()->json(['message' => 'Error al borrar marca.', 'error' => $e->getMessage()], 500);
        }
    }

    public function brandsActive($id = null)
    {
        try {
            $commonQuery = PhoneBrand::where('active', true);

            if ($id != null) {
                $validatedData = validator::make(
                    [
                        'id' => $id
                    ],
                    [
                        'id' => [
                            'required',
                            'integer',
                            'exists:pho_phone_brands,id'
                        ]
                    ],
                    [
                        'id.required' => 'Falta el :attribute',
                        'id.integer' => 'El :attribute es irreconocible.',
                        'id.exists' => 'El :attribute no coincide con los registros'
                    ],
                    [
                        'id' => 'Identificador de la marca',
                    ]
                )->validate();

                $requestBrands = $commonQuery->withCount('models')->with(
                    [
                        'models' => function ($query) {
                            $query->where('active', true);
                        }
                    ]
                )->findOrFail(
                    $validatedData['id']
                );
            } else {
                $requestBrands = $commonQuery->withCount('models')->get();
            }

            return response()->json($requestBrands, 200);
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' | ' . $e->getFile() . ' - ' . $e->getLine() . '. Información enviada: ' . json_encode($id));

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
