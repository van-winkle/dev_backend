<?php

namespace App\Http\Controllers\Phones;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Phones\PhoneBrand;




class BrandController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $phoneBrands = PhoneBrand::all();
        return $phoneBrands;
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
            return response()->json(['message' => 'PhoneBrand not found'], 404);
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
