<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use App\Repositories\CarRepository;

class CarController extends Controller
{

    public function __construct(Car $Car)
    {
        $this->Car = $Car;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $CarRepository = new CarRepository($this->Car);

        if($request->has('atributos_modelos')){
            $atributos_modelos = 'CarTemplate:id,'.$request->atributos_modelos;
            $CarRepository->selectAtributosRegistrosRelacionados($atributos_modelos);
        }else{
            $CarRepository->selectAtributosRegistrosRelacionados('CarTemplate');
        }

        if($request->has('filtro')){
            $filtros = $request->filtro;
            $CarRepository->filtro($filtros);
        }

        if($request->has('atributos')){
            $atributos = $request->atributos;
            $CarRepository->selectAtributos($atributos);
        }

        return response()->json($CarRepository->getResultados(), 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCarRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->Car->rules());

        $Car = $this->Car->create([
            'car_template_id' => $request->car_template_id,
            'placa' => $request->placa,
            'disponivel' => $request->disponivel,
            'km' => $request->km
        ]);

        return response()->json($Car, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Car = $this->Car->with('CarTemplate')->find($id);
        if($Car === null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe'], 404) ;
        } 

        return response()->json($Car, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function edit(Car $car)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCarRequest  $request
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $Car = $this->Car->find($id);

        if($Car === null) {
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe'], 404);
        }

        if($request->method() === 'PATCH') {

            $regrasDinamicas = array();

            //percorrendo todas as regras definidas no Model
            foreach($Car->rules() as $input => $regra) {
                
                //coletar apenas as regras aplicáveis aos parâmetros parciais da requisição PATCH
                if(array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            
            $request->validate($regrasDinamicas);

        } else {
            $request->validate($Car->rules());
        }
        
        $Car->fill($request->all());
        $Car->save();
        
        return response()->json($Car, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $Car = $this->Car->find($id);

        if($Car === null) {
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe'], 404);
        }

        $Car->delete();
        return response()->json(['msg' => 'O Car foi removido com sucesso!'], 200);
        
    }
}
