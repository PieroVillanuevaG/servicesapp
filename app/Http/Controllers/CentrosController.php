<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CentrosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        try {
            $especialidades = DB::table("centros")
                ->get();
            return response()->json(["status" => true, "message" => "Lista de centros obtenida", "data" => $especialidades]);
        } catch (\PDOException $e) {
            return response()->json(["status" => false, "message" => "Surgió un error traer los centros","data"=> $e], 200);
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
