<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DoctorsController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function searchDoctor(Request $request)
    {

        date_default_timezone_set('America/Lima');

        $nombres = $request->nombres;
        $especialidad = $request->especialidad;
        $centro_atencion = $request->centro_atencion;

        if (!$especialidad) {
            return response()->json(["status" => false, "message" => "Debe escoger una especialidad para la busqueda"], 200);
        }
        if (!$centro_atencion) {
            return response()->json(["status" => false, "message" => "Debe escoger un centro de atención para la busqueda"], 200);
        }

        $search = [];
        if ($nombres) {
            $search = DB::table('doctors')
                ->where('especialidad', $especialidad)
                ->where('centro_atencion', $centro_atencion)
                ->where('nombres', "LIKE", "%$nombres%")
                ->get();
        } else {
            $search = DB::table('doctors')
                ->where('especialidad', $especialidad)
                ->where('centro_atencion', $centro_atencion)
                ->get();
        }

        if (count($search) == 0) {
            return response()->json(["status" => true, "message" => "No se encontró resultados"], 200);
        }

        $fecha_actual = strtotime(date("Y-m-d"));
        $dia_actual = date("N", $fecha_actual);

        $traductor = [
            "Sunday" => "Domingo",
            "Monday" => "Lunes",
            "Tuesday" => "Martes",
            "Wednesday" => "Miercoles",
            "Thursday" => "Jueves",
            "Friday" => "Viernes",
            "Saturday" => "Sabado",
        ];

        $traductor_fecha = [
            "Domingo" => "d",
            "Lunes" => "l",
            "Martes" => "ma",
            "Miercoles" => "mi",
            "Jueves" => "j",
            "Viernes" => "v",
            "Sabado" => "s"
        ];

        $fechas = [];
        for ($i = 0; $i < 7; $i++) {
            $fecha = strtotime("-$i day", $fecha_actual);
            $nombre_dia = strftime("%A", $fecha);
            $fechas[$traductor_fecha[$traductor[$nombre_dia]]] = $traductor[$nombre_dia] . " " . date("d", $fecha);
            if ($nombre_dia == "Monday") {
                break;
            }
        }

        $data = [];
        foreach ($search as $detail) {
            $horarios = json_decode($detail->horarios, true);
            $new_horarios = [];
            foreach ($horarios as $d => $horas) {
                if (!array_key_exists($d, $fechas)) continue;
                $new_horarios[$fechas[$d]] = $horas;
            }

            $data[] = [
                "nombres" => $detail->nombres,
                "imagen" => $detail->imagen,
                "cargo" => $detail->cargo,
                "especialidad" => $detail->especialidad,
                "centro_atencion" => $detail->centro_atencion,
                "titulo" => $detail->titulo,
                "premios" => $detail->premios,
                "sociedad" => $detail->sociedad,
                "horarios" => $new_horarios,
                "id" => $detail->id
            ];
        }
        return response()->json(["status" => true, "message" => "Lista generada correctamente", "data" => $data], 200);
    }

    public function index()
    {

        try {
            $doctors = DB::table("doctors")
                ->where("status", 1)
                ->get();

            $traductor_fecha = [
                "d"=> "DOMINGO",
                "l" => "LUNES",
                "ma" => "MARTES",
                "mi" => "MIERCOLES",
                "j" => "JUEVES",
                "v" => "VIERNES",
                "s" => "SABADO"
            ];


            foreach($doctors as &$doctor){
                $horarios = json_decode($doctor->horarios);
                $new_horarios = [];
                foreach($horarios as $fecha => $detail){
                    if(count($detail) == 0) continue;
                    $new_hours = [];
                    foreach($detail as &$h){
                        if (intval($h) <= 12) $h = $h . " a.m.";
                        else $h = $h . " p.m.";
                    }
                    $new_horarios[] = $traductor_fecha[$fecha].": ".implode(" - ", $detail);
                }
                $doctor->horario_actualizado = $new_horarios;
            }




            return response()->json(["status" => true, "message" => "Lista de doctores obtenida", "data" => $doctors]);
        } catch (\PDOException $e) {
            return response()->json(["status" => false, "message" => "Surgió un error traer los doctores","data"=> $e], 200);
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
