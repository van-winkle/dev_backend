<?php

namespace App\Http\Controllers\Phones;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Phones\IncidentsAttaches;
use Illuminate\Support\Facades\Validator;

class PhoneIncidentAttachesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{

        $incidentAttaches = IncidentsAttaches::withCount(['incidents','attaches'])->get();
            return response()->json($incidentAttaches, 200);

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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try {
            $validateData = Validator::make(
                ['id' => $id],
                ['id' =>['required', 'integer', 'exists:pho_phone_incident_attaches,pho_phone_incident_id']],
                [
                    'id.required' => 'Falta el :attribute',
                    'id.integer' => ':attribute irreconocible.',
                    'id.exists' => ':attribute solicitado sin coincidencia.',
                ],
                ['id' => 'Identificador de los archivos por incidencia']

            )->validate();

            $incidentAttaches = IncidentsAttaches::with([
                'incidents',
                'attaches'
            ])->withCount(['incidents', 'attaches'])
            ->findOrFail($validateData['id']);
          return response()->json($incidentAttaches, 200);
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
