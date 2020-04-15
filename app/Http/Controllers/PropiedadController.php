<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Propiedad;
use App\Helpers\JwtAuth;
use App\PropiedadImagen;

class PropiedadController extends Controller
{
    public function __construct(){
        $this->middleware('api.auth', ['except' => [
            'index',
            'show',
            'getImage',
            'getPropiedadesByCategory',
            'buscador'
            ]]);
    }

    public function index(){
        $propiedades = Propiedad::all()->load('category');

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'propiedades' => $propiedades
        ], 200);
    }

    public function show($id){
        $propiedad = Propiedad::find($id)->load('category');

        if(is_object($propiedad)){
            $data = [
                'code' => 200,
                'status' => 'success',
                'propiedades' => $propiedad
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

    public function store(Request $request){
        // Recoger datos por post 

        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        if(!empty($params_array)){
        // Conseguir el usuario identificado 
        $user = $this->getIdentity($request);
        // Validar los datos 
        $validate = \Validator::make($params_array, [
            'titulo'    => 'required',
            'operacion' => 'required',
            'ciudad'    => 'required',
            'precio'    => 'required',
            'content'   => 'required'
        ]);
        
        if($validate->fails()){
            $data = [
                'code' => 400,
                'status' => 'error',
                'message' => 'No se ha guardado la propiedad, faltan datos'
            ];
        }else{

            // Guardar la propiedad
            $propiedad = new Propiedad();
            $propiedad->category_id    = $params->category_id;
            $propiedad->codigo         = $params->codigo;
            $propiedad->titulo         = $params->titulo;
            $propiedad->operacion      = $params->operacion;
            $propiedad->ciudad         = $params->ciudad;
            $propiedad->precio         = $params->precio;
            $propiedad->mtstotales     = $params->mtstotales;
            $propiedad->mtsconstruidos = $params->mtsconstruidos;
            $propiedad->dormitorios    = $params->dormitorios; 
            $propiedad->banos          = $params->banos; 
            $propiedad->direccion      = $params->direccion; 
            $propiedad->piscina        = $params->piscina;
            $propiedad->bodega         = $params->bodega; 
            $propiedad->logia          = $params->logia; 
            $propiedad->content        = $params->content;
            $propiedad->image          = $params->image;       
            $propiedad->image1         = $params->image1;       
            $propiedad->image2         = $params->image2;       
            $propiedad->image3         = $params->image3;       
            $propiedad->image4         = $params->image4;       
            $propiedad->image5         = $params->image5;       
            $propiedad->image6         = $params->image6;       
            $propiedad->image7         = $params->image7;       
            $propiedad->image8         = $params->image8;       
            $propiedad->image9         = $params->image9;       
            

            $propiedad->save();

            $data = [
                'code' => 200,
                'status' => 'success',
                'propiedad' => $propiedad
            ];
        }
        
      }else{  
        $data = [
            'code' => 404,
            'status' => 'error',
            'message' => 'Envia los datos correctamente'
        ];
      }
        // Devolver respuesta 
        return response()->json($data, $data['code']);
    }

    public function update3($id, Request $request){
        // Recoger los datos por post 
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        if(!empty($params_array)){
        // Validar los datos 
        $validate = \Validator::make($params_array, [
            'name' => 'required'
        ]);

        // Quitar lo que no quiero actualizar 
        unset($params_array['id']);
        unset($params_array['created_at']);
        
        // Actualizar categoria 

        $category = Category::where('id', $id)->update($params_array);

        $data = [
            'code' => 200, 
            'status' => 'success',
            'category' => $params_array
        ];
         
        }else{
            $data = [
                'code' => 400, 
                'status' => 'error',
                'message' => 'No has enviado ninguna categoria'
            ];
        }
        // Devolver respuesta

        return response()->json($data, $data['code']);
    }

    public function update($id, Request $request) {
        // Recoger datos por post
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        if(!empty($params_array)){
            // Validar datos
            $validate = \Validator::make($params_array, [
                'titulo'    => 'required',
                'operacion' => 'required',
                'ciudad'    => 'required',
                'precio'    => 'required',
            ]);

            
            if($validate->fails()){
                $data['errors'] = $validate->errors();
                return response()->json($data, $data['code']);
            }

            // Eliminar lo que no queremos actualizar 

            unset($params_array['id']);
            unset($params_array['created_at']);

            //Conseguir usuario identificado
            $user = $this->getIdentity($request);
            
            // Buscar el registro 
            $propiedad =  Propiedad::where('id', $id)
                            ->first();

            if(!empty($propiedad) && is_object($propiedad)){
                // Actualizar el registro     
                $propiedad->update($params_array);

                // Devolver un resultado
                    $data = [
                        'code'      => 200,
                        'status'    => 'success',
                        'propiedad' => $propiedad,
                        'cambios'   => $params_array
                    ];
            }

            /*  
            $where = [
                'id' => $id,
                'user_id' => $user->sub
            ];

            $post = Post::updateOrCreate($where, $params_array);
             */

        }

        return response()->json($data, $data['code']);
    }

    public function destroy($id, Request $request){
        //Conseguir usuario identificado
        $user = $this->getIdentity($request);

        // Conseguir la propiedad
        $propiedad =  Propiedad::where('id', $id)->first();      
        $propiedadImagen = PropiedadImagen::where('propiedad_id', $id)->delete();

        if(!empty($propiedad)){
            // Borrar propiedad
            $propiedad->delete();

            // Devolver algo 
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

    private function getIdentity($request){
        // Conseguir usuario identificado
        $jwtAuth = new JwtAuth();
        $token = $request->header('Authorization', null);
        $user = $jwtAuth->checkToken($token, true);

        return $user;
    }


    public function getPropiedadesByCategory($id){
        $propiedades = Propiedad::where('category_id', $id)->get();

        return response()->json([
            'status' => 'success',
            'propiedades' => $propiedades
        ], 200);
        
    }

    public function uploadPrincipal(Request $request){
        // Recoger la imagen de la petición
        
        $image = $request->file('file0');

        // Validar la imagen
        $validate = \Validator::make($request->all(), [
            'file0' => 'required|image|mimes:jpg,jpeg,png,gif',
        ]);

        // Guardar la imagen 
        if(!$image || $validate->fails()){
            $data = [
                'code' => 400,
                'status' => 'error',
                'message' => 'Error al subir imagen'
            ];
        }else{
            $image_name = time().$image->getClientOriginalName();
            
            \Storage::disk('images')->put($image_name, \File::get($image));

            $data = [
                'code' => 200,
                'status' => 'success',
                'image' => $image_name,
            ];
        }
        // Devolver respuesta
        return response()->json($data, $data['code']);
            
    }
   
    public function upload(Request $request){
        // Recoger la imagen de la petición
        
        $image1 = $request->file('file0');
        $image2 = $request->file('file1');
        $image3 = $request->file('file2');
        $image4 = $request->file('file3');
        $image5 = $request->file('file4');
        $image6 = $request->file('file5');
        $image7 = $request->file('file6');
        $image8 = $request->file('file7');
        $image9 = $request->file('file8');

        // Validar la imagen
        $validate = \Validator::make($request->all(), [
            'file0' => 'image|mimes:jpg,jpeg,png,gif',
            'file1' => 'image|mimes:jpg,jpeg,png,gif',
            'file2' => 'image|mimes:jpg,jpeg,png,gif',
            'file3' => 'image|mimes:jpg,jpeg,png,gif',
            'file4' => 'image|mimes:jpg,jpeg,png,gif',
            'file5' => 'image|mimes:jpg,jpeg,png,gif',
            'file6' => 'image|mimes:jpg,jpeg,png,gif',
            'file7' => 'image|mimes:jpg,jpeg,png,gif',
            'file8' => 'image|mimes:jpg,jpeg,png,gif',
            'file9' => 'image|mimes:jpg,jpeg,png,gif'
        ]);

        // Guardar la imagen 
        if($validate->fails()){
            $data = [
                'code' => 400,
                'status' => 'error',
                'message' => 'Error al subir imagen'
            ];
        }else{
            $image_name1 = time().$image1->getClientOriginalName();
            $image_name2 = time().$image2->getClientOriginalName();
            $image_name3 = time().$image3->getClientOriginalName();
            $image_name4 = time().$image4->getClientOriginalName();
            $image_name5 = time().$image5->getClientOriginalName();
            $image_name6 = time().$image6->getClientOriginalName();
            $image_name7 = time().$image7->getClientOriginalName();
            $image_name8 = time().$image8->getClientOriginalName();
            $image_name9 = time().$image9->getClientOriginalName();
            
            \Storage::disk('images')->put($image_name1, \File::get($image1));
            \Storage::disk('images')->put($image_name2, \File::get($image2));
            \Storage::disk('images')->put($image_name3, \File::get($image3));
            \Storage::disk('images')->put($image_name4, \File::get($image4));
            \Storage::disk('images')->put($image_name5, \File::get($image5));
            \Storage::disk('images')->put($image_name6, \File::get($image6));
            \Storage::disk('images')->put($image_name7, \File::get($image7));
            \Storage::disk('images')->put($image_name8, \File::get($image8));
            \Storage::disk('images')->put($image_name9, \File::get($image9));

            $data = [
                'code' => 200,
                'status' => 'success',
                'image1' => $image_name1,
                'image2' => $image_name2,
                'image3' => $image_name3,
                'image4' => $image_name4,
                'image5' => $image_name5,
                'image6' => $image_name6,
                'image7' => $image_name7,
                'image8' => $image_name8,
                'image9' => $image_name9
            ];
        }
        // Devolver respuesta
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
