<?php

namespace App\Http\Controllers\Phones;

use App\Http\Controllers\Controller;
use App\Models\Phones\PhoneModel;
use Illuminate\Http\Request;

class ModelController extends Controller
{
    public function index()
    {

    }

    public function show($id)
    {

    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'active' => 'required',

        ]);

        PhoneModel::create([
            'name' => $request->input('name'),
            'active' => $request->input('active'),

        ]);


        return redirect()->route('ModelController')->with('success', 'Registro agregado exitosamente');
    }

}
