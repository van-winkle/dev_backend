<?php

namespace App\Http\Controllers\Phones;

use Exception;
use App\Models\Phones\Phone;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\General\GralConfiguration;
use App\Models\Phones\AdminEmployee;
// use Illuminate\Support\Facades\Auth;   //<-TODO GET EMPLOYEES FROM THIS
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PhoneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            //Get the employee id from Auth
            //$employee_id = Auth::user()->employee->id;
            $employee_id = 1;

            //Phone admins
            $phone_admin = GralConfiguration::where('identifier', 'phone_admin')->first();
            $phone_admin_list = explode(',', $phone_admin->value);
            //Phone supervisors
            $phone_supervisors = GralConfiguration::where('identifier', 'phone_supervisor')->first();
            $phone_supervisors_list = explode(',', $phone_supervisors->value);

            $permissions = [];

            if (in_array($employee_id, $phone_admin_list)) {
                # All phones
                $phones = Phone::with([
                    'employee.phones_assigned',
                    'employee.phones_for_assignation',
                    'plan',
                    'model.brand',
                    'type',
                    'phone_supervisors'

                ])->withCount(['incidents'])->get();

                $permissions["admin"] = true;
                $permissions["supervisor"] = true;
            } elseif (in_array($employee_id, $phone_supervisors_list)) {
                # Only assigned phones
                $employee = AdminEmployee::with(
                    [
                        'phones_for_assignation',
                        'phones_for_assignation.employee',
                        'phones_for_assignation.plan',
                        'phones_for_assignation.model.brand',
                        'phones_for_assignation.type',
                    ]
                )->withCount(
                    [
                        'phones_for_assignation'
                    ]
                )->findOrFail($employee_id);

                if ($employee->phones_for_assignation()->exists()) {
                    $phones = $employee['phones_for_assignation'];
                    $permissions["supervisor"] = true;
                } else {
                    $phones = [];
                    //throw ValidationException::withMessages(['id' => 'No tiene Teléfonos asignados.']);
                }
            } else {
                # No phones
                $phones = [];
                //throw ValidationException::withMessages(['id' => 'No tiene Teléfonos asignados.']);
            }

            return response()->json(["phones" => $phones, "permissions" => $permissions], 200);
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
            $admEmployees = AdminEmployee::where('active', true)->get();

            return response()->json([
                'employees' => $admEmployees,
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
                'number' => [
                    'required',
                    'string',
                    'min:9',
                    'max:9',
                    Rule::unique(
                        'pho_phones',
                        'number'
                    )->whereNull('deleted_at')
                ],
                'imei' => [
                    'nullable',
                    'max:15',
                    'min:0',
                    Rule::unique(
                        'pho_phones',
                        'imei'
                    )->whereNull('deleted_at')
                ],
                'price' => [
                    'required',
                    'max:9999.99',
                    'decimal:0,2'
                ],
                'active' => ['nullable'],

                'pho_phone_type_phone_id' => [
                    'required',
                    'integer',
                    Rule::exists(
                        'pho_phone_type_phones',
                        'id'
                    )->where('active', true)
                        ->whereNull('deleted_at')
                ],
                'adm_employee_id' => [
                    'nullable',
                    'integer',
                    Rule::exists(
                        'adm_employees',
                        'id'
                    )->where('active', true)
                        ->whereNull('deleted_at')
                ],
                'pho_phone_plan_id' => [
                    'nullable',
                    'integer',
                    Rule::exists(
                        'pho_phone_plans',
                        'id'
                    )->where('active', true)
                        ->whereNull('deleted_at')
                ],
                'pho_phone_contract_id' => [
                    'required',
                    'integer',
                    Rule::exists(
                        'pho_phone_contracts',
                        'id'
                    )->where('active', true)
                        ->whereNull('deleted_at')
                ],
                'pho_phone_model_id' => [
                    'required',
                    'integer',
                    Rule::exists(
                        'pho_phone_models',
                        'id'
                    )->where('active', true)
                        ->whereNull('deleted_at')
                ]
            ];

            $messages = [
                'string' => 'El formato d:attribute es irreconocible.',
                'number.unique' => ':attribute ya existe',
                'imei.unique' => ':attribute ya existe',
                'number.min' => ':attribute debe ser de 8 caracteres. ',
                'number.max' => ':attribute debe ser de 8 caracteres. ',

                'price.min' => ':attribute debe ser de 0 caracteres. ',
                'price.max' => ':attribute debe ser de 9999.99 caracteres. ',

                'min' => ':attributes ingresado debe ser mayor o igual a 0',
                'max' => ':attribute excede los caracteres máximos',

                'type.max' => ':attribute debe ser de máximo 50 caracteres. ',
                'boolean' => 'El formato de :attribute es diferente al esperado',

                'decimal' => ':attribute solo puede tener 2 decimales',
                'between' => 'El formato d:attribute debe ser mayor que 0 y menor que 9999.99.',
                'integer' => 'El formato d:attribute es irreconocible.',

                'exists' => ':attribute no existe  o está inactivo.',
            ];

            $attributes = [
                'number' => 'El Número del Teléfono',
                'imei' => 'El IMEI del Teléfono',
                'price' => 'el Precio del Teléfono',
                'active' => 'El Estado del Teléfono',

                'pho_phone_type_phone_id' => 'El Tipo del Teléfono',
                'adm_employee_id' => 'el Identificador del Empleado',
                'pho_phone_plan_id' => 'el Identificador del Plan',
                'pho_phone_contract_id' => 'el Identificador del Contrato',
                'pho_phone_model_id' => 'el Identificador del Modelo',
            ];

            $request->validate($rules, $messages, $attributes);

            $requestPhoneData = [
                'number' => $request->number,
                'imei' => $request->imei,
                'price' => $request->price,
                'active' => $request->active == 'true' ? true : false,

                'pho_phone_type_phone_id' => $request->pho_phone_type_phone_id,
                'adm_employee_id' => $request->adm_employee_id,
                'pho_phone_plan_id' => $request->pho_phone_plan_id,
                'pho_phone_contract_id' => $request->pho_phone_contract_id,
                'pho_phone_model_id' => $request->pho_phone_model_id
            ];

            Phone::create($requestPhoneData);

            return response()->json($requestPhoneData, 200);
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
                [
                    'id' => [
                        'required',
                        'integer',
                        Rule::exists('pho_phones', 'id')->whereNull('deleted_at')
                    ]
                ],
                [
                    'id.required' => 'Falta :attribute.',
                    'id.integer' => ':attribute irreconocible.',
                    'id.exists' => ':attribute solicitado sin coincidencia.',
                ],
                ['id' => 'Identificador de Teléfono de Solicitud.'],
            )->validate();

            $phone = Phone::with(
                [
                    'employee',
                    'plan',
                    'contract',
                    'model.brand',
                    'model',
                    'incidents',
                    'type'
                ]
            )->withCount(
                [
                    'incidents'
                ]
            )->findOrFail($validatedData['id']);

            return response()->json($phone, 200);
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
                'id' => [
                    'required',
                    'integer',
                    Rule::exists(
                        'pho_phones',
                        'id'
                    )->whereNull('deleted_at'),
                    Rule::in([$id])
                ],
                'number' => [
                    'required',
                    'string',
                    Rule::unique(
                        'pho_phones',
                        'number'
                    )->ignore($request->id)
                        ->whereNull('deleted_at')
                ],
                'imei' => [
                    'nullable',
                    'max:15',
                    'min:0',
                    Rule::unique(
                        'pho_phones',
                        'imei'
                    )->whereNull('deleted_at')
                ],
                'price' => ['required', 'max:9999.99', 'decimal:0,2'],
                'active' => ['nullable'],

                'pho_phone_type_phone_id' => [
                    'required',
                    'integer',
                    Rule::exists(
                        'pho_phone_type_phones',
                        'id'
                    )->where('active', true)
                        ->whereNull('deleted_at')
                ],
                'adm_employee_id' => [
                    'nullable',
                    'integer',
                    Rule::exists(
                        'adm_employees',
                        'id'
                    )->where('active', true)
                        ->whereNull('deleted_at')
                ],
                'pho_phone_plan_id' => [
                    'nullable',
                    'integer',
                    Rule::exists(
                        'pho_phone_plans',
                        'id'
                    )->where('active', true)
                        ->whereNull('deleted_at')
                ],
                'pho_phone_contract_id' => [
                    'required',
                    'integer',
                    Rule::exists(
                        'pho_phone_contracts',
                        'id'
                    )->where('active', true)
                        ->whereNull('deleted_at')
                ],
                'pho_phone_model_id' => [
                    'required',
                    'integer',
                    Rule::exists(
                        'pho_phone_models',
                        'id'
                    )->where('active', true)
                        ->whereNull('deleted_at')
                ],
            ];

            $messages = [
                'id.in' => 'El ID no coincide con el registro a modificar.',
                'required' => 'Falta :attribute.',
                'string' => 'El formato d:attribute es irreconocible.',
                'number.unique' => ':attribute ya existe.',
                'imei.unique' => ':attribute ya existe.',

                'min' => ':attribute ser de mínimo 0 caracteres.  ',
                'max' => ':attribute excede los caracteres máximos',


                'type.max' => ':attribute ser de máximo 50 caracteres. ',
                'boolean' => 'El formato de :attribute es diferente al esperado',

                'decimal' => ':attribute solo puede tener 2 decimales',
                'between' => 'El formato d:attribute debe ser mayor que 0 y menor que 9999.99.',
                'integer' => 'El formato d:attribute es irreconocible.',
                'exists' => ':attribute no existe o está inactivo.',
            ];

            $attributes = [
                'number' => 'el Número del Teléfono',
                'imei' => 'el IMEI del Teléfono',
                'price' => 'el Precio del Teléfono',
                'active' => 'el Estado del Teléfono',

                'pho_phone_type_phone_id' => 'El Tipo del Teléfono',
                'adm_employee_id' => 'el Identificador del Empleado',
                'pho_phone_plan_id' => 'el Identificador del Plan',
                'pho_phone_contract_id' => 'el Identificador del Contrato',
                'pho_phone_model_id' => 'el Identificador del Modelo',
            ];

            $request->validate($rules, $messages, $attributes);

            $requestPhone = Phone::findOrFail($request->id);

            $requestPhoneData = [
                'number' => $request->number,
                'imei' => $request->imei,
                'price' => $request->price,
                'active' => $request->active == 'true' ? true : false,

                'pho_phone_type_phone_id' => $request->pho_phone_type_phone_id,
                'adm_employee_id' => $request->adm_employee_id,
                'pho_phone_plan_id' => $request->pho_phone_plan_id,
                'pho_phone_contract_id' => $request->pho_phone_contract_id,
                'pho_phone_model_id' => $request->pho_phone_model_id
            ];

            $requestPhone->update($requestPhoneData);

            return response()->json($requestPhoneData, 200);
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
                ['id' => ['required', 'integer', 'exists:pho_phones,id']],
                [
                    'id.required' => 'Falta el :attribute.',
                    'id.integer' => 'El :attribute es irreconocible.',
                    'id.exists' => 'El :attribute enviado, sin coincidencia.',
                ],
                [
                    'id' => 'Identificador del Teléfono de Solicitud',
                ]
            )->validate();

            $phone = NULL;

            DB::transaction(
                function () use ($validatedData, &$phone) {
                    $phone = Phone::findOrFail($validatedData['id']);
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

    /**
     * OTHER RESOURCES ABOUT = [
     * PHONES,
     * PHONE - ACTIVES
     * PHONE - ASSIGNED
     * ].
     */
    public function activePhones(int $id = null)
    {
        try {
            $commonQuery = Phone::where('active', true);

            if ($id !== null) {
                $validatedData = Validator::make(
                    ['id' => $id],
                    ['id' => ['required', 'integer', 'exists:pho_phones,id']],
                    [
                        'id.required' => 'Falta el :attribute.',
                        'id.integer' => 'El :attribute es irreconocible.',
                        'id.exists' => 'El :attribute enviado, sin coincidencia.',
                    ],
                    [
                        'id' => 'Identificador de Contrato de Solicitud',
                    ]
                )->validate();

                $requestPhones = $commonQuery->with(
                    [
                        'employee',
                        'plan',
                        'contract',
                        'model.brand',
                        'incidents'
                    ]
                )->findOrFail($validatedData['id']);
            } else {
                $requestPhones = $commonQuery->with(
                    [
                        'employee',
                        'employee.phones_assigned',
                        'employee.phones_for_assignation',

                        'plan',
                        'model.brand',
                        'type',
                        'phone_supervisors',
                    ]
                )->withCount(['incidents'])->get();
            }

            return response()->json($requestPhones, 200);
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' | ' . $e->getFile() . ' - ' . $e->getLine() . '. Información enviada: ' . json_encode($id));

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function activePhonesAssign()
    {
        try {
            $requestPhones = Phone::where('active', true)->with(
                [
                    'employee',
                    'employee.phones_assigned',
                    'employee.phones_for_assignation',

                    'plan',
                    'model.brand',
                    'type',
                    'phone_supervisors',
                ]
            )->doesntHave('phone_supervisors')->get();

            $availablePhones = $requestPhones;


            return response()->json($availablePhones, 200);
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' | ' . $e->getFile() . ' - ' . $e->getLine());

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
