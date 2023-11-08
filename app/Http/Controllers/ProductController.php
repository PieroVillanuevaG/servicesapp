<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Imports\ArrayImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use PDF;

class ProductController extends Controller
{

    private $bd;

    public function __construct()
    {
        $this->bd = DB::connection('dbstock');
    }


    public function index()
    {
        try {
            $products = $this->bd->table("products")
                ->where("status", 1)
                ->get();

            return response()->json(["status" => true, "message" => "Lista de productos obtenida", "data" => $products]);
        } catch (\PDOException $e) {
            return response()->json(["status" => false, "message" => "Surgió un error traer los productos","data"=> $e], 200);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function salida(Request $request){

        $products = json_decode(json_encode($request->products));
        $user = $request->user;
        $name = $request->name;

        if(count($products) == 0) return response()->json(["status" => false, "message" => "Digite productos para realizar la salida"], 200);

        $errors = 0;
        $codes = [];
        foreach ($products as $pro) {
            $product = $this->bd->table("products")
                ->where("status", 1)
                ->where("code", $pro->code)
                ->where("warehouse", $pro->warehouse)
                ->get();
            if (count($product) == 0) {
                $errors++;
                continue;
            }

            if (intval($pro->qty) > intval($product[0]->qty)) {
                $errors++;
                continue;
            }
            $codes[$pro->code][$pro->warehouse] = $product[0];
        }

        if($errors> 0) return response()->json(["status" => false, "message" => "Al registrar la salida se encontrarón errores de cantidades"], 200);
        $total = 0;
        $total_cantidad = 0;
        foreach ($products as $pro) {
            $id = $codes[$pro->code][$pro->warehouse]->id;
            $qty = intval($codes[$pro->code][$pro->warehouse]->qty) - intval($pro->qty);
            $body = ["qty" => $qty];

            $total += floatval($codes[$pro->code][$pro->warehouse]->price_selling);
            $total_cantidad += intval($codes[$pro->code][$pro->warehouse]->qty);

            $update = $this->bd->table("products")
                ->where("id", $id)
                ->update($body);

            $body_historial = [
                "code" => $pro->code,
                "name" => $pro->name,
                "warehouse" => $pro->warehouse,
                "qty" => $qty,
                "proceso" => "salida",
                "user" => $user,
                "created_date" => date("Y-m-d H:i:s")
            ];
            $insert_historial = $this->bd->table("historial_products")
                ->insert($body_historial);
        }


        $data = [
            "total" => $total,
            "documento" => $user,
            "cliente" => $name,
            "total_cantidad" => $total_cantidad,
            "productos" => json_decode(json_encode($products), true)
        ];

        $insert_detail = $this->bd->table("salida_detail")
            ->insertGetId([
                "data" => json_encode($data),
                "user" => $user,
                "created_date" => date("Y-m-d H:i:s")
            ]);

        return response()->json(["status" => true, "message" => "Salida registrada correctamente", "url"=> "/api/products/pdf/".$insert_detail], 200);


        //$pdf = PDF::loadView('pdf.pdf', $data);
//        return $pdf->download('ejemplo.pdf');
    }

    public function pdf($id)
    {

        $salida_detail = $this->bd->table("salida_detail")
            ->select("*")
            ->where("id", $id)
            ->get();

        $data = json_decode($salida_detail[0]->data, true);
        return PDF::loadView("pdf.pdf", $data)->download('FORMATO_SALIDA.pdf');
    }

    public function search(Request $request)
    {

       /* $warehouse = $request->warehouse;
        $name = $request->nombre;




        $salida_detail = $this->bd->table("salida_detail")
            ->select("*")
            ->where("id", $id)
            ->get();

        $data = json_decode($salida_detail[0]->data, true);
        return PDF::loadView("pdf.pdf", $data)->download('FORMATO_SALIDA.pdf');*/
    }



    public function massive(Request $request)
    {

        try {

            $myExcel = $request->file('file');
            $excels = \Excel::toArray(new ArrayImport, $myExcel);
            $excel = $excels[0];
            $header = $excel[0];
            $headers = ["code", "name", "warehouse", "qty", "price_buying", "price_selling", "user"];
            foreach ($header as &$head) {
                $head = strtolower($head);
                $head = trim($head);
                $head = str_replace(" ", "_", $head);
            }

            $verify_headers = array_diff($headers, $header) === array_diff($header, $headers);
            if (!$verify_headers) {
                return response()->json(["status" => false, "message" => "Verifique bien la cabezera del excel"], 200);
            }

            $errors = 0;

            foreach ($excel as $index => $body) {
                if ($index == 0) continue;
                $code = $body[0];
                $name = $body[1];
                $warehouse = $body[2];
                $qty = $body[3];
                $price_buying = $body[4];
                $price_selling = $body[5];
                $user = $body[6];
                if (!$code) $errors++;
                if (!$name) $errors++;
                if (!$warehouse) $errors++;
                if (!$qty) $errors++;
                if (intval($qty) < 0) $errors++;
                if (!$price_buying) $errors++;
                if (!$price_selling) $errors++;
                if (!$user) $errors++;
            }

            if ($errors > 0) return response()->json(["status" => false, "message" => "Verifique el contenido del excel hay datos mal digitados"], 200);

            foreach ($excel as $index => $body) {
                if ($index == 0) continue;
                $code = $body[0];
                $name = $body[1];
                $warehouse = $body[2];
                $qty = $body[3];
                $price_buying = $body[4];
                $price_selling = $body[5];
                $user = $body[6];
                $this->storeMassive($code, $name, $warehouse, $qty, $price_buying, $price_selling, $user);
            }

            return response()->json(["status" => true, "message" => "Excel subido correctamente, Productos registrados correctamente"], 200);

        } catch (Exception $e) {

            return response()->json(["status" => false, "message" => "Surgió un error al subir el excel"], 200);
        }
    }


    /**
     * Store a newly created resource in storage.
     */

    function storeMassive($code, $name, $warehouse, $qty, $price_buying, $price_selling,$user){

        if (!$code) {
            return ["status" => false,
                "message" => "Digite el codigo del producto"];
        }
        if (!$name) {
            return ["status" => false,
                "message" => "Digite el nombre del producto"];
        }
        if (!$warehouse) {
            return ["status" => false,
                "message" => "Digite el almacen del producto"];
        }
        if (!$qty) {
            return ["status" => false,
                "message" => "Digite la cantidad del producto"];
        }

        if (intval($qty) < 0) {
            return ["status" => false,
                "message" => "La cantidad del producto debe ser mayor a 0"];
        }

        if (!$price_buying) {
            return ["status" => false,
                "message" => "Digite el precio de compra del producto"];
        }
        if (!$price_selling) {
            return ["status" => false,
                "message" => "Digite el precio de venta del producto"];
        }

        try {

            $search_product = $this->bd->table("products")
                ->where('code', $code)
                ->where('warehouse', $warehouse)
                ->get();

            if (count($search_product) > 0) {

                $qty_new = intval($search_product[0]->qty) + intval($qty);
                $body = [
                    "code" => $code,
                    "name" => $name,
                    "warehouse" => $warehouse,
                    "qty" => $qty_new,
                    "price_buying" => $price_buying,
                    "price_selling" => $price_selling,
                    "created_date" => date("Y-m-d H:i:s"),
                    "status" => 1
                ];

                $update_product = $this->bd->table("products")
                    ->where("id", $search_product[0]->id)
                    ->update($body);

            } else {
                $body = [
                    "code" => $code,
                    "name" => $name,
                    "warehouse" => $warehouse,
                    "qty" => $qty,
                    "price_buying" => $price_buying,
                    "price_selling" => $price_selling,
                    "created_date" => date("Y-m-d H:i:s"),
                    "status" => 1
                ];

                $insert_product = $this->bd->table("products")
                    ->insert($body);
            }


            $body_historial = [
                "code" => $code,
                "name" => $name,
                "warehouse" => $warehouse,
                "qty" => $qty,
                "proceso" => "entrada",
                "user" => $user,
                "created_date" => date("Y-m-d H:i:s")
            ];

            $insert_historial = $this->bd->table("historial_products")
                ->insert($body_historial);

            return ["status" => false,
                "message" => "Producto ingresado correctamente"];

        } catch (Exception $e) {
            return ["status" => false,
                "message" => "Surgió un error al insertar el producto"];
        }
    }


    public function store(Request $request)
    {
        $code = $request->code;
        $name = $request->name;
        $warehouse = $request->warehouse;
        $qty = $request->qty;
        $price_buying = $request->price_buying;
        $price_selling = $request->price_selling;
        $user = $request->user;

        if (!$code) {
            return response()->json(["status" => false,
                "message" => "Digite el codigo del producto"], 200);
        }
        if (!$name) {
            return response()->json(["status" => false,
                "message" => "Digite el nombre del producto"], 200);
        }
        if (!$warehouse) {
            return response()->json(["status" => false,
                "message" => "Digite el almacen del producto"], 200);
        }
        if (!$qty) {
            return response()->json(["status" => false,
                "message" => "Digite la cantidad del producto"], 200);
        }

        if (intval($qty) < 0) {
            return response()->json(["status" => false,
                "message" => "La cantidad del producto debe ser mayor a 0"], 200);
        }

        if (!$price_buying) {
            return response()->json(["status" => false,
                "message" => "Digite el precio de compra del producto"], 200);
        }
        if (!$price_selling) {
            return response()->json(["status" => false,
                "message" => "Digite el precio de venta del producto"], 200);
        }

        try {

            $search_product = $this->bd->table("products")
                ->where('code', $code)
                ->where('warehouse', $warehouse)
                ->get();

            if (count($search_product) > 0) {

                $qty_new = intval($search_product[0]->qty) + intval($qty);
                $body = [
                    "code" => $code,
                    "name" => $name,
                    "warehouse" => $warehouse,
                    "qty" => $qty_new,
                    "price_buying" => $price_buying,
                    "price_selling" => $price_selling,
                    "created_date" => date("Y-m-d H:i:s"),
                    "status" => 1
                ];

                $update_product = $this->bd->table("products")
                    ->where("id", $search_product[0]->id)
                    ->update($body);

            } else {
                $body = [
                    "code" => $code,
                    "name" => $name,
                    "warehouse" => $warehouse,
                    "qty" => $qty,
                    "price_buying" => $price_buying,
                    "price_selling" => $price_selling,
                    "created_date" => date("Y-m-d H:i:s"),
                    "status"=> 1
                ];

                $insert_product = $this->bd->table("products")
                    ->insert($body);
            }


            $body_historial = [
                "code" => $code,
                "name" => $name,
                "warehouse" => $warehouse,
                "qty" => $qty,
                "proceso" => "entrada",
                "user" => $user,
                "created_date" => date("Y-m-d H:i:s")
            ];

            $insert_historial = $this->bd->table("historial_products")
                ->insert($body_historial);

            return response()->json(["status" => true,
                "message" => "Producto ingresado correctamente"], 200);

        } catch (Exception $e) {
            return response()->json(["status" => false,
                "message" => "Surgió un error al insertar el producto"], 200);
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
