<?php

namespace App\Http\Controllers\Phones;

use Exception;
use Illuminate\Http\Request;
use App\Models\Phones\PhoneModel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;


class ModelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $model = PhoneModel::with([
                'brand'])
                ->withCount(['brand'])
                ->get();
            return response()->json($model, 200);

        } catch (Exception $e) {
            Log::error($e->getMessage() );
            return response()->json(['message' => 'Ha ocurrido un error al procesar la solicitud.', 'errors' => $e->getMessage()], 500);
            //throw $th;
        }

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
                "name" => ['required', 'max:50', Rule::unique('name')->whereNull('deleted_at')],
                'active' => ['nullable', 'bool'],
                'pho_phone_brand_id' => ['required', 'integer', 'pho_phone_brand_id'],

            ];
            $messages = [
                'required' => 'El valor del :attribute es necesario',
                'bool' => 'El formato de :attribute es diferente al esperado',
            ];

            $attributes = [
                'name' => 'Nombre',
                'active' => 'Activo'
            ];

            $request->validate($rules, $messages, $attributes);

        } catch (\Throwable $th) {
            //throw $th;
        }
      /*  $validation = Validator::make($request->all(),[
            'name' => 'required | string',
            'active' => 'required|boolean',
            'pho_phone_brand_id' => 'required|integer'

        ]);

        if($validation->fails()){
            return response()->json([
                'code' => 400,
                'date' => $validation->messages()
            ], 400);
        } else {
           PhoneModel::create($request->all());
            return response()->json([
                'code' => 200,
                'data' => 'data OK'
            ],200);
        } */

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $model = PhoneModel::find($id);
        $model->brand;
         return $model;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data=PhoneModel::findOrFail($id);

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


    }
}
