<?php

namespace App\Http\Controllers\Phones;

use App\Http\Controllers\Controller;
use App\Models\Phones\Phone;
use Illuminate\Http\Request;

class PhoneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $phones = Phone::all();
        //$phones->employee;
        return $phones;
        if($phones == []){
            return response()->json([
                'code'=>404,
                'data'=>'No data'
            ], 404);
        }
        else if ($phones->count()>0) {
            return response()->json([
                'code'=>200,
                'data'=>$phones
            ], 200);
        } else {
            return response()->json([
                'code'=>404,
                'data'=>'No data'
            ], 404);
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
        //
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
