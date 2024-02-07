<?php

namespace App\Http\Controllers\Phones;

use Exception;
use App\Helpers\FileHelper;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\General\GralConfiguration;
use App\Models\Phones\AdminEmployee;
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
             //$employee_id = Auth::user()->employee->id;
             $employee_id = 1;

            $incident_admin = GralConfiguration::where('identifier', 'incidence_supervisor')->first();
            $incident_admin_list = explode(',', $incident_admin->value);

            $incidence_day = GralConfiguration::where('identifier', 'incidence_day')->first();
            $incidence_day = $incidence_day->value;

            if (in_array($employee_id, $incident_admin_list)) {

                $phoneAssigned = AdminEmployee::with(
                    [
                        'phones_assigned',
                    ]
                )->findOrFail($employee_id);

                $phoneIncidents = PhoneIncident::with(
                    'phone',
                    'phone',
                    'incidentCat',
                    'employee',
                    'supervisor',
                    'resolutions'
                )->withCount(
                    'attaches'
                )->get();


                $phoneIncidents = ['phones_assigned' => $phoneAssigned['phones_assigned'], 'incidence_day' => $incidence_day, 'supervisor' => true, 'incidents' => $phoneIncidents];
            } else {
                $phoneIncidents = AdminEmployee::with(
                    [
                        'phones_assigned',
                        'incidents',
                        'incidents.phone',
                        'incidents.incidentCat',
                        'incidents.employee',
                        'incidents.supervisor',
                        'incidents.resolutions',

                    ]
                )->findOrFail($employee_id);

                $phoneIncidents = ['phones_assigned' => $phoneIncidents['phones_assigned'], 'incidence_day' => $incidence_day, 'supervisor' => false, 'incidents' => $phoneIncidents['incidents']];
            }
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
            $categories = IncidentsCategory::where('active', true)->get();

            return response()->json(
                [
                    'categories' => $categories
                ],
                200
            );
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
            //$employee_id = Auth::user()->employee->id;
            $rules = [
                'description' => ['required', 'string', 'max:250'],
                'percentage' => ['nullable', 'max:100', 'min:0', 'decimal:0,2'],
                'resolution' => ['nullable', 'string', 'max:250'],
                'paymentDifference' => ['nullable', 'max:9999.99', 'min:0', 'decimal:0,2'],
                'date_incident' => ['required', 'date', 'date_format:Y-m-d'],
                'date_resolution' => ['nullable', 'date', 'date_format:Y-m-d'],
                'state' => ['nullable', 'string', 'max:250'],

                'pho_phone_id' => [
                    $request->pho_phone_id > 0 ?
                        ['integer'] : 'nullable',
                    Rule::exists(
                        'pho_phones',
                        'id'
                    )->whereNull('deleted_at')
                ],
                'pho_phone_incident_category_id' => [
                    $request->pho_phone_incident_category_id > 0 ?
                        ['integer'] : 'nullable',
                    Rule::exists(
                        'pho_phone_incident_categories',
                        'id'
                    )->whereNull('deleted_at')
                ],
                'files' => [
                    'nullable',
                    'filled',
                ],
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
                'date_resolution' => 'la Fecha de Resolucion',
                'description' => 'la descripcion de la Incidencia',
                'files' => 'archivo(s)',
                'resolution' => 'la Resolucion Final',
                'pho_phone_id' => 'el Identificador del Teléfono',
                'pho_phone_incident_category_id' => 'el Identificador de la Categoría del Incidente',
            ];

            $request->validate($rules, $messages, $attributes);
            $newRequestIncident = [];

            DB::transaction(function () use ($request, &$newRequestIncident) {
                $newRequestIncidentData = [
                    'description' => $request->description,
                    'percentage' => $request->percentage,
                    'resolution' => /* 'Sin Resolucion', */ $request->resolution,
                    'paymentDifference' => 0,
                    'date_incident' => $request->date_incident,
                    'date_resolution' =>/* '2024-02-02', */$request->date_resolution,
                    'adm_employee_id' => 2, //$employee_id = Auth::user()->employee->id,
                    'pho_phone_supervisor_id'=>1,
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

                        $newRequestIncident->attaches()->create(
                            [
                                'file_name_original' => $file->getClientOriginalName(),
                                'name' => $newFileNameUnique,
                                'file_size' => $fileSize,
                                'file_extension' => $file->getClientOriginalExtension(),
                                'file_mimetype' => $file->getClientMimetype(),
                                'file_location' => $basePath,
                            ]
                        );
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

        $employee_id = 1;

        $incident_admin = GralConfiguration::where('identifier','incidence_supervisor')->first();
        $incident_admin_list = explode(',',$incident_admin->value);

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

            $phoneIncident = PhoneIncident::with(
                [
                    'attaches',
                    'phone',
                    'phone.type',
                    'phone.employee',
                    'phone.plan',
                    'phone.contract',
                    'phone.contract.percentages',
                    'phone.contract.attaches',
                    'phone.model',
                    'phone.model.brand',
                    'incidentCat',
                    'employee',
                    'supervisor',
                    'resolutions',
                    'resolutions.employee'
                ]
            )->findOrFail($validatedData['id']);

            $incidentNumber = PhoneIncident::where('pho_phone_id', $phoneIncident->pho_phone_id)
                ->where('adm_employee_id', $phoneIncident->adm_employee_id)
                ->where('id', '<', $phoneIncident->id)
                ->count() + 1;

        if(in_array($employee_id,$incident_admin_list)){
            $response = [

                'supervisor' => true,
                'incidents' => $phoneIncident,
                'incident_number' => $incidentNumber
            ];} else {
                $response = [

                    'supervisor' => false,
                    'incidents' => $phoneIncident,
                    'incident_number' => $incidentNumber
                ];
            }

            return response()->json($response, 200);
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
                'id' => ['required', 'integer', 'exists:pho_phone_incidents,id', Rule::in([$id])],
                'description' => ['required', 'string', 'max:250'],
                'state' => ['nullable', 'string', 'max:250'],
                'resolution' => ['nullable', 'string', 'max:250'],
                'paymentDifference' => ['required', 'max:9999.99', 'min:0', 'decimal:0,2'],
                'percentage' => ['required', 'max:100', 'min:0', 'decimal:0,2'],
                'date_incident' => ['required', 'date', 'date_format:Y-m-d'],
                'date_resolution' => ['required', 'date', 'date_format:Y-m-d'],
                'pho_phone_id' => [
                    $request->pho_phone_id > 0 ?
                        ['integer'] : 'nullable',
                    Rule::exists(
                        'pho_phones',
                        'id'
                    )->whereNull('deleted_at')
                ],
                'pho_phone_incident_category_id' => [
                    $request->pho_phone_incident_category_id > 0 ?
                        ['integer'] : 'nullable',
                    Rule::exists(
                        'pho_phone_incident_categories',
                        'id'
                    )->whereNull('deleted_at')
                ],
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
                'state' => 'el estado del Incidente',
                'percentage' => 'el Porcentaje del Incidente',
                'date_incident' => 'la Fecha de Incidencia ',
                'date_resolution' => 'la Fecha de resolucion ',
                'resolution' => 'la Resolucion Final',
                'description' => 'la descripcion de la Incidencia',
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
                    'state' => $request->state,
                    'resolution' => $request->resolution,
                    'paymentDifference' => $request->paymentDifference,
                    'date_incident' => $request->date_incident,
                    'date_resolution' => $request->date_resolution,
                    'adm_employee_id' => 2, //$employee_id = Auth::user()->employee->id,
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
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
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

            DB::transaction(
                function () use ($validatedData, &$phone) {
                    $phone = PhoneIncident::findOrFail($validatedData['id']);
                    $phone->delete();
                    $phone['status'] = 'deleted';
                }
            );

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
