<?php

namespace App\Http\Controllers\Phones;

use Exception;
use App\Helpers\FileHelper;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Phones\PhoneIncident;
use Illuminate\Support\Facades\File;
use App\Models\Phones\IncidentsCategory;
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
            $phoneIncidents = PhoneIncident::with(
                'phone',
                'incidentCat',
                'employee',
                'resolutions'
            )->withCount(
                'attaches'
            )->get();
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
            $categories = IncidentsCategory::where('active', true)->get();

            return response()->json([
                'categories'=>$categories
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
                'paymentDifference' => ['nullable', 'max:9999.99', 'min:0', 'decimal:0,2'],
                'percentage' => ['nullable', 'max:100', 'min:0', 'decimal:0,2'],
                'description' => ['required', 'string', 'max:250'],
                'date_incident' =>['required', 'date', 'date_format:Y-m-d'],
                'pho_phone_id' => [$request->pho_phone_id > 0 ? ['integer'] : 'nullable', Rule::exists('pho_phones', 'id')->whereNull('deleted_at')],
                'adm_employee_id' => [$request->adm_employee_id > 0 ? ['integer'] : 'nullable', Rule::exists('adm_employees', 'id')->whereNull('deleted_at')],
                'pho_phone_incident_category_id' => [$request->pho_phone_incident_category_id > 0 ? ['integer'] : 'nullable', Rule::exists('pho_phone_incident_categories', 'id')->whereNull('deleted_at')],
                'files' => ['nullable', function ($attribute, $value, $fail) {
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
                'paymentDifference.max' => ':attribute no puede ser mayor a 9999.99',
                'percentage.max' => ':attribute tiene que ser menor a 100%',
                'date' => 'El formato d:attribute es diferente al formato YY-mm-dd.',
                'min' => ':attribute tiene que ser mayor a 0',
                'integer' => 'El formato d:attribute es irreconocible.',
                'decimal' => ':attribute solo puede incluir 2 decimales',
                'exists' => ':attribute no existe.  ',
            ];

            $attributes = [
                'paymentDifference' => 'la diferencia del pago',
                'percentage' => 'el Porcentaje del Incidente',
                'date_incident' => 'la Fecha de Incidencia ',
                'description' => 'la descripcion de la Incidencia',
                'files' => 'archivo(s)',
                'adm_employee_id' => 'el Identificador del Empleado',
                'pho_phone_id' => 'el Identificador del Teléfono',
                'pho_phone_incident_category_id' => 'el Identificador de la Categoría del Incidente',
            ];



            $request->validate($rules, $messages, $attributes);

            $newRequestIncident = [];

            DB::transaction(function () use ($request, &$newRequestIncident) {
                $newRequestIncidentData = [
                    'description' => $request->description,
                    'percentage' => $request->percentage,
                    'paymentDifference' => $request->paymentDifference,
                    'date_incident' => $request->date_incident,
                    'adm_employee_id' => $request->adm_employee_id,
                    'pho_phone_id' => $request->pho_phone_id,
                    'pho_phone_incident_category_id' => $request->pho_phone_incident_category_id

                ];
                $newRequestIncident = PhoneIncident::create($newRequestIncidentData);

                if ($request->hasFile('files')) {

                    $basePath = 'Phones/Incidents/';
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
                            'name' => $newFileNameUnique,
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
                ['id' => ['required', 'integer', 'exists:pho_phone_incidents,id']],
                [
                    'id.required' => 'Falta :attribute.',
                    'id.integer' => ':attribute irreconocible.',
                    'id.exists' => ':attribute solicitado sin coincidencia.',
                ],
                ['id' => 'Identificador de Incidencia de Teléfono de Solicitud.'],
            )->validate();

            $phoneIncident = PhoneIncident::with([
                'resolutions',
                'resolutions.employee',
                'resolutions.attaches',
                'incidentCat',
                'employee',
                'phone',
                'attaches'
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
        /*   //
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
            $category = IncidentsCategory::where('active', true)->get();

            return response()->json([
                $phoneIncident,
                $phones,
                $category
            ], 200);
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' | En Línea - ' . $e->getLine());
            return response()->json(['message' => 'Ha ocurrido un error al procesar la solicitud.', 'errors' => $e->getMessage()], 500);
        } */
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {/*
        try {
            $rules = [
                'id' => ['required', 'integer', 'exists:pho_phone_incidents,id', Rule::in([$id])],
                'description' => ['required', 'string', 'max:250'],
                'paymentDifference' => ['required', 'max:9999.99', 'min:0', 'decimal:0,2'],
                'percentage' => ['required', 'max:100', 'min:0', 'decimal:0,2'],
                'date_incident' =>['required', 'date', 'date_format:Y-m-d'],
                'adm_employee_id' => [$request->adm_employee_id > 0 ? ['integer'] : 'nullable', Rule::exists('adm_employees', 'id')->whereNull('deleted_at')],
                'pho_phone_id' => [$request->pho_phone_id > 0 ? ['integer'] : 'nullable', Rule::exists('pho_phones', 'id')->whereNull('deleted_at')],
                'pho_phone_incident_category_id' => [$request->pho_phone_incident_category_id > 0 ? ['integer'] : 'nullable', Rule::exists('pho_phone_incident_categories', 'id')->whereNull('deleted_at')],
            ];

            $messages = [
                'required' => 'Falta :attribute.',
                'paymentDifference.max' => ':attribute no puede ser mayor a 9999.99',
                'percentage.max' => ':attribute no puede ser mayor a 100%',
                'date' => 'El formato d:attribute es diferente al formato YY-mm-dd.',
                'min' => ':attribute no puede ser menor a 0',
                'integer' => 'El formato d:attribute es irreconocible.',
                'decimal' => ':attribute solo puede incluir 2 decimales',
                'exists' => ':attribute no existe.  ',
            ];

            $attributes = [
                'id' => 'el Identificador de Incidente',
                'paymentDifference' => 'la diferencia del pago',
                'percentage' => 'el Porcentaje del Incidente',
                'date_incident' => 'la Fecha de Incidencia ',
                'description' => 'la descripcion de la Incidencia',
                'adm_employee_id' => 'el Identificador del Empleado',
                'pho_phone_id' => 'el Identificador del Teléfono',
                'pho_phone_incident_category_id' => 'el Identificador de la Categoría del Incidente',
            ];



            $request->validate($rules, $messages, $attributes);

            $requestIncident = [];

            DB::transaction(function () use ($request, &$requestIncident) {
                $requestIncident = PhoneIncident::findOrFail($request->id);
                $newRequestIncidentData = [
                    'description' => $request->description,
                    'percentage' => $request->percentage,
                    'paymentDifference' => $request->paymentDifference,
                    'date_incident' => $request->date_incident,
                    'adm_employee_id' => $request->adm_employee_id,
                    'pho_phone_id' => $request->pho_phone_id,
                    'pho_phone_incident_category_id' => $request->pho_phone_incident_category_id

                ];

                $requestIncident->update($newRequestIncidentData);
            });
            return response()->json($requestIncident, 200);
        } catch (ValidationException $e) {
            Log::error(json_encode($e->validator->errors()->getMessages()) . ' Información enviada: ' . json_encode($request->all()));

            return response()->json(['message' => $e->validator->errors()->getMessages()], 422);
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' | En línea ' . $e->getFile() . '-' . $e->getLine() . '  Información enviada: ' . json_encode($request->all()));

            return response()->json(['message' => $e->getMessage()], 500);
        } */
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
                ['id' => ['required', 'integer', 'exists:pho_phone_incidents,id']],
                [
                    'id.required' => 'Falta el :attribute.',
                    'id.integer' => 'El :attribute es irreconocible.',
                    'id.exists' => 'El :attribute enviado, sin coincidencia.',
                ],
                [
                    'id' => 'Identificador de Incidencia del Teléfono',
                ]
            )->validate();

            $phone = [];

            DB::transaction(function () use ($validatedData, &$phone) {
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
