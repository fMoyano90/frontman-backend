<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Propiedad;
use App\Helpers\JwtAuth;
use App\PropiedadImagen;

class PropiedadImagenController extends Controller
{
    public function __construct(){
        $this->middleware('api.auth', ['except' => [
            'index','getImage'
            ]]);
    }

    public function index($id){

        $propiedad = Propiedad::find($id)->load('propiedadesImagenes');

        if(is_object($propiedad)){
            $data = [
                'code' => 200,
                'status' => 'success',
                'propiedad' => $propiedad
                
            ];
        }else{
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'La propiedad no existe'
            ];
        }

        return response()->json($data, $data['code']);
    }

    public function upload($id, Request $request){
        
        // Recoger las imagenes de la petición

        $file = $request->file('file0');

            $fileName = time().$file->getClientOriginalName();

            \Storage::disk('images')->put($fileName, \File::get($file));

            $propiedadImagen = new PropiedadImagen();
            $propiedadImagen->propiedad_id = $id;
            $propiedadImagen->file_name = $fileName;
            $propiedadImagen->save();
    
            $data = [
                'code' => 200,
                'status' => 'success',
                'image' => $fileName
            ];
        

        return response()->json($data, $data['code']);
    }

    public function getImage($filename) {
        // Comprobar si existe el fichero
        $isset =  \Storage::disk('images')->exists($filename);

        if($isset) {
        // Conseguir la imagen 
        $file = \Storage::disk('images')->get($filename);
        // Devolver la imgen 
        return new Response($file, 200);

        }else{  
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'La imagen no existe'
            ];
        }
        
        return response()->json($data, $data['code']);
    }
}
