<?php

namespace App\Http\Controllers\Phones;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Phones\PhoneContact;
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
            ])->withCount(['contact', 'plans'/*,'plans'*/])->get();
            return response()->json($requestContract, 200);

        } catch (Exception $e) {
            Log::error($e->getMessage() . ' | En Línea ' . $e->getFile() . '-' . $e->getLine());
            return response()->json(['message' => 'Ha ocurrido un error al procesar la solicitud.', 'errors' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $phoneContacts = PhoneContact::where('active', true)->get();
            return response()->json([
                $phoneContacts,
            ], 200);

        } catch (Exception $e) {
            Log::error($e->getMessage() . ' | En Línea ' . $e->getFile() . '-' . $e->getLine());
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
                'code' => ['required', 'string', Rule::unique('pho_phone_contracts', 'code')->whereNull('deleted_at')],'max:250',
                'start_date' => ['required', 'date', 'date_format:Y-m-d'],
                'expiry_date' => ['required', 'date', 'date_format:Y-m-d', 'after_or_equal:start_date'],
                'active' => ['nullable', 'boolean'],
                'dir_contact_id' => ['required', 'integer', 'exists:dir_contacts,id']
            ];

            $messages = [
                'required' => 'Falta :attribute.',
                'string' => 'El formato d:attribute es irreconocible.',
                'date' => 'El formato d:attribute es diferente al formato YY-mm-dd.',
                'integer' => 'El formato d:attribute es diferente al que se espera',
                'boolean' => 'El formato d:attribute es diferente al esperado',
                'after_or_equal' => 'La Fecha ingresada en :attribute es menor a la Fecha de Inicio',
                'code.unique' => ':attribute ya existe'
            ];

            $attributes = [
                'code' => 'el Codigo del Contrato',
                'start_date' => 'la Fecha de Inicio del Contrato',
                'expiry_date' => 'la Fecha de Expiracion del Contrato',
                'active' => 'el Estado del Contrato',
                'dir_contact_id' => 'el Identificador del Contacto'
            ];

            $request->validate($rules, $messages, $attributes);


            $requestContractData = [
                'code' => $request->code,
                'start_date' => $request->start_date,
                'expiry_date' => $request->expiry_date,
                'active' => $request->active == 'true' ? true : false,
                'dir_contact_id' => $request->dir_contact_id
            ];

            PhoneContract::create($requestContractData);
            return response()->json($requestContractData, 200);

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
                ['id' => ['required', 'integer', 'exists:pho_phone_contracts,id']],
                [
                 'id.required' => 'Falta :attribute.',
                 'id.integer' => ':attribute irreconocible.',
                 'id.exists' => ':attribute solicitado sin coincidencia.',
                ],
                ['id' => 'Identificador de Categoría de Solicitud.'],
            )->validate();

            $contract = PhoneContract::with([
                'plans',
                'contact',
                //'phones'
            ])->withCount(['plans'])->findOrFail($validatedData['id']);



            return response()->json($contract, 200);

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
        try {
            $validatedData = Validator::make(
                ['id' => $id],
                ['id' => ['required', 'integer', 'exists:pho_phone_contracts,id']],
                [
                 'id.required' => 'Falta :attribute.',
                 'id.integer' => ':attribute irreconocible.',
                 'id.exists' => ':attribute solicitado sin coincidencia.',
                ],
                ['id' => 'Identificador de Categoría de Solicitud.'],
            )->validate();

            $contract = PhoneContract::with([
                'plans',
                'contact',
                //'phones'
            ])->withCount(['plans'])->findOrFail($validatedData['id']);
            $phoneContacts = PhoneContact::where('active', true)->get();
            return response()->json([$contract, $phoneContacts], 200);

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
                'id' => ['required', 'integer', 'exists:pho_phone_contracts,id', Rule::in([$id])],
                'code' => ['required', 'string',Rule::unique('pho_phone_contracts','code')->ignore($request->id)->whereNull('deleted_at')],
                'start_date' => ['required', 'date', 'date_format:Y-m-d'],
                'expiry_date' => ['required', 'date', 'date_format:Y-m-d', 'after_or_equal:start_date'],
                'active' => ['nullable', 'boolean'],
                'dir_contact_id' => ['required', 'integer', 'exists:dir_contacts,id']
            ];

            $messages = [
                'id.in' => 'El ID no coincide con el registro a modificar.',
                'required' => 'Falta :attribute.',
                'string' => 'El formato d:attribute es irreconocible.',
                'date' => 'El formato d:attribute es diferente al formato YY-mm-dd.',
                'integer' => 'El formato d:attribute es diferente al que se espera',
                'boolean' => 'El formato d:attribute es diferente al esperado',
                'after_or_equal' => 'La Fecha ingresada en :attribute es menor a la Fecha de Inicio',
                'code.unique' => ':attribute ya existe',
                'exists'=> ':attribute no existe'
            ];

            $attributes = [
                'id' => 'Identificador',
                'code' => 'el Codigo del Contrato',
                'start_date' => 'la Fecha de Inicio del Contrato',
                'expiry_date' => 'la Fecha de Expiracion del Contrato',
                'active' => 'el Estado del Contrato',
                'dir_contact_id' => 'el Identificador del Contacto'
            ];

            $request->validate($rules, $messages, $attributes);



            $requestContract = PhoneContract::findOrFail($request->id);
            $requestContractData = [
                'code' => $request->code,
                'start_date' => $request->start_date,
                'expiry_date' => $request->expiry_date,
                'active' => $request->active == 'true' ? true : false,
                'dir_contact_id' => $request->dir_contact_id
            ];

            $requestContract->update($requestContractData);
            return response()->json($requestContract, 200);

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
                ['id' => ['required', 'integer', 'exists:pho_phone_contracts,id']],
                [
                 'id.required' => 'Falta el :attribute.',
                 'id.integer' => 'El :attribute es irreconocible.',
                 'id.exists' => 'El :attribute enviado, sin coincidencia.',
                ],
                ['id' => 'Identificador del Contrato de Solicitud',])->validate();

                $contract = NULL;

            DB::transaction(function () use ($validatedData, &$contract) {
                $contract = PhoneContract::findOrFail($validatedData['id']);
                $contract->delete();
                $contract['status'] = 'deleted';
            });

            return response()->json($contract, 200);
        } catch (ValidationException $e) {
            Log::error(json_encode($e->validator->errors()->getMessages()) . '. Información enviada: ' . json_encode($id));

            return response()->json(['message' => $e->validator->errors()->getMessages()], 422);
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' | ' . $e->getFile() . ' - ' . $e->getLine() . '. Información enviada: ' . json_encode($id));

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function activeContracts(int $id = null)
    {
        try {
            $commonQuery = PhoneContract::where('active', true);
            if ($id !== null) {
                $validatedData = Validator::make(
                    ['id' => $id],
                    ['id' => ['required', 'integer', 'exists:pho_phone_contracts,id']],
                    [
                     'id.required' => 'Falta el :attribute.',
                     'id.integer' => 'El :attribute es irreconocible.',
                     'id.exists' => 'El :attribute enviado, sin coincidencia.',
                    ],
                    ['id' => 'Identificador de Contrato de Solicitud',])->validate();

                $requestContracts = $commonQuery->with(['contact', 'plans'])->findOrFail($validatedData['id']);

            } else {
                $requestContracts = $commonQuery->with(['contact', 'plans'])->get();
            }
            return response()->json($requestContracts, 200);

        } catch (Exception $e) {
            Log::error($e->getMessage() . ' | ' . $e->getFile() . ' - ' . $e->getLine() . '. Información enviada: ' . json_encode($id));
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}