<?php

namespace App\Http\Controllers\Phones;

use Exception;
use App\Http\Controllers\Controller;
use App\Models\Phones\AdminEmployee;
use App\Models\Phones\Phone;
use App\Models\Phones\PhoneAssignment;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


use Illuminate\Http\Request;

class PhoneAssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
       $assignments = AdminEmployee::with([
            'phones_for_assignation',
        ])->whereHas('phones_for_assignation')
        ->get();
        //$assignments = PhoneAssignment::all();
        return response()->json($assignments, 200);
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
        //
        try {
            $rules = [

                'adm_employee_id' => ['required', 'integer', Rule::exists('adm_employees', 'id')->where('active', true)->whereNull('deleted_at')],
                'phones' => ['required', 'array'],
                'phones.*' => ['integer', 'exists:pho_phones,id'],

            ];

            $messages = [
                'required' => 'Falta :attribute.',
                'array' => 'El formato d:attribute es irreconocible.',
                'integer' => 'El formato d:attribute es irreconocible.',
                'exists' => ':attribute no existe.  ',
            ];

            $attributes = [

                'adm_employee_id' => 'el Identificador del Empleado',
                'phones' => 'Identificadores de Teléfonos',
                'phones.*' => 'el Identificador de Teléfono',
            ];

            $request->validate($rules, $messages, $attributes);

            $newPhoneAssignment = [];

            if ($request->phones) {
                
                foreach ($request->phones as $idx => $phoneId) {
                    //Validate if the phone isn't assigned
                    $phone = Phone::findOrFail($phoneId);
                    if ( $phone->phone_supervisors()->exists()) {
                        throw ValidationException::withMessages(['phones' => 'Se encotrarón Teléfonos ya asignados.']);
                    } 
                }

                $newPhoneAssignment = AdminEmployee::findOrFail($request['adm_employee_id']);
                foreach ($request->phones as $idx => $phoneId) {
                    //Creating the assignment
                    $newPhoneAssignment->phones_for_assignation()->attach($phoneId, ['adm_employee_id' => $request['adm_employee_id']]);
                    /* $newPhoneAssignment[] = PhoneAssignment::create([
                        'adm_employee_id' => $request->adm_employee_id,
                        'pho_phone_id' => $phoneId,
                    ]); */ 

                    
                }

            };

            return response()->json(['assigned_phones'=>$newPhoneAssignment], 200);


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
    public function update(Request $request, int $id)
    {
        //
        try {
            $rules = [

                'adm_employee_id' => ['required', 'integer', 
                    Rule::exists('adm_employees', 'id')->where('active', true)->whereNull('deleted_at'),
                    Rule::in([$id])
                ],
                'phones' => ['required', 'array'],
                'phones.*' => ['integer', 'exists:pho_phones,id'],

            ];

            $messages = [
                'required' => 'Falta :attribute.',
                'array' => 'El formato d:attribute es irreconocible.',
                'integer' => 'El formato d:attribute es irreconocible.',
                'exists' => ':attribute no existe.',
                'adm_employee_id.in' => 'El :attribute no coincide con el registro a modificar.',
            ];

            $attributes = [

                'adm_employee_id' => 'el Identificador del Empleado',
                'phones' => 'Identificadores de Teléfonos',
                'phones.*' => 'El Identificador de Teléfono',
            ];

            $request->validate($rules, $messages, $attributes);

            $employee = [];

            DB::transaction(function () use ($request, &$employee) {

                $employee = AdminEmployee::findOrFail($request['adm_employee_id']);
                if ( $employee->phones_for_assignation()->exists()) {
                    $employee->phones_for_assignation()->sync($request['phones']);
                } else {
                    throw ValidationException::withMessages(['id' => 'El empleado no tiene Asignación de Teléfonos para actualizar.']);
                }
            });

            return response()->json(['message' => 'Asignación de Teléfono a '. $employee['name'] .', actualizada con éxito.'], 200);


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
                [
                    'id' => $id
                ],
                [
                    'id' => ['required', 'integer', 'exists:adm_employees,id']
                ],
                [
                    'id.required' => 'Falta ingresar el :attribute.',
                    'id.integer' => 'El :attribute no es reconocible',
                    'id.exists' => 'El :attribute ingresado no se encontró.',
                ],
                [
                    'id' => 'Identificador del empleado',
                ]
            )->validate();

            $employee = [];

            DB::transaction(function () use ($validatedData, &$employee) {

                $employee = AdminEmployee::findOrFail($validatedData['id']);
                if ( $employee->phones_for_assignation()->exists()) {
                    $employee->phones_for_assignation()->detach();
                } else {
                    throw ValidationException::withMessages(['id' => 'El empleado no tiene Asignación de Teléfonos.']);
                }
            });

            return response()->json(['message' => 'Asignación de Teléfono a '. $employee['name'] .', Eliminada con éxito.'], 200);

        } catch (ValidationException $e) {
            Log::error(json_encode($e->validator->errors()->getMessages()) . '. Información enviada: ' . json_encode($id));

            return response()->json(['message' => $e->validator->errors()->getMessages()], 422);
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' | ' . $e->getFile() . ' - ' . $e->getLine() . '. Información enviada: ' . json_encode($id));

            return response()->json(['message' => 'Error al borrar Asignación de Telefono.', 'error' => $e->getMessage()], 500);
        }
    }
}
