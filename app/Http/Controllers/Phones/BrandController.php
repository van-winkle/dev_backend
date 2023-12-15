<?php

namespace App\Http\Controllers\Phones;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Phones\PhoneBrand;
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
            $phoneBrands = PhoneBrand::with([
                'models'
                // 'models:name,active,pho_phone_brand_id'
            ])
                ->withCount('models')
                ->get();

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
                    'boolean'
                ],
            ];

            $messages = [
                'required' => 'El campo :attribute es requerido',
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

            $requestBrandData = [
                'name' => $request->name,
                'active' => $request->active == 'true' ? true : false
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
                    'id.exists' => ':attribute solicitado sin coincidencia.',
                ],

                [
                    'id' => 'Identificador de Marca de Teléfono.'
                ]
            )->validate();

            $phoneBrand = PhoneBrand::with(['models'])->findOrFail($validatedData['id']);

            return response()->json($phoneBrand, 200);
        } catch (ValidationException $e) {

            return response()->json(['errors' => $e->errors()], 400);
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
                'id' => ['required', 'integer', 'exists:pho_phone_brands,id', Rule::in([$id])],
                'name' => ['required', 'string', 'max:50'],
                'active' => ['nullable', 'boolean'],
            ];

            $messages = [
                'id.in' => 'El ID no coincide con el registro a modificar.',
                'required' => 'El campo :attribute es requerido.',
                'integer' => 'El formato de :attribute es irreconocible.',
                'exists' => 'Ningún registro actual, coincide con :attribute enviado.',
                'string' => 'El formato de :attribute es irreconocible.',
                'max' => 'La longitud de :attribute ha excedido la cantidad máxima.',
                'boolean' => 'El formato de :attribute es diferente al esperado.',
            ];

            $attributes = [
                'id' => 'Identificador',
                'name' => 'Nombre',
                'active' => 'Estado',
            ];

            $request->validate($rules, $messages, $attributes);

            // $this->validate($request, [  // VERSION DOCUMENTACION
            //     'id' => ['required', 'integer', 'exists:pho_phone_brands,id', Rule::in([$id])],
            //     'name' => ['required', 'string', 'max:50'],
            //     'active' => ['nullable', 'boolean'],
            // ], [
            //     'id.in' => 'El ID de la URL no coincide con el ID a editar.',
            //     'required' => 'El campo :attribute es requerido.',
            //     'integer' => 'El formato de :attribute es irreconocible.',
            //     'exists' => 'Ningún registro actual, coincide con :attribute enviado.',
            //     'string' => 'El formato de :attribute es irreconocible.',
            //     'max' => 'La longitud de :attribute ha excedido la cantidad máxima.',
            //     'boolean' => 'El formato de :attribute es diferente al esperado.',
            // ]);

            $updateBrand = PhoneBrand::findOrFail($request->id);

            $data = [
                'name' => $request->name,
                'active' => $request->active == 'true' ? true : false
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

        $phoneBrand = PhoneBrand::find($id);

        if (!$phoneBrand) {

            return response()->json(['message' => 'brand notfound '], 404);
        }

        $existingBrand = PhoneBrand::where('name', $request->input('name'))
            ->where('id', '!=', $id)
            ->where('active', true)
            ->first();

        if ($existingBrand) {


            return response()->json(['message' => 'Name already exists'], 400);
        }

        $phoneBrand->name = $request->input('name');
        $phoneBrand->active = $request->input('active', true);
        $phoneBrand->save();

        return response()->json($phoneBrand);
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
                    'id.exist' => 'El :attribute ingresado no coincide',
                ],

                [
                    'id' => 'Identificador de la Marca',
                ]

            )->validate();
            ///////////////////////
            $phoneBrand = PhoneBrand::findOrFail($validatedData['id']);

            // if (!$phoneBrand) {
            //     return response()->json(['message' => 'Brand not found'], 404);
            // }

            $phoneBrand->delete();

            $phoneBrand['status'] = 'deleted';

            return response()->json([$phoneBrand, 'message' => 'Marca Eliminada con exito.'], 200);
        } catch (ValidationException $e) {

            Log::error(json_encode($e->validator->errors()->getMessages()) . '. Información enviada: ' . json_encode($id));

            return response()->json(['message' => $e->validator->errors()->getMessages()], 422);
        } catch (Exception $e) {

            Log::error($e->getMessage() . ' | ' . $e->getFile() . ' - ' . $e->getLine() . '. Información enviada: ' . json_encode($id));

            return response()->json(['message' => 'Error al borrar marca.', 'error' => $e->getMessage()], 500);
        }
    }

    public function BrandsActive($id = null)
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
                            'exist:pho_phone_brands,id'
                        ]
                    ],

                    [
                        'id.required' => 'Falta el :attribute',
                        'id.integer' => 'El :attribute es irreconocible.',
                        'id.exist' => 'El :attribute no coincide con los registros'
                    ],

                    [
                        'id' => 'Identificador de la marca',
                    ]
                )->validate();

                $requestBrands = $commonQuery->with(
                    [
                        'models'
                    ]
                )->findOrFail(
                    $validatedData['id']
                );
            } else {
                $requestBrands = $commonQuery->with(
                    [
                        'models'
                    ]
                )->get();
            }

            return response()->json($requestBrands, 200);
        } catch (Exception $e) {

            Log::error($e->getMessage() . ' | ' . $e->getFile() . ' - ' . $e->getLine() . '. Información enviada: ' . json_encode($id));

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
