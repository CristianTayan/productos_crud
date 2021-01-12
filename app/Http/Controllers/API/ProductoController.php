<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;
use Intervention\Image\Image;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Producto::latest()->orderBy('id')->paginate(3);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'codigo' => 'required|string|max:10|unique:productos',
            'nombre' => 'required|string|max:150',
            'descripcion' => 'required',
            'imagen' => 'required'
        ]);

        if($request->imagen){
            // Aqui se usa la librería IntervetionImage para reducir el tamaño de la image en este caso 500px como máxima medida,
            // ademas con javascript en el formulario controlamos el peso de la imagen que no sea ausperior a 2MB
            $nombreImagen = time().'.' . explode('/', explode(':', substr($request->imagen, 0, strpos($request->imagen, ';')))[1])[1];
            \Image::make($request->imagen)->resize(500, 500, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('img/').$nombreImagen);
            $request->merge(['imagen' => $nombreImagen]);

        }

        Producto::create($request->all());

        return ['message' => 'Success'];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $producto = Producto::find($id);

        $this->validate($request,[
            'codigo' => 'required|string|max:10',
            'nombre' => 'required|string|max:150',
            'descripcion' => 'required',
            'imagen' => 'required'
        ]);

        $currentPhoto = $producto->imagen;

        if($request->imagen != $currentPhoto){

            $nombreImagen = time().'.' . explode('/', explode(':', substr($request->imagen, 0, strpos($request->imagen, ';')))[1])[1];
            \Image::make($request->imagen)->resize(500, 500, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('img/').$nombreImagen);
            $request->merge(['imagen' => $nombreImagen]);

            $productoImagen = public_path('img/').$currentPhoto;

            if(file_exists($productoImagen)){

                @unlink($productoImagen);

            }

        }

        $producto->update($request->all());

        return ['message' => 'Success'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);

        $producto->delete();

        $currentPhoto = $producto->imagen;

        $productoImagen = public_path('img/').$currentPhoto;

        if(file_exists($productoImagen)) {

            @unlink($productoImagen);

        }

        return [
         'message' => 'Producto eliminado exitosamente'
        ];
    }

    public function buscarProducto(Request $request){
        $filtro = $request->buscador;
        $productos = Producto::where('nombre','LIKE','%'.$filtro.'%')->paginate(3);
        return response()->json($productos, 200);
    }
}
