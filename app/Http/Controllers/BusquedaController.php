<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Propiedad;
use App\Helpers\JwtAuth;

class BusquedaController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.auth', ['except' => [
            'buscador'
        ]]);
    }

    public function buscador(Request $request)
    {

        $json = $request->input('json', null);
        $params_array = json_decode($json, true);
        $operacion = data_get($params_array, 'operacion');
        $titulo = data_get($params_array, 'titulo');
        $ciudad = data_get($params_array, 'ciudad');
        $codigo = data_get($params_array, 'codigo');

        if (is_null($json)) {
            return response()->json([
                'code' => 400,
                'status' => 'error',
                'mensaje' => 'No has enviado nada en la busqueda.',
            ]);
        }

        $propiedadesBuscadas = Propiedad::where("ciudad",  "LIKE", "%{$ciudad}%")
            ->where("titulo", "LIKE", "%{$titulo}%")
            ->where("operacion", "LIKE", "%{$operacion}%")
            ->where("codigo", "LIKE", "%{$codigo}%")
            ->get();

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'propiedades' => $propiedadesBuscadas,
            'operacion' => $operacion,
            'titulo' => $titulo,
            'ciudad' => $ciudad,
            'codigo' => $codigo,
        ], 200);
    }
}
