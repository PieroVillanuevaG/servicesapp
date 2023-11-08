<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserStockController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    private $bd;

    public function __construct()
    {
        $this->bd = DB::connection('dbstock');
    }

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
    public function userVerify(Request $request)
    {

        $user = $request->user;
        $password = $request->password;
        if (!$user) {
            return response()->json(["status" => false, "message" => "Inserte el usuario"], 200);
        }
        if (!$password) {
            return response()->json(["status" => false, "message" => "Inserte la contraseña"], 200);
        }

        $verify = $this->bd->table('usuarios')
            ->where("user", $user)
            //->where("password", $hash_password)
            ->first();

        if (!$verify) {
            return response()->json(["status" => false, "message" => "Las credenciales son incorrectas"], 200);
        }

        if (!password_verify($password, $verify->password)) {
            return response()->json(["status" => false, "message" => "Las credenciales son incorrectas"], 200);
        }

        if (!$verify->status) {
            return response()->json(["status" => false, "message" => "El usuario se encuentra inhabilitado"], 200);
        }

        $customer = $this->bd->table('customer')
            ->where("id", $verify->customer_id)
            ->first();

        return response()->json(["status" => true, "message" => "Usuario encontrado","data"=> $customer], 200);

    }

    public function store(Request $request)
    {
        $user = $request->document;
        $first_name = $request->first_name;
        $second_name = $request->second_name;
        $surnames = $request->surnames;
        $email = $request->email;
        $phone = $request->phone;
        $age = $request->age;
        $address = $request->address;
        $gender = $request->gender;
        $terms = 1;
        $type_document = $request->type_document;
        $fecha_nacimiento = $request->fecha_nacimiento;

        if (!$terms) {
            return response()->json(["status" => false,
                "message" => "Si no acepta los terminos y condiciones no se podra crear su cuenta"], 200);
        }
        if (!$first_name) {
            return response()->json(["status" => false,
                "message" => "Digite el primer nombre"], 200);
        }
        if (!$type_document) {
            return response()->json(["status" => false,
                "message" => "Digite el tipo de documento"], 200);
        }
        if (!$surnames) {
            return response()->json(["status" => false,
                "message" => "Digite los apellidos"], 200);
        }

        if (!$phone) {
            return response()->json(["status" => false,
                "message" => "Digite su numero de telefono"], 200);
        }

        if (!$gender) {
            return response()->json(["status" => false,
                "message" => "Digite su sexo"], 200);
        }
        $password = $request->password;
        $hash_password = password_hash($password, PASSWORD_DEFAULT);

        $verify = $this->bd->table('usuarios')
            ->where("user", $user)
            //->where("password", $hash_password)
            ->get();

        if (count($verify) > 0) {
            return response()->json(["status" => false, "message" => "Ya existe un usuario registrado con esos datos"], 200);
        }

        try {

            $store_customer = $this->bd->table('customer')
                ->insertGetId([
                    "document" => $user,
                    "first_name" => $first_name,
                    "second_name" => $second_name,
                    "surnames" => $surnames,
                    "email" => $email,
                    "phone" => $phone,
                    "age" => $age,
                    "address" => $address,
                    "gender" => $gender,
                    "type_document" => $type_document,
                    "status" => 1,
                    "created_date" => date("Y-m-d H:i:s"),
                    "fecha_nacimiento" => $fecha_nacimiento,
                ]);

            $store_user =$this->bd->table('usuarios')
                ->insert([
                    "user" => $user,
                    "password" => $hash_password,
                    "status" => 1,
                    "customer_id" => $store_customer,
                    "created_date" => date("Y-m-d H:i:s")
                ]);

            if (!$store_user) {
                return response()->json(["status" => false, "message" => "Surgió un error al crear el usuario"], 200);
            }

            return response()->json(["status" => true, "message" => "Registro realizado correctamente"], 200);

        } catch (\PDOException $e) {
            return response()->json(["status" => false, "message" => "Surgió un error al crear el usuario"], 200);
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
