<?php

namespace App\Http\Controllers\Phones;

use Illuminate\Http\Request;
use App\Models\Phones\PhoneBrand;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Exception;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $phoneBrands = PhoneBrand::with([
                'models'
                // 'models:name,active,pho_phone_brand_id'
            ])
                ->withCount('models')
                ->get();

            return response()->json($phoneBrands, 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al procesesar los datos',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $rules = [
                'name' => [
                    'required',
                    'string',
                    'max:50',
                    Rule::unique('pho_phone_brands')->whereNull('deleted_at')
                ],
                'active' => ['nullable', 'boolean'],
            ];

            $messages = [
                'required' => 'El campo :attribute es requerido',
                'boolean' => 'El formato de :attribute es diferente al esperado',
                'name.unique' => 'El nombre ya existe!!',
            ];

            $attributes = [
                'name' => 'Nombre',
                'active' => 'Activo',
            ];

            $validator = Validator::make($request->all(), $rules, $messages, $attributes);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            $data = [
                'name' => $request->name,
                'active' => $request->active == 'true' ? true : false
            ];

            $newBrand = PhoneBrand::create($data);

            return response()->json($newBrand, 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al crear una nueva marca',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try {
            $phoneBrand = PhoneBrand::with(['models'])->findOrFail($id);

            if ($phoneBrand) {
                return response()->json($phoneBrand);
            } else {
                return response()->json(['message' => 'Not found'], 404);
            }
        } catch (Exception $e) {
            return response()->json(['message' => 'Error fetching phone brand', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:50', Rule::unique('pho_phone_brands')->ignore($id)->whereNull('deleted_at')],
                'active' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            $phoneBrand = PhoneBrand::find($id);

            if (!$phoneBrand) {
                return response()->json(['message' => 'Brand not found'], 404);
            }

            $existingBrand = PhoneBrand::where('name', $request->input('name'))
                ->where('id', '!=', $id)
                ->where('active', true)
                ->first();

            if ($existingBrand) {
                return response()->json(['message' => 'Name already exists'], 400);
            }

            $phoneBrand->name = $request->input('name');
            $phoneBrand->active = $request->input('active', true);
            $phoneBrand->save();

            return response()->json($phoneBrand);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error updating phone brand', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $phoneBrand = PhoneBrand::find($id);

            if (!$phoneBrand) {
                return response()->json(['message' => 'Brand not found'], 404);
            }

            $phoneBrand->delete();

            return response()->json(['message' => 'Brand deleted'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error deleting phone brand', 'error' => $e->getMessage()], 500);
        }
    }

    public function phoneBrandsActive($id = null)
    {
        try {
            $brands = PhoneBrand::where('active', true)->get();
            $phoneBrands = PhoneBrand::where('active', true)->where('id', $id)->first();
            // Aquí deberías hacer algo con $brands y $phoneBrands
        } catch (Exception $e) {
            return response()->json(['message' => 'Error fetching phone brands', 'error' => $e->getMessage()], 500);
        }
    }
}
