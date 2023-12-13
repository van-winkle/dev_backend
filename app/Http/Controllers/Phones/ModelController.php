<?php

namespace App\Http\Controllers\Phones;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Phones\PhoneModel;

class ModelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $phoneModel = PhoneModel::all(1);
        return $phoneModel;
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
         $request->validate([
            'name' => 'required',
            'active' => 'required',

        ]);

        PhoneModel::create([
            'name' => $request->input('campo1'),
            'active' => $request->input('campo2'),

        ]);


        return redirect()->route('')->with('', '');
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
        //
    }
}
