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
use App\Models\Phones\ContractAttaches;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


class PhoneContractAttachesController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        try {
            $contractAttaches = ContractAttaches::with('contract')->get();

            return response()->json(['attaches' => $contractAttaches], 200);
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
        try {
            $rules = [
                'pho_phone_contract_id' => [
                    $request->pho_phone_contract_id > 0 ?
                        ['integer'] : 'nullable',
                    Rule::exists(
                        'pho_phone_contracts',
                        'id'
                    )->whereNull('deleted_at')
                ],
                'files' => [
                    'required', 'filled', function ($attribute, $value, $fail) {
                        $maxTotalSize = 300 * 1024 * 1024;
                        $totalSize = 0;

                        foreach ($value as $idx => $file) {
                            $totalSize += $file->getSize();
                        }

                        if ($totalSize > $maxTotalSize) {
                            $fail('La suma total del tamaño de los archivos no debe exceder los ' . $maxTotalSize / 1024 / 1024 . 'MB.');
                        }
                    }
                ],
            ];

            $messages = [
                'required' => 'Falta :attribute.',
                'integer' => 'El formato d:attribute es irreconocible.',
                'exists' => ':attribute no existe.  ',
            ];

            $attributes = [

                'files' => 'archivo(s)',
                'pho_phone_contract_id' => 'el Identificador de la Categoría del Incidente',
            ];

            $request->validate($rules, $messages, $attributes);

            if ($request->hasFile('files')) {

                $basePath = 'phones/Contracts/';

                $fullPath = storage_path('app/public/' . $basePath);

                if (!File::exists($fullPath)) {
                    File::makeDirectory($fullPath, 0775, true);
                }

                foreach ($request->file('files') as $idx => $file) {

                    $newFileName = $request->pho_phone_contract_id . '-' . $file->getClientOriginalName();

                    $newFileNameUnique = FileHelper::FileNameUnique($fullPath, $newFileName);

                    $file->move($fullPath, $newFileNameUnique);

                    $fileSize = File::size($fullPath . $newFileNameUnique);

                    $newRequestIncident = ContractAttaches::create(
                        [
                            'pho_phone_contract_id' => $request->pho_phone_contract_id,
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
        return FileHelper::downloadFile(ContractAttaches::class, $id);
    }

    public function viewFile(int $id)
    {
        return FileHelper::viewFile(ContractAttaches::class, $id);
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
    public function destroy(int $id)
    {
        try {
            $validateData = Validator::make(
                ['id' => $id],
                ['id' => ['required', 'integer', 'exists:pho_phone_contract_attaches,id']],
                [
                    'id.required' => 'Falta el :attribute.',
                    'id.integer' => 'El :attribute es irreconocible.',
                    'id.exists' => 'El :attribute enviado, sin coincidencia.',
                ],
                ['id' => 'Identificador de archivo no reconocido.']
            )->validate();

            $attaches = [];

            DB::transaction(
                function () use ($validateData, &$attaches) {
                    $attaches = ContractAttaches::findOrFail($validateData['id']);
                    $attaches->delete();
                    $attaches['status'] = 'deleted';
                }
            );

            return response()->json([$attaches], 200);
        } catch (ValidationException $e) {
            Log::error(json_encode($e->validator->errors()->getMessages()) . '. Información enviada: ' . json_encode(['id' => $id]));

            return response()->json(['message' => 'Archivo no encontrado.'], 404);
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' | ' . $e->getFile() . ' - ' . $e->getLine() . '. Información enviada: ' . json_encode(['id' => $id]));

            return response()->json(['message' => 'Ha ocurrido un error al procesar la solicitud.', 'errors' => $e->getMessage()], 500);
        }
    }
}
