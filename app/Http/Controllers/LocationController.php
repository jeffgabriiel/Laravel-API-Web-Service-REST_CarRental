<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use App\Repositories\LocationRepository;

class LocationController extends Controller
{
    public function __construct(Location $Location) {
        $this->Location = $Location;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $LocationRepository = new LocationRepository($this->Location);

        if($request->has('filtro')) {
            $LocationRepository->filtro($request->filtro);
        }

        if($request->has('atributos')) {
            $LocationRepository->selectAtributos($request->atributos);
        } 

        return response()->json($LocationRepository->getResultados(), 200);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->Location->rules());

        $Location = $this->Location->create([
            'client_id' => $request->client_id,
            'car_id' => $request->car_id,
            'data_inicio_periodo' => $request->data_inicio_periodo,
            'data_final_previsto_periodo' => $request->data_final_previsto_periodo,
            'data_final_realizado_periodo' => $request->data_final_realizado_periodo,
            'valor_diaria' => $request->valor_diaria,
            'km_inicial' => $request->km_inicial,
            'km_final' => $request->km_final
        ]);

        return response()->json($Location, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Location  $Location
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Location = $this->Location->find($id);
        if($Location === null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe'], 404) ;
        } 

        return response()->json($Location, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Location  $Location
     * @return \Illuminate\Http\Response
     */
    public function edit(Location $Location)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Location  $Location
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $Location = $this->Location->find($id);

        if($Location === null) {
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe'], 404);
        }

        if($request->method() === 'PATCH') {

            $regrasDinamicas = array();

            //percorrendo todas as regras definidas no Model
            foreach($Location->rules() as $input => $regra) {
                
                //coletar apenas as regras aplicáveis aos parâmetros parciais da requisição PATCH
                if(array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            
            $request->validate($regrasDinamicas);

        } else {
            $request->validate($Location->rules());
        }
        
        $Location->fill($request->all());
        $Location->save();
        
        return response()->json($Location, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Location  $Location
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $Location = $this->Location->find($id);

        if($Location === null) {
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe'], 404);
        }

        $Location->delete();
        return response()->json(['msg' => 'A locação foi removida com sucesso!'], 200);
        
    }
}
