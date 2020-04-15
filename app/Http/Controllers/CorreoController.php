<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mail;

class CorreoController extends Controller

{
    public function correoContacto(Request $request) {
        // Recoger los datos por POST
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);
        
        if(!$params_array){
            $data = [
                'code' => 400,
                'status' => 'error',
                'message' => 'Error al enviar correo'
            ];
        }
        else{
            // Enviar correo
            $subject = "Te han contactado desde el formulario de contacto";
            $for = 'info@frontman.cl';
            Mail::send('contacto', ['msg' => $params_array], function($msj) use ($subject, $for){
                $msj->from("info@frontman.cl", "Sitio Web");
                $msj->subject($subject);
                $msj->to($for);
            }); 

            $data = [
                'code' => 200,
                'status' => 'success',
                'correo' => $params_array
            ];
        }

         // Devolver respuesta
         return response()->json($data, $data['code']);
    }
    
    public function correoConfiarPropiedad(Request $request) {
        // Recoger los datos por POST
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);
        

        if(!$params_array){
            $data = [
                'code' => 400,
                'status' => 'error',
                'message' => 'Error al enviar correo'
            ];
        }else{
        // Enviar correo
        $subject = "Quieren confiarte una propiedad";
        $for = 'info@frontman.cl';
        Mail::send('propiedad', ['msg' => $params_array], function($msj) use ($subject, $for){
            $msj->from("feedback@frontman.cl", "Sitio Web");
            $msj->subject($subject);
            $msj->to($for);
        }); 

        $data = [
            'code' => 200,
            'status' => 'success',
            'correo' => $params_array
        ];

        }
        // Devolver respuesta
        return response()->json($data, $data['code']);
    } 

}