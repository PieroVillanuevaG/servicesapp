<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CitaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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

    public function historial(Request $request){

        $document = $request->document;
        if (!$document) {
            return response()->json(["status" => false, "message" => "Es necesario el documento del paciente"], 200);
        }
        try {

            $citas = DB::table('cita')
                ->select("paciente_nombre","document","medico_nombre","especiality","lugar","fecha","estado_cita")
                ->where('document', $document)
                ->get();

            return response()->json(["status" => true, "message" => "Lista de citas encontradas", "data"=> $citas], 200);

        }catch (Exception $e){
            return response()->json(["status" => false, "message" => "Surgió un error al ver el historial de citas"], 200);
        }
    }

    public function store(Request $request)
    {
        $document = $request->document;
        $nombre = $request->nombre;
        $medico = $request->medico;
        $medico_nombre = $request->medico_nombre;
        $date = $request->date;
        $especiality = $request->especiality;
        $email = $request->email;
        $horario = $request->horario;

        if (!$document) {
            return response()->json(["status" => false, "message" => "Es necesario el documento del paciente"], 200);
        }
        if (!$medico) {
            return response()->json(["status" => false, "message" => "Es necesario el medico"], 200);
        }
        if (!$date) {
            return response()->json(["status" => false, "message" => "Es necesario la fecha de atención"], 200);
        }
        if (!$especiality) {
            return response()->json(["status" => false, "message" => "Es necesario la especialidad"], 200);
        }
        if (!$email) {
            return response()->json(["status" => false, "message" => "Es necesario el email del paciente"], 200);
        }
        if (!$horario) {
            return response()->json(["status" => false, "message" => "Es necesario el horario del medico"], 200);
        }


        $body = [
            "document" => $document,
            "paciente_nombre" => $nombre,
            "medico" => $medico,
            "medico_nombre" => $medico_nombre,
            "fecha" => $date,
            "especiality" => $especiality,
            "horario" => $horario,
            "email" => $email,
            "created_date" => date("Y-m-d H:i:s"),
            "estado_cita"=> "PENDIENTE",
            "status" => 1
        ];

        try{

            $insert = DB::table('cita')
                ->insert($body);
            if(!$insert){
                return response()->json(["status" => false, "message" => "Surgió un error al registrar la cita, intente en unos minutos"], 200);
            }
            $send_message = app(EmailController::class)->sendEmail($email, $body, 'CITA REGISTRADA');
            return response()->json(["status" => true, "message" => "Cita registrada correctamente"], 200);
        }catch (\Exception $e){
            return response()->json(["status" => false, "message" => "Surgió un error al registrar la cita, intente en unos minutos"], 200);
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
