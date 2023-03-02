<?php

namespace App\Http\Controllers;

use App\Models\CarTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use App\Repositories\CarTemplateRepository;

class CarTemplateController extends Controller
{

    public function __construct(CarTemplate $CarTemplate)
    {
        $this->CarTemplate = $CarTemplate;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $CarTemplateRepository = new CarTemplateRepository($this->CarTemplate);

        if($request->has('atributos_marca')){
            $atributos_marca = 'brand:id,'.$request->atributos_marca;
            $CarTemplateRepository->selectAtributosRegistrosRelacionados($atributos_marca);
        }else{
            $CarTemplateRepository->selectAtributosRegistrosRelacionados('brand');
        }

        if($request->has('filtro')){
            $filtros = $request->filtro;
            $CarTemplateRepository->filtro($filtros);
        }

        if($request->has('atributos')){
            $atributos = $request->atributos;
            $CarTemplateRepository->selectAtributos($atributos);
        }

        return response()->json($CarTemplateRepository->getResultados(), 200);

        /*
        $CarTemplate = array();

        if($request->has('atributos_marca')){
            $atributos_marca = $request->atributos_marca;
            $CarTemplate = $this->CarTemplate->with('brand:id,'.$atributos_marca);
        }else{
            $CarTemplate = $this->CarTemplate->with('brand');
        }

        if($request->has('filtro')){

            $filtros = explode(';', $request->filtro);
            foreach($filtros as $key => $condicao){
                $c = explode(':', $condicao);
                $CarTemplate = $CarTemplate->where($c[0], $c[1], $c[2]);
            }
            
        }

        if($request->has('atributos')){
            $atributos = $request->atributos;
            $CarTemplate = $CarTemplate->selectRaw($atributos)->get();
        }else{
            $CarTemplate = $CarTemplate->get();
        }

        return response()->json($CarTemplate, 200);
        */
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
        $request->validate($this->CarTemplate->rules()); 
        
        $image = $request->file('imagem');
        $image_urn = $image->store('imagens/car_templates', 'public');

        $CarTemplate = $this->CarTemplate->create([
            'brand_id' => $request->brand_id,
            'nome' => $request->nome,
            'imagem' => $image_urn,
            'numero_portas' => $request->numero_portas,
            'lugares' => $request->lugares,
            'air_bag' => $request->air_bag,
            'abs' => $request->abs,
        ]);

        return response()->json($CarTemplate, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CarTemplate  $CarTemplate
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $CarTemplate = $this->CarTemplate->with('brand')->find($id);

        if($CarTemplate === null){ //mensagem de erro caso não exista o id pesquisado
            return response()->json(['msg' => 'Modelo pesquisada não existe!'], 404); //Status Code HTTP
        }
        
        return response()->json($CarTemplate, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CarTemplate  $CarTemplate
     * @return \Illuminate\Http\Response
     */
    public function edit(CarTemplate $CarTemplate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CarTemplate  $CarTemplate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $CarTemplate = $this->CarTemplate->find($id);

        if($CarTemplate === null){ //mensagem de erro caso não exista o id pesquisado
            //return ['msg' => 'O recurso da pesquisada não existe!'];
            return response()->json(['msg' => 'O recurso da pesquisada não existe!'], 404); //Status Code HTTP
        }

        if($request->method() === 'PATCH'){

            $regrasDinamicas = array();

            //percorrendo todas as regras definidas no Model
            foreach($CarTemplate->rules() as $input => $regra) {
                
                //coletar apenas as regras aplicáveis aos parâmetros parciais da requisição PATCH
                if(array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            
            $request->validate($regrasDinamicas);

        }else{
            $request->validate($this->CarTemplate->rules()); 
        }

        //remove o arquivo antigo caso um novo tenha sido enviado no request
        if($request->file('imagem')){
            Storage::disk('public')->delete($CarTemplate->imagem);
        }

        $image = $request->file('imagem');
        $image_urn = $image->store('imagens/car_templates', 'public');

        /*
        $CarTemplate->update([
            'brand_id' => $request->brand_id,
            'nome' => $request->nome,
            'imagem' => $image_urn,
            'numero_portas' => $request->numero_portas,
            'lugares' => $request->lugares,
            'air_bag' => $request->air_bag,
            'abs' => $request->abs,
        ]);
        */
        
        //preencher os dados de marca com o request
        $CarTemplate->fill($request->all());
        $CarTemplate->imagem = $image_urn;
        $CarTemplate->save();

        return response()->json($CarTemplate, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CarTemplate  $CarTemplate
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $CarTemplate = $this->CarTemplate->find($id);

        if($CarTemplate === null){ //mensagem de erro caso não exista o id pesquisado
            //return ['msg' => 'O recurso da pesquisada não existe!'];
            return response()->json(['msg' => 'O recurso da pesquisada não existe!'], 404); //Status Code HTTP
        }

        //remove o arquivo antigo caso um novo tenha sido enviado no request
        Storage::disk('public')->delete($CarTemplate->imagem);
        
        $CarTemplate->delete();
        return response()->json(['msg' => 'Modelo deletada com sucesso!'], 200);
    }
}
