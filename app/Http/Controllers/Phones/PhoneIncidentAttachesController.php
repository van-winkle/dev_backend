<?php

namespace App\Http\Controllers\Phones;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Phones\IncidentsAttaches;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PhoneIncidentAttachesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            $incidentAttaches = IncidentsAttaches::with('incident')->withCount(['attaches'])->get();
            return response()->json($incidentAttaches, 200);
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try {
            $validateData = Validator::make(
                ['id' => $id],
                ['id' => [
                    'required',
                    'integer',
                    'exists:pho_phone_incident_attaches,pho_phone_incident_id'
                    ]
                ],
                [
                    'id.required' => 'Falta el :attribute',
                    'id.integer' => ':attribute irreconocible.',
                    'id.exists' => ':attribute solicitado sin coincidencia.',
                ],
                ['id' => 'Identificador de los archivos por incidencia']

            )->validate();

            $incidentAttaches = IncidentsAttaches::with([
                'incident',
            ])->findOrFail($validateData['id']);
            return response()->json($incidentAttaches, 200);
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
    public function update(Request $request, int $id)
    {
        // try {

        //     $rules = [
        //         'id'=>['required', 'integer', 'exists:pho_phone_incident_attaches,id', Rule::in([$id])],
        //         'file_name_original' => ['required', 'string', 'max:255'],
        //         'file_name' => ['required', 'string', 'max:255'],
        //         'file_size' => ['required', 'numeric'],
        //         'file_extension' => ['required', 'string', 'max:10'],
        //         'file_mimetype' => ['required', 'string', 'max:255'],
        //         'file_location' => ['required', 'string', 'max:255'],

        //     ];
        //     $messages = [
        //         'required' => 'El campo :attribute es obligatorio.',
        //         'string' => 'El campo :attribute debe ser una cadena de texto.',
        //         'numeric' => 'El campo :attribute debe ser numérico.',
        //         'max' => 'El campo :attribute no debe exceder :max caracteres.',
        //     ];
        //     $attributes = [
        //         'file_name_original' => 'Nombre Original',
        //         'file_name' => 'Nombre',
        //         'file_size' => 'Tamaño',
        //         'file_extension' => 'Extensión',
        //         'file_mimetype' => 'Tipo MIME',
        //         'file_location' => 'Ubicación',
        //     ];

        //     $validatedData = $request->validate($rules, $messages, $attributes);



        //     $attachment = IncidentsAttaches::findOrFail($id);

        //     return response()->json($attachment, 200);
        // } catch (ValidationException $e) {
        //     Log::error(json_encode($e->validator->errors()->getMessages()) .' Información enviada: ' . json_encode($request->all()));

        //     return response()->json(['message' => $e->validator->errors()->getMessages()], 422);
        // } catch (Exception $e) {
        //     Log::error($e->getMessage() . ' | En línea ' . $e->getFile() . '-' . $e->getLine() . '  Información enviada: ' . json_encode($request->all()));

        //     return response()->json(['message' => $e->getMessage()], 500);
        // }
    }

    /**
     * Remove the specified resource from storage.
     */

     public function destroy(int $id)
{
    try {
        $validateData = Validator::make(
            ['id' => $id],
            ['id' => ['required', 'integer', 'exists:pho_phone_incident_attaches,id']],
            [
                'id.required' => 'Falta el :attribute.',
                'id.integer' => 'El :attribute es irreconocible.',
                'id.exists' => 'El :attribute enviado, sin coincidencia.',
            ],
            ['id' => 'Identificador de archivo no reconocido.']
        )->validate();

        $attaches = [];
            DB::transaction(function () use ($validateData, &$attaches) {
                $attaches = IncidentsAttaches::findOrFail($validateData['id']);
                $attaches->delete();
                $attaches['status'] = 'deleted';
            });


        return response()->json([$attaches], 200);
    } catch (ValidationException $e) {
        Log::error(json_encode($e->validator->errors()->getMessages()) . '. Información enviada: ' . json_encode(['id' => $id]));

        return response()->json(['message' => 'Archivo no encontrado.'], 404);
    } catch (Exception $e) {
        Log::error($e->getMessage() . ' | ' . $e->getFile() . ' - ' . $e->getLine() . '. Información enviada: ' . json_encode(['id' => $id]));

        return response()->json(['message' => 'Ha ocurrido un error al procesar la solicitud.', 'errors' => $e->getMessage()], 500);
    }
}


    // public function destroy(int $incidentId, int $attachId)
    // {
    //     try {
    //         $validateData = Validator::make(
    //             ['incidentId' => $incidentId, 'attachId' => $attachId],
    //             [
    //                 'incidentId' => [
    //                     'required', 'integer', 'exists:pho_phone_incident_attaches,pho_phone_incident_id'
    //                 ],
    //                 'attachId' => [
    //                     'required', 'integer', 'exists:pho_phone_incident_attaches,id'
    //                 ],
    //             ],
    //             [
    //                 'incidentId.required' => 'Falta el :attribute.',
    //                 'incidentId.integer' => 'El :attribute es irreconocible.',
    //                 'incidentId.exists' => 'El :attribute enviado, sin coincidencia.',

    //                 'attachId.required' => 'Falta el :attribute.',
    //                 'attachId.integer' => 'El :attribute es irreconocible.',
    //                 'attachId.exists' => 'El :attribute enviado, sin coincidencia.',
    //             ],
    //             [
    //                 'incidentId' => 'Identificador de incidencia no reconocido.',
    //                 'attachId' => 'Identificador de archivo no reconocido.',
    //             ]
    //         )->validate();

    //         $incidentAttaches = IncidentsAttaches::where('pho_phone_incident_id', $validateData['incidentId'])
    //             ->findOrFail($validateData['attachId']);

    //         $incidentAttaches->delete();
    //         $incidentAttaches['Status'] = 'deleted';



    //         return response()->json([$incidentAttaches, 'message' => 'Archivo eliminado exitosamente.'], 200);
    //     } catch (ValidationException $e) {
    //         Log::error(json_encode($e->validator->errors()->getMessages()) . '. Información enviada: ' . json_encode($incidentId, $attachId));

    //         return response()->json(['message' => 'Archivo no encontrado.'], 404);
    //     } catch (Exception $e) {
    //         Log::error($e->getMessage() . ' | ' . $e->getFile() . ' - ' . $e->getLine() . '. Información enviada: ' . json_encode($incidentId, $attachId));

    //         return response()->json(['message' => 'Ha ocurrido un error al procesar la solicitud.', 'errors' => $e->getMessage()], 500);
    //     }
    // }
}
