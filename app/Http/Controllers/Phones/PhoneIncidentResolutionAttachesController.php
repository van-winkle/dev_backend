<?php

namespace App\Http\Controllers\Phones;

use Exception;
use App\Helpers\FileHelper;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;
use App\Models\Phones\IncidentsResolutionsAttaches;

class PhoneIncidentResolutionAttachesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
                'pho_phone_resolution_id' =>
                [
                    $request->pho_phone_resolution_id > 0 ?
                        ['integer'] : 'nullable',
                    Rule::exists(
                        'pho_phone_resolutions',
                        'id'
                    )->whereNull('deleted_at')
                ],
                'files' => ['required', 'filled', function ($attribute, $value, $fail) {
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
                'integer' => 'El formato d:attribute es irreconocible.',
                'exists' => ':attribute no existe.  ',
            ];

            $attributes = [

                'files' => 'archivo(s)',
                'pho_phone_resolution_id' => 'el Identificador de la Categoría del Incidente',
            ];

            $request->validate($rules, $messages, $attributes);

            $newRequestIncidentResolutions = [];

            if ($request->hasFile('files')) {

                $basePath = 'phones/incidents/resolutions';
                $fullPath = storage_path('app/public/' . $basePath);

                if (!File::exists($fullPath)) {
                    File::makeDirectory($fullPath, 0775, true);
                }

                foreach ($request->file('files') as $idx => $file) {

                    $newFileName = $request->pho_phone_incident_id . '-' . $file->getClientOriginalName();

                    $newFileNameUnique = FileHelper::FileNameUnique($fullPath, $newFileName);

                    $file->move($fullPath, $newFileNameUnique);

                    $fileSize = File::size($fullPath . $newFileNameUnique);

                    $newRequestIncidentResolutions = IncidentsResolutionsAttaches::create(
                        [
                            'pho_phone_resolution_id' => $request->pho_phone_resolution_id,
                            'file_name_original' => $file->getClientOriginalName(),
                            'name' => $newFileNameUnique,
                            'file_size' => $fileSize,
                            'file_extension' => $file->getClientOriginalExtension(),
                            'file_mimetype' => $file->getClientMimetype(),
                            'file_location' => $basePath,
                        ]
                    );
                }
            };

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
        //
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
