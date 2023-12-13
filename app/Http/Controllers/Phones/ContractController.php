<?php

namespace App\Http\Controllers\Phones;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Phones\PhoneContract;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $requestContract = PhoneContract::with([
                'contact',
                'plans',
                //'phones'
                ])->withCount(['contact','plans'/*,'plans'*/])
                ->get();
                return response()->json($requestContract, 200);
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
                'code' => ['required','string'],
                'start_date' => ['required','date'],
                'expiry_date' => ['required','date'],
                'active' => ['nullable','boolean'],
                'dir_contact_id' => ['required','integer']
            ];

            $messages = [
                'required' => 'Falta :attribute.',
                'string' => 'El formato d:attribute es irreconocible.',
                'date' => 'El formato d:attribute es irreconocible.',
                'integer' => 'El formato d:attribute es irreconocible.',
                'boolean' => 'El formato d:attribute es irreconocible.'
            ];

            $attributes = [
                'code' => 'el Codigo del Contrato',
                'start_date' => 'la Fecha de Inicio del Contrato',
                'expiry_date' => 'la Fecha de Expiracion del Contrato',
                'active' => 'el Estado del Contrato',
                'dir_contact_id' => 'el Identificador del Contacto'
            ];

            $request->validate($rules, $messages, $attributes);

            $newRequestCategory = [];


/*
            DB::transaction(function () use ($request, &$newRequestCategory) {
                $newRequestCategoryData = [
                    'req_request_type_id' => $request->req_request_type_id,
                    'adm_organizational_unit_id' => $request->adm_organizational_unit_id,
                    'name' => $request->name,
                    'description' => $request->description,
                    'with_date' => $request->with_date == 'true' ? true : false,
                    'anonymous' => $request->anonymous == 'true' ? true : false,
                ];

                $newRequestCategory = RequestCategory::create($newRequestCategoryData);

                if ($request->hasFile('files')) {
                    $basePath = 'requestFiles/categoryAttaches/';
                    $fullPath = storage_path('app/public/' . $basePath);

                    if (!File::exists($fullPath)) {
                        File::makeDirectory($fullPath, 0775, true);
                    }

                    foreach ($request->file('files') as $idx => $file) {
                        $newFileName = $newRequestCategory->id . '-' . $file->getClientOriginalName();
                        $newFileNameUnique = FileHelper::fileNameUnique($fullPath, $newFileName);
                        $file->move($fullPath, $newFileNameUnique);
                        $fileSize = File::size($fullPath . $newFileNameUnique);

                        $newRequestCategory->attaches()->create([
                            'file_name_original' => $file->getClientOriginalName(),
                            'file_name' => $newFileNameUnique,
                            'file_size' => $fileSize,
                            'file_extension' => $file->getClientOriginalExtension(),
                            'file_mimetype' => $file->getClientMimetype(),
                            'file_location' => $basePath,
                        ]);
                    }
                }

                $newRequestCategory->employees()->attach($request->adm_employee_ids);

                $newRequestCategory->load(['requestType', 'organizationalUnit', 'attaches', 'employees:id,name,lastname,email']);
            });

            return response()->json($newRequestCategory, 200);
        */ } catch (ValidationException $e) {
            Log::error(json_encode($e->validator->errors()->getMessages()) .' Información enviada: ' . json_encode($request->all()));

            return response()->json(['message' => $e->validator->errors()->getMessages()], 422);
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' | En línea ' . $e->getFile() . '-' . $e->getLine() . '  Información enviada: ' . json_encode($request->all()));

            return response()->json(['message' => $e->getMessage()], 500);
        }



/*

        $validation = Validator::make($request->all(),[
            'code' => 'required|string',
            'start_date' => 'required|date',
            'expiry_date' => 'required|date',
            'active' => 'required|boolean',
            'dir_contact_id' => 'required|integer'
        ]);

        if($validation->fails()){
            return response()->json([
                'code' => 400,
                'date' => $validation->messages()
            ], 400);
        } else {
           PhoneContract::create($request->all());
            return response()->json([
                'code' => 200,
                'data' => 'Contract OK'
            ],200);
        }*/
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $validation = (gettype($id) === "integer") ? true : false;

        if (!$validation) {
            return response()->json([
                'code' => 400,
                'data' => 'Incorrect identifier: ' . gettype($id)
            ], 400);
        } else {

            $phone = PhoneContract::find($id);
            if ($phone) {
                return response()->json([
                    'code' => 200,
                    'data' => $phone
                ], 200);
            } else {
                return response()->json([
                    'code' => 404,
                    'data' => 'Contract not found'
                ], 404);
            }
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
        $validation = Validator::make($request->all(),[
            'code' => 'required|string',
            'start_date' => 'required|date',
            'expiry_date' => 'required|date',
            'active' => 'required|boolean',
            'dir_contact_id' => 'required|integer'
        ]);

        if($validation->fails()){
            return response()->json([
                'code' => 400,
                'date' => $validation->messages()
            ], 400);
        } else {
           $contract = PhoneContract::find($id);
            if($contract){
                $contract->update([
                    'code' => $request->code,
                    'start_date' => $request->start_date,
                    'expiry_date' => $request->expiry_date,
                    'active' => $request->active,
                    'dir_contact_id' => $request->dir_contact_id
                ]);
                return response()->json([
                    'code' => 200,
                    'data' => $contract
                ],200);
            } else {
                return response()->json([
                    'code' => 400,
                    'data' => 'Contract ERROR, bad request'
                ],400);
            };


        }
    }

    /**
     * Remove the specified resource from storage.
     */
        public function destroy(int $id)
    {
        $validation = (gettype($id) === "integer") ? true : false;

        if (!$validation) {
            return response()->json([
                'code' => 400,
                'data' => 'Incorrect identifier: ' . gettype($id)
            ], 400);
        } else {

            $phone = PhoneContract::find($id);
            if ($phone) {
                $phone->delete();
                return response()->json([
                    'code' => 200,
                    'data' => 'Contract removed'
                ], 200);
            } else {
                return response()->json([
                    'code' => 404,
                    'data' => 'Contract not found'
                ], 404);
            }
        }
    }
    }
