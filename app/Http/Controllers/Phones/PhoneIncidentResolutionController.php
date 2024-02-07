<?php

namespace App\Http\Controllers\Phones;

use Exception;
use App\Helpers\FileHelper;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use App\Models\Phones\IncidentsResolutions;
use Illuminate\Validation\ValidationException;

class PhoneIncidentResolutionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
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
                'title' => ['required', 'max:255'],
                'reply' => ['required', 'max:255'],
                'date_response' => ['required'],
                'pho_phone_incident_id' => [
                    $request->pho_phone_incident_id > 0 ?
                        ['integer'] : 'nullable',
                    Rule::exists(
                        'pho_phone_incidents',
                        'id'
                    )->whereNull('deleted_at')
                ],
                'files' => [
                    'nullable',
                    'filled',
                ],
            ];

            $messages = [
                'max' => ':attribute no pued ser mayor a 255 caracteres',
                'required' => 'Falta :attribute.',
                'integer' => 'El formato d:attribute es irreconocible.',
                'exists' => ':attribute no existe.  ',
            ];

            $attributes = [
                'title' => 'el titulo de la respuesta',
                'reply' => 'la respuesta',
                'files' => 'archivo(s)',
                'pho_phone_incident_id' => 'el Identificador del Incidente',
            ];

            $request->validate($rules, $messages, $attributes);

            $newRequestIncidentResolutions = [];

            DB::transaction(function () use ($request, &$newRequestIncidentResolutions) {
                $newRequestIncidentResolutionsData = [
                    'title' => $request->title,
                    'reply' => $request->reply,
                    'date_response' => $request->date_response,
                    'adm_employee_id' => 1, //$employee_id = Auth::user()->employee->id,
                    'pho_phone_incident_id' => $request->pho_phone_incident_id,

                ];
                $newRequestIncidentResolutions = IncidentsResolutions::create($newRequestIncidentResolutionsData);

                if ($request->hasFile('files')) {

                    $basePath = 'Phones/Incidents/';
                    $fullPath = storage_path('app/public/' . $basePath);

                    if (!File::exists($fullPath)) {
                        File::makeDirectory($fullPath, 0775, true);
                    }

                    foreach ($request->file('files') as $idx => $file) {

                        $newFileName = $newRequestIncidentResolutions->id . '-' . $file->getClientOriginalName();
                        $newFileNameUnique = FileHelper::FileNameUnique($fullPath, $newFileName);
                        $file->move($fullPath, $newFileNameUnique);
                        $fileSize = File::size($fullPath . $newFileNameUnique);

                        $newRequestIncidentResolutions->attaches()->create(
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
                    $newRequestIncidentResolutions->load('attaches');
                }
            });

            return response()->json($newRequestIncidentResolutions, 200);
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
        try {
            $validatedData = Validator::make(
                ['id' => $id],
                ['id' => ['required', 'integer', 'exists:pho_phone_resolutions,id']],
                [
                    'id.required' => 'Falta :attribute.',
                    'id.integer' => ':attribute irreconocible.',
                    'id.exists' => ':attribute solicitado sin coincidencia.',
                ],
                ['id' => 'Identificador de Resolucion'],
            )->validate();

            $phoneIncidentResolutions = IncidentsResolutions::with([
                'employee',
                'attaches'
            ])->findOrFail($validatedData['id']);

            return response()->json($phoneIncidentResolutions, 200);
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

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        
    }
}
