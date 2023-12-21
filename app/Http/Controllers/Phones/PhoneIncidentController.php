<?php

namespace App\Http\Controllers\Phones;

use Exception;
use App\Helpers\FileHelper;
use App\Models\Phones\Phone;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Phones\PhoneIncident;
use Illuminate\Support\Facades\File;
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
            $phoneIncidents = PhoneIncident::all();
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
        try {
            $rules = [
                'paymentDifference' => ['required','max:9999.99','decimal:0,2'],
                'percentage' => ['required', 'max:9999.99', 'min:0','decimal:0,2'],
                'pho_phone_id' => [ $request->pho_phone_id > 0 ? ['integer'] : 'nullable' , Rule::exists('pho_phones','id')->whereNull('deleted_at')],
                'pho_phone_incident_category_id' => [ $request->pho_phone_incident_category_id > 0 ? ['integer'] : 'nullable' , Rule::exists('pho_phone_incident_categories','id')->whereNull('deleted_at')],
                'files' => ['nullable', 'filled', function ($attribute, $value, $fail) {
                    $maxTotalSize = 300 * 1024 * 1024;
                    $totalSize = 0;

                    foreach ($value as $idx => $file) {
                        $totalSize += $file->getSize();
                    }

                    if ($totalSize > $maxTotalSize) {
                        $fail('La suma total del tamaño de los archivos no debe exceder los ' . $maxTotalSize / 1024 / 1024 . 'MB.');
                    }
                }],
            ];

            $messages = [
                'required' => 'Falta :attribute.',
                'string' => 'El formato d:attribute es irreconocible.',

                'boolean' => 'El formato de :attribute es diferente al esperado',

                'numeric' => 'El formato d:attribute debe ser numérico.',
                'between' => 'El formato d:attribute debe ser entre 0 y 100.',
                'integer' => 'El formato d:attribute es irreconocible.',
                'exists' => ':attribute no existe.  ',
            ];

            $attributes = [
                'percentage' => 'el Porcentaje del Incidente',
                'active' => 'el Estado del Incidente',
                'files' => 'archivo(s)',
                'pho_phone_id' => 'el Identificador del Teléfono',
            ];



            $request->validate($rules, $messages, $attributes);

            $newRequestIncident = [];

            DB::transaction(function () use ($request, &$newRequestIncident) {
                $newRequestIncidentData = [
                    'percentage' => $request->percentage,
                    'active' => $request->active == 'true' ? true : false,
                    'pho_phone_id' => $request->pho_phone_id,
                    'pho_phone_incident_category_id'=>$request->pho_phone_incident_category_id

                ];
                $newRequestIncident = PhoneIncident::create($newRequestIncidentData);

                if ($request->hasFile('files')) {

                    $basePath = 'phones/incidents/';
                    $fullPath = storage_path('app/public/' . $basePath);

                    if (!File::exists($fullPath)) {
                        File::makeDirectory($fullPath, 0775, true);
                    }

                    foreach ($request->file('files') as $idx => $file) {

                        $newFileName = $newRequestIncident->id . '-' . $file->getClientOriginalName();
                        $newFileNameUnique = FileHelper::FileNameUnique($fullPath, $newFileName);
                        $file->move($fullPath, $newFileNameUnique);
                        $fileSize = File::size($fullPath . $newFileNameUnique);

                        $newRequestIncident->attaches()->create([
                            'file_name_original' => $file->getClientOriginalName(),
                            'file_name' => $newFileNameUnique,
                            'file_size' => $fileSize,
                            'file_extension' => $file->getClientOriginalExtension(),
                            'file_mimetype' => $file->getClientMimetype(),
                            'file_location' => $basePath,
                        ]);
                    }
                    $newRequestIncident->load('attaches');
                }

            });

            return response()->json($newRequestIncident, 200);
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
