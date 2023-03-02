<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Repositories\ClientRepository;

class ClientController extends Controller
{
    public function __construct(Client $Client) {
        $this->Client = $Client;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $ClientRepository = new ClientRepository($this->Client);

        if($request->has('filtro')) {
            $ClientRepository->filtro($request->filtro);
        }

        if($request->has('atributos')) {
            $ClientRepository->selectAtributos($request->atributos);
        } 

        return response()->json($ClientRepository->getResultados(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->Client->rules());

        $Client = $this->Client->create([
            'nome' => $request->nome
        ]);

        return response()->json($Client, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Client  $Client
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Client = $this->Client->find($id);
        if($Client === null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe'], 404) ;
        } 

        return response()->json($Client, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $Client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $Client = $this->Client->find($id);

        if($Client === null) {
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe'], 404);
        }

        if($request->method() === 'PATCH') {

            $regrasDinamicas = array();

            //percorrendo todas as regras definidas no Model
            foreach($Client->rules() as $input => $regra) {
                
                //coletar apenas as regras aplicáveis aos parâmetros parciais da requisição PATCH
                if(array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            
            $request->validate($regrasDinamicas);

        } else {
            $request->validate($Client->rules());
        }
        
        $Client->fill($request->all());
        $Client->save();
        
        return response()->json($Client, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Client  $Client
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $Client = $this->Client->find($id);

        if($Client === null) {
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe'], 404);
        }

        $Client->delete();
        return response()->json(['msg' => 'O Client foi removido com sucesso!'], 200);
        
    }
}
