<?php

namespace App\Http\Controllers\Phones;

use Illuminate\Http\Request;

use App\Models\Phones\PhoneBrand;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;




class BrandController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $phoneBrands = PhoneBrand::all();
        return response()->json($phoneBrands);
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:pho_phone_brands|max:50',
            'active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $phoneBrand = new PhoneBrand;
        $phoneBrand->name = $request->input('name');
        $phoneBrand->active = $request->input('active', true);
        $phoneBrand->save();

        return response()->json($phoneBrand, 201);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $phoneBrand = PhoneBrand::find($id);
        if ($phoneBrand) {
            return response()->json($phoneBrand);
        } else {
            return response()->json(['message' => 'Not found'], 404);
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
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:50',
            'active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $phoneBrand = PhoneBrand::find($id);

        if (!$phoneBrand) {
            return response()->json(['message' => 'Phone brand not found'], 404);
        }

        $existingBrand = PhoneBrand::where('name', $request->input('name'))
            ->where('id', '!=', $id)
            ->where('active', true)
            ->first();

        if ($existingBrand) {
            return response()->json(['message' => 'Name already exists for an active PhoneBrand'], 400);
        }

        $phoneBrand->name = $request->input('name');
        $phoneBrand->active = $request->input('active', true);
        $phoneBrand->save();

        return response()->json($phoneBrand);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
